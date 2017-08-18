<?php namespace App\Models;

/**
 * 报价地区数据模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

use Cache;
class HgBaojiaArea extends Model {

    protected $table = 'hg_baojia_area';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // 报价地区
    public static function getBaojiaArea($bj_id) {

        $area_ids=self::where('bj_id','=',$bj_id)->get()->toArray();
        $area=array();
        $area_names='';
        // include_once(MALL_DATA_PATH.'/area/area.php');
        // 取得所有地区数据
        $all_area=Area::getArea();
        $p='';//省
        $c='';//市
        foreach ($area_ids as $key => $value) {
            if($value['country']==1) return '全国';
            foreach ($all_area as $k => $v) {
                if ($v['area_id']==$value['province']) {
                    $p=$v['area_name'];
                }
                if ($v['area_id']==$value['city']) {
                    if ($v['area_xianpai']==1) {
                        $c=$v['area_name'].'(限牌城市)';
                    }else{
                        $c=$v['area_name'];
                    }  
                }

            }
            
           $area[$p][]=$c;
        }
        return $area;
    }
    // 取得报价地区中有政府置换补贴的地区
    public static function getButieArea($bj_id) {
        $area_ids=self::where('bj_id','=',$bj_id)->get()->toArray();
        $area=array();
        
        // 取得所有地区数据
        $all_area=Area::getArea();
        foreach ($area_ids as $key => $value) {
            foreach ($all_area as $k => $v) {
                if ($v['area_id']==$value['city'] && $v['area_butie']) {
                    $area[]=$v['area_name'];
                }
            }
            
        }
        return $area;
    }
    // 取得报价地区的所有id 
    public static function getAreaIds($bj_id)
    {
         return self::where('bj_id','=',$bj_id)->get()->toArray();

    }
}
