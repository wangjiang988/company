<?php namespace App\Models;

/**
 * 申辩模型
 */
use Illuminate\Database\Eloquent\Model;
class HgDefend extends Model {

    protected $table = 'hg_defend';

    public $timestamps = false;
    
    // 取得订单争议的申辩信息
    public static function getDefend($order_num,$dispute_id)
    {
    	$defend = self::where('order_num','=',$order_num)->where('dispute_id','=',$dispute_id)->first();
    	return $defend;
    }

}
