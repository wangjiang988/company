<?php namespace App\Models;

/**
 * 车型基本数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgCarInfo extends Model {

    protected $table = 'hg_car_info';

    public $timestamps = false;

    /**
     * 获取该车型本身的数据信息
     * 比如指导价,所包含的颜色,所包含的内饰颜色,座位数,国别,补贴等
     * @param $query    系统本身查询参数，无需额外处理
     * @param $carBrand 车型ID
     * @return array    以name为key，value为数据的数组
     */
    public static function getCarmodelFields($carBrand) {
        $cacheName = 'HgCarInfoGetCarmodelFields' . $carBrand;
        if(!Cache::has($cacheName)) {
            $cacheData = [];
            $fields = ['name', 'value'];
            $d = self::select($fields)
                ->whereGcId($carBrand)
                ->whereModel('carmodel')
                ->get();
            // 数据反序列化
            foreach ($d as $v) {
                $val = unserialize($v->value);
                $cacheData[$v->name] = is_numeric($val) ? intval($val) : $val;
            }
            if(!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        } else {
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }

    public static function getCarmodelPrices($brand)
    {
        $result = self::leftjoin('goods_class', 'goods_class.gc_id', '=', 'hg_car_info.gc_id')
            ->where('hg_car_info.name', 'zhidaojia')
            ->where('hg_car_info.gc_id' ,$brand)
            ->select('goods_class.vehicle_model', 'hg_car_info.value')
            ->first();
        if ($result->count()) {
            $result->value = unserialize($result->value);
            return $result;
        }
    }

    /**
     * @param $bj_id
     * @param $brand_id
     * 取出单车的基本信息,内饰颜色,...
     * @return array
     */
    public static function getInteriorColor($bj_id,$brand_id)
    {
        $result = HgCarInfo::getCarmodelFields($brand_id);
        $temp = HgBaojiaField::getCarFieldsByBjid($bj_id);
        $result['nside_color'] = $result['interior_color'][$temp['interior_color']];
        return $result;
    }

}
