<?php
/**
 * 经销商管理
 */
defined('InHG') or exit('Access Invalid!');

class dealerControl extends SystemControl{
	const EXPORT_SIZE = 5000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 经销商管理
	 */
	public function indexOp(){
        $model_dealer = Model('hg_dealer');
        if ($_GET['query'] == 1) {

            $condition = array();
            if ($_GET['brand_id']) {
                $condition['d_brand_id'] = $_GET['brand_id'];
            }
            if ($_GET['dealer_name']) {
                $condition['d_name'] = array('like', '%' . $_GET['dealer_name'] . '%');
            }
            if ($_GET['dealer_id'] != '') {
                $condition['d_id'] = array('like', '%' . $_GET['dealer_id'] . '%');
            }
            if ($_GET['dealer_area']) {
                $condition['d_areainfo'] = $_GET['dealer_area'];
            }
            if ($_GET['is_show'] != '') {
                $condition['d_is_show'] = $_GET['is_show'];
            }

            $page_view = 'dealer.index.table';
            Tpl::setLayout('layout_ajax');
        } else {
            //经销商归属地区
            $area = $model_dealer->field('d_areainfo')->group('d_areainfo')->select();
            Tpl::output('area_list', $area);

            //品牌
            $brands = Model()->table('hg_dealer,goods_class')
                ->field('goods_class.gc_id,goods_class.gc_name')
                ->join('left')
                ->on('hg_dealer.d_brand_id=goods_class.gc_id')
                ->where('goods_class.gc_id>0')
                ->group('goods_class.gc_id')
                ->select();

            Tpl::output('brand_list', $brands);

            $condition = array();
            $page_view = 'dealer.index';
        }

        //经销商列表
        $dealer_list = $model_dealer->getDealerList($condition, 'hg_dealer.*,goods_class.gc_name', 10, 'd_id asc');
        Tpl::output('dealer_list', $dealer_list);
        Tpl::output('page', $model_dealer->showpage());

        Tpl::showpage($page_view);
	}

	/**
	 * 经销商修改
	 */
	public function dealer_editOp(){
		$lang	= Language::getLangContent();
		$model_dealer = Model('hg_dealer');
		/**
		 * 保存
		 */
		if (chksubmit()){
	
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["email"], "require"=>"true", 'validator'=>'Email', "message"=>'经销商邮件格式错误'),
			);
			$error = $obj_validate->validate();

			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();


				$insert_array['d_id']			= intval($_POST['d_id']);
				$insert_array['d_name']				= trim($_POST['name']);
				$insert_array['d_lngx']				= trim($_POST['lngx']);
				$insert_array['d_lngy']				= trim($_POST['lngy']);
				$insert_array['d_yy_place']			= trim($_POST['yy_place']);
				// 交车地点是否与营业地点相同
				if($_POST['jc_yy_same']) {
					$insert_array['d_jc_lngx']		= trim($_POST['lngx']);
					$insert_array['d_jc_lngy']		= trim($_POST['lngy']);
					$insert_array['d_jc_place']		= trim($_POST['yy_place']);
				} else {
					$insert_array['d_jc_lngx']		= trim($_POST['jc_lngx']);
					$insert_array['d_jc_lngy']		= trim($_POST['jc_lngy']);
					$insert_array['d_jc_place']		= trim($_POST['jc_place']);
				}
				// 省市县
				$insert_array['d_sheng']			= trim($_POST['province_id']);
				$insert_array['d_shi']				= trim($_POST['city_id']);
				$insert_array['d_xian']				= trim($_POST['area_id']);
				$insert_array['d_areainfo']			= trim($_POST['area_info']);
				// 商业保险
				if($_POST['baoxian']) {
					$insert_array['d_baoxian']		= 1;
				}
				// 本地上牌
				if($_POST['shangpai']) {
					$insert_array['d_shangpai']		= 1;
				}
				// 临牌
				if($_POST['linpai']) {
					$insert_array['d_linpai']		= 1;
				}
				// 其它收费项目
				if($_POST['fee']) {
					$insert_array['d_jc_fee']		= 1;
				}
				// 其它免费项目
				if($_POST['free']) {
					$insert_array['d_jc_free']		= 1;
				}
				// 城市限购上牌
				if($_POST['xg_shangpai']) {
					$insert_array['d_xg_shangpai']	= 1;
				}
				// 国家节能补贴
				if($_POST['butie']) {
					$insert_array['d_butie']		= 1;
				}
				// 同地区竞争性经销商数量
				$insert_array['d_dealer_num']		= trim($_POST['dealer_num']);
				// 同地区竞争性经销商备注说明
				$insert_array['d_dealer_content']	= trim($_POST['dealer_content']);
				$insert_array['d_add_time']			= time();
				$insert_array['d_tel']				= trim($_POST['tel']);
				$insert_array['d_email']			= trim($_POST['email']);
				
