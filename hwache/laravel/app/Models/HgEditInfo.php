<?php namespace App\Models;

/**
 * 修改的车辆信息模型
 *
 * 
 */

use Illuminate\Database\Eloquent\Model;

use Cache;

class HgEditInfo extends Model {

    protected $table = 'hg_editinfo';

    public $timestamps = false;
    // 取得订单的修改过的信息
    public static function getEditInfo($order_num,$status=false)
    {
        if ($status) {
            return self::where('order_num','=',$order_num)->where('status','=',$status)->orderBy('createat','desc')->first();
        }else{
            return self::where('order_num','=',$order_num)->orderBy('createat','desc')->first();
        } 
    }
    public static function insertNewModifyLog($param){
    	return self::insert($param);
    }
    
    
}
