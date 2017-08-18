<?php namespace App\Http\Controllers\Business;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Request;
use Input;
use App\Models\HgCart;
use App\Models\HgCartLog;
use App\Models\HgCartAttr;
use App\Models\HgBaojia;
use App\Models\HgCarInfo;
use App\Models\HgBaojiaField;
use App\Models\HgFields;
use App\Models\HgBaojiaBaoxianChesunPrice;
use App\Models\HgBaojiaBaoxianDaoqiangPrice;
use App\Models\HgBaojiaBaoxianSanzhePrice;
use App\Models\HgBaojiaBaoxianRenyuanPrice;
use App\Models\HgBaojiaBaoxianBoliPrice;
use App\Models\HgBaojiaBaoxianHuahenPrice;
use App\Models\HgBaojiaBaoxianZiranPrice;
use App\Models\HgGoodsClass;
use App\Models\HgBaojiaXzj;
use App\Models\HgBaojiaArea;
use App\Models\HgBaojiaMore;
use App\Models\HgBaojiaZengpin;
use App\Models\Area;
use App\Models\HgDealer;
use App\Models\HgBaoXian;
use App\Models\HgDealerBaoXianBuJiMian;
use App\Models\HgDealerBaoXian;
use App\Models\HgBaojiaPrice;
use App\Models\HgSeller;
use App\Models\HgUser;
use App\Models\HgMoney;
use App\Models\HgUserXzj;
use App\Models\HgAnnex;
use App\Models\HgAgentFiles;
use App\Models\HgXzj,App\Models\HgFycXzj;
use App\Models\HgFileCate;
use App\Models\HgEditXzj;
use App\Models\HgEditZengpin;
use App\Models\HgEditInfo;
use App\Models\HgCarAtt;
use App\Models\HgXzjDaili;
use DateTime;
class IndexController extends Controller {
    public function __construct()
    {
        $this->middleware('auth.seller');
    }
    /**
     * 首页控制器
     */
    public function feedBack($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '诚意金已付',
        ];
        // 取得订单基本信息
        // $order=HgCart::GetOrderByUser($order_num)->toArray();
        /*// 判断订单状态是否在这步
        if ($order['cart_sub_status']!=$_ENV['_CONF']['config']['hg_order']['order_earnest_not_confirm']) {
            exit('请在会员中心查看订单');
        }*/

