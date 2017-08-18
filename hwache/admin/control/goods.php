<?php
/**
 * 商品栏目管理
 */
defined('InHG') or exit('Access Invalid!');
class goodsControl extends SystemControl{
    const EXPORT_SIZE = 5000;
    public function __construct() {
        parent::__construct ();
        Language::read('goods');
        Language::read('public');
    }

    /**
     * 商品设置
     */
    public function goods_setOp() {
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['goods_verify'] = $_POST['goods_verify'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,nc_goods_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,nc_goods_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
        Tpl::showpage('goods.setting');
    }

    /**
     * 商品管理
     */
    public function goodsOp()
    {
        $model_goods = Model('hg_baojia');

        $condition['bj_serial'] = intval($_GET['bj_serial']);//报价编号
        $condition['user_name'] = trim($_GET['user_name']);//用户名
        $condition['user_realname'] = trim($_GET['user_realname']);//用户名
        $condition['user_phone'] = trim($_GET['user_phone']);//用户手机号
        $condition['dealer_name'] = trim($_GET['dealer_name']);//经销商
        $condition['dealer_area'] = trim($_GET['dealer_area']);//归属地区
        $condition['brand_id'] = trim($_GET['brand_id']);//品牌
        $condition['gc_series'] = trim($_GET['gc_series']);//车系
        $condition['gc_name'] = trim($_GET['gc_name']);//型号
        $condition['bj_status'] = trim($_GET['bj_status']);//报价状态
        $condition['bj_is_public'] = trim($_GET['bj_is_public']);//发布状态
        $condition['bj_is_xianche'] = trim($_GET['bj_is_xianche']);//现车非现车
        $condition['bj_is_pass'] = trim($_GET['bj_is_pass']);//审核状态
        $condition['bj_create_time_start'] = trim($_GET['common_bj_created_starttime']);//报价开始时间
        $condition['bj_create_time_end'] = trim($_GET['common_bj_created_endtime']);//报价结束时间
        $condition['bj_start_time'] = trim($_GET['common_bj_effectived_starttime']);//报价生效开始时间
        $condition['bj_end_time'] = trim($_GET['common_bj_effectived_endtime']);//报价生效结束时间

        if ($_GET['query'] == 1) {
            $page_view = 'goods.index.table';
            Tpl::setLayout('layout_ajax');
        } else {
            //经销商归属地区
            $area = $model_goods->getAreaListFromBaojia();
            Tpl::output('area_list', $area);

            //品牌
            $brands = $model_goods->getBrandFromBaojia();
            Tpl::output('brand_list', $brands);

            //车型
            if (isset($_GET['brand_id']) && $brand_id = $_GET['brand_id']) {
                $gc_series = $model_goods->getCarseriesFromBaojia($brand_id);
                Tpl::output('gc_series', $gc_series);
            }

            //车系
            if (isset($_GET['brand_id']) && isset($_GET['gc_series']) && $brandid = $_GET['brand_id'] && $gc_series = $_GET['gc_series']) {
                $gc_name = $model_goods->getCarmodelFromBaojia($brandid, $gc_series);
                Tpl::output('gc_name', $gc_name);
            }

            //报价状态
            $bj_status_list = array(
                '1' => '正在报价',
                '2' => '暂时下架',
                '4' => '失效报价',
                '5' => '新建未完',
                '6' => '等待生效',
            );

            Tpl::output('bj_status_list', $bj_status_list);

            $page_view = 'goods.index';
        }

        //报价列表
        $goods_list = $model_goods->getBaojiaList($condition, 10);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('page', $model_goods->showpage());

        Tpl::showpage($page_view);
    }

    /**
     * ajax 获取车辆车系、车型信息
     */
    public function ajaxcardataOp()
    {
        $type = $_GET['type'];
        $brand_id = $_GET['brand_id'];
        $model = Model('hg_baojia');

        $result = array();
        if ($type == 'brand') {
            $result = $model->getCarseriesFromBaojia($brand_id);
        } else if ($type == 'car_series') {
            $gc_series = $_GET['gc_series'];
            $result = $model->getCarmodelFromBaojia($brand_id, $gc_series);
        } else {
        }
        echo json_encode($result);
    }

    /**
     * ajax 发布审核报价
     */
    public function ajaxshenheOp()
    {
        $result = array('error_code' => 0, 'error_msg' => '', 'data' => array());

        //记录审核日志
        $insert_data = array(
            'baojia_id' => $_POST['baojia_id'],
            'member_id' => $_POST['member_id'],
            'status' => $_POST['baojia_status'],
            'remark' => isset($_POST['remark']) ? $_POST['remark'] : ''
        );
        Model('hc_baojia_public')->insert($insert_data);

        $insert_id = Model('hc_baojia_public')->getLastID();
        if ($insert_id) {
            //更新报价状态
            $data = array('bj_is_public' => intval($_POST['baojia_status']), 'bj_is_pass' => 1);
            $ret = Model('hg_baojia')->where(array('bj_id' => $_POST['baojia_id']))->update($data);
            if (!$ret) {
                $result = array('error_code' => 1, 'error_msg' => '数据更新失败', 'data' => array());
            }
        } else {
            $result = array('error_code' => 1, 'error_msg' => '数据插入失败', 'data' => array());
        }

        echo json_encode($result);
    }

    /**
     * 人工审核报价
     */
    public function ajaxpassOp()
    {
        $result = array('error_code' => 0, 'error_msg' => '', 'data' => array());

        $bj_id = $_POST['baojia_id'];
        if ($bj_id) {
            $data = array('bj_is_pass' => 1);
            $ret = Model('hg_baojia')->where(array('bj_id' => $bj_id))->update($data);
            if (!$ret) {
                $result = array('error_code' => 1, 'error_msg' => '数据更新失败', 'data' => array());
            }
        } else {
            $result = array('error_code' => 1, 'error_msg' => '参数输入错误', 'data' => array());
        }

        echo json_encode($result);
    }

    /**
     * 设置华车车价
     */
    public function ajaxsethcpriceOp()
    {
        $result = array('error_code' => 1, 'error_msg' => '参数输入错误', 'data' => array());

        $bj_id = $_POST['baojia_id'];
        $hc_price = $_POST['hc_price'];
        if ($hc_price && $bj_id) {
            Model('common');
            $ret = Model('price')->savePrice($bj_id, $hc_price);
            if ($ret) {
                $result = array('error_code' => 0, 'error_msg' => '', 'data' => array());
            } else {
                $result = array('error_code' => 1, 'error_msg' => '数据保存错误', 'data' => array());
            }
        }

        echo json_encode($result);
    }

    /**
     * 审核报价
     */
    public function shenheOp()
    {
        $data = array('bj_is_pass' => intval($_GET['is_pass']),);
        Model('hg_baojia')->where(array('bj_id' => $_GET['bj_id'],))->update($data);
        showMessage('审核完成', 'index.php?act=goods&op=goods');
    }

    /**
     * 显示报价
     */
    public function showOp()
    {
        $bj_id = $_GET['bj_id'];
        $model = Model('hg_baojia');

        //报价信息
        $baojia = $model->where(array('bj_id' => $bj_id))->select();
        $baojia[0]['bj_status'] = $model->getBaojiaStatus($baojia[0]['bj_status'], $baojia[0]['bj_step'], $baojia[0]['bj_start_time'], $baojia[0]['bj_end_time']);
        Tpl::output('baojia', $baojia[0]);

        //华车车价
        Model('common');
        $hc_price = Model('price')->getPriceAll($bj_id);
        Tpl::output('hc_price', $hc_price);

        //经销商信息
        $dealer = Model('hg_dealer')->where('d_id=' . $baojia[0]['dealer_id'])->find();
        Tpl::output('dealer', $dealer);


        //发布者信息
        $member = Model('member')->where('member_id=' . $baojia[0]['m_id'])->find();
        Tpl::output('member', $member);

        //卖家ID
        $seller = Model('seller')->where('member_id=' . $member['member_id'])->find();
        Tpl::output('seller', $seller);

        //价格信息
        $price = Model('hg_baojia_price')->where(array('bj_id' => $bj_id))->find();
        Tpl::output('price', $price);

        //车辆信息
        $car_info = Model('hg_car_info')->getCarInfo($baojia[0]['brand_id']);
        $zhidaojia = $car_info['zhidaojia'];
        Tpl::output('zhidaojia', $zhidaojia);

        //报价的车源范围
        $scope = $model->getScopeByBaojiaId($bj_id);
        Tpl::output('scope', $scope);

        //管理员id
        $admin = $this->admin_info;
        Tpl::output('admin', $admin);

        //发布审核情况
        $baojia_public_list = Model()->table('hc_baojia_public,admin')
            ->field("hc_baojia_public.*,admin.admin_name")
            ->join('inner')
            ->on('hc_baojia_public.member_id=admin.admin_id')
            ->where(array('hc_baojia_public.baojia_id' => $bj_id))
            ->order('hc_baojia_public.id asc')
            ->select();
        Tpl::output('baojia_public_list', $baojia_public_list);
        Tpl::showpage('showbaojia');
    }

    /**
     * 违规下架
     */
    public function goods_lockupOp() {
        if (chksubmit()) {
            $commonids = $_POST['commonids'];
            $commonid_array = explode(',', $commonids);
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            $update = array();
            $update['goods_stateremark'] = trim($_POST['close_reason']);

            $where = array();
            $where['goods_commonid'] = array('in', $commonid_array);

            Model('goods')->editProducesLockUp($update, $where);
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        Tpl::output('commonids', $_GET['id']);
        Tpl::showpage('goods.close_remark', 'null_layout');
    }

    /**
     * 删除商品
     */
    public function goods_delOp() {
        if (chksubmit()) {
            $commonid_array = $_POST['id'];
            foreach ($commonid_array as $value) {
                if ( !is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            Model('goods')->delGoodsAll(array('goods_commonid' => array('in', $commonid_array)));
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
    }

    /**
     * 审核商品
     */
    public function goods_verifyOp(){
        if (chksubmit()) {
            $commonids = $_POST['commonids'];
            $commonid_array = explode(',', $commonids);
            foreach ($commonid_array as $value) {
                if (!is_numeric($value)) {
                    showDialog(L('nc_common_op_fail'), 'reload');
                }
            }
            $update2 = array();
            $update2['goods_verify'] = intval($_POST['verify_state']);

            $update1 = array();
            $update1['goods_verifyremark'] = trim($_POST['verify_reason']);
            $update1 = array_merge($update1, $update2);
            $where = array();
            $where['goods_commonid'] = array('in', $commonid_array);

            Model('goods')->editProduces($where, $update1, $update2);
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }
        Tpl::output('commonids', $_GET['id']);
        Tpl::showpage('goods.verify_remark', 'null_layout');
    }

    /**
     * ajax获取商品列表
     */
    public function get_goods_list_ajaxOp() {
        $commonid = $_GET['commonid'];
        if ($commonid <= 0) {
            echo 'false';exit();
        }
        $model_goods = Model('goods');
        $goodscommon_list = $model_goods->getGoodeCommonInfo(array('goods_commonid' => $commonid), 'spec_name');
        if (empty($goodscommon_list)) {
            echo 'false';exit();
        }
        $goods_list = $model_goods->getGoodsList(array('goods_commonid' => $commonid), 'goods_id,goods_spec,store_id,goods_price,goods_serial,goods_storage,goods_image');
        if (empty($goods_list)) {
            echo 'false';exit();
        }

        $spec_name = array_values((array)unserialize($goodscommon_list['spec_name']));
        foreach ($goods_list as $key => $val) {
            $goods_spec = array_values((array)unserialize($val['goods_spec']));
            $spec_array = array();
            foreach ($goods_spec as $k => $v) {
                $spec_array[] = '<div class="goods_spec">' . $spec_name[$k] . L('nc_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
            }
            $goods_list[$key]['goods_image'] = thumb($val, '60');
            $goods_list[$key]['goods_spec'] = implode('', $spec_array);
            $goods_list[$key]['url'] = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
        }

        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($goods_list);
        }
        echo json_encode($goods_list);
    }

    /**
     * 报价审核页面
     */
    public function show_bj_infoOp()
    {
        $bj_id = $_GET['bj_id'];
        $model = Model('hg_baojia');

        //报价信息
        $baojia = $model->where(array('bj_id' => $bj_id))->find();
        $baojia['bj_status'] = $model->getBaojiaStatus($baojia['bj_status'], $baojia['bj_step'], $baojia['bj_start_time'], $baojia['bj_end_time']);
        Tpl::output('baojia', $baojia);

        //整车信息
        $car_goods_class = Model('goods_class')->where('gc_id=' . $baojia['brand_id'])->find();
        Tpl::output('car_goods_class', $car_goods_class);

        //发布者
        $member = Model('member')->where('member_id=' . $baojia['m_id'])->find();
        Tpl::output('member', $member);

        //经销商信息
        $dealer = Model('hg_dealer')->where('d_id=' . $baojia['dealer_id'])->find();
        Tpl::output('dealer', $dealer);

        //取得价格信息
        $price = Model('hg_baojia_price')->where(array('bj_id' => $bj_id))->find();
        Tpl::output('price', $price);

        //报价自定义字段数据
        $fields = Model('hg_baojia_fields')->getBaojiaFields($bj_id);

        //汽车基本信息
        $car_info = Model('hg_car_info')->getCarInfo($baojia['brand_id']);

        //车辆排放标准
        $paifang = $car_info['paifang'][$fields['paifang']] == 0 ? '国五' : '国四';
        Tpl::output('paifang', $paifang);

        //取得车的颜色属性值
        $body_color = $car_info['body_color'][$fields['body_color']];
        Tpl::output('body_color', $body_color);

        //内饰颜色
        $interior_color = '';
        if ($baojia['bj_is_xianche']) {
            $interior_color = $car_info['interior_color'][$fields['interior_color']];
        } else {
            $bj_temp_internal_color = unserialize($baojia['bj_temp_internal_color']);//print_r($bj_temp_internal_color);exit;
            if ($bj_temp_internal_color) {
                foreach ($bj_temp_internal_color as $te) {
                    $temp_color[] = $car_info['interior_color'][$te];
                }
                $interior_color = implode(',', $temp_color);
            }

        }
        Tpl::output('interior_color', $interior_color);

        //座位数
        $seat_num = $car_info['seat_num'];
        Tpl::output('seat_num', $seat_num);

        //指导价
        $zhidaojia = $car_info['zhidaojia'];
        Tpl::output('zhidaojia', $zhidaojia);

        //报价销售地区
        $areas = $model->getAreasByBaojiaId($bj_id);
        Tpl::output('area', $areas);

        //保险公司
        $baoxian = Model('hg_baoxian')->where('bx_id=' . $baojia['bj_bx_select'])->find();
        Tpl::output('baoxian', $baoxian);

        //选装件
        $xzjs = $model->getXzjByBaojiaId($bj_id);
        Tpl::output('xzjs', $xzjs);

        //赠品
        $zengpin = $model->getZengpinByBaojiaId($bj_id);
        Tpl::output('zengpin', $zengpin);

        //其他收费
        $otherPrice = Model()->table('hg_baojia_other_price')->where('bj_id=' . $bj_id)->select();
        Tpl::output('otherPrice', $otherPrice);

        //可选精品
        $kxjps = $model->getXzjpByBaojiaId($bj_id);
        Tpl::output('kxjps', $kxjps);

        //取得车损险数据
        $csx = Model()->table('hg_baojia_baoxian_chesun_price')->where('bj_id=' . $bj_id)->order('type asc')->select();
        Tpl::output('csx', $csx);

        // 盗抢险
        $daoqiang = Model()->table('hg_baojia_baoxian_daoqiang_price')->where('bj_id=' . $bj_id)->order('type asc')->select();
        Tpl::output('daoqiang', $daoqiang);

        // 第三者责任险
        $compensates = $sanzhes = array();
        $sanzhe = Model()->table('hg_baojia_baoxian_sanzhe_price')->where('bj_id=' . $bj_id)->order('compensate asc,type asc')->select();
        if ($sanzhe) {
            foreach ($sanzhe as $s) {
                $compensates[] = $s['compensate'];
                $type = $s['type'];
                $tyszx[$type] = $s['bjm_percent'];
                $sanzhes[$type][] = $s['price'];
            }
        }
        Tpl::output('compensates', array_unique($compensates));
        Tpl::output('sanzhes', $sanzhes);

        // 车上人员险
        $renyuan_compensates = $renyuans = array();
        $renyuan = Model()->table('hg_baojia_baoxian_renyuan_price')->where('bj_id=' . $bj_id)->order('staff asc,type asc,compensate asc')->select();
        if ($renyuan) {
            foreach ($renyuan as $ry) {
                $type = $ry['type'];
                $tyryx[$type] = $ry['bjm_percent'];
                $staff = $ry['staff'];
                $renyuan_compensates[$staff][] = $ry['compensate'];
                $renyuans[$staff][$type][] = $ry['price'];
            }
        }
        Tpl::output('renyuan_compensates', $renyuan_compensates);
        Tpl::output('renyuans', $renyuans);

        // 玻璃险
        $bolis = array();
        $boli = Model()->table('hg_baojia_baoxian_boli_price')->where('bj_id=' . $bj_id)->order('type asc,state asc')->select();
        if ($boli) {
            foreach ($boli as $bl) {
                $type = $bl['type'];
                $bolis[$type][] = $bl['price'];
            }
        }
        Tpl::output('bolis', $bolis);

        // 车划痕险
        $huahens = $huanhen_compensates = array();
        $huahen = Model()->table('hg_baojia_baoxian_huahen_price')->where('bj_id=' . $bj_id)->order('type asc,compensate asc')->select();
        if ($huahen) {
            foreach ($huahen as $hh) {
                $type = $hh['type'];
                $tyhhx[$type] = $hh['bjm_percent'];
                $huanhen_compensates[] = number_format($hh['compensate'] / 10000, 1);
                $huahens[$type][] = $hh['price'];
            }
        }
        Tpl::output('huahens', $huahens);
        Tpl::output('huanhen_compensates', array_unique($huanhen_compensates));

        //不计免赔特约险
        $tybjmp = array(
            'csx' => array(number_format($csx[0]['bjm_price'], 2), number_format($csx[1]['bjm_price'], 2)),
            'dqx' => array(number_format($daoqiang[0]['bjm_price'], 2), number_format($daoqiang[1]['bjm_price'], 2)),
            'sanzhex' => $tyszx,
            'ryx' => $tyryx,
            'huahen' => $tyhhx
        );
        Tpl::output('tybjmp', $tybjmp);

        //刷卡费用
        $expandInfo = Model()->table("hg_baojia_expand_info")->where('bj_id=' . $bj_id)->select();
        Tpl::output('expandInfo', $expandInfo[0]);

        //随车资料
        $suicheInfo = array();
        $sql = "select * from car_hc_vehicle_tools_files where brand_id={$baojia['brand_id']}";
        $suiche = Model()->query($sql);
        if ($suiche) {
            foreach ($suiche as $su) {
                // 1 为移交文件 2 为交车工具
                $type = $su['type'];
                $suicheInfo[$type][] = $su['title'];
            }
        }
        Tpl::output('suicheInfo', $suicheInfo);

        Tpl::showpage('goods.bjinfo');
    }

    function show_bj_abstractOp()
    {
        $bj_id=$_GET['bj_id'];
        $model = Model('hg_baojia');
        $baojia=$model->where(array('bj_id'=>$bj_id))->select();

        Tpl::output('baojia', $baojia[0]);
        // 发布者
        $member = Model('member')->where('member_id='.$baojia[0]['m_id'])->find();
        Tpl::output('member', $member['member_name']);
        //取得价格信息
        $price=Model('hg_baojia_price')->where(array('bj_id'=>$bj_id))->find();
        //报价自定义字段数据
        $fields=Model('hg_baojia_fields')->getBaojiaFields($bj_id);
        // 交车时交付文件
        $wenjian=Model('hg_baojia_more')->where(array('bj_id'=>$bj_id,'model'=>'wenjian'))->find();
        Tpl::output('wenjian', $wenjian);
        //取得车的info
        $car_info=Model('hg_car_info')->getCarInfo($baojia[0]['brand_id']);
        //国别
        $car_guobie=$fields['guobie'];
        Tpl::output('guobie', $car_guobie);
        //座位数
        $seat_num=$car_info['seat_num'];
        Tpl::output('seat_num', $seat_num);
        //指导价
        $zhidaojia=$car_info['zhidaojia'];
        Tpl::output('zhidaojia', $zhidaojia);
        Tpl::output('price', $price);

        Tpl::showpage('goods.bjabstract');
    }



}
