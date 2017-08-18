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
  <table class="table tb-type2 order">
    <tbody>
    
      <tr class="space">
        <th colspan="2">结算文件详情</th>
      </tr>
      <tr>
        <td><ul>
            <li><strong>工单编号:</strong><?php echo $output['info']['id'];?></li>
            <li> <strong>&nbsp;</strong>&nbsp;</li>
            <li><strong>寄件用户名：</strong><?php echo $output['sellerInfo']['member_name'];?></li>
            <li><strong>用户姓名：</strong><?php echo $output['sellerInfo']['member_truename'];?></li>
            <li><strong>手机号码：</strong><?php echo $output['sellerInfo']['member_mobile'];?></li>
            <li>
            	<strong>当前售方可结算文件数：</strong><?php echo $output['sellerInfo']['wenjian'];?>&nbsp;&nbsp;
            	<a href="index.php?act=dealer_calc&op=show_log&seller_id=<?php echo $output['info']['seller_id'];?>">查看日志</a>
            </li>
            <li><strong>寄送时间:</strong><?php echo $output['info']['send_date'];?></li>
            <li> <strong>寄送文件数量:</strong><?php echo $output['info']['file_num'];?>&nbsp;</li>
            <li> <strong>快递名称:</strong><?php echo $output['info']['deliver'];?>&nbsp;</li>
            <li> <strong>运单号:</strong><?php echo $output['info']['deliver_num'];?>&nbsp;</li>
            <li> <strong>本次收到可用文件数量:</strong>&nbsp;
            <?php 
            if($output['info']['status']==0){
            	$value = $output['info']['file_num'];
            }else{
            	$value = $output['info']['sure_num'];
            }
            ?>
            <input type='text' value='<?php echo $value;?>' name='file_num' style='width:30px'>
             &nbsp;&nbsp; 
             <?php if($output['info']['status']==2){?>
             <a href="#" id='i-modify'>修改</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj' id='tj' value=' 提交 ' style="display:none;">
            <?php }?>
            </li>
            <li>
            
            <li>
            
            </li>
            
          </ul></td>
      </tr>
      <tr>
      <td>
      <?php if($output['info']['status']==0){?>
      <input type='button' value=' &nbsp;提交&nbsp;  '  id='tj' name='tj'>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type='button' value=' 撤销寄件信息 ' id='cx'>
      <?php }elseif($output['info']['status']==1){?>
       <input type='button' value=' 已撤销文件  '  >
      <?php }elseif($output['info']['status']==2){?>
      <input type='button' value=' 已收到文件  '  >
      <?php }?>
      </td>
      </tr>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#tj').click(function(){
        var _file_num = parseInt($('input[name=file_num]').val());
		if(window.confirm('确认收到的该快件中有'+_file_num+'份可用文件吗')){
			$.ajax({
		        url: "index.php",
		        type: "post",
		        dataType: "json",
		        data: {
		        	act:'dealer_calc',
					op:'sure_file',
					id:'<?php echo $output['info']['id'];?>',
					seller_id:'<?php echo $output['info']['seller_id'];?>',
					file_num:_file_num,
					status:'<?php echo $output['info']['status'];?>'
		        },
		        success: function (data) {
		            var _error_code = data.error_code;
		            var _error_msg = data.error_msg;
		            if(_error_code == 1){
		               alert(_error_msg);
		            }else{
						alert('更新成功');
						window.location.reload();
		            }
		        }
		        
			});
		}

     })

     $('#i-modify').click(function(){
     	$(this).hide();
     	$("input[name='tj']").show();
     });
    $('#cx').click(function(){
    	if(window.confirm('确定要撤销寄件信息吗')){
    		$.ajax({
		        url: "index.php",
		        type: "post",
		        dataType: "json",
		        data: {
		        	act:'dealer_calc',
					op:'cancel_file',
					id:'<?php echo $output['info']['id'];?>',
		        },
		        success: function (data) {
		            var _error_code = data.error_code;
		            var _error_msg = data.error_msg;
		            if(_error_code == 1){
		               alert(_error_msg);
		            }else{
						alert('更新成功');
						window.location.reload();
		            }
		        }
		        
			});
    		
    	}
    })
    
});
</script>
