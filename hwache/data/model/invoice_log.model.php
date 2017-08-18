<?php
/**
 * 买家发票模型
 *
 */
defined('InHG') or exit('Access Invalid!');

class invoice_logModel extends Model {

    public function __construct() {
        parent::__construct('invoice_log');
    }



    /**
     * 取得发票列表
     *
     * @param unknown_type $condition
     * @return unknown
     */
    public function getInvLogList($condition,$page=10, $limit = '') {
        return $this->where($condition)->page($page)->limit($limit)->order('date desc')->select();
    }

    
 
}