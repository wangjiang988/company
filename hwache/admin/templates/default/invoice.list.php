<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>发票管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=invoice&op=index&i_type=1" class="<?php if($_GET['i_type']==1){echo 'current';}?>"><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=invoice&op=index&i_type=2" class="<?php if($_GET['i_type']==2){echo 'current';}?>"><span><?php echo '完成处理';?></span></a></li>
        <li><a href="index.php?act=invoice&op=index&i_type=3" class="<?php if($_GET['i_type']==3){echo 'current';}?>"><span><?php echo '超时未开';?></span></a></li>
        <li><a href="index.php?act=invoice&op=invoice_manage_list" class="<?php if($_GET['i_type']==4){echo 'current';}?>"><span><?php echo '空白发票';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="invoice" />
    <input type="hidden" name="op" value="index" />
    <input type="hidden" name="i_type" value="<?php echo $_GET['i_type'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <td><select name="type">
            <option value="order_num" <?php if($_GET['type'] == 'order_num'){?>selected<?php }?>>订单号</option>
            <?php if($_REQUEST['i_type']!=3){?>
            <option value="inv_title" <?php if($_GET['type'] == 'inv_title'){?>selected<?php }?>>发票抬头</option>
          	<?php }?>
          </select></td>
        <td><input type="text" class="text" name="key" value="<?php echo trim($_GET['key']); ?>" /></td>
          <td>&nbsp;</td>
          <td><label for="add_time_from"><?php if($_REQUEST['i_type']!=3){ echo '申请时间';}else{ echo '失效时间';}?></label></td>
          <td><input class="txt date" type="text" value="<?php echo $_GET['add_time_from'];?>" id="add_time_from" name="add_time_from">
            <label for="add_time_to">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['add_time_to'];?>" id="add_time_to" name="add_time_to"/></td>
          <td><a href="javascript:viod(0);" id="ncsubmit" class="btn-search " title="搜索">&nbsp;</a>
            </td>
        </tr>
        <?php if($_REQUEST['i_type']!=3){?>
        <tr>
        <td align="right">发票类型：</td>
        <td>
        	<select name="inv_state">
        		<option value="" <?php if($_GET['inv_state'] == ''){?>selected<?php }?>>全部</option>
            	<option value="1" <?php if($_GET['inv_state'] == '1'){?>selected<?php }?>>增值税普通发票</option>
            	<option value="2" <?php if($_GET['inv_state'] == '2'){?>selected<?php }?>>增值税专用发票</option>
          	</select>
        </td>
        <td></td>
        <td>发票状态：</td>
        <td>
          <select name="invoice_status">
          		<option value="" <?php if($_GET['invoiceStatus'] == ''){?>selected<?php }?>>全部</option>
          		<?php foreach($output['invoiceStatus'] as $k=>$v){?>
            	<option value="<?php echo $k;?>" <?php if($_GET['invoice_status'] == $k){?>selected<?php }?>><?php echo $v;?></option>
          		<?php }?>
          	</select>
        </td>
        <td>
          	
        </td>
          
        </tr>
        <?php }?>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>发票管理</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>发票管理备注。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
    <?php if($_REQUEST['i_type']==3){?>
      <tr class="thead">
        <th>开票失效时间</th>
        <th>订单号</th>
        <th>结算完成时间</th>
        <th class="align-center">操作</th>
      </tr>
      <?php if (is_array($output['invoice_list']) && !empty($output['invoice_list'])) { ?>
      <?php foreach ($output['invoice_list'] as $key => $val) { ?>
      <tr class="bd-line" >
      	<th><?php echo date('Y-m-d',strtotime($val['updated_at'])+90*86400);?></th>
        <th><?php echo $val['order_num'];?></th>
        <th><?php echo $val['updated_at'];?></th>
        <th class="align-center">
        <?php if($val['overtime_invoice_status']==1){
        		echo "<a href='index.php?act=invoice&op=overtime_invoice&order_num=".$val['order_num']."'>查看</a>";
			  }else{
        ?>
        <a href="javascript:agreeInvoice('<?php echo $val['order_num']; ?>')"> 恢复可开票 </a>
        <?php }?>
        </th>
      </tr>
      <?php }
		}
	  ?>
    
    <?php }else{?>
      <tr class="thead">
        <th>编号</th>
        <th>申请开票时间</th>
        <th>订单号</th>
        <th>申请开票金额</th>
        <th>申请开票抬头</th>
        <th>发票类型</th>
        <th>状态</th>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['invoice_list']) && !empty($output['invoice_list'])) { ?>
      <?php foreach ($output['invoice_list'] as $key => $val) { ?>
      <tr class="bd-line" >
      	<td><?php echo $val['inv_id'] ?></td>
      	<td><?php echo $val['inv_apply_date'] ?></td>
        <td><?php echo $val['order_num'] ?></td>
        <td><?php echo $val['inv_money'] ?></td>
        <td><?php echo $val['inv_title'] ?></td>
        
        <td><?php echo $val['inv_state']==1?"增值税普通发票":"增值税专用发票"; ?></td>
        <td>
        <?php echo $output['invoiceStatus'][$val['invoice_status']] ?>
        <?php if($val['invoice_type']==1){?>
        (重开票)
        <?php }?>
        </td>
        <td class="align-center"><a href="index.php?act=invoice&op=show&inv_id=<?php echo $val['inv_id']; ?>"> 查看 </a></td>
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
    
    <?php }?>
      <?php if (is_array($output['invoice_list']) && !empty($output['invoice_list'])) { ?>
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
function agreeInvoice(order_num){
	if(window.confirm('您确认需要恢复可开票状态吗')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'invoice',
				op:'agree_invoice',
				order_num:order_num
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
