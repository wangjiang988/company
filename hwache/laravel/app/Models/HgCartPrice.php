<?php namespace App\Models;

/**
 * 订单价格表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCartPrice extends Model {

    protected $table = 'hg_cart_price';

    protected $primaryKey = 'cart_id';

    /**
     * 根据查询条件查询订单信息
     * @param $query
     * @param $map
     * @return mixed
     */
    public function scopeGetOrderPrice($query, array $map)
    {
        return $query->where($map)->first();
    }

}
