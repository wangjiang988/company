<?php defined('InHG') or exit('Access Invalid!');?>

<style type="text/css">
	.main{
		clear:both;
		margin: 50px;
		border-top:1px solid gray;
		padding: 20px;
	}

   ul{
   	margin-left: 60px;
   }

   ul li{
   	float:left;
   	padding:13px;
   	font-weight: bolder;
   }
   .heads{
      margin-left: 20px;
      margin-bottom: 20px;
   }
   .heads span {
      padding-right: 20px;
   }
   select{
      width: 300px;
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
    margin-top: 10px;
    margin-bottom: 30px;
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
	<li><a href="index.php?act=commons_manage&op=basic_info&dl_id=<?php echo $output['daili_dealer_id'];?>">主要基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=other_info&dl_id=<?php echo $output['daili_dealer_id'];?>">其他基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=cars_list&id=<?php echo $output['daili_dealer_id'];?>" style="padding:6px;background:gray;color:white;">常用车型</a></li>
	<li><a href="index.php?act=commons_manage&op=file_manage&dl_id=<?php echo $output['daili_dealer_id'];?>">客户文件</a></li>
</ul>
<div class="main">
<div class="heads">
<span>车系:</span>
<form action="" id="forms" method="get">
    <input type="hidden" name="act" value="commons_manage">
    <input type="hidden" name="op" value="cars_list">
    <input type="hidden" name="id" value="<?php echo $output['daili_dealer_id'];?>">
   <select name="order_name" class="querySelect">
             <?php if (empty($output['gc_name'])) { ?>
                 <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <?php foreach($output['car_list'] as $car_list) {?>
              <option value="<?php echo $car_list['gc_name'];?>"><span><?php echo $car_list['gc_name']; ?></span></option>
              <?php } } else {?>
               <option value="<?php echo $output['gc_name'];?>"><span><?php echo $output['gc_name']; }?></span></option>
   </select>
</form>
</div>
     <?php require_once("lists_car_table.php");?>
</div>
<div id="return">
 <button class="btn btnSub" style="width:200px"><a href="index.php?act=commons_manage&op=common_manage"><b>返回</b></a></button>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript">
   $('.querySelect').change(function(){
      Carlist();
   })

       function Carlist()
    {
        $.ajax({
            url: "/index.php?"+$("#forms").serialize(),
            datatype:'json',
        }).done(function (result) {
            $(".table").remove();
            $(".heads").after(result);
        })
    }
</script>