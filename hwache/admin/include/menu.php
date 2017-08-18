<?php
/**
 * 菜单
 */
defined('InHG') or exit('Access Invalid!');
/**
 * top 数组是顶部菜单 ，left数组是左侧菜单
 * left数组中'args'=>'welcome,dashboard,dashboard',三个分别为op,act,nav，权限依据act来判断
 */
$arr = array(
		'top' => array(
			0 => array(
				'args' 	=> 'dashboard',
				'text' 	=> $lang['nc_console']),
			1 => array(
                'args'	=> 'fwsetting',
                'text'	=> '系统'),
//			9 => array(
//                'args'	=> 'fwsetting',
//                'text'	=> '系统'),
			2 => array(
				'args' 	=> 'goods',
				'text' 	=> $lang['nc_goods']),
//			3 => array(
//				'args' 	=> 'store',
//				'text' 	=> $lang['nc_store']),
			4 => array(
				'args'	=> 'member',
				'text'	=> $lang['nc_member']),
			5 => array(
				'args' 	=> 'trade',
				'text'	=> $lang['nc_trade']),
//			6 => array(
//				'args'	=> 'website',
//				'text' 	=> $lang['nc_website']),
			7 => array(
				'args'	=> 'operation',
				'text'	=> $lang['nc_operation']),
//			8 => array(
//				'args'	=> 'stat',
//				'text'	=> $lang['nc_stat']),
		),
		'left' =>array(
			0 => array(
				'nav' => 'dashboard',
				'text' => $lang['nc_normal_handle'],
				'list' => array(
					array('args'=>'welcome,dashboard,dashboard',			'text'=>$lang['nc_welcome_page']),
					array('args'=>'aboutus,dashboard,dashboard',			'text'=>$lang['nc_aboutus']),
					array('args'=>'base,setting,dashboard',	'text'=>$lang['nc_web_set']),
					array('args'=>'member,member,dashboard',				'text'=>$lang['nc_member_manage']),
					array('args'=>'store,store,dashboard',					'text'=>$lang['nc_store_manage']),
					array('args'=>'goods,goods,dashboard',					'text'=>$lang['nc_goods_manage']),
					array('args'=>'index,order,dashboard',			        'text'=>$lang['nc_order_manage']),
				)
			),
//			1 => array(
//				'nav' => 'setting',
//				'text' => $lang['nc_config'],
//				'list' => array(
//					array('args'=>'base,setting,setting',			'text'=>$lang['nc_web_set']),
//					array('args'=>'qq,account,setting',		        'text'=>$lang['nc_web_account_syn']),
//					array('args'=>'param,upload,setting',			'text'=>$lang['nc_upload_set']),
//					array('args'=>'seo,setting,setting',			'text'=>$lang['nc_seo_set']),
//					array('args'=>'email,message,setting',			'text'=>$lang['nc_message_set']),
//					array('args'=>'system,payment,setting',			'text'=>$lang['nc_pay_method']),
//					array('args'=>'admin,admin,setting',			'text'=>$lang['nc_limit_manage']),
//					array('args'=>'index,express,setting',			'text'=>$lang['nc_admin_express_set']),
//					array('args'=>'index,offpay_area,setting',		'text'=>$lang['nc_admin_offpay_area_set']),
//					array('args'=>'clear,cache,setting',			'text'=>$lang['nc_admin_clear_cache']),
//					array('args'=>'perform,perform,setting',		'text'=>$lang['nc_admin_perform_opt']),
//					array('args'=>'search,search,setting',			'text'=>$lang['nc_admin_search_set']),
//					array('args'=>'list,admin_log,setting',			'text'=>$lang['nc_admin_log']),
//				)
//			),
			1 => array(
				'nav' => 'fwsetting',
				'text' => '系统',
				'list' => array(
//					array('args'=>'money,fwsetting,fwsetting',		'text'=>'系统金额设置'),
//					array('args'=>'baoxian,fwsetting,fwsetting',	'text'=>'保险公司管理'),
                    array('args'=>'list,area,fwsetting',	'text'=>'地区关联设置'),
					array('args'=>'index,area_manage,fwsetting',	'text'=>'地区管理'),
					array('args'=>'index,dealer,fwsetting',	'text'=>'经销商管理'),
					array('args'=>'index,provinces,fwsetting',	'text'=>'周边省份管理'),
					array('args'=>'index,centralcity,fwsetting',	'text'=>'中心城市管理'),
					array('args'=>'index,fields,fwsetting',	'text'=>'自定义字段'),
					array('args'=>'xzj,hgsoft,fwsetting',	'text'=>'选装件管理'),
					array('args'=>'zengpin,fwsetting,fwsetting',	'text'=>'赠品管理'),
					array('args'=>'suiche,fwsetting,fwsetting',	'text'=>'随车工具'),
					array('args'=>'files,fwsetting,fwsetting',	'text'=>'交车需要文件'),
//					array('args'=>'index,fwsetting,fwsetting',	'text'=>'杂费管理'), //TODO 暂时没有
//					array('args'=>'otherbaoxian,fwsetting,fwsetting',	'text'=>'其他商业保险'),
					array('args'=>'index,special_file,fwsetting',	'text'=>'特殊文件'),
                    array('args'=>'admin,admin,fwsetting',			'text'=>$lang['nc_limit_manage']),
                    array('args'=>'list,admin_log,fwsetting',			'text'=>$lang['nc_admin_log']),
                    array('args'=>'index,operation_log,fwsetting',			'text'=>'审批操作日志'),
                )
			),
			2 => array(
				'nav' => 'goods',
				'text' => $lang['nc_goods'],
				'list' => array(
					array('args'=>'goods_class,goods_class,goods',			'text'=>$lang['nc_class_manage']),
					//array('args'=>'brand,brand,goods',						'text'=>$lang['nc_brand_manage']),
					array('args'=>'common_manage,commons_manage,goods',					'text'=>$lang['nc_dealer_manage']),
					array('args'=>'index,parameter,goods',						'text'=>'报价审核'),
					array('args'=>'goods,goods,goods',						'text'=>$lang['nc_goods_manage']),
//					array('args'=>'type,type,goods',						'text'=>$lang['nc_type_manage']),
//					array('args'=>'spec,spec,goods',						'text'=>$lang['nc_spec_manage']),
//					array('args'=>'list,goods_album,goods',					'text'=>$lang['nc_album_manage']),
				)
			),
//			3 => array(
//				'nav' => 'store',
//				'text' => $lang['nc_store'],
//				'list' => array(
//					array('args'=>'store,store,store',						'text'=>$lang['nc_store_manage']),
//					array('args'=>'store_grade,store_grade,store',			'text'=>$lang['nc_store_grade']),
//					array('args'=>'store_class,store_class,store',			'text'=>$lang['nc_store_class']),
//					array('args'=>'store_domain_setting,domain,store',		'text'=>$lang['nc_domain_manage']),
//					array('args'=>'stracelist,sns_strace,store',			'text'=>$lang['nc_s_snstrace']),
//				)
//			),
			4 => array(
				'nav' => 'member',
				'text' => $lang['nc_member'],
				'list' => array(
					//array('args'=>'member,member,member',					'text'=>$lang['nc_member_manage']),
                    array('args'=>'list,new_user,member',					'text'=>'客户管理'),
                    array('args'=>'idcart,approve,member',					'text'=>'客户实名认证'),
                    array('args'=>'bank,approve,member',					'text'=>'客户银行认证'),
                    array('args'=>'fsr,new_user,member',					'text'=>'防骚扰管理'),
                    array('args'=>'register,new_user,member',				'text'=>'未注册手机号'),
//					array('args'=>'notice,notice,member',					'text'=>$lang['nc_member_notice']),
//					array('args'=>'addpoints,points,member',				'text'=>$lang['nc_member_pointsmanage']),
//					array('args'=>'predeposit,predeposit,member',			'text'=>$lang['nc_member_predepositmanage']),
//					array('args'=>'sharesetting,sns_sharesetting,member',	'text'=>$lang['nc_binding_manage']),
//					array('args'=>'class_list,sns_malbum,member',			'text'=>$lang['nc_member_album_manage']),
//					array('args'=>'tracelist,snstrace,member',				'text'=>$lang['nc_snstrace']),
//					array('args'=>'member_tag,sns_member,member',			'text'=>$lang['nc_member_tag']),
                    array('args'=>'list,seller,member',			            'text'=>'售方管理')

				)
			),
			5 => array(
				'nav' => 'trade',
				'text' => $lang['nc_trade'],
				'list' => array(
					array('args'=>'index,order,trade',				'text'=>$lang['nc_order_manage']),
//					array('args'=>'refund_manage,refund,trade',				'text'=>'退款管理'),
//					array('args'=>'return_manage,return,trade',				'text'=>'退货管理'),
//					array('args'=>'consulting,consulting,trade',			'text'=>$lang['nc_consult_manage']),
//					array('args'=>'inform_list,inform,trade',				'text'=>$lang['nc_inform_config']),
//					array('args'=>'evalgoods_list,evaluate,trade',			'text'=>$lang['nc_goods_evaluate']),
//					array('args'=>'complain_new_list,complain,trade',		'text'=>$lang['nc_complain_config']),
//					array('args'=>'index,zhengyi,trade',					'text'=>'争议管理'),
					array('args'=>'index,invoice,trade',					'text'=>'客户发票管理'),
					array('args'=>'index,dealer_calc,trade',				'text'=>'售方发票管理'),
//					array('args'=>'index,jiaoche,trade',				'text'=>'交车信息管理'),
//					array('args'=>'index,butie,trade',				    'text'=>'交车附加信息'),
					array('args'=>'index,Finance,trade',				'text'=>'客户财务管理'),
                    array('args'=>'index,seller_finance,trade',			'text'=>'售方财务管理'),
                    array('args'=>'index,admin_finance,trade',		'text'=>'平台财务管理'),
					array('args'=>'index,work_sheet,trade',		'text'=>'工单管理'),
				)
			),
//			6 => array(
//				'nav' => 'website',
//				'text' => $lang['nc_website'],
//				'list' => array(
//					array('args'=>'article_class,article_class,website',	'text'=>$lang['nc_article_class']),
//					array('args'=>'article,article,website',				'text'=>$lang['nc_article_manage']),
//					array('args'=>'document,document,website',				'text'=>$lang['nc_document']),
//					array('args'=>'navigation,navigation,website',			'text'=>$lang['nc_navigation']),
//					array('args'=>'ap_manage,adv,website',					'text'=>$lang['nc_adv_manage']),
//					array('args'=>'web_config,web_config,website',			'text'=>$lang['nc_web_index']),
//					array('args'=>'rec_list,rec_position,website',			'text'=>$lang['nc_admin_res_position']),
//				)
//			),
			7 => array(
				'nav' => 'operation',
				'text' => $lang['nc_operation'],
				'list' => array(
//					array('args'=>'setting,operation,operation',			    'text'=>$lang['nc_operation_set']),
//					array('args'=>'groupbuy_template_list,groupbuy,operation',	'text'=>$lang['nc_groupbuy_manage']),
//					array('args'=>'xianshi_apply,promotion_xianshi,operation',	'text'=>$lang['nc_promotion_xianshi']),
//					array('args'=>'mansong_apply,promotion_mansong,operation',	'text'=>$lang['nc_promotion_mansong']),
//					array('args'=>'bundling_list,promotion_bundling,operation',	'text'=>$lang['nc_promotion_bundling']),
//					array('args'=>'goods_list,promotion_booth,operation',		'text'=>$lang['nc_promotion_booth']),
					array('args'=>'index,hc_vouchers,operation',                'text'=>$lang['nc_voucher_price_manage']),
//					array('args'=>'index,bill,operation',					    'text'=>$lang['nc_bill_manage']),
//					array('args'=>'activity,activity,operation',				'text'=>$lang['nc_activity_manage']),
//					array('args'=>'pointprod,pointprod,operation',				'text'=>$lang['nc_pointprod']),
				)
			),
//			8 => array(
//				'nav' => 'stat',
//				'text' => $lang['nc_stat'],
//				'list' => array(
//					array('args'=>'newmember,stat_member,stat',			'text'=>$lang['nc_statmember']),
//					array('args'=>'newstore,stat_store,stat',			'text'=>$lang['nc_statstore']),
//					array('args'=>'goods,stat_trade,stat',				'text'=>$lang['nc_stattrade']),
//					array('args'=>'promotion,stat_marketing,stat',		'text'=>$lang['nc_statmarketing']),
//					array('args'=>'refund,stat_aftersale,stat',	'text'=>$lang['nc_stataftersale']),
//				)
//			)
		)
);
if(C('mobile_isuse')){
	$arr['top'][] = array(
				'args'	=> 'mobile',
				'text'	=> $lang['nc_mobile']);
	$arr['left'][] = array(
				'nav' => 'mobile',
				'text' => $lang['nc_mobile'],
				'list' => array(
					array('args'=>'mb_ad_list,mb_ad,mobile',				'text'=>$lang['nc_mobile_adset']),
					array('args'=>'mb_home_list,mb_home,mobile',			'text'=>$lang['nc_mobile_homeset']),
					array('args'=>'mb_category_list,mb_category,mobile',	'text'=>$lang['nc_mobile_catepic']),
                    //前台还没坐反馈，暂时隐藏
					//array('args'=>'flist,mb_feedback,mobile',					'text'=>$lang['nc_mobile_feedback'])
				)
			);
}

return $arr;
