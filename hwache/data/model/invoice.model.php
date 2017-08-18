<?php
/**
 * 买家发票模型
 *
 */
defined('InHG') or exit('Access Invalid!');

class invoiceModel extends Model {

    public function __construct() {
        parent::__construct('invoice');
    }

    /**
     * 取得买家默认发票
     *
     * @param array $condition
     */
    public function getDefaultInvInfo($condition = array()) {
        return $this->where($condition)->order('inv_state asc')->find();
    }

    /**
     * 取得单条发票信息
     *
     * @param array $condition
     */
    public function getInvInfo($condition = array()) {
        return $this->where($condition)->find();
    }

    /**
     * 取得发票列表
     *
     * @param unknown_type $condition
     * @return unknown
     */
    public function getInvList($condition,$page=10, $limit = '') {
        return $this->where($condition)->page($page)->limit($limit)->select();
    }

    
    
    /**
     * 删除发票信息
     *
     * @param unknown_type $condition
     * @return unknown
     */
    public function delInv($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 新增发票信息
     *
     * @param unknown_type $data
     * @return unknown
     */
    public function addInv($data) {
        return $this->insert($data);
    }
	
    /**
     * 获取超时发票信息列表
     *
     * @param unknown_type $data
     * @return unknown
     */
    public function getOvertimeCartInvList($condition, $page=10,$limit = '') {
    	$sql = '';
    	if($condition['from_time']!=''){
    		$sql.=' and hg_cart.updated_at >"'.$condition['from_time'].'"';
    	}
    	if($condition['to_time']!=''){
    		$sql.=' and hg_cart.updated_at <"'.$condition['to_time'].'"';
    	}
    	if($condition['order_num']!=''){
    		$sql .= ' and hg_cart.order_num="'.$condition['order_num'].'"';
    	}
    	return $this->table('hg_cart,invoice')
			    	->field('hg_cart.*')
			    	->join('left')
			    	->on('hg_cart.order_num=invoice.order_num')
			    	->where('hg_cart.calc_status = 1 and invoice.inv_id is NULL '.$sql)
			    	->page($page)
			    	->limit($limit)
			    	->select();
    }
    
}