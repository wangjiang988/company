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

use App\Repositories\Contracts\HcAccountLogRepositoryInterface;
use Facades\App\Com\Hwache\Jiaxinbao\Account as Jxb;//加信宝
use App\Models\HgDailiDealer;
use App\Models\HcUserWithdrawLine;
use App\Models\HcUserRecharge;
use App\Models\HcUserConsume;
use App\Models\HcFilterTemplate;
use App\Models\HcItem;

class Money implements MoneyContract
{
    protected $account;
    public function __construct(HcAccountLogRepositoryInterface $hcAccountLogRepository)
    {
        $this->account = $hcAccountLogRepository;
    }
    /**
     * 资金使用方式:1充值、2提现、3消费、4退款、5加信宝
     */
    CONST LOG_TYPE_RECHARGE = 1;
    CONST LOG_TYPE_WITHDRAW = 2;
    CONST LOG_TYPE_CONSUME = 3;
    CONST LOG_TYPE_REFUNDS = 4;
    CONST LOG_TYPE_JIAXINBAO = 5;

    /**
     * 资金正负数标志
     */
    Const MONEY_PLUS = '+';
    Const MONEY_MINUS = '-';

    /**
     * 支付方式：0账户余额、1线上支付（支付宝）、2银行转帐、3商家补偿、4代金券
     */
    CONST PAY_TYPE_BALANCE = 0;
    CONST PAY_TYPE_ONLINE = 1;
    CONST PAY_TYPE_OFFLINE = 2;
    CONST PAY_TYPE_COMPENSATION = 3;
    CONST PAY_TYPE_VOUCHER = 4;

    /**
     * @param int $userId
     * @param float $sellerId
     * @param string $orderId
     * @param int $money
     * @return bool
     */
    public function getOnTopUp($userId, $money, $orderId=0)
    {
        // 开启事务
        DB::beginTransaction();
        try {
            // 查询用户可用余额(加锁)
            $userCount = DB::table('hc_user_account')->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            //查找订单号
            if($orderId){
                $order = \App\Models\HgOrder::findOrFail($orderId)->first();
            }

            // 用户支付诚意金记录
            DB::table('hc_user_account_log')->insertGetId(
                [
                    'user_id' => $userId,
                    'item' => '在线充值',
                    'remark' => $orderId ? '订单号：' . $order->order_sn : '',
                    'money' => $money,
                    'credit_avaliable' => $userCount->avaliable_deposit + $money,
                    'type' => self::LOG_TYPE_RECHARGE,
                    'money_type' => self::MONEY_PLUS,
                    'pay_type' => self::PAY_TYPE_ONLINE,
                    'order_id' => $orderId,
                ]
            );

            // 更新用户可用余额
            DB::table('hc_user_account')
                ->where('user_id', $userId)
                ->increment('avaliable_deposit', $money);

            //更新用户账户总金额
            DB::table('hc_user_account')
                ->where('user_id', $userId)
                ->increment('total_deposit', $money);

            // 提交事务
            DB::commit();
            return true;
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();
            return false;
        }
    }

