<?php namespace App\Http\Controllers\Front;

/**
 * 支付功能
 *
 * @author Andy <php360@qq.com>
 */

use App\Core\Contracts\Money\Money;
use App\Http\Controllers\Controller;
use App\Com\Hwache\Order\Order;
use App\Com\Hwache\Order\Pay;
use App\Com\Hwache\Alipay\Alipay;
use Input;
use DB;
use Session;
use Request;
use App\Models\HgCart;
use App\Models\HgCartAttr;
use App\Models\HgBaojia;
use App\Models\HgBaojiaPrice;
use App\Models\HgMoney;
use App\Models\HgCartLog;
use App\Models\HgFycXzj;
use App\Models\HgCarInfo;
use App\Models\HgBaojiaField;
use App\Models\HgXzjDaili;
use App\Models\HgUser;
use App\Models\HgUserXzj;
use App\Models\HgDealer;

class PayController extends Controller
{

    /**
     * App\Com\Hwache\Order\Order
     * @var string
     */
    protected $order;

    /**
     * App\Com\Hwache\Alipay\Alipay
     * @var string
     */
    protected $alipay;

    /**
     * 检测会员登录
     * @param Order $order
     * @param Alipay $alipay
     */
    public function __construct(Order $order, Alipay $alipay)
    {
        /**
         * 检查会员是否登陆
         * 没有登陆跳转到登陆页面
         */
        // 跳转URL
        session()->flash(
            'backurl',
            $_ENV['_CONF']['config']['shop_site_url'] . '/index.php?act=m_index'
        );
        $this->middleware(
            'user.status',
            ['except' => ['postAlipayNotify']]
        );

        $this->order = $order;
        $this->alipay = $alipay;
    }

    /**
     * 选择支付网关
     */
    public function getIndex()
    {
        return view('pay.index');
    }

    /**
     * 支付诚意金
     * @param Money $money
     * @param $id 订单编号
     * @return \Illuminate\View\View
     * @internal param \App\Com\Hwache\Order\Order
     */
    public function getEarnest(Money $money, $id)
    {
        $userId = session('user.member_id');
        $orderError = false;
        // 检查该订单编号是否属于该会员，同时是未支付诚意金状态
        if (!($order_info = $this->order->checkOrder($id))) {
            $orderError = true;
            $msg = trans('user.order.not_has_order');
        } elseif ($order_info->buy_id != $userId) {
            $orderError = true;
            $msg = trans('user.order.confirm_orderNum');
        } elseif ($order_info->cart_status == 0) {
            $orderError = true;
            $msg = trans('user.order.cancel');
        } elseif ($order_info->cart_sub_status > $_ENV['_CONF']['config']['hg_order']['order_earnest_wait_pay']) {
            $orderError = true;
            $msg = trans('user.order.paid_earnest');
        }
        if ($orderError) {
            return $this->error($msg);
        }

        // 检测用户可用余额
        $userMoney = $money->getUserMoneyCount($userId);
        $earnest = $money->getEarnest();
        if ($userMoney < $earnest) {
            // 用户可用余额小于支付的诚意金，跳转充值页面
            return $this->success(trans('user.money.money_less_earnest'), route('user.money.topup'));
        }

        // 把订单号写入session中
        session()->put('order', [
            'order_num' => $order_info->order_num,
            'order_name' => $order_info->car_name,
            'money' => $earnest, // 诚意金
            'desc' => '诚意金', // 显示的支付款项说明
            'next' => 'order_earnest', // 主状态:诚意预约
            'next_sub' => 'order_earnest_not_confirm', // 子状态:已支付诚意金,未确认
        ]);

        return view('pay.earnest', [
            'mobile' => HgUser::where('member_id', $userId)->value('member_mobile'),
            'order'  => $order_info->order_num,
            'earnest'=> $earnest,
            'userMoney' => $userMoney,
        ]);
    }

