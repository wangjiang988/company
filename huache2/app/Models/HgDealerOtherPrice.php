<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 杂费模型
 */

class HgDealerOtherPrice extends Model
{
    protected $table = 'hg_dealer_other_price';
    public $timestamps = false;

    //获取经销商杂费部分
    public static function getOther($daili_id,$dealer_id)
    {
        $data = self::leftjoin('hg_fields','hg_dealer_other_price.other_id','=','hg_fields.id')
            ->join('hg_daili_dealer','hg_dealer_other_price.daili_dealer_id','=','hg_daili_dealer.id')
            ->where('hg_daili_dealer.dl_status','<>',3)
            ->where('daili_id',$daili_id)
            ->where('dealer_id',$dealer_id)
            ->select('hg_dealer_other_price.*','hg_fields.title')
            ->get();
        return $data->toArray();
    }

    //判断是否有重复
    public static function judge($other_id,$dealer_id,$dl_id,$daili_dealer_id)
    {
        return self::where('other_id',$other_id)
                      ->where('dealer_id',$dealer_id)
                      ->where('daili_id',$dl_id)
                      ->where('daili_dealer_id',$daili_dealer_id)
                      ->first();
    }
}
