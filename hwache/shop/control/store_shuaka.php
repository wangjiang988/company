<?php
/**
 * 经销商代理刷卡管理
 */

defined('InHG') or exit('Access Invalid!');

class store_shuakaControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * [indexOp description]
     */
    public function indexOp() {
        // 选装件车型列表
        $m = Model('hg_shuaka');
        
        $list = $m->where(array('member_id'=>$_SESSION['member_id']))->find();
        if (!empty($list)) {
            Tpl::output('list', $list);
            
        }else{

        	redirect(urlShop('store_shuaka', 'add'));
        }
        
        Tpl::showpage('store_shuaka.index');
    }

    /**
     * 添加选装件
     */
    public function addOp() {
        if (chksubmit()) {

        	$data = array(
                        'member_id' => $_SESSION['member_id'],
                        'credit' => $_POST['credit'],
                        'debit' => $_POST['debit'],
                        'credit_rate' => $_POST['credit_rate'],
						'debit_rate' => $_POST['debit_rate'],
						'credit_quota' => $_POST['credit_quota'],
						'debit_quota' => $_POST['debit_quota'],
						'credit_type' => $_POST['credit_type'],
						'debit_type' => $_POST['debit_type'],
                    );
        	$r = Model('hg_shuaka')->insert($data);
            redirect(urlShop('store_shuaka', 'index'));
            
        } else {
            
            Tpl::showpage('store_shuaka.add');
        }
    }

    

    /**
     * 编辑
     */
    public function editOp() {

    	$data = array(
                    'credit' => $_POST['credit'],
                        'debit' => $_POST['debit'],
                        'credit_rate' => $_POST['credit_rate'],
						'debit_rate' => $_POST['debit_rate'],
						'credit_quota' => $_POST['credit_quota'],
						'debit_quota' => $_POST['debit_quota'],
						'credit_type' => $_POST['credit_type'],
						'debit_type' => $_POST['debit_type'],
                    
                );
    	Model()->table('hg_shuaka')->where(array('id'=>$_POST['id']))->update($data);
    	showMessage('修改完成','index.php?act=store_shuaka&op=index');
        
    }

 
}
