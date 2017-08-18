<?php

defined('InHG') or exit('Access Invalid!');

return array(

    // 报价初始化设置
    'baojia_config' => array(
        'isPass'      => 0, // 默认发布的报价属于未审核状态
    ),

    // 字段数据类型
    'type'          => array(
        'text'      => '单行文本',
        'textarea'  => '多行文本',
        'editor'    => '编辑器',
        'radio'     => '单项选择',
        'checkbox'  => '多项选择',
        'select'    => '下拉列表',
        'datetime'  => '时间日期',
        'number'    => '数字',
        'float'     => '小数 (小数点后保留两位)',
    ),

    // 用于保存数据和快速检索数据
    'type_quite'    => array(
        'text'      => 'var',
        'textarea'  => 'var',
        'datetime'  => 'var',
        'float'     => 'var',
        'editor'    => 'text',
        'radio'     => 'int',
        'checkbox'  => 'int',
        'select'    => 'int',
        'number'    => 'int',
    ),

    // 选项的字段数组
    'box'           => array(
        'radio',
        'checkbox',
        'select',
    ),

    // 关联模型数据
    'model'         => array(
        'carmodel'  => '车型',
        'dealer'    => '经销商',
        'fee'       => '收费服务',
        'free'      => '免费服务',
        'ycxzj'     => '原厂选装件',
        'other_price'    =>'杂费标准',
        'ziliao'    =>'资料',
        'shenfen'    =>'身份',
        'butie_ziliao'=>'领取节能补贴所需资料',
    ),

    // 交车周期
    'jcPeriod'      => array(
        1           => '1个月',
        2           => '2个月',
        3           => '3个月',
        4           => '4个月',
        5           => '5个月',
        6           => '6个月',
        12           => '12个月',
    ),

    // 付款方式
    'payType'       => array(
        1           => '全款',
    ),

    // 补贴方式
    'butieType'     => array(
        1           => '交车时当场兑现，由销售商垫付，消费者上牌后补充所缺资料给销售商',
        2           => array(
            'title' => '上牌资料齐全后，由销售商立即垫付给消费者',
            'num'   => 0,
            'danwei'=> '个工作日内',
        ),
        3           => array(
            'title' => '交车后，销售商将所有资料交给汽车厂商，厂商直接付给消费者，或者厂商付销售商再由销售商付消费者',
            'num'   => 0,
            'danwei'=> '个月内',
        ),
    ),
	'calc_file_status'	=>array(
			0=>'已寄送',
			1=>'已撤销',
			2=>'已确认',
	),
);