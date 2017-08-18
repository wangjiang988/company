<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form action='' method='post' name='item-form'>
<table class="ncsc-table-style">
<tr><td style="text-align: left;">结算文件可用数：</td><td style="text-align: left;">已用结算文件数：</td></tr>
<tr><td colspan=2 style="text-align: left;">温馨提示：请保持可结算文件数量充足，以免影响你的结算时间。如不足，请下载->打印->签名后将正本寄给平台（一次可寄多份），须注明您的会员明，姓名，寄送信息请在下方提交。</td></tr>
<tr><td colspan=2 style="text-align: left;"><span style='background-color: orange;width:20px;height:20px;'>&nbsp;&nbsp;</span>&nbsp;&nbsp;收件人信息：</td></tr>
<tr><td style="text-align: right;">邮寄地址：</td><td style="text-align: left;">江苏省苏州高新区竹园路209号2号楼2205室</td></tr>
<tr><td style="text-align: right;">邮编：</td><td style="text-align: left;">215101</td></tr>
<tr><td style="text-align: right;">公司名：</td><td style="text-align: left;">苏州华车网络科技有限公司</td></tr>
<tr><td style="text-align: right;">收件人：</td><td style="text-align: left;">黄小姐</td></tr>
<tr><td style="text-align: right;">电话：</td><td style="text-align: left;">18112552716</td></tr>

<tr><td colspan=2 style="text-align: left;"><span style='background-color: orange;width:20px;height:20px;'>&nbsp;&nbsp;</span>&nbsp;&nbsp;提交寄件信息：</td></tr>
<tr><td style="text-align: right;">文件数量：</td><td style="text-align: left;"><input type='text' name='file_num'></td></tr>
<tr><td style="text-align: right;">快递名称：</td><td style="text-align: left;"><input type='text' name='deliver'></td></tr>
<tr><td style="text-align: right;">快递单号：</td><td style="text-align: left;"><input type='text' name='deliver_num'></td></tr>
<tr><td></td><td><input type='button' value='提交' name='sub' class="submit"></td></tr>
<tr><td colspan=2 style="text-align: left;"><span style='background-color: orange;width:20px;height:20px;'>&nbsp;&nbsp;</span>&nbsp;&nbsp;提交记录：</td></tr>
<input type='hidden' value='tj' name='todo'>
<input type='hidden' value='store_calc' name='act'>
<input type='hidden' value='index' name='op'>
</table>
</form>
<br>
<table class="ncsc-table-style">
  <thead>
    <tr>
      <th>提交时间</th>
      <th>寄件数量</th>
      <th>快递名称</th>
      <th>运单号</th>
      <th>状态</th>
      <th>收到可用文件数量</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['file_list']) && is_array($output['file_list'])) { ?>
    <?php foreach($output['file_list'] as $file) { ?>
    <tr class="bd-line">
      
      <td><?php echo $file['send_date'];?></td>
      <td><?php echo $file['file_num'];?></td>
      <td><?php echo $file['deliver'];?></td>
      <td><?php echo $file['deliver_num'];?></td>
      <td><?php echo $output['calc_file_status'][$file['status']];?>
      <?php if($file['status'] == 0){?>
      		<a href="javascript:cancel('<?php echo $file['id'];?>')">撤销</a>
      <?php }?>
      </td>
      <td><?php echo $file['sure_num'];?></td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['file_list']) && is_array($output['file_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script type="text/javascript">
$(function(){
    $('input[name=sub]').click(function(){
		var _file_num = $('input[name=file_num]').val();
		var _deliver = $('input[name=deliver]').val();
		var _deliver_num = $('input[name=deliver_num]').val();
		if(_file_num == '' || _deliver=='' || _deliver_num==''){
			alert('文件数量、快递名称、运单号都不能为空');
		}else{
			$('form[name="item-form"]').submit();
		}

     })
});
function cancel(id){
	if(window.confirm('确定要撤销寄件信息吗')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'store_calc',
				op:'cancel_file',
				id:id,
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