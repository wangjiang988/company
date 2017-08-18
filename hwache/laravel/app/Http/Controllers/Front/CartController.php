<?php namespace App\Http\Controllers\Front;

/**
 * 购物车
 *
 * @author Andy <php360@qq.com>
 */

use App\Http\Controllers\Controller;

use Input;
use Validator;
use DB;
use App\Http\Requests;
use Request;
use Carbon\Carbon;
use App\Models\HgCart;
use App\Models\HgCartAttr;
use App\Com\Hwache\Order\Order;
use App\Models\HgBaojia;
use Session;
use App\Models\HgFields;
use App\Models\HgGoodsClass;
use App\Models\HgCarInfo;
use App\Models\HgBaojiaField;
use App\Models\HgBaojiaArea;
use App\Models\HgBaojiaXzj;
use App\Models\HgBaojiaZengpin;
use App\Models\HgDealer;
use App\Models\HgBaoXian;
use App\Models\HgUser;
use App\Models\HgBaojiaPrice;
use App\Models\Area;
use App\Models\HgOtherBaoxian;
use App\Models\HgCartLog;
use App\Models\HgSeller;
use App\Models\HgBaojiaMore;
use App\Models\HgUserXzj;
use App\Models\HgMoney;
use App\Models\HgBaojiaBaoxianChesunPrice;
use App\Models\HgBaojiaBaoxianDaoqiangPrice;
use App\Models\HgBaojiaBaoxianSanzhePrice;
use App\Models\HgBaojiaBaoxianRenyuanPrice;
use App\Models\HgBaojiaBaoxianBoliPrice;
use App\Models\HgBaojiaBaoxianHuahenPrice;
use App\Models\HgDealerBaoxianZiran;
use App\Models\HgDealerBaoxianSanzhe;
use App\Models\HgDealerBaoxianRenyuan;
use App\Models\HgDealerBaoxianHuahen;
use App\Models\HgDealerBaoxianDaoqiang;
use App\Models\HgDealerBaoxianChesun;
use App\Models\HgDealerBaoxianBoli;
use App\Models\HgDealerBaoXianBuJiMian;
use App\Models\HgOrderFiles;
use App\Models\HgAnnex;
use App\Models\HgUserZengpin;
use App\Models\HgVerify;
use App\Models\HgEditXzj;
use App\Models\HgEditZengpin;
use App\Models\HgEditInfo;
use App\Models\HgFycXzj;
use App\Models\HgXzj;
use Config;
use App\Models\HgDefend,App\Models\HgDispute,App\Models\HgEvidence;
use App\Models\HgMediate;
use App\Models\HgXzjDaili;
use Exception;
use App\Models\HgCartJiaoche;
use App\Models\HgCartJiaocheExt;

class CartController extends Controller {

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Order
     */
    private $order;

    public function __construct(Request $request, Order $order)
    {
        /**
         * 检查会员是否登陆
         * 没有登陆跳转到登陆页面
         * 登陆成功后返回当前页面
         */
        // 跳转URL
        // session()->flash(
        //     'backUrl',
        //     $_ENV['_CONF']['config']['shop_site_url'].'/index.php?act=m_index'
        // );
        // $ref=session('_previous');
        // Session::flash('backurl', $ref['url']);
    	$this->middleware('user.status');
    	
        $this->request = $request;

        $this->order   = $order;
    }

    /**
     * 提交订单之后，处理
     * 所有数据均从session中读取
     */
    public function getIndex()
    {
        // 读取诚意金
        $chengyijin = DB::table('hg_money')
            ->where('name', 'chengyijin')
            ->pluck('value');

        return view('cart.index')
            ->withChengyijin($chengyijin);
    }

    /**
     * 订单第一步
     * 购物车post提交控制器
     * 事务模式保存到数据库中
     * @return url
     * @internal param Request $request
     */
    public function oneIndex()
    {
    	$ref=session('_previous');
        Session::flash('backurl', $ref['url']);
        if (Request::Input('wenjian')) {
            $ziliao=serialize(Request::Input('ziliao'));
        }else{
            $ziliao='';
        }
        if(Request::Input('buytype')=='' || !in_array(Request::Input('buytype'),array(0,1))){
        	$buytype=0;
        }else{
        	$buytype=Request::Input('buytype');
        }
        
        if(Request::Input('shenfen')=='国内其他限牌城市户籍居民'){

            $shenfen=Request::Input('shenfen').'('.Request::Input('huji').')';

        }elseif(Request::Input('shenfen')=='非中国大陆人士'){
            $shenfen=Request::Input('shenfen').'('.Request::Input('waiji').')';
        }else{
            $shenfen=Request::Input('shenfen');
        }
        if (Request::Input('zhibiao')) {
            $zhibiao=serialize(Request::Input('zhibiao'));
        }else{
            $zhibiao='';
        }
        // 上牌状态
        $first_shangpai=Request::Input('first_shangpai');
        $id=Request::Input('bj_serial');
        // 查询是否存在该报价
        $bj = HgBaojia::getBaojiaInfo($id, true);
        if (empty($bj) || !is_object($bj)) {
            // TODO 结果为空做一个提示
            exit('没有该报价哦!!');
        } else {
            $bj = $bj->toArray();
        }

        // 不允许购买自己销售车辆
        if ($bj['m_id'] == session('user.member_id')) {
            // TODO 是卖家自己销售的车型，不予购买处理
            exit('不允许购买自己销售车辆');
        }
        
        // 添加至SESSION
        sess('cart', $bj);

        // 保存到数据库购物车中
        $addData = [
            'buy_id'          => session('user.member_id'),
            'seller_id'       => sess('cart.m_id'),
            'dealer_id'       => sess('cart.dealer_id'),
            'order_num'       => $this->_get_order_sn(),
            'bj_id'           => sess('cart.bj_id'),
            'car_id'          => sess('cart.brand_id'),
            'car_name'        => sess('cart.gc_name'),
        	'buytype'        => $buytype,
            'wenjian'        => $ziliao,
            'shenfen'        => $shenfen,
            'zhibiao'        => $zhibiao,
            'first_shangpai'          => $first_shangpai,//最初的上牌状态
            'created_at'      => Carbon::now()->toDateTimeString(),
            'cart_status'     => $_ENV['_CONF']['config']['hg_order']['order_place'], // 订单状态：1
            'cart_sub_status' => $_ENV['_CONF']['config']['hg_order']['order_earnest_wait_pay'], // 子状态:未付诚意金
            'shangpai_area'=>session('area.area_id'),//客户承诺的上牌地区
        ];
        // 开启事务保存数据
        //事务中，其中需要添加判断库存 是否为0  或者更新库存num-1，且库存不为0,可以执行
        DB::transaction(function() use ($addData){
        	$data['bj_num'] = array();
        	//库存减一
        	try {
        		$getBaojiaNum = DB::table('hg_baojia')->where(array('bj_id'=>$addData['bj_id']))->pluck('bj_num');
        		if($getBaojiaNum ==0 ){
	            	throw new Exception('此款报价非常火爆，已经被抢光，请重新选择');
	            }
		    	//支付诚意金时  减库存 此步拿掉
	            /**
        		$effect = DB::table('hg_baojia')->where(array('bj_id'=>$addData['bj_id']))->decrement('bj_num', 1);
	            if(!$effect){
	            	throw new Exception('此款报价非常火爆，已经被抢光，请重新选择');
	            }
				**/
        	}catch (Exception $e) {
        		echo "此款报价非常火爆，已经被抢光，请重新选择";
        		echo "<br>三秒后自动跳转";
        		echo  "<script>setTimeout(\"window.location.href='".url('/')."'\",3000)</script>";
        		exit;
        	}
        	$getId = HgCart::insertGetId($addData);

            if (!empty($getId)) {

                // 添加订单用户信息
                DB::table('hg_cart_user')->insert([
                    'id'    => $getId,
                    'name'  => session('user.member_name'),
                    // 'phone' => $_SESSION['member_mobile'],
                ]);

                // 添加订单价格表
                DB::table('hg_cart_price')->insert([
                    'cart_id'   => $getId,
                    'cp_price'  => $this->order->getBaojiaPrice(sess('cart.bj_id')),
                    'cp_earnest'=> $this->order->getEarnestMoney(),
                    'cp_doposit'=> $this->order->getDepositMoney(sess('cart.bj_id'), true),
                ]);

                // 添加订单详细数据
                DB::table('hg_cart_log')->insert([
                    'cart_id'   => $getId,
                    'user_id'   => session('user.member_id'),
                    'cart_status'   => $_ENV['_CONF']['config']['hg_order']['order_place'], // 订单状态：1
                    'action'    => 'CartController/postIndex',
                    'msg'       => '用户提交订单',
					'timeout'   => 0,
					'msg_time'	=> '用户提交订单时间',
                ]);
            }
        });
        // 刷新token
        rebuild_token();
        // 定向到支付诚意金
        return redirect(route('pay.earnest', ['id' => $addData['order_num']]));
    }

    // 代理反馈并修改过车辆信息
    public function editCar($order_num)
    {

        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){

            exit('订单不存在');
        }

