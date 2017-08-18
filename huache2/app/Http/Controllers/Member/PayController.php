<?php
namespace App\Http\Controllers\Member;

use App\Core\Contracts\Money\Money;
use App\Events\CheckBaojiaEvent;
use App\Http\Controllers\Controller;
use App\Models\HcUserAccountLog;
use Illuminate\Http\Request;
use App\Models\HgOrder;
use App\Repositories\Contracts\HcUserRechargeRepositoryInterface;
use App\Models\HcVoucher;
use App\Models\HcUserAccount;
use App\Models\HgBaojia;
use App\Models\HcPayDeposit;
use Carbon\Carbon;
use App\Models\SendSmsLog;

use Facades\App\Com\Hwache\Jiaxinbao\Account as Jxb;//加信宝
use Facades\App\Com\Hwache\Vouchers\DJQ;//代金券
use App\Repositories\Contracts\HcAccountLogRepositoryInterface;

class PayController extends Controller
{
    protected $account;

    function __construct(Money $money, HcUserRechargeRepositoryInterface $recharge, HcAccountLogRepositoryInterface $account)
    {
        $this->money = $money;
        $this->recharge = $recharge;
        $this->account = $account;
    }

    //支付诚意金页面
    public function payEarnest(Request $request, $id)
    {
        //查询用户账户余额
        $user_id = $request->user()->id;
        $user_account = HcUserAccount::firstOrCreate(['user_id' => $user_id])->toArray();

        //查询用户可用优惠券
        $vouchers = HcVoucher::getVoucherByUserId($user_id);

        $data = ['order_id' => $id,
            'phone' => $request->user()->phone,
            'user_account' => $user_account,
            'voucher' => $vouchers['voucher'],
            'vouchers_default' => $vouchers['vouchers_default'],
            'vouchers_max_price' => $vouchers['vouchers_max_price']
        ];

        //诚意金余额支付不足
        if ($vouchers['vouchers_max_price'] + $user_account['avaliable_deposit'] < 499) {
            //计算线上充值金额总额
            $data['charge_online_limit'] = HcUserAccountLog::getChargeLimitOnline($user_id);
            return view('HomeV2.User.Earnest.charge', $data);
        }

        //诚意金余额支付充足
        return view('HomeV2.User.Earnest.pay', $data);
    }

    //余额支付诚意金
    function payEarnestByBalance(Request $request)
    {
        //判断是否工作时间下单
        if (!check_job_time()) {
            return view('HomeV2.User.Earnest.buy_fail');
        }

        $money = 0;
        $order_id = $request->input('order_id');
        $order = HgOrder::find($order_id);

        //判断报价车源数量
        $baojia = HgBaojia::getBaojiaInfo($order['bj_id']);
        if (!$baojia || $baojia->bj_num <= 0) {
            return view('HomeV2.User.Earnest.pay_fail');
        }

        //判断是否商家工作时间
        if (!check_daili_worktime($order['daili_dealer_id'])) {
            return view('HomeV2.User.Earnest.pay_fail');
        }

        $voucher = null;
        if ($voucher_id = $request->input('voucher_id')) {
            $voucher = HcVoucher::find($voucher_id);
            $money = $voucher->money;
            //使用代金券
            DJQ::useVouchers($voucher_id, $order_id, $money);
        }

        //使用余额支付诚意金
        if ($money < 499) {
            $response = $this->money->payEarnest($request->user()->id, $order->seller_id, $order_id, 499 - $money);
            if ($response) {
                if($voucher){
                    //添加代金券平台账户资金流动日志
                    $this->account->addPayLog($order, $money, $voucher_id);
                }

                //添加支付诚意金日志
                $this->account->addPayLog($order, 499 - $money);

                //根据上牌逻辑判断倒计时长
                $show_status = $order->orderAttr->show_status;
                if ($show_status != 3 || (!$order->special_file)) {
                    $endtime = Carbon::now()->addMinutes(20);
                    $order_state = config('orders.order_earnest_not_confirm');
                } else {
                    $endtime = Carbon::now()->addHours(24);
                    $order_state = config('orders.order_earnest_not_confirm_file');
                }

                $order->rockon_time = $endtime;
                $order->order_state = $order_state;
                $order->save();
                $order->addLog('member', $order->order_status, $order->order_state, trans('orders.log.sinceritygold'));

                //报价后续事件操作
                event(new CheckBaojiaEvent($baojia));

                $user_account = HcUserAccount::getUserAccountById($request->user()->id);
                return view('HomeV2.User.Earnest.pay_ok', ['id' => $order_id, 'time' => $user_account->updated_at]);
            } else {
                return view('HomeV2.User.Earnest.pay_fail');
            }
        }

        return view('HomeV2.User.Earnest.pay_ok');
    }

