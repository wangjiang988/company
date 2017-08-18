<?php namespace App\Models;

/**
 * 用户选择的选装件模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgUserXzj extends Model {

    protected $table = 'hg_user_xzj';

    protected $primaryKey = 'id';

    public $timestamps = false;
    // 判断用户是否已经添加了这个选装件
    public static function isInsert($id,$order_num)
    {
    	return self::where('id','=',$id)->where('order_num','=',$order_num)->first();
    }
    // 取得订单的选装件
    public static function getUserXzj($order_num)
    {
    	return self::where('order_num','=',$order_num)->get();
    }
    // 取得订单选装件的详细信息
    public static function getAllInfo($order_num,$is_yc=NULL)
    {
    	$param = array('order_num'=>$order_num);
    	if($is_yc != NULL){
    		$param['is_yc'] = $is_yc;
    	}
        return self::select(
        		    'id',
                    'xzj_name',
                    'xzj_model',
                    'guide_price',
                    'fee',
                    'discount_price',
                    'num as select_num',
                    'price',
                    'xzj_brand',
                    'is_yc',
                    'order_num',
                    'createtime'                   
            )
        ->where($param)->get();

    }
    // 取得原厂或非原厂的数量
    public static function getCount($order_num,$is_yc=1)
    {
        return self::where('order_num','=',$order_num)->where('is_yc','=',$is_yc)->count();

    }
}
