<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<br>
<table class="ncsc-table-style">
  <thead>
    <tr>
      <th>交车信息核实时间</th>
      <th>订单号</th>
      <th>结算金额</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['list']) && is_array($output['list'])) { ?>
    <?php foreach($output['list'] as $v) { ?>
    <tr class="bd-line">
      <td><?php echo $v['updated_at'];?></td>
      <td><a href="http://www.123.com/getmyorderdaili/<?php echo $v['order_num'];?>" target="_blank" ><?php echo $v['order_num'];?></a></td>
      <td><?php echo $v['inv_money'];?></td>
      <td><a href="javascript:calculate('<?php echo $v['order_num']; ?>')" target="_blank" class="ncsc-btn-mini"><i class="icon-file-text-alt"></i>去结算</a></td>
      
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['list']) && is_array($output['list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script type="text/javascript">
function calculate(order_num){
	if(window.confirm('确定要对此订单进行结算吗')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'store_calc',
				op:'change_cart_status',
				order_num:order_num,
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