<?php namespace App\Http\Controllers\Front;

/**
 * 搜索页面
 *
 * @author  技安  <php360@qq.com>
 */

use App\Http\Controllers\Controller;
use Input;
use App\Models\HgCityPoint;
use App\Models\HgCarInfo;
use App\Models\HgMoney;
use App\Models\HgBaojia;
use App\Models\HgBaojiaField;
use App\Models\HgBaojiaBaoxianChesunPrice;
use App\Models\HgBaojiaBaoxianDaoqiangPrice;
use App\Models\HgBaojiaBaoxianSanzhePrice;
use App\Models\HgBaojiaBaoxianRenyuanPrice;
use App\Models\HgBaojiaBaoxianBoliPrice;
use App\Models\HgBaojiaBaoxianHuahenPrice;
use App\Models\HgBaojiaBaoxianZiranPrice;
use App\Models\HgFields;
use App\Models\Area;
use App\Models\HgGoodsClass;
use App\Models\HgCarAtt;
use App\Models\HgBaojiaXzj;
use App\Models\HgBaojiaZengpin;
use App\Models\HgBaojiaMore;


class SearchController extends Controller {

    /**
     * 显示搜索结果列表
     */
    public function getIndex()
    {
        // 初始化模板数据变量
        $view = [
            'title' => '搜索结果',
        ];
        /*
        | ----------------------------------------------------------------------
        | 获取参数
        */
        // 获取省市地区
        $sheng = intval(Input::get('sheng', 0));
        $shi   = intval(Input::get('area', 0));
        // 将选择的地区保存到session
        session(['area.area_id' => $shi]);
        $body_color = Input::get('body_color', '');
        $interior_color   = Input::get('interior_color', '');
        $juli   = intval(Input::get('juli', 50000));
        $licheng   = intval(Input::get('licheng', 0));
        $chuchang   = Input::get('chuchang', '');
        $fukuan   = intval(Input::get('fukuan', 1));
        $buytype = intval(Input::get('buytype', 0));
        $biaozhun = Input::get('biaozhun', '');
        $view['body_color']=$body_color;
        $view['interior_color']=$interior_color;
        $view['licheng']=$licheng;
        $view['chuchang']=$chuchang;
        $view['fukuan']=$fukuan;
        $view['juli']=$juli;
        $view['biaozhun']=$biaozhun;
        // 获取个人还是公司类别
        $view['buytype']=$buyType=$buytype;// 默认个人
        if ($buyType != 0) {
            $buyType = 1; // 公司
        }

        // 如果城市为空,则根据IP地址判断城市
        if (empty($shi)) {
            $ip = get_client_ip();
            return redirect()->back();
        }
        // 获取城市名称
        $areas=Area::where('area_id','=',$shi)->first()->toArray();
        // 车型层级信息
        $brand_id = intval(Input::get('car', 0));
        //if(!array_key_exists($brand_id, $_ENV['_CONF']['goods_class'])) dd('没有该车型数据，请联系网站更新数据。');
        $chexing=$_ENV['_CONF']['goods_class'][$brand_id];
        $chexi=$_ENV['_CONF']['goods_class'][$chexing['gc_parent_id']];
        $pinpai=$_ENV['_CONF']['goods_class'][$chexi['gc_parent_id']];
        $chexing=$chexing['gc_name'];
        $chexi=$chexi['gc_name'];
        $pinpai=$pinpai['gc_name'];
        $view['car']=$brand_id;
        $view['chexing']=$chexing;
        $view['chexi']=$chexi;
        $view['pinpai']=$pinpai;
        $view['area']=$shi;
        $view['city']=$areas['area_name'];
        $view['xianpai']=$areas['area_xianpai'];
        // 获取品牌列表
        $brand=HgGoodsClass::where('gc_parent_id', '=', 0)->get();
        $view['brand']=$brand;
        //获取品牌车型的基本信息
        $barnd_info=HgGoodsClass::getCarBase($brand_id);
        $view['barnd_info']=$barnd_info;
        $pointData = HgCityPoint::getAreaPiontByCode($shi);
        if (!$pointData) {
            // TODO 没有查找到城市中心点坐标，需要给出一个提示，告之用户。同时把该城市的通知管理员添加
            exit('该地区没有参考中心点坐标');
        }

        // 获取地图数据
        $param = array(
            'center'    => $pointData, // 省市的对应中心店坐标
            'radius'    => $juli, // 查询半径
        );
        $mapData = HgCityPoint::getDealerListByMap($param);
        if (!$mapData || !$mapData['count']) {
            // TODO 没有查询到经销商数据提示信息
            exit('没有查询到经销商数据');
        }

        // 提取地图结果中的商家ID,为查询条件做准备
        $arrDealer = HgCityPoint::getDealerIdList($mapData);

        /*
        | ----------------------------------------------------------------------
        | 提取商家ID,距离,保存成k:v数组,为查询结果循环做准备
        */
        $dealerDistance = HgCityPoint::getDealerIdDistance($mapData);
        $view['dealerDistance'] = $dealerDistance;

        /*
        | ----------------------------------------------------------------------
        | 获取该车型本身的数据信息
        | 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
        */
        $view['carmodelInfo'] = HgCarInfo::getCarmodelFields($brand_id);

        /*
        | ----------------------------------------------------------------------
        | 根据座位数和个人或者公司类别确定车型类别
        | 初始化车型类别为个人6座以下
        */
        $carType = 1;
        if ($view['carmodelInfo']['seat_num'] < 6) {
            $carType = $buyType == 0 ? 1 : 3;
        } else {
            $carType = $buyType == 0 ? 2 : 4;
        }
        // 取得搜索属性，如车源距离,行驶里程等
        $att=HgCarAtt::getAtt();
        $view['att'] =$att;
        /*
        | ----------------------------------------------------------------------
        | 获取系统默认金额
        */
        $view['defaultMoney'] = HgMoney::getMoneyList();

        /*
        | ----------------------------------------------------------------------
        | 查询数据操作
        */
        // 今天时间0点和明天0点
        $dayTime = get_day_time(1);
        // 报价查询条件
        $map = array(
            'bj_start_time' => ['elt', $dayTime['day']], // 开始时间<=今天0点
            'bj_end_time'   => ['egt', $dayTime['assign']], // 结束时间>=今晚24点
            'dealer_id'     => ['in', $arrDealer],
            'brand_id'      => $brand_id,
            'bj_jc_period' =>$chuchang,
            'bj_pay_type'   =>$fukuan,
            'bj_licheng'    =>['elt', intval($licheng)],
        	'bj_num'		=>['egt',1],//检测报价库存
	
            '_sub'          => [
                '_logic'    => 'or',
                'country'   => 1,//可售全国
                //'province'  => $sheng,
                'city'      => $shi,//或者当前地市
            ],

        );
        if(!$chuchang) unset($map['bj_jc_period']);
        if(!$licheng) unset($map['bj_licheng']);
        if(!$fukuan) unset($map['bj_pay_type']);
        // 分页页码
        $page = intval(Input::get('page', 1));

        // 查询报价列表，包含分页
        $bjObject = HgBaojia::getBaojiaList(
            $map,
            config('app.page_size'),
            $page,
            $dayTime['remain']);
        /*
        | ----------------------------------------------------------------------
        | 分页
        */
        $view['page'] = $bjObject->appends([
                            'car'   => $brand_id,
                            'sheng' => $sheng,
                            'area'  => $shi,
                            'buytype'=>$buytype,
                            'body_color'=>$body_color,
                            'interior_color'=>$interior_color,
                            'licheng'=>$licheng,
                            'chuchang'=>$chuchang,
                            'fukuan'=>$fukuan,
                            'juli'=>$juli,
                            'biaozhun'=>$biaozhun,

                        ])->render();
        // 读取最小默认公里数
        $minDealerDistance = config('app.min_dealer_distance');

        // 初始化最低价格
        $view['minPrice'] = 0;
        $baojiaList = [];
        foreach ($bjObject as $value) {
            $v = $value->toArray();

            // 标记最低价格
            if ($view['minPrice'] == 0 || $view['minPrice'] > $v['bj_lckp_price']) {
                $view['minPrice'] = $v['bj_lckp_price'];
            }

            // 经销商距离
            $v['dealer_distance'] = $dealerDistance[$v['dealer_id']];
            if ($dealerDistance[$v['dealer_id']] < $minDealerDistance) {
                $v['show_distance'] = $minDealerDistance/1000;
            } else {
                $v['show_distance'] = $dealerDistance[$v['dealer_id']]/1000;
            }
            // 判断交车周期和生产日期
            if (empty($v['bj_producetime'])) {
                $v['timerange'] = $v['bj_jc_period'];
            } else {
                $v['timerange'] = $v['bj_producetime'];
            }

            // 该报价对应车型本身信息
            $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($v['bj_id']);
            foreach ($bjCarInfo as $key => $val) {
                if (is_array($view['carmodelInfo'][$key])) {
                    $v[$key] = $view['carmodelInfo'][$key][$val];
                } else {
                    $v[$key] = $val;
                }
            }

            // 对比价
            $v['dbj'] = $view['carmodelInfo']['zhidaojia']-$v['bj_lckp_price'];

            // 对比价百分比
            $v['dbjpre'] = sprintf(
                "%.2f%%",
                $v['dbj'] / $view['carmodelInfo']['zhidaojia']*100);
            $carSeatType = ($view['carmodelInfo']['seat_num']>=6)?2:1;//判断车的座位数seat_num，用以获取保险类型 addby Jerry
            // 保险价格
            if ($v['bj_bx_select'] > 0) {
                $v['bxprice']['chesun'] =
                    HgBaojiaBaoxianChesunPrice::getPrice($v['bj_id'],$carSeatType);
                $v['bxprice']['daoqiang'] =
                    HgBaojiaBaoxianDaoqiangPrice::getPrice($v['bj_id'],$carSeatType);
                $v['bxprice']['sanzhe'] =
                    HgBaojiaBaoxianSanzhePrice::getPrice($v['bj_id'],$carSeatType);
                $v['bxprice']['renyuan'] =
                    HgBaojiaBaoxianRenyuanPrice::getPrice($v['bj_id'],$carSeatType);
                $v['bxprice']['boli'] =
                    HgBaojiaBaoxianBoliPrice::getPrice($v['bj_id'],$carSeatType);
                $v['bxprice']['huahen'] =
                    HgBaojiaBaoxianHuahenPrice::getPrice($v['bj_id'],$carSeatType);
                // Todo 自燃险在默认保险报价中不参与报价，默认是不包含其报价中
                //$v['bxprice']['ziran'] = HgBaojiaBaoxianZiranPrice::getPrice($v['bj_id']);

                // 总的保险价格
                $v['bxprice']['count'] =
                    ($v['bxprice']['chesun']['count']
                    + $v['bxprice']['daoqiang']['count']
                    + $v['bxprice']['sanzhe']['count']
                    + $v['bxprice']['renyuan']['count']
                    + $v['bxprice']['boli']['count']
                    + $v['bxprice']['huahen']['count'])*$v['bj_baoxian_discount']/100;
            } else {
                $v['bxprice'] = 0;
            }
            // 报价对应的选装件
            $xzj=HgBaojiaXzj::getBaojiaXzj($v['bj_id']);
            $v['xzj']=$xzj;

            //对应赠品
            $v['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($v['bj_id']);
            // 补贴
            $v['bj_butie'] = intval($v['bj_butie']);
            //取得报价的扩展信息,补贴发放
            $baojiamore=HgBaojiaMore::getBaojiaMove($v['bj_id']);
            if (isset($baojiamore['butie'])) {
                if(is_array($baojiamore['butie'])){

                    $v['baojiamore']=implode(array_values($baojiamore['butie']));
                }else{
                    $v['baojiamore']=$baojiamore['butie'];
                }
            }

            // 其他费用
            $fiels=HgFields::getFieldsName('other_price')->toArray();
            $otherprice=array();
            // 以前的报价没有other_price，为了兼容做一个判断
            if (array_key_exists('other_price',$baojiamore)) {
                foreach ($baojiamore['other_price'] as $key => $value) {
                    foreach ($fiels as $k1 => $v1) {
                        if($key==$v1['name']){
                            $otherprice[$v1['title']]=$value;
                        }
                    }
                }
            }

            $v['otherprice']=$otherprice;
            // 添加到报价列表中
            $baojiaList[] = $v;
        }
        /*
        | ----------------------------------------------------------------------
        | 报价视图数据
        */
        $view['baojialist'] = $baojiaList;
        return view('search.index', $view);
    }
    // 报价对比
    public function Compare($area,$brand_id,$bj_ids,$buytype){
    	
        // 获取城市名称
        $areas=Area::where('area_id','=',intval($area))->first()->toArray();
        // 车型层级信息
        $brand_id = intval($brand_id);
        if(!array_key_exists($brand_id, $_ENV['_CONF']['goods_class'])) dd('没有该车型数据，请联系网站更新数据。');
        $chexing=$_ENV['_CONF']['goods_class'][$brand_id];
        $chexi=$_ENV['_CONF']['goods_class'][$chexing['gc_parent_id']];
        $pinpai=$_ENV['_CONF']['goods_class'][$chexi['gc_parent_id']];
        $chexing=$chexing['gc_name'];
        $chexi=$chexi['gc_name'];
        $pinpai=$pinpai['gc_name'];
        $view['car']=$brand_id;
        $view['chexing']=$chexing;
        $view['chexi']=$chexi;
        $view['pinpai']=$pinpai;
        $view['city']=$areas['area_name'];
        $view['xianpai']=$areas['area_xianpai'];
        //获取品牌车型的基本信息
        $view['barnd_info']=HgGoodsClass::getCarBase($brand_id);
        /*
        | ----------------------------------------------------------------------
        | 获取该车型本身的数据信息
        | 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
        */
        $view['carmodelInfo'] = HgCarInfo::getCarmodelFields($brand_id);
        // 取得中心点坐标
        $pointData = HgCityPoint::getAreaPiontByCode($area);
        if (!$pointData) {
            // TODO 没有查找到城市中心点坐标，需要给出一个提示，告之用户。同时把该城市的通知管理员添加
            exit('该地区没有参考中心点坐标');
        }
        // 获取地图数据
        $param = array(
            'center'    => $pointData, // 省市的对应中心店坐标
            'radius'    => 50000, // 查询半径
        );
        $mapData = HgCityPoint::getDealerListByMap($param);
        if (!$mapData || !$mapData['count']) {
            // TODO 没有查询到经销商数据提示信息
            exit('没有查询到经销商数据');
        }
        // 提取地图结果中的商家ID,为查询条件做准备
        $arrDealer = HgCityPoint::getDealerIdList($mapData);

        /*
        | ----------------------------------------------------------------------
        | 提取商家ID,距离,保存成k:v数组,为查询结果循环做准备
        */
        $dealerDistance = HgCityPoint::getDealerIdDistance($mapData);
        $view['dealerDistance'] = $dealerDistance;
        
        //NeedToDo  测试数据
        $view['show_distance']=50;
        
        
        // 读取最小默认公里数
        $minDealerDistance = config('app.min_dealer_distance');

        // 初始化最低价格
        $view['minPrice'] = 0;

        // 根据报价id取得报价信息
        $bjids=explode(',',$bj_ids);
        $bj = array();
        foreach ($bjids as $key) {
        // 查询报价信息
            $bjbase = HgBaojia::getBaojiaInfo($key);
            if(!$bjbase){
            	continue;
            }
            if ($view['minPrice'] == 0 || $view['minPrice'] > $bjbase['bj_lckp_price']) {
                $view['minPrice'] = $bjbase['bj_lckp_price'];
            }
            // 经销商距离
            $bjbase['dealer_distance'] = $dealerDistance[$bjbase['dealer_id']];
            if ($dealerDistance[$bjbase['dealer_id']] < $minDealerDistance) {
                $bjbase['show_distance'] = $minDealerDistance/1000;
            } else {
                $bjbase['show_distance'] = $dealerDistance[$bjbase['dealer_id']]/1000;
            }

            $bj[$key]=$bjbase;

            // 对比价
            $bj[$key]['dbj'] = $view['carmodelInfo']['zhidaojia']-$bjbase['bj_lckp_price'];

            // 对比价百分比
            $bj[$key]['dbjpre'] = sprintf(
                "%.2f%%",
                $bj[$key]['dbj'] / $view['carmodelInfo']['zhidaojia']*100);
            // 报价自定义字段
            $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($key);
            foreach ($bjCarInfo as $k => $val) {
                if (is_array($view['carmodelInfo'][$k])) {
                    $bj[$key][$k] = $view['carmodelInfo'][$k][$val];
                } else {
                    $bj[$key][$k] = $val;
                }
            }
            // 保险价格
            if ($bj[$key]['bj_bx_select'] > 0) {
                $bj[$key]['chesun'] =HgBaojiaBaoxianChesunPrice::getPrice($key);
                $bj[$key]['daoqiang'] =HgBaojiaBaoxianDaoqiangPrice::getPrice($key);
                $bj[$key]['sanzhe'] =HgBaojiaBaoxianSanzhePrice::getPrice($key);
                $bj[$key]['renyuan'] =HgBaojiaBaoxianRenyuanPrice::getPrice($key);
                $bj[$key]['boli'] =HgBaojiaBaoxianBoliPrice::getPrice($key);
                $bj[$key]['huahen'] =HgBaojiaBaoxianHuahenPrice::getPrice($key);
                // Todo 自燃险在默认保险报价中不参与报价，默认是不包含其报价中
                //$bj[$key]['ziran'] = HgBaojiaBaoxianZiranPrice::getPrice($key);

                // 总的保险价格
                $bj[$key]['bxprice'] =
                    $bj[$key]['chesun']['count']
                    + $bj[$key]['daoqiang']['count']
                    + $bj[$key]['sanzhe']['count']
                    + $bj[$key]['renyuan']['count']
                    + $bj[$key]['boli']['count']
                    + $bj[$key]['huahen']['count'];
            } else {
                $bj[$key]['bxprice'] = 0;
            }
            // 报价对应的选装件
            $xzj=HgBaojiaXzj::getBaojiaXzj($key)->toArray();
            $bj[$key]['xzj']=$xzj;
            //对应赠品
            $bj[$key]['zengpin']=HgBaojiaZengpin::getBaojiaZengpin($key)->toArray();
            //报价扩展信息包括其他费用
            $more=HgBaojiaMore::getBaojiaMove($key);
            $other_price = array();
            if(array_key_exists('other_price',$more)){

                foreach ($more['other_price'] as $k => $v) {
                    // 取得自定义字段标题
                    $other_title=HgFields::where('name','=',$k)->first();
                    $other_price[$other_title['title']]=$v;

                }
            }
            $bj[$key]['other_price']=$other_price;

        }

        $view['bj']=$bj;
        $view['buytype']=$buytype;
        $view['title'] = '车型报价对比';
        return view('compare.index',$view);
    }
}