    //线上充值支付诚意金
    function payEarnestByCharge(Request $request)
    {
        //todo 暂不进行支付宝跳转，只编码线上充值成功后的逻辑
        $money = $request->input('payprice');
        $order_id = $request->input('order_id');

        $response = $this->money->getOnTopUp($request->user()->id, $money, $order_id);
        if ($response) {
            $user_account = HcUserAccount::getUserAccountById($request->user()->id);
            return view('HomeV2.User.Earnest.charge_ok', ['id' => $order_id, 'money' => $money, 'time' => $user_account->updated_at]);
        }

        return view('HomeV2.User.Earnest.charge_fail', ['id' => $order_id, 'money' => $money]);
    }

    //支付担保金
    function payDeposit(Request $request, $id)
    {
        $order = HgOrder::find($id);
        $user_id = $request->user()->id;
        $user_account = HcUserAccount::getUserAccountById($user_id)->toArray();
        $pay_log = $order->payDepositLog;

        if ($request->isMethod('post')) {
            if ($pay_log)
                $money = $pay_log->non_payment <= $user_account['avaliable_deposit'] ? $pay_log->non_payment : $user_account['avaliable_deposit'];
            else
                $money = $order['sponsion_price'] - $order['earnest_price'] <= $user_account['avaliable_deposit'] ? $order['sponsion_price'] - $order['earnest_price'] : $user_account['avaliable_deposit'];

            //使用代金券支付
            $voucher = null;
            if ($voucher_id = $request->input('voucher_id', 0)) {
                $voucher = HcVoucher::find($voucher_id);
                $money = $voucher->money;
                DJQ::useVouchers($voucher_id, $id, $money);
            }

            $response = $this->money->payDeposit($user_id, $money, $id, $voucher_id, 4);
            if ($response['success']) {
                //加信宝
                //todo  加信宝判断有问题
            //    Jxb::addUserJxb($id, $money, $voucher, 5);

                //添加支付担保金平台账户资金流动日志
                $this->account->addPayLog($order, $money, $voucher_id, 2);

                if($response['is_pay_all']){
                    //设置交车时间
                    if ($order->orderBaojia->bj_is_xianche) {
                        $times = Carbon::now()->addDay(15 + $order->orderAttr->new_file_days);
                    } else {
                        $times = Carbon::now()->addMonth($order->orderBaojia->bj_jc_period)->addDay($order->orderAttr->new_file_days);
                    }
                    $order->orderinfo()->update(['car_astrict' => $times]);
                    $order->orderDate()->create([
                        'jiaoche_at' => date('Y-m-d', strtotime($times)) . ' 23:59:59'
                    ]);
                    //订单日志
                    $order->addLog('member', $order->order_status, $order->order_state, trans('orders.log.securityintojiaxinbao'));
                }
                app(SendSmsLog::class)->sendSms(
                    $order->orderMember->member_mobile, 78580069,
                    [
                        'order' => $order->order_sn,
                        'day'   => date('Y年m月d日', strtotime($times))
                    ]
                );

                if ($voucher_id && $response['success']) {// 如果使用代金券支付完成担保金，则跳转到等待交车邀请页面
                    return redirect()->route('cart.editcar', ['id' => $id]);
                }

                return view('HomeV2.User.Deposit.pay_ok', ['id' => $id, 'time' => $user_account['updated_at'], 'money' => $money,'is_pay_all'=>$response['is_pay_all']]);
            } else {
                return view('HomeV2.User.Deposit.pay_fail', ['id' => $id, 'money' => $money,'is_pay_all'=>$response['is_pay_all']]);
            }
        }

        $pay_time_limit = empty($pay_log) ? date("Y-m-d H:i:s", strtotime($order['updated_at']) + 24 * 3600) : date("Y-m-d", strtotime($order['updated_at']) + 24 * 3600 * 3) . " 24:00:00";

        return view('HomeV2.User.Deposit.pay', ['order' => $order->toArray(), 'account' => $user_account, 'phone' => $request->user()->phone, 'pay_time_limit' => $pay_time_limit]);
    }

