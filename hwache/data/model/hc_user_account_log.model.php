<?php
/**
 * 交易日志模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_user_account_logModel extends Model {
    public function __construct(){
        parent::__construct('hc_user_account_log');
    }

    /**
     * 根据用户对象查找最新交易日志信息
     */
    public function findLastLogByUser($user)
    {
        if(!isset($user['id'])){
            return false;
        }
        $ret  = $this->where(['user_id'=>$user['id'],'status'=>1])->order('created_at desc')->limit(1)->select();
        if(!isset($ret[0])){
            $ret = false;
        } else{
            $ret = $ret[0];
        }
        return $ret;
    }

    /**
     * 根据用户对象分页查找交易日志数据
     */
    public function getLogListByUser($user,$where, $per_page = 15)
    {
        if(!isset($user['id'])){
            return false;
        }
        $where['user_id'] = $user['id'];
        $ret  = $this->where($where)->order('created_at desc')->page($per_page)->select();
        return $ret;
    }
}
