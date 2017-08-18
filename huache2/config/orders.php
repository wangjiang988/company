<?php
/**
 *
 * User: S4p3r
 * Date: 2017/3/2
 * Time: 14:11
 */
return
    [
        'order_earnest_price'       => sprintf("%.2f", 499), //诚意金
        'order_place'               => 1, // 已下单,仅在消费者可以看到
        'order_cancel'              => 0, // 订单已取消
        'order_end'                 => 99, // 订单完成

        /**
         * -------------------------------------------------------------------------
         * 诚意金
         */
        'order_earnest'             => 2,
        // 诚意金子状态200-299
        'order_earnest_wait_pay'    => 200, // 等待付诚意金
        'order_earnest_not_confirm' => 2011, // 已付诚意金，未确认
        'order_earnest_not_confirm_file' => 2012, //等待售方反馈特需文件
        'order_earnest_confirm'     => 2021, // 经销商代理反馈信息，修改信息

        'order_earnest_eidt1' => 2032, // 售方反馈了特需,无修改文件
        'order_earnest_edit2' => 2031, // 售方反馈特需,且有修改

        'order_earnest_eidt3' => 2022, // 售方特需中修改订单(客户接受特需,等待确认修改)
        'order_earnest_dealer_end' => 290,//204, //售方主动终止
     //   'order_seller_time_end'    => 292, //205  //客户超时终止 || 客户不接受修改主动终止

        'order_seller_time_end' => 2911,//206, //客户不接修改而终止.
        'order_seller_earnest_end' => 2912,//206, //客户不接受售房的特需提交而终止.

        'order_sincerity_negotiation'         =>621, //进入协商
        'order_sincerity_member_result'        => 622, //客户确定协商结果
        'order_sincerity_seller_result'        => 623,  //售方
        'order_sincerity_all_result'        => 624,  //双方

        'order_sincerity_member_invasion'   => 627, //客户违约
        'order_sincerity_seller_invasion'   => 628, //售方违约
        /**
         * -------------------------------------------------------------------------
         * 担保金主状态
         */
        'order_doposit'            => 3,
        'order_earnest_backok'     => 300, // 反馈同意,跳转到支付担保金页面
        // 担保金子状态300-399
        'order_doposit_wait_pay'   => 302, // 特需文件收到客户买车担保金，等待确认
        'order_doposit_wait_pay2'  => 301, // 无特需文件收到客户买车担保金,待确认

        'order_doposit_timeout'      => 392,//304,  //超时终止订单
        'order_doposit_taskend'      => 390,  //售方主动终止
        'order_doposit_admin'        => 309,  //平台暂停时间
        'order_doposit_not_confirm'  => 303, // 售方修改了订单，等待客户反馈
        'order_doposit_not_edit'     => 391,//306, //客户不接受修改,终止订单
        'order_doposit_end'          => 394, //平台判定终止

        'order_doposit_negotiation'         =>631, //进入协商
        'order_doposit_member_result'        => 632, //客户确定协商结果
        'order_doposit_seller_result'        => 633,  //售方
        'order_doposit_all_result'        => 634,  //双方

        'order_doposit_member_invasion'   => 637, //客户违约
        'order_doposit_seller_invasion'   => 638, //售方违约


        /**
         * -------------------------------------------------------------------------
         * 交车主状态
         */
        'order_jiaoche'              => 4,
        // 交车子状态400-499
        'order_jiaoche_wait'         => 400, // 订单执行,备货中...
        'order_jiaoche_sent_notify'  => 401, // 代理发送交车通知，客户提交资料

        'order_jiaoche_ok'           => 402, // 预约交车反馈ok 准备服务专员

        'order_jiaoche_no'           => 403, // 预约交车反馈不ok,反馈重新确认

        'order_jiaoche_confirm'   => 404, // 售方补充预约信息完成

        'order_jiaoche_negotiation'         =>641, //进入协商
        'order_jiaoche_member_result'        => 642, //客户确定协商结果
        'order_jiaoche_seller_result'        => 643,  //售方
        'order_jiaoche_all_result'        => 644,  //双方

        'order_jiaoche_member_invasion'   => 647, //客户违约
        'order_jiaoche_seller_invasion'   => 648, //售方违约


        /**
         * 交车以后
         */
        'order_deal'                =>5,
        'order_jiaoche_member'      => 501,  // 客户先确认交车
        'order_jiaoche_seller'      => 500,  //售方先确认交车
        'order_jiaoche_all'         => 502, //双方都同意交车
        'order_settlement_security' => 503 , //结算担保金,开始评价
        //'order_deal_record'         => 504, //完成评价
        // 'order_settlement_regular' => 505, //商家等待定期结算
        'order_settlement_have' => 506, //商家已结算，等待收入入账
        'order_settlement_Income' => 507, //商家收入已入账


        /**
         * 交车完成
         */
        'order_deal_appraise'      =>100,   // 客户评价

        /** 协商终止相关 **/
        'order_negotiate'            => 6, //主状态
        'order_confirm_negotiation'  => 601, //确认协商
        'order_member_agreed_nego' => '602', //用户同意协商
        'order_seller_agreed_nego' => '603', //经销商同意协商
        'order_all_agreed_nego'    => '604', //双方都同意

        'order_member_reason_end' => '607', //售方原因
        'order_seller_reason_end' => '608' //客户原因

    ];