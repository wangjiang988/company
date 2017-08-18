<?php
/**
 * 资金流向模块
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 */
namespace App\Com\Hwache\Money;

use DB;
use Exception;
use Carbon\Carbon;
use App\Core\Contracts\Money\Money as MoneyContract;

class Money implements MoneyContract
{
    /**
     * 在线充值
     *
     * 保存充值记录到数据库中，病返回可用于支付的数据
     *
     * @param int $userId 用户ID
     * @param float $money 金额
     * @param string $payType 支付方式
     * @param int $orderId 订单号,默认为0
     * @return array 可用于支付的数据
     */
    public function getOnTopUp($userId, $money, $payType, $orderId = 0)
    {
        $endUserId = config('pay.money.id'); // 可用余额的id为-4

        // 开启事务
        DB::beginTransaction();

        try {
            $insertData = [
                'serial_id'     => get_serial_id($userId, $endUserId),
                'begin_user'    => $userId,
                'end_user'      => $endUserId,
                'pay_type'      => $payType,
                'pay_way'       => 1,
                'money'         => $money,
                'money_type'    => 'topup',
                'order_id'      => $orderId,
                'created_at'    => Carbon::now()->toDateTimeString(),
            ];
            // 插入数据库
            $resultId = DB::table('money')->insertGetId($insertData);

            // 提交事务
            DB::commit();

            return [
                'success'   => true,
                'money_id'  => $resultId,
                'pay_type'  => $payType,
                'pay_way'   => 1,
                'serial_id' => $insertData['serial_id'],
            ];
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();

            return [
                'success'   => false,
                'msg'       => $e->getMessage(),
            ];
        }
    }

    /**
     * 支付诚意金
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $orderId 订单id
     * @return array
     */
    public function payEarnest($userId, $money, $orderId)
    {
        $endUserId = config('pay.hcpay.id'); // 加信宝的id为-1

        // 开启事务
        DB::beginTransaction();

        try {
            // 查询可用余额(加锁)
            $moneyUserCount = DB::table('money_user_count')->where('id', $userId)
                ->lockForUpdate()
                ->first();
            if (!$moneyUserCount || $moneyUserCount->money < $money) {
                throw new Exception('金额不足');
            }

            // 时间
            $time = Carbon::now()->toDateTimeString();
            // 交易/流水号
            $serialId = get_serial_id($userId, $endUserId);
            // 插入数据
            $insertData = [
                'serial_id'     => $serialId,
                'begin_user'    => $userId,
                'end_user'      => $endUserId,
                'pay_type'      => 'money',
                'pay_way'       => 1,
                'money'         => $money,
                'money_type'    => 'earnest',
                'order_id'      => $orderId,
                'pay_status'    => config('money.pay_status.pay_success'),
                'pay_time'      => $time,
                'pay_trade_id'  => $serialId,
                'created_at'    => $time,
            ];
            $moneyId = DB::table('money')->insertGetId($insertData);

            // 更新用户可用余额
            DB::table('money_user_count')->where('id', $userId)->decrement('money', $money);
            // 增加加信宝金额
            DB::table('money_user_count')->where('id', $userId)->increment('hcpay', $money);
            // 可用余额支付
            $this->toMoneyPay($userId, $money, $moneyId);

            // 提交事务
            DB::commit();

            return [
                'success'   => true,
                'time'      => $time,
            ];
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();

            return [
                'success'   => false,
                'msg'       => $e->getMessage(),
            ];
        }
    }

