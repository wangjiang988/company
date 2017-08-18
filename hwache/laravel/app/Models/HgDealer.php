<?php namespace App\Models;

/**
 * 经销商模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgDealer extends Model {

    protected $table = 'hg_dealer';

    public $timestamps = false;
    public static function getDealerInfo($id) {
    	$cacheName = 'Dealer' . $id;
    	if (!Cache::has($cacheName)) {
    		$cacheData = [];
    		$d = self::where('d_id', $id)->get();
    		if (!empty($d)) {
    			foreach ($d as $key => $value) {
    				$cacheData=$value;
    			}
    			
    		}
    	}else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;

    }

}
