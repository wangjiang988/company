<?php
/**
 * 争议
 */
defined('InHG') or exit('Access Invalid!');
class hg_disputeModel extends Model{
	public function __construct() {
		parent::__construct('hg_dispute');
	}


	/**
	 * 取争议列表
	 *
	 * @param
	 * @return array
	 */
	public function getDisputeList($condition = array(), $page = '', $fields = '*', $limit = '') {
		$joinsqlExt = '';
		$sqlStr = '';
		$sqlArray = array();
		if($condition['who'] =='buyer'){
			$joinsqlExt = ' and  hg_dispute.member_id = hg_cart.buy_id';
		}elseif($condition['who'] =='seller'){
			$joinsqlExt = ' and  hg_dispute.member_id = hg_cart.seller_id';
		}
		if($condition['order_num']!=''){
			$sqlArray[] = ' hg_dispute.order_num= "'.$condition['order_num'].'" ';
		}
		if($condition['add_time_from']!=''){
			$sqlArray[] = ' hg_dispute.createat >= "'.$condition['add_time_from'].'" ';
		}
		if($condition['add_time_to']!=''){
			$sqlArray[] = ' hg_dispute.createat <= "'.$condition['add_time_to'].'" ';
		}
		
		if($condition['do_status'] ==1){//未申辩
			$sqlArray[] = ' hg_defend.id = "" ';
		}elseif($condition['do_status']==2){//已申辩
			$sqlArray[] = ' hg_defend.id != "" and hg_mediate.itemid=""';
		}elseif($condition['do_status']==3){//未补充
			$sqlArray[] = '( ( hg_dispute.resupply != "" and hg_dispute.resupply_evidence is null ) or ( hg_defend.resupply != "" and hg_defend.resupply_evidence is null ) ) and hg_mediate.itemid is null';
		}elseif($condition['do_status']==4){//已补充
			$sqlArray[] = '( ( hg_dispute.resupply != "" and hg_dispute.resupply_evidence!="" ) or ( hg_defend.resupply != "" and hg_defend.resupply_evidence!="" ) ) and hg_mediate.itemid is null';
		}elseif($condition['do_status']==5){//正在调解
			$sqlArray[] = ' hg_mediate.itemid >0 and hg_mediate.status in(0,1)';
		}elseif($condition['do_status']==6){//正在裁判
			$sqlArray[] = ' hg_mediate.itemid >0 and hg_mediate.status=3';
		}elseif($condition['do_status']==7){//等待判定
			$sqlArray[] = ' hg_mediate.itemid >0 and hg_mediate.status=4 ';
		}
		
		if($condition['breaker']!=''){
			if($condition['breaker']=='all'){
				$sqlArray[] = ' hg_mediate.breaker in(1,2,3) or hg_mediate.status=2';
			}else{
				$sqlArray[] = ' hg_mediate.breaker ='.$condition['breaker'];
			}
			
		}else{
			$sqlArray[] = ' ((hg_mediate.itemid >0 and hg_mediate.breaker not in(1,2,3) and hg_mediate.status!=2) or hg_defend.id is null or hg_mediate.itemid is null)';
		}
		if(count($sqlArray)>0){
			$sqlStr = implode(' and ',$sqlArray);
		}
		$result =  $this->table('hg_cart,hg_dispute,hg_defend,hg_mediate')
						->join('inner,left,left')
						->on('hg_dispute.order_num = hg_cart.order_num '.$joinsqlExt.' , hg_dispute.id=hg_defend.dispute_id , hg_defend.dispute_id=hg_mediate.dispute_id')
						->field('hg_dispute.*,hg_cart.buy_id,hg_defend.id as defend_id,hg_mediate.itemid as mediate_id,hg_mediate.breaker,hg_mediate.status as mediate_status')
						->where($sqlStr)
						->page($page)
						->limit($limit)
						->order('id desc')
						->select();
		return $result;
	}

	/**
	 * 
	 * 取得争议
	 * @param array $condition
	 * @return array
	 */
	public function getDisputeInfo($condition = array()) {
		$sql = '';
		if($condition['dispute_id']!=''){
			$sql = " hg_dispute.id = '".$condition['dispute_id']."'";
		}else{
			return array();
		}
		$result = $this->table('hg_cart,hg_dispute,hg_defend,hg_mediate')
						->join('inner,left,left')
						->on('hg_dispute.order_num = hg_cart.order_num  , hg_dispute.id=hg_defend.dispute_id , hg_defend.dispute_id=hg_mediate.dispute_id')
						->field('hg_dispute.*,
								hg_cart.buy_id,
								hg_defend.content as defend_content,
								hg_defend.id as defend_id,
								hg_defend.createat as defend_date,
								hg_defend.resupply as defend_resupply,
								hg_defend.resupply_date as defend_resupply_date,
								hg_defend.resupply_date_count as defend_resupply_date_count,
								hg_defend.resupply_evidence as defend_resupply_evidence,
								hg_defend.resupply_evidence_date as defend_resupply_evidence_date,
								hg_defend.defend_hejie,
								hg_defend.defend_hejie_date,
								hg_mediate.content as mediate_content,
								hg_mediate.evidence as mediate_evidence,
								hg_mediate.tiaojie_operator,
								hg_mediate.itemid,
								hg_mediate.createat as mediate_date,
								hg_mediate.note,
								hg_mediate.evidence as hg_mediate_evidence,
								hg_mediate.status as mediate_status,
								hg_mediate.mediate_team_ids,
								hg_mediate.breaker,
								hg_mediate.breaker_content,
								hg_mediate.breaker_excute')
						->where($sql)
						->find();
		return $result;
	}
	
	/**
	 * 
	 * 
	 */
	public function getEvidence($dispute_id){
		$result = $this->table('hg_evidence')
						->where(array('dispute_id'=>$dispute_id))
						->select();
		return $result;
	}
	
}