<?php
/**
 * 短信验证码
 * 使用同一个签名，对同一个手机号码发送短信验证码，支持1条/分钟，累计7条/小时
 * 短信通知
 * 使用同一个签名和同一个短信模板ID，对同一个手机号码发送短信通知，支持50条/日（指自然日）
 */
return [

    /*
    |--------------------------------------------------------------------------
    | App Key
    |--------------------------------------------------------------------------
    |
    | This value is the key of your aliday's application.
    |
    */

    'app_key' => '23313355',

    /*
    |--------------------------------------------------------------------------
    | App Secret
    |--------------------------------------------------------------------------
    |
    | This value is the secret of your aliday's application.
    |
    */

    'app_secret' => '6760fc251692015cb371d52ef538aef8',

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your service is currently
    | running in. This may determine how you prefer to configure the
    | service your application utilizes. The avaliable value can be 'production' or 'sandbox'.
    |
    */

    'env' => 'production',

    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your serivce, which
    | will be used by the PHP date and date-time functions.
    |
    */

    'timezone' => 'PRC',
    
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
        ],
    'sign'          => [
        'reg'           => '注册验证',
        'status'        => '身份验证',
        'login'         => '登录验证',
        'login_other'   => '登录异常',
        'exercise'      => '活动验证',
        'change_pwd'    => '修改密码',
        'change_info'   => '变更验证',
        's_notice'      => '活动验证',
        's_warning'     => '活动验证',
    ], // 短信签名

    //短信发送白名单
    'white_list' =>  [
       '18626210573',
//        '18020278432',
        //'13774220041',
        '13073316920',
        '15821852190'
    ]
];
