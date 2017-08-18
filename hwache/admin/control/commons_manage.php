<?php
/**
 * Created by PhpStorm.
 * Date: 2016/10/25
 * Time: 11:24
 */
defined('InHG') or exit('Access Invalid!');

class commons_manageControl extends SystemControl
{
    const EXPORT_SIZE = 5000;

    public function __construct()
    {
        parent::__construct();
        Language::read('public');
    }

    public function common_manageOp()
    {
        $model = Model();
        //搜索条件
        $on = 'hg_daili_dealer.dl_id=member.member_id,
               hg_daili_dealer.dl_brand_id=goods_class.gc_id,
               hg_dealer.d_id=hg_daili_dealer.d_id';
        $field = 'hg_daili_dealer.id,member.member_name,hg_dealer.d_name,
                  goods_class.gc_name,hg_dealer.d_areainfo,hg_daili_dealer.dl_add_time,
                  hg_daili_dealer.dl_status';
        $conditions['dl_status'] = ['neq', 3];
        $conditions['dl_step'] = ['eq', 10];
        if ($_GET['query'] == 1) {
            if (!empty($_GET['user'])) {
                $conditions['member.member_name'] = ['like', '%' . $_GET['user'] . '%'];
            }
            if (!empty($_GET['name'])) {
                $conditions['member.member_truename'] = ['like', '%' . $_GET['name'] . '%'];
            }
            if (isset($_GET['mobile'])) {
                $conditions['member.member_mobile'] = ['like', $_GET['mobile'] . '%'];
            }
            if (!empty($_GET['order_state'])) {
                $conditions['hg_daili_dealer.dl_status'] = ['eq', $_GET['order_state']];
            }
            if (!empty($_GET['common_area'])) {
                $conditions['hg_dealer.d_areainfo'] = ['eq', $_GET['common_area']];
            }
            if (!empty($_GET['brand'])) {
                $conditions['hg_daili_dealer.dl_brand_id'] = ['eq', $_GET['brand']];
            }
            if (!empty($_GET['dealer'])) {
                $conditions['hg_dealer.d_name'] = ['eq', $_GET['dealer']];
            }
            if (!empty($_GET['query_start_time']) || !empty($_GET['query_end_time'])) {
                if (empty($_GET['query_start_time'])) {
                    $start_time = 0;
                } else {
                    $start_time = strtotime($_GET['query_start_time']);
                }
                $end_time = strtotime($_GET['query_end_time'] . "+1day");
                $conditions['dl_add_time'] = ['between', [$start_time, $end_time]];
            }
            $page_view = 'commons_manage_table';
            Tpl::setLayout('layout_ajax');

        } else {
            $page_view = 'commons_manage';
        }
        $class_list = $model->table('hg_daili_dealer,member,goods_class,hg_dealer')
            ->field($field)
            ->join('inner')
            ->on($on)
            ->where($conditions)
            ->order('hg_daili_dealer.id asc')
            ->page(10)
            ->select();
        Tpl::output('class_list', $class_list);
        Tpl::output('page', $model->showpage());
        //归属地区
        $on = 'hg_dealer.d_id=hg_daili_dealer.d_id';
        $region = Model()
            ->table('hg_daili_dealer,hg_dealer')
            ->join('left')
            ->on($on)
            ->where($conditions)
            ->field('d_areainfo')
            ->group('d_areainfo')
            ->select();
        Tpl::output('region', $region);
        //品牌列表
        $on = 'hg_daili_dealer.dl_brand_id = goods_class.gc_id';
        $brand_list = Model()
            ->table('hg_daili_dealer,goods_class')
            ->join('left')
            ->on($on)
            ->where($conditions)
            ->field('dl_brand_id,gc_name')
            ->group('gc_name')
            ->select();
        Tpl::output('brand_list', $brand_list);
        Tpl::showpage($page_view);
    }

