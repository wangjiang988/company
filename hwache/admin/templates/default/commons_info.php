<?php defined('InHG') or exit('Access Invalid!');?>

<style type="text/css">
	table{  
  
   border-collapse:collapse; 
} 
#two {
	margin: 0 auto;
	width: 98%
}

   #two td{
	border:1px solid gray;
   }
   #two thead{
   	margin:20px;
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
	<li><a href="" style="padding:6px;background:gray;color:white;">主要基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=other_info&dl_id=<?php echo $output['dealer']['id'];?>">其他基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=cars_list&id=<?php echo $output['dealer']['id'];?>">常用车型</a></li>
	<li><a href="index.php?act=commons_manage&op=file_manage&dl_id=<?php echo $output['dealer']['id'];?>">客户文件</a></li>
</ul>
<table class="table tb-type2" style="margin:15px;">
  <tbody>
  <tr>
    <td>UBS编号：</td>
    <td><?php echo $output['dealer']['id']; ?></td>
    <td>审核状态：</td>
    <td>
    <?php 
    if($output['dealer']['dl_status'] == 1) {
            echo "待审核";
          } 
    if($output['dealer']['dl_status'] == 2) {
             echo "审核通过";
          }
    if($output['dealer']['dl_status'] == 4) {
       echo "审核不通过";
    }
    ?></td>
    <td></td>
    <td></td>

  </tr>
  <tr>
  	<td align="left" style="text-align:left;">用户名：</td>
	<td align="left" style="text-align:left"><?php echo $output['users']['member_name']; ?>
   <a href="index.php?act=seller&op=view&id=<?=$output['seller']['seller_id']?>" style="margin-left:20px;">查看售方资料</a> 
  </td>
	<td align="left" style="text-align:left">用户姓名/手机号：</td>
	<td align="left" style="text-align:left"><?php echo $output['users']['member_truename'].'/'.$output['users']['member_mobile']; ?></td>
  </tr>
	<tr>
  	<td align="left" style="text-align:left;">品牌：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['gc_name']; ?></td>
	<td align="left" style="text-align:left">归属地区：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_areainfo']; ?></td>
	<td align="left" style="text-align:left">经销商名称：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_name'] ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">经销商编号：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['d_id']; ?></td>
	<td align="left" style="text-align:left">类别：</td>
	<td align="left" style="text-align:left">4S</td>
	<td align="left" style="text-align:left">经销商简称：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['d_shortname']; ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">营业地点：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_yy_place']; ?></td>
	<td align="left" style="text-align:left">交车地点：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_jc_place']; ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">开户行：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['dl_bank_addr']; ?></td>
	<td align="left" style="text-align:left">账号：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['dl_bank_account']; ?> </td>
	<td align="left" style="text-align:left">统一社会信用代码</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['dl_code'];?></td>
	</tr>
  </tbody>
</table>
<h3 style="color:gray; margin:15px;">竞争分析:</h3>
<table class="table tb-type2" id="two">
  <tbody>
  <tr>
  	<td width="25%">地区</td>
  	<td width="35%">经销商名称</td>
  	<td width="40%">营业地点</td>
  </tr>
  <?php foreach($output['contend'] as $contend) { ?>
	<tr>
  	 <td><?php echo $contend['d_areainfo'];?></td>
  	<td><?php echo $contend['d_name'];?></td>
  	<td><?php echo $contend['d_yy_place'];?></td>
	</tr>
  <?php } ?>
	
  </tbody>
</table>
<div id="return">
 <button class="btn btnSub" style="width:200px"><a href="index.php?act=commons_manage&op=common_manage"><b>返回</b></a></button>
</div>
