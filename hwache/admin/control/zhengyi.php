<?php
/**
 * 争议管理
 */
defined('InHG') or exit('Access Invalid!');
class zhengyiControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		$model_refund = Model('refund_return');
		$model_refund->getRefundStateArray();
	}
	// 争议列表
	public function indexOp() {
		$model_dispute = Model('hg_dispute');
		$condition = array();
		if($_GET['order_num']!=''){
			$condition['order_num'] = $_GET['order_num'];
		}
		if($_GET['who']!=''){
			$condition['who'] = $_GET['who'];
		}
		if($_GET['do_status']!=''){
			$condition['do_status'] = $_GET['do_status'];
		}
		if($_GET['add_time_from']!=''){
			$condition['add_time_from'] = $_GET['add_time_from'];
		}
		if($_GET['add_time_to']!=''){
			$condition['add_time_to'] = $_GET['add_time_to'];
		}
		if($_GET['d_type']=='done'){
			if($_GET['breaker']==''){
				$condition['breaker'] = 'all';
			}else{
				$condition['breaker'] = $_GET['breaker'];
			}
			
		}
		
		$dispute_list = $model_dispute->getDisputeList($condition,10);

		//print_r($refund_list);
		Tpl::output('dispute_list',$dispute_list);
		Tpl::output('show_page',$model_dispute->showpage());

		Tpl::showpage('zhengyi.list');
	}
	
	/**
	 * 编辑争议页
	 *
	 */
	public function editOp() {
		$dispute_model = Model('hg_dispute');
		$condition = array();
		$condition['dispute_id'] = intval($_GET['dispute_id']);
		$dispute = $dispute_model->getDisputeInfo($condition);
		$evidenceAll = $dispute_model->getEvidence($_GET['dispute_id']);
		$evidence['dispute'] = array();
		$evidence['defend'] = array();
		if(count($evidenceAll)>0){
			foreach($evidenceAll as $k=>$v){
				if($v['defend_id'] =='' || $v['defend_id']==0){
					$evidence['dispute'][] = $v;
				}else{
					$evidence['defend'][] = $v;
				}
			}
		}
		//print_r($evidence);
		if(!empty($dispute['itemid'])){
			$search['mediate_id']=$dispute['itemid'];
			$search['dispute_id']=$dispute['id'];
			$search['defend_id']=$dispute['defend_id'];
			$mediate_assistant = Model('hg_mediate_assistant')->getMediateAssistantList($search);
			
			
		}else{
			$mediate_assistant = array();
		}
		
		if($dispute['mediate_team_ids']!=''){//获取裁判团成员数据
			$sqlStr = 'admin.admin_id in('.$dispute['mediate_team_ids'].')';
			$users = Model('hg_mediate')->getAdminForJudgmentList($sqlStr);
			
			$condition_s = array('mediate_id'=>$dispute['itemid']);
			$adviceArray = Model('hg_mediate')->table('hg_mediate_team_advice')->where($condition_s)->select();
			$users_advice = array();
			if(count($adviceArray)>0){
				foreach($adviceArray as $k=>$v){
					$users_advice[$v['m_admin_id']] = $v;
				}
			}
			
		}else{
			$users = array();
			$users_advice =array();
		}
		$service_price = array('platform'=>5000,'seller'=>2000);//测试数据 上线需要重新部署neettodo
		Tpl::output('service_price',$service_price);
		$adminInfo = $this->getAdminInfo();
		Tpl::output('admin_info',$adminInfo);
		Tpl::output('users',$users);
		Tpl::output('users_advice',$users_advice);
		Tpl::output('dispute',$dispute);
		Tpl::output('evidence',$evidence);
		Tpl::output('mediate_assistant',$mediate_assistant);
		Tpl::showpage('zhengyi.edit');
	}
	
	/**
	 * 为争议选择裁判团
	 * 
	 */
	
	public function judgmentOp(){
		$mediate_id = $_GET['mediate_id'];
		$sqlStr = '';
		$users = Model('hg_mediate')->getAdminForJudgmentList($sqlStr);
		
		$dispute_model = Model('hg_dispute');
		$condition = array();
		$condition['dispute_id'] = intval($_GET['dispute_id']);
		$dispute = $dispute_model->getDisputeInfo($condition);
		
		Tpl::output('users',$users);
		Tpl::output('dispute',$dispute);
		Tpl::showpage('zhengyi.judgment');
		
	}
	/**
	 * 
	 * 异步处理 数据
	 */
	public function ajax_subOp(){
		$type = $_POST['type'];
		if($type == 1){//处理补充证据
			$dispute_id = $_POST['dispute_id'];
			$defend_id = $_POST['defend_id'];
			if($dispute_id!=''){
				$model = 'hg_dispute';
				$condition = array('id'=>$dispute_id);
			}else{
				$model = 'hg_defend';
				$condition = array('id'=>$defend_id);
			}
			$resupply = $_POST['resupply'];
			$up_field = $_POST['up_field'];
			$resupplyArray = '';
			foreach($resupply as $k=>$v){
				if($v !='dispute_other_wenti'){
					$resupplyArray[] = $v;
				}else{
					$resupplyArray[] = $_POST['dispute_other_wenti'];
				}
			}
			
			$updata = array(
						$up_field=>implode('、',$resupplyArray),
						'resupply_date'=>date('Y-m-d H:i:s'),
			);
			
			$e = Model($model)->where($condition)->update($updata);
			
		}elseif($type == 2){//处理时限
			$dispute_id = $_POST['dispute_id'];
			$defend_id = $_POST['defend_id'];
			if($dispute_id!=''){
				$model = 'hg_dispute';
				$condition = array('id'=>$dispute_id);
			}else{
				$model = 'hg_defend';
				$condition = array('id'=>$defend_id);
			}
			
			$day = intval($_POST['day']);
			$hour = intval($_POST['hour']);
			$up_field = $_POST['up_field'];
			$date_count = $day*24+$hour;
			$updata = array($up_field=>$date_count);
			$e = Model($model)->where($condition)->update($updata);
		}elseif($type==3){//提交 调解内容
			$dispute_id = $_POST['dispute_id'];
			$defend_id = $_POST['defend_id'];
			$order_num = $_POST['order_num'];
			$content = $_POST['content'];
			$note = $_POST['note'];
			
			$upload = new UploadFile();
			$upload->set('default_dir','../../www/upload/evidence');
			$upload->set('max_size','10241024');
			$evidence = array();
			if(count($_FILES)>0){
				$i=1;
				foreach($_FILES as $k=>$v){
					if(!empty($_FILES[$k]['name'])){
						$type =  substr($v['name'], strrpos($v['name'], '.') + 1);
						$upload->file_name = date('YmdHis').mt_rand(100000, 999999).'.'.$type;
						$result = $upload->upfile($k);
						$img_path = '';
						if($result){
							$img_path   = $upload->file_name;
							$evidence[$i] = array('file'=>$img_path,'tb'=>$_POST[$k.'_tb']);
							$i++;
						}
					}
				}
			}

			$adminInfo = $this->getAdminInfo();
			$insertData=array(
					'order_num'=>$_POST['order_num'],
					'dispute_id'=>$dispute_id,
					'defend_id'=>$defend_id,
					'tiaojie_operator'=>$adminInfo['name'],
					'content'=>$content,
					'evidence'=>serialize($evidence),
					'note'=>$note,
			);
			$model_mediate = Model('hg_mediate');				
			$e=$model_mediate->add($insertData);
			header("Location:index.php?act=zhengyi&op=edit&dispute_id=".$_POST['dispute_id']);
		}elseif($type==4){//提交 更新调解方案
			$dispute_id = $_POST['dispute_id'];
			$defend_id = $_POST['defend_id'];
			$mediate_id = $_POST['mediate_id'];
			$content = $_POST['content'];
			$note = $_POST['note'];
			
			//$evidence = $_POST['evidence'];
			$upload = new UploadFile();
			$upload->set('default_dir','../../www/upload/evidence');
			$upload->set('max_size','10241024');
			$m_evidence = array();
			if(count($_FILES)>0){
				$i=1;
				foreach($_FILES as $k=>$v){
					if(!empty($_FILES[$k]['name'])){
						$type =  substr($v['name'], strrpos($v['name'], '.') + 1);
						$upload->file_name = date('YmdHis').mt_rand(100000, 999999).'.'.$type;
						$result = $upload->upfile($k);
						$img_path = '';
						if($result){
							$img_path   = $upload->file_name;
							$m_evidence[$i] = array('file'=>$img_path,'tb'=>$_POST[$k.'_tb']);
							$i++;
						}
					}
				}
			}
			$adminInfo = $this->getAdminInfo();
			$insertData=array(
					'mediate_id'=>$mediate_id,
					'dispute_id'=>$dispute_id,
					'defend_id'=>$defend_id,
					'tiaojie_operator'=>$adminInfo['name'],
					'm_content'=>$content,
					'm_note'=>$note,
					'm_status'=>0,
					'm_evidence'=>serialize($m_evidence),
					'm_create_at'=>date('Y-m-d H:i:s'),
			);
			//print_r($insertData);exit;
			$model_mediate = Model('hg_mediate_assistant');
			$e=$model_mediate->addAssitant($insertData);
			$e1 = Model('hg_mediate')->table('hg_mediate')->where(array('itemid'=>$mediate_id))->update(array('status'=>3));
			if(!$e1){
				$data['error_code']='1';
				$data['error_msg']='更新补充说明出错';
				echo json_encode($data);
				exit;
			}
			header("Location:index.php?act=zhengyi&op=edit&dispute_id=".$_POST['dispute_id']);
			
		}elseif($type == 5){//裁判团成员选择
			$mediate_id = $_POST['mediate_id'];
			$dispute_id = $_POST['dispute_id'];
			$defend_id = $_POST['defend_id'];
			$user = $_POST['user'];
			if(count($user)==0 || !is_array($user)){
				$e = false;
				continue;
			}
			$mediate_team_ids = implode($user,',');
			$condition= array('itemid'=>$mediate_id);
			$updata = array('mediate_team_ids'=>$mediate_team_ids,'status'=>4);
			$e = Model('hg_mediate')->table('hg_mediate')->where($condition)->update($updata);
		}elseif($type == 6){//裁判团成员 提交判断
			$mediate_id = $_POST['mediate_id'];
			$m_advice = $_POST['m_advice'];
			$m_evidence = $_POST['m_evidence'];
			$adminInfo = $this->getAdminInfo();
			
			$updata = array('mediate_id'=>$mediate_id,
					'm_advice'=>$m_advice,
					'm_content'=>$m_evidence,
					'm_date'=>date('Y-m-d H:i:s'),
					'm_admin_id'=>$adminInfo['id'],
					'm_admin_name'=>$adminInfo['name'],
			);
			$e = Model('hg_mediate')->addAdvice($updata);
		}elseif($type == 7){//最终裁判提交 判定
			$mediate_id = $_POST['mediate_id'];
			$breaker = $_POST['breaker'];
			$breaker_content = $_POST['breaker_content'];
			$dispute_needtodo = $_POST['dispute_needtodo'];
			$defend_needtodo = $_POST['defend_needtodo'];
			$breaker_excute['dispute']= array();
			$breaker_excute['defend']= array();
			foreach($dispute_needtodo as $k=>$v){
				$breaker_excute['dispute'][] = array('title'=>urldecode($k),'money'=>$v);
			}
			foreach($defend_needtodo as $k=>$v){
				$breaker_excute['defend'][] = array('title'=>urldecode($k),'money'=>$v);
			}
			$condition= array('itemid'=>$mediate_id);
			$updata = array('breaker'=>$breaker,
							'breaker_content'=>$breaker_content,
							'breaker_excute'=>serialize($breaker_excute),
							'breaker_date'=>date("Y-m-d H:i:s"),
							'status'=>5,
							);
			$e = Model('hg_mediate')->table('hg_mediate')->where($condition)->update($updata);
			if($breaker ==3){
				$updateCart = array('cart_status'=>5,'cart_sub_status'=>509);
			}else{
				$updateCart = array('cart_status'=>1000,'cart_sub_status'=>1007);
			}
			$conditionCart = array('order_num'=>$_POST['order_num']);
			$e1 = Model('hg_cart')->table('hg_cart')->where($conditionCart)->update($updateCart);
			if(!$e1){
				$e = false;
			}
		}elseif($type==8){//取消调解的证据上传，即删除
			$mediate_id = $_POST['mediate_id'];
			$key = $_POST['key'];
			$mediate_assistant = $_POST['mediate_assistant'];
			
			if($mediate_assistant=='N'){//N是主调解，其他的都是调解副表ID
				$row = Model('hg_mediate')->table('hg_mediate')->where(array('itemid'=>$mediate_id))->find();
				$evidence = unserialize($row['evidence']);
				unset($evidence[$key]);
				$updata = array('evidence'=>serialize($evidence));
				$e = Model('hg_mediate')
						->table('hg_mediate')
						->where(array('itemid'=>$mediate_id))
						->update($updata);
			}else{
				$row = Model('hg_mediate_assistant')
							->table('hg_mediate_assistant')
							->where(array('id'=>$mediate_assistant))
							->find();
				$evidence = unserialize($row['m_evidence']);
				unset($evidence[$key]);
				$updata = array('m_evidence'=>serialize($evidence));
				$e = Model('hg_mediate_assistant')
						->table('hg_mediate_assistant')
						->where(array('id'=>$mediate_assistant))
						->update($updata);
			}
			
			
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
