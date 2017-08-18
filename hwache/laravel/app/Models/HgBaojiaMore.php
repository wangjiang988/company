<?php namespace App\Models;

/**
 * 报价扩展信息
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgBaojiaMore extends Model {
    protected $table = 'hg_baojia_more';
    public $timestamps = false;

    /**
     * 获取报价的数据信息
     * 比如上牌需要的证件资料，补贴发放方式等
     * @param $bjid     报价ID
     * @return array    以name为key，value为数据的数组
     */
    public static function getBaojiaMove($bjid) {
        $cacheName = 'BaojiaMore' . $bjid;
        if (!Cache::has($cacheName)) {
            $cacheData = [];
            $d = self::where('bj_id', $bjid)->get();
            if (!empty($d)) {
                foreach ($d as $k => $v) {
                    $cacheData[$v['model']] = unserialize($v['serialize_data']);
                }
                if (!config('app.debug')) {
                    Cache::put($cacheName, $cacheData, config('app.cache_time'));
                }
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    // 报价上牌所需的资料
    public static function getShangPaiData($bjid,$type=0)
    {
        $cacheName = 'shangpaidata' . $bjid.$type;
        if (!Cache::has($cacheName)) {
            $cacheData = '';
            $d = self::where('bj_id', $bjid)->where('model','=','shangpai')->first();
            if (!empty($d)) {
                $modeldate=unserialize($d['serialize_data']);
                if ($type==0) {
                    $cacheData=$modeldate['gr'];
                }elseif ($type==1) {
                    $cacheData=$modeldate['gs'];
                }
            }
            
        }else{
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    // 报价上临牌所需资料
    public static function getLinPaiData($bjid,$type=0)
    {
        $cacheName = 'linpaidata' . $bjid.$type;
        if (!Cache::has($cacheName)) {
            $cacheData = '';
            $d = self::where('bj_id', $bjid)->where('model','=','linpai')->first();
            if (!empty($d)) {
                $modeldate=unserialize($d['serialize_data']);
                if ($type==0) {
                    $cacheData=$modeldate['gr'];
                }elseif ($type==1) {
                    $cacheData=$modeldate['gs'];
                    
                }
            }
            
        }else{
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
    // 报价投保所需资料
    public static function getBaoXianData($bjid,$type=0)
    {
        $cacheName = 'toubaodata' . $bjid.$type;
        if (!Cache::has($cacheName)) {
            $cacheData = '';
            $d = self::where('bj_id', $bjid)->where('model','=','baoxian')->first();
            if (!empty($d)) {
                $modeldate=unserialize($d['serialize_data']);
                if ($type==0) {
                    $cacheData=$modeldate['gr'];
                }elseif ($type==1) {
                    $cacheData=$modeldate['gs'];
                }
            }
            
        }else{
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }
}
