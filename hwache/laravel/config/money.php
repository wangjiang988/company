<?php

return [
    // 资金类型反转
    'order_type'        => [
        'earnest'       => 1,
        'doposit'       => 2,
        'contract'      => 3,
        'topup'         => 4,
    ],

    // 资金类型
    'order_money'       => [
        1               => [
            'id'        => 1,
            'name'      => 'earnest',
            'title'     => '诚意金',
        ],
        2               => [
            'id'        => 2,
            'name'      => 'doposit',
            'title'     => '担保金',
        ],
        3               => [
            'id'        => 3,
            'name'      => 'contract',
            'title'     => '违约金',
        ],
        4               => [
            'id'        => 4,
            'name'      => 'topup',
            'title'     => '充值',
        ],
    ],

    // 诚意金
    'earnest'           => 499,

    // 每订单免提现手续费资金
    'order_free_money'  => 1000,

    // 资金支付状态
    'pay_status'        => [
        'not_pay'       => 0,
        'pay_success'   => 1,
        'pay_cancel'    => 2,
    ],

];
