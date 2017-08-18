<?php namespace App\Models;

/**
 * 自定义字段模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgFields extends Model {

    protected $table = 'hg_fields';
    public $timestamps = false;

    /**
     * 获取保洁列表，包含分页
     * @param array $model
     * @return mixed
     */
    public static function getFieldsList($model) {
        if (config('app.debug')) {
            return self::where('model', $model)
                ->whereNotNull('setting')
                ->lists('setting', 'name');
        }

        return Cache::rememberForever(
            'hg_fields' . $model,
            function() use ($model) {
                return self::where('model', $model)
                    ->whereNotNull('setting')
                    ->lists('setting', 'name');
            }
        );
    }
    // 取得自定义字段对应的名称
     public static function getFieldsName($model) {
        $cacheName = 'fields' . $model;
        if (!Cache::has($cacheName)) {
            $cacheData = self::where('model', $model)->get();
            if (!empty($cacheData) && !config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        }else{
            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;
    }

    //根据ID来获取杂费的名称
    public static function getTitle($other_id)
    {
        return self::where('hg_fields.id',$other_id)->select('title')->first();
    }

}
