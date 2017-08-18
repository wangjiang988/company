<?php
/**
 * Created by PhpStorm.
 * User: panwenbin
 * Date: 2016/7/8
 * Time: 16:07
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;

class HgGoodsClassStaple extends  Model
{
    protected $table = 'goods_class_staple';
    public $timestamps = false;


    //获取车辆信息
    public static function getModels($daili_id, $dealer_id)
    {
        return self::join('goods_class', 'goods_class_staple.gc_id_2', '=', 'goods_class.gc_id')
            ->join('hg_car_info', 'goods_class_staple.gc_id_3', '=', 'hg_car_info.gc_id')
            ->join('hg_daili_dealer','goods_class_staple.daili_dealer_id','=','hg_daili_dealer.id')
            ->where('hg_car_info.name', 'zhidaojia')
            ->where('hg_daili_dealer.dl_status','<>',3)
            ->where('goods_class_staple.member_id', $daili_id)
            ->where('goods_class_staple.dealer_id', $dealer_id)
            ->where('goods_class_staple.status', 0)
            ->orderBy('goods_class_staple.staple_id', 'desc')
            ->select('goods_class_staple.dealer_id', 'goods_class_staple.staple_id', 'goods_class_staple.staple_name',
                'goods_class_staple.gc_id_3', 'goods_class.gc_name', 'hg_car_info.value')
            ->get();
    }

   public static function getCarSearch($daili_id,$dealer_id,$gc_name)
   {
       return self::join('goods_class', 'goods_class_staple.gc_id_2', '=', 'goods_class.gc_id')
           ->join('hg_car_info', 'goods_class_staple.gc_id_3', '=', 'hg_car_info.gc_id')
           ->where('hg_car_info.name', 'zhidaojia')
           ->where('goods_class_staple.member_id', $daili_id)
           ->where('goods_class_staple.dealer_id', $dealer_id)
           ->where('goods_class.gc_name',$gc_name)
           ->where('goods_class_staple.status', 0)
           ->orderBy('goods_class_staple.staple_id', 'desc')
           ->select('goods_class_staple.dealer_id', 'goods_class_staple.staple_id', 'goods_class_staple.staple_name',
               'goods_class_staple.gc_id_3', 'goods_class.gc_name', 'hg_car_info.value')
           ->get();
   }
    //获取单车车辆信息
    public static function getOneModels($daili_id, $dealer_id, $staple_id)
    {
        return self::leftjoin('goods_class', 'goods_class_staple.gc_id_2', '=', 'goods_class.gc_id')
            ->leftjoin('hg_car_info', 'goods_class_staple.gc_id_3', '=', 'hg_car_info.gc_id')
            ->where('goods_class_staple.member_id', $daili_id)
            ->where('goods_class_staple.dealer_id', $dealer_id)
            ->where('goods_class_staple.staple_id', $staple_id)
            ->where('goods_class_staple.status', 0)
            ->select('goods_class_staple.staple_id', 'goods_class_staple.staple_name', 'goods_class_staple.gc_id_3',
                'goods_class.gc_name', 'goods_class.gc_id', 'goods_class.gc_parent_id', 'hg_car_info.value')
            ->first();
    }

    /**
     * 列出代理商的所有车型,搜索区域
     * @param  $dl_id 代理ID
     * @param  $dealer_id 经销商ID
     */
    public static function getCarseries($dealer_id, $dl_id)
    {
        return DB::table('goods_class_staple')
            ->leftjoin('goods_class', 'goods_class_staple.gc_id_2', '=', 'goods_class.gc_id')
            ->join('hg_daili_dealer','goods_class_staple.daili_dealer_id','=','hg_daili_dealer.id')
            ->where('hg_daili_dealer.dl_status','<>',3)
            ->where('goods_class_staple.dealer_id', $dealer_id)
            ->where('goods_class_staple.member_id', $dl_id)
            ->where('goods_class_staple.status', 0)
            ->groupBy('goods_class.gc_name')
            ->select('goods_class_staple.staple_id', 'goods_class.gc_name')
            ->get();
    }

    /**
     * 删除车型
     * @param  $dl_id 代理ID
     * @param  $staple_id 车型ID
     */
    public static function delStaple($dali_id, $staple_id)
    {
        $row = DB::table('goods_class_staple')
            ->where('staple_id', $staple_id)
            ->where('member_id',$dali_id)
            ->first();
        $exists_order = HgBaojia::where('daili_dealer_id',$row->daili_dealer_id)
            ->where('brand_id',$row->gc_id_3)
            ->count();
        if ($exists_order) {
            return array('error_code' => 1, 'msg' => '删除失败');
        } else {
            DB::transaction(function () use ($row, $staple_id) {

                // 常用车型表改状态
                $e1 = DB::table('goods_class_staple')
                    ->where('staple_id', $staple_id)
                    ->update(['status' => '2']);
                //hg_xzj_daili 改状态
                $e2 = DB::table('hg_xzj_daili')
                    ->where('daili_dealer_id', $row->daili_dealer_id)
                    ->where('car_brand', $row->gc_id_3)
                    ->where('xzj_yc', 0)
                    ->where('xzj_front', 0)
                    ->update(['status' => 2]);
            });
            return array('error_code' => 0, 'msg' => '删除成功');
        }
    }

    public function goodclass()
    {
        return $this->hasOne(HgGoodsClass::class,'gc_id','gc_id_3');
    }
}