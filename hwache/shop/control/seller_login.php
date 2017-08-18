<?php
/**
 * 店铺卖家登录
 */
defined('InHG') or exit('Access Invalid!');

class seller_loginControl extends BaseSellerControl {

	public function __construct() {
		parent::__construct();
        if (!empty($_SESSION['seller_id'])) {
            @header('location: index.php?act=seller_center');die;
        }
	}

    public function indexOp() {
        $this->show_loginOp();
    }

    public function show_loginOp() {
        Tpl::output('nchash', getNchash());
        Tpl::output('back_url', $_GET['back_url']);
		Tpl::setLayout('null_layout');
        Tpl::showpage('login');
    }

    public function loginOp() {
         if (!Security::checkToken()) {
             showMessage('登录错误', '', '', 'error');
         }

         if (!checkSeccode($_POST['nchash'], $_POST['captcha'])) {
             showMessage('验证码错误', '', '', 'error');
         }

        $model_seller = Model('seller');
        $seller_info = $model_seller->getSellerInfo(array('seller_name' => $_POST['seller_name']));
        if($seller_info) {

            $model_member = Model('member');

            /*
             * 检测用户密码
             * @author  技安 <php360@qq.com>
             * @link    http://www.moqifei.com
             */
            $password = $model_member->getMemberInfo(array('member_id' => $seller_info['member_id']), 'member_passwd');
            if ($password) {
                if (!password_verify($_POST['password'], $password['member_passwd'])) {
                    showMessage('密码错误', '', '', 'error');
                }
            } else {
                showMessage('密码错误', '', '', 'error');
            }

            $member_info = $model_member->infoMember(
                array(
                    'member_id' => $seller_info['member_id'],
                )
            );

            if($member_info) {
                // 更新卖家登陆时间
                $model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));

                $model_seller_group = Model('seller_group');
                $seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));

                $model_store = Model('store');
                $store_info = $model_store->getStoreInfoByID($seller_info['store_id']);

                $_SESSION['is_login'] = 1;
                $_SESSION['is_login_agents'] = '1';// 经销商代理登录标志
                $_SESSION['member_id'] = $member_info['member_id'];
                $_SESSION['member_name'] = $member_info['member_name'];
    			$_SESSION['member_email'] = $member_info['member_email'];
    			$_SESSION['is_buy']	= $member_info['is_buy'];
    			$_SESSION['avatar']	= $member_info['member_avatar'];

                $_SESSION['grade_id'] = $store_info['grade_id'];
                $_SESSION['seller_id'] = $seller_info['seller_id'];
                $_SESSION['seller_name'] = $seller_info['seller_name'];
                $_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);
                $_SESSION['store_id'] = intval($seller_info['store_id']);
                $_SESSION['store_name']	= $store_info['store_name'];
                $_SESSION['seller_limits'] = explode(',', $seller_group_info['limits']);

                // 终止超时的订单
                Model('hg_baojia')->chaoshi($member_info['member_id']);

                if($seller_info['is_admin']) {
                    $_SESSION['seller_group_name'] = '管理员';
                } else {
                    $_SESSION['seller_group_name'] = $seller_group_info['group_name'];
                }
                if(!$seller_info['last_login_time']) {
                    $seller_info['last_login_time'] = TIMESTAMP;
                }
                $_SESSION['seller_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);
                $seller_menu = $this->getSellerMenuList($seller_info['is_admin'], explode(',', $seller_group_info['limits']));
                $_SESSION['seller_menu'] = $seller_menu['seller_menu'];
                $_SESSION['seller_function_list'] = $seller_menu['seller_function_list'];
                if(!empty($seller_info['seller_quicklink'])) {
                    $quicklink_array = explode(',', $seller_info['seller_quicklink']);
                    foreach ($quicklink_array as $value) {
                        $_SESSION['seller_quicklink'][$value] = $value ;
                    }

                }
                $this->recordSellerLog('登录成功');
                $back_url=$_POST['back_url']?$_POST['back_url']:'index.php?act=seller_center';
                header("Location: $back_url");
                // showMessage('登录成功', 'index.php?act=seller_center');
            } else {
                showMessage('用户名密码错误', '', '', 'error');
            }
        } else {
            showMessage('用户名密码错误', '', '', 'error');
        }
    }
}
