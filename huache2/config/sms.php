<?php
/**
 * 短信配置文件
 *
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 */

return [
    // 短信验证码默认有效期，单位(分钟)
    'sms_time_limit'    => 10,

    // 默认短信接口
    'sms'               => 'alidayu',

    // 阿里大鱼短信接口参数
    'alidayu'           => [
        'app_key'       => 23313355,
        'app_secret'    => '6760fc251692015cb371d52ef538aef8',
        'template'      => [
            'reg'           => 'SMS_5023974', // 用户注册验证码
            'status'        => 'SMS_5023977', // 身份验证验证码
            'login'         => 'SMS_5023976', // 登录确认验证码
            'login_other'   => 'SMS_5023975', // 登录异常验证码
            'exercise'      => 'SMS_5023973', // 活动确认验证码
            'change_pwd'    => 'SMS_5023972', // 修改密码验证码
            'change_info'   => 'SMS_5023971', // 信息变更验证码
            's_notice'      => 'SMS_70090052',//结算文件不多提示
            's_warning'     => 'SMS_70010020', //结算文件用完警告
        ], // 短信模板
        'sign'          => [
            'reg'           => '注册验证',
            'status'        => '身份验证',
            'login'         => '登录验证',
            'exercise'      => '活动验证',
            'change_info'   => '变更验证',
            's_notice'      => '活动验证',
            's_warning'     => '活动验证',
        ], // 短信签名
    ],

];