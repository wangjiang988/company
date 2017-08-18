<?php
/**
 *
 * User: S4p3r
 * Date: 2017/3/28
 * Time: 10:31
 */

namespace App\Http\Controllers\Orders;

//担保金相关
use App\Http\Controllers\Controller;
use App\Models\HgDailiDealer;
use App\Models\HgEditInfo;
use App\Models\SendSmsLog;
use App\Services\NegotiateAtrait;
use Illuminate\Support\Facades\App;
use App\Models\HgBaojia;
use App\Models\HgCarInfo;
use App\Models\HgBaojiaXzj;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;
use App\Models\HcPayDeposit;
use App\Models\HcUserAccount;
use App\Models\HcVoucher;

class GuaranteesController extends Controller
{
    use NegotiateAtrait;
    //跳转担保金页面
    public function payMemberGuarantee($order)
    {
        //查询担保金支付记录数据
        $pay_log = HcPayDeposit::find($order->id);
        $user_account = HcUserAccount::firstOrCreate(['user_id' => $order->user_id])->toArray();

        //查询用户可用优惠券
        $vouchers = HcVoucher::getVoucherByUserId($order->user_id, 1);
        if (empty($pay_log)) {//第一次支付
            //查询用户是否有可用代金券
            if ($vouchers['voucher']) {//场景5，优先使用代金券
                $mode = 5;
            } else {
                //场景1，使用可用余额全部支付担保金
                if ($user_account['avaliable_deposit'] >= $order->sponsion_price)
                    $mode = 1;
                //场景2，充值
                if ($user_account['avaliable_deposit'] == 0)
                    $mode = 2;
                //场景3，使用可用余额先支付部分担保金
                if ($user_account['avaliable_deposit'] > 0 && $user_account['avaliable_deposit'] < $order->sponsion_price)
                    $mode = 3;
            }
        } else {
            //场景6，使用代金券; 场景4，再次支付
            $mode = $vouchers['voucher'] && $pay_log['voucher_id']==0  ? 6 : 4;
        }

        return view('cart.Sellerpayguaran')->with([
            'order' => $order->toArray(),
            'order_info' =>  $order->orderinfo->first()->toArray(),
            'pay_log' => $pay_log,
            'user_account' => $user_account,
            'voucher' => $vouchers['voucher'],
            'vouchers_default' => $vouchers['vouchers_default'],
            'vouchers_max_price' => $vouchers['vouchers_max_price'],
            'mode' => $mode,
            'pay_time_limit' => empty($pay_log)?date("Y-m-d H:i:s",strtotime($order['updated_at'])+24*3600):date("Y-m-d",strtotime($order['updated_at'])+24*3600*3)." 24:00:00"
        ]);
    }

    //刚付担保金300
    public function getMemberFile($order)
    {
        return view('cart.Shellcarawait')->with(compact('order'));
    }

    //301
    public function getMemberAwait($order)
    {
        return view('cart.Shellcarawait')->with(compact('order'));
    }


    //管理控制 暂停时间
    public function getMemberAdmin($order)
    {
        return view('cart.Shellcarawait')->with(compact('order'));
    }

    //平台判定担保金违约终止
    public function getMemberDopositEnd($order)
    {
        $order->load(['orderjiaxinbao'=>function($query) {
            $query->where('role',1);
        }]);
        $order->load(['orderAccount'=>function($query){
            $query->where('from_where',1);
        }]);
        //取结算数据具体金额
        $settlement = $order->limitConciliation(3);
        return view('cart.Member_trial_end')->with(compact('order','settlement'));
    }


    //售方有修改
    public function getMemberConfirma($order)
    {
        //时间判断,如果超过交车邀请时间就自动终止
        //交车时间加上特需文件时间减7
        $this->setForTime($order,config('orders.order_doposit_not_edit'));   //超时终止默认就授权为客户终止
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $edit_result = HgEditInfo::getEditInfo($order->order_sn, 301);
        return view('cart.Shellpayedit',$view, $edit_data)->with(compact('order','edit_result'));
    }

    //售方主动终止
    public function getMemberTaskEnd($order)
    {
        return view('cart.Shellpaytaskend')->with(compact('order'));
    }

    //售方修改客户不接受
    public function getMemberRefuse($order)
    {
        return view('cart.Shellpayrefuse')->with(compact('order'));
    }

