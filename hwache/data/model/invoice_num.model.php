<?php
/**
 * 买家发票模型
 *
 */
defined('InHG') or exit('Access Invalid!');

class invoice_numModel extends Model {

	public function __construct() {
		parent::__construct('invoice_num');
	}
	
	public function updateNum($param,$condition){
		$result	= db::update('invoice_num',$param,$condition);
		return $result;
	}

}