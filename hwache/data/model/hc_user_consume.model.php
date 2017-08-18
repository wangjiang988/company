<?php
/**
 * 报价模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_consumeModel extends Model {
    public function __construct(){
        parent::__construct('hc_user_consume');
    }


    /**
     * 根据用户对象查找账户信息
     */
    public function findByUser($user)
    {
        if(!isset($user['id'])){
            return false;
        }
        return $this->where(['user_id'=>$user['id']])->find();
    }


    /**
     * 根据用户对象查找账户信息
     */
    public function getListByUser($user, $where =[], $field ="*",$per_page = 10)
    {
        if(!isset($user['id'])){
            return false;
        }
        $where['user_id']  =  $user['id'];
        $result =  $this->field($field)->where($where)->page($per_page)->order('created_at desc')->select();
        //获取对应的充值记录和提现记录,提现线路记录
        if($result)
        {
            foreach ($result as $k => $item) {
                $recharge   =  $this->table('hc_user_recharge')->where(['ur_id'=>$item['ur_id']])->find();
                if($recharge){
                    //'充值方式，默认0，系统充值，1支付宝，2银行转账',
                    if($recharge['recharge_type'] == 1){
                       $recharge['recharge_type_word']  = '线上充值';
                       $recharge['account_info']  = show_recharge_type($recharge['recharge_type']).substr($recharge['alipay_user_name'],0,3).'***'; 
                    }else if($recharge['recharge_type'] == 2){
                        $recharge['recharge_type_word']  = '银行转账';
                        $recharge['account_info']  = '***'.substr($recharge['bank_account'],-4). mb_substr($recharge['user_bank_name'],0,1).'***'; 
                    }else{
                         $recharge['recharge_type_word']  = '线上充值';
                         $recharge['account_info']  = show_recharge_type($recharge['recharge_type']).substr($recharge['alipay_user_name'],0,3).'***'; 
                    //    $recharge['account_info']  = "系统充值"; 
                    }
                }
                $with_draw  =  $this->table('hc_user_withdraw')->where(['uw_id'=>$item['uw_id']])->find();
                if(!$with_draw){
                       $with_draw = ['money' =>'0.00'] ;
                }
                $with_draw_line  =  $this->table('hc_user_withdraw_line')->where(['uwl_id'=>$item['uwl_id']])->find();

                $result[$k]['recharge'] = $recharge;
                // $result[$k]['left_with_draw_limit'] = (float)$recharge['money'] - (float)$with_draw['money'];
                $result[$k]['with_draw'] = $with_draw;
                $result[$k]['with_draw_line'] = $with_draw_line;
            }
        }
        return $result;
    }
}
