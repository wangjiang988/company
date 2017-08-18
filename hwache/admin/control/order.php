<?php
/**
 * 交易管理
 */

defined('InHG') or exit('Access Invalid!');
include(__DIR__.'/../vendor/SendSms.php');

class orderControl extends SystemControl
{
    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    public function __construct()
    {
        parent::__construct();
        Language::read('trade');
        Language::read('order');
    }

    public function indexOp($export=false)
    {
        $model_orders = Model('hc_order');

        $condition['order_id'] = $_GET['order_id'];//订单号
        $condition['user_phone'] = trim($_GET['user_phone']);//用户手机号
        $condition['seller_name'] = trim($_GET['seller_name']);//售方用户名
        $condition['dealer_name'] = trim($_GET['dealer_name']);//经销商
        $condition['order_create_time_start'] = trim($_GET['order_create_starttime']);//下单时间查询始
        $condition['order_create_time_end'] = trim($_GET['order_create_endtime']);//下单时间查询止
        $condition['brand_id'] = trim($_GET['brand_id']);//品牌
        $condition['gc_series'] = trim($_GET['gc_series']);//车系
        $condition['gc_name'] = trim($_GET['gc_name']);//型号
        $condition['order_finished_starttime'] = trim($_GET['order_finished_starttime']);//订单结束时间查询始
        $condition['order_finished_endtime'] = trim($_GET['order_finished_endtime']);//订单结束时间查询止
        $condition['user_order_pri_status'] = trim($_GET['user_order_status']);//客户订单主状态
        $condition['user_order_sub_status'] = trim($_GET['user_order_state']);//客户订单子状态
        $condition['appoint_car_starttime'] = trim($_GET['appoint_car_starttime']);//约定交车时间查询始
        $condition['appoint_car_endtime'] = trim($_GET['appoint_car_endtime']);//约定交车时间查询止
        $condition['seller_order_pri_status'] = trim($_GET['seller_order_status']);//售方订单主状态
        $condition['seller_order_sub_status'] = trim($_GET['seller_order_state']);//售方订单子状态
        $condition['xzjp_is_install'] = trim($_GET['xzjp_is_install']);//选装修改协商中
        $condition['user_pay_timeout'] = trim($_GET['user_pay_timeout']);//当前客户超时
        $condition['order_in_negotiation'] = trim($_GET['user_in_negotiation']);//未了结工单
        $condition['conciliate_department'] = trim($_GET['conciliate_department']);//当前处理部门

        if ($_GET['query'] == 1) {
            $page_view = 'order.index.table';
            Tpl::setLayout('layout_ajax');
        } else {
            $model_goods = Model('hg_baojia');

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

            // 客户订单主状态
            $user_order_pri_status_list = $model_orders->getOrderStatusList(1, 0);
            Tpl::output('user_order_pri_status_list', $user_order_pri_status_list);

            //客户订单子状态
            if (isset($_GET['user_order_pri_status'])) {
                $user_order_sub_status_list = $model_orders->getOrderStatusList(1, $condition['user_order_pri_status']);
                Tpl::output('user_order_sub_status_list', $user_order_sub_status_list);
            }

            //商家订单主状态
            $seller_order_pri_status_list = $model_orders->getOrderStatusList(2, 0);
            Tpl::output('seller_order_pri_status_list', $seller_order_pri_status_list);

            //商家订单子状态
            if (isset($_GET['user_order_pri_status'])) {
                $seller_order_sub_status_list = $model_orders->getOrderStatusList(2, $condition['user_order_pri_status']);
                Tpl::output('seller_order_sub_status_list', $seller_order_sub_status_list);
            }

            $page_view = 'order.index';
        }

        //报价列表

        if($export==true){
            return $model_orders->getOrderList($condition,null);
        }else{
            $orders_list = $model_orders->getOrderList($condition, 10);
        }
        Tpl::output('orders_list', $orders_list);
        Tpl::output('page', $model_orders->showpage());
        Tpl::output('uri',$this->getServerQueryString());
        Tpl::showpage($page_view);
    }

    /**
     *  订单详情
     */
    function show_orderOP()
    {
        $id = intval($_GET['id']);
        Tpl::output('id', $id);

        //订单信息
        $order = Model('hc_order')->find($id);
        if (empty($order)) {
            showMessage("订单不存在", "/index.php", "html", "error");
        }

        Tpl::output('order', $order);

        //订单状态
        $order_progress_status = Model("hc_order_progress_status")->where("sub_status=" . $order['order_state'])->find();
        Tpl::output('order_progress_status', $order_progress_status);

        //交车时间
        $appoint_car = Model('hc_order_appoint_car')->where("order_id=" . $id)->find();
        $appoint_car_time = $appoint_car['is_feeback'] == 0 ? $appoint_car['default_data'] : ($appoint_car['is_feeback'] == 1 ? $appoint_car['member_data'] : $appoint_car['member_data']);
        Tpl::output('appoint_car_time', $appoint_car_time);

        //计划上牌地区
        $shangpai_area = Model('area')->where('area_id=' . $order['shangpai_area'])->find();
        Tpl::output('shangpai_area', $shangpai_area['area_name']);

        //报价销售区域
        $areas = Model('hg_baojia')->getScopeByBaojiaId($order['bj_id']);
        Tpl::output('area', $areas);

        //用户信息
        $user = Model("users")->find($order['user_id']);
        $user['extension'] = Model("user_extension")->where('user_id=' . $order['user_id'])->find();
        Tpl::output('user', $user);

        //售方信息
        $seller = Model("member")->find($order['seller_id']);
        Tpl::output('seller', $seller);

        //经销商信息
        $dealer = Model('hg_dealer')->find($order['dealer_id']);
        Tpl::output('dealer', $dealer);

        //报价信息
        $baojia = Model("hg_baojia")->find($order['bj_id']);
        Tpl::output('baojia', $baojia);

        //车价信息
        $price = Model('hc_price')->find($order['bj_id']);
        Tpl::output('price', $price);

        //整车信息
        $car_goods_class = Model('goods_class')->where('gc_id=' . $baojia['brand_id'])->find();
        Tpl::output('car_goods_class', $car_goods_class);

        //报价自定义字段数据
        $fields = Model('hg_baojia_fields')->getBaojiaFields($order['bj_id']);

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
            $bj_temp_internal_color = unserialize($baojia['bj_temp_internal_color']);
            if ($bj_temp_internal_color) {
                foreach ($bj_temp_internal_color as $te) {
                    $temp_color[] = $car_info['interior_color'][$te];
                }
                $interior_color = implode(',', $temp_color);
            }

        }
        Tpl::output('interior_color', $interior_color);

