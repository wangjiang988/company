<?php
/**
 * 订单模型
 */

defined('InHG') or exit('Access Invalid!');

class hg_cartModel extends Model{

    public function __construct(){
        parent::__construct('hg_cart');
    }

    /**
     * @param $condition
     * @param null $page
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return mixed
     */
    public function getCartList($condition, $page = null, $field = '*', $order = '', $limit = '')
    {
        return $this->table('hg_cart,hg_cart_user,member,hg_baojia,hg_baojia_price')
            ->field($field)
            ->join('left,left,left,left')
            ->on('hg_cart.id=hg_cart_user.id,hg_cart.seller_id=member.member_id,hg_cart.bj_id=hg_baojia.bj_id,hg_cart.bj_id=hg_baojia_price.bj_id')
            ->where($condition)
            ->order($order)
            ->limit($limit)
            ->page($page)
            ->select();
    }

    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderCount($condition) {
        return $this->table('hg_cart,hg_cart_user,member')->where($condition)->count();
    }

    /**
     * 取单条订单信息
     *
     * @param array $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @param string $fields
     * @param string $order
     * @param string $group
     * @return unknown
     */
    public function getOrderInfo($condition = array(), $extend = array(), $fields = '*', $order = '',$group = '') {
        $order_info = $this->table('hg_cart')->field($fields)->where($condition)->group($group)->order($order)->find();
        if (empty($order_info)) {
            return array();
        }
        //购买者信息
        if (in_array('hg_cart_user',$extend)) {
            $user_info = Model()->table('hg_cart_user')->where(array('id'=>$order_info['id']))->find();
            $order_info['username'] = $user_info['name'];
            $order_info['phone'] = $user_info['phone'];
        }

        //追加返回店铺信息
        if (in_array('hg_daili_dealer',$extend)) {
            $daili_dealer=Model()->table('hg_daili_dealer')->where(array('d_id'=>$order_info['seller_id']))->find();
            $order_info['store_name'] = $daili_dealer['dl_store_name'];
            $order_info['dl_jc_place'] = $daili_dealer['dl_jc_place'];
            $order_info['dl_baoxian'] = getIs($daili_dealer['dl_baoxian']);
            $order_info['dl_shangpai'] = getIs($daili_dealer['dl_shangpai']);
            $order_info['dl_linpai'] = getIs($daili_dealer['dl_linpai']);
            $order_info['dl_jc_fee'] = getIs($daili_dealer['dl_jc_fee']);
            $order_info['dl_jc_free'] = getIs($daili_dealer['dl_jc_free']);
            $order_info['dl_xg_shangpai'] = getIs($daili_dealer['dl_xg_shangpai']);
            $order_info['dl_butie'] = getIs($daili_dealer['dl_butie']);
            $order_info['lianxifangshi'] = unserialize($daili_dealer['dl_jc_data']);
        }

        //报价信息
        if (in_array('hg_baojia',$extend)) {
            $baojia=Model()->table('hg_baojia')->where(array('bj_id'=>$order_info['bj_id']))->find();
            $order_info['bj_id']=$baojia['bj_id'];
            $order_info['dealer_name']=$baojia['dealer_name'];
            $order_info['bj_serial']=$baojia['bj_serial'];
            $order_info['brand_id']=$baojia['brand_id'];
            $order_info['gc_name']=$baojia['gc_name'];
            $order_info['bj_num']=$baojia['bj_num'];
            $order_info['bj_producetime']=$baojia['bj_producetime'];
            $order_info['bj_jc_period']=$baojia['bj_jc_period'];
            $order_info['bj_licheng']=$baojia['bj_licheng'];
            $order_info['bj_butie']=$baojia['bj_butie'];
            $order_info['bj_start_time']=date('Y-m-d H:i:s',$baojia['bj_start_time']);
            $order_info['bj_end_time']=date('Y-m-d H:i:s',$baojia['bj_end_time']);
            $order_info['bj_pay_type']=getFukuan($baojia['bj_pay_type']);
            $order_info['bj_nationwide']=getIs($baojia['bj_nationwide']);
            $order_info['bj_baoxian']=getIs($baojia['bj_baoxian']);
            // 车型基本信息
            $car=Model()->table('goods_class')->where(array('gc_id'=>$baojia['brand_id']))->find();
            $order_info['bj_chuchang_time']=$car['chuchang_time'];
            $order_info['bj_official_url']=$car['official_url'];
            // 报价车型扩展信息
            $baojia_more=Model('hg_baojia_fields')->getBaojiaFields($order_info['bj_id']);

            // 车型的扩展信息，颜色等
            $car_more=Model('hg_car_info')->getCarInfo($baojia['brand_id']);

            $order_info['body_color']=$car_more['body_color'][$baojia_more['body_color']];
            $order_info['interior_color']=$car_more['interior_color'][$baojia_more['interior_color']];
            $order_info['zhidaojia']=$car_more['zhidaojia'];
            $order_info['seat_num']=$car_more['seat_num'];
            $order_info['guobie']=getGuobie($car_more['guobie']);
            $order_info['paifang']=getPaifang($car_more['paifang']);

            //取得保险公司名称
            $baoxian=Model()->table('hg_baoxian')->find($baojia['bj_bx_select']);
            $order_info['bj_bx_select']=$baoxian['bx_title'];

            $order_info['bj_shangpai']=getIs($baojia['bj_shangpai']);
            $order_info['bj_linpai']=getIs($baojia['bj_linpai']);
            $order_info['bj_step']=getBaojiaBuzhou($baojia['bj_step']);
            $order_info['bj_is_pass']=getShenhe($baojia['bj_is_pass']);
            $order_info['bj_status']=getBaojiaStatus($baojia['bj_status']);

        }
        //可售区域
        $quyu=Model()->table('hg_baojia_area')->find($baojia['bj_id']);
        $order_info['country']=getIs($quyu['country']);
        if($quyu['province']){
            $sheng=Model()->table('area')->where(array('area_id'=>$quyu['province']))->find();
            $order_info['province']=($sheng['area_name']);
        }else{
            $order_info['province']='否';
        }
        if($quyu['city']){
            $sheng=Model()->table('area')->where(array('area_id'=>$quyu['city']))->find();
            $order_info['city']=($sheng['area_name']);
        }


        return $order_info;
    }

