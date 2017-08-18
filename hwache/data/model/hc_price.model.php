<?php
/**
 * 价格计算
 * Created by PhpStorm.
 * User: Cheney
 * Date: 2016/11/28
 * Time: 14:55
 */
defined('InHG') or exit('Access Invalid!');
class hc_priceModel extends Model {
    public function __construct()
    {
        parent::__construct('hc_price');
    }
}