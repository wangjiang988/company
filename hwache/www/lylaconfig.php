<?php
/**
 * 加载商城框架配置文件
 *
 * @author  技安 <php360@qq.com>
 * @link    http://www.moqifei.com
 */

// 定义加载商城系统配置文件合法常量
define('InHG', true);

// 定义商城配置文件绝对路径
define('MALL_DATA_PATH', dirname(BP) . DS . 'data' . DS);

// 加载系统配置文件
require_once MALL_DATA_PATH . 'config' . DS . 'config.ini.php';

// 加载基本配置文件
$base = require_once MALL_DATA_PATH . 'config' . DS . 'base.ini.php';

// 加载保险配置文件
$baoxian = require_once MALL_DATA_PATH . 'config' . DS . 'baoxian.ini.php';

// 加载商城系统缓存配置文件
$setting_config = require_once MALL_DATA_PATH. 'cache' . DS . 'setting.php';

// 加载车型缓存配置文件
$goods_class = require_once MALL_DATA_PATH. 'cache' . DS . 'goods_class.php';

// 加载订单配置文件
require_once MALL_DATA_PATH . 'config' . DS . 'order.ini.php';
require_once MALL_DATA_PATH . 'config' . DS . 'otherbaoxian.ini.php';
return [
    'config' => $config,
    'base' => $base,
    'baoxian' => $baoxian,
    'setting_config' => $setting_config,
    'goods_class'    => $goods_class,
];
