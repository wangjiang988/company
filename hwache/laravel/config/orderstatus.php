<?php
/*
*订单状态对应的路由，user是用户的
*dealer是经销商代理的
*带参数的路由以/结尾
*/
return [
    'order' => [
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
    	'order_evaluation_end'              => 700,

        // 订单终止------------------------------------------------------------------
        'order_stop'                    =>1000, //订单终止
        'order_stop_daili'              =>1001,//代理在客户支付诚意金后终止
        'order_stop_custem1'             =>1002, // 客户不接受反馈的特需文件，放弃订单,未修改信息
        'order_stop_custem2'            => 1003, // 代理有修改信息，客户不接受反馈的特需文件，放弃订单
        'order_stop_custem3'            => 1004, // 代理有修改信息，客户不接受修改，放弃订单
        'order_stop_daili2'            => 1005, // 售方主动终止或超时终止订单
        'order_stop_custem4'            => 1006, // 售方预约交车前修改订单，客户不接受修改，放弃订单
        'order_stop_tiche'            => 1007, // 交车有争议，协商未成，放弃订单

    ],

    200 => [
        'user' => 'user/money/earnest/',
        'dealer' => '',
        'notice'=>'等待付诚意金',
    ],
    201 => [
        'user' => 'pay/wait/',
        'dealer' => 'dealer/feedback/',
        'notice'=>'已付诚意金，未确认',
    ],
    1001 => [
        'user' => 'cart/stop1/',
        'dealer' => 'dealer/stop1/',
        'notice'=>'经销商代理终止了订单，客户获得歉意金',
    ],
    202 => [
        'user' => 'cart/editcar/',
        'dealer' => 'dealer/editcar/',
        'notice'=>'经销商代理反馈修改信息',
    ],
    2021 => [
        'user' => 'cart/acceptfile/',
        'dealer' => 'dealer/acceptfile/',
        'notice'=>'客户接受了反馈的特需文件，代理未修改信息',
    ],
    2023 => [
        'user' => 'cart/acceptall/',
        'dealer' => 'dealer/acceptall/',
        'notice'=>'客户接受订单修改，等待客户买车担保金进入加信宝',
    ],

    1002 => [
        'user' => 'cart/notacceptfile/',
        'dealer' => 'dealer/notacceptfile/',
        'notice'=>'已终止',
    ],
    1003 => [
        'user' => 'cart/acceptedit/',
        'dealer' => 'dealer/acceptedit/',
        'notice'=>'已终止',
    ],
    1004 => [
        'user' => 'cart/notacceptedit/',
        'dealer' => 'dealer/notacceptedit/',
        'notice'=>'已终止',
    ],
    203 => [
        'user' => 'user/money/doposit/',
        'dealer' => 'dealer/feedbackok/',
        'notice'=>'经销商代理确认诚意金并反馈ok',
    ],
    300 => [
        'user' => 'pay/depositok/',
        'dealer' => 'dealer/feedbackresponse/',
        'notice'=>'收到客户买车担保金，等待确认',
    ],
    /*
    301 => [
        'user' => 'pay/deposit/',
        'dealer' => 'dealer/feedbackok/',
        'notice'=>'已支付部分保证金，还需要继续支付',
    ],*/
    /*302 => [
        'user' => 'pay/depositwait/',
        'dealer' => 'dealer/feedbackresponse/',
        'notice'=>'已付担保金，未确认',
    ],
    303 => [
        'user' => 'pay/depositok/',
        'dealer' => 'dealer/responseok/',
        'notice'=>'经销商代理已确认担保金并响应ok',
    ],*/
    // 302 => [
    //     'user' => 'pay/depositok/',
    //     'dealer' => 'dealer/pdinotice/',
    //     'notice'=>'已付担保金，发出交车邀请',
    // ],
    301 => [
        'user' => 'cart/yuyuefirst/',
        'dealer' => 'dealer/responseok/',
        'notice'=>'代理确认担保金，并发出交车通知',
    ],
    302 => [
        'user' => 'cart/pdiedit/',
        'dealer' => 'dealer/pdiedit/',
        'notice'=>'提议修改订单，等待客户确认',
    ],
    1005 => [
        'user' => 'cart/stop2/',
        'dealer' => 'dealer/stop2/',
        'notice'=>'已终止',
    ],
    303 => [
        'user' => 'cart/waitnotice/',
        'dealer' => 'dealer/waitnotice/',
        'notice'=>'客户接受订单修改，等待发预约通知',
    ],
    1006 => [
        'user' => 'cart/stop3/',
        'dealer' => 'dealer/stop3/',
        'notice'=>'已终止',
    ],
    // 预约
    401 => [
        'user' => 'cart/yuyue/',
        'dealer' => 'dealer/pdinoticeok/',
        'notice'=>'代理发送交车通知，客户提交资料',
    ],
    402 => [
        'user' => 'cart/yuyueok/',
        'dealer' => 'dealer/pdiconfirmok/',
        'notice'=>'客户已确认交车通知，等待补充预约信息',
    ],
    403 => [
        'user' => 'cart/yuyueno/',
        'dealer' => 'dealer/pdiconfirm/',
        'notice'=>'客户修改交车邀请的条件',
    ],
    404 => [
        'user' => 'cart/yuyuenoconfirm/',
        'dealer' => 'dealer/pdiwaitconfirm/',
        'notice'=>'代理再次确认交车邀请的条件',
    ],
    405 => [
        'user' => 'cart/yuyueconfirmok/',
        'dealer' => 'dealer/pdiok/',
        'notice'=>'代理提交补充信息',
    ],
    409 => [
        'user' => 'cart/yuyueend/',
        'dealer' => 'dealer/pdiend/',
        'notice'=>'交车预约完毕',
    ],
    // 交车
    500 => [
        'user' => 'cart/tiche_info/',
        'dealer' => 'dealer/ticheinfo/',
        'notice'=>'提交车辆最终信息',
    ],
    // 经销商处上牌
    501 => [
        'user' => 'cart/tiche_info/',
        'dealer' => 'dealer/ticheend/',
        'notice'=>'代理已提交车辆信息，等待客户提交',
    ],
    // 客户自己上牌
    502 => [
        'user' => 'cart/tiche_info/',
        'dealer' => 'dealer/ticheenduser/',
        'notice'=>'代理已提交车辆信息，等待客户提交',
    ],
    503 => [
        'user' => 'cart/ticheend/',
        'dealer' => 'dealer/ticheinfo/',
        'notice'=>'客户已提交车辆信息，等待代理提交',
    ],
    // 双方都提交了，在经销商处上牌
    504 => [
        'user' => 'cart/ticheend/',
        'dealer' => 'dealer/ticheend/',
        'notice'=>'平台正在核实车辆信息',
    ],
    // 双方都提交了，客户自己上牌
    505 => [
        'user' => 'cart/ticheend/',
        'dealer' => 'dealer/ticheenduser/',
        'notice'=>'平台正在核实车辆信息',
    ],
    // 提车争议处理
    506 => [
        'user' => 'cart/defend/',
        'dealer' => 'dealer/dispute/',
        'notice'=>'代理先提交交车争议',
    ],
    507 => [
        'user' => 'cart/dispute/',
        'dealer' => 'dealer/defend/',
        'notice'=>'客户先提交交车争议',
    ],
    509 => [
        'user' => 'cart/mediateok/',
        'dealer' => 'dealer/mediateok/',
        'notice'=>'调解成功，继续订单',
    ],
    1007 => [
        'user' => 'cart/mediatefail/',
        'dealer' => 'dealer/mediatefail/',
        'notice'=>'调解未成功，终止订单',
    ],
	600 => [
			'user' => 'cart/heshi/',
        	'dealer' => 'dealer/jchjcheckmoney/',
        	'notice'=>'核对金额',
	],
	601 => [
			'user' => 'cart/tuikuan/',
			'dealer' => 'dealer/jchjhandleprocedures/',
			'notice'=>'办理退款',
	],
	602 => [
			//'user' => 'cart/tuikuanend/',
			'dealer' => 'dealer/jchjend/',
			'notice'=>'结算完毕',
	],
	700 => [
			'user' => 'orderoverview/',
			//'dealer' => 'dealer/mediateok/',
			'notice'=>'购车评价完成',
	],
];
