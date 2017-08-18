<?php
/**
 * 用户资金管理控制器
 *
 * 充值，消费，查询，收入统一管理
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

use DB;
use Cache;
use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Com\Hwache\Order\Order;
use App\Com\Hwache\Baojia\Baojia;
use App\Core\Contracts\Money\Pay;
use App\Core\Contracts\Money\Money;

class MoneyController extends Controller
{
    /**
     * 请求依赖
     * @var Request
     */
    private $request;

    /**
     * 资金模块依赖
     *
     * @var string
     */
    private $money;

    /**
     * 初始化资金接口依赖
     *
     * @param Request $request
     * @param Money $money
     */
    public function __construct(Request $request, Money $money)
    {
        parent::__construct();

        $this->request = $request;
        $this->money   = $money;
    }

    /**
     * 资金首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        return view('user.money.index', [
            'moneyList' => $this->money->getUserMoneyList(session('user.member_id')),
        ]);
    }

    /**
     * 会员中心充值
     *
     * @param null $serialId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTopUp($serialId = null)
    {
        // 用户ID
        $userId = session('user.member_id');

        // 获取用户可充值的最大金额
        $userMoney = $this->money->getUserTopUpMaxMoney($userId);
        if ($userMoney <= 0) {
            return $this->error('已经无可充值额度');
        }

        if ($serialId) {
            // 存在serial_id
            if ($money = $this->money->getMoneyDetail($userId, $serialId)) {
                if ($money->pay_status != config('money.pay_status.not_pay')) {
                    return $this->error('订单已经处理过');
                }
                // 可充值金额小于订单金额
                if ($money->money > $userMoney) {
                    // 做该充值订单为取消状态
                    $this->money->setMoneyStatusCancel($userId, $money->id);

                    return $this->error('可充值金额小于订单金额，请重新开始充值');
                }

                // 做充值操作
                return view('user.money.has_topup', [
                    'title' => trans('user.money.topup'),
                    'serialId' => $money->serial_id, // 支付流水号
                    'money' => $money->money, // 充值金额
                    'payType' => config("pay.{$money->pay_type}"), // 支付接口
                ]);
            } else {
                return $this->error('非法访问');
            }
        }

        return view('user.money.topup', [
            'title' => trans('user.money.topup'),
            'userMoney' => $userMoney, // 可用余额数/最大可充值金额
            'payType' => $this->money->getOnlinePayType(), // 在线充值接口
        ]);
    }

    /**
     * POST充值
     *
     * @param Pay $pay
     * @return mixed
     */
    public function postTopUp(Pay $pay)
    {
        // 用户ID
        $userId = session('user.member_id');

        $serialId = $this->request->input('serial_id');
        if ($serialId) {
            // 获取流水号资金记录
            if ($money = $this->money->getMoneyDetail($userId, $serialId)) {
                // 获取用户可充值的最大金额
                $userMoney = $this->money->getUserTopUpMaxMoney($userId);
                // 可充值金额小于订单金额
                if ($money->money > $userMoney) {
                    // 做该充值订单为取消状态
                    $this->money->setMoneyStatusCancel($userId, $money->id);

                    return $this->error('可充值金额小于订单金额，请重新开始充值');
                } elseif ($money->pay_status != config('money.pay_status.not_pay')) {
                    return $this->error('订单已经处理过');
                }

                // 设置支付参数条件
                $payType = $money->pay_type;
                $payInfo = [
                    'serial_id' => $serialId,
                ];
                $price = $money->money;
            } else {
                return $this->error('非法访问');
            }
        } else {
            // 获取用户可充值的最大金额
            $userMoney = $this->money->getUserTopUpMaxMoney($userId);

            // 检查金额
            $price = filter_var($this->request->input('price'), FILTER_VALIDATE_FLOAT);
            if ($price <= 0 || $price > $userMoney) {
                // 价格不合理
                return $this->error('请输入正确的金额');
            }

            // 检查支付方式
            $payType = $this->request->input('pay');
            if (!isset(config('pay')[$payType])) {
                return $this->error('非法支付接口！');
            }

            // 跳转支付
            $payInfo = $this->money->getOnTopUp($userId, $price, $payType);
            if (!$payInfo['success']) {
                // 记录日志
                Log::info('充值失败', $payInfo);
                return $this->error('支付失败，请重新');
            }
        }

        return $pay->setPayment($payType)->pay($payInfo['serial_id'], '充值', $price);
    }

    /**
     * 支付诚意金
     *
     * @param Order $order
     * @param int $orderNum 订单号
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEarnest(Order $order, $orderNum)
    {
        // 模板传递数据
        $view = [
            'title' => '支付诚意金',
        ];

        // 用户id
        $userId = session('user.member_id');

        // 订单相关检测
        $orderStatus = $this->checkOrder($order, $orderNum);
        if ($orderStatus['error']) {
            return $this->error($orderStatus['msg']);
        }

        // 获取当前用户的可用余额，如果大于等于诚意金直接用于支付
        $useEarnest = true;
        // 用户可用余额数
        $userMoney = floatval($this->money->getUserMoneyCount($userId));
        // 系统诚意金
        $earnest = $this->money->getEarnest();
        // 可用余额和诚意金比较
        if ($userMoney < $earnest) {
            $useEarnest = false;
            // 获取最大充值金额
            $userMoney = $this->money->getUserTopUpMaxMoney($userId);
            $view['orderFreeMoney'] = $this->money->getOrderFreeMoney(); // 充值时显示订单最大充值额度
            $view['money'] = $this->money->getUserMoney($userId);
            $view['payType'] = $this->money->getOnlinePayType();
        } else {
            // 使用可用余额支付的时候，读取用户的手机号
            $view['mobile'] = session('user.member_mobile');
        }

        $view['orderNum']  = $orderNum;
        // 诚意金
        $view['earnest']   = $earnest;
        // 判断是否使用可用余额(true/false),如果为false,则下面的为最大可充值金额
        $view['useEarnest']= $useEarnest;
        // 可用余额数/最大可充值金额
        $view['userMoney'] = $userMoney;

        // 把订单号写入session中
        session()->put('order', [
            'next' => 'order_earnest', // 主状态:诚意预约
            'next_sub' => 'order_earnest_not_confirm', // 子状态:已支付诚意金,未确认
        ]);

        return view('user.money.earnest', $view);
    }

    /**
     * 支付诚意金 POST
     *
     * @param Order $order
     * @param Baojia $baojia
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function postEarnest(Order $order, Baojia $baojia)
    {
        // 验证短信验证码
        $mobile = session('user.member_mobile');
        if (!Cache::has($mobile) || $this->request->input('phonecode') != Cache::get($mobile)) {
            return $this->error('短信验证码错误，请重新支付');
        }
        // 验证成功，删除验证码
        Cache::forget($mobile);

        $orderNum = $this->request->input('order_num');

        // 订单相关检测
        $orderStatus = $this->checkOrder($order, $orderNum);
        if ($orderStatus['error']) {
            return $this->error($orderStatus['msg']);
        }

        // 用户id
        $userId = session('user.member_id');

        // 检测该会员的可用余额
        $userMoney = $this->money->getUserMoney($userId);

        // 诚意金
        $earnestMoney = $this->money->getEarnest();
        // 用户可用余额和诚意金比较
        if ($userMoney < $earnestMoney) {
            return $this->error('可用余额不足,请充值');
        }

        // 订单id
        $orderId = $order->getOrderIdByOrderNum($userId, $orderNum);
        // 报价id
        $baojiaId = $order->getBaojiaIdByOrderNum($orderNum);

        // 先减库存
        if (!$baojia->decrementStock($baojiaId)) {
            return $this->error('无库存了');
        }

        // 支付诚意金
        $payResult = $this->money->payEarnest(
            $userId, // 用户ID
            $earnestMoney, // 诚意金金额
            $orderId // 获取订单主键ID
        );
        if ($payResult['success']) {
            // 修改订单状态
            $order->changeOrderStatus(
                $orderNum,
                config('orderstatus.order.'.session('order.next_sub')),
                config('orderstatus.order.'.session('order.next'))
            );
            session()->forget('order');
            // 保存订单日志
            DB::table('hg_cart_log')->insert([
                'cart_id'   => $orderId,
                'user_id'   => $userId,
                'cart_status'   => config('orderstatus.order.order_earnest_wait_pay'),
                'action'    => 'user/money/earnest',
                'msg'       => '已付诚意金，未确认',
                'time'      => $payResult['time'],
                'msg_time'  => '支付诚意金时间',
            ]);
        } else {
            // 库存返还
            $baojia->incrementStock($baojiaId);
        }

        return redirect('/pay/wait/'.$orderNum);//诚意金支付完成之后 跳转
    }

    /**
     * 充值诚意金
     *
     * 该方法单独适用于充值诚意金
     *
     * @return mixed
     */
    public function getTopUpEarnest()
    {
        // 当前登陆用户ID
        $userId = session('user.member_id');

        // 获取在线支付诚意金最大可扩充金额(例如后台无法充值，这里还可以额外提供最大499)
        // 诚意金页面单独特有的充值接口
        $maxMoney = $this->money->getOnTopUpEarnest($userId);

        return view('user.money.topupearnest', [
            'title' => '充值诚意金',
            'maxMoney' => $maxMoney,
            'payType' => $this->money->getOnlinePayType(), // 在线充值接口
        ]);
    }

    /**
     * 支付担保金
     *
     * @param Order $order
     * @param $orderNum
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDoposit(Order $order, $orderNum)
    {
        // 模板传递数据
        $view = [
            'title' => '支付买车担保金余款',
        ];

        // 用户id
        $userId = session('user.member_id');

        // 订单相关检测
        $orderStatus = $this->checkOrder($order, $orderNum, 'order_earnest_backok');
        if ($orderStatus['error']) {
            return $this->error($orderStatus['msg']);
        }

        // 订单id
        $orderId = $order->getOrderIdByOrderNum($userId, $orderNum);

        // 订单担保金
        /*
        [
            "payStatus" => false // 支付担保金状态，布尔值
            "money" => 30001 // 支付担保金总额
            "paidMoney" => 0 // 已支付担保金金额
            "surplusMoney" => 30001 // 未支付担保金金额
        ]
        */
        $dopositMoney = $this->money->getOrderDopositDetail($userId, $orderId);
        if ($dopositMoney['payStatus']) {
            // 已经支付完成,跳转到会员中心
            return redirect()->route('user.money.index');
        }

        // 获取当前用户的可用余额，如果大于等于担保金直接用于支付
        $useDoposit = false;
        // 用户可用余额数
        $userMoney = floatval($this->money->getUserMoneyCount($userId));
        if ($userMoney) {
            // 使用可用余额支付担保金
            $useDoposit = true;

            // 用户支付金额
            $view['userMoney'] = $userMoney < $dopositMoney['surplusMoney']
                ? $userMoney
                : $dopositMoney['surplusMoney'];
        } else {
            // 充值担保金并支付担保金
            // 最大充值金额
            $view['userMoney'] = $dopositMoney['surplusMoney'];
            $view['payType'] = $this->money->getOnlinePayType();
            // 支付记录
            $view['dopositList'] = $this->money->getOrderDopositList($userId, $orderId);
        }

        // 使用可用余额支付的时候，读取用户的手机号
        $view['mobile'] = session('user.member_mobile');
        $view['orderNum']  = $orderNum;
        $view['doposit']   = $dopositMoney['money']; // 担保金
        $view['useDoposit']= $useDoposit; // 判断是否使用可用余额(true/false)

        // 判断是否是第一次支付担保金
        $view['firstPay'] = $this->money->isFirstPayDoposit($userId, $orderId);

        if ($view['firstPay']) {
            // 设置第一次支付到期时间,查询反馈诚意金成功的时间
            $payEarnestTime = DB::table('hg_cart_log')
                ->where('cart_id', $orderId)
                ->where('action', 'IndexController/feedBack')
                ->value('time');
            $payEndDt = Carbon::parse($payEarnestTime)->endOfDay();

            // 标记开始时间
            $view['payStartTime'] = $payEarnestTime;
        } else {
            // 获取首次支付担保金时间
            $payDopositTime = $this->money->getFirstPayDopositTime($userId, $orderId);
            $payEndDt = Carbon::parse($payDopositTime)->endOfDay()->addDays(3);

            // 标记开始时间
            $view['payStartTime'] = $payDopositTime;
        }
        // 结束时间
        $view['payEndTime'] = $payEndDt->toDateTimeString();
        // 是否已经过时
        $nowTime = Carbon::now();
        $view['timeOut'] = $payEndDt->lt($nowTime);
        // 现在的时间
        $view['nowTime'] = $nowTime->toDateTimeString();

        // 保存session数据
        session([
            'doposit' => [
                'orderId' => $orderId,
                'useDoposit' => $useDoposit,
                'userMoney'  => $view['userMoney'],
                'detail' => $dopositMoney
            ]
        ]);

        return view('user.money.doposit', $view);
    }

    /**
     * 担保金post提交
     * @param Pay $pay
     * @param Order $order
     * @return array
     */
    public function postDoposit(Pay $pay, Order $order)
    {
        // 获取订单号
        $orderNum = $this->request->input('order_num');
        // 订单相关检测
        $orderStatus = $this->checkOrder($order, $orderNum, 'order_earnest_backok');
        if ($orderStatus['error']) {
            return $this->error($orderStatus['msg']);
        }

        // 用户id
        $userId = session('user.member_id');

        // 订单号
        $orderId = session('doposit.orderId');

        if (session('doposit.useDoposit')) {
            // 使用可用余额支付担保金
            // 检测手机验证码
            $mobile = session('user.member_mobile');
            if (!Cache::has($mobile) || $this->request->input('phonecode') != Cache::get($mobile)) {
                return $this->error('短信验证码错误，请重新支付');
            }
            // 验证成功，删除验证码
            Cache::forget($mobile);

            // 支付
            $payResult = $this->money->payDoposit($userId, session('doposit.userMoney'), $orderId, 'money');
            if ($payResult['success']) {
                // 修改订单状态
                $order->changeOrderStatus(
                    $orderNum,
                    config('orderstatus.order.order_doposit_wait_pay'),
                    config('orderstatus.order.order_doposit')
                );
                // 保存订单日志
                DB::table('hg_cart_log')->insert([
                    'cart_id'   => $orderId,
                    'user_id'   => $userId,
                    'cart_status'   => config('orderstatus.order.order_doposit_wait_pay'),
                    'action'    => 'user/money/doposit',
                    'msg'       => '已付担保金，未确认',
                    'time'      => $payResult['data']['time'],
                    'msg_time'  => '支付担保金时间',
                ]);
            }

            return redirect()->route('user.money.paydoposit', [$orderNum, $payResult['data']['serial_id']]);
        }

        /*
         * 跳转充值
         */
        // 检查金额，小于等于未支付担保金金额
        $price = filter_var($this->request->input('payprice'), FILTER_VALIDATE_FLOAT);
        if ($price <= 0 || $price > session('doposit.detail.surplusMoney')) {
            // 价格不合理
            return $this->error('请输入正确的金额');
        }

        // 检查支付方式
        $payType = $this->request->input('payType');
        if (!isset(config('pay')[$payType])) {
            return $this->error('非法支付接口！');
        }

        // 跳转支付
        $payInfo = $this->money->getOnTopUp($userId, $price, $payType, $orderId);
        if (!$payInfo['success']) {
            // 记录日志
            Log::info('充值失败', $payInfo);
            return $this->error('支付失败，请重新');
        }

        // 设置支付担保金标记
        session(['payDoposit' => true]);

        return $pay->setPayment($payType)->pay($payInfo['serial_id'], '支付担保金', $price);
    }

    /**
     * 支付担保金结果页面
     *
     * @param Order $order
     * @param $orderNum
     * @param $serialId
     * @return mixed
     */
    public function getPayDoposit(Order $order, $orderNum, $serialId)
    {
        // 模板传递数据
        $view = [
            'title' => '支付买车担保金余款',
            'orderNum' => $orderNum,
        ];

        // 用户id
        $userId = session('user.member_id');

        // 订单相关检测
//        $orderStatus = $this->checkOrder($order, $orderNum, 'order_earnest_backok');
//        if ($orderStatus['error']) {
//            return $this->error($orderStatus['msg']);
//        }

        // 订单id
        $orderId = $order->getOrderIdByOrderNum($userId, $orderNum);

        // 查询该流水的金额
        $view['payInfo'] = DB::table('money')
            ->select('money', 'pay_time')
            ->where('serial_id', $serialId)
            ->where('begin_user', $userId)
            ->where('money_type', 'doposit')
            ->first();
        if (!$view['payInfo']) {
            return $this->error('未查询到支付记录');
        }

        // 获取担保金支付情况
        $view['dopositMoney'] = $this->money->getOrderDopositDetail($userId, $orderId);

        // 担保金支付完成，更改订单状态
        if ($view['dopositMoney']['payStatus']) {
            // 保存订单日志
            DB::table('hg_cart_log')->insert([
                'cart_id'   => $orderId,
                'user_id'   => $userId,
                'cart_status'   => config('orderstatus.order.order_doposit_wait_pay'),
                'action'    => 'user/money/doposit',
                'msg'       => '已付担保金，未确认',
                'time'      => $view['payInfo']->pay_time,
                'msg_time'  => '支付完担保金时间',
            ]);
        }

        return view('user.money.pay_doposit', $view);
    }

    /**
     * 跳转去支付，pay接口依赖
     *
     * @param Pay $pay
     * @return mixed
     */
    public function getGoPay(Pay $pay)
    {
        if (session()->has('money')) {
            $order = session('money');
            session()->forget('money');
            return $pay->setPayment($order['payment'])->pay($order['id'], $order['name'], $order['price']);
        }

        return abort(404);
    }

    /**
     * 支付同步通知
     *
     * @param Pay $pay
     * @param $payment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReturn(Pay $pay, $payment)
    {
        // 获取返回的所有数据
        $requestData = $this->request->input();

        // 当前用户
        $userId = session('user.member_id');

        // 验证支付结果
        $result = $pay->setPayment($payment)->getResult();
        if ($result['success']) {
            // 验证成功,返回数据数组
            // 检测当前订单是否已经支付过
            $payResult = $this->money->getMoneyDetail($userId, $result['data']['serial_id']);
            if (!$payResult) {
                Log::info('订单不存在', $result);
                return $this->error('订单错误');
            } elseif ($payResult->pay_status != 0) {
                return $this->error('该订单不可支付');
            }

            $payResult = $this->money->setToPay(
                $result['data']['serial_id'],
                $result['data']['money'],
                $result['data']['pay_trade_id'],
                $result['data']['pay_status'],
                $result['data']['pay_time'],
                $requestData
            );
            if ($payResult['success']) {
                // 所有的操作均已完成，进行下一步操作

                // 返回url
                $url = $payResult['data']['url'];

                if (session()->has('payDoposit')) {
                    $payDopositResult = $this->money->payDoposit(
                        $userId,
                        $result['data']['money'],
                        session('doposit.orderId'),
                        'money'
                    );
                    if ($payDopositResult['success']) {
                        $url = $payDopositResult['data']['url'];
                    }
                }

                return redirect($url);
            } else {
                // 操作中有错误，失败，进行相应的操作
                Log::info('支付失败', $payResult);
            }
        } else {
            // 验证失败，提示操作！
            Log::info('支付失败', $result);
        }

        return $this->error('支付失败');
    }

    /**
     * 异步通知
     *
     * @param Pay $pay
     * @param $payment
     * @return mixed
     */
    public function postNotify(Pay $pay, $payment)
    {
        if ($retult = $pay->setPayment($payment)->getAsynchronyResult()) {
            // 验证成功,返回数据数组
        }
    }

    /**
     * 检测订单合法性和相关状态
     *
     * @param Order $order
     * @param $orderNum
     * @param string $orderStstus
     * @return array
     */
    private function checkOrder(Order $order, $orderNum, $orderStstus = 'order_earnest_wait_pay')
    {
        // 订单相关检测,初始化变量
        $orderError = false;
        $msg = 'ok';

        // 检查该订单编号是否属于该会员，同时检测订单状态
        if (!($order_info = $order->checkOrder($orderNum))) {
            $orderError = true;
            $msg = trans('user.order.not_has_order');
        } elseif ($order_info->buy_id != session('user.member_id')) {
            $orderError = true;
            $msg = trans('user.order.confirm_orderNum');
        } elseif ($order_info->cart_status == 0) {
            $orderError = true;
            $msg = trans('user.order.cancel');
        } elseif ($order_info->cart_sub_status > config("orderstatus.order.{$orderStstus}")) {
            $orderError = true;
            $msg = trans('user.order.paid_earnest');
        }

        return [
            'error' => $orderError,
            'msg'   => $msg,
        ];
    }

}
