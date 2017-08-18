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
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>查看日志</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>查看日志。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
   
      <tr class="thead">
        <th>操作时间</th>
        <th>增减数量</th>
        <th>当前售方可结算文件数</th>
        <th>操作人</th>
        <th>操作描述</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
      <?php foreach ($output['list'] as $key => $val) { ?>
      <tr class="bd-line" >
      	<td><?php echo $val['date'] ?></td>
      	<td><?php echo $val['num'] ?></td>
        <td><?php echo $val['current_file_num'] ?></td>
        <td><?php echo $val['op_name'] ?></td>
        <td><?php echo $val['note'] ?></td>
      </tr>
      <?php } ?>
    </tbody>
    
    <?php } else { ?>
    <tbody>
      <tr class="no_data">
        <td colspan="20"><?php echo $lang['no_record'];?></td>
      </tr>
    </tbody>
    <?php } ?>
    
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('index');$('#formSearch').submit();
    });
});
function sure_all_num(id,seller_id,file_num){
	if(window.confirm('您确认收到的快件中有'+file_num+'份可用文件吗')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'dealer_calc',
				op:'sure_file',
				id:id,
				seller_id:seller_id,
				file_num:file_num,
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
