<?php
/**
 * 商品图片统一调用函数
 */

defined('InHG') or exit('Access Invalid!');

/**
 * 取得商品缩略图的完整URL路径，接收商品信息数组，返回所需的商品缩略图的完整URL
 *
 * @param array $goods 商品信息数组
 * @param string $type 缩略图类型  值为60,160,240,310,1280
 * @return string
 */
function thumb($goods = array(), $type = ''){
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($goods)){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    if (array_key_exists('apic_cover', $goods)) {
        $goods['goods_image'] = $goods['apic_cover'];
    }
    if (empty($goods['goods_image'])) {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$goods['goods_image']);
    $fname = basename($file);
    //取店铺ID
    if (preg_match('/^(\d+_)/',$fname)){
        $store_id = substr($fname,0,strpos($fname,'_'));
    }else{
        $store_id = $goods['store_id'];
    }
    $file = $type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file);
    if (!file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file)){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL.'/'.ATTACH_GOODS;
    return $thumb_host.'/'.$store_id.'/'.$file;

}
/**
 * 取得商品缩略图的完整URL路径，接收图片名称与店铺ID
 *
 * @param string $file 图片名称
 * @param string $type 缩略图尺寸类型，值为60,160,240,310,1280
 * @param mixed $store_id 店铺ID 如果传入，则返回图片完整URL,如果为假，返回系统默认图
 * @return string
 */
function cthumb($file, $type = '', $store_id = false) {
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($file)) {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage ( $type );
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$file);
    $fname = basename($file);
    // 取店铺ID
    if ($store_id === false || !is_numeric($store_id)) {
        $store_id = substr ( $fname, 0, strpos ( $fname, '_' ) );
    }
    // 本地存储时，增加判断文件是否存在，用默认图代替
    if ( !file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file)) )) {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
    return $thumb_host . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
}

/**
 * 取得团购缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small,mid,max
 * @return string
 */
function gthumb($image_name = '', $type = ''){
	if (!in_array($type, array('small','mid','max'))) $type = 'small';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
    list($base_name, $ext) = explode('.', $image_name);
    list($store_id) = explode('_', $base_name);
    $file_path = ATTACH_GROUPBUY.DS.$store_id.DS.$base_name.'_'.$type.'.'.$ext;
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得买家缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为240,1024
 * @return string
 */
function snsThumb($image_name = '', $type = ''){
	if (!in_array($type, array('240','1024'))) $type = '240';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }

    list($member_id) = explode('_', $image_name);
    $file_path = ATTACH_MALBUM.DS.$member_id.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得积分商品缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small
 * @return string
 */
function pointprodThumb($image_name = '', $type = ''){
	if (!in_array($type, array('small'))) $type = '';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }

    if($type) {
        $file_path = ATTACH_POINTPROD.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    } else {
        $file_path = ATTACH_POINTPROD.DS.$image_name;
    }
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得品牌图片
 *
 * @param string $image_name
 * @return string
 */
function brandImage($image_name = '') {
    if ($image_name != '') {
        return UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$image_name;
    }
    return UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/default_brand_image.gif';
}

/**
* 取得订单状态文字输出形式
*
* @param array $order_info 订单数组
* @return string $order_state 描述输出
*/
function orderState($order_info) {
   $con= array_flip(C('hg_order')); 
   return $con[$order_info];  
}
/*
function orderState($order_info) {
    switch ($order_info['order_state']) {
        case ORDER_STATE_CANCEL:
            $order_state = L('order_state_cancel');
        break;
        case ORDER_STATE_NEW:
            $order_state = L('order_state_new');
        break;
        case ORDER_STATE_PAY:
            $order_state = L('order_state_pay');
        break;
        case ORDER_STATE_SEND:
            $order_state = L('order_state_send');
        break;
        case ORDER_STATE_SUCCESS:
            $order_state = L('order_state_success');
        break;
    }
    return $order_state;
}*/
/**
 * 取得订单支付类型文字输出形式
 *
 * @param array $payment_code
 * @return string
 */
function orderPaymentName($payment_code) {
    return str_replace(
            array('offline','online','alipay','tenpay','chinabank','predeposit'),
            array('货到付款','在线付款','支付宝','财付通','网银在线','预存款'),
            $payment_code);
}

/**
 * 取得订单商品销售类型文字输出形式
 *
 * @param array $goods_type
 * @return string 描述输出
 */
function orderGoodsType($goods_type) {
    return str_replace(
            array('1','2','3','4','5'),
            array('','团购','限时折扣','优惠套装','赠品'),
            $goods_type);
}

/**
 * 取得结算文字输出形式
 *
 * @param array $bill_state
 * @return string 描述输出
 */
function billState($bill_state) {
    return str_replace(
            array('1','2','3','4'),
            array('已出账','商家已确认','平台已审核','结算完成'),
            $bill_state);
}
/**
 * 取得是否类型的文字输出形式
 *
 */
function getIs($payment_code) {
    switch ($payment_code) {
        case 1:
            $payment_code = '是';
        break;
        case 0:
            $payment_code = '否';
        break;
        
    }
    return $payment_code;
}

/**
 * 取得审核类型的文字输出形式
 *
 */
function getShenhe($payment_code) {
    switch ($payment_code) {
        case 1:
            $payment_code = '审核通过';
        break;
        case 0:
            $payment_code = '未审核';
        break;
        
    }
    return $payment_code;
}
/**
 * 取得付款方式的文字输出形式
 *
 */
function getFukuan($payment_code) {
    switch ($payment_code) {
        case 1:
            $payment_code = '全款';
        break;
        case 2:
            $payment_code = '贷款';
        break;
        
    }
    return $payment_code;
}

/**
 * 取得报价状态的文字输出形式
 *
 */
function getBaojiaStatus($payment_code) {
    switch ($payment_code) {
        case 1:
            $payment_code = '正常';
        break;
        case 0:
            $payment_code = '终止';
        break;
        
    }
    return $payment_code;
}
/**
 * 取得报价步骤的文字输出形式
 *
 */
function getBaojiaBuzhou($payment_code) {
    switch ($payment_code) {
        case 1:
            $payment_code = '填写基本信息';
        break;
        case 0:
            $payment_code = '选择车型';
        break;
        case 2:
            $payment_code = '选择经销商';
        break;
        case 99:
            $payment_code = '完成';
        break;
    }
    return $payment_code;
}
// 取得国别的文字输出
function getGuobie($guobie){
    switch($guobie){
        case 1:
            $guobie='进口';
        break;
        case 0:
            $guobie='国产';
        break;
    }
    return $guobie;
}
// 取得排放标准的文字输出
function getPaifang($biaozhun){
    switch($biaozhun){
        case 1:
            $biaozhun='国四';
        break;
        case 0:
            $biaozhun='国五';
        break;
        case 2:
            $biaozhun='新能源';
        break;
    }
    return $biaozhun;
}
?>