    // 支付完诚意金等待卖方确认
    public function getWait($order_num)
    {
        // 检查该订单编号是否属于该会员，同时是未支付诚意金状态
        if (!($order_info = $this->order->checkOrder($order_num))) {
            // TODO 这里应该做一个无该订单的一个提示
            dd('无此订单，请确认订单号正确');
        } elseif ($order_info->buy_id != session('user.member_id')) {
            // TODO 这里提示订单不属于该会员
            dd('订单号有误');
        } elseif ($order_info->cart_status == 0) {
            // TODO 提示订单已经取消
            dd('订单已经取消');
        }

        $view = [
            'order_num' => $order_num,
            'title' => '已付诚意金等待卖家确认',
        ];

        // 取得诚意金进入时间
        $view['earnest_time'] = HgCartLog::get_earnest_time($order_info->id);
        if (!$view['earnest_time']) exit('未查到诚意金支付记录');
        // 倒计时开始时间
        $view['starttime'] = date('Y-m-d H:i:s');

        // 需要办理的特殊资料
        $view['ziliao'] = unserialize($order_info->wenjian);

        // 等待反馈剩余时间
        if (empty($view['ziliao'])) {
            $view['endtime'] = date('Y-m-d H:i:s', strtotime($view['earnest_time']) + 20 * 60);
        } else {
            $view['endtime'] = date('Y-m-d H:i:s', strtotime($view['earnest_time']) + 24 * 3600);
        }

        if ($view['endtime'] < date('Y-m-d H:i:s')) {//诚意金反馈超时处理办法
            $view['timeout'] = 1;
            //超时 //查看是否有特需  没有特需自动确认，有特需 全部不办；
            if (!empty($view['ziliao']) && count($view['ziliao']) > 0) {//有特需 订单状态修改为202  然后再检测是否有特需，全部赋值不能办理
                HgCart::where('id', '=', $order_info->id)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_earnest'], 'cart_sub_status' => 202]);
                $autoWenjian = array();
                foreach ($view['ziliao'] as $k => $v) {
                    $autoWenjian[] = array('title' => $v, 'fee' => '', 'days' => '', 'ok' => '0');
                }
                // 文件资料办理
                $affectedRows = HgCartAttr::where('cart_id', '=', $order_info->id)->update(['wenjian' => serialize($autoWenjian),]);
                if (!$affectedRows) {
                    $cartattr = new HgCartAttr;
                    $cartattr->cart_id = $order_info->id;
                    $cartattr->wenjian = serialize($autoWenjian);
                    $cartattr->save();
                }
                $msg_time = '代理超时系统自动响应时间';
                $log_id = HgCartLog::putLog($order_info->id, session('user.member_id'), 202, "IndexController/feedBack", "诚意金支付确认反馈", 1, $msg_time);
                return redirect(route('cart.editcar', ['order_num' => $order_num]));
            } else {
                HgCart::where('id', '=', $order_info->id)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_earnest'], 'cart_sub_status' => 203]);
                $msg_time = '代理超时系统自动响应时间';
                $log_id = HgCartLog::putLog($order_info->id, session('user.member_id'), 202, "IndexController/feedBack", "诚意金支付确认反馈", 1, $msg_time);
                return redirect(route('pay.deposit', ['order_num' => $order_num]));
            }

        }
        // 取得系统设定的金额
        $view['sysmoney'] = HgMoney::getMoneyList();
        //担保金余额
        $view['money'] = $this->order->getDepositMoney($order_info->bj_id);

        $view['order'] = $order_info;

        $view['wenjian'] = unserialize($order_info->wenjian);
        // 报价信息
        $bj = HgBaojia::getBaojiaInfo($order_info->bj_id);
        $view['brand'] = explode('&gt;', $bj->gc_name);
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj->brand_id);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj->bj_id);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        // 合并该报价对应车型的具体参数
        $view['bodycolor'] = $carmodelInfo['body_color'];
        $view['interior_color'] = $carmodelInfo['interior_color'];
        $view['shangpai_price'] = $bj->bj_shangpai_price;

        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order_info->id, $order_info->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('pay.wait', $view);
    }

    /**
     * 支付保证金
     * @param $id 订单编号
     * @return \Illuminate\View\View
     * @internal param |App\Com\Hwache\Order\Order
     */
    public function getDeposit($id)
    {
        if (Request::Input('pay_deposit') == 'yes') {//支付担保金动作  还需要操作支付接口
            $data['id'] = Request::Input('id');
            $data['order_num'] = $id;
            DB::transaction(function () use ($data) {
            	$cartArray = ['cart_status' => 3, 'cart_sub_status' => 300];
                $effect1 = DB::table("hg_cart")->where('id',$data['id'])->update($cartArray);
                $logArray = ['cart_id' => $data['id'], 'user_id' => session('user.member_id'),'cart_status'=>300,'action'=>'PayController/getDeposit','msg'=>'担保金支付完成','timeout'=>0,'msg_time'=>'客户支付担保金时间'];
                $effect2 = DB::table("hg_cart_log")->insert($logArray);
            });
            return redirect(route('pay.depositok', ['id' => $data['order_num']]));
        }
        // 检查该订单编号是否属于该会员，同时是未支付诚意金状态
        if (!($order_info = $this->order->checkOrder($id))) {
            // TODO 这里应该做一个无该订单的一个提示
            dd('无效订单');
        } elseif ($order_info->buy_id != session('user.member_id')) {
            // TODO 这里提示订单不属于该会员
            dd('订单号有误');
        } elseif ($order_info->cart_status == 0) {
            // TODO 提示订单已经取消
            dd('订单已经取消');
        } elseif (
            $order_info->cart_status == $_ENV['_CONF']['config']['hg_order']['order_doposit'] &&
            $order_info->cart_sub_status >= $_ENV['_CONF']['config']['hg_order']['order_doposit_not_confirm']
        ) {
            // TODO 提示订单已经支付过保证金
            dd('已经支付过保证金');
        }

        // 判断订单状态是否在这步
        if ($order_info->cart_sub_status < $_ENV['_CONF']['config']['hg_order']['order_earnest_backok']) {
            exit('请在会员中心查看订单');
        }
        $view = [
            'title' => '支付担保金',
        ];
        // 把订单号写入session中
        session()->put('order', [
            'order_num' => $order_info->order_num,
            'order_name' => $order_info->car_name,
            'money' => $this->order->getDepositMoney($order_info->bj_id, true), // 获取保证金减去诚意金
            'desc' => '担保金', // 显示的支付款项说明
            'next' => 'order_jiaoche', // 主状态:预约交车
            'next_sub' => 'order_doposit_not_confirm', // 子状态:代理确认
        ]);
        // 取得经销商代理确认诚意金ok时间
        $feedbackok = HgCartLog::get_feedback_ok_time($order_info->id);
        $view['starttime'] = date('Y-m-d H:i:s');
        $view['endtime'] = date('Y-m-d H:i:s', strtotime($feedbackok) + 24 * 3600);
        $view['order'] = $order_info;
        $view['money_in'] = 60000;//测试数据 用户的余额


        // 品牌
        $view['brand'] = explode('&gt;', $order_info->car_name);
        // 车辆的颜色，内饰，国别等信息
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order_info->bj_id);

        $carmodelInfo = HgCarInfo::getCarmodelFields($order_info->car_id);

        $view['body_color'] = $carmodelInfo['body_color'][$bjCarInfo['body_color']];
        $view['interior_color'] = $carmodelInfo['interior_color'][$bjCarInfo['interior_color']];

        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order_info->id, $order_info->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        $view['id'] = $order_info->id;
        $view['order_num'] = $id;
        return view('pay.deposit', $view);
    }

    // 保证金支付完成等待审核
    public function getDepositWait($order_num)
    {
        // 检查该订单编号是否属于该会员，同时是未支付诚意金状态
        if (!($order_info = $this->order->checkOrder($order_num))) {
            // TODO 这里应该做一个无该订单的一个提示
            echo 'haha,错误了';
            exit;
        } elseif ($order_info->buy_id != session('user.member_id')) {
            // TODO 这里提示订单不属于该会员
            echo '无此订单，请确认订单号正确';
            exit;
        } elseif ($order_info->cart_status == 0) {
            // TODO 提示订单已经取消
            echo '订单已经取消';
            exit;
        }
        // 担保金
        $money = $this->order->getDepositMoney($order_info->bj_id, true);
        $view['money'] = $money;
        // 取得订单基本信息
        $order = HgCart::GetOrderByUser($order_num)->toArray();
        // 判断订单状态是否在这步
        // if (!in_array($order['cart_sub_status'], ['302','303'])) {
        //     exit('请在会员中心查看订单');
        // }

        // 取得报价单信息
        $baojia = HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        /*记录发出交车邀请日期和交车时限*/
        // 现车

        if ($baojia['bj_producetime']) {
            $base_date = strtotime(HgCartLog::get_deposit_time($order['id']));
            $jiaoche_time = date('Y-m-d H:i:s', strtotime('15 days', $base_date));
            $jiaoche_notice_time = date('Y-m-d H:i:s', strtotime('7 days', $base_date));
        }
        // 非现车
        if ($baojia['bj_jc_period']) {
            $base_date = strtotime(HgCartLog::get_deposit_time($order['id']));
            $jiaoche_time = date('Y-m-d H:i:s', strtotime($baojia['bj_jc_period'] . ' months', $base_date));
            $view['new_date'] = date('Y年m月d日', strtotime($jiaoche_time));
            $jiaoche_notice_time = date('Y-m-d H:i:s', strtotime('-7 days', strtotime($jiaoche_time)));
            $view['date_tiqian'] = date('Y年m月d日', strtotime($jiaoche_notice_time));
        }
        $affectedRows = HgCart::where('id', '=', $order['id'])->update(['jiaoche_time' => $jiaoche_time, 'jiaoche_notice_time' => $jiaoche_notice_time]);
        if (!$affectedRows) {
            dd('更新交车时限失败');
        }


        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee = $price->bj_car_guarantee;
        $view['guarantee'] = $guarantee;
        // 获取歉意金
        $view['jine'] = HgMoney::getMoneyList();
        $view['order_num'] = $order_num;
        $view['baojia'] = $baojia;
        $view['title'] = '担保金支付完成等待审核';
        return view('pay.depositwait', $view);

    }

    // 保证金支付完成
    public function getDepositOk($order_num)
    {
    	$ref=session('_previous');
    	Session::flash('backurl', $ref['url']);
        // 检查该订单编号是否属于该会员，同时是未支付诚意金状态
        if (!($order_info = $this->order->checkOrder($order_num))) {
            // TODO 这里应该做一个无该订单的一个提示
            dd('无此订单');
        } elseif ($order_info->buy_id != session('user.member_id')) {
            // TODO 这里提示订单不属于该会员
            dd('订单号有误');
        } elseif ($order_info->cart_status == 0) {
            // TODO 提示订单已经取消
            dd('订单已经取消');
        }

        session()->flash('backurl', URL('pay/depositok') . '/' . $order_num);
        // 担保金
        $money = $this->order->getDepositMoney($order_info->bj_id, true);
        $view['money'] = $money;
        // 取得订单基本信息
        $order = HgCart::GetOrderByUser($order_num)->toArray();
        $view['order'] = $order;
        // 判断订单状态是否在这步
        // if (!in_array($order['cart_sub_status'], ['302','303'])) {
        //     exit('请在会员中心查看订单');
        // }

        // 取得报价单信息
        $baojia = HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        /*记录发出交车邀请日期和交车时限*/
        // 现车

        if ($baojia['bj_producetime']) {
            $base_date = strtotime(HgCartLog::get_deposit_time($order['id']));
            $jiaoche_time = date('Y-m-d H:i:s', strtotime('15 days', $base_date));
            $jiaoche_notice_time = date('Y-m-d H:i:s', strtotime('7 days', $base_date));
        }
        // 非现车
        if ($baojia['bj_jc_period']) {
            $base_date = strtotime(HgCartLog::get_deposit_time($order['id']));
            $jiaoche_time = date('Y-m-d H:i:s', strtotime($baojia['bj_jc_period'] . ' months', $base_date));
            $view['new_date'] = date('Y年m月d日', strtotime($jiaoche_time));
            $jiaoche_notice_time = date('Y-m-d H:i:s', strtotime('-7 days', strtotime($jiaoche_time)));
            $view['date_tiqian'] = date('Y年m月d日', strtotime($jiaoche_notice_time));
        }
        $affectedRows = HgCart::where('id', '=', $order['id'])->update(['jiaoche_time' => $jiaoche_time, 'jiaoche_notice_time' => $jiaoche_notice_time]);
        if (!$affectedRows) {
            dd('更新交车时限失败');
        }

        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee = $price->bj_car_guarantee;
        $view['guarantee'] = $guarantee;
        // 获取歉意金
        $view['jine'] = HgMoney::getMoneyList();
        $view['order_num'] = $order_num;
        $view['baojia'] = $baojia;
        $view['title'] = '担保金支付已确认';

        // 可供选择的原厂选装件
        $view['xzj_daili'] = HgXzjDaili::getOtherXzj($order['car_id'], $order['dealer_id'], $order['seller_id'], $baojia['bj_id']);

        // 代理推荐的，可供选择的非原厂选装件
        $view['fycxzj_daili'] = HgFycXzj::getByOrder($order_num);

        // 支付完担保金的时间
        $view['t'] = time() - $base_date;

   		$u=HgUserXzj::where('order_num','=',$order_num)->get();
    	$userXzjData = array();
    	$xzj_status_array[] = 0;//选装件状态，0为添加  1为客户进行修改，2经销商同意修改  3 经销商不同意修改；
    	if(count($u)>0){
    		$u = $u->toArray();
    		foreach($u as $k=>$v){
    			$userXzjData[$v['id']] = $v['num'];
    			if($v['xzj_status']>0){
    				$xzj_status_array[] = $v['xzj_status'];
    			}
    		}
    		$view['userXzjData'] = $userXzjData;
    		$view['userXzjAllData'] = $u;
    		$view['xzj_status'] = max($xzj_status_array);
    	}else{
    		$view['userXzjData'] = array();
    		$view['userXzjAllData'] = array();
    		$view['xzj_status'] = 0;
    	}

    	// 经销商信息
   		if($view['xzj_status'] == 3){
    		$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
   		}
        return view('pay.depositok', $view);
    }

    // 支付成功
    public function paySuccess()
    {

        return view('pay.paysuccess');
    }

    /**
     * 跳转到支付网关
     * @param Pay $pay
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postGopay(Pay $pay)
    {
        // 检测支付方式合法性
        if (!$pay->checkPayment($pay_name = Input::get('payment'))) {
            // TODO 此处做非法提示处理
            echo '支付方式出错啦';
            exit;
        }

        // 保存支付方式
        $order = session()->get('order');
        $order['payment'] = $pay_name;
        session()->put('order', $order);

        /**
         * 测试跳转
         */
        return redirect()->route('pay.alipay');

        // 跳转到支付页面
        // return $pay->requestPay();
    }

    /**
     * 发送支付请求
     */
    public function getAlipay()
    {
        //return redirect()->route('pay.alipay.return');

        $this->alipay->paramPostData();
    }

    /**
     * 同步接收支付宝通知
     * @return void
     */
    public function getAlipayReturn()
    {
        // 检验是否成功付款
        // 回调URL事例: http://www.123.com/pay/alipay/return?buyer_email=tgxz2000%40qq.com&buyer_id=2088002528209961&exterface=create_direct_pay_by_user&is_success=T&notify_id=RqPnCoPT3K9%252Fvwbh3InVamgCCiYPJlb%252Bm2rjS1vumBQmmm7%252FvDY0z1lp0uTS1W2uIRF%252F&notify_time=2015-10-10+14%3A56%3A38&notify_type=trade_status_sync&out_trade_no=20150818123454433445&payment_type=1&seller_email=05gzs%4005gzs.com&seller_id=2088611880876368&subject=奥迪+%3E奥迪A3+%3E2015款+Sportback+35+T&total_fee=0.01&trade_no=2015101021001004960016133586&trade_status=TRADE_SUCCESS&sign=a4411452cc44a381a4221f0e1cefb683&sign_type=MD5
        $payResult = $this->alipay->getResult();

        // TODO 目前直接测试成功付款
        if ($payResult) {
            $result = $this->order->changeOrderStatus(
                session('order.order_num'),
                $_ENV['_CONF']['config']['hg_order'][session('order.next_sub')],
                $_ENV['_CONF']['config']['hg_order'][session('order.next')]
            );

            if ($result) {
                // $order_num = session('order.order_num');
                session()->forget('order');
                return redirect($_ENV['_CONF']['config']['shop_site_url'] . '/index.php?act=m_order');
            }
        }
    }

    /**
     * 异步接收支付宝通知
     */
    public function postAlipayNotify()
    {
        echo '异步接收支付宝通知成功';
    }

}
