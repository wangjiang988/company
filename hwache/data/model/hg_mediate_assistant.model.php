<?php
/**
 * 争议
 */
defined('InHG') or exit('Access Invalid!');
class hg_mediate_assistantModel extends Model{






	public function getMediateAssistantList($condition = array(), $page = '', $fields = '*', $limit = '') {
		$result = $this->table('hg_mediate_assistant')->field($fields)->where($condition)->page($page)->limit($limit)->order('id asc')->select();
		return $result;
	}


	
	/**
	 *
	 * 提交更新调解方案内容
	 * @param unknown $param
	 * @return boolean|Ambigous <number, unknown>
	 */
	public function addAssitant($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('hg_mediate_assistant',$param);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}

}