    public function basic_infoOp()
    {
        $dl_id = $_GET['dl_id'];
        $on = 'hg_daili_dealer.dl_brand_id=goods_class.gc_id';
        $dealer = Model()
            ->table('hg_daili_dealer,goods_class')
            ->join('left')
            ->on($on)
            ->where(array('id' => $dl_id))
            ->find();
        Tpl::output('dealer', $dealer);
        $dealers = Model('hg_dealer')->where(array('d_id' => $dealer['d_id']))->find();
        $users = Model('member')->where(array('member_id' => $dealer['dl_id']))->find();
        $seller  = Model('seller')->where(array('member_id' => $dealer['dl_id']))->find();
        $condition['d_id'] = array(
            'in',
            array($dealer['dl_competitor_dealer_id_1'], $dealer['dl_competitor_dealer_id_2'])
        );
        $contend = Model('hg_dealer')->where($condition)->select();
        //var_dump($contend);
        Tpl::output('contend', $contend);
        Tpl::output('dealers', $dealers);
        Tpl::output('users', $users);
        Tpl::output('seller', $seller);
        Tpl::showpage('commons_info');

    }

    /**
     * 其他基本资料
     */
    public function other_infoOp()
    {
        $dl_id = $_GET['dl_id'];
        if (empty($dl_id)) {
            exit('操作失败');
        }
        //读取服务专员,get地址栏要传一个 daili_dealer_id过来
        $waiter = Model('hg_waiter')->where(array('daili_dealer_id' => $dl_id))->select();
        Tpl::output('waiter', $waiter);
        Tpl::output('daili_dealer_id', $dl_id);

        //保险状态
        $type = Model('hg_daili_dealer')->where(array('id' => $dl_id))->field('dl_baoxian')->find();
        Tpl::output('type', $type);

        //保险部分
        $on = 'hg_dealer_baoxian.co_id = hg_baoxian.bx_id';
        $baoxian = Model()
            ->table('hg_dealer_baoxian,hg_baoxian')
            ->join('inner')
            ->on($on)
            ->where(array('hg_dealer_baoxian.daili_dealer_id' => $dl_id))
            ->select();
        Tpl::output('baoxian', $baoxian);

        //免费提供
        $on = 'hg_dealer_zengpin.zp_id=hg_zengpin.id';
        $free = Model()
            ->table('hg_dealer_zengpin,hg_zengpin')
            ->join('inner')
            ->on($on)
            ->where(array('hg_dealer_zengpin.daili_dealer_id' => $dl_id))
            ->field('hg_dealer_zengpin.dl_zp_num,hg_zengpin.title')
            ->select();
        Tpl::output('free', $free);

        //其他收费
        $on = 'hg_dealer_other_price.other_id=hg_fields.id';
        $zafei = Model()
            ->table('hg_dealer_other_price,hg_fields')
            ->join('left')
            ->on($on)
            ->where(array('hg_dealer_other_price.daili_dealer_id' => $dl_id))
            ->field('hg_dealer_other_price.other_price,hg_fields.title')
            ->select();
        Tpl::output('zafei', $zafei);

        //刷卡标准,节能补贴
        $Charge = Model()
            ->table('hg_dealer_standard')
            ->where(array('daili_id' => $dl_id))
            ->find();
        Tpl::output('charge', $Charge);

        //工作时段
        $work = Model()
            ->table('hg_daili_dealer_workday')
            ->where(array('daili_dealer_id' => $dl_id))
            ->find();
        Tpl::output('work', $work);
        //var_dump($work);
        //临牌跟上牌
        $data = Model()
            ->table('hg_daili_dealer')
            ->where(array('id' => $dl_id))
            ->find();
        Tpl::output('data', $data);
        Tpl::showpage('other_info');
    }