    //线上充值支付担保金
    function payDepositOnline(Request $request, $id)
    {
        //查询用户账户余额
        $user_id = $request->user()->id;
        $user_account = HcUserAccount::firstOrCreate(['user_id' => $user_id])->toArray();

        //todo 暂不进行支付宝跳转，只编码线上充值成功后的逻辑
        if ($request->isMethod('post')) {
            $money = $request->input('payprice', 0);
            //线上充值
            $response = $this->money->getOnTopUp($user_id, $money, $id);
            if ($response) {
                $user_account = HcUserAccount::getUserAccountById($request->user()->id);
                return view('HomeV2.User.Deposit.online_ok', ['money' => $money, 'time' => $user_account->updated_at, 'id' => $id]);
            } else {
                return view('HomeV2.User.Deposit.online_fail', ['money' => $money, 'id' => $id]);
            }
        }

        //查询用户账户余额
        $data['user_account'] = $user_account;
        $data['phone'] = $request->user()->phone;
        $order = HgOrder::find($id);
        $pay_log = $order->payDepositLog;

        $data['non_payment'] = empty($pay_log) ? $order->sponsion_price - $order->earnest_price : $pay_log->non_payment;
        $data['pay_time_limit'] = empty($pay_log) ? date("Y-m-d H:i:s", strtotime($order['updated_at']) + 24 * 3600) : date("Y-m-d", strtotime($order['updated_at']) + 24 * 3600 * 3) . " 24:00:00";
        $data['user_account'] = HcUserAccount::firstOrCreate(['user_id' => $request->user()->id]);
        $data['order'] = $order->toArray();

        return view('HomeV2.User.Deposit.online', $data);
    }

    //线下充值支付担保金页面
    function payDepositOffline(Request $request, $id)
    {
        $order = HgOrder::find($id);
        $pay_log = $order->payDepositLog;

        $data['phone'] = $request->user()->phone;
        $data['non_payment'] = empty($pay_log) ? $order->sponsion_price - $order->earnest_price : $pay_log->non_payment;
        $data['pay_time_limit'] = empty($pay_log) ? date("Y-m-d H:i:s", strtotime($order['updated_at']) + 24 * 3600) : date("Y-m-d", strtotime($order['updated_at']) + 24 * 3600 * 3) . " 24:00:00";
        $data['user_account'] = HcUserAccount::firstOrCreate(['user_id' => $request->user()->id]);
        $data['order'] = $order->toArray();

        return view('HomeV2.User.Deposit.offline', $data);
    }

    function payDepositReceipt(Request $request, $id)
    {
        if ($request->ismethod('post')) {
            $money = $request->input('payprice', 0);
            $response = $this->money->getontopup($money);
            if ($response) {
                $user_account = hcuseraccount::getuseraccountbyid($request->user()->id);
                return view('homev2.user.deposit.offline_ok', ['money' => $money, 'time' => $user_account->updated_at]);
            } else {
                return view('homev2.user.deposit.offline_fail', ['money' => $money]);
            }
        }

        $order = hgorder::find($id);
        $pay_log = $order->paydepositlog;
        $data['phone'] = $request->user()->phone;

        $data['non_payment'] = empty($pay_log) ? $order->sponsion_price - $order->earnest_price : $pay_log->non_payment;
        $data['pay_time_limit'] = empty($pay_log) ? date("y-m-d h:i:s", strtotime($order['updated_at']) + 24 * 3600) : date("y-m-d", strtotime($order['updated_at']) + 24 * 3600 * 3) . " 24:00:00";
        $data['user_account'] = hcuseraccount::firstorcreate(['user_id' => $request->user()->id]);
        $data['order'] = $order->toarray();

        return view('homev2.user.deposit.voucher_recharge', $data);
    }

