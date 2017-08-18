<?php namespace App\Models;

/**
 * 代理保险模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgDealerBaoXian extends Model {

    protected $table = 'hg_dealer_baoxian';

    public $timestamps = false;
    // 取得代理对应的保险
    public static function getDealerBaoXian($m_id,$bx_id) {
    	return self::where('m_id','=',$m_id)->where('co_id','=',$bx_id)->first();

    }
    // 只取对应保险的id
    public static function getDealerBaoXianId($m_id,$bx_id) {
        return self::where('m_id','=',$m_id)->where('co_id','=',$bx_id)->pluck('id');

    }
    public static function getDealerBaoXianAllInfoList($m_id,$dealer_id) {
    	return self::join('hg_baoxian','hg_dealer_baoxian.co_id','=','hg_baoxian.bx_id')
                    ->join('hg_daili_dealer','hg_dealer_baoxian.daili_dealer_id','=','hg_daili_dealer.id')
                    ->where('hg_daili_dealer.dl_status','<>',3)
			    	->where('hg_dealer_baoxian.m_id','=',$m_id)
                    ->where('hg_dealer_baoxian.dealer_id',$dealer_id)
			    	->select('hg_dealer_baoxian.*','hg_baoxian.*')
			    	->get();
    
    }

}
