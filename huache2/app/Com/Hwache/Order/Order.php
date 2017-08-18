<?php namespace App\Com\Hwache\Order;
/**
 * 订单功能模块
 *
 * @author Andy php360@qq.com
 * @copyright 苏州华车网络科技有限公司
 * @link http://www.hwache.com
 */

use App\Models\HgCart;
use App\Models\HgCartAttr;
use App\Models\HgCartPrice;
use App\Models\HgCartUser;
use App\Models\HgCartLog;
use App\Models\HgMoney;
use App\Models\HgBaojiaPrice;
use Cache;

class Order
{
    /**
     * 根据订单ID获取该订单所有的信息
     * @param $id
     * @param bool|false $is_order_num
     * @return array
     */
    public function getOrderAllInfoById($id, $is_order_num = false)
    {
        if ($is_order_num) {
            $id = HgCart::getIdByOrdernum($id);
        }

        // 缓存名称
        $cacheName = 'orderById'.$id;

        if (! Cache::has($cacheName)) {
            // 组合查询条件
            $map = [
                'id' => $id,
            ];

            // 初始化缓存数据变量
            $cacheDate = [];

            // 查询基本表
            $order = HgCart::getOrder($map)->toArray();
            $cacheDate['cartBase'] = $order;

            // 查询属性表
            $map = [
                'cart_id' => $order['id'],
            ];
            $order_attr = HgCartAttr::getOrderAttr($map);
            if ($order_attr->count()) {
                $cacheDate['cartAttr'] = $order_attr->toArray();
            }

            // 查询价格表
            $order_price =  HgCartPrice::getOrderPrice($map);
            if ($order_price->count()) {
                $cacheDate['cartPrice'] = $order_price->toArray();
            }

            // 查询订单用户信息表
            $map = [
                'id' => $order['id'],
            ];
            $order_user = HgCartUser::getOrderUser($map);
            if ($order_user->count()) {
                $cacheDate['cartUser'] = $order_user->toArray();
            }

            // 查询订单日志表
            $map = [
                'cart_id' => $order['id'],
            ];
            $order_log = HgCartLog::getOrderLog($map);
            if ($order_log->count()) {
                $cacheDate['cartLog'] = $order_log->toArray();
            }

            // 生成缓存
            if (! config('app.debug')) {
                Cache::put($cacheName, $cacheDate, config('app.cache_time'));
            }
        } else {
            $cacheDate = Cache::get($cacheName);
        }

        return $cacheDate;
    }

    /**
     * 检测订单编号的合法性
     * @param $serial 订单号
     * @return bool|$order
     */
    public function checkOrder($serial)
    {
        $order = HgCart::getOrderStatus($serial);
        if ($order->count()) {
            return $order;
        }
        return false;
    }

    /**
     * 获取诚意金价格
     * @return mixed
     */
    public function getEarnestMoney()
    {
        $earnest = HgMoney::getMoneyList();
        foreach ($earnest as $k => $v) {
            if ($k == 'chengyijin') {
                return $v;
            }
        }
        return false;
    }

    /**
     * 获取担保金价格(根据公式计算得出的价格)
     *
     * @param int $serial 订单主键ID
     * @param bool $pay
     * @return int 担保金价格
     */
    public function getDepositMoney($serial, $pay = false)
    {
        $price = HgBaojiaPrice::getBaojiaPrice($serial);

        // 减去诚意金
        if ($pay && $earnest = $this->getEarnestMoney()) {
            return $price->bj_car_guarantee - $earnest;
        }

        return $price->bj_car_guarantee;
    }

    /**
     * 获取订单车价总价格(裸车开票价+服务费)
     *
     * @param  int $serial 订单主键ID
     * @return int 车价总价格
     */
    public function getBaojiaPrice($serial)
    {
        $price = HgBaojiaPrice::getBaojiaPrice($serial);

        return $price->bj_price;
    }

    /**
     * 获取指定订单号,经销商ID的订单信息
     *
     * @param $num
     * @param $user
     * @param bool $is_buy
     * @return mixed
     */
    public function getOrder($num, $user, $is_buy = false)
    {
        if(! self::checkOrder($num)) exit('没有此订单！');
        return HgCart::getOrderByUser($num, $user, $is_buy);
    }

    /**
     * 更改订单状态
     * @param $serial 订单号
     * @param $sub_status 要修改的订单子状态
     * @param $status 要修改的订单状态
     * @return bool 修改结果(0/1)
     */
    public function changeOrderStatus($serial, $sub_status, $status = null)
    {
        $map = [
            'cart_sub_status' => $sub_status,
        ];

        if (!is_null($status)) {
            $map['cart_status'] = $status;
        }

        return HgCart::where('order_num', $serial)->update($map);
    }

    /**
     * 根据订单序列号获取订单主键ID
     *
     * @param $userId
     * @param $orderNum
     * @return mixed
     */
    public function getOrderIdByOrderNum($userId, $orderNum)
    {
        return HgCart::where('order_num', $orderNum)
            ->where('buy_id', $userId)
            ->value('id');
    }

    /**
     * 根据订单序列号获取报价主键ID
     *
     * @param $orderNum
     * @return mixed
     */
    public function getBaojiaIdByOrderNum($orderNum)
    {
        return HgCart::where('order_num', $orderNum)->value('bj_id');
    }
    /**
     * 获取用户的订单
     * @param $member_id array('')
     * @return array
     */
    public function getOrderListByUser($map,$num=10,$order='hg_cart.created_at'){
        $tmpMap = $map;
        unset($tmpMap['date']);
        $objTmp = HgCart::leftjoin('hg_baojia_price','hg_cart.bj_id','=','hg_baojia_price.bj_id')
            ->where($tmpMap);
        $datetime = date("Y-m-d H:i:s",time()-86400*90);
        if($map['date'] == 'in_threemonth'){
            $objTmp->where('created_at','>=',$datetime);
        }elseif($map['date'] == 'over_threemonth'){
            $objTmp->where('created_at','<',$datetime);
        }
        return $objTmp->orderBy($order,'desc')->paginate($num);
        /**
        return HgCart::leftjoin('hg_baojia_price','hg_cart.bj_id','=','hg_baojia_price.bj_id')
        ->where($map)
        ->paginate($num);
         **/
    }

}
