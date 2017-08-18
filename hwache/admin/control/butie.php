<?php
/**
 * 补贴管理
 */
defined('InHG') or exit('Access Invalid!');
class butieControl extends SystemControl{
	public function __construct(){
		parent::__construct();
	}
	// 争议列表
	public function indexOp() {
		
		
		$model_jiaoche = Model('hg_cart_jiaoche');
		$condition = array();
		
		if($_GET['order_num']!=''){
			$condition['order_num'] = $_GET['order_num'];
		}
		if($_GET['seller_name']!=''){
			$condition['seller_name'] = $_GET['seller_name'];
		}
		if($_GET['status_1']!=''){
			$condition['status_1'] = $_GET['status_1'];
		}
		if($_GET['status_2']!=''){
			$condition['status_2'] = $_GET['status_2'];
		}
		if($_GET['add_time_from']!=''){
			$condition['add_time_from'] = $_GET['add_time_from'];
		}
		if($_GET['add_time_to']!=''){
			$condition['add_time_to'] = $_GET['add_time_to'];
		}
		$_GET['d_type'] = empty($_GET['d_type'])?1:$_GET['d_type'];
		$condition['d_type'] = $_GET['d_type'];
		
		
		$list = $model_jiaoche->getButieList($condition,10);
		Tpl::output('list',$list);
		Tpl::output('show_page',$model_jiaoche->showpage());

		Tpl::showpage('butie.list');
	}
	
	/**
	 * 编辑争议页
	 *
	 */
	public function editOp() {
		$model_jiaoche = Model('hg_cart_jiaoche');
		$id = $_GET['id'];
		$info = $model_jiaoche->getButieInfo($id);
		$allUserInfo = $model_jiaoche->getCustomerSellerDealer($info['buy_id'],$info['seller_id']);
		$note = Model()->table('hg_cart_jiaoche_note')->where(array('order_num'=>$info['order_num'],'type'=>2))->select();
		
		Tpl::output('allUserInfo',$allUserInfo);
		Tpl::output('info',$info);
		Tpl::output('note',$note);
		Tpl::showpage('butie.show');
	}
	
	
	/**
	 * 
	 * 异步处理 数据
	 */
	public function ajax_subOp(){
		$type = $_POST['type'];
		if($type == 1){//处理单个字段更新
			$id = $_POST['id'];
			$adminInfo = $this->getAdminInfo();
			$note['noter'] = $adminInfo['name'];
			$note['order_num'] = $_POST['order_num'];
			$note['note'] = $_POST['note'];
			$note['type'] = 2;//2为判断补贴的备注
			$note['date'] = date('Y-m-d H:i:s');
			
			$upload = new UploadFile();
			$upload->set('default_dir','../../www/upload/file');
			$upload->set('max_size','10241024');
			$note_file = array();
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
							$note_file[$i] = 'file/'.$img_path;
							$i++;
						}
					}
				}
			}
			$note['file']=serialize($note_file) ;
			$e = Model()->table('hg_cart_jiaoche_note')->insert($note);
		}elseif($type == 2){//华车平台补充补贴备注 删除文件
			$root_dir = BASE_ROOT_PATH.'/www/upload/';
			$id = $_POST['id'];
			$key =$_POST['key'];
			
			$note = Model()->table('hg_cart_jiaoche_note')->where(array('id'=>$id))->find();
			if(!empty($note['file'])){
				$f = unserialize($note['file']);
				if(count($f)>0){
					@unlink($root_dir.$f[$key]);
				}
				unset($f[$key]);
				$updata['file'] = serialize($f);
				$e = Model()->table('hg_cart_jiaoche_note')->where(array('id'=>$id))->update($updata);
			}else{
				$e =false;
			}
			
		}elseif($type == 3){//补贴结果处理 更新
			$id = $_POST['id'];
			$status = $_POST['status'];
			$adminInfo = $this->getAdminInfo();
			$reason = $_POST['reason'];
			$updata = array(
					'hw_butie_status'=>$status,
					'hw_butie_check_date'=>date('Y-m-d H:i:s'),
					'hw_butie_checker'=>$adminInfo['name'],
					'hw_butie_check_reason'=>$reason,
					);
			$e = Model()->table('hg_cart_jiaoche')->where(array('id'=>$id))->update($updata);
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