    public function cars_listOp()
    {
        $id = $_GET['id'];   //获取经销商代理id dealer_daili_id
        if (empty($id)) {
            exit('操作失败');
        }
        $model = Model();
        $on = 'goods_class_staple.gc_id_2=goods_class.gc_id,goods_class_staple.gc_id_3=hg_car_info.gc_id';
        $field = 'goods_class_staple.staple_name,
                 goods_class.gc_name,hg_car_info.value,
                 goods_class_staple.staple_id,
                 goods_class_staple.gc_id_3,
                 goods_class_staple.daili_dealer_id';
        Tpl::output('daili_dealer_id', $id);
        if (!empty($_GET['order_name'])) {
            $gc_name = $_GET['order_name'];
            $car_lists = $model->table('goods_class_staple,goods_class,hg_car_info')
                ->field($field)
                ->join('inner')
                ->on($on)
                ->where(array(
                    'goods_class_staple.status'          => 0,
                    'goods_class_staple.daili_dealer_id' => $id,
                    'hg_car_info.name'                   => 'zhidaojia',
                    'goods_class.gc_name'                => $gc_name
                ))
                ->select();
            $page_view = "lists_car_table";
            Tpl::setLayout('layout_ajax');
        } else {
            $car_list = $model->table('goods_class_staple,goods_class,hg_car_info')
                ->field($field)
                ->join('inner')
                ->on($on)
                ->where(array(
                    'goods_class_staple.status'          => 0,
                    'goods_class_staple.daili_dealer_id' => $id,
                    'hg_car_info.name'                   => 'zhidaojia'
                ))
                ->group('goods_class.gc_name')
                ->select();

            $car_lists = $model->table('goods_class_staple,goods_class,hg_car_info')
                ->field($field)
                ->join('inner')
                ->on($on)
                ->where(array(
                    'goods_class_staple.status'          => 0,
                    'goods_class_staple.daili_dealer_id' => $id,
                    'hg_car_info.name'                   => 'zhidaojia'
                ))
                ->select();
            $page_view = "lists_car";
        }

        Tpl::output('gc_name', $gc_name);
        Tpl::output('car_lists', $car_lists);
        Tpl::output('car_list', $car_list);
        Tpl::showpage($page_view);
    }

    /**
     * 读取车辆前后装信息
     */
    public function car_infoOp()
    {
        $daili_dealer_id = $_GET['dl_id'];
        $gc_id = $_GET['gc_id'];
        $staple_id = $_GET['sp_id'];
        $price = Model('hg_car_info')
            ->where(array('gc_id' => $gc_id, 'name' => 'zhidaojia'))
            ->field('value')
            ->find();
        $carmodel = Model('goods_class_staple')
            ->where(array('staple_id' => $staple_id))
            ->field('gc_id_1,gc_id_2,gc_id_3,daili_dealer_id')
            ->find();
        $condition['gc_id'] = array('in', array($carmodel['gc_id_1'], $carmodel['gc_id_2'], $carmodel['gc_id_3']));
        $carmodels = Model('goods_class')->where($condition)->field('gc_name')->select();
        foreach ($carmodels as $car) {
            $arr[] = $car['gc_name'];
        }
        $vehicle_model = Model('goods_class')->where(array('gc_id'=>$gc_id))->field('vehicle_model')->find();
        $carout = implode('>', $arr);
        Tpl::output('daili_dealer_id', $daili_dealer_id);
        Tpl::output('price', $price);
        Tpl::output('carout', $carout);
        Tpl::output('vehicle_model', $vehicle_model['vehicle_model']);
        //读取前装,后装,非原厂
        $field = 'hg_xzj_list.*,hg_xzj_daili.xzj_cs_serial,hg_xzj_daili.xzj_has_num,hg_xzj_daili.xzj_fee';
        $cond = 'hg_xzj_list.id=hg_xzj_daili.xzj_list_id';
        $fyc['qz'] = Model()
            ->table('hg_xzj_list,hg_xzj_daili')
            ->join('left')
            ->on($cond)
            ->where(array(
                'hg_xzj_list.xzj_yc'           => 1,
                'hg_xzj_list.xzj_front'        => 1,
                'hg_xzj_list.car_brand'        => $gc_id,
                'hg_xzj_daili.daili_dealer_id' => $daili_dealer_id,
                'hg_xzj_daili.staple_id'       => $staple_id
            ))
            ->field($field)
            ->select();
        $fyc['hz'] = Model()
            ->table('hg_xzj_list,hg_xzj_daili')
            ->join('left')
            ->on($cond)
            ->where(array(
                'hg_xzj_list.xzj_yc'           => 1,
                'hg_xzj_list.xzj_front'        => 0,
                'hg_xzj_list.car_brand'        => $gc_id,
                'hg_xzj_daili.daili_dealer_id' => $daili_dealer_id,
                'hg_xzj_daili.staple_id'       => $staple_id
            ))
            ->field($field)
            ->select();
        $condition = [
            'hg_xzj_list.status'           => ['neq', 2],
            'hg_xzj_daili.status'          => ['neq', 2],
            'hg_xzj_list.xzj_yc'           => 0,
            'hg_xzj_list.car_brand'        => $gc_id,
            'hg_xzj_daili.daili_dealer_id' => $daili_dealer_id,
            'hg_xzj_daili.staple_id'       => $staple_id
        ];
        $fyc['xz'] = Model()
            ->table('hg_xzj_list,hg_xzj_daili')
            ->join('left')
            ->on($cond)
            ->where($condition)
            ->select();
        Tpl::output('fyc', $fyc);
        Tpl::showpage('commons_car');
    }

