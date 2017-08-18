<?php namespace App\Models;

/**
 * 系统默认金额数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;
use DB;

class HgBaojiaBaoxianBoliPrice extends Model {

    protected $table = 'hg_baojia_baoxian_boli_price';

    public $timestamps = false;

    use Common;

    public static function getPrice($bjid, $carType = 1, array
$map = ['state' => 'jk',]) {
        $cacheName = 'boli_price' . $bjid . $carType . serialize($map);
        // Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
                
            ];
            if(!empty($map)) {
                $whereMap = array_merge($whereMap, $map);
            }
            $d = self::select('price','state','discount_price')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
                    $cacheData['price'] = $cacheData['count'] = floatval($val->price);
                    $cacheData['state'] = ($val->state);
                    $cacheData['discount_price'] = floatval($val->discount_price);
                }
                if(!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            }

        } else {
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }
    public static function getAllPrice($bjid,$carType = 1)
    {
        $cacheName = 'boli_price_all' . $bjid . $carType;
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
            ];
            $cacheData = self::select('price','discount_price','state')
                       ->param($whereMap)->orderBy('state')
                       ->get()->toArray();
            if(!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
        }else{
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    
    public static function getAllPriceByGrade($bjid,$carType = 1)
    {
    	$cacheName = 'boli_price_all_by_grade' . $bjid . $carType;
    	if(!Cache::has($cacheName)) {
    		$cacheData = [];
    		$whereMap = [
    				'type'  => $carType,
    				'bj_id' => $bjid,
    		];
    		$cacheData = self::select('price','discount_price','state')
    		->param($whereMap)->orderBy('state')
    		->get()->toArray();
    		if(count($cacheData)>0){
    			$tmp = array();
    			foreach($cacheData as $k=>$v){
    				$tmp[$v['state']] = $v;
    			}
    			$cacheData = $tmp;
    		}
    		if(!config('app.debug')) {
    			Cache::put($cacheName, $cacheData, config('app.cache_time'));
    		}
    	}else{
    		$cacheData = Cache::get($cacheName);
    	}
    	return $cacheData;
    }
}
