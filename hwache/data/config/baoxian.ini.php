<?php
/**
 * 保险费率表
 */
defined ( 'InHG' ) or exit ( 'Access Invalid!' );

return array (

    // 保险种类,车辆座位数量.非运营个人/企业,6/6-10座
    'baoxian_seat_num'      => array (
        1                   => array (
            'id'            => 1,
            'title'         => '个人',
            'seat'          => '6座以下'
        ),
        2                   => array (
            'id'            => 2,
            'title'         => '个人',
            'seat'          => '6-10座'
        ),
        3                   => array (
            'id'            => 3,
            'title'         => '企业',
            'seat'          => '6座以下'
        ),
        4                   => array (
            'id'            => 4,
            'title'         => '企业',
            'seat'          => '6-10座'
        )
    ),

    // 保险的险种
    'baoxianType'           => array (
        1                   => array (
            'id'            => 1,
            'name'          => 'chesun',
            'title'         => '机动车损失险',
            'main'          => 1,
            'desc'          => '基础保费+新车购置价×费率',
            'fields'        => array (
                1           => array (
                    'name'  => 'base',
                    'title' => '基础保费',
                    'dw'    => '元'
                ),
                2           => array (
                    'name'  => 'rate',
                    'title' => '费率',
                    'dw'    => '%'
                )
            )
        ),
        2                   => array (
            'id'            => 2,
            'name'          => 'daoqiang',
            'title'         => '机动车盗抢险',
            'main'          => 1,
            'desc'          => '基础保费+新车购置价×费率',
            'fields'        => array (
                1           => array (
                    'name'  => 'base',
                    'title' => '基础保费',
                    'dw'    => '元'
                ),
                2           => array (
                    'name'  => 'rate',
                    'title' => '费率',
                    'dw'    => '%'
                )
            )
        ),
        3                   => array (
            'id'            => 3,
            'name'          => 'sanzhe',
            'title'         => '第三者责任险',
            'main'          => 1,
            'desc'          => '固定值，按赔付额度查找确定',
            'fields'        => array (
                1           => array (
                    'name'  => 'base',
                    'title' => '赔付额度',
                    'value' => array (
                        5   => '5万元',
                        10  => '10万元',
                        15  => '15万元',
                        20  => '20万元',
                        30  => '30万元',
                        50  => '50万元',
                        100 => '100万元'
                    ),
                    'dw'    => '元'
                )
            )
        ),
        4                   => array (
            'id'            => 4,
            'name'          => 'renyuan',
            'title'         => '车上人员责任险',
            'main'          => 1,
            'desc'          => '每次事故责任限额×费率(×投保座位数)',
            'foreach'       => array (
                'sj'        => '司机',
                'ck'        => '乘客'
            ),
            'fields'        => array (
                1           => array (
                    'name'  => 'rate',
                    'title' => '费率',
                    'value' => array (
                        1   => '每座限额1万元',
                        2   => '每座限额2万元',
                        3   => '每座限额3万元',
                        4   => '每座限额4万元',
                        5   => '每座限额5万元'
                    ),
                    'dw'    => '%'
                )
            )
        ),
        5                   => array (
            'id'            => 5,
            'name'          => 'boli',
            'title'         => '玻璃单独破碎险',
            'main'          => 0,
            'desc'          => '新车购置价×费率',
            'foreach'       => array (
                'jk'        => '进口',
                'gc'        => '国产'
            ),
            'fields'        => array (
                1           => array (
                    'name'  => 'rate',
                    'title' => '费率',
                    'dw'    => '%'
                )
            )
        ),
        6                   => array (
            'id'            => 6,
            'name'          => 'ziran',
            'title'         => '自燃损失险',
            'main'          => 0,
            'desc'          => '新车购置价×费率',
            'fields'        => array (
                1           => array (
                    'name'  => 'rate',
                    'title' => '费率',
                    'dw'    => '%'
                )
            )
        ),
        7                   => array (
            'id'            => 7,
            'name'          => 'huahen',
            'title'         => '车身划痕损失险',
            'main'          => 0,
            'desc'          => '固定值，按新车购置价和赔付额度查找确定',
            'foreach'       => array (
                30          => '30万以下',
                3050        => '30~50万',
                50          => '50万以上'
            ),
            'fields'        => array (
                1           => array (
                    'name'  => 'peifu',
                    'title' => '赔付额度',
                    'value' => array (
                        2000    => '赔付额度0.2万元',
                        5000    => '赔付额度0.5万元',
                        10000   => '赔付额度1万元',
                        20000   => '赔付额度2万元'
                    ),
                    'dw'    => '元'
                )
            )
        ),
        8                   => array (
            'id'            => 8,
            'name'          => 'bujimian',
            'title'         => '不计免赔特约险',
            'main'          => 0,
            'desc'          => '适用本条款的险种标准保费×费率',
            'fields'        => array (
                1           => array (
                    'name'  => 'baoxian_type',
                    'title' => '保险的种类',
                    'value' => array (
                        'chesun'    => '机动车损失险',
                        'daoqiang'  => '机动车盗抢险',
                        'sanzhe'    => '第三者责任险',
                        'renyuan'   => '车上人员责任险',
                        'huahen'    => '车身划痕损失险'
                    ),
                    'dw'    => '%'
                )
            )
        )
    ),

    // 车损险
    'chesun'                => array (
        'base'              => 285,
        'rate'              => 0.01088
    ),

    // 全车盗抢险
    'daoqiang'              => array (
        'base'              => 120,
        'rate'              => 0.0049
    ),

    // 第三者责任险
    'sanzhe'                => array (
        5                   => 710,
        10                  => 1026,
        15                  => 1169,
        20                  => 1270,
        30                  => 1434,
        50                  => 1721,
        100                 => 2242
    ),

    // 玻璃单独破碎险
    'boli'                  => array (
        // 国产车
        'rate1'             => 0.0015,
        // 进口车
        'rate2'             => 0.0025
    ),

    // 自燃损失险
    'ziran'                 => array (
        'rate'              => 0.0015
    ),

    // 不计免赔特约险
    'bujimian'              => array (
        'rate'              => 0.2
    ),

    // 无过免责险
    'wuguomianze'           => array (
        'rate'              => 0.2
    ),

    // 车上人员责任险
    'renyuan'               => array (
        'base'              => 50
    ),

    // 车身划痕险
    'huahen'                => array (
        2000                => 400,
        5000                => 570,
        10000               => 760,
        20000               => 1140
    )
);
