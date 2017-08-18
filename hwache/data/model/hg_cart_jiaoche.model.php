<?php
/**
 * 争议
 */
defined('InHG') or exit('Access Invalid!');
class hg_cart_jiaocheModel extends Model{


	/**
	 * 修改记录
	 *
	 * @param
	 * @return bool
	 */
	public function editJiaoche($condition, $data) {
		if (empty($condition)) {
			return false;
		}
		if (is_array($data)) {
			$result = $this->table('hg_cart_jiaoche')->where($condition)->update($data);
			return $result;
		} else {
			return false;
		}
	}


	/**
	 * 取交车记录
	 *
	 * @param
	 * @return array
	 */
	public function getJiaocheList($condition = array(), $page = '', $limit = '') {
		$sqlArr = array();
		if($condition['order_num']!=''){
			$sqlArr[] = " hg_cart_jiaoche.order_num = ".$condition['order_num'];
		}
		if($condition['shangpai']!=''){
			$sqlArr[] = " hg_cart.shangpai = ".$condition['shangpai'];
		}
		if($condition['status']==1){
			$sqlArr[] = " hg_cart_jiaoche.pdi_date_first != '' and hg_cart_jiaoche.user_date_first is null ";
		}elseif($condition['status']==2){
			$sqlArr[] = " hg_cart_jiaoche.user_date_first != '' and hg_cart_jiaoche.pdi_date_first is null ";
		}elseif($condition['status']==3){
			$sqlArr[] = " hg_cart_jiaoche.pdi_date_first != '' and hg_cart_jiaoche.user_date_first !='' ";
		}elseif($condition['status']==4){//超时
			$sqlArr[] = " hg_cart.shangpai=0 and  hg_cart_jiaoche.user_shangpai_time < '".date("Y-m-d")."' and hg_cart_jiaoche.user_chepai = '' ";
		}elseif($condition['status']==5){
			$sqlArr[] = " hg_cart_jiaoche.pdi_chepai != '' and hg_cart_jiaoche.user_chepai is null ";
		}elseif($condition['status']==6){
			$sqlArr[] = " hg_cart_jiaoche.user_chepai != '' and hg_cart_jiaoche.pdi_chepai is null ";
		}elseif($condition['status']==7){
			$sqlArr[] = " hg_cart_jiaoche.pdi_chepai != '' and hg_cart_jiaoche.user_chepai != '' ";
		}
		
		if($condition['add_time_from']!=''){
			$sqlArr[] = ' hg_cart_jiaoche.create_at >= "'.$condition['add_time_from'].'" ';
		}
		if($condition['add_time_to']!=''){
			$sqlArr[] = ' hg_cart_jiaoche.create_at <= "'.$condition['add_time_to'].'" ';
		}
		if($condition['hw_check_status']=='' ){
			$sqlArr[] = " hg_cart_jiaoche.hw_check_status is null";
		}else{
			$sqlArr[] = " hg_cart_jiaoche.hw_check_status =1";
		}
		
		$sql = implode(' and ',$sqlArr);
		$result = $this->table('hg_cart_jiaoche,hg_cart')
						->join('inner')
						->on('hg_cart_jiaoche.order_num = hg_cart.order_num')
						->field('hg_cart_jiaoche.*,hg_cart.shangpai,hg_cart.cart_sub_status')
						->where($sql)
						->page($page)
						->limit($limit)
						->order('hg_cart_jiaoche.id desc')
						->select();
		return $result;
	}

	


    /**
	 * 取交车记录
	 *
	 * @param
	 * @return array
	 */
	public function getCartJiaocheInfo($id) {
		$result = $this->table('hg_cart_jiaoche,hg_cart')
		->join('inner')
		->on('hg_cart_jiaoche.order_num = hg_cart.order_num')
		->field('hg_cart_jiaoche.*,hg_cart.shangpai,hg_cart.cart_sub_status,hg_cart.car_name,hg_cart.buy_id,hg_cart.seller_id,hg_cart.shangpai_area,hg_cart.bj_id')
		->where("hg_cart_jiaoche.id=".$id)
		->find();
        return $result;
	}
	
	public function getCustomerSellerDealer($buy_id,$seller_id){
		$result['buyer'] = $this->table('member')
								->field('member_name,member_truename,member_mobile')
								->where("member_id=".$buy_id)
								->find();
		
		$result['seller'] = $this->table('member,seller,hg_daili_dealer,hg_dealer')
									->join('inner,inner,inner')
									->on('member.member_id = seller.member_id ,seller.seller_id=hg_daili_dealer.dl_id,hg_daili_dealer.d_id=hg_dealer.d_id')
									->field('member.member_truename,seller.seller_name,hg_dealer.d_name,hg_dealer.d_jc_place,member_mobile')
									->where("seller.seller_id=".$seller_id)
									->find();
		return $result;
	}

