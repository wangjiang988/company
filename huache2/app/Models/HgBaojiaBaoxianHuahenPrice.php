<?php namespace App\Models;

/**
 * 系统默认金额数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgBaojiaBaoxianHuahenPrice extends Model {

    protected $table = 'hg_baojia_baoxian_huahen_price';

    public $timestamps = false;

    use Common;

    public static function getPrice($bjid, $carType = 1, array
$map = ['compensate' => '20000',]) {
        $cacheName = 'huahen_price' . $bjid . $carType . serialize($map);
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
            $d = self::select('price', 'bjm_price','compensate','discount_price','bjm_discount_price')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
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
    // 取得所有赔付额度的价格
    public static function getAllPrice($bjid,$carType = 1)
    {
        $cacheName = 'huahen_price_all' . $bjid . $carType ;
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
            ];
            $cacheData = self::select('price', 'bjm_price','discount_price','bjm_discount_price','compensate')
                       ->param($whereMap)->orderBy('compensate')
                       ->get()->toArray();
            if(!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
        }else{
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    
    // 取得所有赔付额度的价格
    public static function getAllPriceByGrade($bjid,$carType = 1)
    {
    	$cacheName = 'huahen_price_all' . $bjid . $carType ;
    	if(!Cache::has($cacheName)) {
    		$cacheData = [];
    		$whereMap = [
    				'type'  => $carType,
    				'bj_id' => $bjid,
    		];
    		$cacheData = self::select('price', 'bjm_price','discount_price','bjm_discount_price','compensate','bjm_percent')
    		->param($whereMap)->orderBy('compensate')
    		->get()->toArray();
    		
    		if(count($cacheData)>0){
    			$tmp = array();
    			foreach($cacheData as $k=>$v){
    				$tmp[$v['compensate']] = $v;
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
