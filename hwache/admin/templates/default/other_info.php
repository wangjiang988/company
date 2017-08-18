
<?php defined('InHG') or exit('Access Invalid!');?>

<style type="text/css">
	table{  
  
   border-collapse:collapse; 
} 
#two {
  padding-top: 20px;
	margin: 0 auto;
	width: 98%
}

#two h5{
  margin-top: 15px;
  margin-bottom:15px;
  display: inline;
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
	<li><a href="index.php?act=commons_manage&op=basic_info&dl_id=<?php echo $output['daili_dealer_id'];?>">主要基本资料</a></li>
	<li><a href=""  style="padding:6px;background:gray;color:white;">其他基本资料</a></li>
	<li><a href="index.php?act=commons_manage&op=cars_list&id=<?php echo $output['daili_dealer_id'];?>">常用车型</a></li>
	<li><a href="index.php?act=commons_manage&op=file_manage&dl_id=<?php echo $output['daili_dealer_id'];?>">客户文件</a></li>
</ul>
<hr style="clear:both;">
<div id="two">
	<p><h5>服务专员</h5></p>
  <table border="1" width="100%">
   <tr align="center" height="30px">
    <td width="60px;"><b>编号</b></td>
    <td width="80px;"><b>姓名</b></td>
    <td><b>手机</b></td>
    <td><b>备用电话</b></td>
    <td><b>备注</b></td>
   </tr>
   <?php if(count($output['waiter'])>0) {?>
   <?php foreach($output['waiter'] as $waiters=>$waiter) { ?>
     <tr align="center" height="30px">
     <td><?php echo $waiters+'1';?></td>
      <td><?php echo $waiter['name'];?></td>
      <td><?php echo $waiter['mobile'];?></td>
      <td><?php echo $waiter['tel'];?></td>
      <td><?php echo $waiter['notice'];?></td>
   </tr>
   <?php } } else {?>
     <tr>
        <td colspan="5" height="30px">无</td>
     </tr>
   <?php }?>
</table>

<p><p><h5>首年商业保险条件:</h5>
<?php if(($output['type']['dl_baoxian']) == 1) {?>
<span>强制</span></p>
<?php }else{?>
  <span>自由办理</span></p>
  <?php }?>

<p><h5>保险公司:</h5></p>
  <table border="1" width="60%">
   <tr align="center" height="30px">
    <td><b>保险公司名称</b></td>
    <td><b>理赔范围</b></td>
   </tr>
   <?php if(count($output['baoxian'])>0) {?>
   <?php foreach($output['baoxian'] as $baoxian) { ?>
     <tr align="center" height="30px">
     <td><?php echo $baoxian['bx_title'];?></td>
      <td>
      <?php if($baoxian['bx_is_quanguo'] == 1) { echo '全国'; }?>
      <?php if($baoxian['bx_is_quanguo'] == 0) { echo '本地'; }?>       
      </td>
   </tr>
   <?php } } else {?>
     <tr>
        <td colspan="5" height="30px">无</td>
     </tr>
   <?php }?>
</table>
<table width="60%" style="margin-top:20px;">
  <tr height="30px">
    <td><b>代办上牌条件:</b>
    <?php if($output['data']['dl_shangpai'] == 1) {?>
    <span>强制</span>
    <?php }else{ ?>
      <span>自由办理</span>
      <?php } ?>
    </td>
    <td><b>代办上牌服务费:</b><span><?php echo '￥'.$output['data']['dl_shangpai_fee'];?></span></td>
  </tr>
  <tr height="30px">
    <td><b>客户本人上牌违约赔偿条件:</b><span>
      <?php if ($output['data']['dl_shangpai_object'] == 0) { echo '无要求';}else { echo '有要求';} ?>
    </span></td>
    <td><b>客户本人上牌违约赔偿:</b><span>
      <?php if ($output['data']['dl_shangpai_object'] == 1) { echo $output['data']['dl_shangpai_object_fee']; }?>
    </span></td>
  </tr>
  <tr height="30px">
    <td><b>代办临牌条件:</b><span>
      <?php if ($output['data']['dl_linpai'] == 0) { echo '客户自行办理';}else { echo '经销商代办';} ?>
    </span></td>
    <td><b>代办临牌(每次)服务费:</b><span>
      <?php echo '￥'.$output['data']['dl_linpai_fee'];?>
    </span></td>
  </tr>
</table>
<p><h5>免费提供:</h5></p>
  <table border="1" width="60%">
   <tr align="center" height="30px">
    <td><b>名称</b></td>
    <td><b>数量</b></td>
   </tr>
   <?php if(count($output['free'])>0) {?>
   <?php foreach($output['free'] as $free) { ?>
     <tr align="center" height="30px">
     <td><?php echo $free['title'];?></td>
      <td><?php echo $free['dl_zp_num'];?></td>
   </tr>
   <?php } } else {?>
     <tr>
        <td colspan="5" height="30px">无</td>
     </tr>
   <?php }?>
