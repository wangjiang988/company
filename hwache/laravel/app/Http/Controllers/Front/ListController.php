<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use App\Models\HgCarInfo;
use App\Models\Search;
use App\Models\HgBaojiaField;
use App\Models\HgBaojiaXzj;
use App\Models\HgBaojiaZengpin;
use Cache;
use DB;


class ListController extends Controller
{
    protected $Search;
    public function __construct()
    {
        $this->middleware('home.job_time');
        $this->Search = new Search();
    }

    /**
     * 显示检索视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(){
        //$this->testASope();
        $assign['title'] = "搜索列表 - " . trans('common.www_title');
        $city     = intval(Input::get('area', 0));
        if(empty($city)){
            return $this->setErrorMsg('没有城市参数!');
        }
        $assign['region'] = $this->getRegions($city);
        //品牌id
        $brand = intval(Input::get('car', 0));
        //$carTag = HgCarInfo::getCarmodelFields($brand);
        $assign['brands']   = $this->Search->getBrandList($brand)->toArray();
        return view('HomeV2.search.search-adv',$assign);
    }

    /**
     * 测试车源范围排序
     */
    private function testASope(){
        $arr = [
            //'南通' => '江苏省南通市,江苏省南京市,江苏省无锡市',
            //'南通' => '浙江省杭州市,江苏省南京市,江苏省苏州市',
            //'南通' =>'江苏省苏州市,上海市上海市,浙江省杭州市',
            //'苏州' => '江苏省南通市,江苏省南京市,江苏省无锡市',
            //'苏州' =>'浙江省杭州市,江苏省南京市,江苏省苏州市',
            //'苏州' => '江苏省苏州市,上海市上海市,浙江省杭州市',
            //'苏州' => '浙江省杭州市,北京市北京市,江苏省苏州市',
            //'苏州' => '江苏省南京市,北京市北京市,江苏省苏州市',
            '苏州' => '浙江省杭州市,浙江省宁波市,上海市上海市'
        ];
        $result = '';
        foreach($arr as $cityName => $values){
            $city     = DB::table('area')->where('area_name','like','%'.$cityName.'%')->where('area_deep','=',2)->value('area_id');
            // 获取城市数据
            $areas    = $this->getRegions($city);
            $result .= $this->Search->getBuyCityOrderStr($values,$areas).'|';
        }
        dd($result);
    }
    /**
     * 显示搜索结果列表
     */
    public function getAjax(Request $request){
        $cacheName = sha1(serialize($_GET));
       if($request ->ajax()){
        //Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            // 获取省市地区
            $city = intval(Input::get('city', 0));
            if (empty($city)) {
                return $this->setErrorMsg('没有城市参数!');
            }
            // 获取城市数据
            $areas = $this->getRegions($city);
            //品牌id
            $search['brand'] = $brand_id = intval(Input::get('brand', 0));
            //车身颜色
            $search['body_color'] = $body_color = Input::get('body_color', '');
            //内饰颜色
            $search['interior_color'] = $interior_color = Input::get('interior_color', '');
            //车源范围
            $search['scope'] = $car_scope = Input::get('scope', 0);
            //行驶里程
            $search['mileage'] = $mileage = intval(Input::get('mileage', 0));
            //出厂年月
            $search['factory_date'] = $factory_date = Input::get('factory_date', '');
            //付款方式
            $paytype = intval(Input::get('pay_type', 1));
            $search['pay_type'] = $paytype = ($paytype > 0) ? 1 : 0;
            //上牌标准
            $search['standard'] = $standard = Input::get('standard', '');
            //交车周期
            $search['term'] = $term = Input::get('term', 0);
            $search['city'] = $city;
            $search['car_use'] = $is_job = Input::get('car_use', 0);//上牌用途（个人、企业）
            $search['id_find'] = $id_find = Input::get('id_find', '');//对比

            $order = Input::get('order', 'region');
            $sort = Input::get('sort', 'asc');
            /*
            | ----------------------------------------------------------------------
            | 获取该车型本身的数据信息
            | 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
            */
            $carTag = HgCarInfo::getCarmodelFields($brand_id);
            /*
            | ----------------------------------------------------------------------
            | 查询数据操作
            */
            // 今天时间0点和明天0点
            $dayTime = get_day_time(1);
            // 报价查询条件
            $map[] = ' brand_id =' . $brand_id;//$map[] = 'bj_num >= 1';
            $map[] = sprintf(" bj_start_time < %s and bj_end_time > %s ", time(), time());
            if (!empty($car_scope)) {
                $scopeWhere = $this->Search->setMapScope($car_scope, $areas);
                if (!$scopeWhere) {
                    return $this->setErrorMsg('查询条件不存在！');
                }
                $map[] = $scopeWhere;
            }
            //车身颜色
            if (!empty($body_color)) {
                $bgArr = $this->Search->setStrToArray($body_color);
                $bgFind = implode("','", $bgArr);
                $map[] = "bj_body_color in ('{$bgFind}')";
            }
            //内饰颜色
            if (!empty($interior_color)) {
                //颜色多项判断
                if (strstr($interior_color, ',')) {
                    $interior_color_arr = $this->Search->setStrToArray($interior_color);
                    $LikeColor = [];
                    foreach ($interior_color_arr as $color_tmp) {
                        $colorKey = $this->getColorKey($carTag['interior_color'], $color_tmp);
                        $LikeColor[] = "interior_color LIKE '%____" . $colorKey . "__%'";
                    }
                    $map[] = '(' . implode(' OR ', $LikeColor) . ')';
                } else {
                    $colorKey = $this->getColorKey($carTag['interior_color'], $interior_color);
                    $map[] = "interior_color LIKE '%____" . $colorKey . "__%'";
                }
            }
            if (!empty($factory_date)) $map[] = $this->CcNy($factory_date);
            if (!empty($term)) $map[] = 'bj_jc_period =' . $term . ' and bj_is_xianche =0';
            if (!empty($paytype)) $map[] = 'bj_pay_type =' . $paytype;
            if (!empty($mileage)) {
                $map[] = ($mileage < 100) ? 'bj_licheng <=' . $mileage : 'bj_licheng > 50';
            }
            if (!empty($standard)) {
                //0国五1国四 2新能源 10不限制
                switch ($standard) {
                    case 5:
                        $map[] = 'standard like \'%:"0"%\'';
                        break;
                    case 4:
                        $map[] = 'standard like \'%:"1"%\'';
                        break;
                    case 6:
                        $map[] = 'standard like \'%:"2"%\'';
                        break;
                }
            }
            if (!empty($id_find)) {
                $map[] = "bj_id in (" . $id_find . ")";
            }
            //$map[] = "bj_id = 864";
            // 分页页码
            $page = intval(Input::get('page', 1));
            $pageSize = intval(Input::get('size', 20));
            // 查询报价列表，包含分页
            $BjObject = $this->Search->pageList(implode(' AND ', $map), $areas, $pageSize, $page, $dayTime['remain'], $order, $sort);
            //$pages = $BjObject->appends($search)->render();
            $BjResult = $BjObject->ToArray();

            if (!$BjResult['data']) {
                return $this->setErrorMsg('没有搜索结果!');
            }
            $pages = [
                "total" => $BjResult['total'],
                "per_page" => $BjResult['per_page'],
                "current_page" => $BjResult['current_page'],
                "last_page" => $BjResult['last_page']
            ];
            $baojiaList = [];
            $regionStr = $areas['parent_name'] . $areas['name'];

            foreach ($BjResult['data'] as $v) {
                $v['car_scope'] = $this->Search->getBuyCityOrderStr($v['car_scope'], $areas);
                // 判断交车周期和生产日期
                $v['timerange'] = empty($v['bj_producetime']) ? $v['bj_jc_period'] : $v['bj_producetime'];
                // 该报价对应车型本身信息
                $bjCarInfo = HgBaojiaField::getCarFieldsByBjid($v['bj_id']);
                foreach ($bjCarInfo as $key => $val) {
                    $v[$key] = is_array($carTag[$key]) ? $carTag[$key][$val] : $val;
                }
                //经销商所在城市
                $dealer_addr = preg_replace('/\t/', '', $v['dealer_addr']);
                // 保险价格
                $bxTotal = Search::getBXTotal($carTag['seat_num'], $v['bj_id'], $carTag['guobie'], $is_job);
                $v['bj_baoxian'] = $this->getInsuranceShow($v['bj_baoxian'], $v['bj_bx_select'], $areas, $dealer_addr);
                // 总的保险价格
                $v['insurance']['count'] = $bxTotal;

                // 报价对应的选装件
                $select = HgBaojiaXzj::getBaojiaXzjInfo($v['bj_id']);
                if (is_object($select) && !is_null($select)) {
                    $v['select'] = $select->toArray();
                }
                //报价其他费用
                $v['other'] = $this->Search->getOtherPrice($v['bj_id'])->toArray();
                //对应赠品
                $v['gifts'] = HgBaojiaZengpin::getBaojiaGifts($v['bj_id']);
                $v['standard'] = paifang(unserialize($v['standard']), true);
                $v['bj_shangpai'] = $this->setShangpai($v['car_scope'], $v['scopeOrder'], $areas, $v['bj_shangpai'], $v['bj_id']);

                $v['client_sponsion_price'] = ($dealer_addr == $regionStr) ? $v['client_sponsion_price_low'] : $v['client_sponsion_price_high'];
                //报价后装选装件判断
                //if(empty($v['bj_is_xianche'])){
                $isHzXzj = HgBaojiaXzj::getBaojiaHzXzj($v['bj_id']);
                $v['bj_xzj_zhekou'] = empty($isHzXzj) ? null : $v['bj_xzj_zhekou'];
                //url加密
                $text = $v['bj_id'].','.$city;
                $v['url'] = encrypt($text, 'HwaChe@cn');
                //}
                $baojiaList[] = $v;
            }
            $result = ['success' => 1, 'search' => $search, 'carTag' => $carTag, 'pages' => $pages, 'dataList' => $baojiaList];
            if(!config('app.debug')){
                Cache::put($cacheName , $result , config('app.cache_time'));
            }
        }else{
            $result = Cache::get($cacheName);
        }
         return $result;
       }
        return view('HomeV2.search.search-adv',$result);
    }

    /** 获取保险状态
     * @param $bj_baoxian
     * @param $isBxJobLocal
     * @param $region
     * @return mixed
     */
    private function getInsuranceShow($bj_baoxian,$bxJob,$region,$jxsAddr){
        $isBxJobLocal = Search::getBxJob($bxJob);
        //是自由投保
        if($bj_baoxian ==0) return $bj_baoxian;
        if(!empty($isBxJobLocal)){//保险-不是本地理赔
            $Insutance = 1;
        } else{
            $Insutance = ($region['parent_name'].$region['name'] == $jxsAddr) ? 1 : 0;
        }
       return $Insutance;
    }
    /**
     * @param $factory_date
     * @return string  出厂年月
     */
    private function CcNy($factory_date){
        $month6 = date('Y-m',strtotime('-6 month'));
        $Year   = date('Y-m',strtotime('-1 year'));
        $YearB  = date('Y-m',strtotime('-1 year -1 month'));
        switch($factory_date){
            case 1://半年内
                $dateStr = sprintf("cc_time >='%s'",$month6);
                break;
            case 2:
                $dateStr = sprintf("cc_time >='%s'",$Year);
                break;
            default:
                $dateStr = sprintf("cc_time <'%s'",$YearB);
        }
        return $dateStr.' and bj_is_xianche =1';
    }
    /**
     * @param $city 获取地区所有数据
     * @return bool
     */
    private function getRegions($city){
        $regionList = config('area');
        $areas    = $this->Search -> getRegion($city);
        $areas['parent_name']  = $regionList[0][$areas['parent_id']]['name'];
        return $areas;
    }

    /**
     * @param $colorArr 获取颜色值的key
     * @param $colorName
     * @return mixed
     */
    private function getColorKey($colorArr,$colorName){
        return array_search($colorName,$colorArr);
    }
    /**
     * @param $scope
     * @param $scopeOrder
     * @param $region
     * @param $shangpai
     */
    private function setShangpai($scope,$scopeOrder,$region,$shangpai,$bj_id){
        /**
         * 说明1：本人上牌：车源范围不含客户上牌地。
        说明2：指定上牌：车源范围与客户上牌地完全相同，且售方设置本地客户必须代办。
        说明3：自选上牌：车源范围与客户上牌地完全相同，且售方设置本地客户自由办理；此时车源范围=客户上牌地=经销商所属地区。
        说明4：接受安排：车源范围包含有客户上牌地，但不完全相同。
         */
        $regionStr = $region['parent_name'].$region['name'];
        if($scopeOrder == 6){
            $sp = ($shangpai == 1) ? 2 : 3;//2指定上牌，3自选上牌
            //$sp =  ($shangpai == 1) ? '-指定上牌' : '-自选上牌';
        }else if(strstr($scope,$regionStr)){
            $sp = 4; //接受安排
        }else{
            $sp = 1;//本人上牌
        }
        //echo $bj_id.$sp.'|scope:'.$scope.'|scopeOrder:'.$scopeOrder.'|regionStr:'.$regionStr.'|shangpai:'.$shangpai.'<br />';
        return $sp;
    }
    /**
     * 设置错误
     * @param string $msg
     * @return array
     */
    private function setErrorMsg($msg = '发生错误！'){
        return ['success' => 0 , 'msg' => $msg];
    }
}
