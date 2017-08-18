<?php
/**
 * 经销商代理订单管理
 */

defined('InHG') or exit('Access Invalid!');

class daili_orderControl extends BaseSellerControl {

    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
        Language::read('order');
    }

    /**
     * 订单列表
     *
     */
    public function indexOp()
    {
        // 查询所有订单
        $model_cart = Model('hg_cart');
        // 过滤掉已下单，未付诚意金订单
        $map = array(
            'seller_id' => $_SESSION['member_id'],
            'cart_status' => array('gt', 1),
        );
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? ($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? ($_GET['query_end_date']): null;
        if ($start_unixtime && $end_unixtime) {
            $map['created_at']=array('between',$start_unixtime,$end_unixtime);
        }elseif ($start_unixtime) {
            $map['created_at']=array('gt',$start_unixtime);
        }elseif ($end_unixtime) {
            $map['created_at']=array('lt',$end_unixtime);
        }
        if ($_GET['order_sn']) {
            $map['order_num']=array('eq',$_GET['order_sn']);
        }

        $orderList = $model_cart->getCartList(
            $map,
            C('pagesize'),
            'hg_cart.*,hg_cart_user.*,member.member_name,hg_baojia.*,hg_baojia_price.*',
            'hg_cart.created_at DESC'
        );
        
        if (!empty($orderList)) {
            // 获取车型属性
            $carFields = Model('hg_fields')->getCarFields();

            // 订单状态，从配置文件中读取
            $hg_order_status = array_flip(C('hg_order'));

            foreach ($orderList as $k => $v) {
                // 车型属性
                $carInfo = Model('hg_car_info')->getCarInfo($v['car_id']);

                // 修改订单状态为配置文件格式
                $orderList[$k]['cart_status'] = $hg_order_status[$v['cart_status']];
                
                if($v['cart_status'] <=5 || $v['cart_status']==1000){
                	$orderList[$k]['cart_sub_status'] = $hg_order_status[$v['cart_sub_status']];
                }else{
                	//echo $v['cart_status'],'#';
                	$orderList[$k]['cart_sub_status'] = $hg_order_status[$v['end_pdi_status']];
                }
				
                // 买家信息
                $buy_info = Model('member')
                    ->where(array('member_id'=>$v['buy_id']))
                    ->field('member_name')
                    ->find();
                $orderList[$k]['buyer_name'] = $buy_info['member_name'];

                // 具体报价车型详细属性
                $bjFields = Model('hg_baojia_fields')->getBaojiaFields($v['bj_id']);
                foreach ($carInfo as $_k => $_v) {
                    if (isset($carFields[$_k])) {
                        $orderList[$k][$_k] = $carFields[$_k][$bjFields[$_k]];
                    } else if (isset($bjFields[$_k]) && is_array($_v)) {
                        $orderList[$k][$_k] = $_v[$bjFields[$_k]];
                    } else {
                        $orderList[$k][$_k] = $_v;
                    }
                }
            }
        }
       // print_r($orderList);exit;
        Tpl::output('orderList', $orderList);

        if (!isset($_GET['state_type'])) {
            $_GET['state_type'] = 'daili_order';
        }

        // 订单分页
        Tpl::output('show_page',$model_cart->showpage());

        // 子分类(例如：全部订单，已支付诚意金订单，支付定金订单等等...)
        self::profile_menu('list',$_GET['state_type']);

        Tpl::showpage('daili_order.index');
    }

    /**
     * 更新订单为确认该订单
     */
    public function confirmOp()
    {
        $order_id = $_GET['order_id'];
        // 更新订单(满足条件：1、订单号；2、卖家ID；3、订单状态)
        $model_cart = Model('hg_cart');
        $r = $model_cart->where(array(
                'order_num' => $order_id,
                'seller_id' => $_SESSION['member_id'],
                'cart_status'   => C('hg_order.order_earnest'),
                'cart_sub_status'   => C('hg_order.order_earnest_not_confirm'),
            ))->update(array(
                'cart_status' => C('hg_order.order_doposit'),
                'cart_sub_status' => C('hg_order.order_doposit_wait_pay'),
            ));

        if ($r) {
            echo json_encode(array(
                'code'  => 1,
                'msg'   => '订单确认成功',
            ));
        } else {
            echo json_encode(array(
                'code'  => 0,
                'msg'   => '订单确认失败',
            ));
        }
    }

    /**
     * 确认收到担保金
     */
    public function doposit_confirmOp()
    {
        $order_id = $_GET['order_id'];
        // 更新订单(满足条件：1、订单号；2、卖家ID；3、订单状态)
        $model_cart = Model('hg_cart');
        $r = $model_cart->where(array(
                'order_num' => $order_id,
                'seller_id' => $_SESSION['member_id'],
                'cart_status'   => C('hg_order.order_jiaoche'),
                'cart_sub_status'   => C('hg_order.order_doposit_not_confirm'),
            ))->update(array(
                'cart_sub_status' => C('hg_order.order_jiaoche_wait'),
            ));

        if ($r) {
            // 添加订单记录日志
            $cart_id = $model_cart->getfby_order_num($order_id, 'id');
            Model('hg_cart_log')->insert(array(
                'cart_id'   => $cart_id,
                'user_id'   => $_SESSION['member_id'],
                'cart_status'=> C('hg_order.order_doposit_confirm'),
                'action'    => 'daili_order/doposit_confirmOp',
                'msg'       => L('order')['order_deposit_confirm'],
            ));

            echo json_encode(array(
                'code'  => 1,
                'msg'   => '订单确认成功',
            ));
        } else {
            echo json_encode(array(
                'code'  => 0,
                'msg'   => '订单确认失败',
            ));
        }
    }

    /**
     * 确认提车信息
     */
    public function jiaoche_confirmOp()
    {
        $order_id = $_GET['order_id'];
        // 更新订单(满足条件：1、订单号；2、卖家ID；3、订单状态)
        $model_cart = Model('hg_cart');
        $r = $model_cart->where(array(
            'order_num' => $order_id,
            'seller_id' => $_SESSION['member_id'],
            'cart_status'   => C('hg_order.order_jiaoche'),
            'cart_sub_status'   => C('hg_order.order_jiaoche_confirm'),
        ))->update(array(
            'cart_status'   => C('hg_order.order_tiche'),
            'cart_sub_status' => C('hg_order.order_tiche_info_check'),
        ));

        if ($r) {
            // 添加订单记录日志
            $cart_id = $model_cart->getfby_order_num($order_id, 'id');
            Model('hg_cart_log')->insert(array(
                'cart_id'   => $cart_id,
                'user_id'   => $_SESSION['member_id'],
                'cart_status'=> C('hg_order.order_tiche'),
                'action'    => 'daili_order/jiaoche_confirmOp',
                'msg'       => L('order')['order_tiche_info_check'],
            ));

            echo json_encode(array(
                'code'  => 1,
                'msg'   => '订单确认成功',
            ));
        } else {
            echo json_encode(array(
                'code'  => 0,
                'msg'   => '订单确认失败',
            ));
        }
    }

    /**
     * 卖家订单详情
     *
     */
    public function show_orderOp()
    {
        $order_num = $_GET['order_id'];
        if ($order_num <= 0) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $model_order = Model('hg_cart');
        $condition = array();
        $condition['order_num'] = $order_num;
        //$condition['store_id'] = $_SESSION['store_id'];

        $order_info = $model_order->getOrderInfo($condition,array('hg_cart_user','hg_baojia'));
        if (empty($order_info)) {
            showMessage(Language::get('store_order_none_exist'),'','html','error');
        }
        // 取得订单状态文字说明
        $order_status=$model_order->getOrderStatus($order_info['cart_status']);
        $order_sub_status=$model_order->getOrderStatus($order_info['cart_sub_status']);
        Tpl::output('order_info',$order_info);
        Tpl::output('order_status',$order_status);
        Tpl::output('order_sub_status',$order_sub_status);

        Tpl::showpage('store_order.show');
    }

    /**
     * 卖家订单状态操作
     *
     */
    public function change_stateOp()
    {
        $state_type  = $_GET['state_type'];
        $order_id    = intval($_GET['order_id']);

        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info    = $model_order->getOrderInfo($condition);
        Tpl::output('order_info',$order_info);
        try {

            $model_order->beginTransaction();

            if ($state_type == 'order_cancel') {
                $this->_change_state_order_cancel($order_info);
                $message = Language::get('store_order_cancel_success');
            } elseif ($state_type == 'modify_price') {
                $this->_change_state_modify_price($order_info);
                $message = Language::get('store_order_edit_ship_success');
            }

            $model_order->commit();
            showDialog($message,'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');

        } catch (Exception $e) {
            $model_order->rollback();
            showDialog($e->getMessage(),'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        }
    }

    /**
     * 取消订单
     * @param unknown $order_info
     * @throws Exception
     */
    private function _change_state_order_cancel($order_info)
    {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        if(chksubmit()) {
            $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
            if (!$if_allow) {
                throw new Exception(L('invalid_request'));
            }
            $goods_list = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
            $model_goods = Model('goods');
            if(is_array($goods_list) and !empty($goods_list)) {
                foreach ($goods_list as $goods) {
                    $data = array();
                    $data['goods_storage'] = array('exp','goods_storage+'.$goods['goods_num']);
                    $data['goods_salenum'] = array('exp','goods_salenum-'.$goods['goods_num']);
                    $update = $model_goods->editGoods($data,array('goods_id'=>$goods['goods_id']));
                    if (!$update) {
                        throw new Exception(L('nc_common_save_fail'));
                    }
                }
            }

            //解冻预存款
            $pd_amount = floatval($order_info['pd_amount']);
            if ($pd_amount > 0) {
                $model_pd = Model('predeposit');
                $data_pd = array();
                $data_pd['member_id'] = $order_info['buyer_id'];
                $data_pd['member_name'] = $order_info['buyer_name'];
                $data_pd['amount'] = $pd_amount;
                $data_pd['order_sn'] = $order_info['order_sn'];
                $model_pd->changePd('order_cancel',$data_pd);
            }

            //更新订单信息
            $data = array('order_state'=>ORDER_STATE_CANCEL);
            $update = $model_order->editOrder($data,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception(L('nc_common_save_fail'));
            }

            //记录订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = 'seller';
            $data['log_user'] = $_SESSION['member_name'];
            $data['log_msg'] = L('order_log_cancel');
            $extend_msg = $_POST['state_info1'] != '' ? $_POST['state_info1'] : $_POST['state_info'];
            if ($extend_msg) {
                $data['log_msg'] .= ' ( '.$extend_msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_CANCEL;
            $model_order->addOrderLog($data);
        } else {
            Tpl::output('order_id',$order_id);
            Tpl::showpage('store_order.cancel','null_layout');
            exit();
        }
    }

    /**
     * 修改运费
     * @param unknown $order_info
     * @throws Exception
     */
    private function _change_state_modify_price($order_info)
    {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        if(chksubmit()) {
            $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
            if (!$if_allow) {
                throw new Exception(L('invalid_request'));
            }
            $data = array();
            $data['shipping_fee'] = abs(floatval($_POST['shipping_fee']));
            $data['order_amount'] = array('exp','goods_amount+'.$data['shipping_fee']);
            $update = $model_order->editOrder($data,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception(L('nc_common_save_fail'));
            }
            //记录订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = 'seller';
            $data['log_user'] = $_SESSION['member_name'];
            $data['log_msg'] = L('order_log_edit_ship');
            $model_order->addOrderLog($data);
        } else {
            Tpl::output('order_id',$order_id);
            Tpl::showpage('store_order.edit_price','null_layout');
            exit();
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     */
    private function profile_menu($menu_type='',$menu_key='')
    {
        Language::read('order');
        switch ($menu_type) {
            case 'list':
            $menu_array = array(
            array('menu_key'=>'daili_order',        'menu_name'=>Language::get('all_order'),    'menu_url'=>'index.php?act=daili_order'),
//            array('menu_key'=>'state_new',            'menu_name'=>Language::get('nc_member_path_wait_pay'),    'menu_url'=>'index.php?act=store_order&op=index&state_type=state_new'),
//            array('menu_key'=>'state_pay',            'menu_name'=>Language::get('nc_member_path_wait_send'),    'menu_url'=>'index.php?act=store_order&op=store_order&state_type=state_pay'),
//            array('menu_key'=>'state_send',            'menu_name'=>Language::get('nc_member_path_sent'),        'menu_url'=>'index.php?act=store_order&op=index&state_type=state_send'),
//            array('menu_key'=>'state_success',        'menu_name'=>Language::get('nc_member_path_finished'),    'menu_url'=>'index.php?act=store_order&op=index&state_type=state_success'),
//            array('menu_key'=>'state_cancel',        'menu_name'=>Language::get('nc_member_path_canceled'),    'menu_url'=>'index.php?act=store_order&op=index&state_type=state_cancel'),
            );
            break;
            case 'show':
            $menu_array = array(
            array('menu_key'=>'all_order',            'menu_name'=>Language::get('nc_member_path_all_order'),    'menu_url'=>'index.php?act=store_order&op=index'),
            array('menu_key'=>'show_order',                'menu_name'=>Language::get('nc_member_path_show_order'),    'menu_url'=>'')
            );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
