<?php namespace App\Models;

/**
 * 系统默认金额数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgBaojiaBaoxianZiranPrice extends Model {

    protected $table = 'hg_baojia_baoxian_ziran_price';

    public $timestamps = false;

    use Common;

    public static function getPrice($bjid, $carType = 1, array $map =
    []) {
        $cacheName = 'ziran_price' . $bjid . $carType . serialize($map);
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $whereMap = [
                'type'  => $carType,
                'bj_id' => $bjid,
            ];
            if(!empty($map)) {
                $whereMap = array_merge($whereMap, $map);
            }
            $d = self::select('price','discount_price')
                       ->param($whereMap)
                       ->get();
            if(!$d->isEmpty()) {
                foreach ($d as $val) {
                    $cacheData['price'] = $cacheData['count'] = floatval($val->price);
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

}
