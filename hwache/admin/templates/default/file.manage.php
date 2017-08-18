
<?php defined('InHG') or exit('Access Invalid!');?>

<style type="text/css">
	table{  
  
   border-collapse:collapse; 
} 


   ul{
   	margin-left: 60px;
   }

   ul li{
   	float:left;
   	padding:13px;
   	font-weight: bolder;
   }
   th{
   	width: 0px;
   }

   p{
    padding: 5px 0;
   }
   span{
    padding-left:5px;
   }
   #first{
   	clear:both;
   	margin: 60px 0 20px 85px;
   }
   #first select{
   	width: 200px;
   }
   #two {
   	margin-left:60px;
   }
   #two select{
   	width: 300px;
   }
   #file{
   	width: 800px;
   	margin-top: 40px;
   	border:1px solid red;
   	margin-left: 60px;
   	height: 300px;
   }
   #file div {
   	float:left;
   	border:1px soldi red;
   }
   table{
   	margin-top:30px;
   	margin-left:60px;
   }
    .btn{
    width: 50px;
    height: 30px;
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 5px 0px;
    text-align: center;
    }
.btnSub{
    background-color: #CFEEF0;
   }
   #return{
    margin-top: 20px;
    margin-bottom: 20px;
    width: 100%
   }
   #return button{
    display: block;
    margin:0 auto;
   }
   #return button a{
    display: block;
   }
</style>
<ul>
	<li><a href="index.php?act=commons_manage&op=basic_info&dl_id=<?php echo $output['dealer_id'];?>">主要基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=other_info&dl_id=<?php echo $output['dealer_id'];?>">其他基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=cars_list&id=<?php echo $output['dealer_id'];?>">常用车型</a></li>
	<li><a href="" style="padding:6px;background:gray;color:white;">客户文件</a></li>
</ul>
<form action="index.php" method="get" id="form">
<input type="hidden" name="act" value="commons_manage">
<input type="hidden" name="op" value="file_manage">
<input type="hidden" name="dl_id" value="<?php echo $output['dealer_id'];?>">
<input type="hidden" name="query" value="1">
<div id="first">
<span>车辆用途:</span>
	<select id="type" class="querySelect" name="type_id">
	    <option>请选择</option>
		<option value="0">非营业个人客车</option>
		<option value="1">非营业企业客车</option>
	</select>
</div>
<div id="two">
	<span>上牌车主身份类别:</span>
	<select name="category" id="category">
	<option>请选择车主身份类别</option>
	</select>
</div>
</form>
<div id="content"></div>
<?php require_once('file.manage.table.php');?>
<div id="return">
 <button class="btn btnSub" style="width:200px"><a href="index.php?act=commons_manage&op=common_manage"><b>返回</b></a></button>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
	$('#type').change(function(){
		var _html = '';
        if ($(this).val() == 0) {
        _html ='<option value="0">上牌地本市户籍居民</option><option value="1">国内其他非限牌城市户籍居民</option><option value="2">国内限牌城市（上海）户籍居民</option><option value="3">国内限牌城市（北京）户籍居民</option><option value="4">国内限牌城市(广州）户籍居民</option><option value="5">国内限牌城市（天津）户籍居民</option><option value="6">国内限牌城市（杭州）户籍居民</option><option value="7">内限牌城市（贵阳）户籍居民</option><option value="8">国内限牌城市（深圳）户籍居民</option><option value="9">中国军人</option><option value="10">非中国大陆人士（外籍人士）</option><option value="11">非中国大陆人士（台胞）</option><option value="12">大陆人士（港澳人士）</option><option value="13">非中国大陆人士（持绿卡华侨</option>';
        }
        if ($(this).val() == 1) {
        	_html = '<option value="0">上牌地本市注册企业（增值税一般纳税人）</option> <option value="1">上牌地本市注册企业（小规模纳税人）</option>';
        }
        $("#category").find('option').slice(1).remove();
        $("#category").append(_html);
	})
    $(document).delegate("#category","change",function(){
    getFileTable();
	})

    //查询数据
    function getFileTable()
    {
    $.ajax({
    url: "/index.php?"+$("#form").serialize(),
    datatype:'json',
    }).done(function (result) {
      console.log(result);
    $('#rmcontent').remove();
    $("#content").after(result);
    })
    }
</script>