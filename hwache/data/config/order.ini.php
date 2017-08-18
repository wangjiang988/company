<?php
//华车服务费设置类型
$config['PRICE_TYPE']               = 1;//（1：固定类型，2：比例设置，3：动态调整）
$config['TAX_RATE']                 = '0.94';//税率
$config['HC_PROPORTION']            = '0.05';//华车佣金比例
// 华车服务费
$config['hwacheServicePrice']       = 500;

// 定义订单状态
$config['hg_order'] = array(
    // 'order_place'                   => 1, // 已下单，未付诚意金
    // 'order_earnest_not_confirm'     => 2, // 已付诚意金，未确认
    // 'order_confirm'                 => 3, // 经销商代理已确认
    // 'order_pay_deposit'             => 4, // 客户已支付担保金
    // 'order_deposit_confirm'         => 5, // 经销商代理确认担保金
    // 'order_execute'                 => 6, // 订单执行（备货中）
    // 'order_barter_car_notice'       => 7, // 经销商发出交车通知
    // 'order_confirm_time_place'      => 8, // 已确定具体交车时间、地点
    // 'order_bartering'               => 9, // 正在交车
    // 'order_barter_ok'               => 10, // 完成交车
    // 'order_upload_material'         => 11, // 提交材料至平台

    // // 其它订单状态
    // 'order_modify'                  => 20, // 经销商代理修改订单
    // 'order_custorm_agree'           => 21, // 客户同意修改
    // 'order_custorm_not_agree'       => 22, // 客户不同意修改
    // 'order_return_earnest'          => 23, // 退歉意金

    // 'order_return_earnest_deposit'  => 41, // 退歉意金、担保金、利息

    // 'order_end'                     => 99, // 订单完成


    /**
     * -------------------------------------------------------------------------
     * 订单主状态
     */
    'order_place'                   => 1, // 已下单,仅在消费者可以看到
    'order_cancel'                  => 0, // 订单已取消
    'order_end'                     => 99, // 订单完成
    


    /**
     * -------------------------------------------------------------------------
     * 诚意金
     */
    'order_earnest'                 => 2,
    // 诚意金子状态200-299
    'order_earnest_wait_pay'        => 200, // 等待付诚意金
    'order_earnest_not_confirm'     => 201, // 已付诚意金，未确认
    'order_earnest_confirm'         => 202, // 经销商代理反馈特殊文件，修改信息
    'order_earnest_eidt1'         => 2021, // 客户接受了反馈的特需文件，代理未修改信息
    
    'order_earnest_eidt3'         => 2023, // 客户接受订单修改，等待客户买车担保金进入加信宝
    
    // 'order_earnest_eidt5'         => 2025, // 客户接受反馈的特需文件,不接受修改
    'order_earnest_backok'         => 203, // 经销商代理确认反馈ok
    /**
     * -------------------------------------------------------------------------
     * 担保金主状态
     */
    'order_doposit'                 => 3,
    // 担保金子状态300-399
    'order_doposit_wait_pay'        => 300, // 收到客户买车担保金，等待确认
    'order_doposit_pay_part'        => 301, // 代理确认担保金，并准备发出交车通知
    'order_doposit_not_confirm'     => 302, // 售方修改了订单，等待客户反馈
    'order_doposit_accept'     => 303, // 售方修改了订单，客户接受修改
    

    /**
     * -------------------------------------------------------------------------
     * 交车主状态
     */
    'order_jiaoche'                 => 4,
    // 交车子状态400-499
    'order_jiaoche_wait'            => 400, // 订单执行,备货中...
    'order_jiaoche_sent_notify'     => 401, // 代理发送交车通知，客户提交资料
    'order_jiaoche_ok'         => 402, // 预约交车反馈ok
    'order_jiaoche_no'       => 403, // 预约交车反馈不ok
    'order_jiaoche_no_confirm'       => 404, // 预约交车反馈不ok，代理再确认
    'order_jiaoche_no_confirmok'       => 405, // 预约交车反馈不ok，客户再反馈ok


    'order_jiaoche_end'              => 409, // 预约交车完毕
    /**
     * -------------------------------------------------------------------------
     * 付款提车主状态
     */
    'order_tiche'                   => 5,
    // 付款提车子状态500-599
    'order_tiche_info_check'        => 500, // 填写提车信息
    'order_tiche_pdi_ok'            => 501, // 代理已经提交了车辆最终信息，客户未提交(在经销商处上牌)
    'order_tiche_pdi_ok_u'          => 502, // 代理已经提交了车辆最终信息，客户未提交(客户自己上牌)
    'order_tiche_custem_ok'         => 503, // 客户已经提交了车辆最终信息，代理未提交
    'order_tiche_ok'                => 504, // 两边都提交了车辆最终信息(在经销商处上牌)
    'order_tiche_ok_u'              => 505, // 两边都提交了车辆最终信息(客户自己上牌)
    'order_tiche_dispute'              => 506, // 代理先提交争议
    'order_tiche_dispute_u'              => 507, // 客户先提交争议
    'order_tiche_mediateok'              => 509, // 调解成功，继续订单
    /**
     * -------------------------------------------------------------------------
     * 结算主状态
     */
    'order_refund'                  => 6,
    // 退担保金子状态600-699
    'order_refund_check'       => 600, // 核对
    'order_refund_handle'       => 601, // 办理
    'order_refund_end'       => 602, // 完成



    /**
     * -------------------------------------------------------------------------
     * 评价主状态
     */
    'order_evaluation'              => 7,
    // 评价子状态700-799

    // 订单终止------------------------------------------------------------------
    'order_stop'                    =>1000, //订单终止
    'order_stop_daili'              =>1001,//代理在客户支付诚意金后终止
    'order_stop_custem1'             =>1002, // 客户不接受反馈的特需文件，放弃订单,未修改信息
    'order_stop_custem2'            => 1003, // 代理有修改信息，客户不接受反馈的特需文件，放弃订单
    'order_stop_custem3'            => 1004, // 代理有修改信息，客户不接受修改，放弃订单
    'order_stop_daili2'            => 1005, // 售方主动终止或超时终止订单
    'order_stop_custem4'            => 1006, // 售方预约交车前修改订单，客户不接受修改，放弃订单
    'order_stop_tiche'            => 1007, // 交车有争议，协商未成，放弃订单
);
