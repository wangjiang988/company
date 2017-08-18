<?php
namespace App\Core\Contracts\Money;
/**
 * 支付接口
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 */

interface Pay
{
    /**
     * 支付
     *
     * @param string $orderNum 订单号
     * @param string $orderName 订单名称（商品名称）
     * @param float $money 金额
     * @return mixed
     */
    public function pay($orderNum, $orderName, $money);

    /**
     * 获取同步支付通知
     *
     * @return mixed
     */
    public function getResult();

    /**
     * 获取异步支付通知
     *
     * @return mixed
     */
    public function getAsynchronyResult();

    /**
     * 设置支付方式
     *
     * @param string $payment 支付方式名称
     * @return mixed
     */
    public function setPayment($payment);

}
