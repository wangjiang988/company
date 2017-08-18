<?php
/**
 * 退款管理
 */
defined('InHG') or exit('Access Invalid!');
class invoiceControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	// 发票列表
	public function indexOp() {
		$model_invoice = Model('invoice');
		
		$_GET['i_type'] = empty($_GET['i_type'])?1:$_GET['i_type'];
		$condition = array();
		//$condition = array('invoice_type'=>0);//判断是否是原始表

		$keyword_type = array('order_num','inv_title');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		
		if($_GET['add_time_from']!=''){
			$condition['inv_apply_date'] = array('gt',$_GET['add_time_from']);
		}
		if($_GET['add_time_to']!=''){
			$condition['inv_apply_date'] = array('lt',$_GET['add_time_to']);
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = trim($_GET['add_time_from']);
			$add_time_to = trim($_GET['add_time_to']);
			if ($add_time_from != '' || $add_time_to != '') {
				$condition['inv_apply_date'] = array('between',array($add_time_from,$add_time_to));
			}
		}
		
		if($_GET['inv_state']!=''){
			$condition['inv_state'] = array('eq',$_GET['inv_state']);
		}
		
		if($_GET['invoice_status']!=''){
			$condition['invoice_status'] = array('eq',$_GET['invoice_status']);
		}
		
		if($_GET['i_type'] == 1){//待处理发票 初始化列表
			$invoiceStatus = array(
					'1'=>'已申请未开',
					'2'=>'平台已开出',						
			);
			$condition['invoice_status'] = array('in',array(1,2));
		}elseif($_GET['i_type'] == 2){//处理完成 初始化列表
			$invoiceStatus = array(
					'3'=>'平台已寄出',
					'4'=>'客户已收到',
					'5'=>'申请重开',
						
			);
			$condition['invoice_status'] = array('in',array(3,4,5));
		}
		
		if($_GET['i_type'] == 3){//超时未开票订单
			if($_GET['key'] !='key'){
				$condition['order_num'] = $_GET['key'];
			}
			if($_GET['add_time_from'] !=''){
				$condition['from_time'] = date("Y-m-d",strtotime($_GET['add_time_from'])-86400*90);
			}
			if($_GET['add_time_to']!=''){
				$condition['to_time'] = date("Y-m-d",strtotime($_GET['add_time_to'])-86400*90);
			}
			
			if($_GET['add_time_from']=='' && $_GET['add_time_to']==''){
				$condition['to_time'] = date("Y-m-d",time()-86400*90);
				$invoiceStatus = array();
			}
			$invoice_list = $model_invoice->getOvertimeCartInvList($condition,10);
			//print_r($condition);
		}else{
			$invoice_list = $model_invoice->getInvList($condition,10);
		}
		Tpl::output('invoice_list',$invoice_list);
		Tpl::output('invoiceStatus',$invoiceStatus);
		Tpl::output('show_page',$model_invoice->showpage());
		Tpl::showpage('invoice.list');
	}
	
	public function showOp() {
		$id = $_GET['inv_id'];
		if($id==''){
			die('非法操作');
		}
		if($_POST['todo'] == 'save_invoice_1' || $_POST['todo'] == 'save_re_invoice_1'){//开票信息填写处理
			$id = $_POST['inv_id'];
			$adminInfo = $this->getAdminInfo();
			//事务处理发票
			Db::beginTransaction();
			try{
				
				if($_POST['inv_state'] ==1){
					$data['ptfp_num'] = array('sign'=>'decrease','value'=>1);
				}else{
					$data['zyfp_num'] = array('sign'=>'decrease','value'=>1);
				}
				$e = Db::update("invoice_num",$data);
				if(!$e){
					throw new Exception('操作失误[更新发票库存]，请返回重新操作');
				}
				$updata = array(
						'invoice_status'=>2,
						'invoice_re_edit'=>1,
						'inv_number'=>$_POST['inv_number'],
						'inv_operator'=>$adminInfo['name'],
						'inv_operator_date'=>date("Y-m-d"),
						'inv_state'=>$_POST['inv_state'],
				);
				$conditionStr = "inv_id= ".$id." and order_num=".$_POST['order_num'];				
				$f = Db::update("invoice",$updata,$conditionStr);
				if(!$f){
					throw new Exception('操作失误[更新发票ID：'.$id.']，请返回重新操作');
				}
			}catch(Exception $e){
				Db::rollback();//回滚
				echo $e->getMessage();
				die('操作失误，请返回重新操作');
			}
			Db::commit();//失误提交
			if($_POST['todo'] == 'save_re_invoice_1'){
				$url = 'index.php?act=invoice&op=show&inv_id='.$_POST['ori_inv_id'];
			}else{
				$url = 'index.php?act=invoice&op=show&inv_id='.$_POST['inv_id'];
			}
			echo "更新成功,三秒后进行跳转";
			echo '<script>setTimeout("window.location=\''.$url.'\'",3000)</script>';
			exit;
		}elseif($_POST['todo'] == 'save_invoice_2' ||$_POST['todo'] == 'save_re_invoice_2'){//寄送开票信息填写处理
			$id = $_POST['inv_id'];
			$updata = array(
					'inv_deliver_date'=>$_POST['inv_deliver_date'],
					'inv_deliver'=>$_POST['inv_deliver'],
					'inv_deliver_number'=>$_POST['inv_deliver_number'],
					'inv_note'=>$_POST['inv_note'],
					'invoice_status'=>3,
					//'inv_state'=>$_POST['inv_state'],
			);
			$f = Model('invoice')->where(array('inv_id'=>$id,'order_num'=>$_POST['order_num']))->update($updata);
			if(!$f){
				die('操作失误[更新发票ID'.$id.']，请返回重新操作');
			}

			if($_POST['todo'] == 'save_re_invoice_2'){
				$url = 'index.php?act=invoice&op=show&inv_id='.$_POST['ori_inv_id'];
			}else{
				$url = 'index.php?act=invoice&op=show&inv_id='.$_POST['inv_id'];
			}
			echo "更新成功,三秒后进行跳转";
			echo '<script>setTimeout("window.location=\''.$url.'\'",3000)</script>';
			exit;
		}elseif($_POST['todo'] == 'save_invoice' || $_POST['todo'] == 'save_re_invoice'){//修改开票信息填写处理
			$id = $_POST['inv_id'];
			$adminInfo = $this->getAdminInfo();
			$updata = array(
					'invoice_re_edit'=>1,
					'inv_number'=>$_POST['inv_number'],
					'inv_operator'=>$adminInfo['name'],
					'inv_operator_date'=>date("Y-m-d"),
					'inv_deliver_date'=>$_POST['inv_deliver_date'],
					'inv_deliver'=>$_POST['inv_deliver'],
					'inv_deliver_number'=>$_POST['inv_deliver_number'],
					'inv_note'=>$_POST['inv_note'],
					'inv_state'=>$_POST['inv_state'],
			);
			$f = Model('invoice')->where(array('inv_id'=>$id,'order_num'=>$_POST['order_num']))->update($updata);
			if(!$f){
				die('操作失误[更新发票ID'.$id.']，请返回重新操作');
			}
			if($_POST['todo'] == 'save_re_invoice'){
				$url = 'index.php?act=invoice&op=show&inv_id='.$_POST['ori_inv_id'];
			}else{
				$url = 'index.php?act=invoice&op=show&inv_id='.$_POST['inv_id'];
			}
			echo "更新成功,三秒后进行跳转";
			echo '<script>setTimeout("window.location=\''.$url.'\'",3000)</script>';
			exit;
		}
		$model_invoice = Model('invoice');
		$invoiceInfo = $model_invoice->getInvInfo(array('inv_id'=>$id));
		
		if($invoiceInfo['invoice_type']==1){//如果判断是重开票，则跳转至原始发票 
			$m = array(
					'order_num'=>$invoiceInfo['order_num'],
					'invoice_type'=>0,
			);
			$invoiceInfo = $model_invoice->getInvInfo($m);
			header("Location:index.php?act=invoice&op=show&inv_id=".$invoiceInfo['inv_id']);
		}
		
		$memberInfo = Model("member")->where(array('member_id'=>$invoiceInfo['member_id']))->find();
		$invoiceConfig = Model('invoice_num')->find();
		if($invoiceInfo['inv_state']==1){
			$invoiceNum = $invoiceConfig['ptfp_num'];
		}else{
			$invoiceNum = $invoiceConfig['zyfp_num'];
		}
		$express = Model('express')->where(array('e_state'=>1))->select();
		
		if($invoiceInfo['invoice_status']==5){
			$condition = array('order_num'=>$invoiceInfo['order_num'],'invoice_type'=>1);
			$invoiceReInfo =  Model('invoice')->where($condition)->order("inv_id desc")->find();
			Tpl::output('invoiceReInfo',$invoiceReInfo);
		}
		Tpl::output('express',$express);
		Tpl::output('invoiceNum',$invoiceNum);
		Tpl::output('memberInfo',$memberInfo);
		Tpl::output('invoiceInfo',$invoiceInfo);
		Tpl::showpage('invoice.view');
	}
	
	/**
	 * 空白发票列表
	 */
	
	public function invoice_manage_listOp(){
		$model_invoice = Model('invoice_num');
		$invoiceNum = $model_invoice->find();
		$invoiceNum['ptfp_update']=Model()->table('invoice_log')->where(array('type'=>1))->order('date desc')->limit(1)->find();
		$invoiceNum['zyfp_update']=Model()->table('invoice_log')->where(array('type'=>2))->order('date desc')->limit(1)->find();
		Tpl::output('invoiceNum',$invoiceNum);
		Tpl::showpage('invoice.manage.list');
	}
	
	/**
	 * 发票日志列表
	 */
	
	public function invoice_log_listOp(){
		$type= empty($_GET['invoice_type'])?1:$_GET['invoice_type'];
		$log_model = Model('invoice_log');
		$log=$log_model->getInvLogList(array('type'=>$type),10);
		Tpl::output('log',$log);
		Tpl::output('show_page',$log_model->showpage());
		Tpl::showpage('invoice.log.list');
	}
	
	public function invoice_manageOp() {
		
		if(!empty($_POST['todo'])){
			$adminInfo = $this->getAdminInfo();
			$data = array();
			if($_POST['todo'] == 'ptfp-add-num'){
				$newLog = array(
						'op_name'=>$adminInfo['name'],
						'add_num'=>$_POST['add_num'],
						'type'=>1,
						'note'=>'普通发票增加'.$_POST['add_num']."份",
				);
				$data['ptfp_num'] = array('sign'=>'increase','value'=>intval($_POST['add_num']));
			}elseif($_POST['todo'] == 'ptfp-period'){
				$newLog = array(
						'op_name'=>$adminInfo['name'],
						'period'=>$_POST['ptfp_period'],
						'type'=>1,
						'note'=>'普通发票取票周期修改为'.$_POST['ptfp_period']."天",
				);
				$data['ptfp_period'] = $_POST['ptfp_period'];
			}elseif($_POST['todo'] == 'ptfp-max'){
				$newLog = array(
						'op_name'=>$adminInfo['name'],
						'max'=>$_POST['ptfp_max'],
						'type'=>1,
						'note'=>'普通发票每次取票份数修改为'.$_POST['ptfp_max']."份",
				);
				$data['ptfp_max'] = $_POST['ptfp_max'];
			}elseif($_POST['todo'] == 'zyfp-add-num'){
				$newLog = array(
						'op_name'=>$adminInfo['name'],
						'add_num'=>$_POST['add_num'],
						'type'=>2,
						'note'=>'专用发票增加'.$_POST['add_num']."份",
				);
				
				$data['zyfp_num'] = array('sign'=>'increase','value'=>intval($_POST['add_num']));
			}elseif($_POST['todo'] == 'zyfp-period'){
				$newLog = array(
						'op_name'=>$adminInfo['name'],
						'period'=>$_POST['zyfp_period'],
						'type'=>2,
						'note'=>'专用发票取票周期修改为'.$_POST['zyfp_period']."天",
				);
				$data['zyfp_period'] = $_POST['zyfp_period'];
			}elseif($_POST['todo'] == 'zyfp-max'){
				$newLog = array(
						'op_name'=>$adminInfo['name'],
						'max'=>$_POST['zyfp_max'],
						'type'=>2,
						'note'=>'专用发票每次取票份数修改为'.$_POST['zyfp_max']."份",
				);
				$data['zyfp_max'] = $_POST['zyfp_max'];
			}
			if(!empty($newLog)){
				$e = Model()->table('invoice_log')->insert($newLog);
				if(!$e){
					die('数据更新失败-1');
				}
			}
			if(!empty($data)){
				$d = Db::update("invoice_num",$data);
				if(!$d){
					die('数据更新失败-2');
				}
				$invoice_type = empty($_REQUEST['invoice_type'])?1:$_REQUEST['invoice_type'];
				header("Location:index.php?act=invoice&op=invoice_manage&invoice_type=".$invoice_type);
			}
		}
		$model_invoice = Model('invoice_num');
		$invoiceNum = $model_invoice->find();
		Tpl::output('invoiceNum',$invoiceNum);
		Tpl::showpage('invoice.mange');
	}
	
	public function ajaxOp() {
		$type= $_POST['type'];
		if($type == 'invoice_re_edit'){
			$inv_id = $_POST['inv_id'];
			$order_num = $_POST['order_num'];
			$map = array(
					'inv_id'=>$inv_id,
					'order_num'=>$order_num,
			);
			$d = Model('invoice')->where($map)->update(array('invoice_re_edit'=>0));
			if(!$d){
				$data['error_code'] = '1';
				$data['error_msg'] = '更新失败[发票ID'.$inv_id.']';
			}else{
				$data['error_code'] = '0';
			}
			
		}elseif($type=='invoice_rec'){
			$inv_id = $_POST['inv_id'];
			$order_num = $_POST['order_num'];
			$map = array(
					'inv_id'=>$inv_id,
					'order_num'=>$order_num,
			);
			$adminInfo = $this->getAdminInfo();
			$updata = array(
					'return_rec_name'=>$adminInfo['name'],
					'return_rec_date'=>date('Y-m-d'),
			);
			$d = Model('invoice')->where($map)->update($updata);
			if(!$d){
				$data['error_code'] = '1';
				$data['error_msg'] = '更新失败[发票ID'.$inv_id.']';
			}else{
				$data['error_code'] = '0';
			}
		}elseif($type=='redo_invoice'){
			$inv_id = $_POST['inv_id'];
			$order_num = $_POST['order_num'];
			$map = array(
					'inv_id'=>$inv_id,
					'order_num'=>$order_num,
			);
			$re_do_status = intval($_POST['re_do_status']);
			$adminInfo = $this->getAdminInfo();
			$updata = array(
							're_do_status'=>$re_do_status,
							're_do_operator'=>$adminInfo['name'],
							're_do_date'=>date('Y-m-d H:i:s'),
						);
			$d = Model('invoice')->where($map)->update($updata);
			if(!$d){
				$data['error_code'] = '1';
				$data['error_msg'] = '更新失败[发票ID'.$inv_id.']';
			}else{
				$data['error_code'] = '0';
			}
		}else{
			$data['error_code'] = '1';
			$data['error_msg'] = '更新失败';
		}
		echo json_encode($data);
	}
	
	public function overtime_invoiceOp(){
		$order_num = $_GET['order_num'];
		$map = array('order_num'=>$order_num);
		$order = Model('hg_cart')->where($map)->find();
		$order['inv_money'] = 2000;//测试开票金额
		$memberInfo = Model("member")->where(array('member_id'=>$order['buy_id']))->find();
		Tpl::output('memberInfo',$memberInfo);
		Tpl::output('order',$order);
		Tpl::showpage('invoice.overtime');
	}
	
	/**
	 * 发票超时 后台开启重开状态
	 * 
	 * 
	 */
	
	public function agree_invoiceOp(){
		$order_num = $_POST['order_num'];
		$map = array('order_num'=>$order_num);
		$order = Model('hg_cart')->where($map)->find();
		//print_r($order);
		$dataUp = array(
				'overtime_invoice_status'=>1,
				'updated_at'=>$order['updated_at'],
		);
		$f = Model('hg_cart')->where($map)->update($dataUp);
		if(!$f){
			$data['error_code'] = '1';
			$data['error_msg'] = '更新失败';
		}else{
			$data['error_code'] = '0';
			$data['error_msg'] = '更新成功';
			
		}
		echo json_encode($data);
	}
	
}