    /**
     * 订单操作历史列表
     * @param $condition
     */
    public function getOrderLogList($condition) {
        return $this->table('order_log')->where($condition)->select();
    }
    /*
        取得订单状态的文本表示
    */
    public function getOrderStatus($order_status){  
        $order_config=array_flip(C('hg_order'));
        return $order_config[$order_status];
    }
    
    /**
     * 平台获取结算信息 关联用户表，售方seller表
     * @param $condition
     * @param null $page
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return mixed
     */
    public function getCartInvoiceList($condition, $page = null, $field = '*', $order = '', $limit = '')
    {
    	return $this->table('hg_cart,seller,member')
    	->field('hg_cart.*,seller.wenjian as calc_file,member.member_name,member.member_truename')
    	->join('left,left')
    	->on('hg_cart.seller_id=seller.seller_id,seller.member_id=member.member_id')
    	->where($condition)
    	->order($order)
    	->limit($limit)
    	->page($page)
    	->select();
    }
    /**
     * 平台获取结算信息 关联用户表，售方seller表
     * @param $condition
     */
    public function getCartInvoiceOne($condition)
    {
    	return $this->table('hg_cart,seller,member')
    	->field('hg_cart.*,seller.wenjian as calc_file,member.member_name,member.member_truename,member.member_mobile')
    	->join('left,left')
    	->on('hg_cart.seller_id=seller.seller_id,seller.member_id=member.member_id')
    	->where($condition)
    	->find();
    }
    
    /**
     * 平台获取结算信息 关联用户表，售方seller表
     * @param $condition
     */
    public function getCartInvoiceList2($condition, $page = null, $field = '*', $order = '', $limit = '')
    {
    	$sql = '';
    	$sqlStr =array();
    	if($condition['pdi_calc_status']!=''){
    		$sqlStr[] = "hg_cart.pdi_calc_status='".$condition['pdi_calc_status']."'";
    	}
    	if($condition['order_num']!=''){
    		$sqlStr[] = "hg_cart.order_num='".$condition['order_num']."'";
    	}
    	if($condition['member_name']!=''){
    		$sqlStr[] = "member.member_name='".$condition['member_name']."'";
    	}
    	if($condition['member_truename']!=''){
    		$sqlStr[] = "member.member_truename='".$condition['member_truename']."'";
    	}
    	if($condition['inv_no']!=''){
    		$sqlStr[] = "invoice_seller.inv_no='".$condition['inv_no']."'";
    	}
    	if(count($sqlStr)>0){
    		$sql = implode(' and ',$sqlStr);
    	}
    	return $this->table('hg_cart,invoice_seller,seller,member')
    	->field('hg_cart.*,invoice_seller.*,seller.wenjian as calc_file,member.member_name,member.member_truename')
    	->join('left,left,left')
    	->on('hg_cart.order_num=invoice_seller.order_num,invoice_seller.seller_id=seller.seller_id,seller.member_id=member.member_id')
    	->where($sql)
    	->order($order)
    	->limit($limit)
    	->page($page)
    	->select();
    	
    }
}
