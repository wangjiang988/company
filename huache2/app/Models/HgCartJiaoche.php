<?php namespace App\Models;

/**
 * 订单表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCartJiaoche extends Model {

 protected $table = 'hg_cart_jiaoche';
    protected $primaryKey = 'id';

    
    public static  function getJiaocheInfo( array $map)
    {
        return self::where($map)->first();
    }
}
