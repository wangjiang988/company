<?php namespace App\Models;

/**
 * 文件资料模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgFiles extends Model {
	 use Common;
    protected $table = 'hg_file';

    public $timestamps = false;
    // 取得一条文件信息
    public static function getFile($file_id) {
    	$cacheName = 'file'.$file_id;
        if(!Cache::has($cacheName)) {
        	$cacheData = self::leftJoin(
                    'hg_file_cate as bp',
                    'hg_file.cate_id',
                    '=',
                    'bp.cate_id')->where('hg_file.file_id','=',$file_id)
                ->first();
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        }else{

        	$cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    } 
}
