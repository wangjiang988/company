<?php namespace App\Models;

/**
 * 用户模型
 */
use Illuminate\Database\Eloquent\Model;
use DB;

class HgBaoXian extends Model {

    protected $table = 'hg_baoxian';

    public $timestamps = false;
    // 取得保险公司信息
    public static function getName($id){
    	return self::where('bx_id','=',$id)->first();
    }

    public static function getBaoxian($dl_id,$dealer_id,$daili_dealer_id)
    {
        return DB::table('hg_dealer_baoxian')
                ->join('hg_baoxian','hg_dealer_baoxian.co_id','=','hg_baoxian.bx_id')
                ->join('hg_daili_dealer','hg_dealer_baoxian.daili_dealer_id','=','hg_daili_dealer.id')
                ->where('hg_daili_dealer.id','<>',3)
                ->where('hg_dealer_baoxian.daili_dealer_id',$daili_dealer_id)
                ->where('m_id',$dl_id)
                ->where('dealer_id',$dealer_id)
                ->get();
	}
}