        $view=[
            'title'=>'确认代理反馈的信息',
            'order_num'=>$order_num,
            'id'=>$order->id,

        ];
        $view['order'] = $order;
        $wenjian='';
        if(!empty($order->wenjian)){
	        foreach (unserialize($order->wenjian) as $key => $value) {
	            $wenjian.=$value.'、';
	        }

	        $view['wenjian']=mb_substr($wenjian, 0,-1);
        }else{
        	$view['wenjian'] =  $wenjian;
        }
        // 取得系统金额
        $sysprice=HgMoney::getMoneyList();
        // 歉意金
        $view['qianyijin']=$sysprice['qianyijin'];
        // 诚意金
        $view['chengyijin']=$sysprice['chengyijin'];
        // 华车服务费
        $view['hwacheServicePrice']=$sysprice['hwacheServicePrice'];
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order->bj_id);
        $view['guarantee']=$price->bj_car_guarantee;
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 取得反馈诚意金时间
        $feedback_time=HgCartLog::get_feedback_time($order->id);
        $view['endtime']=date('Y-m-d H:i:s',strtotime($feedback_time)+30*60);
        if (date('Y-m-d H:i:s')>$view['endtime']) {
            $view['timeout']=1;
        }else{
            $view['timeout']=0;
        }
        $view['starttime']=date('Y-m-d H:i:s');
        /*
        *代理反馈的修改的信息
        */
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        // 反馈的特需文件
        $view['ziliao']=unserialize($order_attr->wenjian);


        // 品牌，车型
        $view['brand']=explode('&gt;',$order->car_name);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();

        $carmodelInfo = HgCarInfo::getCarmodelFields($order->car_id);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order->bj_id);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }


        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }

        /*
        *修改前的车辆信息
        */
        // 国别
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
        // 排放
        $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];

        // 车身颜色
        $view['body_color']=$carmodelInfo['body_color'];
        // 内饰颜色
        $view['interior_color']=$carmodelInfo['interior_color'];
        // 报价中出厂年月
        $view['bj_producetime']=$baojia['bj_producetime'];
        // 报价中里程
        $view['bj_licheng']=$baojia['bj_licheng'];
        // 报价中交车周期
        $view['bj_jc_period']=$baojia['bj_jc_period'];
        /*
        * 代理修改过的信息
        */
        $editinfo=HgEditInfo::getEditInfo($order_num,201);
        if(!empty($editinfo)){//判断车辆信息是否被修改
        	$view['editcarinfo']=unserialize($editinfo->carinfo);
        	$view['xzj']=unserialize($editinfo->xzj);
        	$view['zengpin']=unserialize($editinfo->zengpin);
        	//$view['editCarModel'] = "Y";
        }else{
        	//$view['editCarModel'] = "N";
        }

        // 超过20分钟未选择，自动放弃  超时响应 默认终止订单
        if(diffBetweenTwoDays($order->updated_at,3)>=20){
        	if ($this->order->changeOrderStatus($order_num,1003,1000)!==false)
        	{
        		// 记录日志
        		$log_id=HgCartLog::putLog($order->id,session('user.member_id'),1003,"CartController/editCar","代理有修改信息，客户不接受特需文件反馈，订单中止",1,'客户不接受特需条件终止订单时间');
        		if($log_id){
        			return redirect(route('cart.acceptedit', ['id' =>$order_num]));
        		}else{
        			return '记录写入失败';
        		}

        	}else{
        		return '更新订单状态出错';
        	}
        }
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('cart.editcar',$view);
    }
    // 确认修改的信息
    public function confirmEidt()
    {
        // 检验订单
        if (! $order = $this->order->getOrder(Request::Input('order_num'), session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }

        // 有特殊文件
        if (Request::Input('texu'))
        {
            // 保存客户选择的特需文件
            HgCartAttr::where('cart_id', '=', Request::Input('id'))->update(['wenjian_custem' => serialize(Request::Input('ziliao'))]);

            if (Request::Input('xiugai')) {
                if (Request::Input('wenjian')==1 && Request::Input('jiaoche')==1) {

                    if ($this->order->changeOrderStatus(Request::Input('order_num'),2023,2)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),2023,"CartController/editCar","客户同时接受了特需文件和修改信息反馈",Request::Input('timeout'),'客户接受订单修改时间');
                        if($log_id){
                                return redirect(route('cart.acceptall', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }

                    // return 2023;
                }
                if (Request::Input('wenjian')==0) {
                    if ($this->order->changeOrderStatus(Request::Input('order_num'),1003,1000)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1003,"CartController/editCar","代理有修改信息，客户不接受特需文件反馈，订单中止",Request::Input('timeout'),'客户不接受特需条件终止订单时间');
                        if($log_id){
                                return redirect(route('cart.acceptedit', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }
                    // return 1003;
                }
                if (Request::Input('wenjian')==1 && Request::Input('jiaoche')==0) {
                    if ($this->order->changeOrderStatus(Request::Input('order_num'),1004,1000)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1004,"CartController/editCar","代理有修改信息，客户接受特需文件不接受修改",Request::Input('timeout'),'客户不接受修改而终止订单时间');
                        if($log_id){
                                return redirect(route('cart.notacceptedit', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }

                    // return 2025;
                }

            }else{
                if (Request::Input('wenjian')==1) {
                    if ($this->order->changeOrderStatus(Request::Input('order_num'),2021,2)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),2021,"CartController/editCar","代理没有修改信息，客户接受特需文件",Request::Input('timeout'),'客户接受特别文件安排时间');
                        if($log_id){
                                return redirect(route('cart.acceptfile', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }

                    // return 2021;
                }else{
                    if ($this->order->changeOrderStatus(Request::Input('order_num'),1002,1000)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1002,"CartController/editCar","代理没有修改信息，客户不接受特需文件，订单终止",Request::Input('timeout'),'客户不接受特需条件终止订单时间');
                        if($log_id){
                                return redirect(route('cart.notacceptfile', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }

                    // return 1002;
                }
            }
        }else{
            // 没有特殊文件，但有修改
            if (Request::Input('xiugai'))
            {
                if (Request::Input('jiaoche')==1) {
                    if ($this->order->changeOrderStatus(Request::Input('order_num'),2023,2)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),2023,"CartController/editCar","代理有修改信息，客户接受特需文件和修改",Request::Input('timeout'),'客户接受订单修改时间');
                        if($log_id){
                                return redirect(route('cart.acceptall', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }


                    // return 2023;
                }else{
                    if ($this->order->changeOrderStatus(Request::Input('order_num'),1004,1000)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1004,"CartController/editCar","代理有修改信息，客户接受特需文件不接受修改",Request::Input('timeout'),'客户不接受修改而终止订单时间');
                        if($log_id){
                                return redirect(route('cart.notacceptedit', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }

                    // return 2025;
                }
            }else{
                // 没有修改
                if ($this->order->changeOrderStatus(Request::Input('order_num'),2021,2)!==false)
                    {
                        // 记录日志
                        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),2021,"CartController/editCar","代理没有修改信息，客户接受特需文件",Request::Input('timeout'),'客户接受特别文件安排时间');
                        if($log_id){
                                return redirect(route('cart.acceptfile', ['id' =>Request::Input('order_num') ]));
                                }else{
                                    return '记录写入失败';
                                }

                    }else{
                        return '更新订单状态出错';
                    }

                // return 2021;
            }
        }
    }
    // 代理没有修改信息，客户接受了特需文件反馈
    public function acceptFile($order_num)
    {
    	$order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '提议修改订单，等待客户确认',
            'order_num'=>$order_num,
        ];
        // 取得订单基本信息
        $view=array_merge($view,$order->toArray());
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order->id);
        if(!$earnest_time) exit('未查到诚意金支付记录');
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 接受特需时间
        $acceptfile_time=HgCartLog::get_acceptfile_time($order->id);
        $view['starttime']=date('Y-m-d H:i:s');
        $view['endtime']=date('Y-m-d H:i:s',strtotime($acceptfile_time)+24*3600);
        // 订单扩展信息
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        // 判断是否有特需文件
        $view['texu']=empty($order_attr->wenjian)?0:1;
        // 品牌
        $view['brand']=explode('&gt;',$order->car_name);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($order->bj_id);
        $view['baojia']=$baojia;
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order->car_id);
        $view['barnd_info']=$barnd_info;
        // 车辆的颜色，内饰，国别等信息
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order->bj_id);
        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }

        // 排放
        $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
        $carmodelInfo = HgCarInfo::getCarmodelFields($order->car_id);
        $view['carmodelInfo']=$carmodelInfo;
        $view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
        $view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
    	// 担保金
        $money=$this->order->getDepositMoney($order->bj_id, true);
        $view['money']=$money;
        // 取得系统金额
        $sysprice=HgMoney::getMoneyList();
        // 诚意金
        $view['chengyijin']=$sysprice['chengyijin'];
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;

    	return view('cart.acceptfile',$view);
    }
    // 客户接受了特需文件和修改信息反馈
    public function acceptAll($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view=[
            'title'=>'确认代理反馈的信息',
            'order_num'=>$order_num,
            'id'=>$order->id,
        ];
        $view['order'] = $order;
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order->id);
        // 客户接受修改时间
        $accepttime=HgCartLog::get_acceptall_time($order->id);
        // 倒计时
        $view['starttime']=date('Y-m-d H:i:s');
        $view['endtime']=date('Y-m-d H:i:s',strtotime($accepttime)+24*3600);
        // 品牌，车型
        $view['brand']=explode('&gt;',$order->car_name);
        // 修改过的车辆信息
        $car=HgEditInfo::getEditInfo($order_num,201);

        $view['editcarinfo']=!empty($car->carinfo)?unserialize($car->carinfo):array();
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        $carmodelInfo = HgCarInfo::getCarmodelFields($order->car_id);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order->bj_id);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        // 车身颜色
        $view['body_color']=$carmodelInfo['body_color'];
        // 内饰颜色
        $view['interior_color']=$carmodelInfo['interior_color'];
        // 报价中出厂年月
        $view['bj_producetime']=$baojia['bj_producetime'];
        // 报价中里程
        $view['bj_licheng']=$baojia['bj_licheng'];
        // 报价中交车周期
        $view['bj_jc_period']=$baojia['bj_jc_period'];
        // 排放
        $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
        // 取得系统金额
        $sysprice=HgMoney::getMoneyList();
        // 歉意金
        $view['qianyijin']=$sysprice['qianyijin'];
        // 诚意金
        $view['chengyijin']=$sysprice['chengyijin'];
        // 华车服务费
        $view['hwacheServicePrice']=$sysprice['hwacheServicePrice'];
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order->bj_id);
        $view['guarantee']=$price->bj_car_guarantee;

        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('cart.acceptall',$view);
    }
    // 代理没有修改信息，客户不接受特需文件反馈
    public function notAcceptFile($order_num)
    {
        return view('cart.notacceptfile');
    }
    // 代理有修改信息，客户不接受特需文件反馈
    public function acceptEdit($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view=[
            'title'=>'不同意经销商代理反馈的特需文件',
            'order_num'=>$order_num,
        ];
        // 取得系统金额
        $sysprice=HgMoney::getMoneyList();
        // 歉意金
        $view['qianyijin']=$sysprice['qianyijin'];
        // 诚意金
        $view['chengyijin']=$sysprice['chengyijin'];
        return view('cart.acceptedit',$view);
    }
    // 代理有修改信息，客户接受特需文件反馈,不接受修改
    public function notAcceptEdit($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view=[
            'title'=>'不接受销商提出的修改',
            'order_num'=>$order_num,
        ];
        // 取得系统金额
        $sysprice=HgMoney::getMoneyList();
        // 歉意金
        $view['qianyijin']=$sysprice['qianyijin'];
        // 诚意金
        $view['chengyijin']=$sysprice['chengyijin'];
        return view('cart.notacceptedit',$view);
    }
    // 订单被代理终止
    public function stop1($order_num)
    {
        // 检验订单
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        // 系统设置的金额，如诚意金
        $view['sysprice']=HgMoney::getMoneyList();
        //终止订单，更新结算状态
        $eff = HgCart::where("order_num",$order_num)->update(array('calc_status'=>1));
        return view('cart.stop1',$view);
    }
    /**
     * 预约交车
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getYuyueSell($id)
    {
        if (empty($_SESSION['seller_id']) || ! $order = $this->order->getOrder($id, $_SESSION['seller_id'])) {
            abort(404, trans('common.code404'));
        }

        $view = [
            'title' => trans('common.cart.yuyue'),
        ];

        // 该订单当前状态及其防伪验证
        $cart_status = [
            'id'                => $order->id, // 订单ID
            'bj_id'             => $order->bj_id, // 订单对应的报价ID
            'buy_id'            => $order->buy_id, // 消费者ID
            'seller_id'         => $order->seller_id, // 经销商代理ID
            'order_num'         => $order->order_num, // 订单编号
            'car_id'            => $order->car_id, // 车型ID
            'cart_status'       => $order->cart_status, // 订单主状态
            'cart_sub_status'   => $order->cart_sub_status, // 订单子状态
            'hash'              => md5(
                $order->id.$order->buy_id.$order->seller_id.$order->cart_sub_status), // 订单防伪验证
        ];

        // 返回预约交车当前状态和下一步状态
        switch(get_order_status($order->cart_sub_status)) {
            case 'order_jiaoche_wait':
                // 下一步:发送交车通知
                $cart_status['next'] = 'order_jiaoche_sent_notify';
                break;
            case 'order_jiaoche_sent_notify':
                // 下一步:等待经销商确认交车时间
                $cart_status['next'] = 'order_jiaoche_confirm';
                break;
            case 'order_jiaoche_confirm':
                // 发送交车通知
                $cart_status['next'] = 'order_jiaoche_ok';
                break;
        }

        // 保存该订单信息
        session()->put('cart', $cart_status);

        return view('cart.yuyue_sell', $view);
    }

    /**
     * 消费者预约
     *
     * @param $order_num
     * @return \Illuminate\View\View
     */
    // 预约交车等待
    public function getYuyueFirst($order_num)
    {

    	$ref=session('_previous');
        Session::flash('backurl', $ref['url']);
        // 检查该订单编号是否属于该会员，同时是未支付诚意金状态
        if (! ($order_info = $this->order->checkOrder($order_num))) {
            // TODO 这里应该做一个无该订单的一个提示
            dd('订单错误');
        } elseif ($order_info->buy_id != session('user.member_id')) {
            // TODO 这里提示订单不属于该会员
            dd('无此订单，请确认订单号正确');

        } elseif ($order_info->cart_status == 0) {
            // TODO 提示订单已经取消
            dd('订单已经取消');
        } elseif ($order_info->cart_sub_status != 301) {
            dd('此订单选装件步骤操作失效，无法选择，请合理按照购车流程，谢谢！');
        }
        // 担保金
        $money=$this->order->getDepositMoney($order_info->bj_id, true);
        $view['money']=$money;
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order'] = $order;


        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        /*记录发出交车邀请日期和交车时限*/
        // 现车

        if ($baojia['bj_producetime']) {
                $base_date=strtotime(HgCartLog::get_deposit_time($order['id']));
                $jiaoche_time=date('Y-m-d H:i:s',strtotime('15 days',$base_date));
                $jiaoche_notice_time=date('Y-m-d H:i:s',strtotime('7 days',$base_date));
        }
        // 非现车
        if ($baojia['bj_jc_period']) {
                $base_date=strtotime(HgCartLog::get_deposit_time($order['id']));
                $jiaoche_time=date ( 'Y-m-d H:i:s' ,  strtotime ( $baojia['bj_jc_period'].' months',$base_date ));
                $view['new_date']=date('Y年m月d日',strtotime($jiaoche_time));
                $jiaoche_notice_time=date ('Y-m-d H:i:s' ,  strtotime ('-7 days',strtotime($jiaoche_time) ));
                $view['date_tiqian']=date('Y年m月d日',strtotime($jiaoche_notice_time));
        }


        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 获取歉意金
        $view['jine']=HgMoney::getMoneyList();
        $view['order_num']=$order_num;
        $view['baojia']=$baojia;
        $view['title']='担保金支付已确认';
        // 报价选装件
        //$view['xzjs']=HgBaojiaXzj::getBaojiaXzj($baojia['bj_id']);
        // 支付完担保金的时间
        $view['t']=time()-$base_date;

        /*//获取原厂非前置选装件
        $view['xzjs'] = HgXzj::getDealerYcNotFrontXzj($baojia['brand_id'],$baojia['dealer_id'],$baojia['m_id']);

        // 非原厂选装件
        $view['fycxzj']=HgFycXzj::getByOrder($order_num);
        $userxzjListTmp = HgUserXzj::getAllInfo($order_num)->toArray();
        $userxzjList = array();
        if(count($userxzjListTmp)>0){
	        foreach($userxzjListTmp as $k => $v){
	        	$userxzjList[$v['is_yc']][$v['id']] = $v;
	        }
        }
        $view['userxzjList']=$userxzjList;*/
        //$userxzjList[1]为用户提交的 原厂选装件的取值 $userxzjList[0]为用户提交的 非原厂选装件的取值
        // 可供选择的原厂选装件
        $view['xzj_daili']=HgXzjDaili::getOtherXzj($order['car_id'],$order['dealer_id'],$order['seller_id'],$baojia['bj_id']);
        // 代理推荐的，可供选择的非原厂选装件
        $view['fycxzj_daili']=HgFycXzj::getByOrder($order_num);

    	$u=HgUserXzj::where('order_num','=',$order_num)->get();
    	$userXzjData = array();
    	$xzj_status_array[] = 0;//选装件状态，0为添加  1为客户进行修改，2经销商同意修改  3 经销商不同意修改；
    	if(count($u)>0){
    		$u = $u->toArray();
    		foreach($u as $k=>$v){
    			$userXzjData[$v['id']] = $v['num'];
    			$userXzjAllData[$v['id']] = $v;
    			if($v['xzj_status']>0){
    				$xzj_status_array[] = $v['xzj_status'];
    			}
    		}
    		$view['userXzjData'] = $userXzjData;
    		$view['userXzjAllData'] = $userXzjAllData;
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
        return view('cart.yuyuefirst',$view);
    }
    // 预约交车前代理终止了订单
    public function stop2($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view=[
            'title'=>'经销商代理终止了订单',

        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view=array_merge($view,$order);
        $bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        $view['brand']=explode('&gt;', $bj['gc_name']);
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        // 合并该报价对应车型的具体参数
       $view['bodycolor']=$carmodelInfo['body_color'];
       $view['interior_color']=$carmodelInfo['interior_color'];
       // 代理终止订单的时间
       $view['stoptime']=HgCartLog::get_stop2_time($order['id']);
       
       //终止订单，更新结算状态
       $eff = HgCart::where("order_num",$order_num)->update(array('calc_status'=>1));
        return view('cart.stop2',$view);
    }
    // 代理提议修改订单，等待客户确认
    public function pdiEdit($order_num)
    {
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view=[
            'title'=>'售方修改了订单内容，待客户确认',
            'order_num'=>$order_num,
            'id'=>$order->id,
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view=array_merge($view,$order);
        $bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        /**
         * 获取该车型本身的数据信息----车型
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
        */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
        	if (is_array($carmodelInfo[$key])) {
        		$carmodelInfo[$key] = $carmodelInfo[$key][$val];
        	} else {
        		$carmodelInfo[$key] = $val;
        	}
        }
        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);
        $view['carmodelInfo'] = $bj;
        $modifyLog = HgEditInfo::getEditInfo($order_num,301);
	    $view['modifyLogCarInfo'] = !empty($modifyLog->carinfo)?unserialize($modifyLog->carinfo):array();
	    $view['modifyLogXzj'] = !empty($modifyLog->xzj)?unserialize($modifyLog->xzj):array();
	    $view['modifyLogZengpin'] =!empty($modifyLog->zengpin)?unserialize($modifyLog->zengpin):array();
	    $view['brand']=explode('&gt;',$view['car_name']);
	    //车辆扩展信息
	    $carFields = HgFields::getFieldsList('carmodel');
	    foreach ($carFields as $k => $v) {
	    	$carFields[$k] = unserialize($v);
	    }

	    $view['carInfoDetail'] = array(
	    		"body_color"=>$carmodelInfo['body_color'],
	    		"interior_color"=>$carmodelInfo['interior_color'],
	    		"chuchang"=>$view['carmodelInfo']['bj_producetime'],
	    		"licheng"=>$view['carmodelInfo']['bj_licheng'],
	    		"zhouqi"=>$view['carmodelInfo']['bj_jc_period'],
	    		"paifang"=>$carFields['paifang'][$carmodelInfo['paifang']],
	    );
	    $view['carInfoDetailLang'] = array(
	    		"body_color"=>"车身颜色",
	    		"interior_color"=>"内饰颜色",
	    		"chuchang"=>"出厂年月",
	    		"licheng"=>"行驶里程",
	    		"zhouqi"=>"交车周期",
	    		"paifang"=>"排放标准",
	    		);
        // 到计时
        $view['starttime']=date('Y-m-d H:i:s');
        // 代理修改订单时间
        $edittime=HgCartLog::get_pdiedit_time($order['id']);
        $view['endtime']=date('Y-m-d H:i:s',strtotime($edittime)+24*3600);
        $view['timeout']=($view['starttime']>$view['endtime'])?1:0;
	    /**
	     * 获取订单日志
	     */
	    $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();

	    $view['cart_log'] = $log;
        return view('cart.pdiedit',$view);
    }
    // 客户的选择
    public function savePdiEdit()
    {
        if (! $order = $this->order->getOrder(Request::Input('order_num'), session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        switch (Request::Input('jiaoche')) {
            case '1':
                $this->order->changeOrderStatus(Request::Input('order_num'),303,3);
                HgCartLog::putLog(Request::Input('id'),session('user.member_id'),303,"CartController/pdiEdit","客户接受了修改订单",Request::Input('timeout'),"客户接受修改订单时间");
                return redirect(route('cart.waitnotice', ['id' =>Request::Input('order_num') ]));

                break;

            case '2':
                $this->order->changeOrderStatus(Request::Input('order_num'),1006,1000);
                HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1006,"CartController/pdiEdit","客户不接受了修改订单",Request::Input('timeout'),"客户不接受修改订单时间");
                return redirect(route('cart.stop3', ['id' =>Request::Input('order_num') ]));
                break;

        }

    }
    // 接受订单修改等待发交车通知
    public function waitNotice($order_num)
    {
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    		exit('订单不存在');
    	}
    	$view = [
    			'title' => '已响应订单，等待预约交车',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	// 取得订单基本信息
    	$view['order'] = $order;

    	$view=array_merge($view,$order);
    	// 交车时限，剩余天数
    	$view['jiaoche_time']=$order['jiaoche_time'];
    	$view['surplus_time']=diffBetweenTwoDays($view['jiaoche_time'],1);
    	// 交车通知时限，剩余天数
    	$view['jiaoche_notice_time']=$order['jiaoche_notice_time'];
    	$view['surplus_time_notice']=diffBetweenTwoDays($view['jiaoche_notice_time'],3);
    	// 取得诚意金进入时间
    	$earnest_time=HgCartLog::get_earnest_time($order['id']);
    	$view['earnest_time']=$earnest_time?$earnest_time:'';
    	// 取得客户付完诚意金代理反馈订单的时间
    	$feedback_time=HgCartLog::get_feedback_time($order['id']);
    	$view['feedback_time']=$feedback_time ? $feedback_time : '';
    	// 担保金进入时间
    	$deposit_time=HgCartLog::get_deposit_time($order['id']);
    	if(!$deposit_time) exit('未查到担保金支付记录');
    	$view['danbaojin_time']=$deposit_time ? $deposit_time : '';

    	// 收到担保金响应订单时间
    	$response_time=HgCartLog::get_response_time($order['id']);
    	if(!$response_time) dd('保证金还未确认');
    	$view['response_time']=$response_time?$response_time:'';

    	// 品牌
    	$view['brand']=explode('&gt;',$order['car_name']);
    	// 取得报价单信息
    	$baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
    	//报价扩展信息包括其他费用
    	$baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
    	$view['baojia']=$baojia;
    	// 经销商信息
    	$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
    	$view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
    	//获取品牌车型的基本信息
    	$barnd_info=HgGoodsClass::getCarBase($order['car_id']);
    	$view['barnd_info']=$barnd_info;
    	//车辆扩展信息
    	$carFields = HgFields::getFieldsList('carmodel');
    	foreach ($carFields as $k => $v) {
    		$carFields[$k] = unserialize($v);
    	}
    	$carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);
    	$view['carmodelInfo']=$carmodelInfo;
    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
    	$view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
    	$view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
    	// 国别
    	$view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
    	// 排放
    	$view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
    	// 补贴
    	$view['butieTitle'] = $carFields['butie'][$bjCarInfo['butie']];
    	// 报价可售地区

    	$area=HgBaojiaArea::getBaojiaArea($order['bj_id']);
    	$area_names='';
    	$area_names_xianpai='';//限牌地区名
    	if(!is_array($area)){
    		$view['area']=$area;
    		$view['area_xianpai']=$area_names_xianpai;
    	}else{
    		foreach ($area as $key => $value) {
    			foreach ($value as $kk) {
    				if (strpos($kk,'限牌')!== false ) {
    					$area_names_xianpai.=str_replace('(限牌城市)','',$kk);
    				}
    			}
    			$area_names.=' '.$key.'：'.implode(',',$value);
    		}

    		$view['area']=$area_names;
    		$view['area_xianpai']=$area_names_xianpai;
    	}
    	// 客户承诺上牌地区
    	$view['shangpaiarea']=Area::getAreaName($order['shangpai_area']);
    	// 报价选装件
    	$xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
    	// 用户选择的选装件
    	$view['userxzj']=HgUserXzj::getAllInfo($order_num);
    	// 原厂选装件数量
    	$ycxzj_count=HgUserXzj::getCount($order_num,1);
    	// 非原厂选装件数量
    	$fycxzj_count=HgUserXzj::getCount($order_num,0);
    	$view['ycxzj_count']=$ycxzj_count;
    	$view['fycxzj_count']=$fycxzj_count;

    	$view['xzj']=$xzjs->toArray();
    	$xzj_yc_install_front = array();
    	if(!empty($view['xzj']) && count($view['xzj'])>0){
    		foreach($view['xzj'] as $k =>$v){
    			if($v['is_install'] == 1 && $v['xzj_yc'] == 1 && $v['xzj_front']){
    				$xzj_yc_install_front[] = $v;
    			}
    		}
    	}
    	$view['xzj_yc_install_front'] = $xzj_yc_install_front;

    	// 客户上牌地为本地还是异地
    	$view['islocal']=HgCart::isLocal($order_num);
    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	//如果订单已经被修改过，取最后一次修改的信息 并赋值 add by jerry 2016-02-01
    	$carEditLog = HgEditInfo::getEditInfo($order_num,302);
    	$view['modifyLogCarInfo'] = !empty($carEditLog->carinfo)?unserialize($carEditLog->carinfo):array();
    	$view['modifyLogXzj'] = !empty($carEditLog->xzj)?unserialize($carEditLog->xzj):array();
    	$view['modifyLogZengpin'] =!empty($carEditLog->zengpin)?unserialize($carEditLog->zengpin):array();

    	$view['body_color'] = isset($view['modifyLogCarInfo']['body_color'])?$view['modifyLogCarInfo']['body_color']:$view['body_color'];
    	$view['interior_color'] = isset($view['modifyLogCarInfo']['interior_color'])?$view['modifyLogCarInfo']['interior_color']:$view['interior_color'];
    	$view['baojia']['bj_producetime']= isset($view['modifyLogCarInfo']['chuchang'])?$view['modifyLogCarInfo']['chuchang']:$view['baojia']['bj_producetime'];
    	/*if(isset($view['bj_jc_period'])){
    		$view['bj_jc_period'] = isset($view['modifyLogCarInfo']['zhouqi'])?$view['modifyLogCarInfo']['zhouqi']:$view['bj_jc_period'];
    	}else{
    		$view['bj_jc_period'] = "";
    	}*/
    	$view['bj_licheng'] = isset($view['modifyLogCarInfo']['bj_licheng'])?$view['modifyLogCarInfo']['bj_licheng']:$view['baojia']['bj_licheng'];
    	$view['paifangTitle'] = isset($view['modifyLogCarInfo']['paifang'])?$view['modifyLogCarInfo']['paifang']:$view['paifangTitle'];
    	/**
    	 * 获取订单日志
    	 */
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();

    	$view['cart_log'] = $log;

    	$view['xzj_daili']=HgXzjDaili::getOtherXzj($order['car_id'],$order['dealer_id'],$order['seller_id'],$baojia['bj_id']);
    	// 代理推荐的，可供选择的非原厂选装件
    	$view['fycxzj_daili']=HgFycXzj::getByOrder($order_num);

    	$u=HgUserXzj::where('order_num','=',$order_num)->get();
    	$userXzjData = array();
    	$xzj_status_array[] = 0;//选装件状态，0为添加  1为客户进行修改，2经销商同意修改  3 经销商不同意修改；
    	if(count($u)>0){
    		$u = $u->toArray();
    		foreach($u as $k=>$v){
    			$userXzjData[$v['id']] = $v['num'];
    			$userXzjAllData[$v['id']] = $v;
    			if($v['xzj_status']>0){
    				$xzj_status_array[] = $v['xzj_status'];
    			}
    		}
    		$view['userXzjData'] = $userXzjData;
    		$view['userXzjAllData'] = $userXzjAllData;
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
        return view('cart.waitnotice',$view);
    }
    // 不接受订单修改终止订单
    public function stop3($order_num)
    {
        return view('cart.stop3');
    }
    // 客户填写预约信息
    public function getYuyue($order_num)
    {
    	$ref=session('_previous');
        Session::flash('backurl', $ref['url']);
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }

        $view = [
            'title' => '交车邀请',
            'order_num'=>$order_num,
        ];
        /*
        *客户信息
        */

        $view['buyer']=HgUser::getMember($order->buy_id);

        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        // 该订单当前状态及其防伪验证
        $cart_status = [
            'id'                => $order->id, // 订单ID
            'bj_id'             => $order->bj_id, // 订单对应的报价ID
            'buy_id'            => $order->buy_id, // 消费者ID
            'seller_id'         => $order->seller_id, // 经销商代理ID
            'order_num'         => $order->order_num, // 订单编号
            'car_id'            => $order->car_id, // 车型ID
            'cart_status'       => $order->cart_status, // 订单主状态
            'cart_sub_status'   => $order->cart_sub_status, // 订单子状态
            'hash'              => md5(
                $order->id.$order->buy_id.$order->seller_id.$order->cart_sub_status), // 订单防伪验证
        ];
        // 保存该订单信息
        session()->put('cart', $cart_status);

        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($order_num, true);
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
        // 取得报价信息
        $bj_id=$view['order']['cartBase']['bj_id'];

        $bj=HgBaojia::getBaojiaInfo($bj_id)->toArray();
        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($bj['brand_id']);
        $bj['barnd_info']=$barnd_info;
        // 车型扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        if (empty($carFields)) {
            // TODO 没有查找到对应的车型扩展信息
            exit('没有查找到对应的车型扩展信息');
        }
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        //报价扩展信息包括其他费用
        $bj['more']=HgBaojiaMore::getBaojiaMove($bj['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$bj['more'])){

            foreach ($bj['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;

            }
        }
        $bj['other_price']=$other_price;
        /**
         * 获取该车型本身的数据信息----车型
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }

        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);
        // 支付方式
        $bj['payTitle'] = $_ENV['_CONF']['base']['payType'][$bj['bj_pay_type']];
        // 国别
        $bj['guobieTitle'] = $carFields['guobie'][$bj['guobie']];
        // 排放
        $bj['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        // 补贴
        $bj['butieTitle'] = $carFields['butie'][$bj['butie']];
        // 保险公司信息
        if ($bj['bj_bx_select']) {
            $view['baoxianname']=HgBaoXian::getName($bj['bj_bx_select']);
        }
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($bj['bj_id']);
        $bj['xzj']=$xzjs->toArray();
        // 判断是否有原厂已安装选装件
        $yc_xzj=0;
        foreach ($bj['xzj'] as $key => $value) {
            if($value['is_install'] && $value['xzj_yc']) $yc_xzj++;
        }
        $bj['yc_xzj']=$yc_xzj;
        // 报价地区
        $area=HgBaojiaArea::getBaojiaArea($bj['bj_id']);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($area)){
            $bj['area']=$area;
            $bj['area_xianpai']=$area_names_xianpai;

        }else{
                foreach ($area as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }

            $bj['area']=$area_names;
            $bj['area_xianpai']=$area_names_xianpai;

        }

        $view['bj']=$bj;

        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 经销商地址
        $dealer=HgDealer::getDealerInfo($bj['dealer_id']);
        $view['dealer_area']=$dealer->d_shi;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($view['order']['cartBase']['dealer_id'])->toArray();
        // 车身颜色，内饰颜色，排放标准，实际里程，出厂年月重新赋值
        // 取得订单修改的所有信息
        $cartattr=HgCartAttr::GetOrderAttr(array('cart_id'=>$order->id));

        if (!empty($cartattr->mileage)) {
            $view['bj']['bj_licheng']=$cartattr->mileage;
        }
        if (!empty($cartattr->production_date)) {
            $view['bj']['bj_producetime']=$cartattr->production_date;
        }
        // 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
            $view['bj']['body_color']=$carinfo['body_color'];
            $view['bj']['interior_color']=$carinfo['interior_color'];
            $view['bj']['paifangTitle']=$carinfo['paifang'];
        }
        // 免费礼品
        // $view['zengpin']=unserialize($cartattr->zengpin);
        // 约定交车的时限
        $view['jiaoche_time']=date('Y-m-d',strtotime($order->jiaoche_time));
        // 倒计时
        $view['starttime']=date('Y-m-d H:i:s');
        $view['endtime']=date('Y-m-d H:i:s',strtotime($view['jiaoche_time']));
        // 代理选择的交车日期
        $jiaoche_date=($cartattr->pdi_date_dealer);
        $view['jiaoche_date']=array_filter(explode(',',$jiaoche_date));

        //  领取国家补贴需要的文件
        $butiefile=HgOrderFiles::getFile($order_num,4);
        $butiefiles='';
        foreach ($butiefile as $key => $value) {
            if($value['num']==0) continue;
            $butiefiles.=$value['title'].':'.$value['num'].'份   ';
        }

        $view['butiefile']=$butiefiles;
        // 上牌需要的资料
        $shangpai_file=HgOrderFiles::getFile($order_num,2);
        $shangpaifiles='';
        foreach ($shangpai_file as $key => $value) {
            $shangpaifiles.=$value['title'].':'.$value['num'].'份   ';
        }
        $view['shangpai_file']=$shangpaifiles;
        // 上临牌需要的文件
        $linpai_file=HgOrderFiles::getFile($order_num,3);
        $linpaifiles='';
        foreach ($linpai_file as $key => $value) {
            $linpaifiles.=$value['title'].':'.$value['num'].'份   ';
        }
        $view['linpai_file']=$linpaifiles;
        // 投保需要的文件
        $toubao_file=HgOrderFiles::getFile($order_num,1);
        $toubaofiles='';
        foreach ($toubao_file as $key => $value) {
            $toubaofiles.=$value['title'].':'.$value['num'].'份   ';
        }
        $view['toubao_file']=$toubaofiles;
        /*
        *保险价格计算
        */
        if ($bj['bj_bx_select']) {
                // 保险折扣
            $baoxian_discount=$bj['bj_baoxian_discount'];
            // 保险的类别
            $baoxian_type=getBaoxianType($order['cartBase']['buytype'],$bj['seat_num']);
            // 全部额度
            $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
            $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
            $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($bj['bj_id'],$baoxian_type);
            $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($bj['bj_id'],$baoxian_type);
            $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($bj['bj_id'],$baoxian_type);
            $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($bj['bj_id'],$baoxian_type);

             // 推荐组合
            $view['sanzhe_r'] =HgBaojiaBaoxianSanzhePrice::getPrice($bj['bj_id'],$baoxian_type);

            $view['renyuan_r'] =HgBaojiaBaoxianRenyuanPrice::getPrice($bj['bj_id'],$baoxian_type);
            $view['boli_r'] =HgBaojiaBaoxianBoliPrice::getPrice($bj['bj_id'],$baoxian_type);
            $view['huahen_r'] =HgBaojiaBaoxianHuahenPrice::getPrice($bj['bj_id'],$baoxian_type);
            $view['renyuan_r_ck']=HgBaojiaBaoxianRenyuanPrice::getCkPrice($bj['bj_id'],$baoxian_type);
            $szd='szd'.$view['sanzhe_r']['compensate'];
            $ryd='rysj'.$view['renyuan_r']['compensate'];
            $bld='bld'.$view['boli_r']['state'];
            $hhd='hhd'.$view['huahen_r']['compensate'];
            $ckd='ryck'.$view['renyuan_r_ck']['compensate'];
            $seat='seat'.($bj['seat_num']-1);
            $view['seat']=$seat;
            $view['zuhe']="'cs1','dq1','sz1','$szd','ry1','$ryd','bl1','hh1','bjmcs','bjmdq','bjmsz','bjmry','bjmhh','$bld','$hhd','$ckd','$seat'";
        }

        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
        // 其他商业保险
        $view['otherbaoxian']=HgOtherBaoxian::getOtherBaoxian();
        $view['zhuxian']=($_ENV['_CONF']['config']['otherbaoxian']);
        // dd($view['zhuxian']);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;

        return view('cart.yuyue', $view);
    }
    /**
     * post预约交车，保存客户提交的信息，并设置订单状态
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function postYuyue()
    {
        // 检验订单
        if (! $order = $this->order->getOrder(Request::Input('order_num'), session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        // 定义下步状态及反馈ok的标志
        $next_status=402;
        $modifydate=$modifytrip= false;

        // 客户确定的交车日期
        if (Request::Input('mydate')) {
            $modifydate=true;
            $jiaoche_date=Request::Input('mydate').' '.Request::Input('d-s-r');
        }else{
            $jiaoche_date=Request::Input('jiaoche_date');
        }
        // 注册登记人
        $reg_name=Request::Input('chezhu');
        $upcart=HgCart::where('id','=',Request::Input('id'))->update([
                'reg_name'=>$reg_name,
                'final_baoxian'=>Request::Input('final_baoxian'),
                'shangpai'=>Request::Input('shangpai'),
                'linpai'=>Request::Input('linpai'),
            ]);
        if (!$upcart) {
            dd('更新订单失败');
        }

        // 回程方式
        $huicheng = array('fangshi' => Request::Input('huichengCheMethod'),'price'=>0,'baoxian'=>0,'pay'=>'' );
        if (Request::Input('huichengCheMethod')=='了解送车服务报价') {
            $modifytrip=true;
        }
        if ($modifytrip || $modifydate) {
            $next_status=403;
        }

        // 更新订单属性表
        $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update(['take_way' => Request::Input('tiCheMethod'),
            'trip_way'=>serialize($huicheng),
            'deliver_addr'=>Request::Input('deliver_addr'),
            'butie'=>Request::Input('butie'),
            'zhihuan'=>Request::Input('zhihuan'),
            'other_baoxian'=>serialize(Request::Input('other_baoxian')),
            'other'=>Request::Input('other'),
            'pdi_date_client'=>$jiaoche_date,
            'jc_date'=>serialize(array('date'=>$jiaoche_date,'fee'=>0)),
            'cj_butie'=>Request::Input('cj_butie'),
            'take_cause'=>Request::Input('yuanyin'),
            'agreement'=>Request::Input('agreement'),
            'ticheren'=>serialize(Request::Input('tiche')),
            'baoxian'=>serialize(Request::Input('baoxian')),
            ]);
        if(!$affectedRows) return '更新订单属性出错';

        if ($this->order->changeOrderStatus(Request::Input('order_num'),$next_status,$_ENV['_CONF']['config']['hg_order']['order_jiaoche'])!==false)
            {

                // 记录日志

                if(HgCartLog::putLog(Request::Input('id'),session('user.member_id'),$_ENV['_CONF']['config']['hg_order']['order_jiaoche_sent_notify'],"CartController/getYuyue","客户填写交车邀请资料",0,'客户反馈交车通知时间'))
                {

                    switch ($next_status) {
                        case '402':
                            return redirect(route('cart.yuyueok', ['id' =>Request::Input('order_num') ]));
                            break;

                        case '403':
                            return redirect(route('cart.yuyueno', ['id' =>Request::Input('order_num') ]));
                            break;
                    }

                }else{
                    return '记录写入失败';
                }

            }else{
                return '更新订单状态出错';
            }


    }

    /*
    *客户提交完资料等待代理确认
    */
    public function yuyueConfirm($order_num)
    {
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$cart_status= $order->cart_status;
    	$cart_sub_status=$order->cart_sub_status;
    	$view=[
    			'title'=>'预约完成',
    			'order_num'=>$order_num,
    	];
    	// TODO 测试的获取订单的详细信息
    	$view['order'] = $this->order->getOrderAllInfoById($order_num, true);
    	$order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
    	$view['order_attr']=$order_attr;
    	/*
    	 * 各项时间
    	 */
    	// 取得诚意金进入时间
    	$view['earnest_time']=HgCartLog::get_earnest_time($order->id);
    	// 经销商反馈订单时间
    	$view['feedback_time']=HgCartLog::get_feedback_time($order->id);
    	//  担保金进去加信宝时间
    	$view['deposit_time']=HgCartLog::get_deposit_time($order->id);
    	// 收到担保金响应订单时间,经销商开始执行订单时间
    	$view['response_time']=HgCartLog::get_response_time($order->id);
    	// 经销商代理发出交车通知时间
    	$view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
    	// 取得报价信息
    	$bj_id=$view['order']['cartBase']['bj_id'];
    	$bj=HgBaojia::getBaojiaInfo($bj_id)->toArray();
    	// 品牌，车型
    	$bj['brand']=explode('&gt;',$bj['gc_name']);
    	//获取品牌车型的基本信息
    	$barnd_info=HgGoodsClass::getCarBase($bj['brand_id']);
    	$bj['barnd_info']=$barnd_info;
    	// 车型扩展信息
    	$carFields = HgFields::getFieldsList('carmodel');
    	if (empty($carFields)) {
    		// TODO 没有查找到对应的车型扩展信息
    		exit('没有查找到对应的车型扩展信息');
    	}
    	foreach ($carFields as $k => $v) {
    		$carFields[$k] = unserialize($v);
    	}
    	//报价扩展信息包括其他费用
    	$bj['more']=HgBaojiaMore::getBaojiaMove($bj['bj_id']);
    	$other_price = array();
    	if(array_key_exists('other_price',$bj['more'])){

    		foreach ($bj['more']['other_price'] as $key => $value) {
    			// 取得自定义字段标题
    			$other_title=HgFields::where('name','=',$key)->first();
    			$other_price[$other_title['title']]=$value;

    		}
    	}
    	$bj['other_price']=$other_price;
    	/**
    	 * 获取该车型本身的数据信息----车型
    	 * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
    	 */
    	$carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
    	/**
    	 * 该报价对应车型本身信息----报价
    	 * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
    	*/
    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
    	foreach ($bjCarInfo as $key => $val) {
    		if (is_array($carmodelInfo[$key])) {
    			$carmodelInfo[$key] = $carmodelInfo[$key][$val];
    		} else {
    			$carmodelInfo[$key] = $val;
    		}
    	}

    	// 合并该报价对应车型的具体参数
    	$bj = array_merge($bj, $carmodelInfo);
    	// 支付方式
    	$bj['payTitle'] = $_ENV['_CONF']['base']['payType'][$bj['bj_pay_type']];
    	// 国别
    	$bj['guobieTitle'] = $carFields['guobie'][$bj['guobie']];
    	// 排放
    	$bj['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
    	// 补贴
    	$bj['butieTitle'] = $carFields['butie'][$bj['butie']];


    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
    	// 系统设置的金额，如诚意金
    	$view['sysprice']=HgMoney::getMoneyList();
    	// 经销商信息
    	$view['jxs']=HgDealer::getDealerInfo($view['order']['cartBase']['dealer_id'])->toArray();

    	$view['bj']=$bj;

    	/*
    	 * 各种费用
    	 */
    	// 其他费用
    	$other_price_total=0.00;
    	foreach ($other_price as $key => $value) {
    		$other_price_total+=$value;
    	}
    	$view['other_price_total']=$other_price_total;
    	//用户选择的选装件总价
    	$xzj_total=0.00;
    	$userxzj=HgUserXzj::getUserXzj($order_num)->toArray();
    	foreach ($userxzj as $key => $value) {
    		$xzj_total+=$value['discount_price']*$value['num'];
    	}
    	$view['xzj_total']=$xzj_total;
    	// 办理特殊文件费用
    	$fl_price=0.00;
    	$ziliao=unserialize($order_attr->wenjian);
    	if ($ziliao) {
    		foreach ($ziliao as $key => $value) {
    			$fl_price+=$value['fee'];
    		}
    	}

    	$view['fl_price']=$fl_price;

    	$view['trip_way']=unserialize($order_attr->trip_way);
    	$view['jc_date']=unserialize($order_attr['jc_date']);
    	/*
    	 *按场合查看文件
    	*/
    	$view['files1']=HgOrderFiles::getByCate($order_num);
    	// 按文件名查看
    	$view['files2']=HgOrderFiles::getByName($order_num);
    	// 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);

    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($view['order']['cartBase']['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;

    	$baoxian_type=getBaoxianType($order['buytype'],$bj['seat_num']);//根据车座判断保险类型 add by jerry
    	// 默认保险价格供参考
    	$chesun =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
    	$daoqiang=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
    	$sanzhe =HgBaojiaBaoxianSanzhePrice::getPrice($bj['bj_id'],$baoxian_type);
    	$renyuan =HgBaojiaBaoxianRenyuanPrice::getPrice($bj['bj_id'],$baoxian_type);
    	$boli =HgBaojiaBaoxianBoliPrice::getPrice($bj['bj_id'],$baoxian_type);
    	$huahen =HgBaojiaBaoxianHuahenPrice::getPrice($bj['bj_id'],$baoxian_type);
    	// 总的保险价格
    	$bxprice=$chesun+$daoqiang+$sanzhe+$renyuan+$boli+$huahen;
    	$view['bxprice']=$bxprice['count']*$bj['bj_baoxian_discount']/100;
    	$orderAttr = HgCartAttr::where('cart_id', '=', $order->id)->first();
    	$view['zhuanyuan'] = unserialize($orderAttr->waiter);
    	$view['ticheren'] = unserialize($orderAttr->ticheren);
    	$view["take_way"] = $orderAttr->take_way;
    	$dates = array();
    	$jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
    	$jiaoche_m=date('m',strtotime($order['jiaoche_time']));
    	$jiaoche_d=date('d',strtotime($order['jiaoche_time']));
    	for ($i=14; $i >=0 ; $i--) {
    		$dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
    	}
    	$view['dates']=$dates;
    	$view['pdi_date_dealer']=explode(',', $view['order_attr']['pdi_date_dealer']);
    	// 客户选择的日期
    	$view['pdi_date_client']=explode(',', $view['order_attr']['pdi_date_client']);

        return view('cart.yuyueconfirm',$view);
    }

    /*
    预约反馈不ok
    */
    public function YuYueNo($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
	 $view = [
            'title' => '交车邀请中，客户有特别需求提出',
            'order_num'=>$order_num,
        ];
        /*
        *客户信息
        */

        $view['buyer']=HgUser::getMember($order->buy_id);

        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        // 该订单当前状态及其防伪验证
        $cart_status = [
            'id'                => $order->id, // 订单ID
            'bj_id'             => $order->bj_id, // 订单对应的报价ID
            'buy_id'            => $order->buy_id, // 消费者ID
            'seller_id'         => $order->seller_id, // 经销商代理ID
            'order_num'         => $order->order_num, // 订单编号
            'car_id'            => $order->car_id, // 车型ID
            'cart_status'       => $order->cart_status, // 订单主状态
            'cart_sub_status'   => $order->cart_sub_status, // 订单子状态
            'hash'              => md5(
                $order->id.$order->buy_id.$order->seller_id.$order->cart_sub_status), // 订单防伪验证
        ];
        // 保存该订单信息
        session()->put('cart', $cart_status);

        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($order_num, true);
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
        // 取得报价信息
        $bj_id=$view['order']['cartBase']['bj_id'];

        $bj=HgBaojia::getBaojiaInfo($bj_id)->toArray();
        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($bj['brand_id']);
        $bj['barnd_info']=$barnd_info;
        // 车型扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        if (empty($carFields)) {
            // TODO 没有查找到对应的车型扩展信息
            exit('没有查找到对应的车型扩展信息');
        }
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        //报价扩展信息包括其他费用
        $bj['more']=HgBaojiaMore::getBaojiaMove($bj['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$bj['more'])){

            foreach ($bj['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;

            }
        }
        $bj['other_price']=$other_price;
        /**
         * 获取该车型本身的数据信息----车型
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }

        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);
        // 支付方式
        $bj['payTitle'] = $_ENV['_CONF']['base']['payType'][$bj['bj_pay_type']];
        // 国别
        $bj['guobieTitle'] = $carFields['guobie'][$bj['guobie']];
        // 排放
        $bj['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        // 补贴
        $bj['butieTitle'] = $carFields['butie'][$bj['butie']];
        // 保险公司信息
        if ($bj['bj_bx_select']) {
            $view['baoxianname']=HgBaoXian::getName($bj['bj_bx_select']);
        }
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($bj['bj_id']);
        $bj['xzj']=$xzjs->toArray();
        // 判断是否有原厂已安装选装件
        $yc_xzj=0;
        foreach ($bj['xzj'] as $key => $value) {
            if($value['is_install'] && $value['xzj_yc']) $yc_xzj++;
        }
        $bj['yc_xzj']=$yc_xzj;
        // 报价地区
        $area=HgBaojiaArea::getBaojiaArea($bj['bj_id']);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($area)){
            $bj['area']=$area;
            $bj['area_xianpai']=$area_names_xianpai;

        }else{
                foreach ($area as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }

            $bj['area']=$area_names;
            $bj['area_xianpai']=$area_names_xianpai;

        }

        $view['bj']=$bj;


        // 经销商地址
        $dealer=HgDealer::getDealerInfo($bj['dealer_id']);
        $view['dealer_area']=$dealer->d_shi;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($view['order']['cartBase']['dealer_id'])->toArray();
        // 车身颜色，内饰颜色，排放标准，实际里程，出厂年月重新赋值
        // 取得订单修改的所有信息
        $cartattr=HgCartAttr::GetOrderAttr(array('cart_id'=>$order->id));

        if (!empty($cartattr->mileage)) {
            $view['bj']['bj_licheng']=$cartattr->mileage;
        }
        if (!empty($cartattr->production_date)) {
            $view['bj']['bj_producetime']=$cartattr->production_date;
        }
        // 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
            $view['bj']['body_color']=$carinfo['body_color'];
            $view['bj']['interior_color']=$carinfo['interior_color'];
            $view['bj']['paifangTitle']=$carinfo['paifang'];
        }
        // 免费礼品
        // $view['zengpin']=unserialize($cartattr->zengpin);
        // 约定交车的时限
        $view['jiaoche_time']=date('Y-m-d',strtotime($order->jiaoche_time));
        // 代理选择的交车日期
        $jiaoche_date=($cartattr->pdi_date_dealer);
        $view['jiaoche_date']=array_filter(explode(',',$jiaoche_date));

        //  领取国家补贴需要的文件
        $butiefile=HgOrderFiles::getFile($order_num,4);
        $butiefiles='';
        foreach ($butiefile as $key => $value) {
            if($value['num']==0) continue;
            $butiefiles.=$value['title'].':'.$value['num'].'份   ';
        }

        $view['butiefile']=$butiefiles;
        // 上牌需要的资料
        $shangpai_file=HgOrderFiles::getFile($order_num,2);
        $shangpaifiles='';
        foreach ($shangpai_file as $key => $value) {
            $shangpaifiles.=$value['title'].':'.$value['num'].'份   ';
        }
        $view['shangpai_file']=$shangpaifiles;
        // 上临牌需要的文件
        $linpai_file=HgOrderFiles::getFile($order_num,3);
        $linpaifiles='';
        foreach ($linpai_file as $key => $value) {
            $linpaifiles.=$value['title'].':'.$value['num'].'份   ';
        }
        $view['linpai_file']=$linpaifiles;
        // 投保需要的文件
        $toubao_file=HgOrderFiles::getFile($order_num,1);
        $toubaofiles='';
        foreach ($toubao_file as $key => $value) {
            $toubaofiles.=$value['title'].':'.$value['num'].'份   ';
        }
        $view['toubao_file']=$toubaofiles;

        $view['baoxian']=unserialize($cartattr->baoxian);

        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
       // 代理供选择的日期
       $view['pdi_date_dealer']=explode(',', $view['order']['cartAttr']['pdi_date_dealer']);
       // 客户选择的日期
       $view['pdi_date_client']=explode(',', $view['order']['cartAttr']['pdi_date_client']);

        // 其他商业保险
        $view['otherbaoxian']=HgOtherBaoxian::getOtherBaoxian();
        $view['zhuxian']=($_ENV['_CONF']['config']['otherbaoxian']);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        $view['ticheren']= unserialize($view['order']['cartAttr']['ticheren']);
        $view['other_baoxian'] = !empty($view['order']['cartAttr']['other_baoxian'])?unserialize($view['order']['cartAttr']['other_baoxian']):array();
        // 是否同意按流程办理补贴
        $view['guojiabutie']=$cartattr['butie'];
        return view('cart.yuyue_no',$view);

    }
    // 再次确认
    public function yuYueNoConfirm($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view=[
            'order_num'=>$order_num,
            'id'=>$order->id,
        	'title' => '交车邀请中，客户有特别需求提出 经销商已经进行回复，待客户确认',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();

        // 订单的属性
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        $pdiReply = !empty($order_attr['pdi_reply_especial_request'])?unserialize($order_attr['pdi_reply_especial_request']):array();
        $view['pdiReply'] = $pdiReply;
        $view['order_attr'] = $order_attr;
        $view['order'] = $order;
        $view['ticheren'] = unserialize($order_attr['ticheren']);
        // 提车人需要准备的文件资料
        $str='';
        $needfile=$pdiReply['needfile'];
        foreach (array_filter($needfile['toubao']) as $key => $value) {
            $vv=explode(',', $value);
            $substr=$vv[0].$vv[1].'份';
        }
        $str.='投保：'.$substr.' ';
        foreach (array_filter($needfile['shangpai']) as $key => $value) {
            $vv=explode(',', $value);
            $substr=$vv[0].$vv[1].'份';
        }
        $str.='上牌：'.$substr.' ';
        foreach (array_filter($needfile['linpai']) as $key => $value) {
            $vv=explode(',', $value);
            $substr=$vv[0].$vv[1].'份';
        }
        $str.='上临牌：'.$substr.' ';
        if(isset($needfile['butie'])){
	        foreach (array_filter($needfile['butie']) as $key => $value) {
	            $vv=explode(',', $value);
	            $substr=$vv[0].$vv[1].'份';
	        }
        }
        $str.='国家节能补贴：'.$substr.' ';
        $view['needfile']=$str;
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;
        return view('cart.yuyuenoconfirm',$view);
    }
    // 保存再次确认的数据
    public function postYuyueNoConfirm()
    {
        //存送车方式
    	$data['songche'] = Request::Input('songche');

    	//存保险
    	$baoxian = Request::Input('baoxian');
    	$data['baoxian'] = array();
    	if(!empty($baoxian)){
    		foreach($baoxian as $k => $v){
    			$data['baoxian'][] =$k;
    		}
    	}

    	//存可以办理的项目
    	$project_ok = Request::Input('project_ok');
    	$data['project_ok'] = array();
    	if(!empty($project_ok)){
    		foreach($project_ok as $k => $v){
    			$data['project_ok'][] =$v;
    		}
    	}
    	//存可以办理的服务或者赠品
    	$service_ok = Request::Input('service_ok');
    	$data['service_ok'] = array();
    	if(!empty($service_ok)){
    		foreach($service_ok as $k => $v){
    			$data['service_ok'][] =$v;
    		}
    	}
    	$user_data['user_choose'] = serialize($data);

    	//更新入订单附加属性
    	$affectedHgCartAttrRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($user_data);

        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_jiaoche'],'cart_sub_status'=>405]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }

        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),404,"CartController/yuYueNoConfirm","再次确认预约资料",0,'交车预约达成时间');
        return redirect(route('cart.yuyueconfirmok', ['order_num' =>Request::Input('order_num') ]));

    }
    // 再次确认ok
    public function yuYueConfirmOk($order_num)
    {
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'id'=>$order->id,
    			'title' => '交车进行中，等待经销商进行交车确认',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();

    	// 订单的属性
    	$order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);

    	//获取经销商对特殊要求的回复
    	$pdiReply = !empty($order_attr['pdi_reply_especial_request'])?unserialize($order_attr['pdi_reply_especial_request']):array();
    	$view['pdiReply'] = $pdiReply;
    	// 提车人需要准备的文件资料
        $str='';
        if (!empty($pdiReply['needfile'])) {
            $needfile=$pdiReply['needfile'];
            foreach (array_filter($needfile['toubao']) as $key => $value) {
                $vv=explode(',', $value);
                $substr=$vv[0].$vv[1].'份';
            }
            $str.='投保：'.$substr.' ';
            foreach (array_filter($needfile['shangpai']) as $key => $value) {
                $vv=explode(',', $value);
                $substr=$vv[0].$vv[1].'份';
            }
            $str.='上牌：'.$substr.' ';
            foreach (array_filter($needfile['linpai']) as $key => $value) {
                $vv=explode(',', $value);
                $substr=$vv[0].$vv[1].'份';
            }
            $str.='上临牌：'.$substr.' ';
            if(isset($needfile['butie'])){
	            foreach (array_filter($needfile['butie']) as $key => $value) {
	                $vv=explode(',', $value);
	                $substr=$vv[0].$vv[1].'份';
	            }
            }
            $str.='国家节能补贴：'.$substr.' ';
        }

        $view['needfile']=$str;
    	//获取用户的选择
    	$user_choose = !empty($order_attr['user_choose'])?unserialize($order_attr['user_choose']):array();
    	$view['user_choose'] = $user_choose;

    	$view['order_attr'] = $order_attr;
    	$view['order'] = $order;
    	$view['ticheren'] = unserialize($order_attr['ticheren']);
    	/**
    	 * 获取订单日志
    	*/
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	$view['cart_log'] = $log;
        return view('cart.yuyueconfirmok',$view);

    }
    /*
    预约反馈ok
    */
    public function YuYueOk($order_num){
        $ref=session('_previous');
        Session::flash('backurl', $ref['url']);
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view = [
            'title' => '确认交车邀请',
            'order_num'=>$order_num,
        ];
        $view['order']=$order;
        // 取得报价信息
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        $bj = array_merge($bj, $carmodelInfo);

        $view['bj']=$bj;
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 反馈交车通知
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order->id);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('cart.yuyue_ok',$view);
    }
    /*
    预约完成
    */
    public function YuYueEnd($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $cart_status= $order->cart_status;
        $cart_sub_status=$order->cart_sub_status;
        $view=[
            'title'=>'预约完成',
            'order_num'=>$order_num,
            'id'=>$order->id,
        ];
        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($order_num, true);
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        $view['order_attr']=$order_attr;
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 取得报价信息
        $bj_id=$view['order']['cartBase']['bj_id'];
        $bj=HgBaojia::getBaojiaInfo($bj_id)->toArray();
        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($bj['brand_id']);
        $bj['barnd_info']=$barnd_info;
        // 车型扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        if (empty($carFields)) {
            // TODO 没有查找到对应的车型扩展信息
            exit('没有查找到对应的车型扩展信息');
        }
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        //报价扩展信息包括其他费用
        $bj['more']=HgBaojiaMore::getBaojiaMove($bj['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$bj['more'])){

            foreach ($bj['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;

            }
        }
        $bj['other_price']=$other_price;
        /**
         * 获取该车型本身的数据信息----车型
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }

        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);
        // 支付方式
        $bj['payTitle'] = $_ENV['_CONF']['base']['payType'][$bj['bj_pay_type']];
        // 国别
        $bj['guobieTitle'] = $carFields['guobie'][$bj['guobie']];
        // 排放
        $bj['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        // 补贴
        $bj['butieTitle'] = $carFields['butie'][$bj['butie']];


        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
        // 系统设置的金额，如诚意金
        $view['sysprice']=HgMoney::getMoneyList();
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($view['order']['cartBase']['dealer_id'])->toArray();

        $view['bj']=$bj;

        /*
        * 各种费用
        */
        // 其他费用
        $other_price_total=0.00;
        foreach ($other_price as $key => $value) {
            $other_price_total+=$value;
        }
        $view['other_price_total']=$other_price_total;
        //用户选择的选装件总价
        $xzj_total=0.00;
        $userxzj=HgUserXzj::getUserXzj($order_num)->toArray();
        foreach ($userxzj as $key => $value) {
            $xzj_total+=$value['discount_price']*$value['num'];
        }
        $view['xzj_total']=$xzj_total;
        // 办理特殊文件费用
        $fl_price=0.00;
        $ziliao=unserialize($order_attr->wenjian);
        if ($ziliao) {
            foreach ($ziliao as $key => $value) {
                $fl_price+=$value['fee'];
            }
        }

        $view['fl_price']=$fl_price;

        $view['trip_way']=unserialize($order_attr->trip_way);
        $view['jc_date']=unserialize($order_attr['jc_date']);
        /*
        *按场合查看文件
       */
       $view['files1']=HgOrderFiles::getByCate($order_num);
       // 按文件名查看
       $view['files2']=HgOrderFiles::getByName($order_num);
       // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);

        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($view['order']['cartBase']['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;

        $baoxian_type=getBaoxianType($order['buytype'],$bj['seat_num']);//根据车座判断保险类型 add by jerry
        // 默认保险价格供参考
        $chesun =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
        $daoqiang=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
        $sanzhe =HgBaojiaBaoxianSanzhePrice::getPrice($bj['bj_id'],$baoxian_type);
        $renyuan =HgBaojiaBaoxianRenyuanPrice::getPrice($bj['bj_id'],$baoxian_type);
        $boli =HgBaojiaBaoxianBoliPrice::getPrice($bj['bj_id'],$baoxian_type);
        $huahen =HgBaojiaBaoxianHuahenPrice::getPrice($bj['bj_id'],$baoxian_type);
        // 总的保险价格
        $bxprice=$chesun+$daoqiang+$sanzhe+$renyuan+$boli+$huahen;
        $view['bxprice']=$bxprice['count']*$bj['bj_baoxian_discount']/100;
        $orderAttr = HgCartAttr::where('cart_id', '=', $order->id)->first();
        $view['zhuanyuan'] = unserialize($orderAttr->waiter);
        $view['ticheren'] = unserialize($orderAttr->ticheren);
        $view["take_way"] = $orderAttr->take_way;
        /**
         * 获取订单日志
         */

        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;

        return view('cart.yuyue_end',$view);
    }
    // 更改订单状态，进入提车阶段
    public function goTiche()
    {
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>500]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),$_ENV['_CONF']['config']['hg_order']['order_jiaoche_end'],"CartController/YuYueEnd","预约完成",0,"客户预约完成时间");
        return redirect(route('cart.ticheinfo', ['order_num' =>Request::Input('order_num') ]));
    }
    /**
     * 选装件独立页面
     * @param $order_num
     * @return \Illuminate\View\View
     */
    public function xzjUserGetList($order_num){

    	$ref=session('_previous');
    	Session::flash('backurl', $ref['url']);
    	// 检查该订单编号是否属于该会员，同时是未支付诚意金状态
    	if (! ($order_info = $this->order->checkOrder($order_num))) {
    		// TODO 这里应该做一个无该订单的一个提示
    		dd('订单错误');
    	} elseif ($order_info->buy_id != session('user.member_id')) {
    		// TODO 这里提示订单不属于该会员
    		dd('无此订单，请确认订单号正确');

    	} elseif ($order_info->cart_status == 0) {
    		// TODO 提示订单已经取消
    		dd('订单已经取消');
    	}

    	// 担保金
    	$money=$this->order->getDepositMoney($order_info->bj_id, true);
    	$view['money']=$money;

    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view['order'] = $order;


    	// 取得报价单信息
    	$baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
    	/*记录发出交车邀请日期和交车时限*/
    	// 现车

    	if ($baojia['bj_producetime']) {
    		$base_date=strtotime(HgCartLog::get_deposit_time($order['id']));
    		$jiaoche_time=date('Y-m-d H:i:s',strtotime('15 days',$base_date));
    		$jiaoche_notice_time=date('Y-m-d H:i:s',strtotime('7 days',$base_date));
    	}
    	// 非现车
    	if ($baojia['bj_jc_period']) {
    		$base_date=strtotime(HgCartLog::get_deposit_time($order['id']));
    		$jiaoche_time=date ( 'Y-m-d H:i:s' ,  strtotime ( $baojia['bj_jc_period'].' months',$base_date ));
    		$view['new_date']=date('Y年m月d日',strtotime($jiaoche_time));
    		$jiaoche_notice_time=date ('Y-m-d H:i:s' ,  strtotime ('-7 days',strtotime($jiaoche_time) ));
    		$view['date_tiqian']=date('Y年m月d日',strtotime($jiaoche_notice_time));
    	}


    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	// 获取歉意金
    	$view['jine']=HgMoney::getMoneyList();
    	$view['order_num']=$order_num;
    	$view['baojia']=$baojia;
    	$view['title']='选装件修改';

    	// 支付完担保金的时间
    	$view['t']=time()-$base_date;

    	// 可供选择的原厂选装件
    	$view['xzj_daili']=HgXzjDaili::getOtherXzj($order['car_id'],$order['dealer_id'],$order['seller_id'],$baojia['bj_id']);
    	if(count($view['xzj_daili'])>0){
    		$view['xzj_daili'] = $view['xzj_daili']->toArray();
    	}else{
    		$view['xzj_daili'] = array();
    	}

    	// 代理推荐的，可供选择的非原厂选装件
    	$view['fycxzj_daili']=HgFycXzj::getByOrder($order_num);

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


    	return view('cart.xzjusergetlist',$view);
    }

    /**
     * 选装件提交
     * @param $id
     * @return \Illuminate\View\View
     */
    public function xzjPost(){
        $tj_type = Request::Input("tj_type");
        $order_num = Request::Input("order_num");
        $ycxzj_num = Request::Input("ycxzj_num");
        $ycxzj_title = Request::Input("ycxzj_title");
        $ycxzj_model = Request::Input("ycxzj_model");
        $ycxzj_guide_price = Request::Input("ycxzj_guide_price");
        $ycxzj_fee = Request::Input("ycxzj_fee");
        $discount_price = Request::Input("discount_price");
        $ycxzj_is_yc = Request::Input("ycxzj_is_yc");
        $ycxzj_is_front = Request::Input("ycxzj_is_front");
        $xzj_brand = Request::Input("xzj_brand");
        if(count($ycxzj_num)>=1){
            foreach($ycxzj_num as $k=>$v){
                if($v>0 ||($v ==0 && $tj_type == 'xzj_modify')){//新添加有数据才保存；修改为0时需要判断
                    $data= array(
                                        "id"=>$k,
                                        "member_id"=>session('user.member_id'),
                                        "xzj_name"=>$ycxzj_title[$k],
                                        "xzj_model"=>$ycxzj_model[$k],
                                        "guide_price"=>$ycxzj_guide_price[$k],
                                        "fee"=>$ycxzj_fee[$k],
                                        "discount_price"=>$discount_price[$k],
                                        "num"=>$v,
                                        "is_yc"=>$ycxzj_is_yc[$k],
                                        "order_num"=>$order_num,
                                        "xzj_front"=>$ycxzj_is_front[$k],
                                        "xzj_brand"=>$xzj_brand[$k],
                                        );
                    // 检测提交的数量是否大于库存
                    $kucun=HgXzjDaili::where('id', $k)->first();
                    if ($kucun['xzj_has_num']<$v) {
                        dd('选择 '.$ycxzj_title[$k].' 的数量超出了库存数量');
                    }
                    $a=HgUserXzj::where('order_num','=',$order_num)->where('id','=',$k)->first();
                    if($tj_type == 'xzj_modify'){
                    	if($a){

                    		$dataUp = array();
                    		$dataUp['xzj_status'] = 1;
                    		$dataUp['xzj_modify'] = $v;
                    		 if($v > $a->num){ //增加有操作   减少的话需要代理同意才行
                    		 	$d=HgXzjDaili::where('id', $k)->decrement('xzj_has_num', $v - $a->num);
                    		 	$dataUp['num'] = $v;//如果增加的话 num  需要更新
                    		 }

                    		 //print_r($dataUp);
                    		 // 如果已经添加过，则直接update
                    		 $u=HgUserXzj::where('order_num','=',$order_num)->where('id','=',$k)->update($dataUp);
                    		 if($u === false ) dd("选装件保存失败，请返回重新选择");
                    	}else{
                    		$data['xzj_status'] = 1;
                    		$data['num'] = 0;
                    		$data['xzj_modify'] = $v;
                    		$i=HgUserXzj::insert($data);
                    		$d=HgXzjDaili::where('id', $k)->decrement('xzj_has_num', $v);
                    		if(!$i || !$d) dd("选装件保存失败，请返回重新选择");
                    	}
                    }else{
                    	if($a){
                    		//存在不需要操作
                    	}else{
                    		$i=HgUserXzj::insert($data);
                    		$d=HgXzjDaili::where('id', $k)->decrement('xzj_has_num', $v);
                    		if(!$i || !$d) dd("选装件保存失败，请返回重新选择");
                    	}
                    }



                }
            }
        }
		if($tj_type == "xzj"){
	    	return redirect(route('cart.yuyuefirst', ['id' =>$order_num ]));
		}elseif($tj_type == "xzj_2"){
			return redirect(route('cart.xzjusergetlist', ['id' =>$order_num ]));
		}elseif($tj_type == "xzj_3"){
			return redirect(route('cart.waitnotice', ['id' =>$order_num ]));
		}elseif($tj_type =='ycxzj'){
			return redirect(route('pay.depositok', ['id' =>$order_num ]));
		}else{
			if(!empty(session('backurl'))){
				header("Location:".session('backurl'));
			}else{
				return redirect(route('getmyorder', ['id' =>$order_num ]));
			}
		}


    }

    /**
     * 提车信息确认
     * @param $id
     * @return \Illuminate\View\View
     */
    public function getTiche($id)
    {
        if (! $order = $this->order->getOrder($id, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }

        $view = [
            'title' => trans('common.cart.tiche'),
        ];

        // 该订单当前状态及其防伪验证
        $cart_status = [
            'id'                => $order->id, // 订单ID
            'bj_id'             => $order->bj_id, // 订单对应的报价ID
            'buy_id'            => $order->buy_id, // 消费者ID
            'seller_id'         => $order->seller_id, // 经销商代理ID
            'order_num'         => $order->order_num, // 订单编号
            'car_id'            => $order->car_id, // 车型ID
            'cart_status'       => $order->cart_status, // 订单主状态
            'cart_sub_status'   => $order->cart_sub_status, // 订单子状态
            'hash'              => md5(
                $order->id.$order->buy_id.$order->seller_id.$order->cart_sub_status), // 订单防伪验证
        ];

        // 返回当前状态和下一步状态
        switch(get_order_status($order->cart_sub_status)) {
            case 'order_tiche_info_check':
                // 下一步:消费者已确认提车信息,进行线下提车...
                $cart_status['next'] = 'order_tiche_offline';
                break;
        }

        // 保存该订单信息
        session()->put('cart', $cart_status);

        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($id, true);

        return view('cart.tiche', $view);
    }
    // 交车前提醒
    public function ticheTixing(){
        return view('cart.tichetixing');
    }
    // 到交车时间
    public function ticheNow(){
        return view('cart.tichenow');
    }
    // 填写交车信息
    public function ticheInfo($order_num)
    {
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view['order_num']=$order_num;
        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($order_num, true);
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        $view['order_attr']=$order_attr;
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 客户反馈交车通知时间
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
        // 取得报价信息
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        $bj = array_merge($bj, $carmodelInfo);

        $view['bj']=$bj;
        // 客户选择的选装件
        $view['userxzj']=HgUserXzj::getUserXzj($order_num)->toArray();
        // 客户选择的赠品
        $view['zengpin']=HgUserZengpin::getZengpin($order_num)->toArray();
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);
        // 车辆的最终信息
       $pdi_carinfo=unserialize($view['order_attr']->pdi_carinfo);

       // 如果代理先提交则读取代理的数据，否则从订单中读取
       if ($pdi_carinfo) {
           $view['fin_car_info']=$pdi_carinfo;
           if ($view['order']['cartBase']['shangpai']) {
               Session::put('next_status', 504);
           }else{
                Session::put('next_status', 505);
           }

       }else{
            $fin_car_info['vin']=$view['order_attr']->vin;
            $fin_car_info['engine_no']=$view['order_attr']->engine_no;
            $fin_car_info['shangpai_area']=$view['shangpai_area'];
            $fin_car_info['yongtu']=carUse($order['buytype']);
            $fin_car_info['reg_name']=($order['reg_name']);

            // 车牌号
            $chepai=Area::getPaizhao($order['shangpai_area']);
            array_push ( $chepai ,  1 ,  1,1,1,1 );
            $fin_car_info['chepai']=$chepai;
            // $view['chepai']=$chepai;
            $view['fin_car_info']=$fin_car_info;
            Session::put('next_status', 503);
       }
       $view['chepai']=$view['fin_car_info']['chepai'];
       // 各省名称id
       $view['sheng']=Area::getTopArea()->toArray();

       /**
        * 获取订单日志
        */

       $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
       $view['cart_log'] = $log;

        return view('cart.ticheinfo',$view);
    }
    // 保存提交的车辆信息
    public function saveTicheInfo()
    {
        $order_num = Request::Input("order_num");
        //获取交车信息表中的数据
        $jiaoche_map = array('order_num'=>$order_num);
        $jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
        
        // 要更新的数据库字段
        $feilds = array('guobie','peizhi','jxs','address','jiaoche','price','biaozhun','csys','nsys','licheng','chuchang','xzj','baoxian','shangpai','linpai','other','wenjian','gongju','huicheng','butie',);
        HgVerify::where("order_num","=",$order_num)->delete();//先执行删除原先数据（可能造成数据重复提交）
        foreach ($feilds as $key => $value) {
           // HgVerify::where('item', '=', $value)->update(['accord'=>Request::Input($value),'notice'=>Request::Input($value.'_notice'),'order_num'=>$order_num]);
            HgVerify::insert(['item'=>$value,'accord'=>Request::Input($value),'notice'=>Request::Input($value.'_notice'),'order_num'=>$order_num]);
        }
        // 更新选装件是否相符
        $ycxzj_notice=Request::Input('ycxzj_notice');
        if (Request::Input('ycxzj')) {
            foreach (Request::Input('ycxzj') as $key => $value) {
                HgUserXzj::where('id','=',$key)->update(['accord'=>$value,'notice'=>$ycxzj_notice[$key]]);
            }
        }
        // 赠品是否相符
        $zp_notice=Request::Input('zp_notice');
        if (Request::Input('zengpin')) {
            foreach (Request::Input('zengpin') as $key => $value) {
                HgUserZengpin::where('id','=',$key)->update(['accord'=>$value,'notice'=>$zp_notice[$key]]);
            }
        }
	/**
        $che = array(
                'vin' => Request::Input('vin'),
                'engine_no' => Request::Input('engine_no'),
                'shangpai_area' => Request::Input('sheng').' '.Request::Input('shi'),
                'yongtu' => Request::Input('yongtu'),
                'reg_name' => Request::Input('reg_name'),
                'chepai'=>Request::Input('chepai'),
                'fafang_butie' => Request::Input('fafang_butie'),
                'shangpai_time'=>Request::Input('shangpai_time'),
             );
     **/   
        $jiaoche = array(
        		'user_vin' => Request::Input('vin'),
        		'user_engine_no' => Request::Input('engine_no'),
        		'user_shangpai_area' => Request::Input('sheng').' '.Request::Input('shi'),
        		'user_useway' => Request::Input('yongtu'),
        		'user_regname' => Request::Input('reg_name'),
        		'user_chepai'=>!empty(Request::Input('chepai'))?serialize(Request::Input('chepai')):'',
        		'user_butie_date' => Request::Input('fafang_butie'),
        		'user_shangpai_time'=>Request::Input('shangpai_time'),
        );
        if(count($jiaocheInfo)>0){
        	$jiaoche['user_date_update']=date('Y-m-d H:i:s');
        	HgCartJiaoche::where('order_num', '=', Request::Input('order_num'))->update($jiaoche);
        }else{
        	$jiaoche['order_num']=Request::Input('order_num');
        	$jiaoche['user_date_first']=date('Y-m-d H:i:s');
        	$jiaoche['user_date_update']=date('Y-m-d H:i:s');
        	$jiaoche['create_at']=date('Y-m-d H:i:s');
        	HgCartJiaoche::insert($jiaoche);
        }
        
        /**
        // 保存车辆信息
        $map = array(
            'user_carinfo'=>serialize($che),

            );
        // 更新提交的数据
        $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($map);
        if (!$affectedRows) {
            dd('保存最终车辆信息出错');
        }
        **/
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>Session::get('next_status')]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),$_ENV['_CONF']['config']['hg_order']['order_tiche_pdi_ok'],"CartController/ticheInfo","客户提交车辆最终信息",0,"客户提交车辆最终信息时间");
        return redirect(route('cart.ticheend', ['order_num' =>Request::Input('order_num') ]));
    }
    // 交车完成
    public function ticheOK($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view['order_num']=$order_num;
        $view['title']='交车完成';
        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($order_num, true);
        //print_r($view['order']);exit;
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
        /*
        * 各项时间
        */

        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 客户反馈交车通知时间
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);

        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;

        // 取得报价信息
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        $bj = array_merge($bj, $carmodelInfo);

        $view['bj']=$bj;
        
        //获取交车信息表中的数据
        $jiaoche_map = array('order_num'=>$order_num);
        $jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
        
        if(!empty($jiaocheInfo)){
        	$jiaocheInfo = $jiaocheInfo->toArray();
        }else{
        	$jiaocheInfo = array();
        }
        $view['jiaoche'] = $jiaocheInfo;
        // 客户填写的车辆最终信息
        //$view['che']=unserialize($view['order']['cartAttr']['user_carinfo']);
        // 代理填写的车辆最终信息
       //$view['che_pdi']=unserialize($view['order']['cartAttr']['pdi_carinfo']);

        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 服务费
        $view['server_money']=HgMoney::getMoneyList();
        // 各省名称id
       $view['sheng']=Area::getTopArea()->toArray();
       // 车牌号
        $chepai=Area::getPaizhao($view['order']['cartBase']['shangpai_area']);
        array_push ( $chepai ,  8 ,  8,8,8,8 );
        if(empty($jiaocheInfo['user_chepai'])){
       	 	$view['chepai']=$chepai;
        }else{
        	$view['chepai']=unserialize($jiaocheInfo['user_chepai']);
        	
        }
        // 代理提交的车牌
       // $view['chepai_pdi']=$view['che_pdi']['chepai'];
       // $view['chepai_pdi']=!empty($view['che_pdi']['chepai'])?implode('', $view['che_pdi']['chepai']):array();
        // 补充信息最晚时间,预计上牌最晚时间加3天
        $view['bc_time']=strtotime("+3 day",strtotime($view['jiaoche']['user_shangpai_time']));
        // 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
            $view['bj']['body_color']=$carinfo['body_color'];
        }
        
        $map = array('order_num'=>$order_num,'member_type'=>1);
        $jiaocheExt = HgCartJiaocheExt::getJiaocheExtInfoList($map);
        if(!empty($jiaocheExt)){
        	$view['jiaocheExt'] = $jiaocheExt->toArray();
        }else{
        	$view['jiaocheExt'] = array();
        }
        
        return view('cart.ticheok',$view);
    }
    // 提交争议页面
    public function zhengYi($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view=[
            'order_num'=>$order_num,
            'id'=>$order->id,
        ];
        // 如果页面不是从页面链接过来的则跳出
        if(!in_array($order->cart_sub_status, ['409','500','509'])) dd('请按正常步骤进行');
        // 读取问题
        $view['wenti']=config('wenti.custem');
        $view['order']=$order;
        /*
        * 各项时间
        */

        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 客户反馈交车通知时间
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);

        $view['brand']=explode('&gt;',$order->car_name);
        // 取得报价信息
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();

        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        $bj = array_merge($bj, $carmodelInfo);

        // 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
        }
        $view['body_color']=!empty($carinfo['body_color'])?$carinfo['body_color']:$bj['body_color'];
        $view['interior_color']=!empty($carinfo['interior_color'])?$carinfo['interior_color']:$bj['interior_color'];
        /**
         * 获取订单日志
         */

        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();

        $view['cart_log'] = $log;
        return view('cart.zhengyi',$view);
    }
    // 保存提交的争议
    public function saveZhengyi()
    {
        $map = array(
                'order_num' => Request::Input('order_num'),
                'problem'=>serialize(Request::Input('wenti')),
                'content'=>Request::Input('content'),
                'member_id'=>session('user.member_id'),
            );
        $dispute=HgDispute::insertGetId($map);
        if(!$dispute) dd('插入记录数据失败');
        if (Request::file('file')) {
            foreach (Request::file('file') as $key => $value) {
                if ($value->isValid())
                {
                    $entension = $value-> getClientOriginalExtension();
                    if(!allowext($entension)) dd('文件类型不允许');
                    $fileName='p'.date('YmdHms').mt_rand(1000,9999).'.'.$entension;
                    $value->move(config('app.uploaddir').'evidence', $fileName);
                }
                $ev=HgEvidence::insert(['urls'=>$fileName,'member_id'=>session('user.member_id'),'dispute_id'=>$dispute,'order_num'=>Request::Input('order_num')]);
                if(!$ev) dd('插入证据记录数据失败');
            }
        }

        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>507]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),507,"CartController/zhengYi","客户提交争议",0,"客户提交争议时间");
        return redirect(route('cart.dispute', ['order_num' =>Request::Input('order_num') ]));
    }
    // 争议调查调解
    public function Dispute($order_num)
    {
    	if(Request::Input("act") == "tiaojie"){//是否接受平台调解
    		$itemid = Request::Input("itemid");
    		$member_id = session('user.member_id');
    		if(Request::Input("result")==0){
    			$log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1007,"CartController@Defend","调解未成功，终止订单",0,"调解未成功终止订单时间");
    			//更改调解状态 1为不接受 2为接受
    			$e = HgMediate::where(array('itemid'=>$itemid))->update(array('status'=>1)); 
    			return redirect(route('cart.dispute', ['order_num' =>Request::Input('order_num') ]));
    		}else{
    			
    			$e = HgMediate::where(array('itemid'=>$itemid))->update(array('status'=>2));
    			self::acceptMediate($order_num,1);
    			//更改调解状态 1为不接受 2为接受
    			return redirect(route('cart.mediateok', ['order_num' =>Request::Input('order_num') ]));
    		}
    	}elseif(Request::Input("act") == "hejie"){//如果和解了告知华车
    		$dispute_id = Request::Input("dispute_id");
    		$dispute_hejie = empty(Request::Input("content2"))?"同意和解":Request::Input("content2");
    		HgDispute::where("id",$dispute_id)->update(array("dispute_hejie"=>$dispute_hejie,'dispute_hejie_date'=>date('Y-m-d H:i:s')));
    		return redirect(route('cart.dispute', ['order_num' =>Request::Input('order_num') ]));
    	}elseif(Request::Input("act") == "defendfirst_resupply"){
    		$dispute_id = request::Input("dispute_id");
    		$files = Request::file('file');
    		$destinationPath = config('app.uploaddir')."evidence";
    		$fileArray = array();
    		if(count($files)>0){
    			$allFileName = array();
    			foreach($files as $K=>$file){
    				if(!empty($file) && $file->isValid()){
    					$type= $file->getClientOriginalExtension();
    					if(!allowext($type)) dd('文件类型不允许');
    					$fileName = 'p'.date('YmdHms').mt_rand(1000,9999).".".$type;
    					$file->move($destinationPath, $fileName);
    					$fileArray[] = $fileName;
    				}
    			}
    		
    		}
    		$data['resupply_evidence'] = serialize($fileArray);
    		$e = HgDispute::where(array('id'=>$dispute_id))->update($data);
    		if(!$e) dd('更新补充证据失败，请重新提交');
    		return redirect(route('cart.dispute', ['order_num' =>Request::Input('order_num') ]));
    	}
    	
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view=[
            'order_num'=>$order_num,
            'id'=>$order->id,
            'order'=>$order,
        ];
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 客户反馈交车通知时间
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);

        $view['brand']=explode('&gt;',$order->car_name);
        // 取得报价信息
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();

        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        $bj = array_merge($bj, $carmodelInfo);

        // 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
        }
        $view['body_color']=!empty($carinfo['body_color'])?$carinfo['body_color']:$bj['body_color'];
        $view['interior_color']=!empty($carinfo['interior_color'])?$carinfo['interior_color']:$bj['interior_color'];
        // 读取争议
        $dispute=HgDispute::getDispute($order_num,session('user.member_id'));
        // 争议问题
        $view['problem']=unserialize($dispute->problem);
        $view['content']=$dispute->content;
        // 提交的证据照片等
        $view['evidence']=HgEvidence::getEvidence($order_num,$dispute->id);
        $view['dispute']=$dispute->toArray();
        // 此争议的申辩信息
        $defend=HgDefend::getDefend($order_num,$dispute->id);


    	if(!empty($defend)){
    		$view['is_defend']=1;
    		$view['defend'] =$defend->toArray();
    		$defend_evidence= HgEvidence::getEvidence($order_num,$dispute->id,$view['defend']['id']);
    		if(!empty($defend_evidence)){
    			$view['defend_evidence'] = $defend_evidence->toArray();
    		}else{
    			$view['defend_evidence'] =array();
    		}

    	}else{
    		$view['is_defend']=0;
    		$view['defend'] = array();
    		$view['defend_evidence'] =array();
    	}

        // 平台调解建议
        $mediate=HgMediate::getMediate($order_num,$dispute->id);
        if(!empty($mediate)){
        	$view['mediate']=$mediate->toArray();
        }

        /**
         * 获取订单日志
         */

        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;


        return view('cart.dispute',$view);
    }
    // 经销商提交了争议，客户申辩
    public function Defend($order_num)
    {
        if(Request::Input("act") == "defendfirst"){
    		$map = array("content"=>request::Input("content"),
    				"member_id"=>session('user.member_id'),
    				"order_num"=>request::Input("order_num"),
    				"dispute_id"=>request::Input("dispute_id"),
    		);
    		$defendId = HgDefend::insertGetId($map);

    		$files = Request::file('file');
    		$destinationPath = config('app.uploaddir')."evidence";
    		if(count($files)>0){
    			$allFileName = array();
    			foreach($files as $K=>$file){
    				if(!empty($file) && $file->isValid()){
    					$type= $file->getClientOriginalExtension();
                        if(!allowext($type)) dd('文件类型不允许');
    					$fileName = 'p'.date('YmdHms').mt_rand(1000,9999).".".$type;
    					$file->move($destinationPath, $fileName);
    					$ev=HgEvidence::insert(['urls'=>$fileName,'member_id'=>session('user.member_id'),'dispute_id'=>request::Input("dispute_id"),'defend_id'=>$defendId,'order_num'=>Request::Input('order_num')]);
    					if(!$ev) dd('插入证据记录数据失败');
    				}
    			}

    		}
    		// 记录日志
    		$log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),506,"CartController@Defend","客户进行申辩",0,"客户进行申辩时间");

    		return redirect(route('cart.defend', ['order_num' =>Request::Input('order_num') ]));

    	}elseif(Request::Input("act") == "defendfirst_resupply"){
    		$defend_id = request::Input("defend_id");
    		$files = Request::file('file');
    		$destinationPath = config('app.uploaddir')."evidence";
    		$fileArray = array();
    		if(count($files)>0){
    			$allFileName = array();
    			foreach($files as $K=>$file){
    				if(!empty($file) && $file->isValid()){
    					$type= $file->getClientOriginalExtension();
    					if(!allowext($type)) dd('文件类型不允许');
    					$fileName = 'p'.date('YmdHms').mt_rand(1000,9999).".".$type;
    					$file->move($destinationPath, $fileName);
    					$fileArray[] = $fileName;
    				}
    			}
    		
    		}
    		$data['resupply_evidence'] = serialize($fileArray);
    		$e = HgDefend::where(array('id'=>$defend_id))->update($data);
    		if(!$e) dd('更新补充证据失败，请重新提交');
    		return redirect(route('cart.defend', ['order_num' =>Request::Input('order_num') ]));
    	}elseif(Request::Input("act") == "hejie"){//如果和解了告知华车
    		$defend_id = Request::Input("defend_id");
    		$defend_hejie = empty(Request::Input("content2"))?"同意和解":Request::Input("content2");
    		HgDefend::where("id",$defend_id)->update(array("defend_hejie"=>$defend_hejie,'defend_hejie_date'=>date('Y-m-d H:i:s')));
    		return redirect(route('cart.defend', ['order_num' =>Request::Input('order_num') ]));
    	}elseif(Request::Input("act") == "tiaojie"){//是否接受平台调解
    		$itemid = Request::Input("itemid");
    		$member_id = session('user.member_id');
    		if(Request::Input("result")==0){
    			$log_id=HgCartLog::putLog(Request::Input('id'),session('user.member_id'),1007,"CartController@Defend","调解未成功，终止订单",0,"调解未成功终止订单时间");
    			//更改调解状态 1为不接受 2为接受
    			$e = HgMediate::where(array('itemid'=>$itemid))->update(array('status'=>1)); 
    			return redirect(route('cart.defend', ['order_num' =>Request::Input('order_num') ]));
    		}else{
    			$e = HgMediate::where(array('itemid'=>$itemid))->update(array('status'=>2));
    			//更改调解状态 1为不接受 2为接受
    			self::acceptMediate($order_num,1);
    			//更改调解状态 1为不接受 2为接受
    			return redirect(route('cart.mediateok', ['order_num' =>Request::Input('order_num') ]));
    		}
    	}

    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){

    		exit('订单不存在');
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'title'=>'经销商提交了争议，客户进行申辩',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view['order']=$order;
    	$view['brand']=explode('&gt;',$order['car_name']);

    	// 取得报价信息
    	$bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
    	/**
    	 * 该报价对应车型本身信息----报价
    	 * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
    	 */
    	$carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
    	foreach ($bjCarInfo as $key => $val) {
    		if (is_array($carmodelInfo[$key])) {
    			$carmodelInfo[$key] = $carmodelInfo[$key][$val];
    		} else {
    			$carmodelInfo[$key] = $val;
    		}
    	}
    	$bj = array_merge($bj, $carmodelInfo);

    	// 修改过的车辆信息
    	$editcar=HgEditInfo::getEditInfo($order_num);

    	if (!empty($editcar->carinfo)) {
    		$carinfo=unserialize($editcar->carinfo);
    	}
    	$view['body_color']=!empty($carinfo['body_color'])?$carinfo['body_color']:$bj['body_color'];
    	$view['interior_color']=!empty($carinfo['interior_color'])?$carinfo['interior_color']:$bj['interior_color'];

    	$view['dispute'] = HgDispute::getDispute($order_num,$order['seller_id']);
    	$evidence = HgEvidence::getEvidence($order_num,$view['dispute']['id'])->toArray();
    	$view['file_path'] = config('app.uploaddir')."evidence";
    	if(!empty($view['dispute']['problem'])){
    		$view['question'] = unserialize($view['dispute']['problem']);
    	}else{
    		$view['question'] = array();
    	}

    	$view['evidence'] = $evidence;
    	$defend = HgDefend::getDefend($order_num,$view['dispute']['id']);
    	if(!empty($defend)){
    		$view['defend'] =$defend->toArray();
    		$defend_evidence= HgEvidence::getEvidence($order_num,$view['dispute']['id'],$view['defend']['id']);
    		if(!empty($defend_evidence)){
    			$view['defend_evidence'] = $defend_evidence->toArray();
    		}else{
    			$view['defend_evidence'] =array();
    		}
				
    	}else{
    		$view['defend'] = array();
    		$view['defend_evidence'] =array();
    	}
    	
    	$mediate = HgMediate::getMediate($order_num,$view['dispute']['id']);
    	if(!empty($mediate)){//判断是否存在平台和解
    		$view['mediate'] = $mediate->toArray();
    	}
    	
    	/**
		 * 获取订单日志
		 */
		$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();

		$view['cart_log'] = $log;
        return view('cart.defend',$view);
    }
    // 接不接受争议
    public function acceptMediate($order_num,$a)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $a=intval($a);
        switch ($a) {
            case '1':
                // 更新订单状态
                $affectedRows = HgCart::where('order_num', '=', $order_num)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>509]);

                if (!$affectedRows) {
                    dd('更新订单状态失败');
                }

                return redirect(route('cart.mediateok', ['order_num' =>Request::Input('order_num') ]));
                break;

            default:
                // 更新订单状态
                $affectedRows = HgCart::where('order_num', '=', $order_num)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>1007]);

                if (!$affectedRows) {
                    dd('更新订单状态失败');
                }

                return redirect(route('cart.mediatefail', ['order_num' =>Request::Input('order_num') ]));
                break;
        }
    }
    // 调解成功
    public function mediateOk($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
            abort(404, trans('common.code404'));
        }
        $view=[
            'order_num'=>$order_num,
            'id'=>$order->id,
            'order'=>$order,
        ];
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order->id);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        // 客户反馈交车通知时间
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);

        $view['brand']=explode('&gt;',$order->car_name);
        // 取得报价信息
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();

        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }
        $bj = array_merge($bj, $carmodelInfo);

        // 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
        }
        $view['body_color']=!empty($carinfo['body_color'])?$carinfo['body_color']:$bj['body_color'];
        $view['interior_color']=!empty($carinfo['interior_color'])?$carinfo['interior_color']:$bj['interior_color'];
        // 客户选择的选装件
        $userxzj=HgUserXzj::getUserXzj($order_num)->toArray();
        $view['userxzj']=$userxzj?$userxzj:array();
        // 客户选择的赠品
        $zengpin=HgUserZengpin::getZengpin($order_num)->toArray();
        $view['zengpin']=$zengpin?$zengpin:array();
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);

        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        $view['order_attr']=$order_attr;
        // 车辆的最终信息
       $pdi_carinfo=unserialize($view['order_attr']->pdi_carinfo);
       // 如果代理先提交则读取代理的数据，否则从订单中读取
       if ($pdi_carinfo) {
           $view['fin_car_info']=$pdi_carinfo;
           if ($view['order']['shangpai']) {
               Session::put('next_status', 504);
           }else{
                Session::put('next_status', 505);
           }

       }else{
            $fin_car_info['vin']=$view['order_attr']->vin;
            $fin_car_info['engine_no']=$view['order_attr']->engine_no;
            $fin_car_info['shangpai_area']=$view['shangpai_area'];
            $fin_car_info['yongtu']=carUse($order->buytype);
            $fin_car_info['reg_name']=($order->reg_name);

            // 车牌号
            $chepai=Area::getPaizhao($order->shangpai_area);
            array_push ( $chepai ,  8 ,  8,8,8,8 );
            $fin_car_info['chepai']=$chepai;
            // $view['chepai']=$chepai;
            $view['fin_car_info']=$fin_car_info;
            Session::put('next_status', 503);
       }
       $view['chepai']=$view['fin_car_info']['chepai'];
       // 各省名称id
       $view['sheng']=Area::getTopArea()->toArray();
       /**
        * 获取订单日志
        */

       $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
       $view['cart_log'] = $log;

        return view('cart.mediateok',$view);
    }
    // 调解失败
    public function mediatefail($order_num)
    {
	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'id'=>$order->id,
    			'order'=>$order,
    	];
    	
    	$log = HgCartLog::where("cart_id",$order->id)->where("cart_status",1007)->first();
        if(!empty($log) && isset($log->user_id) ){
        	if($log->user_id == session('user.member_id')){
        		$disputeType = "user";
        	}else{
        		$disputeType = "dealer";
        	}
        }else{
        	die("不存在的争议");
        }
        //根据最后的mediate 来取dispute和 defend信息
        $mediate=HgMediate::getMediate($order_num)->toArray();
        
        $dispute=HgDispute::getDisputeById($mediate['dispute_id'])->toArray();
        $defend=HgDefend::getDefend($order_num,$dispute['id'])->toArray();
        
        $evidence=HgEvidence::getEvidence($order_num,$dispute['id']);
        $evidence_defend=HgEvidence::getEvidence($order_num,$dispute['id'],$defend['id']);
        if(!empty($evidence)){
        	$evidence = $evidence->toArray();
        }else{
        	$evidence = array();
        }
        if(!empty($evidence_defend)){
        	$evidence_defend = $evidence_defend->toArray();
        }else{
        	$evidence_defend = array();
        }
        $view['dispute']=$dispute;
        $view['defend']=$defend;
        $view['mediate']=$mediate;
        $view['evidence']=$evidence;
        $view['evidence_defend']=$evidence_defend;
        $view['deposit']= $this->order->getDepositMoney($order->bj_id, false);
    	$view['disputeType'] = $disputeType;
    	
    	/**
    	 * 获取订单日志
    	 */

    	$log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
    	$view['cart_log'] = $log;

    	return view('cart.mediatefail',$view);
    }
    //提车结束后 进行其他信息填充，如果上牌时间或者上牌的相关信息 上牌地区 车辆用途  车主名称 拍照号码 节能补贴发放时间
    public  function postOtherTicheInfo(){
    	$order_num = Request::Input("order_num");
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){

    		exit('订单不存在');
    	}
    	$edit = Request::Input("edit");
    	// 订单属性
    	$order_attr = HgCartAttr::GetOrderAttr(['cart_id'=>Request::Input("id")]);
    	$arrParam = unserialize($order_attr->user_carinfo);


    	if($edit == 1){//只更新交车时间
    		$map = array("user_shangpai_time"=>Request::Input("shangpai_time"));
    		$effect = HgCartJiaoche::where("order_num",$order_num)->update($map);
    		if(!$effect){
    			die("数据更新失败");
    		}
    		return redirect(route('cart.ticheend', ['order_num' =>Request::Input('order_num') ]));
    	}elseif($edit == 2 || $edit == 3){
    		$map = array(
    				"user_shangpai_area"=>Request::Input("sheng")." ".Request::Input("shi"),
    				"user_useway"=>Request::Input("yongtu"),
    				"user_regname"=>Request::Input("reg_name"),
    				"user_chepai"=>!empty(Request::Input('chepai'))?serialize(Request::Input('chepai')):'',
    				"user_butie_date"=>Request::Input("fafang_butie"),
    				"user_date_update"=>date('Y-m-d H:i:s'),
    				
    		);
    		$effect = HgCartJiaoche::where("order_num",$order_num)->update($map);
    		if(!$effect){
    			die("数据更新失败");
    		}
    		return redirect(route('cart.heshi', ['order_num' =>Request::Input('order_num') ]));
    	}else{
    		die("更新失败");
    	}
    }
    // 退担保金核实信息
    public function heShi($order_num){
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view['order_num']=$order_num;
    	$view['title']='交车完成';
    	// TODO 测试的获取订单的详细信息
    	$view['order'] = $this->order->getOrderAllInfoById($order_num, true);
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
    	$bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
    	// 品牌，车型
    	$bj['brand']=explode('&gt;',$bj['gc_name']);
    	$carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
    	foreach ($bjCarInfo as $key => $val) {
    		if (is_array($carmodelInfo[$key])) {
    			$carmodelInfo[$key] = $carmodelInfo[$key][$val];
    		} else {
    			$carmodelInfo[$key] = $val;
    		}
    	}
    	$bj = array_merge($bj, $carmodelInfo);
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$view['allPrice']=$price;

    	$view['bj']=$bj;
		
    	//获取交车信息表中的数据
    	$jiaoche_map = array('order_num'=>$order_num);
    	$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
    	
    	if(!empty($jiaocheInfo)){
    		$jiaocheInfo = $jiaocheInfo->toArray();
    	}else{
    		$jiaocheInfo = array();
    	}
    	$view['jiaoche'] = $jiaocheInfo;
    	
    	// 客户填写的车辆最终信息
    	//$view['che']=unserialize($view['order']['cartAttr']['user_carinfo']);
    	// 代理填写的车辆最终信息
    	//$view['che_pdi']=unserialize($view['order']['cartAttr']['pdi_carinfo']);
    	// 修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);

        if (!empty($editcar->carinfo)) {
            $carinfo=unserialize($editcar->carinfo);
            $view['bj']['body_color']=$carinfo['body_color'];
        }
    	return view('cart.heshi',$view);
    }
    /**
     *	退款银行卡号填写；
     */
    public function postHeShi(){
    	// 检验订单
    	if (! $order = $this->order->getOrder(Request::Input('order_num'), session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	};
    	$member_id = session('user.member_id');
/**
    	$newAccount = array(
	    				"member_id"=>$member_id,
    					"order_num"=>Request::Input('order_num'),
	    				"calculate_text"=>serialize(Request::Input('money')),
	    				"money"=>Request::Input('totalmoney'),
    					);
    	DB::table("member_bank_account")->where("order_num","=",Request::Input('order_num'))->delete();
    	$affect = DB::table("member_bank_account")->insert($newAccount);
    	
    	if(!$affect){
    		die("操作失败");
    	}else{
    		
    	}
    	**/
    	$eff = HgCart::where("order_num",Request::Input("order_num"))->update(array('calc_status'=>1,'calc_date'=>date('Y-m-d H:i:s'),'end_user_status'=>601,'cart_status'=>6));
    	if(!$eff){
    		die("操作失败");
    	}
    	return redirect(route('cart.tuikuan', ['id' =>Request::Input('order_num') ]));

    }
    // 退担保金办理退款
    public function tuiKuan($order_num){
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view['order_num']=$order_num;
    	$view['title']='交车完成';
    	// TODO 测试的获取订单的详细信息
    	$view['order'] = $this->order->getOrderAllInfoById($order_num, true);
    	//print_r($view['order']);exit;
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
    	$bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
    	// 品牌，车型
    	$bj['brand']=explode('&gt;',$bj['gc_name']);
    	$carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
    	foreach ($bjCarInfo as $key => $val) {
    		if (is_array($carmodelInfo[$key])) {
    			$carmodelInfo[$key] = $carmodelInfo[$key][$val];
    		} else {
    			$carmodelInfo[$key] = $val;
    		}
    	}
    	$bj = array_merge($bj, $carmodelInfo);
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$view['allPrice']=$price;

    	$view['bj']=$bj;
   	 	$view['bankAccount'] = DB::table("member_bank_account")->where("order_num",$order_num)->first();
		
   	 	//获取交车信息表中的数据
   	 	$jiaoche_map = array('order_num'=>$order_num);
   	 	$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
   	 	
   	 	if(!empty($jiaocheInfo)){
   	 		$jiaocheInfo = $jiaocheInfo->toArray();
   	 	}else{
   	 		$jiaocheInfo = array();
   	 	}
   	 	$view['jiaoche'] = $jiaocheInfo;
   	 	/**
   	 	 * 获取订单日志
   	 	 */
   	 	$log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
   	 	$view['cart_log'] = $log;
        return view('cart.tuikuan',$view);
    }
    // 退款完毕
    public function tuiKuanEnd($order_num){
    	return redirect('/cart/tuikuan/'.$order_num);
    	exit;
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view['order_num']=$order_num;
    	$view['title']='交车完成';
    	// TODO 测试的获取订单的详细信息
    	$view['order'] = $this->order->getOrderAllInfoById($order_num, true);
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
    	$bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
    	// 品牌，车型
    	$bj['brand']=explode('&gt;',$bj['gc_name']);
    	$carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);

    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
    	foreach ($bjCarInfo as $key => $val) {
    		if (is_array($carmodelInfo[$key])) {
    			$carmodelInfo[$key] = $carmodelInfo[$key][$val];
    		} else {
    			$carmodelInfo[$key] = $val;
    		}
    	}
    	$bj = array_merge($bj, $carmodelInfo);
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$view['allPrice']=$price;

    	$view['bj']=$bj;
    	$view['bankAccount'] = DB::table("member_bank_account")->where("order_num",$order_num)->first();
        
    	//获取交车信息表中的数据
    	$jiaoche_map = array('order_num'=>$order_num);
    	$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
    	
    	if(!empty($jiaocheInfo)){
    		$jiaocheInfo = $jiaocheInfo->toArray();
    	}else{
    		$jiaocheInfo = array();
    	}
    	$view['jiaoche'] = $jiaocheInfo;
    	
    	return view('cart.tuikuanend',$view);
    }
    // 完成评价
    public function pingJia($order_num){
    	return redirect('/cart/tuikuan/'.$order_num);
    	exit;
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view['order_num']=$order_num;
    	$view['title']='交车完成';
    	// TODO 测试的获取订单的详细信息
    	$view['order'] = $this->order->getOrderAllInfoById($order_num, true);
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
    	$bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
    	// 品牌，车型
    	$bj['brand']=explode('&gt;',$bj['gc_name']);


    	$view['bj']=$bj;
        return view('cart.pingjia',$view);
    }
    // 完成评价
    public function postPingJia(){
    	$order_num = Request::Input("order_num");
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$map = array(
    			"order_id"=>Request::Input("order_id"),
    			"buy_id"=>session('user.member_id'),
    			"hwache_service"=>Request::Input("hwache_service"),
    			"seller_service"=>Request::Input("seller_service"),
    			"evaluate"=>Request::Input("evaluate"),
    	);
    	$delRow = DB::table("hg_cart_evaluate")->where("order_id",Request::Input("order_id"))->delete();
    	$affect = DB::table("hg_cart_evaluate")->insert($map);
    	
    	$eff = HgCart::where("order_num",Request::Input("order_num"))->update(array('end_user_status'=>700));
    	if(!$eff){
    		die("操作失败");
    	}
    	
    	if($affect){
    		return redirect(route('cart.pingjiaend', ['id' =>Request::Input('order_num') ]));
    	}else{
    		die("评价失败，请返回重新评价！");
    	}
    }
    public function pingJiaEnd($order_num){
    	if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
    		abort(404, trans('common.code404'));
    	}
    	$view['order_num']=$order_num;
    	$view['title']='评价完成';
    	// TODO 测试的获取订单的详细信息
    	$view['order'] = $this->order->getOrderAllInfoById($order_num, true);
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
    	$bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
    	// 品牌，车型
    	$bj['brand']=explode('&gt;',$bj['gc_name']);
    	$view['bj']=$bj;
    	$view['evaluate'] = DB::table("hg_cart_evaluate")->where("order_id",$order->id)->first();
		
		if(!empty($view['evaluate'])){
			$view['hwache_service'] = $view['evaluate']->hwache_service;
		}else{
			die('您还没有对此次购车进行评价');
		}
    	return view('cart.pingjiaend',$view);
    }

    public function postTiche()
    {
        if (session('cart.buy_id') == session('user.member_id')) {
            // 当前是消费者登陆

            // TODO 保存post提交的数据 根据当前的状态保存

            // TODO 记录日志

            if ($this->order->changeOrderStatus(
                session('cart.order_num'),
                get_order_code(session('cart.next'))
            )!==false) {
                return redirect($_ENV['_CONF']['config']['shop_site_url'] .
                    '/index.php?act=m_order');
            }
        }
    }


    /**
     * 检测订单号是否重复
     * @param null $sn
     * @return null|string
     */
    private function _get_order_sn($sn = null)
    {
        if (empty($sn)) {
            $sn = generate_sn();
        }
        $check_sn = DB::table('hg_cart')
            ->where('order_num', $sn)
            ->count();
        if ($check_sn == 0) {
            return $sn;
        } else {
            $this->_get_order_sn();
        }
    }
    //  客户查看订单概况
    public function getOrderOverview($order_num)
    {
        if (! $order = $this->order->getOrder($order_num, session('user.member_id'), true)) {
        	abort(404, trans('common.code404'));
        }

        // 如果订单还未付诚意金则退出
        if ($order->cart_sub_status < 201) {
            exit('还未付诚意金');
        }
        // 如果是后台登录提示退出
        if (isset($_SESSION['is_login_agents'])) {
        	exit('请在前端登录');
        }
        // 登录返回路径
        Session::flash('backurl', URL('orderoverview').'/'.$order_num);
        $view = [
            'title' => '订单详细信息',
            'order_num'=>$order_num,
        ];
        // 订单状态变量
        $cart_status=$order->cart_status;
        $cart_sub_status=$order->cart_sub_status;
        $view['cart_status']=$cart_status;
        $view['cart_sub_status']=$cart_sub_status;
        // 如果订单走到预约交车时，取出订单中修改过的信息
        if ($cart_status>=4) {
            $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
            // 提车方式
            $view['take_way']=$order_attr->take_way;
            // 回程方式
            $view['trip_way']=$order_attr->trip_way;
            // 送车大致地址
            $view['deliver_addr']=$order_attr->deliver_addr;
            // 提车人姓名电话
            $view['ticheren']=unserialize($order_attr->ticheren);
            // 节能补贴需要的文件
            $view['butie_file']=unserialize($order_attr->butie_fille);
            // 车辆信息如发动机号等
            $view['car_info']=unserialize($order_attr->car_info);
            // 客户选定的保险
            $view['baoxian']=unserialize($order_attr->baoxian);
            // 非原厂选装件
            $view['not_original']=unserialize($order_attr->not_original);
        }
        if ($cart_status>=5) {
            // 服务专员信息
            $view['waiter']=unserialize($order_attr->waiter);
        }
        // TODO 测试的获取订单的详细信息
        $view['order'] = $this->order->getOrderAllInfoById($order_num, true);

        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 经销商反馈订单时间
        if ($cart_sub_status>=201) {
            $view['feedback_time']=HgCartLog::get_feedback_time($order->id);
        }
        //  担保金进去加信宝时间
        if ($cart_sub_status>=300) {
            $view['deposit_time']=HgCartLog::get_deposit_time($order->id);
        }
        // 收到担保金响应订单时间,经销商开始执行订单时间
        if ($cart_sub_status>=301) {
            $view['response_time']=HgCartLog::get_response_time($order->id);
        }
        // 经销商代理发出交车通知时间
        if ($cart_sub_status>=400) {
            $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order->id);
        }

        // 经销商代理信息 (卖家)
        $view['seller']=HgSeller::getProxy($order->seller_id);
        // 取得代理修改过的车辆信息
        $editcar=HgEditInfo::getEditInfo($order_num);
        // 取得报价信息
        $bj_id=$view['order']['cartBase']['bj_id'];
        $bj=HgBaojia::getBaojiaInfo($bj_id)->toArray();
        // 销售区域
        $view['seller_area']=HgBaojiaArea::getBaojiaArea($bj_id);
        // 保险公司名称
        $view['baoxianname']=HgBaoXian::getName($bj['bj_bx_select']);

        // 品牌，车型
        $bj['brand']=explode('&gt;',$bj['gc_name']);
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($bj['brand_id']);
        $bj['barnd_info']=$barnd_info;
        // 车型扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        if (empty($carFields)) {
            // TODO 没有查找到对应的车型扩展信息
            exit('没有查找到对应的车型扩展信息');
        }
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        //报价扩展信息包括其他费用
        $bj['more']=HgBaojiaMore::getBaojiaMove($bj['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$bj['more'])){

            foreach ($bj['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;

            }
        }
        $bj['other_price']=$other_price;
        /**
         * 获取该车型本身的数据信息----车型
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $carmodelInfo = HgCarInfo::getCarmodelFields($bj['brand_id']);
        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
         */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($bj['bj_id']);
        foreach ($bjCarInfo as $key => $val) {
            if (is_array($carmodelInfo[$key])) {
                $carmodelInfo[$key] = $carmodelInfo[$key][$val];
            } else {
                $carmodelInfo[$key] = $val;
            }
        }

        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);

        /*
        *赠品或免费服务器
        *如果经销商代理做过说明则从订单属性表中读，否则从报价赠品表中读
        */
        if ($cart_sub_status>=401) {

            $view['zengpin']=unserialize($order_attr->zengpin);
        }else{
            $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($bj['bj_id']);
        }

        // 支付方式
        $bj['payTitle'] = $_ENV['_CONF']['base']['payType'][$bj['bj_pay_type']];
        // 国别
        $bj['guobieTitle'] = $carFields['guobie'][$bj['guobie']];

        // 排放
        $bj['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        // 补贴
        $bj['butieTitle'] = $carFields['butie'][$bj['butie']];

        $area=HgBaojiaArea::getBaojiaArea($bj['bj_id']);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($area)){
            $bj['area']=$area;
            $bj['area_xianpai']=$area_names_xianpai;

        }else{
                foreach ($area as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }

            $bj['area']=$area_names;
            $bj['area_xianpai']=$area_names_xianpai;

        }
        // 如果代理修改过车辆信息，则调用修改过的信息
        if ($editcar) {
        	$editcar = $editcar->toArray();
            $carinfo=unserialize($editcar['carinfo']);

            $bj['guobieTitle']=isset($carinfo['guobieTitle'])?$carinfo['guobieTitle']:"国产";
            $bj['paifangTitle']=$carinfo['paifang'];
            $bj['body_color']=$carinfo['body_color'];
            $bj['interior_color']=$carinfo['interior_color'];
            $bj['bj_producetime']=$carinfo['chuchang'];
            $bj['bj_licheng']=$carinfo['licheng'];
            $bj['bj_jc_period']=$carinfo['zhouqi'];
            $xzjs=unserialize($editcar['xzj']);
            $view['xzj']=$xzjs;
        }else{
            // 报价选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=$xzjs->toArray();
        }

        $view['bj']=$bj;
        /*
        *客户信息
        */
        $view['buyer']=HgUser::getMember($view['order']['cartBase']['buy_id']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['cartBase']['shangpai_area']);
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($view['order']['cartBase']['dealer_id'])->toArray();

        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);

        /*
        * 上牌方式，客户在没有预约修改资料以前调用报价里面的方式，之后调用订单里面最终选择的方式
        */

        if ($cart_sub_status>=402) {
            $view['order']['shangpai']=$order->shangpai;
            $view['order']['linpai']=$order->linpai;
        }else{
            $view['order']['shangpai']=$bj['bj_shangpai'];
            $view['order']['linpai']=$bj['bj_linpai'];

        }
        /*
        *补贴，客户在没有预约选择补贴办理方式时调用报价里面的约定
        */
        if ($cart_sub_status>=402) {
            $view['jn_butie']=$order_attr->butie;
            $view['zh_butie']=$order->zhihuan;
            $view['cj_butie']=$order->cj_butie;
        }else{
            $view['jn_butie']=$bj['bj_butie'];
            $view['zh_butie']=$bj['bj_zf_butie'];
            $view['cj_butie']=$bj['bj_cj_butie'];

        }
        /*
        *客户选择的选装件
        */
        $userxzj = array();
        if ($cart_sub_status>=303) {

            $view['userxzj']=HgUserXzj::getUserXzj($order->order_num)->toArray();
        }
        // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        /**
         * 获取订单日志
         */

        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
       //客户提车需要的文件
       if($view['cart_status']>=3){
        	$view['files']=HgOrderFiles::getByName($order_num);
       }
        return view('cart.overview',$view);
    }
    
    public function ajaxAction($order_num,$type){
    	if($type == 'surebutie'){
    		//获取交车信息表中的数据
    		$jiaoche_map = array('order_num'=>$order_num);
    		$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
	    	
	    	if(count($jiaocheInfo)>0){
	    		$jiaoche['user_butie_get_date']=date('Y-m-d H:i:s');
	    		$jiaoche['user_date_update']=date('Y-m-d H:i:s');
	    		$jiaoche['hw_butie_status']=3;//主动确认更改华车平台 确认值
	    		$e = HgCartJiaoche::where('order_num', '=', Request::Input('order_num'))->update($jiaoche);
	    	}else{
	    		$jiaoche['user_date_first']=date('Y-m-d H:i:s');
	    		$jiaoche['user_date_update']=date('Y-m-d H:i:s');
	    		$jiaoche['order_num']=Request::Input('order_num');
	    		$jiaoche['create_at']=date('Y-m-d H:i:s');
	    		$jiaoche['hw_butie_status']=3;//主动确认更改华车平台 确认值
	    		$e = HgCartJiaoche::insert($jiaoche);
	    	}
	    	if(!$e){
	    		echo '1';
	    	}else{
	    		echo '0';
	    	}
    	}elseif($type =='sub_jiaoche_ext'){
    		$jiaoche['order_num']=Request::Input('order_num');
    		$ext_id = Request::Input('ext_id');
    		$files = Request::file('file');
    		$destinationPath = config('app.uploaddir')."file";
    		$resupply = array();
    		foreach($files as $file){
    			if(!empty($file) && $file->isValid()){
    				$type= $file->getClientOriginalExtension();
    				$fileName = 'z'.date('YmdHms').mt_rand(1000,9999).".".$type;
    				$file->move($destinationPath, $fileName);
    				$resupply[]='file/'.$fileName;
    			}
    		}
    		if(count($resupply)>0){
    			$updata = array('resupply_file'=>serialize($resupply),
    					'resupply_date'=>date('Y-m-d H:i:s'),
    					);
    			$e = HgCartJiaocheExt::where(array('id'=>$ext_id))->update($updata);
    		}else{
    			$e = false;
    		}
    		if(!$e){
    			echo '1';
    		}else{
    			echo '0';
    		}
    	}
    }

}
