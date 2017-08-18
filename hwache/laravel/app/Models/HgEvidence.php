<?php namespace App\Models;

/**
 * 争议模型
 */
use Illuminate\Database\Eloquent\Model;
class HgEvidence extends Model {

    protected $table = 'hg_evidence';

    public $timestamps = false;
    
    // 取得订单的争议信息
    public static function getEvidence($order_num,$dispute_id,$defend_id=0)
    {
    		
    	$seller = self::where('order_num','=',$order_num)
    					->where('dispute_id','=',$dispute_id)
    					->where('defend_id','=',$defend_id)
    					->get();
    	
    	return $seller;
    }
}
