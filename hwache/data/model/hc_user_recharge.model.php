<?php
/**
 * 客户充值模型
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_rechargeModel extends Model {
    public function __construct(){
        parent::__construct('hc_user_recharge');
    }

    public function get_list($where, $field="*", $page =10)
    {
        $list  =  $this->field($field)->where($where)->page($page)->order('created_at desc')->select();

        if($list)
        {
            $user_model = Model('user');
            foreach ($list as $k=>$item)
            {
                $user  = $user_model->getUserById($item['user_id']);
                $list[$k]['user'] = $user;
            }
        }
        return $list;
    }

    /**
     * 根据查询条件读取用户账户信息，联合查询
     * 加上用户status 状态验证
     * @author wangjiang
     * @param array $condition
     */
    public function getRechargeList( $options=null, $pageSize=10) {
//        $options['users.status']  = 1; //状态为1的用户可用
        $list =  $this->table('hc_user_recharge,users')
            ->join('left')
            ->on('users.id=hc_user_recharge.user_id ')
            ->field('hc_user_recharge.*,users.name,users.phone')
            ->where($options)
            ->page($pageSize)
            ->order('ur_id desc')
            ->select();

        return $list;
    }

    /**
     * 根据充值id取数据
     */

    public function getById($id,$field="*")
    {
        $recharge  = $this->where(['ur_id'=>$id])->find();

        if($recharge)
        {
            $recharge['user'] = Model('user')->getUserById($recharge['user_id']);
            if($recharge['use_type'] >0 && $recharge['order_id'] > 0 ){ //充值用途 不是光充值的话，订单号可用，查询对应订单
                $recharge['order']  = $this->table('hc_order')->where(['id'=>$recharge['order_id']])->find();
            }
        }

        return $recharge;
    }


    /**
     * 获取用户提现额度列表
     */
    public function get_all_withdraw_limit($user_id)
    {
        $where  =  'user_id='.$user_id.' and status in (2,3)';
        $list   =  $this->table('hc_user_recharge')->where($where)->order('created_at desc')->page(10)->select();
        if($list)  {
            foreach($list as $k =>  $item)
            {
                //消费表记录
                $consume  = Model('hc_user_consume')->where('user_id='.$user_id.' and ur_id='.$item['ur_id'])
                            ->find();
                $list[$k]['consume']  = $consume;
                //提现记录
                $withdraw  =  Model('hc_user_withdraw')->where('ur_id='.$item['ur_id'].' and status in (1,5,6)')
                            ->find();           
                if($withdraw) $list[$k]['withdraw'] =  $withdraw;
                $list[$k]['recharge_type_name'] = $this->_get_online_pay_type($item['recharge_type']);

            }
        }
        
        return $list;
    }

    /**
     * @param $type
     */
    private function _get_online_pay_type($type)
    {
        switch ($type)
        {
            case '1' :  return "支付宝";
             case '2' :  return "银行转账";
            case '3' :  return "财付通";
            default: return "未知支付方式";
        }
    }
}
