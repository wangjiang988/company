<?php namespace App\Models;

/**
 * 报价扩展信息
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgBaojiaPrice extends Model {

    protected $table = 'hg_baojia_price';

    public $timestamps = false;

    /**
     * 获取报价的数据信息
     * 比如上牌需要的证件资料，补贴发放方式等
     * @param $id     报价ID
     * @return object
     */
    public static function getBaojiaPrice($id) {
        $cacheName = 'BaojiaPrice'.$id;
        if (!Cache::has($cacheName)) {
            $cacheData = self::where('bj_id', $id)->first();
            if (!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }

}
