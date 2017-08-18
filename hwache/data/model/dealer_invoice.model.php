<?php
/**
 * 经销商代理结算文件
 */
defined('InHG') or exit('Access Invalid!');

class dealer_invoiceModel extends Model {
    public function __construct() {
        parent::__construct('invoice_seller');
    }

    /**
     * 获取发票信息
     *
     * @param array $condition
     * @return array
     */
    public function getInvoiceInfo($condition) {
        return $this->where($condition)->find();
    }

}