<?php namespace App\Models;

/**
 * 车型模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgGoodsClass extends Model {

    protected $table = 'goods_class';

    protected $primaryKey = 'gc_id';

    public $timestamps = false;
    // 取得车型的基本信息
    public static function getCarBase($carBrand) {
    	$cacheName = 'HgGoodsClassBase' . $carBrand;
    	if(!Cache::has($cacheName)) {
    		$cacheData = self::where('gc_id', '=', $carBrand)
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
