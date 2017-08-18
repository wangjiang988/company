<?php namespace App\Models;

/**
 * 车型基本数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgBaojiaField extends Model {

    public $timestamps = false;

    /**
     * 获取该车型本身的数据信息
     * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
     * @param $bjid     报价ID
     * @return array    以name为key，value为数据的数组
     */
    public static function getCarFieldsByBjid($bjid) {
        $cacheName = 'CarFieldsByBjid' . $bjid;
        if (!Cache::has($cacheName)) {
            $cacheData = [];
            $d = self::where('bj_id', $bjid)->lists('value', 'name');
            if (!empty($d)) {
                foreach ($d as $k => $v) {
                    $val = unserialize($v);
                    $cacheData[$k] = is_numeric($val) ? intval($val) : $val;
                }
                if (!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }

}
