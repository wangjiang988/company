<?php
/**
 * 交车文件 售方
 */
defined('InHG') or exit('Access Invalid!');
class dealer_calcControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	// 发票列表
	public function indexOp() {
		$_GET['i_type'] = $_GET['i_type']==''?1:$_GET['i_type'];
		$model = Model('dealer_calc_file');
		$condition = array();
		if($_GET['i_type']==1){
			$condition['status'] = 0;
		}elseif($_GET['i_type']==2){
			$condition['status'] = 2;
		}elseif($_GET['i_type']==3){
			$condition['status'] = 1;
		}
		if($_GET['add_time_from']!=''){
			$condition['send_date'] = array('gt',$_GET['add_time_from']);
		}
		if($_GET['add_time_to']!=''){
			$condition['send_date'] = array('lt',$_GET['add_time_to']);
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = trim($_GET['add_time_from']);
			$add_time_to = trim($_GET['add_time_to']);
			if ($add_time_from != '' || $add_time_to != '') {
				$condition['send_date'] = array('between',array($add_time_from,$add_time_to));
			}
		}
		$keyword_type = array('seller_name','deliver_num');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		$list = $model->getCalcFileList($condition,10);
		Tpl::output('list',$list);
		Tpl::output('show_page',$model->showpage());
		Tpl::showpage('dealer_calc.list');
		
	}
	
	public function showOp() {
		$id = intval($_GET['id']);
		$model = Model('dealer_calc_file');
		$condition = array('id'=>$id);
		$info = $model->where($condition,10)->find();
		Tpl::output('info',$info);
		$sellerInfo = Model('member')->where(array('member_id'=>$info['seller_id']))->find();
		$seller = Model('seller')->where(array('member_id'=>$info['seller_id']))->find();
		$sellerInfo['wenjian'] = $seller['wenjian'];
		Tpl::output('sellerInfo',$sellerInfo);
		Tpl::showpage('dealer_calc.show');
	}
	
	public function show_logOp() {
		$seller_id = intval($_GET['seller_id']);
		$model = Model('dealer_calc_file_log');
		$condition = array('seller_id'=>$seller_id);
		$list = $model->getCalcFileLogList($condition,10);
		Tpl::output('list',$list);
		Tpl::output('show_page',$model->showpage());
		Tpl::showpage('dealer_calc_file_log.list');
		
	}
	
	/**
	 * ajax
	 * 结算文件确认
	 * 
	 */
	public function sure_fileOp() {
		$id = intval($_POST['id']);
		if($id == 0){//ID为空 不做处理 返回失败
			$data['error_code'] = '1';
			$data['error_msg'] = '更新失败';
			echo json_encode($data);
			exit;
		}
		
		$seller_id = $_POST['seller_id'];
		$adminInfo = $this->getAdminInfo();
		//事务处理发票
		Db::beginTransaction();
		try{
			if($_POST['status']==0){//还没有确认 新处理
				$file_num = intval($_POST['file_num']);
				$updata1['wenjian'] = array('sign'=>'increase','value'=>intval($_POST['file_num']));
				$e1 = Db::update("seller",$updata1,'member_id='.$seller_id);
				if(!$e1){
					throw new Exception('操作失误[更新经销商代理结算文件1]，请返回重新操作');
				}
				
				$updata2 = array(
						'sure_num'=>intval($_POST['file_num']),
						'receive_date'=>date("Y-m-d H:i:s"),
						'receiver_id'=>$adminInfo['id'],
						'status'=>2,//已结收到文件状态
				);
				$conditionStr = "id= ".$id;
				$e2 = Db::update("hg_dealer_calc_file",$updata2,$conditionStr);
				if(!$e2){
					throw new Exception('操作失误[更新经销商代理结算文件2]，请返回重新操作');
				}
				
				$sellerInfo = Model('seller')->getSellerInfo(array('member_id'=>$seller_id));
				$insertData=array(
						'op_name'=>$adminInfo['name'],
						'op_id'=>$adminInfo['id'],
						'num'=>'+'.$file_num,
						'date'=>date("Y-m-d H:i:s"),
						'seller_id'=>$seller_id,
						'current_file_num'=>$sellerInfo['wenjian'],
						'note'=>'确认收到新可用文件',
				);
				$e3 = Db::insert('hg_dealer_calc_file_log',$insertData);
				if(!$e3){
					throw new Exception('操作失误[更新经销商代理结算文件3]，请返回重新操作');
				}
			}elseif($_POST['status']==2){//已结确认 直接更新
				$model = Model('dealer_calc_file');
				$condition = array('id'=>$id);
				$ori_info = $model->where($condition)->find();
				$file_num = intval($_POST['file_num'])-$ori_info['sure_num'];
				if($file_num>0){
					$updata1['wenjian'] = array('sign'=>'increase','value'=>abs($file_num));
				}else{
					$updata1['wenjian'] = array('sign'=>'decrease','value'=>abs($file_num));
				}
				$e1 = Db::update("seller",$updata1,'member_id='.$seller_id);
				if(!$e1){
					throw new Exception('操作失误[更新经销商代理结算文件1]，请返回重新操作');
				}
				$updata2 = array(
						'sure_num'=>abs(intval($_POST['file_num'])),
						'receive_date'=>date("Y-m-d H:i:s"),
						'receiver_id'=>$adminInfo['id'],
				);
				$conditionStr = "id= ".$id;
				$e2 = Db::update("hg_dealer_calc_file",$updata2,$conditionStr);
				if(!$e2){
					throw new Exception('操作失误[更新经销商代理结算文件2]，请返回重新操作');
				}
				$sellerInfo = Model('seller')->getSellerInfo(array('member_id'=>$seller_id));
				$file_num_str = $file_num>0?"+":"-";
				$insertData=array(
						'op_name'=>$adminInfo['name'],
						'op_id'=>$adminInfo['id'],
						'num'=>$file_num_str.abs($file_num),
						'date'=>date("Y-m-d H:i:s"),
						'seller_id'=>$seller_id,
						'current_file_num'=>$sellerInfo['wenjian'],
						'note'=>'更改确认收到新可用文件',
				);
				$e3 = Db::insert('hg_dealer_calc_file_log',$insertData);
				if(!$e3){
					throw new Exception('操作失误[更新经销商代理结算文件3]，请返回重新操作');
				}
				
			}
			
		}catch(Exception $e){
			Db::rollback();//回滚
			$data['error_code'] = '1';
			$data['error_msg'] = '操作失误[更新经销商代理结算文件]，请返回重新操作';
			echo json_encode($data);
			exit;
		}
		Db::commit();//失误提交
		$data['error_code'] = '0';
		echo json_encode($data);
		exit;
	}
	public function cancel_fileOp() {
		$id = intval($_POST['id']);
		if($id == 0){//ID为空 不做处理 返回失败
			$data['error_code'] = '1';
			$data['error_msg'] = '更新失败';
			echo json_encode($data);
			exit;
		}
		$updata = array(
				'status'=>1,
		);
		$conditionStr = "id= ".$id;
		$e = Db::update("hg_dealer_calc_file",$updata,$conditionStr);
		if(!$e){
			$data['error_code'] = '1';
			$data['error_msg'] = '操作失误[撤销结算文件]，请返回重新操作';
		}else{
			$data['error_code'] = '0';
		}
		echo json_encode($data);
		exit;
	}
	
	public function invoice_listOp(){
		$invoice_type = empty($_GET['invoice_type'])?"undo":$_GET['invoice_type'];
		$model = Model("hg_cart");
		
		$keyword_type = array('member_name','member_truename');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)) {
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if($_GET['order_num']!=''){
			$condition['order_num'] = $_GET['order_num'];
		}
		if($_GET['inv_no']!=''){
			$condition['inv_no'] = $_GET['inv_no'];
		}
		
		if($invoice_type == 'undo'){
			$condition['pdi_calc_status'] = 1;
			$list = $model->getCartInvoiceList($condition,10);
		}else{
			$condition['pdi_calc_status'] = 2;
			$list = $model->getCartInvoiceList2($condition,10);
		}
		//print_r($condition);
		Tpl::output('invoice_type',$invoice_type);
		Tpl::output('list',$list);
		Tpl::output('show_page',$model->showpage());
		Tpl::showpage('dealer_invoice.list');
	}
	
	public function show_invoiceOp(){
		$order_num = $_GET['order_num'];
		$model = Model("hg_cart");
		$condition = array('order_num'=>$order_num);
		$info = $model->getCartInvoiceOne($condition);
		$info['inv_money'] = '2000';//测试开票数据 needtodo
		if($_GET['inv_id']>0){
			$inv_info = Model('dealer_invoice')->getInvoiceInfo(array('inv_id'=>$_GET['inv_id']));
		}else{
			$inv_info = array();
		}
		Tpl::output('info',$info);
		Tpl::output('inv_info',$inv_info);
		Tpl::showpage('dealer_invoice.show');
	}
	//ajax 新增 编辑售方的发票
	public function edit_invOp(){
		$inv_id = intval($_POST['inv_id']);
		$updata['order_num'] = $_POST['order_num'];
		$updata['inv_no'] = $_POST['inv_no'];
		$updata['note'] = $_POST['note'];
		$updata['inv_money'] = $_POST['inv_money'];
		$updata['seller_id'] = $_POST['seller_id'];
		$adminInfo = $this->getAdminInfo();
		if($inv_id>0){//update
			$updata['op_name']=$adminInfo['name'];
			$e = Model('dealer_invoice')->update($updata,array('inv_id'=>$inv_id));
		}else{//add
			
			//事务处理发票
			Db::beginTransaction();
			try{
				
				$updata['op_name']=$adminInfo['name'];
				$e = Db::insert("invoice_seller",$updata);
				$f = Db::update('hg_cart',array('pdi_calc_status'=>2),'order_num='.$updata['order_num']);
			}catch(Exception $e){
					Db::rollback();//回滚
					$data['error_code'] = '1';
					$data['error_msg'] = '操作失误[更新经销商代理结算文件]，请返回重新操作';
					echo json_encode($data);
					exit;
			}
			Db::commit();//失误提交
		}
		
		if(!$e){
			$data['error_code'] = '1';
			$data['error_msg'] = '操作失误[撤销结算文件]，请返回重新操作';
		}else{
			$data['error_code'] = '0';
			if($inv_id>0){
				$data['inv_id'] = $inv_id;
			}else{
				$data['inv_id'] = $e;
			}
		}
		echo json_encode($data);
		exit;
		
	}
}
