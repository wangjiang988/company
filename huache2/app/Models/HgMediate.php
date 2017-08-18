<?php namespace App\Models;

/**
 * 调解结果模型
 */
use Illuminate\Database\Eloquent\Model;
class HgMediate extends Model {

    protected $table = 'hg_mediate';

    public $timestamps = false;
    
    // 取得订单的调解信息
    public static function getMediate($order_num,$dispute_id='')
    {
    	if($dispute_id ==''){
    		$mediate = self::where('order_num','=',$order_num)->orderBy('createat', 'desc')->first();
    	}else{
    		$mediate = self::where('order_num','=',$order_num)->where('dispute_id','=',$dispute_id)->orderBy('createat', 'desc')->first();
    	}
    	return $mediate;
    }
    // 取得某个会员发送的和解信息
    public static function getHejie($order_num,$dispute_id,$member_id)
    {
    	return self::where('order_num','=',$order_num)->where('dispute_id','=',$dispute_id)->where('member_id','=',$member_id)->pluck('content2');
    }
}
