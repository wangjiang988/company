<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <h3>交车附加信息管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=butie&op=index&d_type=1" <?php if($_GET['d_type']=='1'){echo "class=current";}?>><span>国家节能补贴待发放</span></a></li>
        <li><a href="index.php?act=butie&op=index&d_type=2" <?php if($_GET['d_type']=='2'){echo "class=current";}?>><span>国家节能补贴发放审核</span></a></li>
        <li><a href="index.php?act=butie&op=index&d_type=3" <?php if($_GET['d_type']=='3'){echo "class=current";}?>><span>国家节能补贴发放完成</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2 order">
    <tbody>
    
      <tr class="space">
        <th colspan="2">交车信息详情</th>
      </tr>
      <tr>
        <td><ul>
            <li><strong>工单编号:</strong><?php echo $output['info']['id'];?></li>
            <li> <strong>交车信息提交时间:</strong><?php echo $output['info']['create_at'];?></li>
            <li><strong>订单编号：</strong><?php echo $output['info']['order_num'];?></li>
            <li>&nbsp;</li>
            
            <li><strong>客户姓名：</strong><?php echo $output['allUserInfo']['buyer']['member_name'];?></li>
            <li><strong>客户称呼：</strong><?php echo $output['allUserInfo']['buyer']['member_truename'];?></li>
            <li><strong>客户电话：</strong><?php echo $output['allUserInfo']['buyer']['member_mobile'];?></li>
            <li>&nbsp;</li>
            <li><strong>售方用户名：</strong><?php echo $output['allUserInfo']['seller']['seller_name'];?></li>
            <li><strong>售方真实姓名：</strong><?php echo $output['allUserInfo']['seller']['member_truename'];?></li>
            <li><strong>售方电话：</strong><?php echo $output['allUserInfo']['seller']['member_mobile'];?></li>
            <li>&nbsp;</li>
            
            <li>
           		 <strong>国家节能补贴金额：</strong><?php echo $output['info']['bj_butie']; ?>
            </li>
            <li>
            	<strong>发放约定时间：</strong><?php echo $output['info']['pdi_butie_date']; ?>
            </li>
            <li>
           		 <strong>冻结保证金金额：</strong><?php echo $output['info']['bj_butie']; ?>
            </li>
            <li>
            	<strong>冻结保证金时间：</strong><?php echo ''; ?>
            </li>
            <?php 
		      //$output['info']['pdi_butie_fafang'] = '';
		      if (!empty($output['info']['pdi_butie_fafang'])){//如果售方没有提交发放时间
		      ?>
            <li>
           		 <strong>售方发放提交时间：</strong><?php echo $output['info']['pdi_butie_fafang']; ?>
            </li>
            <li>
            	<strong>售方发放证明资料：</strong><a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/'.$output['info']['pdi_butie_file'];?>" target="_bank"><?php echo ltrim($output['info']['pdi_butie_file'],'file/'); ?></a>
            </li>
            <?php }?>
          </ul></td>
      </tr>
      <?php 
      if (empty($output['info']['pdi_butie_fafang'])){//如果售方没有提交发放时间
      	exit;
      }
      ?>
      <tr class='space'><th colspan=2>平台核实</th></tr>
      <tr>
      	<td colspan=2>
      		<table style="width: 100%">
      			<tr><th>编号</th><th>内容</th><th>证据</th><th>备注人</th><th>备注时间</th></tr>
      			<?php 
      			if(count($output['note'])>0){
      				foreach($output['note'] as $note){	
      			?>
      			<tr>
      				<td><?php echo $note['id'];?></td>
      				<td><?php echo $note['note'];?></td>
      				<td>
      				<?php 
      				if(!empty($note['file'])){
      					$files = unserialize($note['file']);
      					foreach($files as $k=>$file){
      						echo '<a href="'.$GLOBALS['config']['www_site_url'].'/upload/'.$file.'" target="_bank">'.ltrim($file,"file/").'</a>&nbsp;&nbsp;<a href="javascript:del_jiaoche_butie_file('.$note['id'].','.$k.')">取消上传</a><br>';
      					}
      				}
      				?>
      				
      				</td>
      				<td><?php echo $note['noter'];?></td>
      				<td><?php echo $note['date'];?></td>
      			</tr>
      			<?php 
      				}
      			}
      			?>
      			<tr>
      				<td colspan=4>
      				<form action="index.php?act=butie&op=ajax_sub" method='post' name='item-form-butie' enctype="multipart/form-data">
      				备注：<br><br>
      				<textarea name='note' style='width:80%;height:50px;'></textarea><br><br>
      				证据：<br>
      	  			<input type='file' name='file_1'>
      	  			<input type='button' value='新增' id='add-for-butie-file'>
      	  			<br><br>
      				<input type='button' name='add-note' value='提交备注'>
      				<input type="hidden" name="act" value="butie" />
			    	  <input type="hidden" name="op" value="ajax_sub" />
			    	  <input type="hidden" name="id" value="<?php echo $output['info']['id']?>" />
			    	  <input type="hidden" name="order_num" value="<?php echo $output['info']['order_num']?>" />
			    	  <input type="hidden" name="type" value="1" />
      				</td>
      			</tr>
      		</table>
      	</td>
      </tr>
      <tr id='add-hw-check' style="display:none">
      	<td colspan=2>
      	<table width='100%'>
      	<tr class='space'><th>判定结果</th></tr>
      		<tr>
      			<td>
			      	<form>
			      	判定结论：售方违约<br>
			      	判定依据：<br>
			      	<textarea rows="10" cols="100" name='reason'></textarea><br><br>
			      	执行（客户）：<input type='checkbox' checked disabled>获得国家节能补贴发放保证金补贴+￥<?php echo $output['info']['bj_butie'];?><br><br>
			      	执行（售方）：<input type='checkbox' checked disabled>国家节能补贴发放保证金补贴赔偿-￥<?php echo $output['info']['bj_butie'];?><br><br>
			      	<input type="button" value='判定并执行' onclick="butie_get(2)" style="background-color: blue;color:white">
			      	</form>
      			</td>
      		</tr>
      	</table>
      	</td>
      </tr>
      <tr>
      	<td>      	
      	<?php if(empty($output['info']['hw_butie_status'])){ ?>
      		<input type='button' name='check-button-1' onclick="butie_get(1)" value='客户已收到' style='background-color: orange;width:100px;height:40px;line-height:40px;color:white'>
      		&nbsp;&nbsp;&nbsp;&nbsp;
      		<input type='button' name='check-button-2' value='售方未付，平台判定' style='background-color: orange;width:150px;height:40px;line-height:40px;color:white'>
      	<?php }else{?>
      		<?php if(!empty($output['info']['user_butie_get_date'])){?>
      		客户确认收到时间：<?php echo $output['info']['user_butie_get_date'];?>
      		<?php }else{?>
      		客户已收到确认人：<?php echo $output['info']['hw_butie_checker'];?>
      		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		客户已收到确认时间:<?php echo $output['info']['hw_butie_check_date'];?>
      		<?php }?>
      	<?php }?>
      	</td>
      	<td>&nbsp;</td>
      </tr>
      
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
	$("#add-for-butie-file").click(function(){
		var _num = $('form[name="item-form-butie"]').find('input[type=file]').length + 1;
		var _add_str = '<br><br><input type="file" name="file_'+_num+'" >';
		$(this).after(_add_str);
	})
	$("input[name='add-note']").click(function(){
		if($("textarea[name='note']").val()==''){
			alert('备注不能为空');
			return  false;
		}
		var _form = $("form[name='item-form-butie']")
		var options = {
				dataType: "json",
	            success: function (data) {
	              if(data.error_code==0){
	           	   alert('补贴备注提交成功');
	           	   setTimeout("window.location.reload()",1000);
	              }else{
	           	   alert('补贴备注提交失败');
	              }
	            }
	            ,
	            beforeSubmit:function(){
	            	
	            }
	            ,
	            clearForm:true
	        }
	   	 	_form.ajaxForm(options) 
	        _form.ajaxSubmit(options)
	})

	$("input[name='check-button-2']").click(function(){
		$("#add-hw-check").show();
	})
})
function del_jiaoche_butie_file(id,key){
	$.ajax({
        url: "index.php",
        type: "post",
        dataType: "json",
        data: {
        	act:'butie',
			op:'ajax_sub',
			type:2,
			id:id,
			key:key,
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

function butie_get(v){
	var _str=''
	if(v==1){
		_str='已核实客户已经收到该补贴了吗';
		_reason='';
	}else if(v==2){
		_str='确定判定并执行此结果吗'
		_reason = $("textarea[name=reason]").val();
	}else{
		return false;
	}
	if(window.confirm(_str)){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'butie',
				op:'ajax_sub',
				type:3,
				id:'<?php echo $output['info']['id'];?>',
				status:v,
				reason:_reason,
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
}

</script>
