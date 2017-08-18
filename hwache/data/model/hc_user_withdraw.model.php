<?php
/**
 * 客户提现模型
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_withdrawModel extends Model {
    public function __construct(){
        parent::__construct('hc_user_withdraw');
        $this->set_pk('uw_id');
    }

    /**
     * 得到详情列表
     * @param $where
     * @param string $field
     * @param string $order
     * @param int $page
     * @return mixed
     */
    public function getDetailList($where,
                                  $field="hc_user_withdraw.*,
                                            users.name as u_name,
                                            users.phone as u_phone,
                                            users.email as u_email,
                                            
                                            hc_user_recharge.money as ur_money,
                                            hc_user_recharge.recharge_money as ur_recharge_money,
                                            hc_user_recharge.remark as ur_remark,
                                            hc_user_recharge.order_id as ur_order_id,
                                            hc_user_recharge.trade_no as ur_trade_no,
                                            hc_user_recharge.alipay_user_name as ur_alipay_user_name,
                                            hc_user_recharge.bank_id as ur_bank_id,
                                            hc_user_recharge.bank_name as ur_bank_name,
                                            hc_user_recharge.bank_account as ur_bank_account,
                                            hc_user_recharge.user_bank_name as ur_user_bank_name,
                                            hc_user_recharge.recharge_type as ur_recharge_type,
                                            hc_user_recharge.status as ur_status,
                                            hc_user_recharge.use_type as ur_use_type,
                                            hc_user_recharge.recharge_confirm_at as ur_recharge_confirm_at,
                                            hc_user_recharge.created_at as ur_created_at,
                                            hc_user_recharge.updated_at as ur_updated_at,
                                            
                                            hc_user_withdraw_line.line_type as uwl_line_type,
                                            hc_user_withdraw_line.account_code as uwl_account_code,
                                            hc_user_withdraw_line.account_name as uwl_account_name,
                                            hc_user_withdraw_line.bank_id as uwl_bank_id,
                                            hc_user_withdraw_line.status as uwl_status,
                                            hc_user_withdraw_line.remark as uwl_remark,
                                            hc_user_withdraw_line.is_del as uwl_is_del,
                                            hc_user_withdraw_line.created_at as uwl_created_at,
                                            hc_user_withdraw_line.updated_at as uwl_updated_at,
                                            op.*
                                            ",
                                  $order='uw_id desc',
                                  $page =10)
    {
        $op_where = '';
        if(isset($where['step'])) {
            $op_where .= ' and op1.step='.$where['step'];
            unset($where['step']);
        }
        if(isset($where['user_name'])) {
            $op_where .= ' and op1.user_name like \'%'.$where['user_name'].'%\' ';
            unset($where['user_name']);
        }
        if($where['hc_user_withdraw.status'] && $where['hc_user_withdraw.status'] ==1){
            $where['hc_user_withdraw.status'] = ['exp','hc_user_withdraw.status=1 or hc_user_withdraw.status>=100'];
        }
        if($op_where !=''){ //
            $list = $this->table('(SELECT DISTINCT(related_id) from car_hc_admin_operation as op1 where op1.type=13 '.$op_where.' ) AS op,hc_user_withdraw,users,hc_user_withdraw_line,hc_user_recharge') //AS 必须大写 不然不能识别
            ->field($field)
                ->join('left,left,left,left')
                ->on('hc_user_withdraw.uw_id=op.related_id,hc_user_withdraw.user_id=users.id,hc_user_withdraw.line_id=hc_user_withdraw_line.uwl_id,hc_user_withdraw.ur_id=hc_user_recharge.ur_id')
                ->where($where)
                ->order($order)
                ->page($page)
                ->select();
        }else{
            $list = $this->table('hc_user_withdraw,users,hc_user_withdraw_line,hc_user_recharge,(SELECT DISTINCT(related_id) from car_hc_admin_operation as op1 where op1.type=13  ) AS op') //AS 必须大写 不然不能识别
            ->field($field)
                ->join('left,left,left,left')
                ->on('hc_user_withdraw.user_id=users.id,hc_user_withdraw.line_id=hc_user_withdraw_line.uwl_id,hc_user_withdraw.ur_id=hc_user_recharge.ur_id,hc_user_withdraw.uw_id=op.related_id')
                ->where($where)
                ->order($order)
                ->page($page)
                ->select();
        }

        return $list;
    }

    /**
     * 得到详情
     * @param $where
     * @param string $field
     * @param string $order
     * @param int $page
     * @return mixed
     */
    public function getDetailById($id,
                                  $field="hc_user_withdraw.*,
                                            users.name as u_name,
                                            users.phone as u_phone,
                                            users.email as u_email,
                                            
                                            hc_user_recharge.money as ur_money,
                                            hc_user_recharge.recharge_money as ur_recharge_money,
                                            hc_user_recharge.remark as ur_remark,
                                            hc_user_recharge.order_id as ur_order_id,
                                            hc_user_recharge.trade_no as ur_trade_no,
                                            hc_user_recharge.alipay_user_name as ur_alipay_user_name,
                                            hc_user_recharge.bank_id as ur_bank_id,
                                            hc_user_recharge.bank_name as ur_bank_name,
                                            hc_user_recharge.bank_account as ur_bank_account,
                                            hc_user_recharge.user_bank_name as ur_user_bank_name,
                                            hc_user_recharge.recharge_type as ur_recharge_type,
                                            hc_user_recharge.status as ur_status,
                                            hc_user_recharge.use_type as ur_use_type,
                                            hc_user_recharge.recharge_confirm_at as ur_recharge_confirm_at,
                                            hc_user_recharge.created_at as ur_created_at,
                                            hc_user_recharge.updated_at as ur_updated_at,
                                            
                                            hc_user_withdraw_line.line_type as uwl_line_type,
                                            hc_user_withdraw_line.account_code as uwl_account_code,
                                            hc_user_withdraw_line.account_name as uwl_account_name,
                                            hc_user_withdraw_line.bank_id as uwl_bank_id,
                                            hc_user_withdraw_line.status as uwl_status,
                                            hc_user_withdraw_line.remark as uwl_remark,
                                            hc_user_withdraw_line.is_del as uwl_is_del,
                                            hc_user_withdraw_line.created_at as uwl_created_at,
                                            hc_user_withdraw_line.updated_at as uwl_updated_at"
                                  )
    {
        $where = ['uw_id'=>$id];
        $ret = $this->table('hc_user_withdraw,users,hc_user_withdraw_line,hc_user_recharge')
            ->field($field)
            ->join('left,left,left')
            ->on('hc_user_withdraw.user_id=users.id,hc_user_withdraw.line_id=hc_user_withdraw_line.uwl_id,hc_user_withdraw.ur_id=hc_user_recharge.ur_id')
            ->where($where)
            ->find();
        if($ret)
        {
            $ret['user'] = Model('user')->getUserById($ret['user_id']);
            if($ret['use_type'] >0 && $ret['ur_order_id'] > 0 ){ //充值用途 不是光充值的话，订单号可用，查询对应订单
                $ret['order']  = $this->table('hc_order')->where(['id'=>$ret['ur_order_id']])->find();
            }
            if($ret['uwl_line_type'] == 2 && $ret['uwl_bank_id'] > 0 ){ //对应的银行卡信息
                $ret['bank']  = $this->table('user_bank')->where(['id'=>$ret['uwl_bank_id']])->find();
            }else{
                $ret['consume']  = $this->table('hc_user_consume')->where(['uw_id'=>$ret['uw_id']])->find();
            }


        }
        return $ret;
    }




}
