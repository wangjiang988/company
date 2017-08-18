<?php
/**
 * 结算管理
 */
defined('InHG') or exit('Access Invalid!');
class store_calcControl extends BaseSellerControl {
    
	

    public function __construct() {
    	parent::__construct() ;
    }

	/**
	 * 结算文件首页
	 *
	 */
    public function indexOp() {
    	if($_POST['todo'] !=''){
    		$data ['file_num'] = $_POST['file_num'];
    		$data ['deliver'] = $_POST['deliver'];
    		$data ['deliver_num'] = $_POST['deliver_num'];
    		$data ['seller_id'] = $_SESSION['member_id'];
    		$data ['seller_name'] = $_SESSION['member_name'];
    		$mem = Model('member')->where(array('member_id'=>$_SESSION['member_id']))->find();
    		$data ['seller_truename'] = $mem['member_truename'];
    		$data ['status'] = 0;
    		$data ['send_date'] = date('Y-m-d H:i:s');
    		$row = Model('dealer_calc_file')->insert($data);
    		if(!$row){
    			die('更新失败');
    		}
    		$url = "index.php?act=store_calc&op=index";
    		echo "更新成功,三秒后进行跳转";
    		echo '<script>setTimeout("window.location=\''.$url.'\'",3000)</script>';
    		exit;
    	}
        $model_calc = Model('dealer_calc_file');
        $condition = array();
        
        $file_list = $model_calc->getCalcFileList($condition,10);
        $c = require_once(BASE_DATA_PATH.'/config/base.ini.php');
        Tpl::output('calc_file_status',$c['calc_file_status']);
        Tpl::output('file_list',$file_list);
        Tpl::output('show_page',$model_calc->showpage());
        $this->profile_menu('index');
        Tpl::showpage('store_calc.index');
    }
    
    public function cancel_fileOp(){
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
    
    public function toCalcOp(){
    	$condition['seller_id'] = $_SESSION['member_id'] ;
    	$condition['cart_sub_status'] = array('in',array(505));
    	$condition['pdi_calc_status'] = 0;
    	$list = Model('hg_cart')->getCartList($condition,10);
    	$this->profile_menu('toCalc');
    	Tpl::output('list',$list);
    	Tpl::output('show_page',Model('hg_cart')->showpage());
        Tpl::showpage('store_calc.tocalc');
    }
    public function inCalcOp(){
    	$condition['seller_id'] = $_SESSION['member_id'] ;
    	$condition = array('pdi_calc_status'=>1);
    	$list = Model('hg_cart')->getCartInvoiceList($condition,10);
    	$this->profile_menu('inCalc');
    	Tpl::output('list',$list);
    	Tpl::output('show_page',Model('hg_cart')->showpage());
    	Tpl::showpage('store_calc.incalc');
    }
    public function endCalcOp(){
    	$condition['seller_id'] = $_SESSION['member_id'] ;
    	$condition = array('pdi_calc_status'=>2);
    	$list = Model('hg_cart')->getCartInvoiceList2($condition,10);
    	$this->profile_menu('endCalc');
    	Tpl::output('list',$list);
    	Tpl::output('show_page',Model('hg_cart')->showpage());
    	Tpl::showpage('store_calc.endcalc');
    }
    
    /**
     * 改变订单的结算状态为1
     */
    public function change_cart_statusOp(){
    	$order_num = $_POST['order_num'];
    	$e = Model('hg_cart')->where(array('order_num'=>$order_num))->update(array('pdi_calc_status'=>1,'pdi_calc_date'=>date('Y-m-d')));
		if(!$e){
    		$data['error_code'] = '1';
    		$data['error_msg'] = '操作失误[更新结算状态]，请返回重新操作';
    	}else{
    		$data['error_code'] = '0';
    	}
    	echo json_encode($data);
    	exit;
    }
    private function profile_menu($menu_key='') {
    	
    	$menu_array = array(
    			array('menu_key'=>'toCalc','menu_name'=>'待结算', 'menu_url'=>'index.php?act=store_calc&op=toCalc'),
    			array('menu_key'=>'inCalc','menu_name'=>'结算中', 'menu_url'=>'index.php?act=store_calc&op=inCalc'),
    			array('menu_key'=>'endCalc','menu_name'=>'已结算', 'menu_url'=>'index.php?act=store_calc&op=endCalc'),
    			array('menu_key'=>'index','menu_name'=>'结算文件', 'menu_url'=>'index.php?act=store_calc&op=index'),
    			);
    	Tpl::output('member_menu',$menu_array);
    	Tpl::output('menu_key',$menu_key);
    }
}
