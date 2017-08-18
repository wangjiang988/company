<?php
/**
 * 资金流向接口
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 */
namespace App\Core\Contracts\Money;

interface Money
{
    /**
     * 在线充值
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @param float $money 金额
     * @return array
     */
    public function getOnTopUp($userId, $money, $orderId=0);

    /**
     * 支付诚意金
     *
     * @param int $userId 用户id
     * @param int $sellerId 商家id
     * @param int $orderId 订单id
     * @param float $money 金额
     * @return array
     */
    public function payEarnest($userId, $sellerId, $orderId, $money);

    /**
     * 支付担保金
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $orderId 订单id
     * @param int $voucherId 代金券id
     * @param int $payType 支付方式，默认0账户余额、1线上充值（支付宝）、2银行转帐、3商家补偿、4代金券 5平台划款'
     * @return array
     */
    public function payDeposit($userId, $money, $orderId, $voucherId = 0, $payType = 0);

    /**
     * 支付违约金
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $orderId 订单id
     * @param string $payType 支付类型(money:可用余额，hcpay:加信宝等等)
     * @return array
     */
    public function PayPenalty($userId, $money, $orderId, $payType);

    /**
     * 支付违约金利息
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @param float $money 金额
     * @param string $payType 支付类型(money:可用余额，hcpay:加信宝等等)
     * @return array
     */
    public function PayInterest($userId, $orderId, $money,  $payType);

    /**
     * 支付
     *
     * 主要用于充值，诚意金，担保金，赔偿金等相关联资金表的金额调账
     *
     * @param int $serialId 资金流水号
     * @param float $money 金额
     * @param int $payTradeId 交易流水号
     * @param int $payStatus 支付状态
     * @param string $payTime 支付时间
     * @param string|null $payInfo 详细支付记录
     * @return array
     */
    public function setToPay($serialId, $money, $payTradeId, $payStatus, $payTime, $payInfo = null);

    /**
     * 获取在线充值诚意金的最大金额
     *
     * 仅仅使用与在支付诚意金的时候充值
     *
     * @param int $userId 用户id
     * @return int 返回最大的充值金额
     */
    public function getOnTopUpEarnest($userId);

    /**
     * 获取制定用户、制定流水号的资金记录
     *
     * @param int $userId 用户ID
     * @param int $serialId 流水号
     * @return mixed
     */
    public function getMoneyDetail($userId, $serialId);

    /**
     * 获取每次在线充值最大可充值金额
     *
     * 检测最后连续未发生消费记录的在线充值的金额的总数
     *
     * @param int $userId 用户ID
     * @return int 最大可充值金额
     */
    public function getUserTopUpMaxMoney($userId);

    /**
     * 获取用户的所有可用余额数
     *
     * 包括线上，线下所有
     *
     * @param int $userId 用户id
     * @return int 可用余额金额
     */
    public function getUserMoneyCount($userId);

    /**
     * 获取当前用户指定记录ID的可用余额详细
     *
     * @param $userId
     * @param $id
     * @return mixed
     */
    public function getUserMoneyDetail($userId, $id);

    /**
     * 获取指定用户的可用金额总数
     *
     * @param $userId
     * @return mixed
     */
    public function getUserMoney($userId);

    /**
     * 获取用户可用余额列表
     *
     * @param $userId
     * @param int $pageSize
     * @return mixed
     */
    public function getUserMoneyList($userId, $pageSize = 15);

    /**
     * 获取系统诚意金
     *
     * @return int
     */
    public function getEarnest();

    /**
     * 获取系统每笔订单免费提现额度
     *
     * @return int
     */
    public function getOrderFreeMoney();

    /**
     * 获取第三方在线支付方式
     *
     * @return array
     */
    public function getOnlinePayType();

    /**
     * 获取订单的担保金支付情况
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return array
     */
    public function getOrderDopositDetail($userId, $orderId);

    /**
     * 获取订单的担保金支付详细列表
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return array
     */
    public function getOrderDopositList($userId, $orderId);

    /**
     * 获取是否第一次支付担保金
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return bool
     */
    public function isFirstPayDoposit($userId, $orderId);

    /**
     * 获取第一次支付担保金时间
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return mixed
     */
    public function getFirstPayDopositTime($userId, $orderId);

    /**
     * 设置资金支付状态为取消
     *
     * @param int $userId 用户id
     * @param int $id 资金记录id
     * @return mixed
     */
    public function setMoneyStatusCancel($userId, $id);
}
