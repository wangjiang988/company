<?php defined('InHG') or exit('Access Invalid!');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/admincp.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/axios.min.js" charset="utf-8"></script>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/skin_0.css" rel="stylesheet" type="text/css" id="cssfile2" />
<link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/pure/pure-min.css">
<link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/font-awesome-4.7.0/css/font-awesome.min.css">

    <script type="text/javascript">
var PROXY_URL = '<?php echo urlencode(ADMIN_SITE_URL);?>';
var SITEURL = '<?php echo SHOP_SITE_URL;?>';
var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
var MICROSHOP_SITE_URL = '<?php echo MICROSHOP_SITE_URL;?>';
var CIRCLE_SITE_URL = '<?php echo CIRCLE_SITE_URL;?>';
var ADMIN_TEMPLATES_URL = '<?php echo ADMIN_TEMPLATES_URL;?>';
var LOADING_IMAGE = "<?php echo ADMIN_TEMPLATES_URL.DS.'images/loading.gif';?>";
//换肤
var cookie_skin = $.cookie("MyCssSkin");
if (cookie_skin) {
	$('#cssfile2').attr("href","<?php echo ADMIN_TEMPLATES_URL;?>/css/"+ cookie_skin +".css");
}
</script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php
	require_once($tpl_file);
?>
<?php if ($GLOBALS['setting_config']['debug'] == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div>
      <?php print_r(Tpl::showTrace());?>
    </div>
  </fieldset>
</div>
<?php }?>
</body>
</html>
