<?php
/**
 * 经销商代理选装件管理
 */

defined('InHG') or exit('Access Invalid!');

class store_xzjControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * [indexOp description]
     */
    public function indexOp() {
        // 选装件车型列表
        $m = Model('xzj');
        $list = $m->getXzjList(
            'hg_xzj_daili_main',
            array(
                'member_id' => $_SESSION['member_id'],
            ),
            'id,car_brand,dealer_id'
        );
        if (!empty($list)) {
            // 查询代理的经销商和车型信息
            $m_car = Model('hg_xzj_car');
            $m_dealer = Model('hg_dealer');
            foreach ($list as $key => $value) {
                $arrCar = $m_car->field('car_title')->where(array('car_brand'=>intval($value['car_brand'])))->find();
                $list[$key]['car_title'] = $arrCar['car_title'];
                /**
                 * 读入已经添加的经销商
                 */
                // 查询条件
                $arrDealer = $m_dealer->field('d_name')->where(array('d_id'=>intval($value['dealer_id'])))->find();
                $list[$key]['dealer_title'] = $arrDealer['d_name'];
            }
        }

        Tpl::output('list', $list);
        Tpl::output('show_page',$m->showpage()); // 分页


        Tpl::showpage('store_xzj.index');
    }

    /**
     * 添加选装件
     */
    public function addOp() {
        if (chksubmit()) {
            $carid = $_POST['class_id'] ? intval($_POST['class_id']) : 0;
            $dealerid = $_POST['d_id'] ? intval($_POST['d_id']) : 0;
            if (!empty($carid) && !empty($dealerid)) {
                $count = Model('xzj')->getCount(
                    'hg_xzj_daili_main',
                    array(
                        'member_id' => $_SESSION['member_id'],
                        'car_brand' => $carid,
                        'dealer_id' => $dealerid,
                    ));
                if (empty($count)) {
                    $data = array(
                        'member_id' => $_SESSION['member_id'],
                        'car_brand' => $carid,
                        'dealer_id' => $dealerid,
                        'time_add'  => time()
                    );
                    $r = Model('hg_xzj_daili_main')->insert($data);
                    redirect(urlShop('store_xzj', 'addtwo', array('dlxzjid'=>$r)));
                } else {
                    showMessage('已经添加该选装件报价');
                }
            }
        } else {
            /**
             * ----------------------------------------------------------------
             * 只显示存在选装件的车型
             */
            // 一级分类列表
            $gc_list = H('goods_class') ? H('goods_class') : H('goods_class', true);
            // var_dump($gc_list);
            // 查询已经含有选装件的车型
            $xzjList = Model('hg_xzj_car')->field('car_brand')->select();
            
            // 用于模板中选装件下来列表选择
            $xzjTopArr = array();
            foreach ($xzjList as $key => $value) {
                $carid2 = $gc_list[$value['car_brand']]['gc_parent_id'];
                $carid1 = $gc_list[$carid2]['gc_parent_id'];
                $xzjTopArr[] = array(
                    'id'   => $carid1,
                    'name' => $gc_list[$carid1]['gc_name']);
            }

            Tpl::output('xzjSelect', $xzjTopArr);

            /**
             * ----------------------------------------------------------------
             * 显示已经添加的经销商信息
             */
            $model = Model();
            // 查询条件
            $where = 'hg_daili_dealer.dl_id='.$_SESSION['member_id'];
            $on = 'hg_dealer.d_id=hg_daili_dealer.d_id';
            $field = 'hg_dealer.d_id,hg_dealer.d_name';
            $model->table('hg_daili_dealer,hg_dealer')->field($field);
            $dealer_list  = $model->join('inner')->on($on)->where($where)->select();
            Tpl::output('dealer_list', $dealer_list);

            Tpl::showpage('store_xzj.add');
        }
    }

    public function addtwoOp() {
        // 安全验证
        $dlxzjid = $_GET['dlxzjid']
            ? intval($_GET['dlxzjid'])
            : showMessage('选装件ID错误');
        $xzjModel = Model('xzj');
        $xzjDlMain = $xzjModel->getXzjOne(
            'hg_xzj_daili_main',
            array(
                'member_id' => $_SESSION['member_id'],
                'id'        => $dlxzjid,
            ));
        if (empty($xzjDlMain)) {
            showMessage('无该选装件');
        }

        if (chksubmit()) {


            //插入数据
            foreach ($_POST['xzj'] as $key => $value) {
                if (!$value['id']) continue;
                $xzjvalue=Model('hg_xzj_list')->where(array('id'=>$value['id']))->find();
                $data = array(
                    'xzj_list_id'=>$value['id'],
                    
                    'xzj_title'=>$xzjvalue['xzj_title'],
                    'car_brand'=>$xzjvalue['car_brand'],
                    'xzj_yc'=>$xzjvalue['xzj_yc'],
                    'xzj_front'=>$xzjvalue['xzj_front'],
                    'xzj_model'=>$xzjvalue['xzj_model'],
                    'xzj_max_num'=>$xzjvalue['xzj_max_num'],
                    'xzj_guide_price'=>$xzjvalue['xzj_guide_price'],
                    'xzj_cs_serial'=>$xzjvalue['cs_serial'],
                    'member_id'=>$_SESSION['member_id'],
                    'xzj_has_num'=>$value['has_num'],
                    'dealer_id'=>$xzjDlMain['dealer_id'],

                );
                 Model()->table('hg_xzj_daili')->insert($data);
            }
            showMessage('选装件添加完成','index.php?act=store_xzj&op=index');    
        } else {

            /**
             * ----------------------------------------------------------------
             * 选装件对应数据
             */
            // 对应车型和经销商的名称
            // 查询代理的经销商和车型信息
            $carTitle = Model('hg_xzj_car')->field('car_title')->where(array('car_brand'=>intval($xzjDlMain['car_brand'])))->find();
            $carTitle = $carTitle['car_title'];
            Tpl::output('carTitle', $carTitle);

            $dealerTitle = Model('hg_dealer')->field('d_name')->where(array('d_id'=>intval($xzjDlMain['dealer_id'])))->find();
            $dealerTitle = $dealerTitle['d_name'];
            Tpl::output('dealerTitle', $dealerTitle);

            // 对应车型选装件列表
            $xzjCarList = $xzjModel->getXzjList(
                'hg_xzj_list',
                array('car_brand' => $xzjDlMain['car_brand'],'xzj_yc'=>1)
                );
            // 整理数据,把必须前装的分离出来
            /*$data = array(
                'front' => array(),
                'other' => array(),
            );
            foreach ($xzjCarList as $key => $value) {
                if ($value['xzj_front'] == 1) {
                    $data['front'][] = $xzjCarList[$key];
                } else {
                    $data['other'][] = $xzjCarList[$key];
                }
            }*/
            // unset($xzjCarList);
            Tpl::output('xzjCarList', $xzjCarList);


            // 通用选装件列表
            $xzjTongyongList = $xzjModel->getXzjList(
                'hg_xzj_list',
                array('car_brand' => 0)
                );
            Tpl::output('xzjTongyongList', $xzjTongyongList);
            Tpl::output('dlxzjid', $dlxzjid);
            // $xzjList = array(
            //     'car'   => $xzjCarList,
            //     'tongyong'  => $xzjTongyongList);
            // var_dump($xzjList);
            Tpl::showpage('store_xzj.addtwo');
        }
    }

    /**
     * 编辑选装件
     */
    public function editOp() {
        if (chksubmit()) {
          
            foreach ($_POST['xzj'] as $key => $value) {
                $data = array(
                    'xzj_fee'=>$value['fee'],
                   
                    'xzj_discount'=>$value['discount'],
                    'xzj_price'=>$value['price'],
                    
                );
                 Model()->table('hg_xzj_daili')->where(array('id'=>$key))->update($data);
                 
            }
            showMessage('选装件修改完成','index.php?act=store_xzj&op=index');
        } else {
            $id = intval($_GET['id']);
            $car_brand=intval($_GET['car_brand']);
            $dealer_id=intval($_GET['dealer_id']);
            Tpl::output('car_brand', $car_brand);
            Tpl::output('dealer_id', $dealer_id);
            // 查询是否属于该会员
            $ok = Model('hg_xzj_daili_main')
                ->where(array('id'=>$id,'member_id'=>$_SESSION['member_id']))
                ->count();
            if (empty($ok)) {
                showMessage('参数错误');
                exit;
            }
			
			$xzjModel = Model('xzj');
			$xzjDlMain = $xzjModel->getXzjOne(
				'hg_xzj_daili_main',
				array(
					'member_id' => $_SESSION['member_id'],
					'id'        => $id,
				));

            if (empty($xzjDlMain)) {
				showMessage('无该选装件');
			}
			$carTitle = Model('hg_xzj_car')->field('car_title')->where(array('car_brand'=>intval($xzjDlMain['car_brand'])))->find();
            $carTitle = $carTitle['car_title'];
            Tpl::output('carTitle', $carTitle);
            $dealerTitle = Model('hg_dealer')->field('d_name')->where(array('d_id'=>intval($xzjDlMain['dealer_id'])))->find();
            $dealerTitle = $dealerTitle['d_name'];
            Tpl::output('dealerTitle', $dealerTitle);
			//取得代理添加的选装件列表
			// $xzjCarList=Model()->table('hg_xzj_daili,hg_xzj_list')->field('hg_xzj_daili.id,hg_xzj_daili.xzj_list_id,hg_xzj_daili.xzj_fee,hg_xzj_daili.xzj_discount,hg_xzj_daili.xzj_price,hg_xzj_list.car_brand,hg_xzj_list.xzj_title,hg_xzj_list.xzj_yc,hg_xzj_list.xzj_front,hg_xzj_list.xzj_brand,hg_xzj_list.xzj_model,hg_xzj_list.xzj_guide_price')->join('left')->on('hg_xzj_daili.xzj_list_id=hg_xzj_list.id')->where("hg_xzj_daili.main_id=$id")->select();
			// var_dump($xzjCarList);exit;
            $xzjCarList=Model()->table('hg_xzj_daili')->where(array('car_brand'=>$car_brand,'member_id'=>$_SESSION['member_id'],'dealer_id'=>$dealer_id,'xzj_yc'=>1))->select();

			/*$data = array(
                'front' => array(),
                'other' => array(),
				
            );
			$xzjTongyongList=array();
			foreach ($xzjCarList as $key => $value) {
                if ($value['car_brand'] == 0) {
					$xzjTongyongList[] = $xzjCarList[$key];
                    
                } elseif($value['xzj_front']==1) {
                    $data['front'][] = $xzjCarList[$key];
                }else{
					$data['other'][] = $xzjCarList[$key];
				}
            }
			unset($xzjCarList);*/
			$fyc=Model()->table('hg_xzj_daili')->where(array('car_brand'=>$car_brand,'member_id'=>$_SESSION['member_id'],'dealer_id'=>$dealer_id,'xzj_yc'=>0))->select();
            Tpl::output('fyc', $fyc);
            Tpl::output('xzjCarList', $xzjCarList);
			// Tpl::output('xzjTongyongList', $xzjTongyongList);
			
            Tpl::output('id', $id);
            Tpl::showpage('store_xzj.edit');
        }
    }
    // 添加非原厂选装件
    public function addfycOp()
    {
        if (chksubmit()) {
            $data= array(
                
                'car_brand' => $_POST['car_brand'],
                'xzj_front'=>$_POST['xzj_front'],
                'xzj_title'=>$_POST['xzj_title'],
                'xzj_brand'=>$_POST['xzj_brand'],
                'xzj_model'=>$_POST['xzj_model'],
                'xzj_max_num'=>$_POST['xzj_max_num'],
                'xzj_guide_price'=>$_POST['xzj_price'],
                'member_id'=>$_SESSION['member_id'],
                'xzj_yc'=>0,
             );
            // 先插入到选装件基础表
            $xzj_id=Model()->table('hg_xzj_list')->insert($data);
            $map= array(
                'dealer_id' => $_POST['dealer_id'],
                'car_brand' => $_POST['car_brand'],
                'xzj_front'=>$_POST['xzj_front'],
                'xzj_title'=>$_POST['xzj_title'],
                'xzj_brand'=>$_POST['xzj_brand'],
                'xzj_model'=>$_POST['xzj_model'],
                'xzj_max_num'=>$_POST['xzj_max_num'],
                'xzj_has_num'=>$_POST['xzj_has_num'],
                'xzj_fee'=>$_POST['xzj_fee'],
                'xzj_guide_price'=>$_POST['xzj_price'],
                'xzj_list_id'=>$xzj_id,
                'member_id'=>$_SESSION['member_id'],
                'xzj_yc'=>0,
             );
            Model()->table('hg_xzj_daili')->insert($map);
            showMessage('选装件添加完成','index.php?act=store_xzj&op=index'); 
        }else{
            Tpl::output('dealer_id', $_GET['dealer_id']);
            Tpl::output('car_brand', $_GET['car_brand']);
             Tpl::showpage('store_addfyc');
        }
    }
    /**
     * 查询该会员已经添加的选装件的车型和经销商的关系列表
     */
    public function ajax_get_xzj_cardealerOp() {
        $carid = $_GET['carid'] ? intval($_GET['carid']) : 0;
        $dealerid = $_GET['dealerid'] ? intval($_GET['dealerid']) : 0;
        $returnArr = array(
            'code'=>0,
            'msg'=>'已经添加该选装件报价'
        );
        if (!empty($carid) && !empty($dealerid)) {
            $count = Model('xzj')->getCount(
                'hg_xzj_daili_main',
                array(
                    'member_id' => $_SESSION['member_id'],
                    'car_brand' => $carid,
                    'dealer_id' => $dealerid,
                ));
            if (empty($count)) {
                $returnArr = array(
                    'code'=>1,
                    'msg'=>''
                );
            }
        }
        echo json_encode($returnArr);
    }

    public function ajax_get_xzjOp() {
        $carid = intval($_GET['carid']);
        // 查询是否存在该车型选装件
        $r = Model('hg_xzj_car')
            ->where(array('car_brand'=>$carid))
            ->count();
        if (empty($r)) {
            echo json_encode(array(
                'code'=>0,
                'msg'=>'该车型无选装件'
                ));
        } else {
            // 查询选装件
            $r = Model('hg_xzj_admin')
                ->where(array('car_brand'=>$carid))
                ->order('sort ASC')
                ->select();
            // 整理数据,把必须前装的分离出来
            $data = array(
                'front' => array(),
                'other' => array(),
            );
            foreach ($r as $key => $value) {
                if ($value['xzj_front'] == 1) {
                    $data['front'][] = $r[$key];
                } else {
                    $data['other'][] = $r[$key];
                }
            }
            echo json_encode(array(
                'code'=>1,
                'd'=>$data
                ));
        }
    }

    public function delOp(){
        Model()->table('hg_xzj_daili_main')->where(array('id'=>$_GET['id']))->delete();
        Model()->table('hg_xzj_daili')->where(array('main_id'=>$_GET['id']))->delete();
        showMessage('删除成功','index.php?act=store_xzj&op=index');
    }
}
