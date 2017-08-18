<?php namespace App\Models;

/**
 * 报价选装件模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
class HgBaojiaXzj extends Model
{
    use Common;
    protected $table = 'hg_baojia_xzj';

    public $timestamps = false;

    public static function getBaojiaXzj($bj_id)
    {
        $cacheName = 'baojiaxzj' . $bj_id;
        // Cache::forget($cacheName);
        $map = array('bj_id' => $bj_id,);
        if (!Cache::has($cacheName)) {
            $cacheData = self::leftJoin('hg_xzj_list as bp', 'hg_baojia_xzj.xzj_id', '=', 'bp.id')
                ->leftJoin('hg_xzj_daili', 'hg_xzj_daili.id', '=', 'hg_baojia_xzj.m_id')
                ->select('hg_baojia_xzj.id',
                    'hg_baojia_xzj.is_install',
                    'hg_baojia_xzj.bj_id',
                    'hg_baojia_xzj.xzj_id',
                    'hg_baojia_xzj.m_id',
                    'hg_baojia_xzj.num',
                    'hg_baojia_xzj.fee',
                    'bp.xzj_guide_price',
                    'bp.car_brand',
                    'bp.xzj_title',
                    'bp.xzj_yc',
                    'bp.xzj_front',
                    'bp.xzj_brand',
                    'bp.xzj_model',
                    'bp.xzj_max_num',
                    'bp.cs_serial',
                    'bp.xzj_notice',
                    'hg_baojia_xzj.beizhu',
                    'hg_baojia_xzj.fee',
                    'hg_xzj_daili.xzj_has_num',
                    'hg_xzj_daili.xzj_cs_serial')
                ->where('hg_baojia_xzj.bj_id', '=', $bj_id)
                //->param(['hg_baojia_xzj.is_install'=>1,'hg_baojia_xzj.bj_id'=>$bj_id])
                ->get();
            if ($cacheData && !config('app.debug'))
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
        } else {

            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }


    /**
     * 读取前后装选装件(1前装,0后装)
     * @param $bj_id
     * @param $type
     * @return array
     */
    public static function getBaojiaxzxzj($bj_id, $type = 0, $status = null)
    {
        if ( ! self::getBaojiaXzj($bj_id)->isEmpty()) {
            if ($status) {
                return self::getBaojiaXzj($bj_id)->where('xzj_max_num', '<>', 0)
                    ->where('xzj_has_num', '<>', 0)->count();
            } else {
                return self::getBaojiaXzj($bj_id)
                    ->where('xzj_front', $type);
            }
        } else {
            return collect();
        }
    }

    /**
     * @param $bj_id   报价详情
     * @return mixed
     */
    public static function getBaojiaXzjInfo($bj_id)
    {
        $cacheName = 'SearchBaoJiaXZJ' . $bj_id;
        // Cache::forget($cacheName);
        if (!Cache::has($cacheName)) {
            $col = "GROUP_CONCAT(car_bp.xzj_title) as xzj_titles,sum((car_bp.xzj_guide_price + car_hg_baojia_xzj.fee) * car_hg_baojia_xzj.num) as xzj_totalPrice";
            $resultData = self::leftJoin('hg_xzj_list as bp', 'hg_baojia_xzj.xzj_id', '=', 'bp.id')
                ->leftJoin('hg_xzj_daili', 'hg_xzj_daili.id', '=', 'hg_baojia_xzj.m_id')
                ->select(DB::raw($col))
                ->param(['hg_baojia_xzj.is_install' => 1, 'hg_baojia_xzj.bj_id' => $bj_id])
                ->groupBy('hg_baojia_xzj.bj_id')
                ->first();
            if ($resultData) {
                Cache::put($cacheName, $resultData, config('app.cache_time'));
            }
        } else {
            $resultData = Cache::get($cacheName);
        }
        return $resultData;
    }

    public static function getBaojiaHzXzj($bj_id)
    {
        $map['xjz_list.xzj_front'] = 0;
        $map['bj_id'] = $bj_id;
        $isHzXzj = self::leftJoin('hg_xzj_list as xjz_list', 'xjz_list.id', '=', 'xzj_id')
            ->param($map)
            ->count();
        return $isHzXzj;
    }

    /**
     * @param $id
     * @return array
     * 选装件分类
     */
    public static function getXzjType($id)
    {
        $datas = self::getBaojiaXzj($id)->toArray();
        if (count($datas) > 0) {
            $xzj['rpo_sum'] = 0;
            foreach ($datas as $key => $value) {
                // 1.现车（前装）(订单,非现车不显示)
                if ($value['xzj_front'] == 1) {
                    //$xzj['rpo_sum'] += $value['xzj_guide_price'] * $value['num'];
                    $xzj['rpo_sum'] += $value['fee'];
                    $xzj['rpo'][]   = $value;
                } else {
                    $xzj['rpo_sum'] += $value['fee'];
                    $xzj['rear'][] = $value;
                }
            }
        } else {
            $xzj = array();
        }
        return $xzj;
    }


    public static function getNonXzj($order_id)
    {
        $xzj = HgOrder::find($order_id)->orderAttr->non_xzj_list;
        $num = explode(',', $xzj);
        if (count($num)) {
            return HgDailiDealer::getOrderOption($num);
        }
        return [];
    }
}