    /**
     * 支付担保金
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $orderId 订单id
     * @param string $payType 支付类型(money:可用余额，hcpay:加信宝等等)
     * @return array
     */
    public function payDoposit($userId, $money, $orderId, $payType)
    {
        $endUserId = config('pay.hcpay.id'); // 加信宝的id为-1

        // 开启事务
        DB::beginTransaction();

        try {
            // 查询可用余额(加锁)
            $moneyUserCount = DB::table('money_user_count')->where('id', $userId)
                ->lockForUpdate()
                ->first();
            if (!$moneyUserCount || $moneyUserCount->money < $money) {
                throw new Exception('金额不足');
            }

            // 时间
            $time = Carbon::now()->toDateTimeString();
            // 交易/流水号
            $serialId = get_serial_id($userId, $endUserId);
            // 插入数据
            $insertData = [
                'serial_id'     => $serialId,
                'begin_user'    => $userId,
                'end_user'      => $endUserId,
                'pay_type'      => 'money',
                'pay_way'       => 1,
                'money'         => $money,
                'money_type'    => 'doposit',
                'order_id'      => $orderId,
                'pay_status'    => config('money.pay_status.pay_success'),
                'pay_time'      => $time,
                'pay_trade_id'  => $serialId,
                'created_at'    => $time,
            ];
            $moneyId = DB::table('money')->insertGetId($insertData);

            // 更新用户可用余额
            DB::table('money_user_count')->where('id', $userId)->decrement('money', $money);
            // 增加加信宝金额
            DB::table('money_user_count')->where('id', $userId)->increment('hcpay', $money);
            // 可用余额支付
            $this->toMoneyPay($userId, $money, $moneyId);

            $orderNum = DB::table('hg_cart')->where('id', $orderId)->value('order_num');

            // 提交事务
            DB::commit();

            return [
                'success'   => true,
                'msg'       => 'ok',
                'data'      => [
                    'time'      => $time,
                    'serial_id' => $serialId,
                    'url'       => route('user.money.paydoposit', [$orderNum, $serialId]),
                ],
            ];
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();

            return [
                'success'   => false,
                'msg'       => $e->getMessage(),
            ];
        }
    }


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
    public function setToPay($serialId, $money, $payTradeId, $payStatus, $payTime, $payInfo = null)
    {
        // 初始化跳转url
        $url = route('user.money.index');

        // 事务处理
        DB::beginTransaction();

        try {
            // 查询流水号对应的id(加锁)
            $moneyInfo = DB::table('money')->where('serial_id', $serialId)
                ->lockForUpdate()
                ->first();
            if (!$moneyInfo) {
                throw new Exception('not found data');
            }

            // 更新money表
            $updateData = [
                'pay_status'    => $payStatus,
                'pay_time'      => $payTime,
                'pay_trade_id'  => $payTradeId,
                'updated_at'    => Carbon::now()->toDateTimeString(),
            ];
            // 更新数据
            DB::table('money')->where('serial_id', $serialId)
                ->where('money', $money)
                ->update($updateData);
            // 存在支付详细信息，保存详细信息记录
            if ($payInfo) {
                DB::table('money_info')->insert([
                    'id'    => $moneyInfo->id,
                    'info'  => serialize($payInfo),
                ]);
            }
            // 判断记录类型
            switch ($moneyInfo->money_type) {
                case 'topup':
                    // 充值，添加到用户可用余额表
                    $moneyUserId = DB::table('money_user')->insertGetId([
                        'user_id'   => $moneyInfo->begin_user,
                        'money_id'  => $moneyInfo->id,
                        'pay_type'  => $moneyInfo->pay_type,
                        'pay_way'   => $moneyInfo->pay_way,
                        'money'     => $moneyInfo->money,
                        'money_used'    => 0,
                        'money_not_use' => $moneyInfo->money,
                        'pay_status'    => $payStatus,
                        'pay_time'      => $updateData['updated_at'],
                        'consumption'   => 0,
                        'created_at'    => $updateData['updated_at'],
                    ]);

                    // 用户可用余额总表
                    $moneyUserCountModel = DB::table('money_user_count');
                    // 查询(加锁)
                    $userMoneyCountData = $moneyUserCountModel->where('id', $moneyInfo->begin_user)
                        ->lockForUpdate()
                        ->first();
                    if (empty($userMoneyCountData)) {
                        // 增加总金额
                        $moneyUserCountModel->insert([
                            'id'    => $moneyInfo->begin_user,
                            'money' => $moneyInfo->money,
                            'hcpay' => 0,
                        ]);
                    } else {
                        // 更新：增加充值金额
                        $moneyUserCountModel->where('id', $moneyInfo->begin_user)
                            ->increment('money', $moneyInfo->money);
                    }

                    // 更新money表
                    DB::table('money')->where('id', $moneyInfo->id)
                        ->update([
                            'money_user_id' => $moneyUserId,
                        ]);

                    break;
                case 'earnest':
                    // 诚意金
                    break;
                case 'doposit':
                    // 担保金
                    $doposit = $this->payDoposit($moneyInfo->begin_user, $moneyInfo->money, $moneyInfo->order_id);

                    $orderNum = DB::table('hg_cart')->where('id', $moneyInfo->order_id)->value('order_num');
                    $url = route('user.money.paydoposit', [$orderNum, $doposit['serial_id']]);

                    break;
                case 'contract':
                    // 赔偿金
                    break;
            }

            // 提交事务
            DB::commit();

            return [
                'success'   => true,
                'msg'       => 'success',
                'data'      => [
                    'url'   => $url,
                ],
            ];
        } catch (Exception $e) {
            // 回滚事务
            DB::rollback();

            return [
                'success'   => false,
                'code'      => $e->getCode(),
                'msg'       => $e->getMessage(),
            ];
        }
    }

