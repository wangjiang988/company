<?php namespace App\Models;

/**
 * 订单表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCartJiaocheExt extends Model {

 	protected $table = 'hg_cart_jiaoche_extinfo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    
    
    public static  function getJiaocheExtInfoList( array $map)
    {
    	return self::where($map)->orderBy('id','asc')->get();
    }
}
