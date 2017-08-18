<?php
/**
 * 支付接口配置文件
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 */

return [
    // 支付账户
    'pay_user'          => [
        -1              => 'hcpay',
        -2              => 'hc',
        -3              => 'withdrawcash',
        -4              => 'money',
        -5              => 'earnest',
        -6              => 'doposit',
        -7              => 'contract',

        -50             => 'alipay',
        -51             => 'wxpay',

        -999            => 'offline',
    ],

    'hcpay'             => [
        'id'            => -1,
        'name'          => 'hcpay',
        'title'         => '加信宝',
        'remarks'       => '加信宝',
    ],

    'hc'                => [
        'id'            => -2,
        'name'          => 'hc',
        'title'         => '华车',
        'remarks'       => '华车专用账户',
    ],

    'withdrawcash'      => [
        'id'            => -3,
        'name'          => 'withdrawcash',
        'title'         => '提现',
        'remarks'       => '提现',
    ],

    'money'             => [
        'id'            => -4,
        'name'          => 'money',
        'title'         => '可用余额',
        'remarks'       => '用户可用余额',
    ],

    'earnest'           => [
        'id'            => -5,
        'name'          => 'earnest',
        'title'         => '诚意金',
        'remarks'       => '诚意金',
    ],

    'doposit'           => [
        'id'            => -6,
        'name'          => 'doposit',
        'title'         => '担保金',
        'remarks'       => '担保金',
    ],

    'contract'          => [
        'id'            => -7,
        'name'          => 'contract',
        'title'         => '赔偿金',
        'remarks'       => '赔偿金',
    ],

    /**
     * 第三方支付参数配置
     *
     * 第三方支付的id从－50开始，依次减1，增加一个支付方式，还需要增加该支付方式id的反向指向。
     * 详见本配置最上面的支付账户
     *
     * 默认支付接口配置，名称就是第三方支付名称
     */
    'sessionPayment'    => 'sessionPayment',

    /**
     * 支付宝配置参数
     */
    'alipay'            => [
        'id'            => -50,
        'name'          => 'alipay',
        'title'         => '支付宝',
        'remarks'       => '支付宝在线支付',
        'pid'           => env('ALIPAY_PID', ''),
        'email'         => env('ALIPAY_SELLER_EMAIL', ''),
        'key'           => env('ALIPAY_KEY', ''),
        'pay_way'       => 1, // 1:线上  2:线下
    ],

    /**
     * 微信支付配置参数
     */
    'wxpay'            => [
        'id'            => -51,
        'name'          => 'wxpay',
        'title'         => '微信支付',
        'remarks'       => '微信支付',
        'pid'           => env('ALIPAY_PID', ''),
        'email'         => env('ALIPAY_SELLER_EMAIL', ''),
        'key'           => env('ALIPAY_KEY', ''),
        'pay_way'       => 1, // 1:线上  2:线下
    ],


    'offline'           => [
        'id'            => -999,
        'name'          => 'offline',
        'title'         => '线下支付',
        'remarks'       => '线下支付',
        'pay_way'       => 2, // 1:线上  2:线下
    ],
];
