<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Session;
use Request;
use Input;
use App\Models\HgCart;
use App\Models\HgCartLog;
use App\Models\HgCartAttr;
use App\Models\HgBaoJia;
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
use App\Models\HgDealerBaoXian;
use App\Models\HgDealerBaoXianBuJiMian;


class ShowOrderController extends Controller {
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * 查看订单详情
     */
    public function showOrder($order_num)
    {
        $id=HgCart::GetOrderStatus($order_num);
        if(!isset($id->id)){

            exit('订单不存在');
        }
        $view=['title'=>'订单详情'];
        // 取得订单基本信息
        $order=HgCart::GetOrderByUser($order_num)->toArray();
        $view=array_merge($view,$order);
        // 取得诚意金进入时间
        $earnest_time=HgCartLog::get_earnest_time($order['id']);
        $view['earnest_time']=$earnest_time?$earnest_time:'';
        // 品牌
        $view['brand']=explode('&gt;',$order['car_name']);

        // 取得报价单信息
        $baojia=HgBaoJia::getBaojiaInfo($order['bj_id'])->toArray();
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
        // 报价选装件
        $xzjs=HgBaojiaXzj::getBaojiaXzj($order['bj_id']);

        $view['xzj']=$xzjs->toArray();
        // 保险公司名称
        if ($baojia['bj_bx_select']) {
            $baoxian=HgBaoXian::getName($baojia['bj_bx_select']);
        }

        $view['baoxian_name']=$baoxian['bx_title'];
        // 车损险
        $view['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($order['bj_id']);
        // 盗抢险
        $view['daoqiang']=HgBaojiaBaoxianDaoqiangPrice::getPrice($order['bj_id']);
        // 第三责任险
        $view['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getAllPrice($order['bj_id']);
        // 车上人员险
        $view['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getAllPrice($order['bj_id']);
        // 玻璃险
        $view['boli'] =HgBaojiaBaoxianBoliPrice::getAllPrice($order['bj_id']);
        // 划痕险
        $view['huahen'] =HgBaojiaBaoxianHuahenPrice::getAllPrice($order['bj_id']);
        // 自燃险
        // $view['ziran'] =HgBaojiaBaoxianZiranPrice::getPrice($order['bj_id']);
        // 取得代理的信息
        $view['dealer']=HgDealer::getDealerInfo($order['dealer_id']);
        /*报价扩展信息包括其他费用*/
        $view['more']=HgBaojiaMore::getBaojiaMove($order['bj_id']);
        // 上牌所需资料
        if(isset($view['more']['shangpai'])){
            $shangpai_ziliao=HgBaojiaMore::getShangPaiData($order['bj_id'],$order['buytype']);
            
        }
        $view['shangpai_ziliao']=isset($shangpai_ziliao)?$shangpai_ziliao:'';
        // 上临牌所需资料

        if(isset($view['more']['linpai'])){
            $linpai_ziliao=HgBaojiaMore::getLinPaiData($order['bj_id'],$order['buytype']);
            
        }
        $view['linpai_ziliao']=isset($linpai_ziliao)?$linpai_ziliao:'';
        
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

        return view('admin.showorder',$view);
    }
  
}
