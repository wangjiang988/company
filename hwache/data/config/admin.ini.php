<?php
/**
 * Created by wangjiang
 * 后台配置文件
 * Time: 17:47
 */
defined ( 'InHG' ) or exit ( 'Access Invalid!' );

return [
    'max_transfer_to_user_account'  => 10000.00, //转入，是从平台转到客户可用余额，单笔不超过10000元。
    'admin_banks' => ['泰隆银行','招商银行'],
];
