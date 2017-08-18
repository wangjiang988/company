<?php
/**
 * 报价参数审核表模型
 */
defined('InHG') or exit('Access Invalid!');

class hc_price_auditing_parameterModel extends Model
{
    /**
     * 表名称
     *
     * @var string
     */
    private $table = 'hc_price_auditing_parameter';

    /**
     * hc_price_auditing_parameterModel constructor.
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    /**
     * 获取所有参数
     *
     * @return array
     */
    public function getList()
    {
        return $this->select(['limit' => false]);
    }
}