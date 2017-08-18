<?php namespace App\Models;

/**
 * 订单表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCart extends Model {

    protected $table = 'hg_cart';
    protected $primaryKey = 'id';

    /**
     * 根据查询条件查询订单信息
     * @param $query
     * @param $map
     * @return mixed
     */
    public function scopeGetOrder($query, array $map)
    {
        return $query->where($map)->first();
    }

    /**
     * 根据订单编号查询的号订单主键ID
     *
     * @param $query
     * @param $order_num
     * @return mixed
     */
    public function scopeGetIdByOrdernum($query, $order_num)
    {
        return $query->where('order_num', $order_num)->value('id');
    }

    /**
     * 检测订单是否存在
     *
     * @param $query
     * @param $order_num
     * @return mixed
     */
    public function scopeGetOrderStatus($query, $order_num)
    {
        return $query->where('order_num', $order_num)
                     ->first();
    }

    /**
     * 获取该订单全部信息
     *
     * @param $query
     * @param $order_num
     * @param null $user_id
     * @param bool $is_buy
     * @return
     */
    public function scopeGetOrderByUser($query, $order_num, $user_id = null, $is_buy = false)
    {
        $map = ['order_num' => $order_num];
        if (! is_null($user_id)) {
            if ($is_buy) {
                $map['buy_id'] = $user_id;
            } else {
                $map['seller_id'] = $user_id;
            }
        }

        return $query->where($map)->first();
    }
    // 取得订单的状态
    public static function getOrderStat($order_num)
    {
        return self::where('order_num','=',$order_num)->select('cart_status', 'cart_sub_status','end_user_status','end_pdi_status')->first();
    }
    // 更新订单交车时限和交车通知时限
    public static function upJiaocheTime($order_num,$jiaoche_time,$jiaoche_notice_time)
    {
        return self::where('order_num','=',$order_num)->update(['jiaoche_time' => $jiaoche_time,'jiaoche_notice_time'=>$jiaoche_notice_time]);

    }
    /*
        *客户是本地还是外地,客户承诺上牌地与经销商地址是否同城来判断
        *返回 true 为本地，false 为异地
    */
    public static function isLocal($order_num)
    {
        $area=self::leftJoin('hg_dealer','hg_cart.dealer_id','=','hg_dealer.d_id')->select('hg_cart.dealer_id','hg_cart.shangpai_area','hg_cart.order_num','hg_dealer.d_id','hg_dealer.d_shi')->where('hg_cart.order_num','=',$order_num)->first();
        return $area['shangpai_area']==$area['d_shi'];
    }
    // 确认超时,自动更改订单状态并跳转到响应页面
    public static function autoCommit($status,$route,$order_num=null)
    {
        $id=self::where('order_num','=',$order_num)->update(['cart_sub_status' => $status]);
        return redirect(route($route, ['order_num' =>$order_num ]));
    }
}
