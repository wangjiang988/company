<?php
/**
 * 车型订单控制器
 *
 * @author Andy php360@qq.com
 * @copyright 苏州华车网络科技有限公司
 */

defined('InHG') or exit('Access Invalid!');

class m_orderControl extends BaseMemberControl {

    public function __construct() {
        parent::__construct();
        Language::read('member_member_index');
        Language::read('order');
    }

    /**
     * 买家我的订单，以总订单pay_sn来分组显示
     *
     */
    public function indexOp() {
        // 会员信息
        $this->get_member_info();

        // 订单状态分类
        self::profile_menu('member_order');

        // 查询所有订单
        $model_cart = Model('hg_cart');
        $map = array(
            'buy_id' => $_SESSION['member_id'],
        );
        $orderList = $model_cart->getCartList(
            $map,
            C('pagesize'),
            'hg_cart.*,hg_cart_user.*,member.member_name,hg_baojia.*,hg_baojia_price.*',
            'hg_cart.created_at DESC'
        );
        if (!empty($orderList)) {
            // 获取车型属性
            $carFields = Model('hg_fields')->getCarFields();

            // 订单状态
            $hg_order = array_flip(C('hg_order'));

            foreach ($orderList as $k => $v) {
                // 车型属性
                $carInfo = Model('hg_car_info')->getCarInfo($v['car_id']);

                // 修改订单状态为配置文件格式
                $orderList[$k]['cart_status'] = $hg_order[$v['cart_status']];
                $orderList[$k]['cart_sub_status'] = $hg_order[$v['cart_sub_status']];

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
        }//var_dump($orderList);exit;

        // 订单列表模板变量
        Tpl::output('orderList', $orderList);
        Tpl::output('show_page', $model_cart->showpage());

        // 默认选中顶部 “订单” 菜单
        Tpl::output('header_menu_sign','order');

        // 加载模板
        Tpl::showpage('m_order.index');
    }

    /**
     * 按成提车
     */
    public function tiche_confirmOp()
    {var_dump($_SESSION);exit;
        $order_id = $_GET['order_id'];
        // 更新订单(满足条件：1、订单号；2、卖家ID；3、订单状态)
        $model_cart = Model('hg_cart');
        $r = $model_cart->where(array(
            'order_num' => $order_id,
            'buy_id'    => $_SESSION['member_id'],
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
     * 用户中心右边，小导航
     *
     * @param string $menu_key 当前导航的menu_key
     * @internal param string $menu_type 导航类型
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            // 订单列表
            array(
                'menu_key'  => 'member_order',
                'menu_name' => Language::get('nc_member_path_order_list'),
                'menu_url'  => 'index.php?act=m_order'
            ),
            // 退款申请
            array(
                'menu_key'  => 'buyer_refund',
                'menu_name' => Language::get('nc_member_path_buyer_refund'),
                'menu_url'  => 'index.php?act=m_refund'
            ),
            // 退货申请
            array(
                'menu_key'  => 'buyer_return',
                'menu_name' => Language::get('nc_member_path_buyer_return'),
                'menu_url'  => 'index.php?act=m_return'
            )
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
