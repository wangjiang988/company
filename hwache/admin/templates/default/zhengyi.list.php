<?php defined('InHG') or exit('Access Invalid!');
$_GET['d_type'] = !empty($_GET['d_type'])?$_GET['d_type'] :"undone";
$breaker_status = array(1=>'售方违约',2=>'客户违约',3=>'不违约-继续订单');
?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>争议处理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=zhengyi&op=index&d_type=undone" <?php if($_GET['d_type']=='undone'){echo "class=current";}?>><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=zhengyi&op=index&d_type=done" <?php if($_GET['d_type']=='done'){echo "class=current";}?>><span><?php echo '已处理';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="zhengyi" />
    <input type="hidden" name="op" value="index" />
    <input type="hidden" name="d_type" value="<?php echo $_GET['d_type'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <th>订单编号</th>
        <th><input type="text" class="text" name="order_num" value="<?php echo trim($_GET['order_num']); ?>" /></th>
          <th><label for="add_time_from">争议类别</label></th>
          <th>
          <select name="dispute_type">
          	<option value="dispute" >全部</option>
            <option value="dispute" <?php if($_GET['dispute_type'] == 'dispute'){?>selected<?php }?>>交车争议</option>
          </select>
          </th>
           <th><label for="add_time_from">提交方</label></th>
          <th>
          <select name="who">
          	<option value="" >全部</option>
            <option value="seller" <?php if($_GET['who'] == 'seller'){?>selected<?php }?>>售方</option>
            <option value="buyer" <?php if($_GET['who'] == '"buyer"'){?>selected<?php }?>>客户</option>
          </select>
          </th>
          <th>
          </th>
        </tr>
        <tr>
        <?php if($_GET['d_type']=='undone'){?>
        <th>处理进程:</th>
        <th><select name="do_status">
            <option value="">全部</option>
            <option value="1" <?php if($_GET['do_status'] == '1'){?>selected<?php }?>>未申辩</option>
            <option value="2" <?php if($_GET['do_status'] == '2'){?>selected<?php }?>>已申辩</option>
            <option value="3" <?php if($_GET['do_status'] == '3'){?>selected<?php }?>>未补充</option>
            <option value="4" <?php if($_GET['do_status'] == '4'){?>selected<?php }?>>已补充</option>
            <option value="5" <?php if($_GET['do_status'] == '5'){?>selected<?php }?>>正在调解</option>
            <option value="6" <?php if($_GET['do_status'] == '6'){?>selected<?php }?>>正在裁判</option>
            <option value="7" <?php if($_GET['do_status'] == '7'){?>selected<?php }?>>等待审判</option>
            

          </select></th>
        <?php }else{?>
        <th>处理结果:</th>
        <th><select name="breaker">
            <option value="">全部</option>
            <option value="1" <?php if($_GET['breaker'] == '1'){?>selected<?php }?>>售方违约</option>
            <option value="2" <?php if($_GET['breaker'] == '2'){?>selected<?php }?>>客户违约</option>
            <option value="3" <?php if($_GET['breaker'] == '3'){?>selected<?php }?>>不违约-继续订单</option>
          </select></th>
        <?php }?>
          <th><label for="add_time_from">提交时间</label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['add_time_from'];?>" id="add_time_from" name="add_time_from">
            <label for="add_time_to">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['add_time_to'];?>" id="add_time_to" name="add_time_to"/></td>
          <th></th>
          <td><a href="javascript:viod(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
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
        <th>订单编号</th>
		<th>争议类别</th>
		<th>争议提交方</th>
		<th>争议</th>
        <th>提交争议时间</th>
        <th><?php if($_GET['d_type']=='undone'){echo '处理进程';}else{echo '处理结果';}?></th>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['dispute_list']) && !empty($output['dispute_list'])) { ?>
      <?php foreach ($output['dispute_list'] as $key => $val) { ?>
      <tr class="bd-line" >
        <td><?php echo $val['order_num'] ?></td>
        <td>交车争议</td>
        <td><?php echo $val['member_id']==$val['buy_id']?"客户":"售方"; ?></td>
        
        <td><?php echo implode(',',unserialize($val['problem'])) ?></td>
        
        <td><?php echo $val['createat'] ?></td>
        <td>
        
        <?php 
        if($_GET['d_type']=='undone'){
        	
        	if(empty($val['mediate_id'])){
        		if($val['defend_id']==''){
        			echo '未申辩';
        		}else{
        			echo '已申辩';	
        		}
        		
        		if(($val['resupply']!='' && $val['resupply_evidence']=='') || ($val['defend_resupply']!='' && $val['defend_resupply_evidence']=='')){
        			echo '未补充';
        		}
        		if(($val['resupply']!='' && $val['resupply_evidence']!='') && ($val['defend_resupply']!='' && $val['defend_resupply_evidence']!='')){
        			echo '已补充';
        		}
        		
        	}else{
        		if($val['mediate_status']==0 || $val['mediate_status']==1){
        			echo '正在调解';
        		}elseif($val['mediate_status']==3){
        			echo '正在裁判';
        		}elseif($val['mediate_status']==4){
        			echo '等待审判';
        		}
        		
        	}
        }elseif($_GET['d_type']=='done'){
        	if($val['mediate_status']==2){
        		echo '已结和解';
        	}else{
        		echo $breaker_status[$val['breaker']];
        	}
        }
        	
        ?>
        
        </td>
        <td class="align-center"><a href="index.php?act=zhengyi&op=edit&dispute_id=<?php echo $val['id']; ?>"> 查看 </a></td>
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
      <?php if (is_array($output['dispute_list']) && !empty($output['dispute_list'])) { ?>
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
