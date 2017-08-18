<?php
defined('InHG') or exit('Access Invalid!');
$config = array();
$config['www_site_url']                     = 'http://www.hwache.com';
$config['api_site_url']                     = 'http://www.hwache.com/api';
$config['shop_site_url']                    = 'http://user.hwache.com';
$config['admin_site_url']                   = 'http://admin.hwache.com';
$config['upload_site_url']                  = 'http://upload.hwache.com';
$config['resource_site_url']                = 'http://resource.hwache.com';
$config['cms_site_url']                     = 'http://cms.hwache.com';
$config['microshop_site_url']               = 'http://microshop.hwache.com';
$config['circle_site_url']                  = 'http://circle.hwache.com';
$config['mobile_site_url']                  = 'http://mobile.hwache.com';
$config['wap_site_url']                     = 'http://wap.hwache.com';
$config['version']                          = '201401162490';
$config['setup_date']                       = '2015-03-13 14:23:17';
$config['gip']                              = 0;
$config['dbdriver']                         = 'mysqli';
$config['tablepre']                         = 'car_';
$config['db'][1]['dbhost']                  = '120.27.147.99';
$config['db'][1]['dbport']                  = '3306';
$config['db'][1]['dbuser']                  = 'hwache';
$config['db'][1]['dbpwd']                   = 'YRVMd6dj0qgGg7PU';
$config['db'][1]['dbname']                  = 'hwache_web';
$config['db'][1]['dbcharset']               = 'UTF-8';
$config['db']['slave']                      = array();
$config['session_expire']                   = 3600;
$config['lang_type']                        = 'zh_cn';
$config['cookie_pre']                       = '75E7_';
$config['tpl_name']                         = 'default';
$config['thumb']['cut_type']                = 'gd';
$config['thumb']['impath']                  = '';
$config['cache']['type']                    = 'file';
//$config['memcache']['prefix']               = 'nc_';
//$config['memcache'][1]['port']              = 11211;
//$config['memcache'][1]['host']              = '127.0.0.1';
//$config['memcache'][1]['pconnect']          = 0;
$config['redis']['prefix']                  = 'ad_';
$config['redis']['master']['port']          = 6379;
$config['redis']['master']['host']          = '120.27.147.99';
$config['redis']['master']['pconnect']      = 0;
$config['redis']['slave']                   = array();
//$config['fullindexer']['open']              = false;
//$config['fullindexer']['appname']           = 'andy';
$config['debug']                            = false;
$config['default_store_id']                 = '1';
// 是否开启伪静态
$config['url_model']                        = false;
// 二级域名后缀
$config['subdomain_suffix']                 = '';
// 公司
$config['company']                          = '苏州华车网络科技有限公司';
// 分页
$config['pagesize']                         = 15;
//add wangjiang
// 默认用户名
$config['default_username']                 = "未命名";

//添加操作日志类型
$config['operation_type'] =[
    "withdraw_line"   =>  11,
];
//end