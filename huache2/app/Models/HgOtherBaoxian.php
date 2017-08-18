<?php namespace App\Models;

/**
 * 其他商业保险模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;
use Cache;

class HgOtherBaoxian extends Model {

    protected $table = 'hg_other_baoxian';
    protected $primaryKey = 'id';

    // 其他商业保险列表
    public static function getOtherBaoxian()
    {
        $cacheName = 'otherbaoxian';
        if(!Cache::has($cacheName)) {
            $cacheData = self::get()->toArray();
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        }else{

            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }

}