				$result = $model_dealer->updateDealer($insert_array,intval($_POST['d_id']));
				
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=dealer&op=index',
					'msg'=>'返回经销商列表',
					),
					array(
					'url'=>'index.php?act=dealer&op=dealer_edit&d_id='.intval($_POST['d_id']),
					'msg'=>'继续修改',
					),
					);
					$this->log(L('nc_edit,member_index_name').'[ID:'.$_POST['d_id'].']',1);
					showMessage($lang['member_edit_succ'],$url);
				}else {
					showMessage('修改失败');
				}
			}
		}
		$condition['d_id'] = intval($_GET['d_id']);
		$dealer_array = $model_dealer->getDealerInfo($condition);

		Tpl::output('dealer_array',$dealer_array);
		Tpl::showpage('dealer.edit');
	}

	/**
	 * 新增经销商
	 */
	public function dealer_addOp(){
        $model_dealer = Model('hg_dealer');

        if (chksubmit()) {
            if (empty($_POST['brand_id']) || empty($_POST['area_info']) || empty($_POST['name']) || empty($_POST['yy_place'])) {
                showMessage('请完善经销商信息，名称， 销售品牌和营业地点是必填项目');
                exit;
            }

            $insert_array = array();
            $insert_array['d_name'] = trim($_POST['name']);
            $insert_array['d_brand_id'] = trim($_POST['brand_id']);

            // 交车地点与营业地点相同
            $insert_array['d_lngx'] = $insert_array['d_jc_lngx'] = trim($_POST['lngx']);
            $insert_array['d_lngy'] = $insert_array['d_jc_lngy'] = trim($_POST['lngy']);
            $insert_array['d_yy_place'] = $insert_array['d_jc_place'] = trim($_POST['yy_place']);

            // 省市县
            $insert_array['d_sheng'] = trim($_POST['province_id']);
            $insert_array['d_shi'] = trim($_POST['city_id']);
            $insert_array['d_xian'] = trim($_POST['area_id']);
            $insert_array['d_areainfo'] = trim($_POST['area_info']);

            // 同地区竞争性经销商备注说明
            $insert_array['d_dealer_content'] = trim($_POST['dealer_content']);
            $insert_array['d_add_time'] = time();
            $insert_array['d_tel'] = trim($_POST['tel']);

            //管理员id
            $admin = $this->admin_info;
            $insert_array['d_operator_id'] = $admin['id'];

            $result = $model_dealer->addDealer($insert_array);

            // 经销商基本信息保存完成，保存附加信息
            if ($result) {
                showMessage('成功添加经销商', 'index.php?act=dealer&op=index');
            } else {
                showMessage('经销商添加失败，请重新添加...');
            }
        } else {
            //品牌
            $brands = Model('goods_class')->where(array('gc_parent_id' => 0))->select();
            Tpl::output('brand_list', $brands);
        }
        Tpl::showpage('dealer.add');
	}

    /**
     * 查看经销商
     */
    function dealer_viewOp() {
        $d_id = $_GET['d_id'];
        $dealer_info = Model('hg_dealer')->getDealerInfo(array('d_id' => $d_id), 'hg_dealer.*,goods_class.gc_name');
        if (empty($dealer_info)) {
            showMessage('经销商信息错误');
            exit;
        }

        $admin = Model('admin')->getOneAdmin($dealer_info['d_operator_id']);
        Tpl::output('admin', $admin);

        Tpl::output('dealer_info', $dealer_info);
        Tpl::showpage('dealer.view');
    }

    /**
     *  ajax设置始终不显示经销商的原因
     */
    function ajax_hidedealerOp()
    {
        $admin = $this->admin_info;

        $data = array(
            'd_is_show' => 0,
            'd_hide_time' => date("Y-m-d H:i:s"),
            'd_operator_id' => $admin['id'],
            'd_is_hide_reason' => $_POST['d_is_hide_reason'],
        );

        $ret = Model('hg_dealer')->where(array('d_id' => $_POST['d_id']))->update($data);
        if (!$ret) {
            $result = array('error_code' => 1, 'error_msg' => '数据更新失败', 'data' => array());
        }else{
            $result = array('error_code' => 0, 'error_msg' => '', 'data' => array());
        }


        echo json_encode($result);
    }

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证会员是否重复
			 */
			case 'check_user_name':
				$model_member = Model('member');
				$condition['member_name']	= trim($_GET['member_name']);
				$condition['no_member_id']	= intval($_GET['member_id']);
				$list = $model_member->infoMember($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
				/**
			 * 验证邮件是否重复
			 */
			case 'check_email':
				$model_member = Model('member');
				$condition['member_email'] = trim($_GET['member_email']);
				$condition['no_member_id'] = intval($_GET['member_id']);
				$list = $model_member->infoMember($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}

	public function yuntutestOp() {
		$s = microtime(true);
		// 保存到云图数据库中
		// $posturl	= 'http://yuntuapi.amap.com/datamanage/data/create';
		// $key		= '7d3c0c6f75a818b8fa90669249c83ea6';
		// $tableid	= '551b81a2e4b050797970af88';
		// $loctype	= 1;
		// $sig		= md5('_location='.$insert_array['d_lngy'].','.$insert_array['d_lngy'].'&_name='.$insert_array['d_name'].'ec1b74d8893f43c37cbb71a74a8e9689');
		// $data = array(
		// 	'_name'		=> $insert_array['d_name'],
		// 	'_location'	=> $insert_array['d_lngy'].','.$insert_array['d_lngy'],
		// );
		require BASE_ROOT_PATH . '/admin/control/yuntu.php';
		$param = array(
			'key'		=> '7d3c0c6f75a818b8fa90669249c83ea6',
			'sig'		=> '46d5ab8a1b3b706a068cfaa01692604c',
			'tableid'	=> '551b81a2e4b050797970af88',
		);
		$map = new yuntu($param);
		// $tmp = $map->create('测试');
		// var_dump($tmp);
		// ------------------------
		// 添加一个地标
		// $adddata = array(
		// 	'_name'		=> '观前肯德基',
		// 	'_location'	=> '120.626301,31.310966',
		// );
		// $r = $map->add($adddata);
		// ------------------------
		// 更新一个地标
		// $data = array(
		// 	'_id'		=> 2,
		// 	'_name'		=> '再一次更新.哈哈',
		// 	'_location'	=> '120.626301,31.310966',
		// );
		// $r = $map->update($data);
		// 获取信息
		// $r = $map->get_local();
		$r = $map->get_around(array('center'=>'120.601689,31.299929','radius'=>50000));
		var_dump($r);
		$e = microtime(true);
		echo $e-$s;

	}

}