</table>

<p><h5>其他收费:</h5></p>
  <table border="1" width="60%">
   <tr align="center" height="30px">
    <td><b>费用名称</b></td>
    <td><b>金额</b></td>
   </tr>
   <?php if(count($output['zafei'])>0) {?>
   <?php foreach($output['zafei'] as $zafei) { ?>
     <tr align="center" height="30px">
     <td><?php echo $zafei['title'];?></td>
      <td><?php echo '￥'.$zafei['other_price'];?></td>
   </tr>
   <?php } } else {?>
     <tr>
        <td colspan="5" height="30px">无</td>
     </tr>
   <?php }?>
</table>
<p><h5>刷卡标准</h5></p>
<p>单车付款刷信用卡免费次数:
<?php if ($output['charge']['xyk_status'] == 0) { echo '不限';} else { ?>
<span><?php echo $output['charge']['xyk_number'];?>次,</span>超过次数收费:
<?php if ($output['charge']['xyk_per_num'] != 0) { ?>
刷卡金额的<span><?php echo $output['charge']['xyk_per_num'].'%';?></span>(百分之),
<?php } if($output['charge']['xyk_yuan_num'] != 0) {?>
每次<span><?php echo '￥'.$output['charge']['xyk_yuan_num'];?>元</span>(封顶);
<?php }?>
</p>
<?php } ?>
<p>单车付款刷借记卡免费次数:
<?php if ($output['charge']['jjk_status'] == 0) { echo '不限';} else { ?>
<span><?php echo $output['charge']['jjk_number'];?>次,</span>超过次数收费:
<?php if($output['charge']['jjk_per_num'] != 0) {?>
刷卡金额的<span><?php echo $output['charge']['jjk_per_num'].'%';?></span>(百分之),
<?php } if($output['charge']['jjk_yuan_num'] != 0) {?>
每次<span><span><?php echo '￥'.$output['charge']['jjk_yuan_num'];?>元</span>(封顶);
<?php }?>
</p>
<?php }?>
<p><h5>国家节能补贴</h5></p>
<?php if ($output['charge']['bt_status'] == 0) { echo '不提供'; } else { ?> 
<?php if($output['charge']['bt_work_day'] == 0) { ?>
  上牌资料齐全后，经销商将所有资料交给汽车厂商，厂商直接付给客户，或者（厂商付经销商再由） 
经销商付给客户，时限 <span><?php echo $output['charge']['bt_work_month'];?>个</span>月。
<?php } else { ?>
:经销商代办上牌的,交车上牌时当场约现;由客户本人上牌的,上牌资料齐全后,经销商垫付给客户,时限<span><?php echo $output['charge']['bt_work_day'];?>个</span>工作日. 
<?php } ?>
<?php }?>
<p><h5>地方政府置换补贴</h5></p>
<span>
<?php if($output['charge']['bt_gov'] == 1) { echo '可为客户提供协助';} else { echo '不提供'; } ?></span>
<p><h5>厂家或经销商置换补贴</h5></p>
<span>
<?php if($output['charge']['bt_factory'] == 1) { echo '有';} else { echo '无'; } ?>
</span>
<p><h5>工作时段</h5></p>
可以报价日期: 
<?php if ($output['work']['day_1'] == 1) { echo '周一,'; } ?>
<?php if ($output['work']['day_2'] == 1) { echo '周二,'; } ?>
<?php if ($output['work']['day_3'] == 1) { echo '周三,'; } ?>
<?php if ($output['work']['day_4'] == 1) { echo '周四,'; } ?>
<?php if ($output['work']['day_5'] == 1) { echo '周五,'; } ?>
<?php if ($output['work']['day_6'] == 1) { echo '周六,'; } ?>
<?php if ($output['work']['day_7'] == 1) { echo '周天,'; } ?>
<p>报价有效日期(上午):
<span><?php echo $output['work']['am_start'];?></span>~
<span><?php echo $output['work']['am_end'];?></span>
<span style="margin-left:97px;">报价有效日期(下午):</span>
<span><?php echo $output['work']['pm_start'];?></span>~
<span><?php echo $output['work']['pm_end'];?></span>
</p>
<span>不报价休息日程:1.
<?php echo $output['work']['rest_1_start'].'~'.$output['work']['rest_1_end'];?>
</span>
<span style="margin-left:40px;">2.
<?php echo $output['work']['rest_2_start'].'~'.$output['work']['rest_2_end'];?>
</span>
</div>
<div id="return">
 <button class="btn btnSub" style="width:200px"><a href="index.php?act=commons_manage&op=common_manage"><b>返回</b></a></button>
</div>