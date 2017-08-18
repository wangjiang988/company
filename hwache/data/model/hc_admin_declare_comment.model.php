<?php
/**
 * 交易日志模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_admin_declare_commentModel extends Model {
    public function __construct(){
        parent::__construct('hc_admin_declare_comment');
    }

    public function getList($where){
        $where['is_del'] = 0;
        $list  = $this->where($where)
                    ->order('created_at desc')->select();
        return $list;
    }
}
