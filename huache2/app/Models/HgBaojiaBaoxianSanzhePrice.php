<?php namespace App\Models;

/**
 * 第三责任险模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;
use DB;

class HgBaojiaBaoxianSanzhePrice extends Model {

    protected $table = 'hg_baojia_baoxian_sanzhe_price';

    public $timestamps = false;

    use Common;

    public static function getPrice($bjid, $carType = 1,$compensate=50, array $map =
    []) {
        $cacheName = 'sanzhe_price' . $bjid . $carType . serialize($map);
        // Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
                'compensate' => $compensate,
            ];
            if(!empty($map)) {
                $whereMap = array_merge($whereMap, $map);
            }
            $d = self::select('price', 'bjm_price','discount_price','bjm_discount_price','compensate')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
                    $cacheData['price'] = floatval($val->price);
                    $cacheData['bjm_price'] = floatval($val->bjm_price);
                    $cacheData['count'] = floatval($val->price);
                    $cacheData['discount_price'] = floatval($val->discount_price);
                    $cacheData['bjm_discount_price'] = floatval($val->bjm_discount_price);
                    $cacheData['compensate'] = intval($val->compensate);
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
    // 取得第三责任险所有额度数据
    public static function getAllPrice($bjid, $carType = 1, $map =array()) {
        $cacheName = 'sanzhe_price_all' . $bjid . serialize($map);
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
                
            ];
            if(!empty($map)) {
                $whereMap = array_merge($whereMap, $map);
            }
            $d = self::select('price', 'bjm_price','discount_price','bjm_discount_price','compensate','bjm_percent')
                       ->param($whereMap)->orderBy('compensate','asc')
                       ->get()->toArray();
            if($d) {
            	$tmp= array();
            	foreach($d as $k=>$v){
            		$tmp[$v['compensate']] = $v;
            	}
                $cacheData=$tmp;
                if(!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            }

        } else {
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }
}