    /**
     * 编辑的显示部分
     */
    public function edit_infoOp()
    {
        $dl_id = $_GET['dl_id'];
        $on = 'hg_daili_dealer.dl_brand_id=goods_class.gc_id';
        $dealer = Model()
            ->table('hg_daili_dealer,goods_class')
            ->join('left')
            ->on($on)
            ->where(array('id' => $dl_id))
            ->find();
        Tpl::output('dealer', $dealer);
        $dealers = Model('hg_dealer')->where(array('d_id' => $dealer['d_id']))->find();
        $users = Model('member')->where(array('member_id' => $dealer['dl_id']))->find();
        $condition['d_id'] = array(
            'in',
            array($dealer['dl_competitor_dealer_id_1'], $dealer['dl_competitor_dealer_id_2'])
        );
        $contend = Model('hg_dealer')->where($condition)->select();
        //var_dump($contend);
        Tpl::output('contend', $contend);
        Tpl::output('dealers', $dealers);
        Tpl::output('users', $users);
        //下半部分
        $area = Model('area')->field('area_id,area_name,area_parent_id')
                                    ->where(['area_deep'=>1,'not_mainland'=>0])
                                    ->select();
        Tpl::output('area', $area);
        $region = Model('area')->getAreaList();
        Tpl::output('region', $region);
        //车源范围
        $Carscop = Model('hc_scope')->where(array('dealer_id' => $dl_id))->find();
        Tpl::output('carscop', $Carscop);
        //车源范围操作日志
        $ons = 'hc_scope_log.operate_id=admin.admin_id';
        $scop_log = Model()->table('hc_scope_log,admin')->join('left')->on($ons)->where(array('hc_scope_log.dealer_id' => $dl_id))->select();
        Tpl::output('scop_log', $scop_log);
        //审核与退回按钮及操作日志
        $on = 'hc_verify_log.dealer_id=hg_daili_dealer.id,hc_verify_log.verify_id=admin.admin_id';
        $verify_log = Model()
            ->table('hc_verify_log,hg_daili_dealer,admin')
            ->join('left')
            ->on($on)
            ->field('hc_verify_log.verify_content,hc_verify_log.verify_time,hg_daili_dealer.dl_add_time,admin.admin_name')
            ->where(array('hc_verify_log.dealer_id' => $dl_id))
            ->select();
        Tpl::output('verify_log', $verify_log);
        //提交证据文件列表
        $on = 'hc_evidence.operate_id=admin.admin_id';
        $evidence_list = Model()->table('hc_evidence,admin')
            ->join('left')
            ->on($on)
            ->where(array('dealer_id' => $dl_id))
            ->field('hc_evidence.*,admin.admin_name')
            ->select();
        Tpl::output('evidence_list', $evidence_list);
        Tpl::showpage('edit.info');
    }

