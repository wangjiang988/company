<?php namespace App\Models;

/**
 * 用户选择的赠品模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgUserZengpin extends Model {

    protected $table = 'hg_user_zengpin';

    protected $primaryKey = 'id';

    public $timestamps = false;
    
    // 取得订单的赠品
    public static function getZengpin($order_num)
    {
    	return self::where('order_num','=',$order_num)->get();

    }
}
