<?php
defined('InHG') or exit('Access Invalid!');
/**
 * header中的文字
 */
$lang['all_order']                          = '所有订单';
$lang['order_sn']                           = '订单编号';
$lang['order_add_time']                     = '下单时间';
$lang['view_order']                         = '查看订单';
$lang['car_brand']                          = '车型';
$lang['buyer_name']                         = '消费者';
$lang['changshang_price']                   = '厂商指导价';
$lang['guobie']                             = '国别';
$lang['seat_num']                           = '座位数';
$lang['body_color']                         = '车身颜色';
$lang['interior_color']                     = '内饰颜色';
$lang['paifang']                            = '排放';
$lang['producetime']                        = '生产日期';
$lang['jc_period']                          = '交车周期';
$lang['licheng']                            = '行驶里程';
$lang['lckp']                               = '裸车开票价';
$lang['dingjin']                            = '消费者担保金';
$lang['dealer']                             = '经销商';
$lang['order_status']                       = '订单状态';
$lang['order'] = array(
    'order_place'                   => '已下单',
    'order_cancel'                  => '订单已取消',
	'order_stop_daili'              =>'代理在客户支付诚意金后终止',//代理在客户支付诚意金后终止
	'order_stop_custem1'             =>'客户不接受反馈的特需文件，放弃订单,未修改信息', // 客户不接受反馈的特需文件，放弃订单,未修改信息
	'order_stop_custem2'            => '代理有修改信息，客户不接受反馈的特需文件，放弃订单', // 代理有修改信息，客户不接受反馈的特需文件，放弃订单
	'order_stop_custem3'            => '代理有修改信息，客户不接受修改，放弃订单', // 代理有修改信息，客户不接受修改，放弃订单
	'order_stop_daili2'            => '售方主动终止或超时终止订单', // 售方主动终止或超时终止订单
	'order_stop_custem4'            => '售方预约交车前修改订单，客户不接受修改，放弃订单', // 售方预约交车前修改订单，客户不接受修改，放弃订单
	'order_stop_tiche'            => '交车有争议，协商未成，放弃订单', // 交车有争议，协商未成，放弃订单
		

    'order_earnest'                 => '诚意预约',
    'order_earnest_wait_pay'        => '等待付诚意金',
    'order_earnest_not_confirm'     => '已付诚意金，未确认',
    'order_earnest_confirm'         => '经销商已确认收到诚意金',
	'order_earnest_backok'          => '经销商代理确认反馈ok', // 
		
    'order_doposit'                 => '付担保金',
    'order_doposit_wait_pay'        => '等待消费者付担保金',
    'order_doposit_pay_part'        => '已支付部分保证金，还需要继续支付',
    'order_pay_deposit'             => '消费者已支付担保金',
    'order_doposit_not_confirm'     => '已付担保金,未确认',
    'order_deposit_confirm'         => '经销商已确认担保金',
	'order_doposit_accept'     		=> '售方修改了订单，客户接受修改', // 售方修改了订单，客户接受修改
	'order_jiaoche_no_confirmok'    => '预约交车反馈不ok，客户再反馈ok', // 
		
    'order_jiaoche'                 => '预约交车',
    'order_jiaoche_wait'            => '订单执行,备货中...',
    'order_jiaoche_sent_notify'     => '经销商发出交车通知',
    'order_jiaoche_confirm'         => '等待经销商确认交车时间',
    'order_jiaoche_ok'              => '已确定具体交车时间、地点',

    'order_tiche'                   => '付款提车',
    'order_tiche_info_check'        => '消费者确认提车信息',
    'order_tiche_ok'                => '消费者已确认提车信息,进行线下提车...',
	'order_tiche_pdi_ok_u'			=> '等待客户提交上牌信息',
	'order_tiche_custem_ok'         => '等待经销商提供上牌信息',
	'order_tiche_ok_u'				=> '交车信息双方已经提交',
	
    'order_refund'                  => '订单结算',
	'order_refund_check'       => '结算核实', // 核对
	'order_refund_handle'       => '办理手续', // 办理
	'order_refund_end'       => '结算完成', // 完成

    'order_evaluation'              => '评价',

    'order_upload_material'         => '提交材料至平台',
    'order_end'                     => '订单完成',

    // 其它订单状态
    'order_modify'                  => '经销商修改订单',
    'order_custorm_agree'           => '消费者同意修改',
    'order_custorm_not_agree'       => '消费者不同意修改',
    'order_return_earnest'          => '退歉意金',

    'order_return_earnest_deposit'  => '退歉意金、担保金、利息',
);








