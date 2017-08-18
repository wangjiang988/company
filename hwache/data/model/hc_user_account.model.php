<?php
/**
 * 报价模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_accountModel extends Model {
    public function __construct(){
        parent::__construct('hc_user_account');
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
}
