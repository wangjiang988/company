<?php
/**
 * 加信宝冻结余额明细模型管理
 */
defined('InHG') or exit('Access Invalid!');
class hc_order_jiaxinbao_detailModel extends Model {
    public function __construct(){
        parent::__construct('hc_order_jiaxinbao_detail');
    }


    public function getList($where=[])
    {
        return $this->where($where)->order('created_at asc')->select();
    }

    /**
     * 获取加薪包总额
     */
    public function getSum($where=[])
    {
        return $this->field('SUM( CASE WHEN type = 10 THEN money ELSE -money END ) as sum')->where($where)->find();
    }
}

