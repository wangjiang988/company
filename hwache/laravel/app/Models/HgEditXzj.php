<?php namespace App\Models;

/**
 * 代理修改过的选装件模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgEditXzj extends Model {

    protected $table = 'hg_edit_xzj';

    public $timestamps = false;
    //取得订单的代理修改过的选装件
    public static function getEditXzj($order_num) {
        $cacheName = 'editxzj' . $order_num;
        if (!Cache::has($cacheName)) {
           
            $cacheData= self::where('order_num', $order_num)->get();
            if (!empty($cacheData)) {
                
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