    /**
     * 获取在线充值诚意金的最大金额
     *
     * 仅仅使用与在支付诚意金的时候充值
     *
     * @param int $userId 用户id
     * @return int 返回最大的充值金额
     */
    public function getOnTopUpEarnest($userId)
    {
        // 在线可充值最大金额
        $money = $this->getUserTopUpMaxMoney($userId);
        // 诚意金
        $earnest = $this->getEarnest();

        return $money >= $earnest ? $money : ($earnest - $money);
    }

    /**
     * 获取制定用户、制定流水号的资金记录
     *
     * @param int $userId 用户ID
     * @param int $serialId 流水号
     * @return mixed
     */
    public function getMoneyDetail($userId, $serialId)
    {
        return DB::table('money')
            ->where('serial_id', $serialId)
            ->where('begin_user', $userId)
            ->first();
    }

    /**
     * 获取每次在线充值最大可充值金额
     *
     * 检测最后连续未发生消费记录的在线充值的金额的总数
     *
     * @param int $userId 用户ID
     * @return int 最大可充值金额
     */
    public function getUserTopUpMaxMoney($userId)
    {
        // 查询所有在线支付为发生消费记录的金额
        $money = DB::table('money_user')
            ->where('user_id', $userId)
            ->where('pay_way', 1)
            ->where('consumption', 0)
            ->sum('money_not_use');

        // 获取每笔订单最多免费额度-当前金额
        return $this->getOrderFreeMoney() - $money;
    }

    /**
     * 获取用户的所有可用余额数
     *
     * 包括线上，线下所有
     *
     * @param int $userId 用户id
     * @return int 可用余额金额
     */
    public function getUserMoneyCount($userId)
    {
        return DB::table('money_user_count')->where('id', $userId)
            ->value('money');
    }