        $view=array_merge($view,$order->toArray());

        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order->id);
        if(!$earnest_time) exit('未查到诚意金支付记录');
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 品牌
        $view['brand']=explode('&gt;',$order->car_name);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
        $view['baojia']=$baojia;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order->dealer_id)->toArray();
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order->car_id);
        $view['barnd_info']=$barnd_info;
        // 出厂年月
        $view['chuchang']=explode('-', $barnd_info['chuchang_time']);

        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        // 排放标准
        $view['paifang']=$carFields['paifang'];
        $carmodelInfo = HgCarInfo::getCarmodelFields($order->car_id);
        $view['carmodelInfo']=$carmodelInfo;
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order->bj_id);
        $view['bjCarInfo']=$bjCarInfo;
        $view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
        $view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
        // 国别
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
        // 排放
        $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
        // 补贴
        $view['butieTitle'] = $carFields['butie'][$bjCarInfo['butie']];
        // 远期订单计算时间
        $new_date=date_create($order->created_at);
        date_add ( $new_date ,  date_interval_create_from_date_string ( $baojia['bj_jc_period'].' months' ));
        $view['new_date']=date_format($new_date,'Y年m月d日');
        // 报价可售地区

        $view['area']=HgBaojiaArea::getBaojiaArea($order->bj_id);

        /*$area_names='';
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
        }*/
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);
        // 报价中已装原厂选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order->bj_id);
        $view['xzj']=empty($xzjs)?array():$xzjs->toArray();
        // 代理选择的选装件
        $xzj_daili=HgXzjDaili::getDealerXzj($order->car_id,$order->dealer_id,$_SESSION['member_id']);
        $xzj_daili=empty($xzj_daili)?array():$xzj_daili->toArray();
        $ids = array();
        // 可供客户选择的选装件，除去已经安装的
        foreach ($xzjs as $key => $value) {
            $ids[]=$value->m_id;//要去除的id
        }
        /*foreach ($xzj_daili as $key => $value) {
            if(in_array($value->id, $ids)) unset($xzj_daili[$key]);
        }*/
        $view['xzj_daili']=unsetArray($xzj_daili,$ids);
        // 保险公司名称
        $view['baoxian_name']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxian_name']=$baoxian['bx_title'];
        }
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order->bj_id,$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order->bj_id,$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order->bj_id,$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order->bj_id,$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order->bj_id,$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order->bj_id,$carSeatType);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id']);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order->dealer_id);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order->bj_id);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){

            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order->bj_id);

        // 需要办理的特殊资料
        $view['ziliao']=unserialize($order->wenjian);
         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order->seller_id,$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);
        // 等待反馈剩余时间
        if (empty($view['ziliao'])) {
            $view['endtime']=date('Y-m-d H:i:s',strtotime($earnest_time)+20*60);
        }else{
            $view['endtime']=date('Y-m-d H:i:s',strtotime($earnest_time)+24*3600);
        }
        if ($view['endtime']>date('Y-m-d H:i:s')) {
            $view['timeout']=0;
        }else{//订单超时
            $view['timeout']=1;
            //查看是否有特需  没有特需自动确认，有特需 全部不办；
        }
        $view['starttime']=date('Y-m-d H:i:s');
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('dealer.feedback',$view);
    }
    public function saveFeedBack()
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus(Request::Input('order_num'));
        if(!isset($order->id)){
            exit('订单不存在');
        }
        // 如果代理终止订单
        if (Request::Input('jiaoche')==2) {
            // 文件资料办理
            $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update(['wenjian' => serialize(Request::Input('ziliao'))]);
            if (!$affectedRows) {
                // 插入订单属性表
                $cartattr = new HgCartAttr;
                $cartattr->cart_id = Request::Input('id');
                $cartattr->wenjian = serialize(Request::Input('ziliao'));
                $cartattr->save();
            }
            // 更新订单状态
            $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => 1000,'cart_sub_status'=>1001]);
            if (!$affectedRows) {
                dd('更新订单状态失败');
            }

            // 记录日志
            $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],1001,"IndexController/feedBack","代理终止了订单",Request::Input('timeout'),'售方提议终止订单时间');
            if ($log_id) {
                    return redirect(route('pdistop1', ['order_num' =>Request::Input('order_num') ]));
                }else{
                    dd('记录日志失败');
                }
        }
        // 如果代理要修改选项
        if (Request::Input('jiaoche')==1) {

            // 更新车身颜色，内饰颜色，排放标准,里程，交车周期，出厂日期
            $car=[
                'body_color'=>Request::Input('body_color'),
                'interior_color'=>Request::Input('interior_color'),
                'paifang'=>Request::Input('paifang'),
                'licheng'=>Request::Input('licheng'),
                'zhouqi'=>Request::Input('zhouqi'),
                'chuchang'=>Request::Input('nian').'-'.Request::Input('yue'),
                'guobieTitle'=>Request::Input('guobieTitle'),
            ];


            // 将修改的信息保存到数据库
            if (!HgEditInfo::insert(['carinfo'=>serialize($car),'xzj'=>serialize(Request::Input('xzj')),'zengpin'=>serialize(Request::Input('zengpin')),'status'=>201,'order_num'=>Request::Input('order_num')]))
            {
                dd('保存修改信息时出错');
            }
            if (empty(Request::Input('ziliao'))) {
                $msg_time='售方提议修改订单时间';
            }else{
                $msg_time='售方反馈特需要求并提议修改订单时间';
            }
            // 文件资料办理
            $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update(['wenjian' => serialize(Request::Input('ziliao')),]);
            if (!$affectedRows) {
                // 插入订单属性表
                $cartattr = new HgCartAttr;
                $cartattr->cart_id = Request::Input('id');
                $cartattr->wenjian = serialize(Request::Input('ziliao'));
                $cartattr->save();
            }
            // 更新订单状态
            $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => 2,'cart_sub_status'=>202]);
            if (!$affectedRows) {
                dd('更新订单状态失败');
            }
            // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],202,"IndexController/feedBack","诚意金支付，代理修改信息",Request::Input('timeout'),$msg_time);
        return redirect(route('editcar', ['order_num' =>Request::Input('order_num') ]));

        }
        // 代理不修改执行以下操作
        $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update(['wenjian' => serialize(Request::Input('ziliao'))]);
        if (!$affectedRows) {
            // 插入订单属性表
            $cartattr = new HgCartAttr;
            $cartattr->cart_id = Request::Input('id');
            $cartattr->wenjian = serialize(Request::Input('ziliao'));
            $cartattr->save();
        }
        if (!empty(Request::Input('ziliao'))) {
            $nextstatus=202;
        }else{
            $nextstatus=203;
        }
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_earnest'],'cart_sub_status'=>$nextstatus]);
        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        // $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],$order->cart_sub_status,"IndexController/feedBack","诚意金支付确认反馈",Request::Input('timeout'));
        if ($nextstatus==203) {
        	// 记录日志
        	$log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],$nextstatus,"IndexController/feedBack","诚意金支付确认反馈",Request::Input('timeout'),'售方反馈订单时间');
            return redirect(route('feedbackok', ['order_num' =>Request::Input('order_num') ]));
        }else{
        	// 记录日志
        	$log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],$order->cart_sub_status,"IndexController/feedBack","诚意金支付确认反馈",Request::Input('timeout'),'售方反馈特需要求时间');
            return redirect(route('editcar', ['order_num' =>Request::Input('order_num') ]));
        }

    }

    // 代理修改了车辆信息
    public function editCar($order_num)
    {
        // 检测订单是否存在
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
        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order->id);
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
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 报价可售地区
        $view['area']=HgBaojiaArea::getBaojiaArea($order->bj_id);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($view['area'])){

            $view['area_xianpai']=$area_names_xianpai;
        }else{
                foreach ($view['area'] as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }


            $view['area_xianpai']=$area_names_xianpai;
        }
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);

        $view['xzj']=$xzjs->toArray();

        // 保险公司名称
        $view['baoxian_name']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxian_name']=$baoxian['bx_title'];
        }
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        // $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['baojia']['more'])){

            foreach ($view['baojia']['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

        // 为客户办理的特需文件
        $view['ziliao']=empty($order_attr->wenjian)?array():unserialize($order_attr->wenjian);
        // dd($view['ziliao']);
        // 修改过的车辆信息
        $car=HgEditInfo::getEditInfo($order_num,201);
        if(!empty($car)){//判断车辆信息是否被修改
        	$view['editcarinfo']=unserialize($car->carinfo);
        	$view['editxzj']=unserialize($car->xzj);
        	$view['editzengpin']=unserialize($car->zengpin);
        	$view['editCarModel'] = "Y";
        }else{
        	$view['editCarModel'] = "N";
        }
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order->shangpai_area);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('dealer.editcar',$view);
    }
    // 代理没有修改信息，客户接受了特需文件反馈
    public function acceptFile($order_num)
    {
    	        // 检测订单是否存在
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
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order->id);
        if(!$earnest_time) exit('未查到诚意金支付记录');
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order->id);
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
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 报价可售地区
        $view['area']=HgBaojiaArea::getBaojiaArea($order->bj_id);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($view['area'])){

            $view['area_xianpai']=$area_names_xianpai;
        }else{
                foreach ($view['area'] as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }


            $view['area_xianpai']=$area_names_xianpai;
        }
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();
        // 保险公司名称
        $view['baoxian_name']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxian_name']=$baoxian['bx_title'];
        }
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        // $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['baojia']['more'])){

            foreach ($view['baojia']['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

        // 为客户办理的特需文件
        $view['ziliao']=empty($order_attr->wenjian)?array():unserialize($order_attr->wenjian);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order->bj_id);
        $view['guarantee']=$price->bj_car_guarantee;
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
    	return view('dealer.acceptfile',$view);
    }
    // 代理没有修改信息，客户不接受特需文件反馈
    public function notAcceptFile($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '客户接受了订单修改，等待客户付担保金',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        /*// 判断订单状态是否在这步
        if ($order['cart_sub_status']!=$_ENV['_CONF']['config']['hg_order']['order_earnest_not_confirm']) {
            exit('请在会员中心查看订单');
        }*/
        $view=array_merge($view,$order);
        /*
        * 各项时间
        */

        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order['id']);
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order['id']);
        // 客户不接受特需条件终止订单时间
        $view['stop_time']=HgCartLog::get_shop2_time($order['id']);
        if(!$earnest_time) exit('未查到诚意金支付记录');
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 品牌
        $view['brand']=explode('&gt;',$order['car_name']);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $view['guarantee']=$price->bj_car_guarantee;
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
        $view['baojia']=$baojia;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order['car_id']);
        $view['barnd_info']=$barnd_info;
        // 出厂年月
        $view['chuchang']=explode('-', $barnd_info['chuchang_time']);

        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        // 排放标准
        $view['paifang']=$carFields['paifang'];
        $carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);
        $view['carmodelInfo']=$carmodelInfo;
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
        $view['bjCarInfo']=$bjCarInfo;
        $view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
        $view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
        // 国别
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
        // 排放
        $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
        // 补贴
        $view['butieTitle'] = $carFields['butie'][$bjCarInfo['butie']];
        // 远期订单计算时间
        $new_date=date_create($order['created_at']);
        date_add ( $new_date ,  date_interval_create_from_date_string ( $baojia['bj_jc_period'].' months' ));
        $view['new_date']=date_format($new_date,'Y年m月d日');
        // 报价可售地区
        $view['area']=HgBaojiaArea::getBaojiaArea($order['bj_id']);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($view['area'])){
            $view['area_xianpai']=$area_names_xianpai;
        }else{
                foreach ($view['area'] as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }
            $view['area_xianpai']=$area_names_xianpai;
        }
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();

        // 保险公司名称
        $view['baoxian_name']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxian_name']=$baoxian['bx_title'];
        }

        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry

        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id'],$carSeatType);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){
            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        /*
        *赠品或免费服务器，
        */
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);

        //订单扩展信息
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);

        // 为客户办理的特需文件
        $view['ziliao']=empty($order_attr->wenjian)?array():unserialize($order_attr->wenjian);

        /*
        * 修改过的车辆信息
        */
        $car=HgEditInfo::getEditInfo($order_num,201);
        if (!empty($car->carinfo)) {
            $editcar=unserialize($car->carinfo);
            $view['body_color']=$editcar['body_color'];
            $view['interior_color']=$editcar['interior_color'];
            $view['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['baojia']['bj_licheng']=$editcar['licheng'];
            $view['baojia']['bj_jc_period']=$editcar['zhouqi'];
            // 排放
            $view['paifangTitle'] = $editcar['paifang'];
        }
        if (!empty($car->zengpin)) {
            $view['zengpin']=unserialize($car->zengpin);

        }
        if (!empty($car->xzj)) {
            $view['xzj']=unserialize($car->xzj);

        }

         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

         /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;
        return view('dealer.notacceptfile',$view);
    }
    // 代理有修改信息，客户不接受特需文件反馈
    public function acceptEdit($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '订单终止，赔偿歉意金',
            'order_num'=>$order_num,
            'order'=>$order,
        ];
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order->id);
        // 客户不接受特需文件终止订单时间
        $view['shop_time']=HgCartLog::get_shop2_time($order->id);
        // 取得报价单信息
        $view['baojia']=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 品牌
        $view['brand']=explode('&gt;',$order->car_name);
         //订单扩展信息
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order->car_id);
        $view['barnd_info']=$barnd_info;
        /*// 车辆的颜色，内饰，国别等信息
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
        // 代理修改过的车辆信息
        $view['editcarinfo']=unserialize($order_attr->editcarinfo);
        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];*/
        $carmodelInfo = HgCarInfo::getCarmodelFields($order->car_id);
        $view['carmodelInfo']=$carmodelInfo;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 报价可售地区
        $view['area']=HgBaojiaArea::getBaojiaArea($order->bj_id);
        /*$area_names='';
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
        }*/
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order->shangpai_area);
        /*
        * 修改过的车辆信息包括选装件，赠品
        */
        $car=HgEditInfo::getEditInfo($order_num,201);
        $view['editcarinfo']=unserialize($car->carinfo);
        $view['xzj']=unserialize($car->xzj);
	/**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('dealer.acceptedit',$view);
    }
    // 代理有修改信息，客户接受特需文件反馈,不接受修改
    public function notAcceptEdit($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '订单终止，赔偿歉意金',
            'order_num'=>$order_num,
            'order'=>$order,
        ];
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order->id);
        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order->id);
        // 客户不接受修改终止订单时间
        $view['shop_time']=HgCartLog::get_shop3_time($order->id);
        // 取得报价单信息
        $view['baojia']=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 品牌
        $view['brand']=explode('&gt;',$order->car_name);
         //订单扩展信息
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order->id]);
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order->car_id);
        $view['barnd_info']=$barnd_info;
        /*// 车辆的颜色，内饰，国别等信息
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];*/
        $carmodelInfo = HgCarInfo::getCarmodelFields($order->car_id);
        $view['carmodelInfo']=$carmodelInfo;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 报价可售地区
        $view['area']=HgBaojiaArea::getBaojiaArea($order->bj_id);
        /*$area_names='';
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
                    $area_names.=implode(',',$value);
                }

            $view['area']=$area_names;
            $view['area_xianpai']=$area_names_xianpai;
        }*/
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order->shangpai_area);
        // 代理修改过的订单信息
        $edit_order=HgEditInfo::getEditInfo($order_num,201);
        // 修改过的商品内容
        $view['editcarinfo']=unserialize($edit_order->carinfo);
        // 修改过的原厂选装件
        $view['xzj']=unserialize($edit_order->xzj);
	/**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order->id,$order->cart_sub_status)->toArray();
        $view['cart_log'] = $log;
        return view('dealer.notacceptedit',$view);
    }
    // 客户接受了特需文件和信息修改反馈
    public function acceptAll($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '客户接受了订单修改，等待客户付担保金',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        /*// 判断订单状态是否在这步
        if ($order['cart_sub_status']!=$_ENV['_CONF']['config']['hg_order']['order_earnest_not_confirm']) {
            exit('请在会员中心查看订单');
        }*/
        $view=array_merge($view,$order);
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order['id']);
        // 代理提议修改时间
        $view['feedback_edit_time']=HgCartLog::get_feedback_edit_time($order['id']);
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order['id']);
        // 客户接受特别文件安排时间
        $view['acceptall_time']=HgCartLog::get_acceptall_time($order['id']);
        if(!$earnest_time) exit('未查到诚意金支付记录');
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 品牌
        $view['brand']=explode('&gt;',$order['car_name']);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $view['guarantee']=$price->bj_car_guarantee;
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
        $view['baojia']=$baojia;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order['car_id']);
        $view['barnd_info']=$barnd_info;
        // 出厂年月
        $view['chuchang']=explode('-', $barnd_info['chuchang_time']);

        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        // 排放标准
        $view['paifang']=$carFields['paifang'];
        $carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);
        $view['carmodelInfo']=$carmodelInfo;
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
        $view['bjCarInfo']=$bjCarInfo;
        $view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
        $view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
        // 国别
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
        // 排放
        $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
        // 补贴
        $view['butieTitle'] = $carFields['butie'][$bjCarInfo['butie']];
        // 远期订单计算时间
        $new_date=date_create($order['created_at']);
        date_add ( $new_date ,  date_interval_create_from_date_string ( $baojia['bj_jc_period'].' months' ));
        $view['new_date']=date_format($new_date,'Y年m月d日');
        // 报价可售地区
        $view['area']=HgBaojiaArea::getBaojiaArea($order['bj_id']);
        $area_names='';
        $area_names_xianpai='';//限牌地区名
        if(!is_array($view['area'])){
            $view['area_xianpai']=$area_names_xianpai;
        }else{
                foreach ($view['area'] as $key => $value) {
                    foreach ($value as $kk) {
                        if (strpos($kk,'限牌')!== false ) {
                            $area_names_xianpai.=str_replace('(限牌城市)','',$kk);
                        }
                    }
                    $area_names.=' '.$key.'：'.implode(',',$value);
                }
            $view['area_xianpai']=$area_names_xianpai;
        }
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();

        // 保险公司名称
        $view['baoxian_name']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxian_name']=$baoxian['bx_title'];
        }
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry

        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id'],$carSeatType);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){
            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        /*
        *赠品或免费服务器，
        */
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);

        //订单扩展信息
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        $view['ziliao']=unserialize($order_attr->wenjian_custem);
        /*
        * 修改过的车辆信息
        */
        $car=HgEditInfo::getEditInfo($order_num,201);
        $view['editcarinfo']=unserialize($car->carinfo)?unserialize($car->carinfo):array();
        $view['zengpin_edit']=unserialize($car->zengpin)?unserialize($car->zengpin):array();
        $view['xzj_edit']=unserialize($car->xzj)?unserialize($car->xzj):array();

         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;

        return view('dealer.acceptall',$view);
    }
    // 代理终止了订单
    public function stop1($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '担保金已付，等待反馈',
            'order_num'=>$order_num,
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view=array_merge($view,$order);
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order['id']);
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 代理终止订单时间
        $stop1_time=HgCartLog::get_stop1_time($order['id']);
        $view['stop1_time']=$stop1_time?$stop1_time:'';
        // 品牌
        $view['brand']=explode('&gt;',$order['car_name']);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
        $view['baojia']=$baojia;

        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($order['car_id']);
        $view['barnd_info']=$barnd_info;
        //车辆扩展信息
        $carFields = HgFields::getFieldsList('carmodel');
        foreach ($carFields as $k => $v) {
            $carFields[$k] = unserialize($v);
        }
        $carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);

        /**
         * 该报价对应车型本身信息----报价
         * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
        */
        $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['car_id']);
        foreach ($bjCarInfo as $key => $val) {
        	if (is_array($carmodelInfo[$key])) {
        		$carmodelInfo[$key] = $carmodelInfo[$key][$val];
        	} else {
        		$carmodelInfo[$key] = $val;
        	}
        }

        // 合并该报价对应车型的具体参数
        $baojia = array_merge($baojia, $carmodelInfo);

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
        // 客户承诺上牌地区
        $view['shangpaiarea']=Area::getAreaName($order['shangpai_area']);
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
        // 报价选装件
        $view['xzjs']=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        // 保险公司名称
        $view['baoxianname']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxianname']=$baoxian;
        }
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 客户信息
        $view['buyer']=HgUser::getMember($order['buy_id']);
        $baoxian_type=getBaoxianType($order['buytype'],$baojia['seat_num']);//根据车座判断保险类型 add by jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$baoxian_type);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$baoxian_type);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$baoxian_type);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$baoxian_type);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$baoxian_type);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$baoxian_type);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id']);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){

            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);


        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

        //订单扩展信息
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        // 特殊文件
        $view['ziliao']=unserialize($order_attr->wenjian);
	 /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;

        //终止订单，更新结算状态
        $eff = HgCart::where("order_num",$order_num)->update(array('calc_status'=>1));

        return view('dealer.stop1',$view);
    }
    // 反馈ok，等待付担保金
    public function feedBackOk($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '诚意金已付，反馈完成,等待付担保金',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        // 判断订单状态是否在这步
        // if ($order['cart_sub_status']!=$_ENV['_CONF']['config']['hg_order']['order_earnest_backok']) {
        //     exit('请在会员中心查看订单');
        // }
        $view=array_merge($view,$order);
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order['id']);

        $view['earnest_time']=$earnest_time?$earnest_time:'';

        // 取得客户付完诚意金代理反馈订单的时间
        $feedback_time=HgCartLog::get_feedback_time($order['id']);
        if(!$feedback_time) exit('代理还未反馈此订单');
        $view['feedback_time']=$feedback_time ? $feedback_time : '';

        // 品牌
        $view['brand']=explode('&gt;',$order['car_name']);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
        $view['baojia']=$baojia;
        // 保险公司信息
        if ($baojia['bj_bx_select']) {
            $view['baoxianname']=HgBaoXian::getName($baojia['bj_bx_select']);
        }
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
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
        // 远期订单计算时间
        $new_date=date_create($order['created_at']);
        date_add ( $new_date ,  date_interval_create_from_date_string ( $baojia['bj_jc_period'].' months' ));
        $view['new_date']=date_format($new_date,'Y年m月d日');
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
        $view['shangpai_area']=Area::getAreaName($order['shangpai_area']);
        // 报价中已经安装的原厂选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=empty($xzjs)?array():$xzjs->toArray();
        // 代理选择的原厂精品选装件
        $xzj_daili=HgXzjDaili::getDealerXzj($order['car_id'],$order['dealer_id'],$_SESSION['member_id']);
        $xzj_daili=empty($xzj_daili)?array():$xzj_daili->toArray();
        // 可供客户选择的选装件，除去已经安装的
        $ids = array();
        foreach ($xzjs as $key => $value) {
            $ids[]=$value->m_id;//要去除的id
        }
        $view['xzj_daili']=unsetArray($xzj_daili,$ids);
        // 保险公司名称
        $view['baoxian_name']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxian_name']=$baoxian['bx_title'];
        }

        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;

        // 客户信息
        $view['buyer']=HgUser::getMember($order['buy_id']);
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id']);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){

            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;
        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 其他上牌资料
        $ziliao=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
		
        if(!empty($ziliao->wenjian) && $ziliao->wenjian!='N;'){
        	$view['ziliao'] = unserialize($ziliao['wenjian']);
        }else{
        	$view['ziliao'] = array();
        }

        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);
          /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;
        return view('dealer.feedbackok',$view);
    }
    // 收到担保金等待响应
    public function feedBackResponse($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '担保金已付，等待反馈',
        ];
        // 取得订单基本信息
        $order=$order->toArray();
        $view=array_merge($view,$order);
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
        // 品牌
        $view['brand']=explode('&gt;',$order['car_name']);
        // 取得报价单信息
        $baojia=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
        //报价扩展信息包括其他费用
        $baojia['more']=HgBaojiaMore::getBaojiaMove($baojia['bj_id']);
        $view['baojia']=$baojia;
        // 经销商信息
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
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
        // 判断是否修改过车辆信息
        $car=HgEditInfo::getEditInfo($order_num,201);
        if (!empty($car->carinfo)) {
            $editcar=unserialize($car->carinfo);
            $view['body_color']=$editcar['body_color'];
            $view['interior_color']=$editcar['interior_color'];
            $view['guobieTitle']=$editcar['guobieTitle'];
            $view['paifangTitle']=$editcar['paifang'];
            $barnd_info['chuchang_time']=$editcar['chuchang'];
            $baojia['bj_licheng']=$editcar['licheng'];
            $baojia['bj_jc_period']=$editcar['zhouqi'];
            $view['xzj']=unserialize($car->xzj);
            // 赠品或免费服务器
            $view['zengpin']=unserialize($car->zengpin);

        }else{//订单没有被修改 获取原始信息
            $view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
            $view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
            // 国别
            $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
            // 排放
            $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
            // 报价中已装原厂选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=empty($xzjs)?array():$xzjs->toArray();
            // 赠品或免费服务器
            $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        }

        // 可供选择的非原厂选装件
        $view['fycxzj_daili']=HgXzjDaili::getDealerXzjFyc($order['car_id'],$order['dealer_id'],$order['seller_id']);
        // 补贴
        $view['butieTitle'] = $carFields['butie'][$bjCarInfo['butie']];
        // 非现车交车时间,以担保金进入时间为准
        $base_date=date_create($deposit_time);
        $new_date=date_add ( $base_date ,  date_interval_create_from_date_string ( $baojia['bj_jc_period'].' months' ));
        $view['new_date']=date_format($new_date,'Y-m-d H:i:s');
        // 非现车交车通知时限
        $notice_time=date_add ( $new_date ,  date_interval_create_from_date_string ( '-7 days' ));
        $view['notice_time']=date_format($notice_time,'Y-m-d H:i:s');
        // 现车交车时限
        $base_date=date_create($deposit_time);
        $xianche_time=date_add ( $base_date ,  date_interval_create_from_date_string ('15 days' ));

        $view['xianche_time']=date_format($xianche_time,'Y-m-d H:i:s');
        // 现车通知时限
        $xianche_notice=date_add ( $xianche_time ,  date_interval_create_from_date_string ('-8 days' ));

        $view['xianche_notice']=date_format($xianche_notice,'Y-m-d H:i:s');
        /*交车剩余*/
        $date_now  = new  DateTime ( "now" );
        if ($baojia['bj_jc_period']) {
            // 非现车
            $surplus_time_fx_notice= $date_now -> diff ( $notice_time );
            $surplus_time_fx= ($surplus_time_fx_notice->days)+7;
            $view['surplus_time_fx']=  $surplus_time_fx ;
        }else{
            // 现车
            $surplus_time_notice= $date_now -> diff ( $xianche_notice );
            $surplus_time= ($surplus_time_notice->days)+8;
            $view['surplus_time']=  $surplus_time ;
            $view['surplus_time_notice']=  $surplus_time_notice ->days;
        }

        // 等待响应剩余时间
        $view['xy_time']=diffBetweenTwoDays(date('Y-m-d H:i:s',strtotime($deposit_time)+20*60),2);
        // 如果过了响应时间则自动进入下一步
        if ($view['xy_time']<=0) {
            // 现车
            if ($baojia['bj_producetime']) {

                $affectedRows = HgCart::where('order_num', '=', $order_num)->update(['cart_status' => 3,'cart_sub_status'=>301,'jiaoche_time'=>$view['xianche_time'],'jiaoche_notice_time'=>$view['xianche_notice']]);
                if(!$affectedRows) dd('更新订单状态失败');
                // 超时系统自动响应 确认担保金，并记录日志
                $log_id=HgCartLog::putLog($order['id'],$_SESSION['member_id'],203,"IndexController/feedBackResponse","担保金支付完成响应ok(经销商超时，自动响应)",1,'
售方响应订单时间');
                return redirect(route('responseok', ['order_num' =>$order_num]));
            }else{
                $affectedRows = HgCart::where('order_num', '=', $order_num)->update(['cart_status' => 3,'cart_sub_status'=>301,'jiaoche_time'=>$view['new_date'],'jiaoche_notice_time'=>$view['notice_time']]);
                if(!$affectedRows) dd('更新订单状态失败');
                // 超时系统自动响应 确认担保金，并记录日志
                $log_id=HgCartLog::putLog($order['id'],$_SESSION['member_id'],203,"IndexController/feedBackResponse","担保金支付完成响应ok(经销商超时，自动响应)",1,'
售方响应订单时间');
                return redirect(route('responseok', ['order_num' =>$order_num]));
            }
        }
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

        // 用户选择的选装件
        $view['userxzj']=HgUserXzj::getAllInfo($order_num);
        // dd($userxzj);
        // 原厂选装件数量
        $ycxzj_count=HgUserXzj::getCount($order_num,1);
        $view['ycxzj_count']=$ycxzj_count;

        // 保险公司名称
        $view['baoxianname']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxianname']=$baoxian;
        }
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 客户信息
        $view['buyer']=HgUser::getMember($order['buy_id']);
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id']);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){

            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;


        $view['wenjian']=unserialize($order['wenjian']);
         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        // 其他上牌资料
        $ziliao=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        $view['ziliao']=empty($ziliao->wenjian)?array():unserialize($ziliao->wenjian);
        // 此车型在代理商处拥有的选装件
        $view['dealer_xzj']=HgXzj::getDealerXzj($baojia['brand_id'],$baojia['dealer_id'],$baojia['m_id']);
        // dd($view['dealer_xzj']);
         // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;

        // 等待反馈剩余时间
        if (empty($view['ziliao'])) {
        	$view['endtime']=date('Y-m-d H:i:s',strtotime($earnest_time)+20*60);
        }else{
        	$view['endtime']=date('Y-m-d H:i:s',strtotime($earnest_time)+24*3600);
        }
        if ($view['endtime']>date('Y-m-d H:i:s')) {
        	$view['timeout']=0;
        }else{
        	$view['timeout']=1;
        }

        return view('dealer.feedbackresponse',$view);
    }
    // 处理担保金响应
    public function saveResponse()
    {
        /*处理提交请求*/
        if (!empty(Request::Input('xzj'))) {
            foreach (Request::Input('xzj') as $key => $value) {
                $arrayName = explode ( "," ,  $value );
                $fycxzj=new HgFycXzj;
                $fycxzj->xzj_brand=$arrayName[0];
                $fycxzj->xzj_name=$arrayName[1];
                $fycxzj->xzj_model=$arrayName[2];
                $fycxzj->has_num=$arrayName[3];
                $fycxzj->discount_price=$arrayName[4];
                $fycxzj->order_num=$arrayName[5];
                $fycxzj->mid=$arrayName[6];//add by jerry to add a field xzj_id ,modify struct of table car_hg_fyc_xzj
                $fycxzj->save();
            }
        }


        /*$affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update(['not_original' => serialize(Request::Input('xzj'))]);
        if (!$affectedRows) {
            // 插入订单属性表
            $cartattr = new HgCartAttr;
            $cartattr->cart_id = Request::Input('id');
            $cartattr->not_original = serialize(Request::Input('xzj'));
            $cartattr->save();
        }*/
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => 3,'cart_sub_status'=>301,'jiaoche_time'=>Request::Input('jiaoche_time'),'jiaoche_notice_time'=>Request::Input('jiaoche_notice_time')]);
        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],203,"IndexController/feedBackResponse","担保金支付完成代理响应ok",0,'售方担保金响应订单时间');
        return redirect(route('responseok', ['order_num' =>Request::Input('order_num') ]));
    }
    // 收到担保金等待响应ok
    public function responseOk($order_num)
    {

        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view = [
            'title' => '已响应订单，等待预约交车',
        ];
        // 取得订单基本信息
        $order=$order->toArray();
        $view=array_merge($view,$order);
        // 交车时限，剩余天数
        $view['jiaoche_time']=$order['jiaoche_time'];
        $view['surplus_time']=diffBetweenTwoDays($view['jiaoche_time'],1);
        // 交车通知时限，剩余天数
        $view['jiaoche_notice_time']=$order['jiaoche_notice_time'];
        $view['surplus_time_notice']=diffBetweenTwoDays($view['jiaoche_notice_time'],1);

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
        $body_color=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
        $interior_color=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
        // 国别
        $guobieTitle = $carFields['guobie'][$bjCarInfo['guobie']];
        // 排放
        $paifang = $carFields['paifang'][$bjCarInfo['paifang']];
        // 补贴
        $view['butieTitle'] = $carFields['butie'][$bjCarInfo['butie']];

        // 车身颜色，内饰颜色，里程，出厂日期，排放标准，国别，交车周期，这些涉及修改过的信息单独列出来
        $car=HgEditInfo::getEditInfo($order_num,201);
        if (!empty($car->carinfo)) {
            $view['carinfo']=unserialize($car->carinfo);
            $view['body_color']=$view['carinfo']['body_color'];
            $view['interior_color']=$view['carinfo']['interior_color'];
            $view['guobieTitle']=$view['carinfo']['guobieTitle'];
            $view['paifang']=$view['carinfo']['paifang'];
            $view['chuchang']=$view['carinfo']['chuchang'];
            $view['licheng']=$view['carinfo']['licheng'];
            $view['zhouqi']=$view['carinfo']['zhouqi'];
            $view['xzj']=unserialize($car->xzj);
            // 赠品或免费服务器
            $view['zengpin']=unserialize($car->zengpin);
        }else{//订单没有被修改 获取原始信息
            $carinfo['body_color']= $view['body_color']= $body_color;
            $carinfo['interior_color']=$view['interior_color']=$interior_color;
            $carinfo['guobieTitle']=$view['guobieTitle']=$guobieTitle;
            $carinfo['paifang']=$view['paifang']=$paifang;
            $carinfo['chuchang']=$view['chuchang']=$barnd_info['chuchang_time'];
            $carinfo['licheng']= $view['licheng']=$baojia['bj_licheng'];
            $carinfo['zhouqi']=$view['zhouqi']=$baojia['bj_jc_period'];
            $view['carinfo']=$carinfo;
            // 报价中已装原厂选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=empty($xzjs)?array():$xzjs->toArray();
            // 赠品或免费服务器
            $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        }

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

        // 用户选择的选装件
        $view['userxzj']=HgUserXzj::getAllInfo($order_num);
        // 原厂选装件数量
        $ycxzj_count=HgUserXzj::getCount($order_num,1);
        // 非原厂选装件数量
        $fycxzj_count=HgUserXzj::getCount($order_num,0);
        $view['ycxzj_count']=$ycxzj_count;
        $view['fycxzj_count']=$fycxzj_count;

        // 保险公司名称
        $view['baoxianname']='';
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
            $view['baoxianname']=$baoxian;
        }
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 客户信息
        $view['buyer']=HgUser::getMember($order['buy_id']);
        $carSeatType = ($carmodelInfo['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id'],$carSeatType);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id'],$carSeatType);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id'],$carSeatType);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id'],$carSeatType);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id']);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        //报价扩展信息包括其他费用
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        $other_price = array();
        if(array_key_exists('other_price',$view['more'])){

            foreach ($view['more']['other_price'] as $key => $value) {
                // 取得自定义字段标题
                $other_title=HgFields::where('name','=',$key)->first();
                $other_price[$other_title['title']]=$value;
            }
        }
        $view['other_price']=$other_price;

        $view['wenjian']=unserialize($order['wenjian']);
         /*不计免赔险的费率*/
        // 取得对应保险id
        $baoxian_id=HgDealerBaoXian::getDealerBaoXianId($order['seller_id'],$baojia['bj_bx_select']);
        // 确定车的保险类型
        if ($order['buytype']==1) {
            if ($carmodelInfo['seat_num']<7) {
                $cartype=3;
            }else{
                $cartype=4;
            }
        }else{
              if ($carmodelInfo['seat_num']<7) {
                    $cartype=1;
                }else{
                    $cartype=2;
                }
        }
        $bjm_sanzhe_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'sanzhe');
        $view['bjm_sanzhe_rate']=round($bjm_sanzhe_rate,2);
        $bjm_renyuan_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'renyuan');
        $view['bjm_renyuan_rate']=round($bjm_renyuan_rate,2);
        $bjm_huahen_rate=HgDealerBaoXianBuJiMian::getBuJiMianRate($cartype,$baoxian_id,'huahen');
        $view['bjm_huahen_rate']=round($bjm_huahen_rate,2);
        $files = array();
        $cate=HgFileCate::getCate();
        foreach ($cate as $key => $value) {
            $files[$key]['catename']=$value['cate'];
            $files[$key]['filename']=HgAgentFiles::getFiles($_SESSION['member_id'],$value['cate_id'])->toArray();
        }
        $view['files']=$files;
        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('Y-m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
        // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);


        // 取得搜索属性，如车源距离,行驶里程等
        $att=HgCarAtt::getAtt();
        $view['att'] =$att;
        $view['paifangbianzhun'] = $carmodelInfo['paifang'];
        $view['paifangTitle'] = $carFields['paifang'][$carmodelInfo['paifang']];
        $view['bj_licheng'] = $baojia['bj_licheng'];
        $view['bj_jc_period'] = $baojia['bj_jc_period'];

        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;

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




        return view('dealer.responseok',$view);
    }

    public function saveXzj(){
    	$order_num = Request::Input("order_num");
    	$xzj =  Request::Input("xzj");
    	if(count($xzj)>0){
    		$data['error_code'] = 0;
    		$data['error_msg'] = '更新成功';
    		foreach($xzj as $k =>$v){
    			$a=HgUserXzj::where('order_num','=',$order_num)->where('id','=',$k)->first();
    			if($v ==2){
    				$dataUp['num'] = $a->xzj_modify;
    				$dataUp['notice'] = "经销商同意 ：此选装件用户数量从 ".$a->num." 修改为 ".$a->xzj_modify." ";
    				if($a->xzj_modify < $a->num){ //减少的话 增加库存
    					$d=HgXzjDaili::where('id', $k)->increment('xzj_has_num', $a->num - $a->xzj_modify);
    				}
    			}else{
    				$dataUp['notice'] = "经销商不同意 ：此选装件用户数量从 ".$a->num." 修改为 ".$a->xzj_modify." ";
    			}
    			$dataUp['xzj_status'] =$v;
    			$u=HgUserXzj::where('order_num','=',$order_num)->where('id','=',$k)->update($dataUp);
    			if(!$u){
    				$data['error_code'] = 1;
    				$data['error_msg'] = 1;
    			}
    		}

    		return json_encode($data);

    	}

    }

    // 查看订单概况
    public function getOverview($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){
            exit('订单不存在');
        }
        $view=[
            'order_num'=>$order_num,
            'title'=>'订单概况',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        if ($order['cart_sub_status']>=401) {
            $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        }
        /*
        *报价信息
        */
        $bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
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
        $view['bj']=$bj;
        /*
        * 各项时间
        */
        // 取得诚意金进入时间
        $view['earnest_time']=HgCartLog::get_earnest_time($order['id']);
        // 经销商反馈订单时间
        $view['feedback_time']=HgCartLog::get_feedback_time($order['id']);
        //  担保金进去加信宝时间
        $view['deposit_time']=HgCartLog::get_deposit_time($order['id']);
        // 收到担保金响应订单时间,经销商开始执行订单时间
        $view['response_time']=HgCartLog::get_response_time($order['id']);
        // 经销商代理发出交车通知时间
        $view['pdinotice_time']=HgCartLog::get_pdinotice_time($order['id']);
        /*
        *经销商代理信息 (卖家)
        */
        $view['seller']=HgSeller::getProxy($order['seller_id']);

        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        /*
        *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();
        /*
        *赠品或免费服务器
        *如果经销商代理做过说明则从订单属性表中读，否则从报价赠品表中读
        */
        if ($order['cart_sub_status']>=401) {

            $view['zengpin']=unserialize($order_attr->zengpin);
        }else{
            $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($bj['bj_id']);
        }

        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        /*
        *补贴，客户在没有预约选择补贴办理方式时调用报价里面的约定
        */
        if ($order['cart_sub_status']>=402) {
            $view['jn_butie']=$order_attr->butie;
            $view['zh_butie']=$order_attr->zhihuan;
            $view['cj_butie']=$order_attr->cj_butie;
        }else{
            $view['jn_butie']=$bj['bj_butie'];
            $view['zh_butie']=$bj['bj_zf_butie'];
            $view['cj_butie']=$bj['bj_cj_butie'];
        }
        $view['order_attr']=$order_attr;
         // 随车工具
        $view['gongju'] = empty($baojia['more']['gongju'])?"":HgAnnex::getTitle($baojia['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($baojia['more']['wenjian'])?"":HgAnnex::getTitle($baojia['more']['wenjian']);

        //如果交车前订单有修改
        $carEditLog = HgEditInfo::getEditInfo($order_num,302);
        $view['xzj'] = !empty($carEditLog->xzj)?unserialize($carEditLog->xzj):$view['xzj'];
        $view['zengpin'] =!empty($carEditLog->zengpin)?unserialize($carEditLog->zengpin):$view['zengpin'];
        $view['ticheren'] = !empty($view['order_attr']['ticheren'])?unserialize($view['order_attr']['ticheren']):array("username"=>"","mobile"=>"");
        /**
		 * 获取订单日志
		 */
		$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
		$view['cart_log'] = $log;

        return view('dealer.overview',$view);
    }


}
