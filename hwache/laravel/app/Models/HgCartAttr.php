<?php namespace App\Models;

/**
 * 订单属性表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCartAttr extends Model {

    protected $table = 'hg_cart_attr';

    protected $primaryKey = 'cart_id';

    /**
     * 根据查询条件查询订单信息
     * @param $query
     * @param $map
     * @return mixed
     */
    public function scopeGetOrderAttr($query, array $map)
    {
        return $query->where($map)->first();
    }

}
