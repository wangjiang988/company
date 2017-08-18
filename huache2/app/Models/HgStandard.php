<?php namespace App\Models;

/**
 *  刷卡相关模型
 */
use Illuminate\Database\Eloquent\Model;

class HgStandard extends Model {

    protected $table = 'hg_dealer_standard';
    public $timestamps = false;


    public static function getNorm($sess_id,$id)
    {
       return self::leftjoin('hg_daili_dealer','hg_dealer_standard.daili_id','=','hg_daili_dealer.id')
                   ->where('hg_dealer_standard.dealer_id',$sess_id)
                   ->where('hg_daili_dealer.dl_status','<>',3)
                   ->where('hg_dealer_standard.daili_id',$id)
                   ->first();
    }

}