<?php


defined('InHG') or exit('Access Invalid!');

class hgsoftControl extends SystemControl {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 设置城市参考点
     */
    public function pointOp() {
        $model_city_point = Model('hg_city_point');
        $map = array();
        $list = $model_city_point->pointGetList($map, '*', 10);
        Tpl::output('point_list', $list);
        Tpl::output('page',$model_city_point->showpage());
        Tpl::showpage('hgsoft.point');
    }

    /**
     * 城市参考点 新增功能
     */
    public function pointaddOp() {
        if(chksubmit()) {
            $data = array(
                'sheng' => trim($_POST['province_id']),
                'shi'   => trim($_POST['city_id']),
            );
            $model_city_point = Model('hg_city_point');
            // 查询是否已经添加
            $r = $model_city_point->pointGetCount($data);
            if($r) {
                showMessage('已经添加过该城市坐标');
            } else {
                $data['point'] = trim($_POST['point']);
                $data['title'] = trim($_POST['area_info']);
                $r = $model_city_point->pointAdd($data);
                if($r) {
                    showMessage('添加城市坐标成功', 'index.php?act=hgsoft&op=point');
                } else {
                    showMessage('添加城市坐标失败');
                }
            }
        } else {
            Tpl::showpage('hgsoft.pointadd');
        }
    }

    /**
     * 选装件管理
     */
    public function xzjOp() {
        // 加载车型缓存
        $carBrandData = H('goods_class') ? H('goods_class') : H('goods_class', true);

        // 获取参数
        $carid = $_GET['carid'] ? intval($_GET['carid']) : 0;

        $carList = array();
        // 当前第三级别初始化false
        $currentDepth = false;
        $navCar = '上级车型: ';
        if ($carid == 0) {
            foreach ($carBrandData as $key => $value) {
                if ($value['depth'] == 1) {
                    $carList[] = array(
                        'gc_id' => $value['gc_id'],
                        'gc_name' => $value['gc_name']);
                }
            }
        } else if (empty($carBrandData[$carid])) {
            showDialog('无该车型');
        } else {
            if ($carBrandData[$carid]['depth'] != 3) {
                if ($carBrandData[$carid]['depth'] == 1) {
                    $navCar .= $carBrandData[$carid]['gc_name'];
                } else if ($carBrandData[$carid]['depth'] == 2) {
                    $navCar .= $carBrandData[$carBrandData[$carid]['gc_parent_id']]['gc_name']
                        . ' > '
                        . $carBrandData[$carid]['gc_name'];
                }
                $tmpArr = explode(',', $carBrandData[$carid]['child']);
                foreach ($tmpArr as $value) {
                    $carList[] = array(
                        'gc_id' => $carBrandData[$value]['gc_id'],
                        'gc_name' => $carBrandData[$value]['gc_name']);
                }
            } else {
                $currentDepth = true;
                $navCar .= $carBrandData[$carBrandData[$carBrandData[$carid]['gc_parent_id']]['gc_parent_id']]['gc_name']
                    . ' > '
                    . $carBrandData[$carBrandData[$carid]['gc_parent_id']]['gc_name']
                    . ' > '
                    . $carBrandData[$carid]['gc_name'];
            }
        }
        Tpl::output('carid', $carid);
        Tpl::output('carList', $carList);
        Tpl::output('navCar', $navCar);

        if ($currentDepth) {
            $m = Model('hg_xzj_list');

            $page = 10;

            $xzj_list = $m->field('*')
                ->where(array('car_brand'=>$carid))
                ->order('xzj_sort,id')
                ->page($page)
                ->select();

            Tpl::output('xzj_list',$xzj_list);
            Tpl::output('page',$m->showpage());
            TPl::output('type',$currentDepth);

        }

        Tpl::showpage('xzj.index');
    }

