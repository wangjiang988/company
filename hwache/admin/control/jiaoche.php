<?php
/**
 * 交车管理
 */
defined('InHG') or exit('Access Invalid!');
class jiaocheControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	// 争议列表
	public function indexOp() {
		$model_jiaoche = Model('hg_cart_jiaoche');
		$_GET['d_type'] = !empty($_GET['d_type'])?$_GET['d_type'] :"undone";
		$condition = array();
		
		if($_GET['order_num']!=''){
			$condition['order_num'] = $_GET['order_num'];
		}
		if($_GET['shangpai']!=''){
			$condition['shangpai'] = $_GET['shangpai'];
		}
		if($_GET['status']!=''){
			$condition['status'] = $_GET['status'];
		}

		if($_GET['add_time_from']!=''){
			$condition['add_time_from'] = $_GET['add_time_from'];
		}
		if($_GET['add_time_to']!=''){
			$condition['add_time_to'] = $_GET['add_time_to'];
		}
		if($_GET['d_type']=='undone'){
			$condition['hw_check_status'] = '';
		}else{
			$condition['hw_check_status'] = '1';
		}
		
		$list = $model_jiaoche->getJiaocheList($condition,10);
		Tpl::output('list',$list);
		Tpl::output('show_page',$model_jiaoche->showpage());

		Tpl::showpage('jiaoche.list');
	}
	
	/**
	 * 编辑争议页
	 *
	 */
	public function editOp() {
		$model_jiaoche = Model('hg_cart_jiaoche');
		$id = $_GET['id'];
		$info = $model_jiaoche->getCartJiaocheInfo($id);
		$allUserInfo = $model_jiaoche->getCustomerSellerDealer($info['buy_id'],$info['seller_id']);
		$resupplyTmp = $model_jiaoche->getResupply(array('order_num'=>$info['order_num']));
		$resupply = array();
		if(count($resupplyTmp)>0){
			foreach($resupplyTmp as $k =>$v){
				$resupply[$v['member_type']][] = $v;
				if($v['resupply_date']==''){
					$resupply[$v['member_type'].'_status'] = 'N';//如果补充证据还没提交 状态标出来
				}
			}
		}
		$allPrice = Model()->table('hg_baojia_price')->where(array('bj_id'=>$info['bj_id']))->find();
		$info['contract_money'] =$allPrice['bj_license_plate_break_contract'];
		
		$note = Model()->table('hg_cart_jiaoche_note')->where(array('order_num'=>$info['order_num'],'type'=>1))->select();
		$baojia = Model('hg_baojia')->where(array('bj_id'=>$info['bj_id']))->find();
		$butie= $baojia['bj_zf_butie'];
		$area = Model('area')->where(array('area_id'=>$info['shangpai_area']))->find();
		$shangpai_area = $area['area_name'];
		Tpl::output('allUserInfo',$allUserInfo);
		Tpl::output('resupply',$resupply);
		Tpl::output('note',$note);
		Tpl::output('shangpai_area',$shangpai_area);
		Tpl::output('butie',$butie);
		Tpl::output('info',$info);
		Tpl::showpage('jiaoche.show');
	}
	/**
	 *
	 * 查看客户交车反馈情况
	 * 
	 */
	public function get_user_jiaoche_feedbackOp(){
		$order_num = $_GET['order_num'];
		$map = array('order_num'=>$order_num);
		$verifyTmp = Model()->table('hg_verify')->where($map)->select();
		$verify = array();
		foreach($verifyTmp as $k=>$v){
			$verify[$v['item']] = $v;
		}
		$xzj = Model()->table('hg_user_xzj')->where($map)->select();
		$zengpin = Model()->table('hg_user_zengpin')->where($map)->select();
		Tpl::output('verify',$verify);
		Tpl::output('xzj',$xzj);
		Tpl::output('zengpin',$zengpin);
		Tpl::showpage('jiaoche.feedback');
	}
	
	
	/**
	 * 
	 * 异步处理 数据
	 */
	public function ajax_subOp(){
		$type = $_POST['type'];
		if($type == 1){//处理单个字段更新
			$key = $_POST['field'];
			$val = $_POST['val'];
			$id = $_POST['id'];
			$jiaoche[$key] = $val;
			$jiaoche['updated_at'] = date("Y-m-d H:i:s");
			$condition = array('id'=>$id);
			$e = Model('hg_cart_jiaoche')->editJiaoche($condition,$jiaoche);
		}elseif($type == 2){//交车补充证据
			$data['order_num'] = $_POST['order_num'];
			$data['member_type'] = $_POST['member_type'];
			$data['content'] = $_POST['content'];
			$data['create_at'] = date('Y-m-d H:i:s');
			$e = Model()->table('hg_cart_jiaoche_extinfo')->insert($data);
		}elseif($type == 3){//客户上牌 判定
			$id = $_POST['id'];
			
			$upload = new UploadFile();
			$upload->set('default_dir','../../www/upload/file');
			$upload->set('max_size','10241024');
			$hw_shangpai_check_file = array();
			if(count($_FILES)>0){
				$i=1;
				foreach($_FILES as $k=>$v){
					if(!empty($v['name'])){
						$type =  substr($v['name'], strrpos($v['name'], '.') + 1);
						$upload->file_name = 's'.date('YmdHis').mt_rand(100000, 999999).'.'.$type;
						$result = $upload->upfile($k);
						$img_path = '';
						if($result){
							$img_path   = $upload->file_name;
							$hw_shangpai_check_file[$i] = 'file/'.$img_path;
							$i++;
						}
					}
				}
			}
			$hw_shangpai_check_status = $_POST['is_object']=='N'?2:1;
			$hw_shangpai_excute = $_POST['excute'];//判定执行金额，如果不违约则为空
			$adminInfo = $this->getAdminInfo();
			$updata = array(
							'hw_shangpai_check_file'=>serialize($hw_shangpai_check_file),
							'hw_shangpai_checker'=>$adminInfo['name'],
							'hw_shangpai_check_status'=>$hw_shangpai_check_status,
							'hw_shangpai_check_date'=>date('Y-m-d H:i:s'),
							'hw_shangpai_excute'=>$hw_shangpai_excute,
							);
			$e = Model('hg_cart_jiaoche')->editJiaoche(array('id'=>$id),$updata);
		}elseif($type == 4){//交车备注
			$adminInfo = $this->getAdminInfo();
			$data['noter'] = $adminInfo['name'];
			$data['order_num'] = $_POST['order_num'];
			$data['note'] = $_POST['note'];
			$data['type'] = 1;//1为判断交车的备注
			$data['date'] = date('Y-m-d H:i:s');
			$e = Model()->table('hg_cart_jiaoche_note')->insert($data);
		}elseif($type == 5){//核实交车信息 更新状态
			$adminInfo = $this->getAdminInfo();
			$jiaoche['hw_checker'] = $adminInfo['name'];
			$jiaoche['hw_check_status'] = 1;//审核通过
			$jiaoche['hw_check_date'] = date('Y-m-d H:i:s');
			$condition = array('id'=>$_POST['id']);
			$e = Model('hg_cart_jiaoche')->editJiaoche($condition,$jiaoche);
			$cart_updata = array('cart_status'=>6,'end_user_status'=>600,'end_pdi_status'=>600);
			$e1 = Model('hg_cart')->where(array('order_num'=>$_POST['order_num']))->update($cart_updata);
		}elseif($type == 6){//华车平台补充信息
			$order_num = $_POST['order_num'];
			
			$upload = new UploadFile();
			$upload->set('default_dir','../../www/upload/file');
			$upload->set('max_size','10241024');
			if(!empty($_FILES['file']['name'])){
				$type =  substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1);
				$upload->file_name = 'h'.date('YmdHis').mt_rand(100000, 999999).'.'.$type;
				$result = $upload->upfile('file');
				$img_path = array();
				if($result){
					$img_path[]   = 'file/'.$upload->file_name;
					$updata['order_num'] = $order_num;
					$updata['member_type'] = 3;
					$updata['create_at'] = date("Y-m-d H:i:s");
					$updata['resupply_file'] = serialize($img_path);
					$updata['resupply_date'] = date("Y-m-d H:i:s");
					$e = Model()->table('hg_cart_jiaoche_extinfo')->insert($updata);
				}else{
					$e = false;
				}
			}else{
				$e = false;
			}
		}elseif($type == 7){//华车平台补充信息 删除文件
			$root_dir = BASE_ROOT_PATH.'/www/upload/';
			$id = $_POST['id'];
			$file = Model()->table('hg_cart_jiaoche_extinfo')->where(array('id'=>$id))->find();
			if(!empty($file['resupply_file'])){
				$f = unserialize($file['resupply_file']);
				if(count($f)>0){
					foreach($f as $k=>$v){
						@unlink($root_dir.$v);
					}
				}
			}
			$e = Model()->table('hg_cart_jiaoche_extinfo')->where(array('id'=>$id))->delete();
		}
		if(!$e){
			$data['error_code']='1';
			$data['error_msg']='更新补充说明出错';
		}else{
			$data['error_code']='0';
		}
		echo json_encode($data);
		exit;
	} 

}
