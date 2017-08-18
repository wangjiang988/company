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
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="dealer_calc" />
    <input type="hidden" name="op" value="index" />
    <input type="hidden" name="i_type" value="<?php echo $_GET['i_type'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <td><select name="type">
            <option value="seller_name" <?php if($_GET['type'] == 'op_name'){?>selected<?php }?>>寄件用户名</option>
            <option value="deliver_num" <?php if($_GET['type'] == 'deliver_num'){?>selected<?php }?>>运单号</option>
          </select></td>
        <td><input type="text" class="text" name="key" value="<?php echo trim($_GET['key']); ?>" /></td>
          <td>&nbsp;</td>
          <td><label for="add_time_from">操作时间</label></td>
          <td><input class="txt date" type="text" value="<?php echo $_GET['add_time_from'];?>" id="add_time_from" name="add_time_from">
            <label for="add_time_to">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['add_time_to'];?>" id="add_time_to" name="add_time_to"/></td>
          <td><a href="javascript:viod(0);" id="ncsubmit" class="btn-search " title="搜索">&nbsp;</a>
            </td>
        </tr>
        
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
   
      <tr class="thead">
        <th>编号</th>
        <th>寄件用户名</th>
        <th>用户姓名</th>
        <th>寄送时间</th>
        <th>快递名称</th>
        <th>运单号</th>
        <th>寄送文件数</th>
        <?php if($_GET['i_type']==2){?>
        <th>收到可用文件数量</th>
        <th>收到时间</th>
        <?php }elseif($_GET['i_type']==3){?>
        <th>撤销时间</th>
        <?php }?>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
      <?php foreach ($output['list'] as $key => $val) { ?>
      <tr class="bd-line" >
      	<td><?php echo $val['id'] ?></td>
      	<td><?php echo $val['seller_name'] ?></td>
        <td><?php echo $val['seller_truename'] ?></td>
        <td><?php echo $val['send_date'] ?></td>
        <td><?php echo $val['deliver'] ?></td>
        <td><?php echo $val['deliver_num'] ?></td>
        <td>
        <?php echo $val['file_num'] ?>
        </td>
        <?php if($_GET['i_type']==2){?>
        <td><?php echo $val['sure_num'] ?></td>
        <td><?php echo $val['receive_date'] ?></td>
        <?php }elseif($_GET['i_type']==3){?>
        <td><?php echo $val['receive_date'] ?></td>
        <?php }?>
        
        <td class="align-center">
        <a href="index.php?act=dealer_calc&op=show&id=<?php echo $val['id']; ?>"> 查看 </a>
        <?php if($val['status']==0){?>
        &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:sure_all_num('<?php echo $val['id'];?>','<?php echo $val['seller_id'];?>','<?php echo $val['file_num'];?>')" >全数确认</a>
        <?php }?>
        </td>
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
				status:'0',
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