    /**
     * 客户文件
     */
    public function file_manageOp()
    {
        $use_type = $_GET['type_id'];
        $identity = $_GET['category'];
        $dl_id = $_GET['dl_id'];
        if (empty($dl_id)) {
            die('操作超时');
        }
        Tpl::output('dealer_id', $dl_id);
        $arr = array();
        $view = 'file.manage';
        if ($_GET['query'] == 1) {
            $on = 'hg_file_agent.file_id=hg_file.file_id';
            $file_list = Model()
                ->table('hg_file_agent,hg_file')
                ->on($on)
                ->where(array(
                    'hg_file_agent.daili_dealer_id'  => $dl_id,
                    'hg_file_agent.status'           => 0,
                    'hg_file_agent.car_use_type'     => $use_type,
                    'hg_file_agent.customer_shenfen' => $identity
                ))
                ->field('hg_file_agent.num,hg_file.title,hg_file_agent.file_url,hg_file_agent.cate_id,isself')
                ->select();
            foreach ($file_list as $team => $index) {
                if ($index['cate_id'] == 1) {
                    $arr['one'][] = $index;
                }
                if ($index['cate_id'] == 2) {
                    $arr['two'][] = $index;
                }
                if ($index['cate_id'] == 3) {
                    $arr['three'][] = $index;
                }
                if ($index['cate_id'] == 4) {
                    $arr['four'][] = $index;
                }
                if ($index['cate_id'] == 5) {
                    $arr['five'][] = $index;
                }
            }
            $view = 'file.manage.table';
            Tpl::setLayout('layout_ajax');
        }
        Tpl::output('file', $arr);
        Tpl::showpage($view);
    }


    //接受ajax请求
    public function ajaxqueryOp()
    {
        $brand_id = $_GET['brand_id'];
        $on = 'hg_daili_dealer.d_id=hg_dealer.d_id';
        $dealer = Model()
            ->table('hg_daili_dealer,hg_dealer')
            ->join('left')
            ->on($on)
            ->where(array('hg_daili_dealer.dl_brand_id' => $brand_id, 'dl_step' => 10, 'dl_status' => ['neq', 3]))
            ->field('hg_dealer.d_name')
            ->group('hg_dealer.d_name')
            ->select();
        echo json_encode($dealer);
    }

    /**
     * 地区的ajax请求列表
     */
    public function ajaxareaOp()
    {
        $area_name = $_GET['area_name'];
        $area_id = Model('area')->where(array('area_name' => $area_name))->field('area_id')->find();
        if (!empty($area_id['area_id'])) {
            $condition = 'area_parent_id=' . $area_id['area_id'];
            $field = 'area_id,area_name';
            $data = Model('area')->getAreaList($condition, $field);
            echo json_encode($data);
        }
    }

    /**
     * 车源范围操作
     */
    public function addscopeOp()
    {

        $province = $_GET['city_name'];
        $area_id = $_GET['city_id_name'];
        $dealer_id = $_GET['dealer_id'];
        $model_id = Model('hc_scope')->where(array('dealer_id' => $dealer_id))->find();
        $arr['dealer_id'] = $dealer_id;
        $arr['add_time'] = time();
        $arr['operate_id'] = $this->admin_info['id'];
        if (empty($dealer_id)) {
            die('操作超时,请重新登录!!');
        }
        if (empty($model_id)) {
            $arr['province1_name'] = $province[1];
            $arr['province2_name'] = $province[2];
            $arr['province3_name'] = $province[3];
            $arr['area1_name'] = $area_id[1];
            $arr['area2_name'] = $area_id[2];
            $arr['area3_name'] = $area_id[3];
            $data = array_unique($arr);
            if (array_key_exists('province3_name',$data) || array_key_exists('province2_name',$data) || array_key_exists('province1_name',$data)) {
                if (!array_key_exists('area3_name',$data)) {
                    $arr['area3_name'] = '';
                }
                if (!array_key_exists('area2_name',$data)) {
                    $arr['area2_name'] = '';
                }
                if (!array_key_exists('area1_name',$data)) {
                    $arr['area1_name'] = '';
                }
            }
            $model = Model('hc_scope')->insert($arr);
        } else {
            if (!empty($province[1])) {
                $arr['province1_name'] = $province[1];
            }
            if (!empty($province[2])) {
                $arr['province2_name'] = $province[2];
            }
            if (!empty($province[3])) {
                $arr['province3_name'] = $province[3];
            }
            if (!empty($area_id[1])) {
                $arr['area1_name'] = $area_id[1];
            }
            if (!empty($area_id[2])) {
                $arr['area2_name'] = $area_id[2];
            }
            if (!empty($area_id[3])) {
                $arr['area3_name'] = $area_id[3];
            }
            $data = array_unique($arr);
            if (array_key_exists('province3_name',$data) || array_key_exists('province2_name',$data) || array_key_exists('province1_name',$data)) {
                if (!array_key_exists('area3_name',$data)) {
                    $arr['area3_name'] = '';
                }
                if (!array_key_exists('area2_name',$data)) {
                    $arr['area2_name'] = '';
                }
                if (!array_key_exists('area1_name',$data)) {
                    $arr['area1_name'] = '';
                }
            }
            $model = Model('hc_scope')->where(array('dealer_id' => $dealer_id))->update($arr);
        }
        $model_log = Model('hc_scope_log')->insert($arr);
        if ($model) {
            echo json_encode([
                'error_code' => 1,
            ]);
        } else {
            echo json_encode([
                'error_code' => 0,
            ]);
        }

    }