    /**
     * 选装件添加
     */
    public function xzj_addOp() {

        // 分类列表
        $gc_list = H('goods_class') ? H('goods_class') : H('goods_class', true);

        if (chksubmit()) {
            $carid = $_POST['car_id'] ? intval($_POST['car_id']) : 0;
            if (empty($carid) || $gc_list[$carid]['depth'] != 3) {
                showMessage('车型错误,请重新选择车型');
                exit;
            }

            try {
                // 开启事务
                Model()->beginTransaction();

                // 查询是否已经添加该车型
                $addTrue = Model('hg_xzj_car')->where(array('car_brand'=>$carid))->count();
                if (empty($addTrue)) {
                    // 添加车型
                    $data = array(
                        'car_brand' => $carid,
                        'car_title' => trim($_POST['car_title']));
                    $r = Model('hg_xzj_car')->insert($data);
                    if (empty($r)) {
                        throw new Exception("添加选装件失败,请重试", 1);
                    }
                }

                $data = array(
                    'car_brand'     => intval($_POST['car_id']),
                    'xzj_title'     => trim($_POST['name']),
                    'xzj_yc'        => empty($_POST['yc']) ? 0 : 1,
                    'xzj_front'     => empty($_POST['front']) ? 0 : 1,
                    'xzj_brand'     => isset($_POST['brand']) ? trim($_POST['brand']) : '',
                    'xzj_model'     => trim($_POST['model']),
                    'xzj_max_num'   => intval($_POST['max_num']),
                    'xzj_guide_price'   => isset($_POST['guide_price']) ? floatval($_POST['guide_price']) : 0,

                    'xzj_sort'          => intval($_POST['sort']) ? intval($_POST['sort']) : 255,
                );
                $r = Model('hg_xzj_list')->insert($data);
                if (empty($r)) {
                    throw new Exception("添加选装件失败,请重试", 1);
                }

                //提交事务
                Model()->commit();

                showMessage('添加选装件成功', 'index.php?act=hgsoft&op=xzj&carid='.$carid);
            } catch (Exception $e) {
                // 回滚事务
                Model()->rollback();
                showMessage($e->getMessage());
            }

        } else {
            // 获取参数
            $carid = $_GET['carid'] ? intval($_GET['carid']) : 0;

            if (empty($carid) || $gc_list[$carid]['depth'] != 3) {
                showMessage('车型错误,请重新选择车型');
                exit;
            }
            Tpl::output('carid', $carid);

            $carTitle = $gc_list[$gc_list[$gc_list[$carid]['gc_parent_id']]['gc_parent_id']]['gc_name']
                . '/' . $gc_list[$gc_list[$carid]['gc_parent_id']]['gc_name']
                . '/' . $gc_list[$carid]['gc_name'];
            Tpl::output('carTitle', $carTitle);
            Tpl::showpage('xzj.add');
        }
    }
    /**
     * 选装件修改
     */
    public function xzj_editOp() {
        if ($_POST['form_submit']) {

            $data = array(
				'xzj_title'=>$_POST['name'],
				'xzj_yc'=>$_POST['yc'],
				'xzj_front'=>$_POST['front'],
				'xzj_sort'=>$_POST['sort'],
				'xzj_brand'=>$_POST['brand'],
				'xzj_model'=>$_POST['model'],
				'xzj_max_num'=>$_POST['max_num'],
				'xzj_guide_price'=>$_POST['guide_price'],
			);

			Model()->table('hg_xzj_list')->where(array('id'=>$_POST['id']))->update($data);
			//echo  'index.php?act=hgsoft&op=xzj&carid='.$_POST['carid'];
			showMessage('修改选装件成功', 'index.php?act=hgsoft&op=xzj&carid='.$_POST['carid']);

        }

        $id=$_GET['id'];
        $xzj = Model('hg_xzj_list')->find($id);
        $brand=Model('hg_xzj_car')->where(array('car_brand'=>$xzj['car_brand']))->find();

        $xzj['brand']=$brand['car_title'];
        Tpl::output('xzj', $xzj);
        Tpl::showpage('xzj.edit');
    }
    /*
		*删除选装件
    */
	public function xzj_delOp(){

		Model('hg_xzj_list')->delete($_GET['id']);
		if($_GET['carid']){
			$url='index.php?act=hgsoft&op=xzj&carid='.$_GET['carid'];
		}else{
			$url='index.php?act=hgsoft&op=xzj_common';
		}
		showMessage('删除成功',$url);
	}
    /**
     * 通用性选装件管理
     */
    public function xzj_commonOp() {
        $m = Model('hg_xzj_list');
        $page = 10;
        $xzj_list = $m->field('*')
                  ->where(array('car_brand'=>0))
                  ->order('xzj_sort,id')
                  ->page($page)
                  ->select();
        Tpl::output('xzj_list',$xzj_list);
        Tpl::output('page',$m->showpage());
        Tpl::showpage('xzj.common');
    }

    /**
     * 通用性选装件添加
     */
    public function xzj_common_addOp() {
        $carid = 0;
        if (chksubmit()) {
            $data = array(
                'car_brand'     => $carid,
                'xzj_title'     => trim($_POST['name']),
                'xzj_yc'        => 0,
                'xzj_front'     => empty($_POST['front']) ? 0 : 1,
                'xzj_brand'     => isset($_POST['brand']) ? trim($_POST['brand']) : '',
                'xzj_model'     => trim($_POST['model']),
                'xzj_max_num'   => intval($_POST['max_num']),
                'xzj_guide_price'   => 0,

                'xzj_sort'          => intval($_POST['sort']) ? intval($_POST['sort']) : 255,
            );
            $r = Model('hg_xzj_list')->insert($data);
            if (!empty($r)) {
                showMessage('添加通用选装件成功', 'index.php?act=hgsoft&op=xzj_common');
            } else {
                showMessage('添加通用选装件失败,请重试');
            }
        } else {
            Tpl::showpage('xzj.common_add');
        }
    }
    /**
     * 通用性选装件修改
     */
    public function xzj_common_editOp() {

    	if (chksubmit()) {
    		$data = array(
				'xzj_title'=>$_POST['name'],

				'xzj_front'=>$_POST['front'],
				'xzj_sort'=>$_POST['sort'],
				'xzj_brand'=>$_POST['brand'],
				'xzj_model'=>$_POST['model'],
				'xzj_max_num'=>$_POST['max_num'],

			);
    		Model()->table('hg_xzj_list')->where(array('id'=>$_POST['id']))->update($data);
    		showMessage('修改通用选装件成功', 'index.php?act=hgsoft&op=xzj_common');
    	}


    	$xzj=Model('hg_xzj_list')->where(array('id'=>$_GET['id']))->find();

    	Tpl::output('xzj',$xzj);
    	Tpl::showpage('xzj.common_edit');
    }
}
