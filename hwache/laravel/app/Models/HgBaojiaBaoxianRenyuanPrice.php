<?php namespace App\Models;

/**
 * 系统默认金额数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;
use DB;

class HgBaojiaBaoxianRenyuanPrice extends Model {

    protected $table = 'hg_baojia_baoxian_renyuan_price';

    public $timestamps = false;

    use Common;
    // 司机默认的组合
    public static function getPrice($bjid, $carType = 1, array $map =
    ['staff' => 'sj','compensate' => '1']) {
        $cacheName = 'renyuan_price' . $bjid . $carType . serialize($map);
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
            $d = self::select('staff', 'price', 'bjm_price','compensate','discount_price','bjm_discount_price')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
                    $cacheData['staff'] = $val->staff;
                    $cacheData['price'] = floatval($val->price);
                    $cacheData['bjm_price'] = floatval($val->bjm_price);
                    $cacheData['count'] = floatval($val->price);
                    $cacheData['compensate'] = intval($val->compensate);
                     $cacheData['discount_price'] = floatval($val->discount_price);
                     $cacheData['bjm_discount_price'] = floatval($val->bjm_discount_price);
                    if(!empty($val->bjm_price)) {
                        $cacheData['count'] += $val->bjm_price;
                    }
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
    // 乘客默认组合
    public static function getCkPrice($bjid, $carType = 1, array $map =
    []) {
        $cacheName = 'renyuan_price_ck' . $bjid . $carType . serialize($map);
        // Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
                'compensate' => '1',
                'staff' => 'ck',
            ];
            if(!empty($map)) {
                $whereMap = array_merge($whereMap, $map);
            }
            $d = self::select('staff', 'price', 'bjm_price','compensate','discount_price','bjm_discount_price')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
                    $cacheData['staff'] = $val->staff;
                    $cacheData['price'] = floatval($val->price);
                    $cacheData['bjm_price'] = floatval($val->bjm_price);
                    $cacheData['count'] = floatval($val->price);
                    $cacheData['compensate'] = intval($val->compensate);
                     $cacheData['discount_price'] = floatval($val->discount_price);
                     $cacheData['bjm_discount_price'] = floatval($val->bjm_discount_price);
                    if(!empty($val->bjm_price)) {
                        $cacheData['count'] += $val->bjm_price;
                    }
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
    // 取得人员险所有额度记录
    public static function getAllPrice($bjid,$carType = 1)
    {
        $cacheName = 'renyuan_price_all' . $bjid . $carType;
        // Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
                
            ];
            $cacheData = self::select('staff', 'price', 'bjm_price','discount_price','bjm_discount_price','compensate')
                       ->param($whereMap)->orderBy('staff','asc')->orderBy('compensate','asc')
                       ->get()->toArray();
            if(!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
        }else {
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }
    
    // 取得人员险所有额度记录 并分类
    public static function getAllPriceByGrade($bjid,$carType = 1)
    {
    	$cacheName = 'renyuan_price_all_by_grade' . $bjid . $carType;
    	// Cache::forget($cacheName);
    	if(!Cache::has($cacheName)) {
    		$cacheData = [];
    		$whereMap = [
    				'type'  => $carType,
    				'bj_id' => $bjid,
    
    		];
    		$cacheData = self::select('staff', 'price', 'bjm_price','discount_price','bjm_discount_price','compensate','bjm_percent')
    		->param($whereMap)->orderBy('staff','asc')->orderBy('compensate','asc')
    		->get()->toArray();
    		if(count($cacheData)>0){
    			$tmp = array();
    			foreach($cacheData as $k=>$v){
    				$tmp[$v['staff']][$v['compensate']] = $v;
    			}
    			$cacheData = $tmp;
    		}
    		if(!config('app.debug')) {
    			Cache::put($cacheName, $cacheData, config('app.cache_time'));
    		}
    	}else {
    		$cacheData = Cache::get($cacheName);
    	}
    
    	return $cacheData;
    }
}
