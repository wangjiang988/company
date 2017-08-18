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

   label{
   	font-weight: bolder;
   	font-size: 12px;
   	line-height: 25px;
   }
   .title {
   	margin: 15px;
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

</style>
<ul>
  <li><a href="index.php?act=commons_manage&op=basic_info&dl_id=<?php echo $output['daili_dealer_id'];?>">主要基本资料</a></li>
  <li><a href="index.php?act=commons_manage&op=other_info&dl_id=<?php echo $output['daili_dealer_id'];?>">其他基本资料</a></li>
  <li><a href="index.php?act=commons_manage&op=cars_list&id=<?php echo $output['daili_dealer_id'];?>" style="padding:6px;background:gray;color:white;">常用车型</a></li>
  <li><a href="index.php?act=commons_manage&op=file_manage&dl_id=<?php echo $output['daili_dealer_id'];?>">客户文件</a></li>
</ul>
<div class="main">
	<p>
		<label>车型:</label>
        <span><?php echo $output['carout'];?></span>
	</p>
    <p>
        <label>整车型号:</label>
        <span><?php echo $output['vehicle_model'];?></span>
    </p>
	<p>
	    <label>厂商指导价:</label>
        <span><?php echo '￥'.unserialize($output['price']['value']);?></span>
	</p>
	<p><label>原厂选装精品:</label></p>
	<div>
   <p class="title">出厂前装</p>
   <table border="1" width="100%">
	   <tr align="center" height="30px">
	      <td><b>名称</b></td>
	      <td><b>型号/说明</b></td>
	      <td><b>厂商编号</b></td>
	      <td><b>厂商指导价</b</td>
	      <td><b>单车可装件数</b</td>
	   </tr>
	   <?php if(count($output['fyc']['qz'])>0) {?>
	   <?php foreach($output['fyc']['qz'] as $qz) { ?>
	    <tr align="center" height="40px">
	     <td><?php echo $qz['xzj_title'];?></td>
	      <td><?php echo $qz['xzj_model'];?></td>
	      <td><?php echo $qz['xzj_cs_serial'];?></td>
        <td><?php echo '￥'.$qz['xzj_guide_price'];?></td>
	      <td><?php echo $qz['xzj_max_num'];?></td>
	   </tr>
	   <?php } } else {?>
	     <tr>
	        <td colspan="5">无</td>
	     </tr>
       <?php }?>
</table>
<p class="title">出厂后装</p>
<table border="1" width="100%">
   <tr align="center" height="30px">
      <td><b>名称</b></td>
      <td><b>型号/说明</b></td>
      <td><b>厂商编号</b></td>
      <td><b>厂商指导价</b</td>
      <td><b>安装费</b</td>
      <td><b>单车可装件数</b</td>
      <td><b>可供件数</b</td>
   </tr>
   <?php if(count($output['fyc']['hz'])>0) {?>
     <?php foreach($output['fyc']['hz'] as $hz) { ?>
      <tr align="center" height="40px">
       <td><?php echo $hz['xzj_title'];?></td>
        <td><?php echo $hz['xzj_model'];?></td>
        <td><?php echo $hz['xzj_cs_serial'];?></td>
        <td><?php echo '￥'.$hz['xzj_guide_price'];?></td>
        <td><?php echo '￥'.$hz['xzj_fee'];?></td>
        <td><?php echo $hz['xzj_max_num'];?></td>
        <td>
          <?php if ($hz['xzj_has_num'] == 0) {
            echo '不限';
          } else {
            echo $hz['xzj_has_num'];
          } ?>
        </td>
     </tr>
     <?php } } else {?>
       <tr>
          <td colspan="7" height="30px">无</td>
       </tr>
       <?php }?>
</table>
	</div>
	<div style="margin-top:15px;">
	<p><label>非原厂选装精品:</label></p>
	 <table border="1" width="100%">
   <tr align="center" height="30px">
      <td><b>品牌</b></td>
      <td><b>名称</b></td>
      <td><b>型号/说明</b></td>
      <td><b>厂商编号</b></td>
      <td><b>含安装价格</b></td>
      <td><b>单车可装件数</b</td>
      <td><b>可供件数</b</td>
   </tr>
    <?php if(count($output['fyc']['xz'])>0) {?>
     <?php foreach($output['fyc']['xz'] as $xz) { ?>
      <tr align="center" height="40px">
        <td><?php echo $xz['xzj_brand'];?></td>
        <td><?php echo $xz['xzj_title'];?></td>
        <td><?php echo $xz['xzj_model'];?></td>
        <td><?php echo $xz['xzj_cs_serial'];?></td>
        <td><?php echo '￥'.$xz['xzj_guide_price'];?></td>
        <td><?php echo $xz['xzj_max_num'];?></td>
        <?php if($xz['xzj_has_num'] == 0) { ?>
          <td>不限</td>
          <?php }else{ ?>
        <td><?php echo $xz['xzj_has_num'];?></td>
        <?php } ?>
     </tr>
     <?php } } else {?>
       <tr>
          <td colspan="7" height="30px">无</td>
       </tr>
       <?php }?>
</table>
  <div id="return">
 <button class="btn btnSub" style="width:200px" onclick="history.go(-1)"><b>返回</b></button>
</div>
	</div>
</div>
