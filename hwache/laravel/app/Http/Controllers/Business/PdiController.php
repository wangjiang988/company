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
use App\Models\HgFiles;
use App\Models\HgAgentFiles;
use App\Models\HgFileCate;
use App\Models\HgSeller;
use App\Models\HgUser;
use App\Models\HgBaojiaPrice,App\Models\HgDealerBaoXianBuJiMian;
use App\Models\HgMoney,App\Models\HgUserXzj;
use App\Models\HgOrderFiles;
use App\Models\HgWaiter;
use App\Models\HgAnnex;
use App\Models\HgUserZengpin;
use App\Models\HgEditInfo;
use App\Models\HgDealerBaoXian;
use App\Models\HgCarAtt;
use App\Models\HgDefend,App\Models\HgDispute,App\Models\HgEvidence;
use App\Models\HgCartJiaocheExt;
use Config;
use Validator;
use App\Models\HgMediate;
use App\Models\HgCartJiaoche;
use App\Com\Hwache\Order\Order;
class PdiController extends Controller {
    public function __construct( Order $order)
    {
        $this->middleware('auth.seller');
        $this->order   = $order;
    }

    /**
     * 等待发出交车通知页面
     */
    public function index($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }

        /*// 更新订单状态
        $affectedRows = HgCart::where('id', '=', $id->id)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_jiaoche'],'cart_sub_status'=>$_ENV['_CONF']['config']['hg_order']['order_jiaoche_wait']]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        } */
       $view = [
            'title' => '等待发出交车通知',
            'order_num'=>$order_num,
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
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
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();

        /*
        *保险价格计算
        */
        // 保险折扣
        $baoxian_discount=$bj['bj_baoxian_discount'];
        // 保险的类别
        $baoxian_type=getBaoxianType($order['buytype'],$bj['seat_num']);
        // 全部额度
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['ziran']=HgBaojiaBaoxianZiranPrice::getPrice($bj['bj_id'],$baoxian_type);
        // 不计免费率

        $view['bjm_sanzhe_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'sanzhe');
         $view['bjm_renyuan_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'renyuan');
         $view['bjm_huahen_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'huahen');
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

        // 取得上牌需要的文件

        $view['shangpai_file']=HgAgentFiles::getFiles($_SESSION['member_id'],2)->toArray();

        // 投保需要的文件
        $view['baoxian_file']=HgAgentFiles::getFiles($_SESSION['member_id'],1)->toArray();
        // 上临牌需要 的文件
        $view['linpai_file']=HgAgentFiles::getFiles($_SESSION['member_id'],3)->toArray();
        // 国家节能补贴需要的文件
        $view['butie_file']=HgAgentFiles::getFiles($_SESSION['member_id'],4)->toArray();

        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
        // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;
        

        return view('dealer.pdiwait',$view);

    }
   // 发出交车通知
    public function PdiNotice($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        // 判断订单状态是否在这步
        // if ($order['cart_sub_status']!=$_ENV['_CONF']['config']['hg_order']['order_jiaoche_wait']) {
        //     exit('请在会员中心查看订单');
        // }
       $view = [
            'title' => '发出交车通知',
            'order_num'=>$order_num,
        ];
        $view['order']=$order;

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
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();

        /*
        *保险价格计算
        */
        // 保险折扣
        $baoxian_discount=$bj['bj_baoxian_discount'];
        // 保险的类别
        $baoxian_type=getBaoxianType($order['buytype'],$bj['seat_num']);
        // 全部额度
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['ziran']=HgBaojiaBaoxianZiranPrice::getPrice($bj['bj_id'],$baoxian_type);
        // 不计免费率

        $view['bjm_sanzhe_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'sanzhe');
         $view['bjm_renyuan_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'renyuan');
         $view['bjm_huahen_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'huahen');

         /*// 推荐组合
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
        $view['seat']=$seat;*/
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

        // 取得上牌需要的文件

        $view['shangpai_file']=HgAgentFiles::getFiles($_SESSION['member_id'],2)->toArray();

        // 投保需要的文件
        $view['baoxian_file']=HgAgentFiles::getFiles($_SESSION['member_id'],1)->toArray();
        // 上临牌需要 的文件
        $view['linpai_file']=HgAgentFiles::getFiles($_SESSION['member_id'],3)->toArray();
        // 国家节能补贴需要的文件
        $view['butie_file']=HgAgentFiles::getFiles($_SESSION['member_id'],4)->toArray();

        // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
      // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;
        return view('dealer.pdinotice',$view);

    }
    // 保存代理提交的信息并设置订单状态
    public function postPdiNotice()
    {
		
    	$order_num = Request::Input("order_num");
        // 代理修改订单
        if (Request::Input('edit') ) 
        {
            if(Request::Input('jiaoche') == 1){//修改交车信息 执行脚本
	        	$xzjOriginData = Request::Input('origin_ycxzj');
	            $zengpingOriginData = Request::Input('origin_zengping');
	            $xzjEditData = Request::Input('ycxzj');
	            $zengpingEditData = Request::Input('zengping');
	            
	            $origin_body_color = Request::Input('origin_body_color');
	            $origin_interior_color = Request::Input('origin_interior_color');
	            $origin_bj_producetime = Request::Input('origin_bj_producetime');
	            $origin_licheng = Request::Input('origin_licheng');
	            $origin_bj_jc_period = Request::Input('origin_bj_jc_period');
	            $origin_paifang = Request::Input('origin_paifang');
	            
	            $body_color_modify = Request::Input('body_color_modify');
	            $interior_color_modify = Request::Input('interior_color_modify');
	            $month_modify = Request::Input('bi_producetime_month_modify')<10?"0".printf('%d',Request::Input('bi_producetime_month_modify')):Request::Input('bi_producetime_month_modify');
	            $bj_producetime_modify = Request::Input('bj_producetime_year_modify')."-".$month_modify;
	            $licheng_modify = Request::Input('bj_licheng_modify');
	            $bj_jc_period_modify = Request::Input('bj_jc_period_modify');
	            $paifang_modify = Request::Input('paifang_modify');

	            $isModifyCarInfo = 0;//初始化 车辆信息没有被修改
	            $isModifyXzj = 0;//初始化 车辆选装件没有被修改
	            $isModifyZengpin = 0;//初始化 赠品信息没有被修改
	            //以下代码作为 判断交车信息是否给修改过
	            $modifyArray = array();
	            if($origin_body_color!=$body_color_modify || $origin_interior_color!=$interior_color_modify ||$origin_bj_producetime!=$bj_producetime_modify || $origin_licheng !=$licheng_modify || $origin_bj_jc_period!=$bj_jc_period_modify || $origin_paifang!=$paifang_modify){
	            	$isModifyCarInfo = 1;	            		            	
	            }
	            
	            $modifyArray["carinfo"]=serialize(array(
	            		"body_color"=>$body_color_modify,
	            		"interior_color"=>$interior_color_modify,
	            		"chuchang"=>$bj_producetime_modify,
	            		"licheng"=>$licheng_modify,
	            		"zhouqi"=>$bj_jc_period_modify,
	            		"paifang"=>$paifang_modify
	            ));
	            
	            if(count($xzjOriginData) >=1){
	            	$xzjModelTitle = Request::Input("xzjModelTitle");
	            	$xzjModelModel = Request::Input("xzjModelModel");
	            	$xzjModelGuidePrice = Request::Input("xzjModelGuidePrice");
	            	$xzjModelFee = Request::Input("xzjModelFee");
	            	$xzjModelPrice = Request::Input("xzjModelPrice");
                    $xzjModelBeizhu = Request::Input("xzjModelBeizhu");
		            foreach($xzjOriginData as $k => $v){
		            	if($v != $xzjEditData[$k]){		            		
		            		$isModifyXzj = 1;
		            	}
		            	$modifyXzjArray[$k] = array(
		            			"xzj_id"=>$k,
		            			"xzj_title"=>$xzjModelTitle[$k],
		            			"xzj_model"=>$xzjModelModel[$k],
		            			"xzj_guide_price"=>$xzjModelGuidePrice[$k],
		            			"fee"=>$xzjModelFee[$k],
		            			"price"=>$xzjModelPrice[$k],
		            			"num"=>$xzjEditData[$k],
		            			"old_num"=>$v,
                                "xzj_yc"=>1,
                                "is_install"=>1,
                                "beizhu"=> $xzjModelBeizhu[$k],
		            	) ;
		            	
		            	$modifyArray["xzj"] = serialize($modifyXzjArray);
		            	
		            	
		            }
	            }
	            if(count($zengpingOriginData) >=1){
	            	$zengpinModelTitle = Request::Input("zengpinModelTitle");
	            	$zengpinModeInstall = Request::Input("zengpinModeInstall");
                    $zengpinModelbeizhu = Request::Input("zengpinModelbeizhu");
		            foreach($zengpingOriginData as $k => $v){
		            	if($v != $zengpingEditData[$k]){
		            		$isModifyZengpin = 1;
		            	}
		            	$modifyZengpinArray[$k] = array(
		            			"id"=>$k,
		            			"title"=>$zengpinModelTitle[$k],
		            			"is_install"=>$zengpinModeInstall[$k],
		            			"num"=>$zengpingEditData[$k],
                                "beizhu"=>$zengpinModelbeizhu[$k],
		            			"old_num"=>$v
		            	) ;
		            	
		            	$modifyArray["zengpin"] = serialize($modifyZengpinArray);
		            }
	            }
	            if($isModifyCarInfo == 1 || $isModifyXzj == 1 || $isModifyZengpin == 1){//订单有修改 执行语句
	            	// 修改部分内容
	            	//车身信息修改
	            	$modifyArray["order_num"] = $order_num;
	            	$modifyArray["status"] = Request::Input('currentstatus');
	            	$insertModifyInfoLog = HgEditInfo::insertNewModifyLog($modifyArray);
	            	if (!$insertModifyInfoLog ) dd('更新订单失败');
	            	$affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => 3,'cart_sub_status'=>302]);
	            	if (!$affectedRows ) dd('更新订单状态失败');
                    // 记录日志
                    $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],302,"IndexController/responseOk","担保金反馈ok修改订单",0,'售方提议修改订单时间');
	            	return redirect(route('pdiedit',['order_num' =>Request::Input('order_num') ]));
	            }
            }
            
            // 代理终止订单
            if (Request::Input('jiaoche')==2) {
                 
                // 更新订单状态
                $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => 1000,'cart_sub_status'=>1005]);
                if (!$affectedRows) dd('更新订单状态失败');
                HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],1005,"IndexController/responseOk","代理在发出交车通知前终止订单",0,"代理发交车通知前终止订单时间");
                return redirect(route('stop2', ['order_num' =>Request::Input('order_num') ]));
            }
           
        }
       
        $jc_date = Request::Input("pdi_date_dealer");//获取交车日期

        if(empty($jc_date)){
        	echo "您还没有选择交车日期，请退回重新选择";
        	echo '<script type="text/javascript">history.go(-1)</script>';
            exit;
        }
 
        // 赠品数据
        if (Request::Input('zengpin')) {
            foreach (Request::Input('zengpin') as $key => $value) {
                $onezengpin=HgBaojiaZengpin::getOneZengpin(Request::Input('bj_id'),$key);
                HgUserZengpin::insert([
                        'title'=>$onezengpin->title,
                        'num'=>$onezengpin->num,
                        'install'=>$onezengpin->is_install?'已安装':'未安装',
                        'notice'=>$value,
                        'order_num'=>Request::Input('order_num'),
                    ]);
            }
        }

        foreach (Request::Input('file') as $key => $value) 
        {
            
            foreach ($value['cate'] as $k => $v) {
                if ($value['title'][$k]) {
                    HgOrderFiles::insert(['title'=>$value['title'][$k],'num'=>$value['sl'][$k],'yj'=>$value['yj'][$k],'cate'=>$value['cate'][$k],'cate_id'=>$value['cate_id'][$k],'file_id'=>$value['file_id'][$k] ,'isself'=>$value['isself'][$k],'order_num'=>Request::Input('order_num')]);
                }
            }
            
        }
        
        $map = array(
            'vin' => Request::Input('vin'),
            'engine_no'=>Request::Input('engine_no'),
            'production_date'=>Request::Input('production_date'),
            'mileage'=>Request::Input('mileage'),
            'pdi_date_dealer'=>trimall(Request::Input('pdi_date_dealer')),

           
            );
        // 更新提交的数据
        $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($map);

        if (!$affectedRows) {
            // 插入订单属性表
            $map['cart_id']=Request::Input('id');
            HgCartAttr::insert(array($map));

        }
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_jiaoche'],'cart_sub_status'=>$_ENV['_CONF']['config']['hg_order']['order_jiaoche_sent_notify']]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],301,"IndexController/PdiNotice","代理发出交车邀请",0,'售方交车通知发出时间');
        return redirect(route('pdinoticeok', ['order_num' =>Request::Input('order_num') ]));
    }
    

    // 发出交车通知前代理终止订单
    public function stop2($order_num)
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
        // 用户选择的选装件
        $view['userxzj']=HgUserXzj::getAllInfo($order_num);
        // 原厂选装件数量
        $ycxzj_count=HgUserXzj::getCount($order_num,1);
        // 非原厂选装件数量
        $fycxzj_count=HgUserXzj::getCount($order_num,0);
        $view['ycxzj_count']=$ycxzj_count;
        $view['fycxzj_count']=$fycxzj_count;
        /*$xzj_yc_install_front = array();
        if(!empty($view['xzj']) && count($view['xzj'])>0){
            foreach($view['xzj'] as $k =>$v){
                if($v['is_install'] == 1 && $v['xzj_yc'] == 1 && $v['xzj_front']){
                    $xzj_yc_install_front[] = $v;
                }
            }
        }
        $view['xzj_yc_install_front'] = $xzj_yc_install_front;*/
        
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        // 客户信息
        $view['buyer']=HgUser::getMember($order['buy_id']);
       
        //终止订单，更新结算状态
        $eff = HgCart::where("order_num",$order_num)->update(array('calc_status'=>1));
        
        return view('dealer.stop2',$view);
    }
    // 提议修改订单，等待客户确认
    public function pdiEdit($order_num)
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
    	// 报价中已安装的原厂选装件
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
    	/*$xzj_yc_install_front = array();
    	if(!empty($view['xzj']) && count($view['xzj'])>0){
    		foreach($view['xzj'] as $k =>$v){
    			if($v['is_install'] == 1 && $v['xzj_yc'] == 1 && $v['xzj_front']){
    				$xzj_yc_install_front[] = $v;
    			}
    		}
    	}
    	$view['xzj_yc_install_front'] = $xzj_yc_install_front;*/
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
    	// 赠品或免费服务器
    	$view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
    	// 判断赠品中是否有非原厂选装件
    	 
    	 
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
    	 
    	//如果订单已经被修改过，取最后一次修改的信息 并赋值 add by jerry 2016-02-01
    	/*$carEditLog = HgEditInfo::getEditInfo($order_num,302);
    	$view['modifyLogCarInfo'] = !empty($carEditLog->carinfo)?unserialize($carEditLog->carinfo):array();
    	$view['modifyLogXzj'] = !empty($carEditLog->xzj)?unserialize($carEditLog->xzj):array();
    	$view['modifyLogZengpin'] =!empty($carEditLog->zengpin)?unserialize($carEditLog->zengpin):array();

    	$view['body_color'] = isset($view['modifyLogCarInfo']['body_color'])?$view['modifyLogCarInfo']['body_color']:$view['body_color'];
    	$view['interior_color'] = isset($view['modifyLogCarInfo']['interior_color'])?$view['modifyLogCarInfo']['interior_color']:$view['interior_color'];
    	$view['baojia']['bj_producetime']= isset($view['modifyLogCarInfo']['chuchang'])?$view['modifyLogCarInfo']['chuchang']:$view['baojia']['bj_producetime'];
    	$view['bj_jc_period'] = isset($view['modifyLogCarInfo']['zhouqi'])?$view['modifyLogCarInfo']['zhouqi']:$view['bj_jc_period'];
    	$view['bj_licheng'] = isset($view['modifyLogCarInfo']['bj_licheng'])?$view['modifyLogCarInfo']['bj_licheng']:$view['bj_licheng'];
    	$view['paifangTitle'] = isset($view['modifyLogCarInfo']['paifang'])?$view['modifyLogCarInfo']['paifang']:$view['paifangTitle'];*/
        // 取得修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        $view['editcarinfo']=unserialize($carEditLog->carinfo);
        $view['editxzj']=unserialize($carEditLog->xzj);
        $view['editzengpin']=unserialize($carEditLog->zengpin);
    	/**
    	 * 获取订单日志
    	 */
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	
    	$view['cart_log'] = $log;
    	return view('dealer.pdiedit',$view);
    }
    // 客户接受订单修改，等待发出交车通知
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
    	
    	// 用户选择的选装件
    	$view['userxzj']=HgUserXzj::getAllInfo($order_num)->toArray();
        
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
    	
    	//如果订单已经被修改过，取最后一次修改的信息 并赋值 add by jerry 2016-02-01
    	$carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['body_color']=$editcar['body_color'];
            $view['interior_color']=$editcar['interior_color'];
            $view['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['baojia']['bj_licheng']=$view['bj_licheng']=$editcar['licheng'];
            $view['baojia']['bj_jc_period']=$view['bj_jc_period']=$editcar['zhouqi'];
            $view['paifangTitle']=$editcar['paifang'];

        }else{
            $view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
            $view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];
            // 排放
            $view['paifangTitle'] = $carFields['paifang'][$bjCarInfo['paifang']];
        }
        // 国别
        $view['guobieTitle'] = $carFields['guobie'][$bjCarInfo['guobie']];
        if (!empty($carEditLog->xzj)) {
            $view['xzj']=$view['xzj_yc_install_front']=unserialize($carEditLog->xzj);

        }else{
            // 报价选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
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
        }

        if (!empty($carEditLog->zengpin)) {
            $view['zengpin']=unserialize($carEditLog->zengpin);
        }else{
            // 赠品或免费服务器
            $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']); 
        }
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
        return view('dealer.waitnotice',$view);
    }
    // 客户不接受修改订单，终止订单
    public function stop3($order_num)
    {
    	$view = [
    			'title' => '客户不接受修改订单，终止订单',
    			'order_num'=>$order_num,
    	];
    	
    	//终止订单，更新结算状态
    	$eff = HgCart::where("order_num",$order_num)->update(array('calc_status'=>1));
        return view('dealer.stop3');
    }
    // 已发交车通知
    public function PdiNoticeOK($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        // 判断订单状态是否在这步
        // if ($order['cart_sub_status']!=$_ENV['_CONF']['config']['hg_order']['order_jiaoche_sent_notify']) {
        //     exit('请在会员中心查看订单');
        // }
       $view = [
            'title' => '已发出交车通知',
            'order_num'=>$order_num,
        ];
        $view['order']=$order;
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
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
        // 交车剩余的天数
        $view['surplus_time']=diffBetweenTwoDays($order['jiaoche_time'],1);
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
        *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpaiarea']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();
        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();

        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        /*
        *保险价格计算
        */
        // 保险折扣
        $baoxian_discount=$bj['bj_baoxian_discount'];
        // 保险的类别
        $baoxian_type=getBaoxianType($order['buytype'],$bj['seat_num']);
        // 全部额度
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['ziran']=HgBaojiaBaoxianZiranPrice::getPrice($bj['bj_id'],$baoxian_type);
        // 不计免费率

        $view['bjm_sanzhe_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'sanzhe');
         $view['bjm_renyuan_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'renyuan');
         $view['bjm_huahen_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'huahen');

         // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);

        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
       // 代理选择 的交车日期
       $view['pdi_date_dealer']=explode(',', $view['order_attr']['pdi_date_dealer']);

       
       // 取得订单中客户需要提供是文件资料
       $view['orderfiles']=HgOrderFiles::getOrderFiles($order_num);
       // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        // 取得修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['bj']['body_color']=$editcar['body_color'];
            $view['bj']['interior_color']=$editcar['interior_color'];
            $view['bj']['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['bj']['bj_licheng']=$editcar['licheng'];
            $view['bj']['bj_jc_period']=$view['bj_jc_period']=$editcar['zhouqi'];
            
            // 排放
            $view['bj']['paifangTitle'] = $editcar['paifang'];
        }else{
            
            // 排放
            $view['bj']['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        }
        if (!empty($carEditLog->zengpin)) {
            $view['zengpin']=unserialize($carEditLog->zengpin);
        }else{
            // 赠品
            // $view['zengpin']=unserialize($view['order_attr']['zengpin']);
            $view['zengpin']=HgUserZengpin::getZengpin($order_num);
        }

        if (!empty($carEditLog->xzj)) {
            $view['xzj']=unserialize($carEditLog->xzj);
        }else{
            // 报价中已安装的选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=$xzjs->toArray();
        }

        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        
        $view['cart_log'] = $log;
        return view('dealer.pdinoticeok',$view);

    }
    // 客户已提交资料等待代理确认
    public function PdiConfirm($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        $view['title']='反馈客户提交的资料';
        $view['order_num']=$order_num;
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
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
        // 报价中已经安装的选装件
        // $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);

        // $view['xzj']=$xzjs->toArray();
        // 取得修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['bj']['body_color']=$editcar['body_color'];
            $view['bj']['interior_color']=$editcar['interior_color'];
            $view['bj']['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['bj']['bj_licheng']=$editcar['licheng'];
            $view['bj']['bj_jc_period']=$view['bj_jc_period']=$editcar['zhouqi'];
            
            // 排放
            $view['bj']['paifangTitle'] = $editcar['paifang'];
        }else{
            
            // 排放
            $view['bj']['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        }
        if (!empty($carEditLog->zengpin)) {
            $view['zengpin']=unserialize($carEditLog->zengpin);
        }else{
            // 赠品
            // $view['zengpin']=unserialize($view['order_attr']['zengpin']);
            $view['zengpin']=HgUserZengpin::getZengpin($order_num);
        }

        if (!empty($carEditLog->xzj)) {
            $view['xzj']=unserialize($carEditLog->xzj);
        }else{
            // 报价中已安装的选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=$xzjs->toArray();
        }

        // 用户选择的选装件

        $view['userxzj']=HgUserXzj::getAllInfo($order['order_num']);

        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();

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
        // 订单的属性
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        $view['order_attr']=$order_attr->toArray();
	    $view['ticheren'] =  !empty($order_attr['ticheren'])?unserialize($order_attr['ticheren']):array('username'=>'','mobile'=>'');
        // $view['zengpin']=array();
        $view['baoxian']=unserialize($order_attr->baoxian);

        // 取得订单中客户需要提供是文件资料
        
        $view['butiefile_self']=HgOrderFiles::getFile($order_num,4);
        $view['butiefile']=HgOrderFiles::getFile($order_num,4,0);
        $view['toubaofile_self']=HgOrderFiles::getFile($order_num,1);
        $view['toubaofile']=HgOrderFiles::getFile($order_num,1,0);
        $view['shangpaifile_self']=HgOrderFiles::getFile($order_num,2);
        $view['shangpaifile']=HgOrderFiles::getFile($order_num,2,0);
        $view['linpaifile_self']=HgOrderFiles::getFile($order_num,3);
        $view['linpaifile']=HgOrderFiles::getFile($order_num,3,0);

        // 保险
        $view['baoxian']=unserialize($view['order_attr']['baoxian']);
        // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        
        $view['cart_log'] = $log;
        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
        	$dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
        $view['dates']=$dates;
        // 代理选择 的交车日期
        $view['pdi_date_dealer']=explode(',', $view['order_attr']['pdi_date_dealer']);
        // 客户选择的日期
        $view['pdi_date_client']=explode(',', $view['order_attr']['pdi_date_client']);
        return view('dealer.pdiconfirm',$view);
    }
    // 保存确认客户提交的资料
    public function postPdiConfirm()
    {
   	//检查接受客户的日期 ，如果不接受则 对应的修改进行保存
    	$act_date = Request::Input("act_date");
        $inputData = array();
        if($act_date == "Y"){
        	$inputData['act_date'] = "Y";
        }elseif($act_date == "N"){
        	$inputData['act_date'] = "N";
        	$inputData['jc_date'] = Request::Input("jc_date");
        	$inputData['over_fee'] = Request::Input("over_fee");
        }
        //代驾服务 信息保存
        $daijia = Request::Input("daijia");
        $inputData['daijia'] = array();
        if(!empty($daijia)){
	        $inputData['daijia']=array(
						        		'choose'=>$daijia['choose'],
						        		'fee'=>$daijia['fee'],
						        		'pay_type'=>$daijia['pay_type']
						        		
						        );
        }
        //运输服务 信息保存
        $transport = Request::Input("transport");

        $inputData['transport'] = array();
        if(!empty($transport['choose'])){
        	$inputData['transport']=array(
        			'choose'=>$transport['choose'],
        			'fee'=>$transport['fee'],
        			'bx_fee'=>$transport['bx_fee'],
        			'pay_type'=>$transport['pay_type']
        
        	);
        }
        //$inputData['transport']=array('choose'=>,'fee'=>,'pay_type'=>);
        //保存 代驾和运输费用
        
        //追加保险的数据
        $bx = Request::Input("bx");
        $newBxData = array();
        if(!empty($bx)){   	
        	foreach($bx as $k => $v){
        		if(isset($v['choose']) && $v['choose'] == "Y"){
        			$newBxData[$k] = array('choose'=>'Y','bf'=>$v['bf'],'bfsm'=>$v['bfsm']);
        		}elseif(isset($v['choose']) && $v['choose'] == "N"){
        			$newBxData[$k]['choose'] = "N";
        		}
        		
        	}
        	
        }
        $inputData['baoxian'] = $newBxData;
        
        //客户特殊需求进行分解保存 默认都是存在一条数据，需要判断是否为空
        //可以办理的证明项目分解数据保存
        $project = Request::Input("project");
        $newPorjectOkData = array();

        foreach($project as $k=>$v){	
        	
        	if(!empty(trim($v['name']))){
        		$newPorjectOkData[]=array(
        									"name"=>$v['name'],
					        				"money"=>$v['money'],
					        				"day"=>$v['day'],
					        				'effect'=>$v['effect'],
					        				'jc_date'=>$v['jc_date']
					        				
					        		);
        	}
        }
        
        //不能办理的证明项目分解数据保存
        $project_not = Request::Input("project_not");
        $newPorjectNotData = array();
        foreach($project_not as $k=>$v){
        	if(!empty(trim($v))){
        		$newPorjectNotData[]=$v;
        	}
        }
        
        //可以办理的赠品或者服务项目分解数据保存
        $service_ok = Request::Input("service_ok");
        $newServiceOkData = array();
        foreach($service_ok as $k => $v){
        	if(!empty(trim($v))){
        		$newServiceOkData[]=$v;
        	}
        }
        
        //不可以办理的赠品或者服务项目分解数据保存
        $service_not = Request::Input("service_not");
        $newServiceNotData = array();
        foreach($service_not as $k => $v){
        	if(!empty(trim($v))){
        		$newServiceNotData[]=$v;
        	}
        }
        $inputData['project_ok']  =$newPorjectOkData;
        $inputData['project_not'] =$newPorjectNotData;
        $inputData['service_ok']  =$newServiceOkData;
        $inputData['service_not'] =$newServiceNotData;
        $inputData['needfile']=Request::Input('needfile');
        $data['pdi_reply_especial_request'] = serialize($inputData); 
        //更新入订单附加属性
        $affectedHgCartAttrRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($data);
        
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_jiaoche'],'cart_sub_status'=>404]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }

        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],403,"PidController/PdiConfirm","确认客户提交的预约资料",0,'售方再确认回复时间');
        return redirect(route('pdiwaitconfirm', ['order_num' =>Request::Input('order_num') ]));
    }
    // 等待客户再次确认
    public function PdiWaitConfirm($order_num)
    {
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    	
    		exit('订单不存在');
    	}
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view['order']=$order;
    	$view['title']='经销商已经对客户的需求进行反馈，等待客户选择确认';
    	$view['order_num']=$order_num;
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
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
    	// 取得修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['bj']['body_color']=$editcar['body_color'];
            $view['bj']['interior_color']=$editcar['interior_color'];
            $view['bj']['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['bj']['bj_licheng']=$editcar['licheng'];
            $view['bj']['bj_jc_period']=$view['bj_jc_period']=$editcar['zhouqi'];
            
            // 排放
            $view['bj']['paifangTitle'] = $editcar['paifang'];
        }else{
            
            // 排放
            $view['bj']['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        }
        if (!empty($carEditLog->zengpin)) {
            $view['zengpin']=unserialize($carEditLog->zengpin);
        }else{
            // 赠品
            // $view['zengpin']=unserialize($view['order_attr']['zengpin']);
            $view['zengpin']=HgUserZengpin::getZengpin($order_num);
        }

        if (!empty($carEditLog->xzj)) {
            $view['xzj']=unserialize($carEditLog->xzj);
        }else{
            // 报价中已安装的选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=$xzjs->toArray();
        }
    	// 用户选择的选装件
    	
    	$view['userxzj']=HgUserXzj::getAllInfo($order['order_num']);
    	
    	// 用户选择的原厂选装件精品
    	$view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
    	// 用户选择的非原厂选装件精品
    	$view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();
    	
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
    	// 订单的属性
    	$order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
    	$view['order_attr']=$order_attr->toArray();
    	    	 
    	//获取经销商对特殊要求的回复
    	$pdiReply = !empty($order_attr['pdi_reply_especial_request'])?unserialize($order_attr['pdi_reply_especial_request']):array();
    	$view['pdiReply'] = $pdiReply;
    	$view['ticheren'] =  !empty($order_attr['ticheren'])?unserialize($order_attr['ticheren']):array('username'=>'','mobile'=>'');
    	// $view['zengpin']=array();
    	
    	// 取得订单中客户需要提供是文件资料
        
        $view['butiefile_self']=HgOrderFiles::getFile($order_num,4);
        
        $view['toubaofile_self']=HgOrderFiles::getFile($order_num,1);
        
        $view['shangpaifile_self']=HgOrderFiles::getFile($order_num,2);
        
        $view['linpaifile_self']=HgOrderFiles::getFile($order_num,3);
       
        // 代理确认的客户提供的资料
        $confirmfile=unserialize($order_attr->pdi_reply_especial_request);
        $view['confirmfile']=$confirmfile['needfile'];

        // 保险
        $view['baoxian']=unserialize($view['order_attr']['baoxian']);
    	// 随车工具
    	$view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
    	// 随车文件
    	$view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
    	/**
    	 * 获取订单日志
    	*/
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	
    	$view['cart_log'] = $log;
    	
    	// 交车日期选择
    	$dates = array();
    	$jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
    	$jiaoche_m=date('m',strtotime($order['jiaoche_time']));
    	$jiaoche_d=date('d',strtotime($order['jiaoche_time']));
    	for ($i=14; $i >=0 ; $i--) {
    		$dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
    	}
    	$view['dates']=$dates;
    	// 代理选择 的交车日期
    	$view['pdi_date_dealer']=explode(',', $view['order_attr']['pdi_date_dealer']);
    	// 客户选择的日期
    	$view['pdi_date_client']=explode(',', $view['order_attr']['pdi_date_client']);
        return view('dealer.pdiwaitconfirm',$view);

    }

    // 反馈ok提交补充信息
    public function pdiok($order_num)
    {
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
       $view = [
            'title' => '提交补充信息',
            'order_num'=>$order_num,
        ];
        $view['order']=$order;
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);
        /*
         * 各项时间
         */

        // 交车剩余的天数
        $view['surplus_time']=diffBetweenTwoDays($order['jiaoche_time'],1);
        
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
        
        // 其他费用合计
        $total_op=0.00;
        foreach ($other_price as $key => $value) {
        	$total_op+=$value;
        }
        $view['total_op']=$total_op;
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
        *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpaiarea']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();
        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();

        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        /*
        *保险价格计算
        */
        // 保险折扣
        $baoxian_discount=$bj['bj_baoxian_discount'];
        // 保险的类别
        $baoxian_type=getBaoxianType($order['buytype'],$bj['seat_num']);
        // 全部额度
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($bj['bj_id'],$baoxian_type);
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($bj['bj_id'],$baoxian_type);
        $view['ziran']=HgBaojiaBaoxianZiranPrice::getPrice($bj['bj_id'],$baoxian_type);
        // 不计免费率

        $view['bjm_sanzhe_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'sanzhe');
         $view['bjm_renyuan_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'renyuan');
         $view['bjm_huahen_rate']=HgDealerBaoXianBuJiMian::getBuJiMianRate($baoxian_type,$bj['bj_bx_select'],'huahen');

         // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        // 订单属性
        $view['order_attr'] = $order_attr = HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        $view['ticheren'] = unserialize($view['order_attr']['ticheren']);
        $view['user_choose'] = unserialize($view['order_attr']['user_choose']);
        
        //获取经销商对特殊要求的回复
        $pdiReply = !empty($order_attr['pdi_reply_especial_request'])?unserialize($order_attr['pdi_reply_especial_request']):array();
        $view['pdiReply'] = $pdiReply;
        
        // 办理特殊文件费用
        $fl_price=0.00;
        $ziliao=unserialize($order_attr['wenjian']);
        if ($ziliao) {
        	foreach ($ziliao as $key => $value) {
        		$fl_price+=$value['fee'];
        	}
        }
        
        $view['fl_price']=$fl_price;
        
        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
       // 代理选择 的交车日期
       $view['pdi_date_dealer']=explode(',', $view['order_attr']['pdi_date_dealer']);

       
       // 取得订单中客户需要提供是文件资料
       $view['orderfiles']=HgOrderFiles::getOrderFiles($order_num);
       // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getSuiche($bj['more']['gongju']);

        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getSuiche($bj['more']['wenjian']);
        // 代理确认的客户提供的文件资料
        $confirmfile=unserialize($order_attr->pdi_reply_especial_request);
        $cateids=array();
        foreach ($confirmfile['needfile'] as $key => $value) {
            foreach (array_filter($value) as $k => $vv) {
                $cateids[]=$k;
            }
        }

        /*
         *按场合查看文件
         */
        $view['files1']=HgOrderFiles::getByCate($order_num,$cateids);
        // 按文件名查看
        $view['files2']=HgOrderFiles::getByName($order_num,$cateids);
        
        // 服务专员
        $view['zhuanyuan']=HgWaiter::getAllWaiter($_SESSION['member_id'],$order['dealer_id']);
        
        // 取得修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['bj']['body_color']=$editcar['body_color'];
            $view['bj']['interior_color']=$editcar['interior_color'];
            $view['bj']['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['bj']['bj_licheng']=$editcar['licheng'];
            $view['bj']['bj_jc_period']=$view['bj_jc_period']=$editcar['zhouqi'];
            
            // 排放
            $view['bj']['paifangTitle'] = $editcar['paifang'];
        }else{
            
            // 排放
            $view['bj']['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        }
        if (!empty($carEditLog->zengpin)) {
            $view['zengpin']=unserialize($carEditLog->zengpin);
        }else{
            // 赠品
            // $view['zengpin']=unserialize($view['order_attr']['zengpin']);
            $view['zengpin']=HgUserZengpin::getZengpin($order_num);
        }

        if (!empty($carEditLog->xzj)) {
            $view['xzj']=unserialize($carEditLog->xzj);
        }else{
            // 报价选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=$xzjs->toArray();
        }
        // 保险
        $view['baoxian']=unserialize($view['order_attr']['baoxian']);
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        
        $view['cart_log'] = $log;
        return view('dealer.pdiok',$view);
    }
    // 保存补充信息
    public function postPdiOk()
    {
	if(Request::Input('fuwuyuan') == 'none'){
        	die("请返回，选择一个服务专员让此订单继续下去");
        }
        $arrTmp = explode("_",Request::Input('fuwuyuan'));
        $arrZhuanYuan = array(
        		"name"=>$arrTmp[0],
        		"mobile"=>$arrTmp[1],
        		"tel"=>$arrTmp[2],
        );
        // 保存服务专员信息
        $map = array(
        		'waiter'=>serialize($arrZhuanYuan),
        		 
        ); 
        // 更新提交的数据
        $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($map);
        
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_jiaoche'],'cart_sub_status'=>409]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }

        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],405,"PidController/pdiok","提交补充信息",0,"代理提交补充信息时间");
        return redirect(route('pdiend', ['order_num' =>Request::Input('order_num') ]));
    }
    // 预约交车反馈ok
    public function PdiConfirmok($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view=[
            'order_num'=>$order_num,
            'title'=>'确认反馈',
        ];
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
        // 客户确认交车通知时间
        $view['yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);

        // 补充预约信息剩余时间
        $view['sy_time']=diffBetweenTwoDays(date('Y-m-d H:i:s',strtotime($view['yuyueok_time'])+24*3600),2);
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
        $view['order']=$order;
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
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        // 回程方式

        $view['trip_way']=unserialize($view['order_attr']->trip_way);

        // 约定的交车时间
        $view['jiaoche_date']=(unserialize($view['order_attr']['jc_date']));

        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();
        // 客户上牌地为本地还是异地
        $view['islocal']=HgCart::isLocal($order_num);

        // 赠品
       // $view['zengpin']=unserialize($view['order_attr']['zengpin']);
        $view['zengpin']=HgUserZengpin::getZengpin($order_num);
       // 取得订单中客户需要提供是文件资料
       $view['orderfiles']=HgOrderFiles::getOrderFiles($order_num);
        // 交车日期选择
        $dates = array();
        $jiaoche_y=date('Y',strtotime($order['jiaoche_time']));
        $jiaoche_m=date('m',strtotime($order['jiaoche_time']));
        $jiaoche_d=date('d',strtotime($order['jiaoche_time']));
        for ($i=14; $i >=0 ; $i--) {
            $dates[]=date('m-d',(mktime( 0,0,0,$jiaoche_m,$jiaoche_d-$i,$jiaoche_y)));
        }
       $view['dates']=$dates;
       // 代理选择 的交车日期
       $view['pdi_date_dealer']=explode(',', $view['order_attr']['pdi_date_dealer']);
       // 客户选择的日期
       $view['pdi_date_client']=explode(',', $view['order_attr']['pdi_date_client']);
       // 提车人信息
       $view['ticheren']=unserialize($view['order_attr']['ticheren']);
       // 取得上牌需要的文件，非本人
        $view['shangpai_file']=HgAgentFiles::getFiles($_SESSION['member_id'],2)->toArray();
        // 投保需要的文件，非本人
        $view['toubao_file']=HgAgentFiles::getFiles($_SESSION['member_id'],1)->toArray();
        // 上临牌需要 的文件，非本人
        $view['linpai_file']=HgAgentFiles::getFiles($_SESSION['member_id'],3)->toArray();
        // 国家节能补贴需要的文件，非本人
        $view['butie_file']=HgAgentFiles::getFiles($_SESSION['member_id'],4)->toArray();
        // 服务专员
        
        $view['zhuanyuan']=HgWaiter::getAllWaiter($_SESSION['member_id'],$order['dealer_id']);
        // 随车工具
        $view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
        $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        // 车身颜色，内饰颜色，排放标准，实际里程，出厂年月重新赋值
        // 取得订单修改的所有信息
        $cartattr=HgCartAttr::GetOrderAttr(array('cart_id'=>$order['id']));

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
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        
        $view['cart_log'] = $log;
        
        return view('dealer.pdiconfirmok',$view);
    }
    public function postSaveConfirm()
    {
        // 上牌人和提车人不一致需要提供的文件
        // 投保需要的文件
        $baoxian=Request::Input('baoxian');
        if ($baoxian) {
            $bx = array();
            foreach ($baoxian['k'] as $key => $value) {
                if($value){
                    HgOrderFiles::insert(['title'=>$value,'num'=>$baoxian['sl'][$key],'yj'=>$baoxian['yj'][$key],'cate'=>'toubao','isself'=>0,'order_num'=>Request::Input('order_num')]);
                }
            }
        }
        
        // 上牌需要的文件
        $shangpai=Request::Input('shangpai');
        if ($shangpai) {
            $sp = array();
            foreach ($shangpai['k'] as $key => $value) {
                if($value){
                    
                    HgOrderFiles::insert(['title'=>$value,'num'=>$shangpai['sl'][$key],'yj'=>$shangpai['yj'][$key],'cate'=>'shangpai','isself'=>0,'order_num'=>Request::Input('order_num')]);
                }
            }
        }
        
        // 上临牌需要的文件
        $linpai=Request::Input('linpai');
        if ($linpai) {
            $lp = array();
            foreach ($linpai['k'] as $key => $value) {
                if($value){

                    HgOrderFiles::insert(['title'=>$value,'num'=>$linpai['sl'][$key],'yj'=>$linpai['yj'][$key],'cate'=>'linpai','isself'=>0,'order_num'=>Request::Input('order_num')]);
                }
            }
        }
        
        // 领取国家节能补贴需要的文件
        $butie=Request::Input('butie');
        if ($butie) {
            $bt = array();
            foreach ($butie['k'] as $key => $value) {
                if($value){

                    HgOrderFiles::insert(['title'=>$value,'num'=>$butie['sl'][$key],'yj'=>$butie['yj'][$key],'cate'=>'butie','isself'=>0,'order_num'=>Request::Input('order_num')]);
                }
            }
        }
        $arrTmp = explode("_",Request::Input('fuwuyuan'));
        $arrZhuanYuan = array(
        		"name"=>$arrTmp[0],
        		"mobile"=>$arrTmp[1],
        		"tel"=>$arrTmp[2],
        );
        // 保存服务专员信息
        $map = array(
            'waiter'=>serialize($arrZhuanYuan),
           
            );
        // 更新提交的数据
        $affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($map);
        if (!$affectedRows) {
            dd('保存服务专员信息出错');
        }
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_jiaoche'],'cart_sub_status'=>$_ENV['_CONF']['config']['hg_order']['order_jiaoche_end']]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],405,"PdiController/PdiConfirmok","交车邀请反馈ok，提交补充信息",0,"代理提交补充信息时间");
        return redirect(route('pdiend', ['order_num' =>Request::Input('order_num') ]));

    }
    // 预约交车完毕
    public function PdiEnd($order_num)
    {
        // return 'C.3.1';
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();

        $view=[
            'title'=>'预约交车完毕',
            'order_num'=>$order_num,
            'order'=>$order,
        ];
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
        // 其他费用合计
        $total_op=0.00;
        foreach ($other_price as $key => $value) {
            $total_op+=$value;
        }
        $view['total_op']=$total_op;
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
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 用户选择的原厂选装件精品
        $view['ycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',1)->count();
        // 用户选择的非原厂选装件精品
        $view['fycxzj_count']=HgUserXzj::where('order_num','=',$order['order_num'])->where('is_yc','=',0)->count();

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

        // 订单的属性
        $order_attr=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        $view['order_attr']=$order_attr->toArray();
        $view['jc_date']=unserialize($order_attr['jc_date']);
        $view['ticheren']=unserialize($order_attr->ticheren);
        $view['zengpin']=unserialize($order_attr->zengpin);
        $view['trip_way']=unserialize($order_attr->trip_way);
        // 距离交车剩余时间
        $jiaochedate=substr($view['jc_date']['date'], 0,5);
        $view['sy_time']=diffBetweenTwoDays(date('Y-m-d H:i:s',strtotime(date('Y').'-'.$jiaochedate)),1);

        // 办理特殊文件费用
        $fl_price=0.00;
        $ziliao=unserialize($order_attr->wenjian);
        if ($ziliao) {
            foreach ($ziliao as $key => $value) {
                $fl_price+=$value['fee'];
            }
        }
        
        $view['fl_price']=$fl_price;
        // 随车工具
        $view['gongju']=empty($bj['more']['gongju'])?"":HgAnnex::getSuiche($bj['more']['gongju']);
        // 随车文件
        $view['wenjian']=empty($bj['more']['wenjian'])?"":HgAnnex::getSuiche($bj['more']['wenjian']);
        // 随车工具
        //$view['gongju'] = empty($bj['more']['gongju'])?"":HgAnnex::getTitle($bj['more']['gongju']);
        // 随车文件
       // $view['wenjian'] = empty($bj['more']['wenjian'])?"":HgAnnex::getTitle($bj['more']['wenjian']);
        //print_r($view['wenjian']);exit;
        
       /*
        *按场合查看文件
       */

       $view['files1']=HgOrderFiles::getByCate($order_num);
       // 按文件名查看
       
       $view['files2']=HgOrderFiles::getByName($order_num);
       // 服务专员
       $view['zhuanyuan']=HgWaiter::getAllWaiter($_SESSION['member_id'],$order['dealer_id']);
       $originZhuanyuanObj = HgCartAttr::select("waiter")->where('cart_id', '=', $id->id)->first();
       //echo serialize(array("name"=>"习近平","mobile"=>"18988888888","tel"=>"13888888888"));
       $view['orignZhuanyuan'] = unserialize($originZhuanyuanObj->waiter);
       
       /**
        * 获取订单日志
        */
       $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
       
       $view['cart_log'] = $log;

        return view('dealer.pdiend',$view);
    }
    // 保存服务专员信息跳转到提车
    public function saveZhuanyuan()
    {
    	$arrTmp = explode("_",Request::Input('fuwuyuan'));
    	$arrZhuanYuan = array(
    			"name"=>$arrTmp[0],
    			"mobile"=>$arrTmp[1],
    			"tel"=>$arrTmp[2],
    	);
    	if(Request::Input('originZhuanyuan') != $arrTmp[0]){//如果专员信息没有被修改 则不需要更新表 add by jerry
    		// 保存服务专员信息
    		$map = array(
    				'waiter'=>serialize($arrZhuanYuan),
    				 
    		);
    		// 更新提交的数据
    		$affectedRows = HgCartAttr::where('cart_id', '=', Request::Input('id'))->update($map);
    		if (!$affectedRows) {
    			dd('保存服务专员信息出错');
    		}
    		
    	}
    	
        
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>500]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],$_ENV['_CONF']['config']['hg_order']['order_jiaoche_end'],"PdiController/PdiEnd","预约完成",0,"代理预约完成时间");
        return redirect(route('dealer.ticheinfo', ['order_num' =>Request::Input('order_num') ]));
    }
    // 填写提车信息
    public function ticheInfo($order_num)
    {

        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        $view=[
            'order_num'=>$order_num,
            'title'=>'提车信息',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        // 如果有操作记录怎跳到提车完成页面
        // if (HgCartLog::get_dealer_tiche_time($order['id'])) {
        //     return redirect(route('dealer.ticheend', ['order_num' =>$order_num ]));
        // }
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
        // 客户反馈交车通知时间
        $view['get_yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
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
        // 其他费用合计
        $total_op=0.00;
        foreach ($other_price as $key => $value) {
            $total_op+=$value;
        }
        $view['total_op']=$total_op;
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
        *经销商代理信息 (卖家)
        */
        $view['seller']=HgSeller::getProxy($order['seller_id']);
        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        // 约定的交车时间
        $view['jc_date']=(unserialize($view['order_attr']['jc_date']));
        /*
        *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 提车人信息
       $view['ticheren']=unserialize($view['order_attr']->ticheren);
       $view['trip_way']=unserialize($view['order_attr']->trip_way);
       // 赠品或免费服务器
        $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        // 办理特殊文件费用
        $fl_price=0.00;
        $ziliao=unserialize($view['order_attr']->wenjian);
        if ($ziliao) {
            foreach ($ziliao as $key => $value) {
                $fl_price+=$value['fee'];
            }
        }
        
        $view['fl_price']=$fl_price;
         // 随车工具
        $view['gongju']=empty($bj['more']['gongju'])?"":HgAnnex::getSuiche($bj['more']['gongju']);
        // 随车文件
        $view['wenjian']=empty($bj['more']['wenjian'])?"":HgAnnex::getSuiche($bj['more']['wenjian']);
        
        /*
        *按场合查看文件
       */
       $view['files1']=HgOrderFiles::getByCate($order_num);
       // 按文件名查看
       $view['files2']=HgOrderFiles::getByName($order_num);
       // 服务专员
       $view['zhuanyuan']=unserialize($view['order_attr']->waiter);
       // 车辆的最终信息
       $user_carinfo=unserialize($view['order_attr']->user_carinfo);
       // 如果客户先提交则读取客户的数据，否则从订单中读取
       if ($user_carinfo['shangpai_area']) {
           $view['fin_car_info']=$user_carinfo;
           if ($order['shangpai']) {
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
            $view['fin_car_info']=$fin_car_info;
            
            if ($order['shangpai']) {
                   Session::put('next_status', 501);
               }else{
                    Session::put('next_status', 502);
               }
       }
       // 车牌号
            $chepai=Area::getPaizhao($view['order']['shangpai_area']);
            array_push ( $chepai ,  8 ,  8,8,8,8 );
            $view['chepai']=$chepai;
       // 各省名称id
       $view['sheng']=Area::getTopArea()->toArray();
		
       /**
        * 获取订单日志
        */
       $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        
       $view['cart_log'] = $log;
       return view('dealer.ticheinfo',$view);
    }
    // 保存车辆最终信息
    public function saveTicheInfo()
    {
    	//获取交车信息表中的数据
    	$jiaoche_map = array('order_num'=>Request::Input('order_num'));
		$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
		
        $jiaoche = array(
        		'pdi_vin' => Request::Input('vin'),
        		'pdi_engine_no' => Request::Input('engine_no'),
        		'pdi_shangpai_area' => Request::Input('sheng').' '.Request::Input('shi'),
        		'pdi_useway' => Request::Input('yongtu'),
        		'pdi_regname' => Request::Input('reg_name'),
        		'pdi_chepai'=>!empty(Request::Input('chepai'))?serialize(Request::Input('chepai')):'',
        		'pdi_butie_date' => Request::Input('fafang_butie'),
        );
        //交车 经销商需要提交的文件
        $file_arr = array('pdi_car_sure_file','pdi_car_validate_file','pdi_car_checkin_file');
        
        $files = Request::file();
        $destinationPath = config('app.uploaddir')."file";
        foreach($file_arr as $k=>$v){
        	$file = isset($files[$v])?$files[$v]:'';
        	if(!empty($file) && $file->isValid()){
        		$type= $file->getClientOriginalExtension();
        		$fileName = 'f'.date('YmdHms').mt_rand(1000,9999).".".$type;
        		$file->move($destinationPath, $fileName);
        		$jiaoche[$v] = "file/".$fileName;//文件存放包含文件夹
        		
        	}
        }
        if(count($jiaocheInfo)>0){
        	$jiaoche['pdi_date_update']=date('Y-m-d H:i:s');
        	HgCartJiaoche::where('order_num', '=', Request::Input('order_num'))->update($jiaoche);
        }else{
        	$jiaoche['order_num']=Request::Input('order_num');
        	$jiaoche['pdi_date_first']=date('Y-m-d H:i:s');
        	$jiaoche['pdi_date_update']=date('Y-m-d H:i:s');
        	$jiaoche['create_at']=date('Y-m-d H:i:s');
        	HgCartJiaoche::insert($jiaoche);
        }
       
        // 更新订单状态
        $affectedRows = HgCart::where('id', '=', Request::Input('id'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>Session::get('next_status')]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],$_ENV['_CONF']['config']['hg_order']['order_tiche_info_check'],"PdiController/ticheInfo","提交车辆最终信息",0,"代理提交车辆最终信息时间");
        if (Request::Input('shangpai')) {
            return redirect(route('dealer.ticheend', ['order_num' =>Request::Input('order_num') ]));
        }else{
            return redirect(route('dealer.ticheenduser', ['order_num' =>Request::Input('order_num') ]));
        }
        
    }
    // 提交争议
    public function zhengYi($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        // 如果页面不是从页面链接过来的则跳出
        // if(!in_array($order->cart_sub_status, ['409','500','509'])) dd('请按正常步骤进行');
        $view=[
            'order_num'=>$order_num,
            'title'=>'车辆信息提交完成等待平台审核',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        
        /*
         *报价信息
         */
        $bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
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
         *经销商代理信息 (卖家)
         */
        $view['seller']=HgSeller::getProxy($order['seller_id']);
        /*
         *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        // 约定的交车时间
        $view['jc_date']=(unserialize($view['order_attr']['jc_date']));
        /*
         *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
        	$userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        
        $view['question'] = Config::get('wenti.dealer');
        
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();     
        $view['cart_log'] = $log;
       return view('dealer.zhengyi',$view); 
    }
    // 保存争议
    public function saveZhengYi()
    {
    	$map = array(
    			'order_num' => Request::Input('order_num'),
    			'problem'=>serialize(Request::Input('question')),
    			'content'=>Request::Input('content'),
    			'member_id'=>$_SESSION['member_id'],
    	);
    	$dispute=HgDispute::insertGetId($map);
    	$files = Request::file('file');	
    	$destinationPath = config('app.uploaddir')."evidence";
    	if(count($files)>0){
    		$allFileName = array();
    		foreach($files as $K=>$file){
    			if(!empty($file) && $file->isValid()){
			    	$type= $file->getClientOriginalExtension();
			    	$fileName = 'p'.date('YmdHms').mt_rand(1000,9999).".".$type;
			    	$file->move($destinationPath, $fileName);
			    	$ev=HgEvidence::insert(['urls'=>$fileName,'member_id'=>$_SESSION['member_id'],'dispute_id'=>$dispute,'order_num'=>Request::Input('order_num')]);
			    	if(!$ev) dd('插入证据记录数据失败');
    			}		
    		}
		} 
		
        // 更新订单状态
        $affectedRows = HgCart::where('order_num', '=', Request::Input('order_num'))->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>506]);

        if (!$affectedRows) {
            dd('更新订单状态失败');
        }
        // 记录日志
        $log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],506,"PdiController/zhengYi","提交争议",0,"代理提交争议时间");

        return redirect(route('dealer.dispute', ['order_num' =>Request::Input('order_num') ]));
    }
    // 调查调解
    public function Dispute($order_num)
    {
    	
    	if(Request::Input("act") == "hejie"){//如果和解了告知华车
    		$dispute_id = Request::Input("dispute_id");
    		$content2 = empty(Request::Input("content2"))?"同意和解":Request::Input("content2");
    		HgDispute::where("id",$dispute_id)->update(array("dispute_hejie"=>$content2,'dispute_hejie_date'=>date('Y-m-d H:i:s')));
    		
    		return redirect(route('dealer.dispute', ['order_num' =>Request::Input('order_num') ]));
    	}elseif(Request::Input("act") == "tiaojie"){//是否接受平台调解
    		$itemid = Request::Input("itemid");
    		$member_id = $_SESSION['member_id'];
    		HgMediate::where("itemid",$itemid)->update(array("member_id"=>$member_id));
    		if(Request::Input("result")==0){
    			$log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],1007,"CartController@Defend","调解未成功，终止订单",0,"调解未成功终止订单时间");
    			$effect_cart = HgCart::where('order_num', '=', $order_num)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>1007]);
    			//更改调解状态 1为不接受 2为接受
    			$e = HgMediate::where(array('itemid'=>$itemid))->update(array('status'=>1));
    			return redirect(route('dealer.mediatefail', ['order_num' =>Request::Input('order_num') ]));
    		}else{
    			//更改调解状态 1为不接受 2为接受
    			$e = HgMediate::where(array('itemid'=>$itemid))->update(array('status'=>2));
    			$effect_cart = HgCart::where('order_num', '=', $order_num)->update(['cart_status' => $_ENV['_CONF']['config']['hg_order']['order_tiche'],'cart_sub_status'=>509]);
    			return redirect(route('dealer.mediateok', ['order_num' =>Request::Input('order_num') ]));
    		}
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
    		return redirect(route('dealer.dispute', ['order_num' =>Request::Input('order_num') ]));
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
    	
    	/*
    	 *报价信息
    	 */
    	$bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
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
    	 *经销商代理信息 (卖家)
    	 */
    	$view['seller']=HgSeller::getProxy($order['seller_id']);
    	/*
    	 *经销商信息
    	*/
    	$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
    	// 经销商归属地
    	$view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	$view['bj_agent_service_price']=$price->bj_agent_service_price;
    	// 取得系统金额
    	$view['sysprice']=HgMoney::getMoneyList();
    	// 订单属性
    	$view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
    	// 约定的交车时间
    	$view['jc_date']=(unserialize($view['order_attr']['jc_date']));
    	/*
    	 *客户信息
    	*/
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
    	$view['buyer']=HgUser::getMember($view['order']['buy_id']);
    	// 报价选装件
    	$xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
    	$view['xzj']=$xzjs->toArray();
    	// 用户选择的选装件
    	$userxzj = array();
    	if ($order['cart_sub_status']>=303) {
    		$userxzj=HgUserXzj::getAllInfo($order['order_num']);
    	}
    	$view['userxzj']=$userxzj;
    	
    	
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
    	}else{
    		$view['mediate'] = array();
    	}
    	
    	//print_r($view['mediate']);
    	
    	/**
    	 * 获取订单日志
    	 */
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	$view['cart_log'] = $log;
    	
	 	return view('dealer.dispute',$view);
    }
    // 客户提交了争议，经销商申辩
    public function Defend($order_num)
    {
    	if(Request::Input("act") == "defendfirst"){
    		$map = array("content"=>request::Input("content"),
    				"member_id"=>$_SESSION['member_id'],
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
    					$ev=HgEvidence::insert(['urls'=>$fileName,'member_id'=>$_SESSION['member_id'],'dispute_id'=>request::Input("dispute_id"),'defend_id'=>$defendId,'order_num'=>Request::Input('order_num')]);
    					if(!$ev) dd('插入证据记录数据失败');
    				}
    			}
    			 
    		}
    		// 记录日志
    		$log_id=HgCartLog::putLog(Request::Input('id'),$_SESSION['member_id'],506,"PdiController@Defend","经销商进行申辩",0,"经销商进行申辩时间");
    		 
    		return redirect(route('dealer.defend', ['order_num' =>Request::Input('order_num') ]));
    		 
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
    		return redirect(route('dealer.defend', ['order_num' =>Request::Input('order_num') ]));
    	}elseif(Request::Input("act") == "hejie"){//如果和解了告知华车
    		$defend_id = Request::Input("defend_id");
    		$content2 = empty(Request::Input("content2"))?"同意和解":Request::Input("content2");
    		HgDefend::where("id",$defend_id)->update(array("defend_hejie"=>$content2,'defend_hejie_date'=>date('Y-m-d H:i:s')));
    		
    		return redirect(route('dealer.defend', ['order_num' =>Request::Input('order_num') ]));
    	}
        
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){
            exit('订单不存在');
        }
        $view=[
            'order_num'=>$order_num,
            'title'=>'客户提交了争议，经销商申辩',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        /*
         *报价信息
         */
        $bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
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
         *经销商代理信息 (卖家)
         */
        $view['seller']=HgSeller::getProxy($order['seller_id']);
        /*
         *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        // 约定的交车时间
        $view['jc_date']=(unserialize($view['order_attr']['jc_date']));
        /*
         *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
        $view['xzj']=$xzjs->toArray();
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
        	$userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        
        // 读取争议
        $dispute=HgDispute::getDispute($order_num,$id->buy_id);
        $evidence = HgEvidence::getEvidence($order_num,$dispute->id);
        $view['evidence'] = !empty($evidence)?$evidence->toArray():array();
        // 是否已经申辩过
        $defend=HgDefend::getDefend($order_num,$dispute->id);
        if ($defend) {
            $view['defend_content']=$defend['content'];
            $view['is_defend']=1;
            // 提交的证据
            $view['zhengju']=HgEvidence::getEvidence($order_num,$dispute->id,$defend->id)->toArray();
            $view['defend'] = $defend->toArray();
        }else{
            $view['is_defend']=0;
            $view['zhengju'] = array();
            $view['defend'] = array();
        }

        // 保存提交的申辩数据
        if ($view['is_defend']==0 && Request::Input('submit')) 
        {
            $map = array(
                'order_num' => Request::Input('order_num'), 
                'dispute_id'=>Request::Input('dispute_id'),
                'content'=>serialize(Request::Input('content')),
                'member_id'=>$_SESSION['member_id'],
            );
            $defendid=HgDefend::insertGetId($map);
            if(!$defendid) dd('插入申辩记录数据失败');

            // 证据
            if (Request::file('zhengju')) 
            {
                foreach (Request::file('zhengju') as $key => $value) {
                    if ($value->isValid())
                    {
                        $entension = $value-> getClientOriginalExtension(); 
                        $fileName='f'.date('YmdHms').mt_rand(1000,9999).'.'.$entension;
                        $value->move(config('app.uploaddir').'evidence', $fileName);
                        $ev=HgEvidence::insert(['urls'=>$fileName,'member_id'=>$_SESSION['member_id'],'defend_id'=>$defendid,'order_num'=>Request::Input('order_num'),'dispute_id'=>Request::Input('dispute_id')]);
                        if(!$ev) dd('插入申辩记录数据失败');
                    }
                    
                }
            }
            $view['is_defend']=1;
        }
        // -------------------

        // 争议问题
        $view['problem']=unserialize($dispute->problem);
        $view['content']=$dispute->content;
        $view['dispute_id']=$dispute->id;
        
        $mediate = HgMediate::getMediate($order_num,$view['dispute_id']);
        if(!empty($mediate)){//判断是否存在平台和解
        	$view['mediate'] = $mediate->toArray();
        }else{
        	$view['mediate'] = array();
        }
        
        
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        $view['cart_log'] = $log;
        return view('dealer.defend',$view);
    }
    // 提车完成经销商上牌
    public function ticheend($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        $view=[
            'order_num'=>$order_num,
            'title'=>'车辆信息提交完成等待平台审核',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        
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
        // 客户反馈交车通知时间
        $view['get_yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
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
        // 其他费用合计
        $total_op=0.00;
        foreach ($other_price as $key => $value) {
            $total_op+=$value;
        }
        $view['total_op']=$total_op;
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
        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);
        $view['bj']=$bj;
        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']])->toArray();
        // 约定的交车时间
        $view['jc_date']=(unserialize($view['order_attr']['jc_date']));
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
        
        //获取交车信息表中的数据
        $jiaoche_map = array('order_num'=>$order_num);
        $jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
        
        if(!empty($jiaocheInfo)){
        	$jiaocheInfo = $jiaocheInfo->toArray();
        }else{
        	$jiaocheInfo = array();
        }
        $view['jiaoche'] = $jiaocheInfo;
        $map = array('order_num'=>$order_num,'member_type'=>2);
        $jiaocheExt = HgCartJiaocheExt::getJiaocheExtInfoList($map);
        if(!empty($jiaocheExt)){
        	$view['jiaocheExt'] = $jiaocheExt->toArray();
        }else{
        	$view['jiaocheExt'] = array();
        }
        //print_r($view['jiaocheExt']);
        // 车辆的最终信息
       //$view['car']=unserialize($view['order_attr']->pdi_carinfo);
       /**
        * 获取订单日志
        */
       $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
       $view['cart_log'] = $log;
        
        return view('dealer.ticheend',$view);
    }
    // 提车完成客户上牌
    public function ticheEndUser($order_num)
    {
        // 检测订单是否存在
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        $view=[
            'order_num'=>$order_num,
            'title'=>'车辆信息提交完成等待平台审核',
        ];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view['order']=$order;
        
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
        // 客户反馈交车通知时间
        $view['get_yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
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
        // 合并该报价对应车型的具体参数
        $bj = array_merge($bj, $carmodelInfo);
        $view['bj']=$bj;
        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']])->toArray();
        // 约定的交车时间
        $view['jc_date']=(unserialize($view['order_attr']['jc_date']));
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
        // 代理提交的车辆的最终信息
        //$view['car']=unserialize($view['order_attr']->pdi_carinfo);
        // 客户提交的车辆的最终信息
       // $view['car_user']=unserialize($view['order_attr']->user_carinfo);
        
		
        //获取交车信息表中的数据
        $jiaoche_map = array('order_num'=>$order_num);
        $jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
        
        if(!empty($jiaocheInfo)){
        	$jiaocheInfo = $jiaocheInfo->toArray();
        }else{
        	$jiaocheInfo = array();
        }
        $view['jiaoche'] = $jiaocheInfo;
        
        if(empty($jiaocheInfo['user_chepai'])){
        	// 车牌号
        	$chepai=Area::getPaizhao($view['order']['shangpai_area']);
        	array_push ( $chepai ,  1 ,  1,1,1,1 );
        	$view['chepai']=$chepai;
        }else{
        	$view['chepai']=unserialize($jiaocheInfo['user_chepai']);
        	 
        }
        
        // 客户预计最晚上牌时间，超期时间
        $view['chaoshi']=date('Y-m-d',strtotime('+3 day',strtotime($view['jiaoche']['user_shangpai_time'])));
	
        // 各省名称id
        $view['sheng']=Area::getTopArea()->toArray();
        // 修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['bj']['body_color']=$editcar['body_color'];
            $view['bj']['interior_color']=$editcar['interior_color'];
        }
        $calc_file = HgSeller::where('member_id','=',$_SESSION['member_id'])->value('wenjian');
        
        $view['calc_file'] = $calc_file;
        /**
         * 获取订单日志
         */
        $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
        
        $view['cart_log'] = $log;
        
        $condition['seller_id'] = $_SESSION['member_id'];
        $condition['pdi_calc_status'] = array(1,2);
        //计算已经申请结算的订单数 即使用的文件数
        $view['calc_file_used'] =DB::table('hg_cart')
						        ->where('seller_id',$condition['seller_id'])
						        ->whereIn('pdi_calc_status',$condition['pdi_calc_status'])
						        ->count();
        
        return view('dealer.ticheenduser',$view);
    }
    
    public function agreeCalc(){
    	$order_num = Request::Input('order_num');
    	
    	$data = array(
    			'pdi_calc_date'=>date("Y-m-d H:i:s"),
    			'pdi_calc_status'=>1,
    			'order_num'=>$order_num,
    			'seller_id'=>$_SESSION['member_id'],
    			'member_name'=>$_SESSION['member_name'],
    			'member_id'=>$_SESSION['member_id'],
    	);
    	/**
    	$effect = HgCart::where('order_num','=',$order_num)->update($updata);
    	if(!$effect){
    		$data['error_code'] = 1;
    		$data['error_msg'] = '数据更新失败';
    	}else{
    		$data['error_code'] = 0;
    	}
    	echo json_encode($data);
    	**/
    	DB::transaction(function() use ($data){
    		try {
    			//结算文件库存减一
    			$e1 = DB::table('seller')->where(array('member_id'=>$data['seller_id']))->decrement('wenjian', 1);
    			if(!$e1){
    				throw new Exception('数据更新失败1');
    			}
    			//更新订单 经销商结算状态
    			$updata1 =array(
    					'pdi_calc_date'=>$data['pdi_calc_date'],
    					'pdi_calc_status'=>$data['pdi_calc_status'],
    					'end_pdi_status' =>601,//同意结算
    					);
    			$e2 =  DB::table('hg_cart')->where(array('order_num'=>$data['order_num']))->update($updata1);
    			if(!$e2){
    				throw new Exception('数据更新失败2');
    			}
    			$sellerInfo = HgSeller::getProxy($data['member_id'])->toArray();
    			//记录结算文件日志
    			$addLog =array(
						'op_name'=>$data['member_name'],
						'op_id'=>$data['member_id'],
						'num'=>'-1',
						'date'=>date("Y-m-d H:i:s"),
						'seller_id'=>$data['member_id'],
						'current_file_num'=>$sellerInfo['wenjian'],
						'note'=>'订单'.$data['order_num'].'同意结算',
				);
    			$e3 =  DB::table('hg_dealer_calc_file_log')->insert($addLog);
    			if(!$e3){
    				throw new Exception('数据更新失败3');
    			}
    		}catch (Exception $e) {
    			$data = array();
    			$data['error_code'] = 1;
    			$data['error_msg'] = '数据更新失败';
    			echo json_encode($data);
    			exit;
    		}
    		
    	});
    	$data = array();
    	$data['error_code'] = 0;
    	echo json_encode($data);
    	exit;
    }
    // 调解成功
    public function MediateOk($order_num)
    {
        // 检测订单是否存在
        $order=HgCart::GetOrderStatus($order_num);
        if(!isset($order->id)){
            exit('订单不存在');
        }
        $view=[
            'order_num'=>$order_num,
            'title'=>'提车信息',
            'id'=>$order->id,
            'order'=>$order,
        ];
        
        /*
        *报价信息
        */
        $bj=HgBaojia::getBaojiaInfo($order->bj_id)->toArray();
        // 保险公司名称
        $view['baoxianname']=HgBaoXian::getName($bj['bj_bx_select']);
        // 品牌，车型
        $view['brand']=explode('&gt;',$bj['gc_name']);
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
        // 其他费用合计
        $total_op=0.00;
        foreach ($other_price as $key => $value) {
            $total_op+=$value;
        }
        $view['total_op']=$total_op;
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
        *经销商代理信息 (卖家)
        */
        $view['seller']=HgSeller::getProxy($order['seller_id']);
        /*
        *经销商信息
        */
        $view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
        // 经销商归属地
        $view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
        // 担保金
        $price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
        $guarantee=$price->bj_car_guarantee;
        $view['guarantee']=$guarantee;
        $view['bj_agent_service_price']=$price->bj_agent_service_price;
        // 取得系统金额
        $view['sysprice']=HgMoney::getMoneyList();
        // 订单属性
        $view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
        // 约定的交车时间
        $view['jc_date']=(unserialize($view['order_attr']['jc_date']));
        /*
        *客户信息
        */
        // 客户承诺上牌地区
        $view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
        $view['buyer']=HgUser::getMember($view['order']['buy_id']);
        
        // 用户选择的选装件
        $userxzj = array();
        if ($order['cart_sub_status']>=303) {
            $userxzj=HgUserXzj::getAllInfo($order['order_num']);
        }
        $view['userxzj']=$userxzj;
        // 取得修改过的车辆信息
        $carEditLog = HgEditInfo::getEditInfo($order_num);
        if (!empty($carEditLog->carinfo)) {
            $editcar=unserialize($carEditLog->carinfo);
            $view['bj']['body_color']=$editcar['body_color'];
            $view['bj']['interior_color']=$editcar['interior_color'];
            $view['bj']['barnd_info']['chuchang_time']=$editcar['chuchang'];
            $view['bj']['bj_licheng']=$editcar['licheng'];
            $view['bj']['bj_jc_period']=$view['bj_jc_period']=$editcar['zhouqi'];
            
            // 排放
            $view['bj']['paifangTitle'] = $editcar['paifang'];
        }else{
            
            // 排放
            $view['bj']['paifangTitle'] = $carFields['paifang'][$bj['paifang']];
        }
        // 修改过的选装件
        if (!empty($carEditLog->carinfo)) {
            $view['xzj']=unserialize($carEditLog->xzj);
        }else{
            // 报价选装件
            $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
            $view['xzj']=$xzjs?$xzjs->toArray():array();
        }
        // 赠品或免费服务器
        if (!empty($carEditLog->zengpin)) {
            $view['zengpin']=unserialize($carEditLog->zengpin);
        }else{
            $view['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($order['bj_id']);
        }
        // 提车人信息
       $view['ticheren']=unserialize($view['order_attr']->ticheren);
       $view['trip_way']=unserialize($view['order_attr']->trip_way);
	// 保险
       $view['baoxian']=unserialize($view['order_attr']->baoxian);

        // 办理特殊文件费用
        $fl_price=0.00;
        $ziliao=unserialize($view['order_attr']->wenjian);
        if ($ziliao) {
            foreach ($ziliao as $key => $value) {
                $fl_price+=$value['fee'];
            }
        }
        
        $view['fl_price']=$fl_price;
         // 随车工具
        $view['gongju']=empty($bj['more']['gongju'])?"":HgAnnex::getSuiche($bj['more']['gongju']);
        // 随车文件
        $view['wenjian']=empty($bj['more']['wenjian'])?"":HgAnnex::getSuiche($bj['more']['wenjian']);
        /*
        *按场合查看文件
       */
       $view['files1']=HgOrderFiles::getByCate($order_num);
       // 按文件名查看
       $view['files2']=HgOrderFiles::getByName($order_num);
       // 服务专员
       $view['zhuanyuan']=unserialize($view['order_attr']->waiter);
       // 车辆的最终信息
       $user_carinfo=unserialize($view['order_attr']->user_carinfo);
       // 如果客户先提交则读取客户的数据，否则从订单中读取
       if ($user_carinfo['shangpai_area']) {
           $view['fin_car_info']=$user_carinfo;
           if ($order['shangpai']) {
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
            $view['fin_car_info']=$fin_car_info;
            
            if ($order['shangpai']) {
                   Session::put('next_status', 501);
               }else{
                    Session::put('next_status', 502);
               }
       }
       // 车牌号
            $chepai=Area::getPaizhao($view['order']['shangpai_area']);
            array_push ( $chepai ,  8 ,  8,8,8,8 );
            $view['chepai']=$chepai;
       // 各省名称id
       $view['sheng']=Area::getTopArea()->toArray();
       /**
        * 获取订单日志
        */
       $log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
       $view['cart_log'] = $log;
       
        return view('dealer.mediateok',$view);
    }
    // 调解失败
    public function mediateFail($order_num)
    {	
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    	
    		exit('订单不存在');
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'title'=>'提车信息',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view=[
    			'order_num'=>$order_num,
    			'id'=>$order['id'],
    			'order'=>$order,
    	];
    	
    	/*
    	 *报价信息
    	 */
    	$bj=HgBaojia::getBaojiaInfo($order['bj_id'])->toArray();
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
    	 *经销商代理信息 (卖家)
    	 */
    	$view['seller']=HgSeller::getProxy($order['seller_id']);
    	/*
    	 *经销商信息
    	*/
    	$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
    	// 经销商归属地
    	$view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	$view['bj_agent_service_price']=$price->bj_agent_service_price;
    	// 取得系统金额
    	$view['sysprice']=HgMoney::getMoneyList();
    	// 订单属性
    	$view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
    	// 约定的交车时间
    	$view['jc_date']=(unserialize($view['order_attr']['jc_date']));
    	/*
    	 *客户信息
    	*/
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);
    	$view['buyer']=HgUser::getMember($view['order']['buy_id']);
    	// 报价选装件
    	$xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);
    	$view['xzj']=$xzjs->toArray();
    	// 用户选择的选装件
    	$userxzj = array();
    	if ($order['cart_sub_status']>=303) {
    		$userxzj=HgUserXzj::getAllInfo($order['order_num']);
    	}
    	$view['userxzj']=$userxzj;
    	
    	$log = HgCartLog::where("cart_id",$order['id'])->where("cart_status",1007)->first();
    	if(!empty($log) && isset($log->user_id) ){
    		if($log->user_id == $_SESSION['member_id']){
    			$disputeType = "dealer";
    		}else{
    			$disputeType = "user";
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
    	//print_r($order);exit;
    	$view['deposit']= $this->order->getDepositMoney($order['bj_id'], false);
    	
    	$calc_file = HgSeller::where('member_id','=',$_SESSION['member_id'])->value('wenjian');
    	
    	$view['calc_file'] = $calc_file;
    	
    	$view['disputeType'] = $disputeType;
    	/**
    	 * 获取订单日志
    	 */
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	$view['cart_log'] = $log;
    	
        return view('dealer.mediatefail',$view);
    }
    //客户超时，由售方反向添加上牌地区，上牌号码，车辆用途，补贴发放，车主名称 
    public  function pdiSaveCarAttrInfo(){
    	$order_num = Request::Input("order_num");
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    	
    		exit('订单不存在');
    	}
    	
    	$jiaoche = array(
    			'pdi_shangpai_area'=>Request::Input("sheng")." ".Request::Input("shi"),
    			'pdi_useway'=>Request::Input("yongtu"),
    			'pdi_regname'=>Request::Input("reg_name"),
    			'pdi_chepai'=>!empty(Request::Input('chepai'))?serialize(Request::Input('chepai')):'',
    			'pdi_butie_date'=>Request::Input("fafang_butie"),
    			'pdi_date_update'=>date('Y-m-d H:i:s'),
    	);
    	//获取交车信息表中的数据
    	$jiaoche_map = array('order_num'=>$order_num);
    	$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
    	
    	if(empty($jiaocheInfo['pdi_date_first'])){
    		$jiaoche['pdi_date_first'] = date('Y-m-d H:i:s');
    	}
    	
    	$effect = HgCartJiaoche::where("order_num",$order_num)->update($jiaoche);
    	if(!$effect){
    		die("数据更新失败");
    	}else{
    		return redirect(route('dealer.ticheenduser', ['order_num' =>Request::Input('order_num') ]));
    		
    	}
    }
    
    // 交车后记核对金额
    public function jchjCheckMoney($order_num)
    {
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    
    		exit('订单不存在');
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'title'=>'核对结算金额页面',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view['order']=$order;
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
    	// 客户反馈交车通知时间
    	$view['get_yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
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
    	$carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);
    	$view['carmodelInfo']=$carmodelInfo;
    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
    	$view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
    	$view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];    	
    	// 经销商信息
    	$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
    	$view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
    	//获取客户信息
    	$view['buyer']=HgUser::getMember($order['buy_id']);
    	$view['bj'] = $bj;
    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	$view['bj_agent_service_price']=$price->bj_agent_service_price;
    	// 订单属性
    	$view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
    	
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);

    	// 车辆的最终信息
    	//$view['car']=unserialize($view['order_attr']->pdi_carinfo);
    	//print_r($view);exit;
    	//获取交车信息表中的数据
    	$jiaoche_map = array('order_num'=>$order_num);
    	$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
    	 
    	if(!empty($jiaocheInfo)){
    		$jiaocheInfo = $jiaocheInfo->toArray();
    	}else{
    		$jiaocheInfo = array();
    	}
    	$view['jiaoche'] = $jiaocheInfo;
    	$calc_file = HgSeller::where('member_id','=',$_SESSION['member_id'])->value('wenjian');
    	
    	$view['calc_file'] = $calc_file;
    	
    	
    	/**
    	 * 获取订单日志
    	 */
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	
    	$view['cart_log'] = $log;
    	
    	return view('dealer.jchjcheckmoney',$view);
    }
    // 交车后记办理手续
    public function jchjHandleProcedures($order_num)
    {
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    
    		exit('订单不存在');
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'title'=>'核对结算金额页面',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view['order']=$order;
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
    	// 客户反馈交车通知时间
    	$view['get_yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
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
    	$carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);
    	$view['carmodelInfo']=$carmodelInfo;
    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
    	$view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
    	$view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];    	
    	// 经销商信息
    	$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
    	$view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
    	//获取客户信息
    	$view['buyer']=HgUser::getMember($order['buy_id']);
    	$view['bj'] = $bj;
    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	$view['bj_agent_service_price']=$price->bj_agent_service_price;
    	// 订单属性
    	$view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
    	
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);

    	// 车辆的最终信息
    	//$view['car']=unserialize($view['order_attr']->pdi_carinfo);
    	//获取交车信息表中的数据
    	$jiaoche_map = array('order_num'=>$order_num);
    	$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
    	
    	if(!empty($jiaocheInfo)){
    		$jiaocheInfo = $jiaocheInfo->toArray();
    	}else{
    		$jiaocheInfo = array();
    	}
    	$view['jiaoche'] = $jiaocheInfo;
    	
    	$calc_file = HgSeller::where('member_id','=',$_SESSION['member_id'])->value('wenjian');
    	 
    	$view['calc_file'] = $calc_file;
    	$condition['seller_id'] = $_SESSION['member_id'];
    	$condition['pdi_calc_status'] = array(1,2);
    	//计算已经申请结算的订单数 即使用的文件数
    	$view['calc_file_used'] =DB::table('hg_cart')
							    	->where('seller_id',$condition['seller_id'])
							    	->whereIn('pdi_calc_status',$condition['pdi_calc_status'])
							    	->count();
    	
    	/**
    	 * 获取订单日志
    	 */
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	
    	$view['cart_log'] = $log;
    	return view('dealer.jchjhandleprocedures',$view);
    	 
    }
    // 交车后记 结算完毕
    public function jchjEnd($order_num)
    {
    	// 检测订单是否存在
    	$id=HgCart::GetOrderStatus($order_num);
    	if(!isset($id->id)){
    
    		exit('订单不存在');
    	}
    	$view=[
    			'order_num'=>$order_num,
    			'title'=>'核对结算金额页面',
    	];
    	// 取得订单基本信息
    	$order=HgCart::GetOrderByUser($order_num)->toArray();
    	$view['order']=$order;
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
    	// 客户反馈交车通知时间
    	$view['get_yuyueok_time']=HgCartLog::get_yuyueok_time($order['id']);
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
    	$carmodelInfo = HgCarInfo::getCarmodelFields($order['car_id']);
    	$view['carmodelInfo']=$carmodelInfo;
    	$bjCarInfo = HgBaojiaField::getCarFieldsByBjid($order['bj_id']);
    	$view['body_color']=$carmodelInfo['body_color'][$bjCarInfo['body_color']];
    	$view['interior_color']=$carmodelInfo['interior_color'][$bjCarInfo['interior_color']];    	
    	// 经销商信息
    	$view['jxs']=HgDealer::getDealerInfo($order['dealer_id'])->toArray();
    	$view['jxs']['d_shi']=Area::getAreaName($view['jxs']['d_shi']);
    	//获取客户信息
    	$view['buyer']=HgUser::getMember($order['buy_id']);
    	$view['bj'] = $bj;
    	// 担保金
    	$price = HgBaojiaPrice::getBaojiaPrice($order['bj_id']);
    	$guarantee=$price->bj_car_guarantee;
    	$view['guarantee']=$guarantee;
    	$view['bj_agent_service_price']=$price->bj_agent_service_price;
    	// 订单属性
    	$view['order_attr']=HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
    	
    	// 客户承诺上牌地区
    	$view['shangpai_area']=Area::getAreaName($view['order']['shangpai_area']);

    	// 车辆的最终信息
    	//$view['car']=unserialize($view['order_attr']->pdi_carinfo);
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
    	$log = HgCartLog::getDealerLogByStatus($order['id'],$order['cart_sub_status'])->toArray();
    	 
    	$view['cart_log'] = $log;
    	
    	return view('dealer.jchjend',$view);
    	 
    }
    
    public function ajaxAction($order_num,$type){
    	if($type == 'surebutie'){
    		//获取交车信息表中的数据
    		$jiaoche_map = array('order_num'=>$order_num);
    		$jiaocheInfo = HgCartJiaoche::getJiaocheInfo($jiaoche_map);
    		
    		$destinationPath = config('app.uploaddir')."file";
	    	$file = Request::file('pdi_butie_file');
	    	if(!empty($file) && $file->isValid()){
	    		$type= $file->getClientOriginalExtension();
	    		$fileName = 'b'.date('YmdHms').mt_rand(1000,9999).".".$type;
	    		$file->move($destinationPath, $fileName);
	    		$jiaoche['pdi_butie_fafang']=date('Y-m-d H:i:s');
	    		$jiaoche['pdi_butie_file']='file/'.$fileName;
	    		
	    		if(count($jiaocheInfo)>0){
	    			$jiaoche['user_date_update']=date('Y-m-d H:i:s');
	    			$e = HgCartJiaoche::where('order_num', '=', Request::Input('order_num'))->update($jiaoche);
	    		}else{
	    			$jiaoche['order_num']=Request::Input('order_num');
	    			$jiaoche['pdi_date_first']=date('Y-m-d H:i:s');
	    			$jiaoche['pdi_date_update']=date('Y-m-d H:i:s');
	    			$jiaoche['create_at']=date('Y-m-d H:i:s');
	    			$e = HgCartJiaoche::insert($jiaoche);
	    		}
	    		
	    		if(!$e){
	    			echo '1';
	    		}else{
	    			echo '0';
	    		}
	    	}else{
	    		echo '1';
	    		
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
