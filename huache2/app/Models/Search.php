<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
use PhpSpec\Exception\Exception;

class Search extends Model
{
    protected $searchWhere;//搜索条件
    protected $defaultWhere;
    protected $keyExcept;
    protected $primaryKey = 'bj_id';
    protected $table    = 'search_view';//报价表
    use Common;
    private $cacheTime;
    public function __construct()
    {
        $dayTime = get_day_time(1);
        $this->cacheTime = $dayTime['remain'];
        $this->defaultWhere = "bj_status=1 AND bj_is_public = 1 AND bj_step= 99";
        //地区,品牌,车系,车型,排放标准,车身颜色,内饰颜色,行驶里程,交车周期,付款方式,上牌用途
        $this->keyExcept = ['region','brand','series','model','emission','body_color','interior_color','mileage','delivery','pay_type','licensing_purposes'];//允许的检索条件
    }
    //检索列表
    /**
     * @return bool
     */
    public function pageList($wheres , $areas,  $pageSize=10, $page=1, $cacheTime=2000,$order='region',$sort='asc'){
        //通过是否限牌，判断经销商可销售区域
        $city  = $areas['area_id'];
        $is_xp = $areas['area_xianpai'];
        if($is_xp == 1 ){
            $wheres .= " AND sales_region like '%{$areas['name']}%'";
        }else{
            $wheres .= " AND (country =1 OR sales_region like '%{$areas['name']}%')";
        }
        $col =$this->setScopeOrder($areas);
        $cacheName = md5(str_replace(' ','',$wheres) . $order . $sort. $page.md5($col) . '_' . $pageSize);
        switch($order){
            case 'price':
                $orderStr = 'hwache_price';
                break;
            case 'mileage'://行驶里程
                $orderStr = 'bj_licheng';
                break;
            case 'factory_date'://出厂年月
                $orderStr = 'cc_time';
                break;
            case 'term'://交车周期
                $orderStr = 'bj_jc_period';
                break;
            default:
                $orderStr = 'scopeOrder desc ,hwache_price';
        }
        Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $result = self::select(DB::raw('SQL_CACHE *,'.$col))
                ->where(DB::raw($wheres.' AND bj_num'),'>=','1')
                ->orderBy(DB::raw($orderStr),$sort)
                ->paginate($pageSize);
            if(!empty($result) && !config('app.debug')) {
                Cache::put($cacheName, $result, $this->cacheTime);
            }
        } else {
            $result = Cache::get($cacheName);
        }
        return $result;
    }

    /** 设置车源范围检索条件
     * @param $car_scope
     * @param $areas
     * @return bool|string
     */
    public function setMapScope($car_scope,$areas){
        $regionStr = $areas['parent_name'].$areas['name'];
        //$result = false;
        $AroundProvince = $this->getAroundProvince($areas['parent_id']);
        switch($car_scope){
            case 1://本地(当车源范围=上牌地区时)
                $result = "car_scope = '".$regionStr."'";
                break;
            case 2://周边(上牌地区+上牌地区所在省（不含上牌地区）+上牌地区省分周边省分)
                if($AroundProvince){
                    $arountFind = $this->setStrToArray($AroundProvince);
                    array_push($arountFind,$areas['parent_name']);
                    $result  = " car_scope <> '{$regionStr}' AND ".$this->setOrLike($arountFind);
                }else{
                    $result  = " car_scope <> '{$regionStr}' AND car_scope =''";
                }
                break;
            case 3://更远
                if($AroundProvince){
                    $arountFind = $this->setStrToArray($this->getAroundProvince($areas['parent_id']));
                    array_push($arountFind,$areas['parent_name']);
                    $result  = $this->setOrLike($arountFind,true,' AND ');
                }else{
                    $result  = " car_scope not like '%{$areas['parent_name']}%'";
                }
                break;
            case 4://中心城市
                $centerCity = $this->setStrToArray($this->getCenterCity());
                $result     = $this->setOrLike($centerCity,false,' OR ','___','');
                break;
            default://指定城市
                $result = "car_scope like '%$car_scope%'";
        }
        return $result;
    }

    /** 设置车源范围排序
     * @param $areas
     * @return string
     */
    private function setScopeOrder($areas){
        $where = array();
        $regionStr = $areas['parent_name'].$areas['name'];
        $where[6] = "car_scope = '".$regionStr."'";
        $where[5] = "car_scope like '%".$regionStr."%'";
        $where[4] = "car_scope like '%".$areas['parent_name']."%'";
        $arountFind = $this->getAroundProvince($areas['parent_id']);
        if($arountFind){
            $arountArr = $this->setStrToArray($arountFind);
            array_push($arountArr,$areas['parent_name']);
            $where[3]  = "car_scope <> '{$regionStr}' AND ".$this->setOrLike($arountArr);
            $where[2]  = $this->setOrLike($arountArr,true,' AND ');
        }
        $where[1] = "car_scope not like '%".$areas['parent_name']."%'";
        //CASE 1 WHEN 1 THEN "one" WHEN 2 THEN "two" ELSE "more" END;
        $scopeOrder = '(CASE ';
        foreach($where as $key => $wh){
            $scopeOrder .= ' WHEN '.$wh.' THEN '.$key;
        }
        $scopeOrder .= ' ELSE 0 END) as scopeOrder';
        return $scopeOrder;
    }
    /**
     * 获取省份的其他周边省份
     * @param $province
     */
    public function getAroundProvince($province){
        $cacheName = 'ArountProvince_' . $province;
        Cache::forget($cacheName);
        if(!Cache::has($cacheName)){
            $result = DB::table('hc_provinces_manage')
                //->select(DB::raw('GROUP_CONCAT(car_a.area_name) as areafind'))
                ->leftjoin('area as a','ambitus_area_id','=','a.area_id')
                ->where('hc_provinces_manage.area_id',$province)
                ->orderBy('id')
                ->groupBy('a.area_id')
                ->value(DB::raw('GROUP_CONCAT(car_a.area_name)'));
            Cache::put($cacheName , $result , config('app.cache_time') );
        }else{
            $result = Cache::get($cacheName);
        }
        return $result;
        //->rirst();
    }

    /**
     * 获取中心城市
     * @return mixed
     */
    public function getCenterCity(){
        $cacheName = 'Center_city';
        Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $result = DB::table('hc_central_city')
                //->select(DB::raw('GROUP_CONCAT(car_a.area_name) as centerCity'))
                ->leftjoin('area as a', 'area_city_id', '=', 'a.area_id')
                ->value(DB::raw('GROUP_CONCAT(car_a.area_name)'));
            if($result) {
                Cache::put($cacheName, $result, config('app.cache_time'));
            }
        }else{
            $result = Cache::get($cacheName);
        }
        return $result;
    }
    /**
     * 组织多条件like查询语句
     * @param array $dataArray
     * @param string $col
     * @param bool $not
     * @return string
     */
    public function setOrLike(Array $dataArray,$not=false,$or=' OR ',$left='%',$right='%',$col='car_scope'){
        $orLike = [];
        $like = ($not) ? ' not like ' : ' like ';
        foreach($dataArray as $val){
            $orLike[] = $col . $like . "'".$left.$val.$right."'";
        }
        return '('.implode($or , $orLike).')';
    }
    /**
     * 设置检索 多条件多重数据
     * @param $searchArr        条件数组
     * @param string $serchStr  检索字段
     */
    public function setFINDsWhere($searchArr,$serchStr="FIND_IN_SET('%s',sales_region)"){
        $resultStr = array_fill(0,count($searchArr),$serchStr);
        return vsprintf(implode(' OR ' , $resultStr),$searchArr);
    }

    /**
     * 其他费用
     * @param $bj_id
     * @return mixed
     */
    public function getOtherPrice($bj_id){
        $cahceName = 'searchBaojiaOther_'.$bj_id;
        if(!Cache::has($cahceName)){
            $this->setTable('hg_baojia_other_price');
            $result = self::select(DB::raw("`other_name`,sum(`other_price`) sub_total"))
                ->where('bj_id',$bj_id)
                ->groupBy('other_id')
                ->get();
           $result = ( ! is_object($result)) ? ((object) $result) : $result ;
           if($result && !config('app.debug')){
               Cache::put($cahceName , $result , config('app.cache_time'));
           }
        }else{
            $result = Cache::get($cahceName);
        }
        return $result;
    }

    /**
     * 可销售区域（城市）
     * @param $bj_id
     * @return mixed
     */
    public function getBuyCity($bj_id){
        $col =" GROUP_CONCAT(CONCAT(car_d.area_name,car_c.area_name)) as sales_region";
        return self::select(DB::raw($col))
            ->leftjoin('hg_baojia_area as b','bj_id', '=' , 'b.bj_id')
            ->leftjoin('area as c','b.city','=','c.area_id')
            ->leftjoin('area as d','b.province','=','d.area_id')
            ->where('bj_id',$bj_id)
            ->first();
    }

    /** 车源范围排序
     * @param $bj_id
     * @param $city
     * @return mixed
     */
    public function getBuyCityOrderStr($scope,$areas){
        $arr = $this->setStrToArray($scope);
        $regionStr = $areas['parent_name'].$areas['name'];
        $where = array();
        foreach($arr as $citys){
            //本省
            if(strstr($citys,$areas['parent_name'])){
                //完全相等
                if($citys == $regionStr){
                    $_citys[]   = $citys;
                }else{
                    $_parents[] = $citys;
                }
            }else{
                //周边排序
                $arountFind = $this->getAroundProvince($areas['parent_id']);
                $arountProvince = mb_substr($citys,0,3);
                if ($arountFind && strstr($arountFind,$arountProvince)) {
                    if(!strstr($arountProvince,$areas['parent_name'])){
                        $ProvinceSort = $this->setStrToArray($arountFind);
                        foreach($ProvinceSort as $k=> $sortSrt){
                            if($sortSrt == $arountProvince)
                                $_arounts[$k][] = $citys;
                        }
                    }
                }else{
                    //更远地区
                    $_other[] = $citys;
                }
            }
        }
        $scopeStrs = [];
        if(isset($_citys)){
            $scopeStrs['_citys'] = implode(',',$_citys);
        }
        if(isset($_parents)){
            $scopeStrs['_parents'] = implode(',',$_parents);
        }
        if(isset($_arounts)){
            $scopeStrs['_arounts'] = implode(',',$this->arrayTwoToOne($_arounts));
        }
        if(isset($_other)){
            $scopeStrs['_other'] = implode(',',$_other);
        }
        $scopeNewStr = implode(',',$scopeStrs);
        return str_replace([',','上海市上海市','北京市北京市','天津市天津市','重庆市重庆市'],[' 或 ','上海市','北京市','天津市','重庆市'],$scopeNewStr);
    }
    private function arrayTwoToOne($array){
        $newArr = array();
        foreach($array as $key => $val){
            if(is_array($val)){
                 $newArr[$key] = implode(',',$val);
            }else{
                $newArr[$key] = $val;
            }
        }
        sort($newArr);
        return explode(',',implode(',',$newArr));
    }

    private function isSearchKey($key){
        $isKey = in_array($key,$this->keyExcept);
        if(!$isKey){
            throw new Exception('参数错误！');
        }
    }

    private function showError($msg){
        echo json_encode(['Success'=>0,'Msg'=>$msg]);
    }

    /**
     * 读取品牌缓存数据
     * @param $brandid
     * @return mixed
     */
    public function getBrand($brandid){
        $brandList = config('car');
        //$car[$v['gc_parent_id']][$v['gc_id']] = array('gc_id' => $v['gc_id'], 'gc_name' => $v['gc_name']);
        $parent_id = DB::table('goods_class')->where('gc_id',$brandid)->value('gc_parent_id');
        return $brandList[$parent_id][$brandid];
    }

    /**
     * 读取地区缓存数据
     * @param $city
     * @return bool
     */
    public function getRegion($city){
        $regionList = config('area');
        if($regionList){
            foreach($regionList as $key => $regs){
                if(array_key_exists($city,$regs)){
                    $tmp = $regionList[$key][$city];
                    $tmp['parent_id'] = $key;
                    return $tmp;
                }

            }
        }
        return false;
        //return array_search($city,$regionList);
    }

    /** 查找车型
     * @param $id
     * @param int $level
     */
    public function getBrandList($id){
        $this->setTable('goods_class as brand');
        $col = "car_brand.gc_id,car_brand.gc_name,car_brand.detail_img as d_img,car_xi.detail_img,car_xin.gc_id AS xin_id,car_xin.gc_name AS xin_name,car_xi.gc_id AS xi_id,car_xi.gc_name AS xi_name";
        return self::select(DB::raw($col))
            ->leftJoin('goods_class as xin','brand.gc_parent_id','=','xin.gc_id')
            ->leftJoin('goods_class as xi','xin.gc_parent_id','=','xi.gc_id')
            ->where('brand.gc_id',$id)->first();
    }
    /** 计算保险
     * @param $chesun
     * @param $daoqiang
     * @param $sanzhe
     * @param $renyuan
     * @param $boli
     * @param $huahen
     * @param $bj_baoxian_discount
     * @return float|int
     */
    public function setInsuranceTotalPrice($chesun,$daoqiang,$sanzhe,$renyuan,$boli,$huahen,$bj_baoxian_discount){
        // 总的保险价格
        $bxprice = (($chesun  + $daoqiang  + $sanzhe + $renyuan + $boli + $huahen) * $bj_baoxian_discount) / 100;
        return $bxprice;
    }

    /** 字符串
     * @param $str切割数组
     * @param string $dellimit
     * @return array
     */
    public function setStrToArray($str,$dellimit=','){
        return explode($dellimit,$str);
    }

    /** 获取保险总额
     * @param $num
     * @param $bj_id
     * @param $guobie
     * @param $isJob
     * @return mixed 获取人员险
     */
    public static function getBXTotal($num,$bj_id,$guobie=0,$isJob=0){
        return 0;
        /*$result = DB::select(sprintf("call setBxTotal(%d,%d,%d,%d)",$num,$bj_id,$guobie,$isJob));
        return $result[0]->bx_total;*/
        //dd($carTag['seat_num']);exit;
    }

    /** 保险公司理赔范围是否本地
     * @param $bx_id
     * @return mixed
     */
    public static function getBxJob($bx_id){
        return DB::table('hg_baoxian')->where('bx_id',$bx_id)->value('bx_is_quanguo');
    }
    public static function getSpecial($num,$bj_id,$guobie=0,$isJob=0){
        #判断买车用途
        $_type = ($isJob=0) ? 1 :3;
        #判断国别
        $_guobie = ($guobie=0) ? 'gc' : 'jk';
        $result = self::select()
                  ->leftJoin('hg_baojia_baoxian_chesun_price as cs','cs.bj_id','=','bj_id')
                  ->leftJoin();
  /*select price,bjm_price into @cs_price,@cs_discount from car_hg_baojia_baoxian_chesun_price where bj_id=id and type=@_type;
  select price,bjm_price into @dq_price,@dq_discount from car_hg_baojia_baoxian_daoqiang_price where bj_id=id and type=@_type;
  #人员乘客
  select price,(bjm_percent/100) into @ck_price,@ry_percent from car_hg_baojia_baoxian_renyuan_price where compensate='1' and staff ='ck' and bj_id=id and type=@_type;
  select price,(bjm_percent/100) into @sz_price,@sz_percent from car_hg_baojia_baoxian_sanzhe_price where compensate='50' and bj_id=id and type=@_type;
  select price,(bjm_percent/100) into @hh_price,@hh_percent from car_hg_baojia_baoxian_huahen_price WHERE compensate='20000' and bj_id=id and type=@_type;
  #人员司机
  SET @sj_price =(select price from car_hg_baojia_baoxian_renyuan_price where compensate='1' and staff='sj' and bj_id=id and type=@_type);
  SET @boli     =(select price from car_hg_baojia_baoxian_boli_price where state =@_guobie and bj_id=id and type=@_type);#type=1个人3企业；state=jk 进口，gc

  #计算人员险
  SET @_ryTotal = @sj_price + @ck_price * (num-1);
  #计算免赔特约险
  SET @_bjf = (@cs_discount+ @dq_discount) + (@sz_price* @sz_percent + @_ryTotal*@ry_percent + @hh_price*@hh_percent);

  #SELECT @cs_price,@cs_discount,@dq_price,@dq_discount,@ck_price,@ry_percent,@sz_price,@sz_percent,@hh_price,@hh_percent,@sj_price,@boli,@_ryTotal,@_bjf;
  SET @total = @cs_price + @dq_price + @sz_price + @hh_price + @boli + @_ryTotal + @_bjf;
  SELECT @total as bx_total;*/
    }

    /**
     * @param $num       座位数
     * @param $bj_id     报价id
     * @param $guobie    国别
     * @param int $isJob 用途（个人，企业）
     */
    public static function getITotal($num,$bj_id,$guobie,$isJob=0){
        //车损
        $csTable = 'hg_baojia_baoxian_chesun_price';
        //盗抢
        $dqTable = 'hg_baojia_baoxian_daoqiang_price';
        //第三者责任
        $dszTable = 'hg_baojia_baoxian_sanzhe_price';
        //人员险
        $ryTable = 'hg_baojia_baoxian_renyuan_price';
        //玻璃险
        $blTable = 'hg_baojia_baoxian_boli_price';
        //划痕险
        $hxTable = 'hg_baojia_baoxian_huahen_price';
    }
}
