<?php namespace App\Models;

/**
 * 服务专员模型
 *$agent_id   代理id
 *$dealer_id    经销商id
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgWaiter extends Model {
	 use Common;
    protected $table = 'hg_waiter';

    public $timestamps = false;
    // 取得服务专员
    public static function getWaiter($agent_id,$dealer_id) {
    	$cacheName = 'waiter' . $agent_id.$dealer_id;
    	// Cache::forget($cacheName);
        if(!Cache::has($cacheName)) {
        	$cacheData = self::where('agent_id','=',$agent_id)->where('dealer_id','=',$dealer_id)->first();

        }else{

        	$cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    } 
    public static function getAllWaiter($agent_id,$dealer_id) {
    	$cacheName = 'waiterall' . $agent_id.$dealer_id;
    	// Cache::forget($cacheName);
    	if(!Cache::has($cacheName)) {
    		$cacheData = self::where('agent_id','=',$agent_id)->where('dealer_id','=',$dealer_id)->get()->toArray();
    
    	}else{
    
    		$cacheData = Cache::get($cacheName);
    	}
    	return $cacheData;
    }
    
}
