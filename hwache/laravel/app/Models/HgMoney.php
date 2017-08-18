<?php namespace App\Models;

/**
 * 系统默认金额数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgMoney extends Model {

    protected $table = 'hg_money';

    public $timestamps = false;


    /**
     * 获取城市对应的中心点坐标
     * @return mixed
     */
    public static function getMoneyList() {
        $cacheName = 'defaultmoney';
        if(!Cache::has($cacheName)) {
            $cacheDate = self::get()->lists('value', 'name');
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        } else {
            $cacheDate = Cache::get($cacheName);
        }
        return $cacheDate;
    }

}
