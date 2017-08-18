<?php namespace App\Models;

/**
 * 订单价格表模型
 *
 * @author 技安 <php360@qq.com>
 */

use Illuminate\Database\Eloquent\Model;

class HgCartLog extends Model
{

    protected $table = 'hg_cart_log';

    protected $primaryKey = 'id';

    protected $guarded = [];


    /**
     * @param $order_id
     * 根据订单id 读取几个状态下的时间
     * @data 20170718
     * @return array
     */
    public static function orderTimeList($order_id)
    {
        $result = self::where('order_id', $order_id)
            ->whereIn('order_state', [2011, 2012,301, 404, 502, 503])
            ->get();
        $times = [];
        $result->each(function ($item) use (&$times) {
            $state = $item->order_state;
            if ($state == 2011 || $state==2012) {
                $times['reserva_time'] = $item->created_at;
            }
            if ($state == 301) {
                $times['security_time'] = $item->created_at;
            }
            if ($state == 404) {
                $times['delivery_time'] = $item->created_at;
            }
            if ($state == 502) {
                $times['pickup_time'] = $item->created_at;
            }
            if ($state == 503) {
                $times['refund_time'] = $item->created_at;
            }
        });
        return $times;
    }

    public function getProcessTime($order_id)
    {
        $sincerity = [2011,2012];
        $feedback = [2021,2031,2032];
        $security = [301,302];
        $times = [];
        $results = self::whereIn('order_state',array_merge($sincerity,$feedback,$security))
                  ->where('order_id',$order_id)
                  ->latest()
                  ->get();
        foreach ($results as $result) {
            if (in_array($result->order_state,$sincerity)) {
                $times['since'] = $result->created_at;
            }
            if (in_array($result->order_state,$feedback)) {
                $times['feed'] = $result->created_at;
            }
            if (in_array($result->order_state,$security)) {
                $times['secur'] = $result->created_at;
            }
        }
        return $times;
    }

}
