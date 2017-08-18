<?php namespace App\Models;

/**
 * 争议模型
 */
use Illuminate\Database\Eloquent\Model;
class HgDispute extends Model {

    protected $table = 'hg_dispute';

    public $timestamps = false;
    
    // 取得订单的争议信息
    public static function getDispute($order_num,$member_id)
    {
    	$dispute = self::where('order_num','=',$order_num)->where('member_id','=',$member_id)->orderBy('createat', 'desc')->first();
    	return $dispute;
    }
    
    // 根据争议ID取得订单的争议信息
    public static function getDisputeById($id)
    {
    	$dispute = self::where('id','=',$id)->first();
    	return $dispute;
    }
    
    // 检测争议id是否存在
    public static function isdispute($dispute_id)
    {
    	return self::where('id','=',$dispute_id)->first();
    }
}
