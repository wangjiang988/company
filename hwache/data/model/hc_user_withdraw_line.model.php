<?php
/**
 * hc_user_withdraw_line用户提现线路管理
 * @author  wangjiang
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_withdraw_lineModel extends Model {

    const forbidden_operation_status = 3; //3.已绑定账户
    public function __construct(){
        parent::__construct('hc_user_withdraw_line');
        $this->set_pk('uwl_id');
    }

    /**
     * 获取所有
     */
    public function getAll($where=[] , $field="*"){
        $where['is_del'] = 0;
        $ret = $this->field($field)->where($where)->order('status desc')->select();
        if($ret)
        {
            foreach ($ret as  $k => $item)
            {
                if($item['bank_id']>0){
                    $ret[$k]['bank'] = $this->table('user_bank')->where(['id'=>$item['bank_id']])->find();
                }
            }
        }
        return $ret;
    }
    /**
     * 根据查询条件查询单条记录
     */
    public function getByWhere($where = [])
    {
        $ret = $this->table('hc_user_withdraw_line')->where($where)->find();
        if($ret && $ret['bank_id'] >0)
        {
            $ret['bank'] = Model('user_bank')->where(['id'=>$ret['bank_id']])->find();
        }
        return $ret;
    }


    /**
     * 根据用户对象查找
     */
    public function getListByUser($user, $where =[], $field ="*",$per_page = 30)
    {
        if(!isset($user['id'])){
            return false;
        }
        $where['hc_user_withdraw_line.user_id']  =  $user['id'];
        //加上删除条件
        $where['hc_user_withdraw_line.is_del']  = 0;
       //先查线上支付

        $result =  $this->table("(select distinct(uwl_id) as uwl_id from car_hc_user_recharge where status=2 or status=3 order by created_at desc) AS hc_user_recharge,hc_user_withdraw_line")
                            ->field('hc_user_withdraw_line.*')
                            ->join('left')
                            ->on('hc_user_recharge.uwl_id=hc_user_withdraw_line.uwl_id')
                            ->where($where)
                            ->page($per_page)
                            ->order('hc_user_withdraw_line.status desc')
                            ->select();

        //获取对应的表BANK信息
        if($result){
            foreach($result as $k => $item)
            {
                //获取账号信息
                if($item['bank_id'] > 0){
                        $bank = $this->table('user_bank')->where(['id'=>$item['bank_id']])->find(); 
                        $result[$k]['bank']         = $item['bank']   =    $bank;
                        $result[$k]['account_info'] =    $bank['bank_code'].'/' .$bank['bank_register_name'];
                }else{
                        $result[$k]['account_info'] =    $item['account_code'];
                }
                //获取提现线路状态
                //四个状态  1.可使用，有充值记录  2.已验证可使用 ( 有充值 有提现记录) 3.已绑定账户 0. 已失效不可用.
                //直接使用status字段。
                $result[$k]['can_operate'] =  true ;
                if($result[$k]['status'] == self::forbidden_operation_status)   $result[$k]['can_operate'] =  false ;
                // $result[$k]['line_status'] = 1;
                // $result[$k]['can_operate'] =  true ;
               
                // if($item['status'] ==0 ){
                //     $result[$k]['line_status'] = 0;
                // }else{
                //      if($item['bank_id'] > 0){
                //              //是否有充值记录
                //             $recharge_count = $this->table('hc_user_recharge')
                //                                     ->where(['user_id'=>$user['id'], 'uwl_id'=>$item['uwl_id']])
                //                                     ->count();
                //             //是否有提现操作
                //             $withdraw_count  = $this->table('hc_user_withdraw')
                //                                         ->where(['user_id'=>$user['id'], 'line_id'=>$item['uwl_id']])
                //                                         ->count();
                //             $result[$k]['withdraw_count'] = $withdraw_count;
                //             $result[$k]['recharge_count'] = $recharge_count;
                //             if($recharge_count &&  $item['bank']['is_default'] ==1)
                //             {
                //                  $result[$k]['line_status'] = 3;
                //                  $result[$k]['can_operate'] =  false ;
                //                  continue;
                //             } 

                //             if($recharge_count>0 && $withdraw_count==0)
                //             {
                //                 $result[$k]['line_status'] = 1 ;
                //             }else if($recharge_count>0 && $withdraw_count>0)
                //             {
                //                 $result[$k]['line_status'] = 2 ;
                //             } else{
                //                 //没有充值记录
                //                  $result[$k]['line_status'] = -1;
                //             }
                //      }                               
                // }
                
            }

        }

        return $result;
    }

}

