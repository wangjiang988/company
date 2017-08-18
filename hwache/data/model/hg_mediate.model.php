<?php
/**
 * 争议
 */
defined('InHG') or exit('Access Invalid!');
class hg_mediateModel extends Model{



	/**
	 * 修改记录
	 *
	 * @param
	 * @return bool
	 */
	public function editMediate($condition, $data) {
		if (empty($condition)) {
			return false;
		}
		if (is_array($data)) {
			$result = $this->table('hg_mediate')->where($condition)->update($data);
			return $result;
		} else {
			return false;
		}
	}


	/**
	 * 取退款退货记录
	 *
	 * @param
	 * @return array
	 */
	public function getRefundReturnList($condition = array(), $page = '', $fields = '*', $limit = '') {
		$result = $this->table('hg_mediate')->field($fields)->where($condition)->page($page)->limit($limit)->order('itemid desc')->select();
		return $result;
	}

	/**
	 * 取退款记录
	 *
	 * @param
	 * @return array
	 */
	public function getMediateList($condition = array(), $page = '') {
	    
		$result = $this->getRefundReturnList($condition, $page);
		return $result;
	}


    /**
	 * 取一条记录
	 *
	 * @param
	 * @return array
	 */
	public function getMediateInfo($condition = array(), $fields = '*') {
        return $this->table('hg_mediate')->where($condition)->field($fields)->find();
	}
	/**
	 * 
	 * 提交主调解内容
	 * @param unknown $param
	 * @return boolean|Ambigous <number, unknown>
	 */
	public function add($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('hg_mediate',$param);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}
	
	/**
	 *
	 * 提交裁判团 意见
	 * @param  $param
	 * @return 
	 */
	public function addAdvice($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('hg_mediate_team_advice',$param);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}
	
	
	
	/**
	 *
	 *
	 * @return array
	 */
	public function getAdminForJudgmentList($sql=''){
		return $this->table('admin,gadmin')
		->join('inner')
		->on('admin.admin_gid = gadmin.gid')
		->where($sql)
		->field('admin.*,gadmin.gname')
		->select();
	}

}