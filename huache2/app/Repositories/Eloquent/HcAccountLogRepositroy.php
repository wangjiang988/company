<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/26
 * Time: 14:33
 */

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\HcAccountLogRepositoryInterface;
use App\Models\HcAccountLog;


class HcAccountLogRepositroy extends Repository implements HcAccountLogRepositoryInterface
{
    /**
     * EloquentChannelRepository constructor.
     *
     * @param Channel $model
     */
    public function __construct(HcAccountLog $model)
    {
        $this->model=$model;
    }

    /**
     * 添加交易日志
     * @param array $data
     * @return mixed
     */
    public function addLog(array $data,$from_user=1)
    {
        if(!isset($data['from_user_id'])){
            throw new \Exception('from_user_id:用户id不存在');
        }
        if(!isset($data['related_id'])){
            throw new \Exception('related_id:对应表id不存在！');
        }
        if(!isset($data['status'])){
            throw new \Exception('status:操作状态不存在');
        }
        //插入交易日志
        $logData = [
            'from_user_id'          => (int) $data['from_user_id'],//'支出方用户id  结合对应where使用',
            'from_where'            => intval($from_user),//'1,客户 2,售方 3,平台',
            'from_remark'           => isset($data['from_remark']) ? $data['from_remark'] : '',//'支出方说明',
            'to_user_id'            => isset($data['to_user_id']) ? (int) $data['to_user_id'] : 0,//'收入方id',
            'to_where'              => isset($data['to_where']) ? $data['to_where'] : '',//'1.客户，2. 售方 3.平台',
            'to_remark'             => isset($data['to_remark']) ? $data['to_remark'] : '',//'收入方说明',
            'trade_no'              => isset($data['trade_no']) ? $data['trade_no'] : '',//'流水号',
            'remark'                => isset($data['remark']) ? $data['remark'] : '',//'说明',
            'money'                 => isset($data['money']) ? (int) $data['money'] : 0,//'金额',
            'type'                  => isset($data['type']) ? $data['type'] : '',//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
            'method_type'           => isset($data['method_type']) ? $data['method_type'] : '',//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
            'related_id'            => isset($data['related_id']) ? (int) $data['related_id'] : '',//'对应表的id  对应表的id 结合method_type来做',
            'order_id'              => isset($data['order_id']) ? (int) $data['order_id'] : '',//'购车订单号',
            'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
            'flow_type'             => intval($data['flow_type']),//'1收入，2成本 （该字段表示该资金流向是收入还是成本）',
            'status'                => intval($data['status'])//'状态',
        ];
        return $this->model->create($logData);
    }

    /**
     * 支付日志
     * @param $order
     * @param $money
     * @param int $voucher_id
     * @param int $pay, 支付内容，1诚意金，2担保金余款
     */
    public function addPayLog($order,$money,$voucher_id=0,$pay=1){
        $payName = ($pay ==1) ? '诚意金' : '担保金余款';
        $remark = ($voucher_id >0) ? '已用代金券'.$payName : '支付'.$payName;
        $voucherContent = ($voucher_id >0) ? '使用代金券' : '';
        $formRemark = '支付'.$payName."{$voucherContent}".$money;
        $logData = [
            'from_user_id'          => (int) $order->user_id,//'支出方用户id  结合对应where使用',
            'from_where'            => 1,//'1,客户 2,售方 3,平台',
            'from_remark'           => $formRemark,//'支出方说明',
            'to_user_id'            => 0,//'收入方id',
            'to_where'              => 3,//'1.客户，2. 售方 3.平台',
            'to_remark'             => $remark,//'收入方说明',
            'trade_no'              => '',//'流水号',
            'remark'                => $remark,//'说明',
            'money'                 => $money,//'金额',
            'type'                  => 50,//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
            'method_type'           => 30,//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
            'related_id'            => $voucher_id,//'对应表的id  对应表的id 结合method_type来做',
            'order_id'              => $order->id,//'购车订单号',
            'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
            'flow_type'             => 2,//'1收入，2成本 （该字段表示该资金流向是收入还是成本）',
            'status'                => 1//'状态',
        ];
        $this->addLog($logData);
    }

    /**
     * 充值
     * @param $money
     * @param $user_id
     * @param $recharge_id
     * @param int $order_id
     * @param string $userType
     */
    public function addRechargeLog($money,$user_id,$recharge_id,$order_id=0,$userType='member')
    {
        $type = ($userType=='member') ? 10 : 11;
        $logData = [
            'from_user_id'          => $user_id,//'支出方用户id  结合对应where使用',
            'from_where'            => 1,//'1,客户 2,售方 3,平台',
            'from_remark'           => '客户账号充值:￥'.$money,//'支出方说明',
            'to_user_id'            => 0,//'收入方id',
            'to_where'              => 3,//'1.客户，2. 售方 3.平台',
            'to_remark'             => '收到客户充值:￥'.$money,//'收入方说明',
            'trade_no'              => '',//'流水号',
            'remark'                => '客户向自己账号充值:￥'.$money,//'说明',
            'money'                 => $money,//'金额',
            'type'                  => $type,//'流水类型、10转入客户 11转入售方、20客户转出，21售方转出、30客户解冻，31售方解冻、40客户冻结，41售方冻结',
            'method_type'           => $type,//'流水类型、10客户充值 11售方充值 、20k客户提现 21 售方提现、30购买、40退款'
            'related_id'            => $recharge_id,//'对应表的id  对应表的id 结合method_type来做',
            'order_id'              => $order_id,//'购车订单号',
            'special_application_id'=> 0,//'特事审批id (跟type配合使用)',
            'flow_type'             => 2,//'1收入，2成本 （该字段表示该资金流向是收入还是成本）',
            'status'                => 1//'状态',
        ];
        $this->addLog($logData);
    }
}