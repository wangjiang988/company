<?php namespace App\Models;

/**
 * 地区模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class Area extends Model {

    protected $table = 'area';
    protected $primaryKey = 'area_id';
    public $timestamps = false;

    /**
     * 获取顶级地区数据
     * @return mixed
     */
    public static function getTopArea() {
        $sql = self::select('area_id', 'area_name' ,'first_letter' )
             ->where('area_id', '<>', 35)
             ->where('area_parent_id', 0)
             ->where('area_deep', 1)
             ->orderBy('area_sort')
             ->orderBy('area_id');
        if (config('app.debug')) {
            return $sql->get();
        }
        return Cache::rememberForever(
            'TopArea',
            function() use ($sql) {
                return $sql->get();
            }
        );
    }

    /**
     * 获取二级地区数据
     * @return mixed
     */
    public static function getSecArea() {
        $cacheName = 'cacheSecArea';
        if(!Cache::has($cacheName)) {
            $cacheData = self::getTopArea()->toArray();
            foreach ($cacheData as $key => $value) {
                $cacheData[$key]['child'] = self::select('area_id', 'area_name' ,'area_xianpai')
                    ->where('area_parent_id', $value['area_id'])
                    ->where('area_deep', 2)
                    ->orderBy('area_sort')
                    ->orderBy('area_id')
                    ->get()
                    ->toArray();
            }
            if(!empty($topArea) && !config('app.debug')) {
                Cache::forever($cacheName, $cacheData);
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    // 缓存所有地区数据
    public static function getArea() {
        $cacheName = 'cacheArea';
        // Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $cacheData = self::all()->toArray();

            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    /**
     * 删除缓存操作
     */
    public function unlinkCache() {
        $cacheName = [
            'cacheTopArea',
            'cacheSecArea',
            'cacheArea'
        ];
        foreach ($cacheName as $val) {
            Cache::forget($val);
        }
    }
    // 取得地区的名称
    public static function getAreaName($area_id){
        return self::where('area_id','=',$area_id)->pluck('area_name');
    }
    // 取得单条地区数据
    public static function getAreaByid($area_id){
        return self::where('area_id','=',$area_id)->first();
    }
    // 取得限牌城市
    public static function getXianPai(){
          $cacheName = 'cacheAreaXianpai';
          if(!Cache::has($cacheName)) {
            $cacheData = self::where('area_xianpai','=',1)->get();

                if(!empty($cacheData) && !config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            } else {
                $cacheData = Cache::get($cacheName);
            }
            return $cacheData;
    }
    // 实行新车险的城市
    public static function newBaoxian()
    {
        $cacheName = 'cacheAreaPolicy';
         if(!Cache::has($cacheName)) {
            $cacheData = self::where('area_area_policy','=',1)->get()->pluck('area_id');

                if(!empty($cacheData) && !config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            } else {
                $cacheData = Cache::get($cacheName);
            }
            return $cacheData;
    }
    // 取得地区独对应的车牌前两位
    public static function getPaizhao($area_id){
        $pai= self::where('area_id','=',$area_id)->pluck('area_chepai');
        return mbStrSplit($pai);
    }
    // 取得某个省的对应城市
    public static function getCitys($sheng)
    {
        $sheng=intval($sheng);
        $cacheName = 'cache'.$sheng;
        if(!Cache::has($cacheName)) {
            $cacheData =self::select('area_name as name')->where('area_parent_id','=',$sheng)->get();

                if(!empty($cacheData) && !config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            } else {
                $cacheData = Cache::get($cacheName);
            }
            return $cacheData;
    }


    /**
     * @param $area_id
     * @return mixed
     * 限牌城市
     */
    public static function getXianpaiCity($area_id)
    {
        $city_list = self::where('area_xianpai',1)->where('area_deep', 2)->lists('area_name', 'area_id');
        if ($city_list) {
            foreach ($city_list as $item => $value) {
                if ($item == $area_id) {
                    unset($city_list[$item]);
                }
            }
        }
        return $city_list;
    }
}
