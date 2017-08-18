<?php namespace App\Models;

/**
 * 文件资料分类模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgFileCate extends Model {
	 use Common;
    protected $table = 'hg_file_cate';

    public $timestamps = false;
    // 将文件分类缓存起来
    public static function getCate($regular=1) {
    	$cacheName = 'filecate'.$regular;
    	
        if(!Cache::has($cacheName)) {
        	$cacheData = self::where('regular','=',$regular)->get()->toArray();
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        }else{

        	$cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    } 
}