	public function getButieList($condition){
		$sqlArr = array();
		$sqlArr[] = " hg_baojia.bj_zf_butie = 1 ";
		if($condition['order_num']!=''){
			$sqlArr[] = " hg_cart.order_num = ".$condition['order_num'];
		}
		if($condition['seller_name']!=''){
			$sqlArr[] = "seller_name='".$condition['seller_name']."'";
		}
		
		if($condition['d_type']==1){
			
			if($condition['add_time_from']!=''){
				$sqlArr[] = ' hg_cart_jiaoche.pdi_butie_date >= "'.$condition['add_time_from'].'" ';
			}
			if($condition['add_time_to']!=''){
				$sqlArr[] = ' hg_cart_jiaoche.pdi_butie_date <= "'.$condition['add_time_to'].'" ';
			}
			$sqlArr[] = " hg_cart_jiaoche.pdi_butie_fafang is null ";
		}elseif($condition['d_type']==2){
			
			if($condition['add_time_from']!=''){
				$sqlArr[] = ' hg_cart_jiaoche.pdi_butie_fafang >= "'.$condition['add_time_from'].'" ';
			}
			if($condition['add_time_to']!=''){
				$sqlArr[] = ' hg_cart_jiaoche.pdi_butie_fafang <= "'.$condition['add_time_to'].'" ';
			}
			$sqlArr[] = " hg_cart_jiaoche.pdi_butie_fafang !='' and hg_cart_jiaoche.hw_butie_status is null ";
		}elseif($condition['d_type']==3){
			if($condition['add_time_from']!=''){
				$sqlArr[] = ' hg_cart_jiaoche.hw_butie_date >= "'.$condition['add_time_from'].'" ';
			}
			if($condition['add_time_to']!=''){
				$sqlArr[] = ' hg_cart_jiaoche.hw_butie_date <= "'.$condition['add_time_to'].'" ';
			}
			$sqlArr[] = " hg_cart_jiaoche.hw_butie_status !='' ";
		}
		if($condition['status_1']==1){//售方已发放并提交发放时间
			$sqlArr[] = ' hg_cart_jiaoche.pdi_butie_fafang !="" ';
		}elseif($condition['status_1']==2){//售方发放超时
			$sqlArr[] = ' hg_cart_jiaoche.pdi_butie_fafang is null and  pdi_butie_date<="'.date('Y-m-d').'"';
		}
		if($condition['status_2']!=''){
			$sqlArr[] = ' hg_cart_jiaoche.hw_butie_status = "'.$condition['status_2'].'" ';
		}
			
		$sql = implode(' and ',$sqlArr);
		$result = $this->table('hg_cart_jiaoche,hg_cart,hg_baojia,seller')
		->join('inner,inner,inner')
		->on('hg_cart_jiaoche.order_num = hg_cart.order_num,hg_cart.bj_id =hg_baojia.bj_id ,hg_cart.seller_id=seller.seller_id')
		->field('hg_cart_jiaoche.*,hg_cart.shangpai,hg_cart.cart_sub_status,hg_baojia.bj_zf_butie,hg_baojia.bj_butie,seller.seller_name')
		->where($sql)
		->page($page)
		->limit($limit)
		->order('hg_cart_jiaoche.id desc')
		->select();
		return $result;
	}
	public function getButieInfo($id) {
		$result = $this->table('hg_cart_jiaoche,hg_cart,hg_baojia,seller')
		->join('inner,inner,inner')
		->on('hg_cart_jiaoche.order_num = hg_cart.order_num,hg_cart.bj_id =hg_baojia.bj_id ,hg_cart.seller_id=seller.seller_id')
		->field('hg_cart_jiaoche.*,hg_cart.shangpai,hg_cart.cart_sub_status,hg_cart.buy_id,hg_cart.seller_id,hg_baojia.bj_zf_butie,hg_baojia.bj_butie,seller.seller_name')
		->where("hg_cart_jiaoche.id=".$id)
		->find();
		return $result;
	}
	public function getResupply($condition){
		return $this->table('hg_cart_jiaoche_extinfo')->where($condition)->order('id desc')->select();
	
	}

}