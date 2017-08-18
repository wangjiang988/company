<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <h3>发票管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=dealer_calc&op=index&i_type=1" class="<?php if($_GET['i_type']==1){echo 'current';}?>"><span>未收到文件</span></a></li>
        <li><a href="index.php?act=dealer_calc&op=index&i_type=2" class="<?php if($_GET['i_type']==2){echo 'current';}?>"><span>已收到文件</span></a></li>
        <li><a href="index.php?act=dealer_calc&op=index&i_type=3" class="<?php if($_GET['i_type']==3){echo 'current';}?>"><span>已撤销文件</span></a></li>
        <li><a href="index.php?act=dealer_calc&op=invoice_list&i_type=4&invoice_type=undo" class="<?php if($_GET['i_type']==4){echo 'current';}?>"><span>未完成开票</span></a></li>
        <li><a href="index.php?act=dealer_calc&op=invoice_list&i_type=4&invoice_type=done" class="<?php if($_GET['i_type']==5){echo 'current';}?>"><span>已完成开票</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form action='' method='post'>
  <table class="table tb-type2 order">
    <tbody>
    
      <tr class="space">
        <th colspan="2">结算详情</th>
      </tr>
      <tr>
        <td><ul>
            <li><strong>工单编号:</strong><?php echo $output['info']['id'];?></li>
            <li> <strong>&nbsp;</strong>&nbsp;</li>
            <li><strong>寄件用户名：</strong><?php echo $output['info']['member_name'];?></li>
            <li><strong>用户姓名：</strong><?php echo $output['info']['member_truename'];?></li>
            <li><strong>手机号码：</strong><?php echo $output['info']['member_mobile'];?></li>
            <li><strong>订单编号：</strong><?php echo $output['info']['order_num'];?></li>
            <li><strong>同意结算时间:</strong><?php echo $output['info']['pdi_calc_date'];?></li>
            <li> <strong>结算金额:</strong><?php echo $output['info']['file_num'];?>&nbsp;</li>
            
            <li>
            
            <li>
            
            </li>
            
          </ul></td>
      </tr>
      <?php if(empty($output['inv_info'])){?>
      <tr class="space"><th colspan="2">结算文件</th></tr>
      <tr><td>
      <ul>
      	<li><strong>当前售方可用结算文件数:</strong><?php echo $output['info']['calc_file'];?>
      	&nbsp;&nbsp;<a href="index.php?act=dealer_calc&op=show_log&seller_id=<?php echo $output['info']['seller_id'];?>">查看日志</a>
      	</li>
        <!--
        <li> <strong>&nbsp;</strong>&nbsp;</li>
        <li><strong>正在办理结算文件数：</strong><?php echo $output['info']['calc_file'];?></li>
        <li> <strong>&nbsp;</strong>&nbsp;</li>
        <li><strong>在手可用文件数量：</strong><?php echo $output['info']['calc_file'];?></li>
      	  -->
      </ul>
      </td></tr>
      
      <tr class="space"><th colspan="2">开票信息</th></tr>
      <tr><td>
      <ul>
      	<li><strong>劳务发票编号:</strong><input type='text' name='inv_no' value=''></li>
        <li><strong>备注：</strong><input type='text' name='note' value='' style="width:300px"></li>
      </ul>
      </td></tr>
      <tr>
      <td>
      <input type='button' value=' &nbsp;提交&nbsp;  '  id='tj' name='tj'>
      </td>
      </tr>
      <?php }else{?>
      <tr class="space"><th colspan="2">开票信息</th></tr>
      <tr><td>
      <ul>
      	<li><strong>劳务发票编号:</strong><?php echo $output['inv_info']['inv_no'];?></li>
        <li><strong>备注：</strong><?php echo $output['inv_info']['note'];?></li>
        <li><strong>劳务发票开票人：</strong><?php echo $output['inv_info']['op_name'];?></li>
        <li><strong>开票提交时间：</strong><?php echo $output['inv_info']['date'];?></li>
      </ul>
      </td></tr>
      <?php }?>
      
  </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#tj').click(function(){
        var _note = $('input[name=note]').val();
        var _inv_no = $('input[name=inv_no]').val();
        if(_inv_no == ''){
            alert('劳务发票不能为空');
            return false;
        }
		if(window.confirm('确认提交此劳务发票吗')){
			$.ajax({
		        url: "index.php",
		        type: "post",
		        dataType: "json",
		        data: {
		        	act:'dealer_calc',
					op:'edit_inv',
					id:'<?php echo $_GET['inv_id'];?>',
					seller_id:'<?php echo $output['info']['seller_id'];?>',
					order_num:'<?php echo $_GET['order_num'];?>',
					inv_no:_inv_no,
					note:_note,
					inv_money:'<?php echo $output['info']['inv_money'];?>',
		        },
		        success: function (data) {
		            var _error_code = data.error_code;
		            var _error_msg = data.error_msg;
		            if(_error_code == 1){
		               alert(_error_msg);
		            }else{
						alert('更新成功');
						var _url = "index.php?act=dealer_calc&op=show_invoice&order_num=<?php echo $_GET['order_num']?>&inv_id="+data.inv_id;
						setTimeout('window.location.href="'+_url+'"',2000);
		            }
		        }
		        
			});
		}

     })

     $('#i-modify').click(function(){
     	$(this).hide();
     	$("input[name='tj']").show();
     });
    
    
});
</script>