    /**
     * 支付实现方法
     * 线上支付
     */
    public function rechargeOnline($userId, $money, $payment)
    {

        // 开启事务
        DB::beginTransaction();
        try {
            // 查询用户可用余额(加锁)
            $userCount = DB::table('hc_user_account')->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            $hide_account  = substr($payment['account'],0,3).'***';
            //提现线路
            $line_type   =  $this->_switch_payment_to_line_type($payment['paymethod']);
            $line  = [
                  'user_id' => $userId,
                  'line_type' => $line_type,
                  'user_id' => $userId,
                  'account_code' => $payment['account'],
                  'account_name' => $payment['payment_word'],
                  'bank_id'      => 0,
                 'created_at'    => Carbon::now()->toDateTimeString()
            ];

            $line_id =  DB::table('hc_user_withdraw_line')->insertGetId($line);
            //充值记录
            $recharge  = [
                'user_id'  =>  $userId,
                'uwl_id'   =>  $line_id,
                'money'    =>  $money,
                'recharge_money'    =>  $money,
                'recharge_confirm_at'    => Carbon::now()->toDateTimeString(),
                'transfer_to_account'    =>  $money,
                'alipay_user_name'      => $payment['account'],
                'recharge_type'      =>   $line_type,
                'remark'   => '线上充值',
                'status'   => 2,
            ];
            $recharge_id = DB::table('hc_user_recharge')->insertGetId($recharge);

            //获取线上支付提现有效时间
            $filter  =  $this->_get_payment_filter($payment['paymethod']);
            $wichdraw_end_at =  Carbon::now()->addDay(30)->toDateTimeString(); //默认一个月
            if($filter)
                $wichdraw_end_at =   Carbon::now()->addDay($filter->user_range)->toDateTimeString(); //

            //消费记录
            $xfData = [
                'user_id'         => $userId,
                'ur_id'           => $recharge_id,
                'uw_id'           => 0,
                'uwl_id'          => $line_id,
                'consume_money'   => $money,
                'avaliable_money' => $userCount->avaliable_deposit + $money,
                'remark'          => '线上充值',
                'is_new'          => 1,
                'status'          => 0,
                'wichdraw_end_at' => $wichdraw_end_at
            ];

            DB::table('hc_user_consume')->insertGetId($xfData);

            // 线上充值 log
            DB::table('hc_user_account_log')->insertGetId(
                [
                    'user_id' => $userId,
                    'item_id' => $recharge_id,
                    'item'    => '转入',
                    'remark'  =>  '线上支付-'.$payment['payment_word'].$hide_account,
                    'money'   =>  $money,
                    'credit_avaliable' => $userCount->avaliable_deposit + $money,
                    'type' => self::LOG_TYPE_RECHARGE,
                    'money_type' => self::MONEY_PLUS,
                    'status'   => 1, //已入账
                    'pay_type' => self::PAY_TYPE_ONLINE,
                ]
            );
            // 线上充值 总表log
            DB::table('hc_account_log')->insertGetId(
                [
                    'from_user_id' => $userId,
                    'from_where' => 1,
                    'from_remark' =>  '线上支付-'.$payment['payment_word'].$hide_account,
                    'to_where'    => 3,
                    'to_remark'   => '客户充值',
                    'trade_no'   =>  '',
                    'remark'  =>  '线上支付-'.$payment['payment_word'].$hide_account,
                    'money'   =>  $money,
                    'type' =>  20,
                    'method_type' => 10,
                    'related_id'   =>$recharge_id,
                    'status'   => 1, //已入账
                    'flow_type'  => 1,
                ]
            );

            $update =  [
                'avaliable_deposit' => (float)$userCount->avaliable_deposit + (float)$money,
                'total_deposit'     => (float)$userCount->total_deposit + (float)$money,
            ];
            // 更新用户可用余额 与总额
            DB::table('hc_user_account')
                ->where('user_id', $userId)
                ->update($update);


            // 提交事务
            DB::commit();
            return true;
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();
            return false;
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
    public function payEarnest($userId, $sellerId, $orderId, $money)
    {
        // 开启事务
        DB::beginTransaction();
        try {
            // 查询可用余额(加锁)
            $userCount = DB::table('hc_user_account')->where('user_id', $userId)->lockForUpdate()->first();
            $sellerAccount = DB::table('hc_daili_account')->where('d_id', $sellerId)->lockForUpdate()->first();

            //查找订单号
            $order = \App\Models\HgOrder::findOrFail($orderId)->first();
            // 用户支付诚意金记录
            DB::table('hc_user_account_log')->insertGetId(
                [
                    'user_id' => $userId,
                    'item' => '支付诚意金',
                    'remark' => '订单号：' . $order->order_sn,
                    'money' => $money,
                    'credit_avaliable' => $userCount->avaliable_deposit - $money,
                    'type' => self::LOG_TYPE_CONSUME,
                    'money_type' => self::MONEY_MINUS,
                    'order_id' => $orderId,
                ]
            );

            //用户加信宝日志数据
            $item = HcItem::where(['code' => 'KHJXB-0100'])->first();
            DB::table('hc_order_jiaxinbao_detail')->insert(
                [
                    'order_id' => $orderId,
                    'item' => $item->name,
                    'item_id' => $item->id,
                    'type' => 10,
                    'user_id' => $userId,
                    'role' => 1,
                    'money' => $money,
                    'freeze_avaiable' => $userCount->freeze_deposit + $money,
                    'description' => $item->name,
                ]
            );

            //售方加信宝日志数据
            DB::table('hc_order_jiaxinbao_detail')->insert(
                [
                    'order_id' => $orderId,
                    'item' => $item->name,
                    'item_id' => $item->id,
                    'type' => 10,
                    'user_id' => $sellerId,
                    'role' => 2,
                    'money' => 499,//歉意金
                    'freeze_avaiable' => $sellerAccount->freeze_deposit + 499,
                    'description' => date("Y-m-d H:i:s"),
                ]
            );

            //更新用户账户中加信宝数据和可用余额
            DB::table('hc_user_account')
                ->where('user_id', $userId)
                ->update([
                    'freeze_deposit' => DB::raw('freeze_deposit+' . $money),
                    'avaliable_deposit' => DB::raw('avaliable_deposit-' . $money),
                ]);

            //更新售方账户中加信宝数据和可用余额
            DB::table('hc_daili_account')
                ->where('d_id', $sellerId)
                ->update([
                    'freeze_deposit' => DB::raw('freeze_deposit+' . 499),
                    'avaliable_deposit' => DB::raw('avaliable_deposit-' . 499),
                ]);

            //更新订单状态和订单家信宝数据
            DB::table('hc_order')
                ->where('id', $orderId)
                ->update(
                    [
                        'order_status' => 2,
                        'order_state' => 2011,
                        'user_freeze_jxb' => DB::raw('user_freeze_jxb+' . $money),
                        'seller_freeze_jxb' => DB::raw('seller_freeze_jxb+' . 499)
                    ]);

            // 提交事务
            DB::commit();
            return true;
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();
            return false;
        }
    }

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
    public function payDeposit($userId, $money, $orderId, $voucherId = 0, $payType = 0)
    {
        // 开启事务
        DB::beginTransaction();
        try {
            // 查询用户可用余额(加锁)
            $userCount = DB::table('hc_user_account')->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            //查找订单号
            $order = \App\Models\HgOrder::find($orderId);
            // 用户支付诚意金记录
            DB::table('hc_user_account_log')->insertGetId(
                [
                    'user_id' => $userId,
                    'item' => '支付买车担保金',
                    'remark' => '订单号：' . $order->order_sn,
                    'money' => $money,
                    'credit_avaliable' => ($payType != 2) ? $userCount->avaliable_deposit - $money : $userCount->avaliable_deposit,
                    'type' => self::LOG_TYPE_CONSUME,
                    'money_type' => self::MONEY_MINUS,
                    'order_id' => $orderId,
                ]
            );

            //更新用户诚意金支付记录
            $pay_log = $order->payDepositLog;
            if ($pay_log) {
                DB::table('hc_pay_deposit')
                    ->where('order_id', $orderId)
                    ->update([
                        'non_payment' => $pay_log->non_payment - $money,
                        'prepaid' => $pay_log->prepaid + $money,
                        'non_confirm_payment' => $payType == 2 ? $pay_log->non_confirm_payment + $money : 0,//线上支付，需要确认
                        'confirmed_payment' => $payType == 2 ? 0 : $pay_log->confirmed_payment + $money,//线上支付，需要确认
                        'voucher_id' => $voucherId
                    ]);
            } else {
                DB::table('hc_pay_deposit')
                    ->insert([
                        'order_id' => $orderId,
                        'total_payment' => $order->sponsion_price,
                        'non_payment' => $order->sponsion_price - $order->earnest_price - $money,
                        'prepaid' => $money,
                        'non_confirm_payment' => $payType == 2 ? $money : 0,//线上支付，需要确认
                        'confirmed_payment' => $payType == 2 ? 0 : $money,//线上支付，需要确认
                        'voucher_id' => $voucherId
                    ]);
            }

            //若完成支付，则更改订单状态为301或302
            $is_pay_all = $pay_log ? ($pay_log->non_payment == $money) : ($order->sponsion_price - $order->earnest_price == $money);
            if ($is_pay_all) {
                $count = HgDailiDealer::getOptionLists($order->daili_dealer_id, $order->brand_id)->count();
                $order_state = ($order->orderAttr->new_file_comment || $count) ? config('orders.order_doposit_wait_pay') : config('orders.order_doposit_wait_pay2');
                DB::table('hc_order')->where('id', $orderId)->update(['order_state' => $order_state]);
            }

            // 提交事务
            DB::commit();
            return ['success' => true, 'is_pay_all' => $is_pay_all];
        } catch (Exception $e) {
            // 回滚事物
            DB::rollback();
            return ['success' => false, 'is_pay_all' => false];
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

    /**
     *   支付违约金
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $moneyId 资金id
     * @return bool
     */
    function PayPenalty($userId, $money, $orderId, $payType)
    {

    }

    /**
     *   支付违约金利息
     *
     * @param int $userId 用户id
     * @param float $money 金额
     * @param int $moneyId 资金id
     * @return bool
     */
    public function PayInterest($userId, $money, $order, $payType)
    {

    }


    //切换支付方式为提现线路值
    private function _switch_payment_to_line_type($type)
    {
        switch($type)
        {
            case "0" : return 1;
            case "1" : return 3;
            case "2" : return 5;
        }
    }

    //获取线上支付提现退款有效周期
    private function _get_payment_filter($paymethod)
    {
        $query  = HcFilterTemplate::where('type','10');
        switch($paymethod)
        {
            case "0" :  $query->where('name','支付宝');break;
            case "1" :  $query->where('name', '微信支付'); break;
            case "2" :  $query->where('name', '财付通'); break;
            default: $query->where('name', '支付宝'); break;;
        }
        return $query->firstOrFail();
    }

}