    /**
     * 获取当前用户指定记录ID的可用余额详细
     *
     * @param $userId
     * @param $id
     * @return mixed
     */
    public function getUserMoneyDetail($userId, $id)
    {
        return DB::table('money_user')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * 获取指定用户的可用金额总数
     *
     * @param $userId
     * @return mixed
     */
    public function getUserMoney($userId)
    {
        return DB::table('money_user_count')->where('id', $userId)
            ->take(1)
            ->value('money');
    }

    /**
     * 获取用户可用余额列表
     *
     * @param $userId
     * @param int $pageSize
     * @return mixed
     */
    public function getUserMoneyList($userId, $pageSize = 15)
    {
        return DB::table('money')->where('begin_user', $userId)->paginate($pageSize);
    }

    /**
     * 获取系统诚意金
     *
     * @return int
     */
    public function getEarnest()
    {
        return config('money.earnest');
    }

    /**
     * 获取系统每笔订单免费可充值/提现额度
     *
     * @return int
     */
    public function getOrderFreeMoney()
    {
        return config('money.order_free_money');
    }

    /**
     * 获取第三方在线支付方式
     *
     * @return array
     */
    public function getOnlinePayType()
    {
        $payType = [];
        foreach (config('pay.pay_user') as $k => $v) {
            if ((-999 < $k) && ($k <= -50)) {
                $payType[$v] = config('pay.'.$v);
            }
        }

        return $payType;
    }

    /**
     * 获取订单的担保金支付情况
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return array
     */
    public function getOrderDopositDetail($userId, $orderId)
    {
        // 获取订单的金额数据
        $dopositMoney = floatval(DB::table('hg_cart_price')
            ->where('cart_id', $orderId)
            ->take(1)
            ->value('cp_doposit'));

        // 获取已经支付的担保金金额
        $paidDopositMoney = floatval(DB::table('money')
            ->where('begin_user', $userId)
            ->where('order_id', $orderId)
            ->where('pay_status', 1)
            ->where('money_type', 'doposit')
            ->sum('money'));

        $return = [
            'payStatus' => false,
            'money' => $dopositMoney, // 担保金金额
            'paidMoney' => $paidDopositMoney, // 已支付担保金金额
            'surplusMoney' => $dopositMoney - $paidDopositMoney, // 剩余担保金金额
        ];

        if ($dopositMoney == $paidDopositMoney) {
            $return['payStatus'] = true;
        }

        return $return;
    }

    /**
     * 获取订单的担保金支付详细列表
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return array
     */
    public function getOrderDopositList($userId, $orderId)
    {
        return DB::table('money')->select('pay_type', 'money', 'pay_time')
            ->where('begin_user', $userId)
            ->where('order_id', $orderId)
            ->where('pay_status', 1)
            ->where('money_type', 'doposit')
            ->orderBy('pay_time', 'asc')
            ->get();
    }

    /**
     * 获取是否第一次支付担保金
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return bool
     */
    public function isFirstPayDoposit($userId, $orderId)
    {
        $payCount = DB::table('money')
            ->where('begin_user', $userId)
            ->where('order_id', $orderId)
            ->where('pay_status', 1)
            ->where('money_type', 'doposit')
            ->count();
        return $payCount == 0 ? true : false;
    }

    /**
     * 获取第一次支付担保金时间
     *
     * @param int $userId 用户id
     * @param int $orderId 订单id
     * @return mixed
     */
    public function getFirstPayDopositTime($userId, $orderId)
    {
        return DB::table('money')
            ->where('begin_user', $userId)
            ->where('order_id', $orderId)
            ->where('pay_status', 1)
            ->where('money_type', 'doposit')
            ->orderBy('pay_time', 'asc')
            ->value('pay_time');
    }

    /**
     * 设置资金支付状态为取消
     *
     * @param int $userId 用户id
     * @param int $id 资金记录id
     * @return mixed
     */
    public function setMoneyStatusCancel($userId, $id)
    {
        return DB::table('money')->where('bengin_user', $userId)
            ->where('id', $id)
            ->update(['pay_status' => config('money.pay_status.pay_cancel')]);
    }

    /**
     * 支付诚意金子系统流程
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $moneyId 资金id
     * @return bool
     */
    private function toMoneyPay($userId, $money, $moneyId)
    {
        // 支付完成标记
        $paySuccess = false;
        // 读取最后一条在线充值记录的金额
        $moneyUserModel = DB::table('money_user');
        $moneyUserDetailModel = DB::table('money_user_detail');
        $lastMoney = $moneyUserModel->where('user_id', $userId)
            ->where('pay_way', 1)
            ->orderBy('pay_time', 'desc')
            ->first();
        if ($lastMoney && $lastMoney->money_not_use > 0) { // 该记录存在为使用金额
            if ($lastMoney->money_not_use >= $money) {
                // 标识支付完成
                $paySuccess = true;
                // 一次性支付所有的金额
                $payMoney = $money;
            } else {
                // 支付未完成，一次性支付该记录所有的金额
                $payMoney = $lastMoney->money_not_use;
                $money -= $lastMoney->money_not_use;
            }
            // 未使用记录减少金额
            $moneyUserModel->where('user_id', $userId)->decrement('money_not_use', $payMoney);
            // 已使用记录增加金额
            $moneyUserModel->where('user_id', $userId)->increment('money_used', $payMoney);
            // 增加使用记录详细记录
            $moneyUserDetailModel->insert([
                'money_user_id' => $lastMoney->id,
                'money_id'      => $moneyId,
                'money_used'    => $payMoney,
                'pay_time'      => Carbon::now()->toDateTimeString(),
            ]);
            // 标记该记录之前的所有的记录为已消费记录
            $moneyUserModel->where('user_id', $userId)
                ->where('consumption', 0)
                ->where('pay_time', '<=', $lastMoney->pay_time)
                ->update(['consumption' => 1]);
        }

        if (!$paySuccess) {
            // 查询所有的存在金额的记录，
            $allMoneyList = $moneyUserModel->where('user_id', $userId)
                ->where('money_not_use' , '>', 0)
                ->orderBy('pay_way', 'asc')
                ->orderBy('pay_time', 'asc')
                ->get();

            foreach ($allMoneyList as $item) {
                if ($item->money_not_use >= $money) {
                    // 标识支付完成
                    $paySuccess = true;
                    // 一次性支付所有的金额
                    $payMoney = $money;
                } else {
                    // 支付未完成，一次性支付该记录所有的金额
                    $payMoney = $item->money_not_use;
                    $money -= $item->money_not_use;
                }
                // 未使用记录减少金额
                $moneyUserModel->where('user_id', $userId)->decrement('money_not_use', $payMoney);
                // 已使用记录增加金额
                $moneyUserModel->where('user_id', $userId)->increment('money_used', $payMoney);
                // 增加使用记录详细记录
                $moneyUserDetailModel->insert([
                    'money_user_id' => $item->id,
                    'money_id'      => $moneyId,
                    'money_used'    => $payMoney,
                    'pay_time'      => Carbon::now()->toDateTimeString(),
                ]);

                // 如果已经标记完成或者支付金额为0,跳出循环
                if ($paySuccess || 0 == $money) {
                    break;
                }
            }
        }

        return true;
    }
}