        //指导价
        $zhidaojia = $car_info['zhidaojia'];
        Tpl::output('zhidaojia', $zhidaojia);

        //国别
        $guobie = $car_info['guobie'] == 0 ? '国产' : '进口';
        Tpl::output('guobie', $guobie);

        //座位数
        $seat_num = $car_info['seat_num'];
        Tpl::output('seat_num', $seat_num);

        //协商确认状态
        $conciliation_status_list = [1 => '待接单', 2 => '正在接单', 3 => '已了结', 4 => '协商确认中', 5 => '平台裁决中'];
        Tpl::output('conciliation_status_list', $conciliation_status_list);

        //华车员工部门
        $department = Model('hc_admin_dept')->where('is_del=0')->select();
        foreach ($department as $dep) {
            $dep_id = $dep['id'];
            $deps[$dep_id] = $dep['name'];
        }
        Tpl::output('departments', $deps);

        //调解信息
        $con_where = 'order_id=' . $id;
        $con_where .= $_GET['conciliation_staus'] ? ' and status=' . $_GET['conciliation_staus'] : '';
        $con_where .= $_GET['target'] ? ' and target=' . $_GET['target'] : '';
        $conciliation = Model('hc_order_conciliation')->where($con_where)->select();
        Tpl::output('conciliation', $conciliation);

        //协商方案发起人信息
        $consultants = [];
        $conciliation_consult = Model()->table('hc_order_conciliation,hc_order_conciliation_consult')
            ->field('hc_order_conciliation_consult.*')
            ->on('hc_order_conciliation.id=hc_order_conciliation_consult.ocid')
            ->where("hc_order_conciliation.order_id=" . $id . " and hc_order_conciliation_consult.admin_id >0")
            ->select();
        if ($conciliation_consult) {
            foreach ($conciliation_consult as $cc) {
                $consultants[$cc['ocid']] = $cc['admin_id'];
            }
        }
        Tpl::output('consultants', $consultants);


        //暂停倒计时数据
        $order_date = Model('hc_order_date')->where("order_id=" . $id)->find();;
        Tpl::output('order_date', $order_date);

        Tpl::output('admin_info', $this->admin_info);

