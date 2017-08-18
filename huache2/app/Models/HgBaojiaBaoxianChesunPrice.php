<?php namespace App\Models;

/**
 * 报价车损险数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgBaojiaBaoxianChesunPrice extends Model {

    protected $table = 'hg_baojia_baoxian_chesun_price';

    public $timestamps = false;

    use Common;

    public static function getPrice($bjid, $carType = 1, array
$map = []) {
        $cacheName = 'chesun_price' . $bjid . $carType . serialize($map);

        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
            ];
            if(!empty($map)) {
                $whereMap = array_merge($whereMap, $map);
            }
            $d = self::select('price', 'bjm_price','discount_price','bjm_discount_price')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
                    $cacheData['price'] = floatval($val->price);
                    $cacheData['bjm_price'] = floatval($val->bjm_price);
                    $cacheData['count']     = floatval($val->price);
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

}
