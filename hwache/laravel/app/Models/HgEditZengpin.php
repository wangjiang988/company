<?php namespace App\Models;

/**
 * 代理修改过的礼品或服务模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgEditZengpin extends Model {

    protected $table = 'hg_edit_zengpin';

    public $timestamps = false;

    // 取得某个订单代理修改过的赠品
    public static function getEditZengpin($order_num)
    {
    	$cacheName = 'editzengpin' . $order_num;
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