        Tpl::showpage('order.detail');
    }

    /**
     *  新建工单
     */
    function create_new_conciliationOp()
    {
        $id = intval($_GET['id']);

        //----保存新建工单数据
        if ($_POST['query'] == 1) {

            //保存新建争议工单
            $insert_array = [
                'order_id' => $id,
                'subject' => $_POST['subject'],
                'target' => $_POST['target'],
                'content' => $_POST['content'] ?: '',
                'follow_depid' => $_POST['follow_depid'],
                'status' => 1,
                'admin_id' => $this->admin_info['id'],
            ];
            $ocid = Model('hc_order_conciliation')->insert($insert_array);

            //保存证据
            $files = $_FILES['files'];
            if (!empty($_FILES['files']['name'])) {//上传图片
                for ($i = 0; $i < count($files); $i++) {
                    if ($_FILES["files"]["size"][$i] != 0) {
                        $pi = pathinfo($_FILES['files']['name'][$i]);
                        $file_name = newName() . '.' . $pi['extension'];
                        $file_path = BASE_UPLOAD_PATH . DS . ATTACH_ORDER . DS . $file_name;
                        if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $file_path)) {
                            //将图片同步上传到七牛云上
                            $pic_path = ATTACH_ORDER . '/' . $file_name;
                            Qiniu::make()->upload($file_path, $pic_path);

                            //保存证据
                            $insert_evidence = ['ocid' => $ocid,
                                'evidence' => $pi['basename'],
                                'evidence_path' => ATTACH_ORDER . '/' . $file_name,
                                'type' => 1];
                            Model('hc_order_conciliation_evidence')->insert($insert_evidence);
                        }
                    }
                }
            }

            //返回订单详情页面
            return redirect("index.php?act=order&op=show_order&id=$id");
        }
        //----保存新建工单end

        //----加载新建工单页面

        //订单信息
        $order = Model('hc_order')->find($id);
        if (empty($order)) {
            showMessage("订单不存在", "/index.php", "html", "error");
        }

        Tpl::output('order', $order);

        //订单状态
        $order_progress_status = Model("hc_order_progress_status")->where("sub_status=" . $order['order_state'])->find();
        Tpl::output('order_progress_status', $order_progress_status);

        //交车时间
        $appoint_car = Model('hc_order_appoint_car')->where("order_id=" . $id)->find();
        $appoint_car_time = $appoint_car['is_feeback'] == 0 ? $appoint_car['default_data'] : ($appoint_car['is_feeback'] == 1 ? $appoint_car['member_data'] : $appoint_car['member_data']);
        Tpl::output('appoint_car_time', $appoint_car_time);

        //计划上牌地区
        $shangpai_area = Model('area')->where('area_id=' . $order['shangpai_area'])->find();
        Tpl::output('shangpai_area', $shangpai_area['area_name']);

        //报价销售区域
        $areas = Model('hg_baojia')->getAreasByBaojiaId($order['bj_id']);
        Tpl::output('area', $areas);

        //用户信息
        $user = Model("users")->find($order['user_id']);
        $user['extension'] = Model("user_extension")->where('user_id=' . $order['user_id'])->find();
        Tpl::output('user', $user);

        //售方信息
        $seller = Model("member")->find($order['seller_id']);
        Tpl::output('seller', $seller);

        //经销商信息
        $dealer = Model('hg_dealer')->find($order['dealer_id']);
        Tpl::output('dealer', $dealer);

        //报价信息
        $baojia = Model("hg_baojia")->find($order['bj_id']);
        Tpl::output('baojia', $baojia);

        //车价信息
        $price = Model('hc_price')->find($order['bj_id']);
        Tpl::output('price', $price);

        //整车信息
        $car_goods_class = Model('goods_class')->where('gc_id=' . $baojia['brand_id'])->find();
        Tpl::output('car_goods_class', $car_goods_class);

        //报价自定义字段数据
        $fields = Model('hg_baojia_fields')->getBaojiaFields($order['bj_id']);

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
            $bj_temp_internal_color = unserialize($baojia['bj_temp_internal_color']);
            if ($bj_temp_internal_color) {
                foreach ($bj_temp_internal_color as $te) {
                    $temp_color[] = $car_info['interior_color'][$te];
                }
                $interior_color = implode(',', $temp_color);
            }

        }
        Tpl::output('interior_color', $interior_color);

        //指导价
        $zhidaojia = $car_info['zhidaojia'];
        Tpl::output('zhidaojia', $zhidaojia);

        //国别
        $guobie = $car_info['guobie'] == 0 ? '国产' : '进口';
        Tpl::output('guobie', $guobie);

        //座位数
        $seat_num = $car_info['seat_num'];
        Tpl::output('seat_num', $seat_num);

        Tpl::showpage('order.create_conciliation');
        //----加载新建工单页面end
    }

    /**
     * 查看工单详情
     */
    function show_conciliationOp()
    {
        $id = $_GET['id'];

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.show_conciliation');
    }

    /**
     *   处理工单
     */
    function doing_conciliationOp()
    {
        $id = $_GET['id'];

        //----了结工单
        if ($_POST['query'] == 1) {

            $insert_array = [
                'ocid' => $id,
                'content' => $_POST['content'],
                'follow_depid' => $_POST['follow_depid'],
                'admin_id' => $this->admin_info['id'],
            ];
            $ocrid = Model('hc_order_conciliation_receivce')->insert($insert_array);

            //保存证据
            $files = $_FILES['files'];
            if (!empty($_FILES['files']['name'])) {//上传图片
                for ($i = 0; $i < count($files); $i++) {
                    if ($_FILES["files"]["size"][$i] != 0) {
                        $pi = pathinfo($_FILES['files']['name'][$i]);
                        $file_name = newName() . '.' . $pi['extension'];
                        $file_path = BASE_UPLOAD_PATH . DS . ATTACH_ORDER . DS . $file_name;
                        if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $file_path)) {
                            //将图片同步上传到七牛云上
                            $pic_path = ATTACH_ORDER . '/' . $file_name;
                            Qiniu::make()->upload($file_path, $pic_path);

                            //保存证据
                            $insert_evidence = ['ocid' => $id,
                                'ocrid' => $ocrid,
                                'evidence' => $pi['basename'],
                                'evidence_path' => ATTACH_ORDER . '/' . $file_name,
                                'type' => 2];
                            var_dump($insert_evidence);
                            Model('hc_order_conciliation_evidence')->insert($insert_evidence);
                        }
                    }
                }
            }

            //更新工单状态
            Model('hc_order_conciliation')->where("id=" . $id)->update(['status' => 3]);

            //返回工单详情页面
            return redirect("index.php?act=order&op=show_conciliation&id=" . $id);
        }
        //----了结工单end

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.doing_conciliation');
    }

    /**
     * 发起协商终止工单
     */
    function consult_conciliationOp()
    {
        $id = $_GET['id'];

        //----保存协商终止信息
        if ($_POST['query'] == 1) {
            $insert_array = [
                'ocid' => $id,
                'seller_deposit_from_userjxb' => $_POST['seller_deposit_from_userjxb'],
                'hwache_deposit_from_userjxb' => $_POST['hwache_deposit_from_userjxb'],
                'return_user_available_deposit_from_userjxb' => $_POST['return_user_available_deposit_from_userjxb'],
                'transfer_hwache_service_charge_from_userjxb' => $_POST['transfer_hwache_service_charge_from_userjxb'],
                'transfer_seller_service_charge_from_userjxb' => $_POST['transfer_seller_service_charge_from_userjxb'],
                'apology_money_from_sellerjxb' => $_POST['apology_money_from_sellerjxb'],
                'user_deposit_interest_from_sellerjxb' => $_POST['user_deposit_interest_from_sellerjxb'],
                'return_user_avaiable_from_sellerjxb' => $_POST['return_user_avaiable_from_sellerjxb'],
                'user_damage' => $_POST['user_damage'],
                'hwache_damage' => $_POST['hwache_damage'],
                'admin_id' => $this->admin_info['id'],
            ];
            $consult_id = Model('hc_order_conciliation_consult')->insert($insert_array);

            //创建接单记录
            $conciliation = Model('hc_order_conciliation')->find($id);
            Model('hc_order_conciliation_receivce')->insert([
                'ocid' => $id,
                'content' => '发出协商方案',
                'follow_depid' => $conciliation['follow_depid'],
                'admin_id' => $this->admin_info['id'],
            ]);

            //创建协商确认信息
            $user_content = "转付华车服务费￥" . $_POST['transfer_hwache_service_charge_from_userjxb'] . "，获得客户买车其他损失补偿￥" . $_POST['user_damage'];
            $seller_content = "售方服务费实得￥" . $_POST['transfer_seller_service_charge_from_userjxb'] . "，客户买车其他损失赔偿￥" . $_POST['user_damage'];
            $consult_data = ['consult_id' => $consult_id, 'updated_at' => '0000-00-00 00:00:00'];
            Model('hc_order_conciation_consult_extension')->insert(array_merge($consult_data, ['content' => $user_content, 'user_type' => 1]));
            Model('hc_order_conciation_consult_extension')->insert(array_merge($consult_data, ['content' => $seller_content, 'user_type' => 2]));

            //更新工单状态
            Model('hc_order_conciliation')->where("id=" . $id)->update(['status' => 4, 'type' => 2]);
            $order = Model('hc_order')->find($conciliation['order_id']);
            switch ($order['order_status']){
                case 2:
                    $state = 621;
                    break;
                case 3:
                    $state = 631;
                    break;
                case 4:
                    $state = 641;
                    break;
            }
            $order_datas = [
                'order_state' => $state,
                'updated_at' => date('Y-m-d H:i:s',time())
            ];
            Model('hc_order')->where("id=".$conciliation['order_id'])->update($order_datas);
            //返回工单详情页面
            return redirect("index.php?act=order&op=show_conciliation&id=" . $id);
        }
        //----保存协商终止信息end

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.consult_conciliation');
    }

    function check_consult_conciliationOp()
    {
        $id = $_GET['id'];

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.check_consult_conciliation');
    }

    /**
     * 发起裁判终止订单
     */
    function judge_conciliationOp()
    {
        $id = $_GET['id'];

        //----保存裁判信息
        if ($_POST['query'] == 1) {
            if($_POST['arbitrate_result']==3){
                $insert_array = [
                    'apology_deposit_to_seller' => 299,
                    'apology_deposit_to_hwache' => 200,
                    'return_user_available_deposit_from_userjxb' =>$_POST['return_user_deposit'],
                    'return_user_avaiable_from_sellerjxb' => 499,
                ];
            }else{
                $insert_array = [
                    'apology_deposit_to_seller' => $_POST['apology_deposit_to_seller'],
                    'apology_deposit_to_hwache' => $_POST['apology_deposit_to_hwache'],
                    'seller_deposit_from_userjxb' => $_POST['seller_deposit_from_userjxb'],
                    'hwache_deposit_from_userjxb' => $_POST['hwache_deposit_from_userjxb'],
                    'return_user_available_deposit_from_userjxb' => $_POST['return_user_available_deposit_from_userjxb'],
                    'transfer_hwache_service_charge_from_userjxb' => $_POST['transfer_hwache_service_charge_from_userjxb'],
                    'transfer_seller_service_charge_from_userjxb' => $_POST['transfer_seller_service_charge_from_userjxb'],
                    'apology_money_from_sellerjxb' => $_POST['apology_money_from_sellerjxb'],
                    'user_deposit_interest_from_sellerjxb' => $_POST['user_deposit_interest_from_sellerjxb'],
                    'return_user_avaiable_from_sellerjxb' => $_POST['return_user_avaiable_from_sellerjxb'],
                    'user_damage' => $_POST['user_damage'],
                    'hwache_damage' => $_POST['hwache_damage'],
                ];
            }

            Model('hc_order_conciliation_arbitrate')->insert(array_merge($insert_array,[
                'ocid' => $id,
                'arbitrate_result' => $_POST['arbitrate_result'],
                'admin_id' => $this->admin_info['id']
            ]));

            //创建接单记录
            Model('hc_order_conciliation_receivce')->insert([
                'ocid' => $id,
                'content' => '发起裁判申请',
                'follow_depid' => 4,
                'admin_id' => $this->admin_info['id'],
            ]);

            //更新工单状态
            Model('hc_order_conciliation')->where("id=" . $id)->update(['status' => 5, 'type' => 3]);
            //返回工单详情页面
            return redirect("index.php?act=order&op=show_conciliation&id=" . $id);
        }
        //----保存裁判信息end

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.judge_conciliation');
    }

    /**
     *  审核裁判方案
     */
    function check_judge_conciliationOp()
    {
        $id = $_GET['id'];

        //----保存裁判信息
        if ($_POST['query'] == 1) {
            //创建接单记录
            Model('hc_order_conciliation_receivce')->insert([
                'ocid' => $id,
                'content' => $_POST['check_result'] == 1 ? '同意裁判' : $_POST['content'],
                'follow_depid' => $this->admin_info['dept_id'],
                'admin_id' => $this->admin_info['id'],
            ]);

            if ($_POST['check_result'] == 1) {//审核同意裁决方案
                //todo 执行裁判方案
                //更新工单状态
                $update = Model('hc_order_conciliation')->where("id=" . $id)->update(['status' => 3]);

                $conciliation = Model('hc_order_conciliation')->where("id=" . $id)->find();
                //查出是何工单(担保金违约还是裁判)
                $arbitrate = Model("hc_order_conciliation_arbitrate")->where("ocid=" .$id)->find();
                $order = Model('hc_order')->find($conciliation['order_id']);
                if ($arbitrate['arbitrate_result'] == 3) {
                    $order_state = 394;
                } else {
                    switch ($order['order_status']) {
                        case 2:
                            $order_state = ($arbitrate['arbitrate_result'] == 1) ? 628 : 627;
                            break;
                        case 3:
                            $order_state = ($arbitrate['arbitrate_result'] == 1) ? 638 : 637;
                            break;
                        case 4:
                            $order_state = ($arbitrate['arbitrate_result'] == 1) ? 647 : 644;
                            break;
                  }
                }
                $order_id = $conciliation['order_id'];
                Model('hc_order')->where("id=" . $order_id)->update([
                    'order_state'  => $order_state,
                    'updated_at' => date('Y-m-d H:i:s',time())
                    ]);
                //发送短信
                $member = Model('member')->where("member_id=".$order['seller_id'])->find();
                $sms = new SendSms;
                $sms->sendSms($member['member_mobile'],'78595078',$order['order_sn']);
                $url = API_URL."/jxbTestArbitrate";
                $result = $this->request_by_curl($url,"order_id={$order_id}");
                //$res = json_decode($result,true);

                if($update){
                    //返回工单详情页面
                    return redirect("index.php?act=order&op=show_conciliation&id=" . $id);
                }else{
                    showDialog('操作失败','','error');
                }
            }

            //返回裁判工单修改页面
            return redirect("index.php?act=order&op=edit_judge_conciliation&id=" . $id);
        }
        //----保存裁判信息end

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.check_judge_conciliation');
    }

    /**
     * 修改裁判方案
     */
    function edit_judge_conciliationOp()
    {
        $id = $_GET['id'];

        //----保存裁判信息
        if ($_POST['query'] == 1) {
            $update_array = [
                'arbitrate_result' => $_POST['arbitrate_result'],
                'apology_deposit_to_seller' => 0,
                'apology_deposit_to_hwache' => 0,
                'seller_deposit_from_userjxb' => $_POST['seller_deposit_from_userjxb'],
                'hwache_deposit_from_userjxb' => $_POST['hwache_deposit_from_userjxb'],
                'return_user_available_deposit_from_userjxb' => $_POST['return_user_available_deposit_from_userjxb'],
                'transfer_hwache_service_charge_from_userjxb' => $_POST['transfer_hwache_service_charge_from_userjxb'],
                'transfer_seller_service_charge_from_userjxb' => $_POST['transfer_seller_service_charge_from_userjxb'],
                'apology_money_from_sellerjxb' => $_POST['apology_money_from_sellerjxb'],
                'user_deposit_interest_from_sellerjxb' => $_POST['user_deposit_interest_from_sellerjxb'],
                'return_user_avaiable_from_sellerjxb' => $_POST['return_user_avaiable_from_sellerjxb'],
                'user_damage' => $_POST['user_damage'],
                'hwache_damage' => $_POST['hwache_damage'],
                'admin_id' => $this->admin_info['id'],
            ];

            if($_POST['arbitrate_result']==3){
                $update_array = array_merge($update_array,[
                    'apology_deposit_to_seller' => 299,
                    'apology_deposit_to_hwache' => 200,
                    'return_user_available_deposit_from_userjxb' =>$_POST['return_user_deposit'],
                    'return_user_avaiable_from_sellerjxb' => 499,
                ]);
            }

            Model('hc_order_conciliation_arbitrate')->where("ocid=" . $id)->update($update_array);

            //创建接单记录
            Model('hc_order_conciliation_receivce')->insert([
                'ocid' => $id,
                'content' => '再次发起裁判申请',
                'follow_depid' => 4,
                'admin_id' => $this->admin_info['id'],
            ]);

            //返回工单详情页面
            return redirect("index.php?act=order&op=show_conciliation&id=" . $id);
        }
        //----保存裁判信息end

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.edit_judge_conciliation');
    }

    /**
     * 查看已了结协商终止工单
     */
    function end_consult_conciliationOp()
    {
        $id = $_GET['id'];

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.end_consult_conciliation');
    }

    /**
     * 查看已了结裁判终止工单
     */
    function end_judge_conciliationOp()
    {
        $id = $_GET['id'];

        //工单信息
        $this->_get_conliation_base_info($id);

        Tpl::showpage('order.end_judge_conciliation');
    }

    /**
     * 接单
     */
    function ajax_receive_conciliationOp()
    {
        $id = $_GET['id'];
        $admin_id = $this->admin_info['id'];
        $admin_dept_id = $this->admin_info['dept_id'];

        $conciliation = Model()->table('hc_order_conciliation')->find($id);
        if ($conciliation['status'] == 1 && $admin_dept_id == $conciliation['follow_depid']) {
            $ret = Model('hc_order_conciliation')->where("id=" . $id)->update(['receive_admin_id' => $admin_id, 'status' => 2]);
            if ($ret) {
                json(['error_code' => 0, 'error_msg' => '']);
            }

            json(['error_code' => 1, 'error_msg' => '数据保存失败']);
        }

        json(['error_code' => 1, 'error_msg' => '该工单不能被接单']);
    }

    /**
     * 转单
     */
    function ajax_return_conciliationOp()
    {
        $id = $_POST['id'];
        $admin_id = $this->admin_info['id'];

        $conciliation = Model()->table('hc_order_conciliation')->find($id);
        if ($conciliation['status'] == 2 && $admin_id == $conciliation['receive_admin_id']) {
            $ret = Model('hc_order_conciliation')->where("id=" . $id)->update(['follow_depid' => $_POST['follow_depid'], 'status' => 1, 'receive_admin_id' => 0]);
            if ($ret) {
                json(['error_code' => 0, 'error_msg' => '']);
            }

            json(['error_code' => 1, 'error_msg' => '数据保存失败']);
        }

        json(['error_code' => 1, 'error_msg' => '该工单不能被转单']);
    }

    /**
     * 放弃本次裁决方案
     */
    function ajax_giveup_judge_conciliationOp()
    {
        $id = $_POST['id'];
        $admin_id = $this->admin_info['id'];

        $conciliation = Model()->table('hc_order_conciliation')->find($id);
        if ($conciliation['status'] == 5) {
            // 记录工单操作
            $arbitrate = Model('hc_order_conciliation_arbitrate')->where('ocid=' . $id)->find();
            $arbitrate_result = $arbitrate['arbitrate_result'] == 1 ? '裁判客户违约' : ($arbitrate['arbitrate_result'] == 2 ? '裁判售方违约' : '客户支付买车担保金违约');
            if($arbitrate['arbitrate_result']==3){
                $content = "放弃裁判申请。原申请内容【判定结论：" . $arbitrate_result . "。客户加信宝：诚意金赔偿诚意金赔偿（售方）￥299.00，诚意金赔偿（平台）￥200.00，退还可用余额￥".$arbitrate['return_user_available_deposit_from_userjxb']."。售方加信宝：退还可提现余额￥499.00";
            }else{
                $content = "放弃裁判申请。原申请内容【判定结论：" . $arbitrate_result . "。客户加信宝：买车担保金赔偿（售方）￥" . $arbitrate['seller_deposit_from_userjxb'] . "，买车担保金赔偿（平台）￥" . $arbitrate['hwache_deposit_from_userjxb'] . "，退还可用余额￥" . $arbitrate['return_user_available_deposit_from_userjxb'] . "，转付华车服务费￥" . $arbitrate['transfer_hwache_service_charge_from_userjxb'] . "（含售方服务费￥" . $arbitrate['transfer_seller_service_charge_from_userjxb'] . "）。售方加信宝：歉意金N赔偿￥" . $arbitrate['apology_money_from_sellerjxb'] . "，客户买车担保金利息N赔偿￥" . $arbitrate['user_deposit_interest_from_sellerjxb'] . "，退还可提现余额￥" . $arbitrate['return_user_avaiable_from_sellerjxb'] . "。售方可提现余额：客户买车其他损失￥" . $arbitrate['user_damage'] . "，华车平台损失赔偿￥" . $arbitrate['hwache_damage'] . "】";
            }

            $ret = Model('hc_order_conciliation_receivce')->insert([
                'ocid' => $id,
                'content' => $content,
                'follow_depid' => 3,
                'admin_id' => $admin_id,
            ]);

            //更新工单状态为已了结
            Model('hc_order_conciliation')->where("id=" . $id)->update(['status' => 3]);

            if ($ret) {
                json(['error_code' => 0, 'error_msg' => '']);
            }

            json(['error_code' => 1, 'error_msg' => '数据保存失败']);
        }

        json(['error_code' => 1, 'error_msg' => '该工单不能被放弃']);
    }

    /**
     * 放弃本次协商方案
     */
    function ajax_giveup_consult_conciliationOp()
    {
        $id = $_GET['id'];
        $admin_id = $this->admin_info['id'];

        $conciliation = Model()->table('hc_order_conciliation')->find($id);
        if ($conciliation['status'] == 4) {
            // 记录工单操作
            $consult = Model('hc_order_conciliation_consult')->where('ocid=' . $id)->find();
            $content = "协商终止失败。方案【客户加信宝：买车担保金赔偿（售方）￥" . $consult['seller_deposit_from_userjxb'] . "，买车担保金赔偿（平台）￥" . $consult['hwache_deposit_from_userjxb'] . "，退还可用余额￥" . $consult['return_user_available_deposit_from_userjxb'] . "，转付华车服务费￥" . $consult['transfer_hwache_service_charge_from_userjxb'] . "（含售方服务费￥" . $consult['transfer_seller_service_charge_from_userjxb'] . "）。售方加信宝：歉意金N赔偿￥" . $consult['apology_money_from_sellerjxb'] . "，客户买车担保金利息N赔偿￥" . $consult['user_deposit_interest_from_sellerjxb'] . "，退还可提现余额￥" . $consult['return_user_avaiable_from_sellerjxb'] . "。售方可提现余额：客户买车其他损失￥" . $consult['user_damage'] . "，华车平台损失赔偿￥" . $consult['hwache_damage'] . "】";
            $ret = Model('hc_order_conciliation_receivce')->insert([
                'ocid' => $id,
                'content' => $content,
                'follow_depid' => 3,
                'admin_id' => $admin_id,
            ]);

            //更新工单状态为已了结
            Model('hc_order_conciliation')->where("id=" . $id)->update(['status' => 3]);

            if ($ret) {
                json(['error_code' => 0, 'error_msg' => '']);
            }

            json(['error_code' => 1, 'error_msg' => '数据保存失败']);
        }

        json(['error_code' => 1, 'error_msg' => '该工单不能被放弃']);
    }

    /**
     * 导出
     *
     */
    public function export_conciliationOp()
    {

    }

    function change_appoint_car_timeOp()
    {
        $order_id = $_GET['order_id'];

        $order = Model('hc_order')->find($order_id);
        if ($order['order_state'] != 403) {
            showMessage("倒计时不能停止", "", "html", "error");
        }
        if (isset($_POST['query']) && $_POST['query'] == 1) {
            $data = [
                'system_data' => $_POST['system_data'],
                'system_day' => $_POST['system_day'] ?: 1,
                'system_out_price' => $_POST['system_out_price'],
                'system_admin_id' => $this->admin_info['id'],
                'system_operate_at' => date("Y-m-d H:i:s")
            ];

            $ret = Model('hc_order_appoint_car')->where("order_id=" . $order_id)->update($data);
            Model('hc_order')->where("id=".$order_id)->update(['order_state'=>402]);
            //短信通知
            $member = Model('member')->where("member_id=".$order['seller_id'])->find();
            Model("hc_order_info")->where("id=".$order_id)->update(
                ['car_jiaoche_at' => $data['system_data'],'car_jiaoche_day'=>$data['system_day']]);
            $time = date('Y年m月d日',strtotime($data['system_data']));
            $comment = "{$order['order_sn']}&time={$time}&money={$data['system_out_price']}";
            $sms = new SendSms;
            $sms->sendSms($member['member_mobile'],'78595077',$comment);
            if ($ret) {
                showMessage('数据已经更新');
            } else {
                showMessage("数据更新失败", "", "html", "error");
            }
        }
        Tpl::showpage('order.change_appoint_car');
    }

    /**
     * 查询工单基本信息
     */
    function _get_conliation_base_info($id)
    {
        //工单
        $conciliation = Model('hc_order_conciliation')->find($id);

        //工单提交人
        $conciliation_submitter = Model()->table('admin')->find($conciliation['admin_id']);

        //工单接单人
        $conciliation_receiver = Model()->table('admin')->find($conciliation['receive_admin_id']);

        //工单证据
        $conciliation_evidence = Model('hc_order_conciliation_evidence')->where("ocid=" . $id . ' and type=1')->select();

        //部门信息
        $departments = Model('hc_admin_dept')->where('is_del=0')->select();
        foreach ($departments as $dep) {
            $dep_id = $dep['id'];
            $deps[$dep_id] = $dep['name'];
        }

        //协商确认状态
        $conciliation_status_list = [1 => '待接单', 2 => '正在接单', 3 => '已了结', 4 => '协商确认中', '5' => '平台裁决中'];

        //协商处理记录
        $conciliation_receive = Model()->table('hc_order_conciliation_receivce,admin')
            ->field('hc_order_conciliation_receivce.*,admin.admin_name,admin.dept_id')
            ->on('hc_order_conciliation_receivce.admin_id=admin.admin_id')
            ->where("ocid=" . $id)->select();
        if ($conciliation_receive) {
            foreach ($conciliation_receive as $re) {
                $ids[] = $re['id'];
            }

            //查询协商证据
            $evidences = Model('hc_order_conciliation_evidence')->where('ocrid in (' . implode(',', $ids) . ') and type=2')->select();
            if ($evidences) {
                foreach ($evidences as $e) {
                    $ocrid = $e['ocrid'];
                    $evidences_temp[$ocrid][] = [
                        'evidence' => $e['evidence'],
                        'evidence_path' => $e['evidence_path'],
                    ];
                }
            }

            foreach ($conciliation_receive as &$r) {
                $r['evidence'] = $evidences_temp[$r['id']];
            }
        }

        //订单信息
        $order = Model('hc_order')->find($conciliation['order_id']);

        //售方账户
        $seller_account = Model()->table('hc_daili_account')->where('d_id=' . $order['seller_id'])->find();

        //报价信息
        $baojia_price = Model('hc_price')->find($order['bj_id']);

        $data = [
            'conciliation' => $conciliation,
            'conciliation_submitter' => $conciliation_submitter,
            'conciliation_receiver' => $conciliation_receiver,
            'conciliation_evidence' => $conciliation_evidence,
            'departments' => $deps,
            'conciliation_status_list' => $conciliation_status_list,
            'conciliation_receive' => $conciliation_receive,
            'admin_info' => $this->admin_info,
            'order' => $order,
            'seller_account' => $seller_account,
            'baojia_price' => $baojia_price,
        ];

        //协商终止工单
        if ($conciliation['type'] == 2) {
            $consult = Model('hc_order_conciliation_consult')->where('ocid=' . $id)->find();
            Tpl::output('consult', $consult);

            $user_consult = Model('hc_order_conciation_consult_extension')->where('user_type=1 and consult_id=' . $consult['id'])->find();
            Tpl::output('user_consult', $user_consult);

            $seller_consult = Model('hc_order_conciation_consult_extension')->where('user_type=2 and consult_id=' . $consult['id'])->find();
            Tpl::output('seller_consult', $seller_consult);
        }

        //裁判终止订单
        if ($conciliation['type'] == 3) {
            $arbitrate = Model('hc_order_conciliation_arbitrate')->where('ocid=' . $id)->find();
            Tpl::output('arbitrate', $arbitrate);
        }

        foreach ($data as $k => $v) {
            Tpl::output($k, $v);
        }
    }

    /**
     * 导出代金券
     */
    public function exportOrderOp()
    {
        $result = $this->indexOp(true);
        $tmp = array();
        foreach($result as $k => $v){
            $tmp[$k][] = $v['order_sn'];
            $tmp[$k][] = $v['created_at'];
            $tmp[$k][] = $v['phone'];
            $tmp[$k][] = $v['d_name'];
            $tmp[$k][] = $v['user_progress'].'>'.$v['remark'];
            $tmp[$k][] = $v['seller_progress'].'>'.$v['remark'];
        }
        $titleArray = ['订单号','订单时间','客户手机','售方用户名','客户订单状态','售方订单状态'];
        $this->createExcel($tmp,$titleArray,'订单-订单列表');
    }

    function request_by_curl($url,$post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        //    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //终止交车倒计时
    public function ajax_stop_appoint_car_timeOp()
    {
        $id = intval($_POST['order_id']);
        $data['reason'] = trim($_POST['reason']);
        $data['status'] = 0;
        $data['admin_id'] = $this->admin_info;
        $model = Model('hc_order');
        $result = $model->find($id);
        $users = Model('users')->find($result['user_id']);
        if ($result['order_status'] == 3) {
            $model->where('id=' . $id)->update(['order_state'=>309]);
            Model('hc_order_date')->where("order_id=" . $id)->update($data);
            //插入短信
            $sms = new SendSms;
            $sms->sendSms($users['phone'],'78530074',$result['order_sn']);
        } else {
            json(['error_code'=>1,'error_msg'=>'该订单不存在']);
        }
    }

    //订单总详情
    public function order_detailOp()
    {
        $id = intval($_GET['order_id']);
        $model = Model();
        $on = 'hc_order.bj_id=hg_baojia.bj_id,hc_order.bj_id=hc_price.id,hg_baojia_expand_info.bj_id=hc_order.bj_id';
        //订单报价表
        $order
            = $model->table('hc_order,hg_baojia,hc_price,hg_baojia_expand_info')
            ->on($on)->where('hc_order.id=' . $id)->find();
        Tpl::output('order', $order);
        //订单属性与附表,评价,交车时间表
        $order_info = Model()
            ->table('hc_order_info,hc_order_attr,hc_cart_evaluate,hc_order_appoint_car')
            ->on('hc_order_info.id=hc_order_attr.order_id,hc_order_info.id=hc_cart_evaluate.order_id,hc_order_appoint_car.order_id=hc_order_info.id')
            ->where('hc_order_info.id=' . $id)->find();
        Tpl::output('info', $order_info);
        //交车范围
        $areas = Model('hg_baojia')->getScopeByBaojiaId($order['bj_id']);
        Tpl::output('areas', $areas);
        //地区
        $area = Model('area')->find($order['shangpai_area']);
        Tpl::output('area', $area);
        //交车日志
        $logs = Model('hg_cart_log')->where('order_id=' . $id)->select();
        Tpl::output('logs', $logs);
        //经销商信息
        $dealer = Model()->table('hg_dealer')->find($order['dealer_id']);
        Tpl::output('dealer', $dealer);
        //用户信息
        $users = $model->table('users,user_extension')
            ->on('users.id=user_extension.user_id')->where('users.id='
            . $order['user_id'])->find();
        Tpl::output('users', $users);
        //身份类别
        $type_cond['identity_id'] = $order_info['identity_type'];
        if ($order_info['identity_type'] == 1) {
            $type_cond['identity_type'] = 0;
        } elseif ($order_info['identity_type'] == 2) {
            $type_cond['identity_type'] = 1;
        }
        $user_type = Model('hc_dealer_identity')->where($type_cond)->find();
        Tpl::output('user_type', $user_type);
        //售方信息
        $dealers = Model('member')->find($order['seller_id']);
        TPl::output('dealers', $dealers);
        //服务专员
        $waiter = Model('hg_waiter')->find($order_info['waiter_id']);
        Tpl::output('waiter', $waiter);
        //交车文件随车工具
        $tool_file = Model('hc_vehicle_tools_files')
            ->where('brand_id=' . $order['brand_id'])
            ->select();
        $suiche = [];
        if ($tool_file) {
            foreach ($tool_file as $value) {
                if ($value['type'] == 1) {
                    $suiche['files'][] = $value['title'];
                } else {
                    $suiche['tools'][] = $value['title'];
                }
            }
        }
        Tpl::output('suiche', $suiche);
        //指导价 车辆信息
        $car_info = Model()->table('goods_class,hg_car_info')
            ->on('goods_class.gc_id=hg_car_info.gc_id')->where([
                'goods_class.gc_id' => $order['brand_id'],
                'hg_car_info.name'  => 'zhidaojia'
            ])->field(['value,vehicle_model,detail_img'])->find();
        Tpl::output('car_info', $car_info);
        //原厂选装与免费礼品
        $repos = Model('hg_editinfo')->where('order_sn=' . $order['order_sn'])
            ->order('createat desc')->find();
        if ( ! empty($repos['xzj'])) {
            $edit_info['xzj'] = unserialize($repos['xzj']);
        } else {
            $edit_info['xzj'] = Model()
                ->table('hg_baojia_xzj,hg_xzj_list,hg_xzj_daili')
                ->on('hg_baojia_xzj.xzj_id=hg_xzj_list.id,hg_xzj_daili.id=hg_baojia_xzj.m_id')
                ->where(['hg_baojia_xzj.bj_id' => $order['bj_id']])
                ->field(['hg_xzj_list.xzj_title,hg_xzj_list.xzj_model,hg_baojia_xzj.num,hg_xzj_list.xzj_guide_price'])
                ->select();
        }
        if ( ! empty($repos['zengpin'])) {
            $edit_info['zp'] = unserialize($repos['zengpin']);
        } else {
            $edit_info['zp'] = Model('hg_baojia_zengpin')->where(['bj_id'=>$order['bj_id']])->select();
        }
        TPl::output('edit_info', $edit_info);
        //其他收费
        $other_price = Model('hg_baojia_other_price')->where('bj_id='
            . $order['bj_id'])->select();
        Tpl::output('other_price', $other_price);
        //已购原厂与非原厂
        $order_xzjs = Model('hc_order_xzj')->where('order_id=' . $id)
            ->order('created_at desc')->select();
        $arr_xzjs = ['yc' => [], 'fyc' => []];
        if ( ! empty($order_xzjs)) {
            foreach ($order_xzjs as $orderXzj) {
                if ($orderXzj['xzj_type'] == 1) {
                    $arr_xzjs['yc'][] = $orderXzj;
                } else {
                    $arr_xzjs['fyc'][] = $orderXzj;
                }
            }
        }
        Tpl::output('arr_xzjs', $arr_xzjs);
        //订单状态显示
        $order_status = Model('hc_order_progress_status')->where('sub_status='
            . $order['order_state'])->find();
        Tpl::output('order_status',$order_status);
        //交车时间段
        $jioache = Model('hc_order_date')->where("order_id=" . $id)->find();
        Tpl::output('jiaoche',$jioache);
        //特需文件
        $file_part = Model('hg_editinfo')->where('order_sn=' . $order['order_sn'])
            ->field(['shangpai_file'])->find();
        $files = (!empty($file_part['shangpai_file'])) ? unserialize($file_part['shangpai_file']) : '';
        Tpl::output('files',$files);
        //todo 售方修改
        //精品协商
        $consults = Model()->table('hc_order_xzj_edit,hg_xzj_list,hg_xzj_daili')->on('hc_order_xzj_edit.xzj_id=hg_xzj_list.id,hg_xzj_list.id=hg_xzj_daili.xzj_list_id')->where('hc_order_xzj_edit.order_id='.$id)->field(['hg_xzj_list.*,hg_xzj_daili.xzj_fee,hc_order_xzj_edit.*'])->select();
        Tpl::output('consults',$consults);
        Tpl::showpage('order.total_order_detail');

    }
}