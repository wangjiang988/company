<?php namespace App\Models;

/**
 * 赠品模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
class HgBaojiaZengpin extends Model { 
    protected $table = 'hg_baojia_zengpin';
    public $timestamps = false;
    public static function getBaojiaZengpin($bj_id) {
    	$cacheName = 'baojiaZengpin' . $bj_id;
        // Cache::forget($cacheName);
    	$map = array('bj_id' => $bj_id, );
        if(!Cache::has($cacheName)) {
        	/*$cacheData = self::where('bj_id','=',$bj_id)
                ->get();*/
                $cacheData = self::leftJoin(
                    'hg_zengpin as bp',
                    'hg_baojia_zengpin.zengpin_id',
                    '=',
                    'bp.id')->where('hg_baojia_zengpin.bj_id','=',$bj_id)
                    ->orderBy('is_install', 'desc')
                    ->select('title','num','zp_title','price', 'is_install')
                ->get();
        }else{

        	$cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    } 
    // 取得一条赠品信息
    public static function getOneZengpin($bj_id,$id)
    {
        $cacheName = 'baojiaZengpin' . $bj_id.$id;
        
        if(!Cache::has($cacheName)) {
            $cacheData = self::leftJoin(
                    'hg_zengpin as bp',
                    'hg_baojia_zengpin.zengpin_id',
                    '=',
                    'bp.id')->where('hg_baojia_zengpin.bj_id','=',$bj_id)->where('hg_baojia_zengpin.zengpin_id','=',$id)
                ->first();
        }else{

            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }

    public static function getBaojiaGifts($bj_id) {
        $zp_titles = self::select(DB::raw('GROUP_CONCAT(car_zp.title) as title'))
            ->leftJoin('hg_zengpin as zp','zp.id','=','zengpin_id')
            ->where('bj_id','=',$bj_id)
            ->first();
        return $zp_titles->title;
    }
}
