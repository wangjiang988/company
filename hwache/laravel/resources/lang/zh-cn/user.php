<?php
/**
 * 用户中心语言包
 *
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
return [
    // 注册相关
    'reg_title'                 => '帐号注册',
    'reg_mobile_exists'         => '此手机号码已经注册，请更换',
    'reg_mobile_code_ok'        => '验证码已经成功发送，请查收',
    'reg_mobile_code_false'     => '验证码发送失败，请等待重试',
    'login_title'               => '用户登录',

    'reg_email_exists'          => '该邮箱已经注册，请更换',
    'reg_email_link_ok'         => '已成功发送验证邮件，请查收',
    'reg_email_link_false'      => '发送验证邮件失败，请重试',

    // 登陆相关
    'name_pwd_not_empty'        => '帐号和密码不能为空',
    'validator_fails'           => '登陆失败，帐号或密码不正确',

    'money'                     => [
        'return_topup'          => '充值失败，请检查参数',
        'no_payment'            => '没有支付方式',
        'money_less_earnest'    => '可用余额小于诚意金，请充值',

        'top_up'                => '充值',
    ],

    'order'                     => [
        'not_has_order'         => '不存在订单',
        'cancel'                => '订单已经取消',
        'confirm_orderNum'      => '无此订单，请确认订单号正确',
        'paid_earnest'          => '已经支付过诚意金',
    ],
];