    /**
     * 证据文件上传
     */
    public function add_uploadOp()
    {
        $dealer_id = $_POST['dealer_id'];
        if (empty($dealer_id)) {
            die('操作失败');
        }
        $arr['dealer_id'] = $dealer_id;
        $arr['sign'] = $_POST['sign'];
        $arr['add_time'] = time();
        $arr['operate_id'] = $this->admin_info['id'];
        //如果没有文件名出现,说明只有文字
        if (empty($_POST['name'])) {
            $arr['contents'] = $_POST['contents'];
            $model = Model('hc_evidence')->insert($arr);
        } else {
            $type = substr($_POST['name'], strrpos($_POST['name'], '.') + 1);
            $upload = new UploadFile();
            $upload->file_name = date('YmdHis') . mt_rand(100000, 999999) . '.' . $type;
            if ($type == 'mp3') {
                $upload->set('default_dir', '../../www/upload/evidence/mp3');
                $upload->set('max_size', '1024102400');
            } else {
                $upload->set('default_dir', '../../www/upload/evidence');
                $upload->set('max_size', '10241024');
            }
            $result = $upload->upfile('imageFile');
            if ($result) {
                $file = 'file_' . $_POST['num'];
                if ($type == 'mp3') {
                    $arr[$file] = 'mp3/' . $upload->file_name;
                } else {
                    $arr[$file] = $upload->file_name;
                }
                if ($_POST['num'] == 1) {
                    $arr['contents'] = $_POST['contents'];
                    $arr['file_1_name'] = $_POST['name'];
                    $model = Model('hc_evidence')->insert($arr);
                } else {
                    $file_name = 'file_' . $_POST['num'] . '_name';
                    $arr[$file_name] = $_POST['name'];
                    $model = Model('hc_evidence')
                        ->where(array('dealer_id' => $dealer_id, 'sign' => $arr['sign']))
                        ->update($arr);
                }
            }
        }
        if ($model) {
            echo json_encode([
                'error_code' => 1,
            ]);
        } else {
            echo json_encode([
                'error_code' => 0,
            ]);
        }

    }

    /**
     * 审核通过按钮
     */
    public function audit_dealerOp()
    {
        $dealer_id = $_POST['dealer_id'];
        if (!empty($dealer_id)) {
            $model = Model('hg_daili_dealer')
                ->where(array('id' => $dealer_id))
                ->update(array('dl_status' => 2));
        }
        $arr = [
            'dealer_id'      => $dealer_id,
            'verify_time'    => time(),
            'verify_id'      => $this->admin_info['id'],
            'verify_content' => '审核通过'

        ];
        if ($model) {
            $reuslt = Model('hc_verify_log')->insert($arr);
            echo json_encode([
                'error_code' => 1,
            ]);
        } else {
            echo json_encode([
                'error_code' => 0,
            ]);
        }
    }

    /**
     * 审核不通过按钮
     */
    public function pass_dealerOp()
    {
        $dealer_id = $_POST['dealer_id'];
        if (!empty($dealer_id)) {
            $model = Model('hg_daili_dealer')
                ->where(array('id' => $dealer_id))
                ->update(array('dl_status' => 4));
        }
        $arr = [
            'dealer_id'      => $dealer_id,
            'verify_time'    => time(),
            'verify_id'      => $this->admin_info['id'],
            'verify_content' => '审核不通过'

        ];
        if ($model) {
            $reuslt = Model('hc_verify_log')->insert($arr);
            echo json_encode([
                'error_code' => 1,
            ]);
        } else {
            echo json_encode([
                'error_code' => 0,
            ]);
        }
    }


}