    //担保金 超时主动终止 304
    public function getMemberDopositTimeOut($order)
    {
        return view('cart.Shellpaytimeout')->with(compact('order'));
    }


    //=============================经销商部分=========================\\
    //300支付担保金
    public function paySellGuarantee($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        $view['car'] = HgEditInfo::getEditInfo($order->order_sn, 201);
        return view('dealer.orders.order_details_security',$view)->with(compact('order'));
    }

    // 300本页面出现条件是：存在特别文件办理、或有非原厂选装精品。
    //301 本页面出现条件是：1.不存在特别文件办理、且没有非原厂选装精品。
    //2.在OS.X.2A页面已经选择提交过：特别文件客户联系方式、推荐非原厂选装精品。

    public function getSellFile($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        return view('dealer.orders.order_security_two',$view,$edit_data)->with(compact('order','edit_data'));
    }

    public function getSellAwait($order)
    {
        //两种情况,1不存在特别文件办理
        $view = $this->getData($order->bj_id,$order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $time = $this->setJiaoche($order);
        $work = $this->setWorkJiaoche($order);
        $colors = $this->getAllColor($order);
        //特别文件跳转过来的
        //判断时间超时情况
        $this->setForTime($order,config('orders.order_doposit_timeout'));
        return view('dealer.orders.order_security_first',$view,$edit_data)
                ->with(compact('order','time','colors','work'));
    }

    //售房修改订单待确认
    public function getSellConfirma($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $edit_data = HgEditInfo::getEditInfo($order->order_sn, 201);
        $edit_result = HgEditInfo::getEditInfo($order->order_sn, 301);
        $time = $this->setJiaoche($order);
        return view('dealer.orders.order_security_confirma', $view, $edit_data)
            ->with(compact('order','edit_result','time'));
    }

    // 后台判定担保金违约终止
    public function getSellDopositEnd($order)
    {
        $view = $this->getData($order->bj_id, $order->brand_id);
        $order->load([
            'orderjiaxinbao' => function ($query) {
                $query->where('role', 2);
            }
        ]);
        $order->load([
            'orderAccount' => function ($query) {
                $query->where('from_where', 2);
            }
        ]);
        //取结算数据具体金额
        $settlement = $order->limitConciliation(3);
        return view('dealer.orders.order_trial_end',
            $view)->with(compact('order', 'settlement'));
    }

    //向管理提交向后延迟交车,暂停计时
    public function getSellAdmin($order)
    {
        return $this->getSellAwait($order);
    }

    //售方主动终止
    public function getSellTaskEnd($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        return view('dealer.orders.order_security_task_end',$view)->with(compact('order'));
    }

    //客户不接受修改而终止
    public function getSellRefuse($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        return view('dealer.orders.order_security_member_refuse',$view)->with(compact('order'));
    }

    //交车超时终止
    public function getSellDopositTimeOut($order)
    {
        $view = $this->getData($order->bj_id,$order->brand_id);
        return view('dealer.orders.order_security_timeout',$view)->with(compact('order'));
    }


    /**
     * @param $bj_id
     * @param $brand_id
     * 数据读取
     * @return mixed
     */
    public function getData($bj_id,$brand_id)
    {
        $view['baojia'] = App(HgBaojia::class)->getBaojiaData($bj_id);
        $view['car_info'] = HgCarInfo::getInteriorColor($bj_id, $brand_id);
        $view['originals'] = HgBaojiaXzj::getXzjType($bj_id);
        return $view;
    }


    //超时的判断
    public function setForTime($order,$state)
    {
        $date = $order->orderDate->jiaoche_at;
        //超时,自动终止订单
        if (Carbon::now()->addDays($order->orderAttr->new_file_days+7) > $date && $order->orderDate->status)
        {
            $order->order_state = $state;
            $order->save();
            $order->addLog('seller',
                $order->order_status,
                $order->order_state,
                '倒计时超时,自动终止'
            );
            //发送短信通知
            app(SendSmsLog::class)->sendSms(
                $order->orderMember->member_mobile,
                78770091,
                ['order' => $order->order_sn]
            );
            return redirect()->back();
        }
    }


    //非原创选装件接口数据
    public function getNotOptions($daili_dealer_id,$brand_id,$type = null)
    {
        if ($type == 'all') {
            $result = HgDailiDealer::getOptionAlls($daili_dealer_id,$brand_id);
        }else {
            $result = HgDailiDealer::getOptionLists($daili_dealer_id,$brand_id);
        }
        if (count($result)>0) {
            return response()->json($result);
        }
        return response()->json(['error_response'=>['code'=>404,'msg'=>'NotFound']]);
    }

    //交车时间逻辑部分
    public function setJiaoche($order)
    {
        //特需文件时间
        $file_days = $order->orderAttr->new_file_days;
        $date = Carbon::parse($order->orderDate->jiaoche_at);
        $end_date = $date->addDays($file_days)->toDateTimeString();
        if ($order->orderBaojia->bj_is_xianche) {
            $start_date = $date->addDays(-14)->toDateTimeString();
        } else {
            $start_date = (Carbon::now()->addDay(15) > $end_date) ?
                $date->addDays(-15)->toDateTimeString() :
                $start_date = Carbon::now()->toDateTimeString();
        }
        while ($start_date <= $end_date) {
            $year = Carbon::parse($start_date)->year;
            $day = Carbon::parse($start_date)->day;
            $month = Carbon::parse($start_date)->month;
            $week = Carbon::parse($start_date)->dayOfWeek;
            $now_day = Carbon::now()->day;
            $now_month = Carbon::now()->month;
            $type = $month > $now_month || ($months = $now_month && $day > $now_day) ? false : true;
            $data[] = [
                'disabled'    => $type,
                'year'        => $year,
                'month'       => $month,
                'day'         => $day,
                'week'        => $week,
                'monthSelect' => false,
                'daySelect'   => false,
                'amSelect'    => false,
                'pmSelect'    => false,
            ];

            $start_date = Carbon::parse($start_date)->addDays(1);
        }

        return $data;
    }


    //工作时间逻辑部分
    public function setWorkJiaoche($order)
    {
        //特需文件时间
        $file_days = $order->orderAttr->new_file_days;
        $date = Carbon::parse($order->orderDate->jiaoche_at);
        $end_date = $date->addDays($file_days)->toDateTimeString();
        if ($order->orderBaojia->bj_is_xianche) {
            $start_date = $date->addDays(-14)->toDateTimeString();
        } else {
            $start_date = (Carbon::now()->addDay(15) > $end_date) ?
                $date->addDays(-15)->toDateTimeString() :
                $start_date = Carbon::now()->toDateTimeString();
        }
        //格式化时间去时分秒
        //dd(Carbon::parse('2017-04-16')->dayOfWeek);
        $start_date_tmp = Carbon::parse($start_date)->toDateString();
        $end_date_tmp = Carbon::parse(date('Y-m-d', strtotime($end_date)));
        if ($order->orderDealerWorkday()->count()) {
            $days = array_flatten($order->orderDealerWorkday()->Days());
            while ($start_date_tmp <= $end_date_tmp) {
                if (
                    !Carbon::parse($start_date_tmp)
                        ->between(Carbon::parse($order->orderDealerWorkday->rest_1_start),
                            Carbon::parse($order->orderDealerWorkday->rest_1_end)) &&
                    !Carbon::parse($start_date_tmp)
                        ->between(Carbon::parse($order->orderDealerWorkday->rest_2_start),
                            Carbon::parse($order->orderDealerWorkday->rest_2_end))

                ) {
                    if ($days && $days[Carbon::parse($start_date_tmp)->dayOfWeek]) {
                        $year = Carbon::parse($start_date_tmp)->year;
                        $day = Carbon::parse($start_date_tmp)->day;
                        $month = Carbon::parse($start_date_tmp)->month;
                        $data[] = [
                            'year' => $year,
                            'month' => $month,
                            'day'   => $day
                        ];
                    }
                }
                $start_date_tmp = Carbon::parse($start_date_tmp)->addDays(1);
            }
        } else {
            return $data = [];
        }

        return $data;
    }


    //读取所有的颜色
    public function getAllColor($order)
    {
        $color = $order->orderColors->keyBy('name');
        foreach (unserialize($color['body_color']['value']) as $key=>$body) {
            $colors['body'][$key]['name'] = $body;
            $colors['body'][$key]['id'] = $key+1;
        }

        foreach (unserialize($color['interior_color']['value']) as $key=>$inter) {
            $colors['inter'][$key]['name'] = $inter;
            $colors['inter'][$key]['id'] = $key+1;
        }
        return $colors;
    }

    //注意事项:一定要在支付担保金跳转前,判断好交车时间,因为之后都是读取对应的表,直接读取时间的.
    //选装件文件字段不完整,


}