    //线上充值
    function rechargeOnline(Request $request)
    {
        //TODO 暂不进行支付宝跳转，只编码线上充值成功后的逻辑
        if ($request->isMethod('post')) {
            $money = $request->input('payprice', 0);
            $paymethod = $request->input('paymethod', 0);
            //将类型转换为文字
            $paymethod_word  = $this->_get_paymethod($paymethod);
            $payment  = [
                'account'   => '123456', //TODO  线上支付账号
                'paymethod' =>  $paymethod,
                'payment_word' => $paymethod_word
            ];
            $response = $this->money->rechargeOnline($request->user()->id, $money, $payment);
            if ($response) {
                $user_account = HcUserAccount::getUserAccountById($request->user()->id);
                return view('HomeV2.User.Online.recharge_ok', ['money' => $money, 'paymethod'=>$paymethod_word ,'phone'=> $request->user()->phone ,'time' => $user_account->updated_at]);
            } else {
                return view('HomeV2.User.Online.recharge_fail', ['money' => $money ,'paymethod'=>$paymethod_word, 'phone'=> $request->user()->phone ]);
            }
        }

        //查询用户账户余额
        $user_id = $request->user()->id;
        $user_account   =   HcUserAccount::where('user_id',$user_id)->first();
        //没有账户，则创建一个
        if(!$user_account) {
            $user_account = new HcUserAccount();
            $user_account->user_id = $user_id;
            $user_account->total_deposit = 0;
            $user_account->avaliable_deposit = 0;
            $user_account->temp_deposit = 0;
            $user_account->freeze_deposit = 0;
            $user_account->status = 1;
            $user_account->save();
            //保存之后 user_id丢失，重新赋值一下
            $user_account->user_id = $user_id;
        }
        $data['user_account'] = $user_account->toArray();

        //计算线上充值金额总额
        $data['charge_online_limit'] = HcUserAccountLog::getChargeLimitOnline($user_id);

        $data['phone'] = $request->user()->phone;

        return view('HomeV2.User.Online.recharge', $data);
    }

    /** 充值页面
     * @param Request $request
     * @param string $voucher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function payRecharge(Request $request,$receipt='')
    {
        $order_id = $request->get('order',0);
        $userAccount = HcUserAccount::firstOrCreate(['user_id'=>$request->user()->id]);
        $userAccount->order_id = $order_id;
        if($receipt =='receipt')
            return view('HomeV2.User.Order.voucher_recharge',['account'=>$userAccount,'title'=>'账户充值-上传凭证']);
        else
            return view('HomeV2.User.Order.bank_recharge',['account'=>$userAccount,'title'=>'账户充值']);
    }

    /**
     * 上传充值凭证
     * @param Request $request
     */
    public function postReceipt(\App\Http\Requests\UserRechargeRequest $request)
    {
        if ($request->isMethod('post')) {
            $rule = [
                'bank_code' => 'required|max:40',
                'bank_name' => 'required',
                'user_name' => 'required',
                'price' => 'required|numeric',
                'bank_voucher' => 'required|image'
            ];
            $this->validate($request, $rule);
            $res = $request->saveBankVoucher();
            $error = ['money' => base64_encode($request->input('price')), 'order' => base64_encode($request->input('order_id'))];
            return ($res) ? setJsonMsg(1, '充值成功！', ['id' => base64_encode($res)]) : setJsonMsg(0, '充值失败！', $error);
        }
    }

    /**
     * 支付结果页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function payResult(Request $request)
    {
        $id = 0;
        if ($request->has('id'))
            $id = (int)base64_decode($request->get('id'));

        if ($id > 0) {
            $rechargeFind = $this->recharge->first(['ur_id' => $id]);
            return view('HomeV2.User.Order.recharge_success', $rechargeFind);
        } else {
            if ($request->has('m') && $request->has('order')) {
                $money = (int)base64_decode($request->get('m'));
                $order = (int)base64_decode($request->get('order'));
                return view('HomeV2.User.Order.recharge_error', ['money' => $money, 'order_id' => $order]);
            } else {
                abort('404');
            }
        }
    }

    //将支付类型转化为文字
    private function _get_paymethod($type = 0 )
    {   
        $return = '';
        switch($type)
        {
            case 0 : $return = "支付宝"; break;
            case 1 : $return = "微信支付"; break;
            case 2 : $return = "财付通"; break;
            default:;
        }
        return $return;
    }
}
