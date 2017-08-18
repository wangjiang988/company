<?php namespace App\Models;

/**
 * 城市地区坐标模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

use App\Com\Hwache\Amap\Jsapi;

class HgCityPoint extends Model {

    protected $table = 'hg_city_point';

    public $timestamps = false;

    /**
     * 获取城市对应的中心点坐标
     * @param $id
     * @return mixed
     */
    public static function getAreaPiontByCode($id) {
        if (config('app.debug')) {
            return self::where('shi', $id)->pluck('point');
        }

        return Cache::rememberForever('City' . $id, function() use ($id){
            return self::where('shi', $id)->pluck('point');
        });
    }

    /**
     * 根据云图API获取指定范围内的经销商列表
     * @param  array  $param 查询条件数组
     * @return array         获取到的数据数组
     */
    public static function getDealerListByMap(array $param) {
        return Cache::remember(
            'cacheMapData' . $param['center'], // 缓存名称
            config('app.cache_time') * 12, // 缓存时间
            function() use ($param) {
                /*
                | 从高德服务器读取信息
                | 加载地图类
                */
                $map = new Jsapi;
                return $mapData = $map->getAround($param);
            } // 回调函数
        );
    }

    /**
     * 从返回的地图数据中提取商家ID
     * @param  array  $param 返回的地图数组数据
     * @return array         返回提取的商家ID数组
     */
    public static function getDealerIdList(array $param) {
        $r = [];
        if ($param['status'] == 1 && $param['count'] > 0) {
            foreach ($param['datas'] as $key => $value) {
                $r[] = $value['id'];
            }
        }
        return $r;
    }

    /**
     * 从返回的地图数据中提取商家ID
     * @param  array  $param 返回的地图数组数据
     * @return array         返回提取的商家ID数组
     *                       key:为经销商ID,value:为经销商到中心店的距离
     */
    public static function getDealerIdDistance(array $param) {
        $r = [];
        if ($param['status'] == 1 && $param['count'] > 0) {
            foreach ($param['datas'] as $key => $value) {
                $r[$value['id']] = $value['_distance'];
            }
        }
        return $r;
    }


}
