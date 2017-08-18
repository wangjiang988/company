<?php defined('InHG') or exit('Access Invalid!');
$_GET['d_type'] = !empty($_GET['d_type'])?$_GET['d_type'] :"1";
?>

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
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="butie" />
    <input type="hidden" name="op" value="index" />
    <input type="hidden" name="d_type" value="<?php echo $_GET['d_type'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <th>订单编号</th>
        <th><input type="text" class="text" name="order_num" value="<?php echo trim($_GET['order_num']); ?>" /></th>
        <th>售方用户名</th>
        <th>
          <input type="text" class="text" name="seller_name" value="<?php echo trim($_GET['seller_name']); ?>" />
       </th>
          <?php if($_GET['d_type']=='2' || $_GET['d_type']=='3'){?>
          <th><label for="add_time_from">状态</label></th>
          <th>
          	<?php if($_GET['d_type']=='2'){?>
	          <select name="status_1">
	          	<option value="" >全部</option>
	          	<option value="1" <?php if($_GET['status_1'] == '1'){?>selected<?php }?>>售方发放已提交</option>
	          	<option value="2" <?php if($_GET['status_1'] == '2'){?>selected<?php }?>>售方发放已超时</option>
	          </select>
          <?php }else{?>
	          <select name="status_2">
	          	<option value="" >全部</option>
	          	<option value="3" <?php if($_GET['status_2'] == '3'){?>selected<?php }?>>客户主动确认</option>
	          	<option value="1" <?php if($_GET['status_2'] == '1'){?>selected<?php }?>>平台与客户确认</option>
	          	<option value="2" <?php if($_GET['status_2'] == '2'){?>selected<?php }?>>平台判定并执行</option>
	          </select>
          <?php }?>
          </th>
          <?php }else{?>
          <td></td>
          <td></td>
          <?php }?>
        </tr>
        <tr>
        
          <th><label for="add_time_from">
          <?php 
          if($_GET['d_type']==1){
          	echo '发放约定时间';
          }elseif($_GET['d_type']==2){
          	echo '售方发放时间';
          }elseif($_GET['d_type']==3){
          	echo '确认收到时间';
          }
          ?>
          </label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['add_time_from'];?>" id="add_time_from" name="add_time_from">
            <label for="add_time_to">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['add_time_to'];?>" id="add_time_to" name="add_time_to"/></td>
          <td></td>
          <td></td>
          <td></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            </td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
      	<th>工单编号</th>
        <th>订单编号</th>
		<th>发放约定时间</th>
		<th>售方</th>
		 <?php 
          if($_GET['d_type']==3){
         ?>
          	 <th>确认收到时间</th>
        	<th>确认方式</th>
          	<?php 
          }else{
          	?>
          	 <th>售方发放提交时间</th>
        	<th>冻结保证金金额</th>
          
          	<?php
          }
          ?>
       
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
      <?php foreach ($output['list'] as $key => $val) { ?>
      <tr class="bd-line" >
      	<td><?php echo $val['id'] ?></td>
        <td><?php echo $val['order_num'] ?></td>
        <td><?php echo $val['shangpai']==1?'经销商上牌':'客户自己上牌';?></td>
       	<td><?php echo $val['seller_name'];?></td>
       	<td>
       	<?php 
       	if($_GET['d_type']==3){
       		echo $val['hw_butie_check_date'];
       	}else{
       		echo $val['pdi_butie_fafang'];
         }
         ?>
       	</td>
       	<td>
       	
       	<?php 
       	if($_GET['d_type']==3){
       		if($val['hw_butie_status']==1){
       			echo '平台与客户确认';
       		}elseif($val['hw_butie_status']==2){
       			echo '平台判定并执行';
       		}elseif($val['hw_butie_status']==3){
       			echo '客户主动确认';
       		}
       	}else{
       		echo $val['bj_butie'];
         }
         ?>
       	</td>
        <td class="align-center"><a href="index.php?act=butie&op=edit&id=<?php echo $val['id']; ?>"> 查看 </a></td>
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
    	$('#formSearch').submit();
    });
});
</script>
