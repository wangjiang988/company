<?php defined('InHG') or exit('Access Invalid!');
//$_GET['d_type'] = !empty($_GET['d_type'])?$_GET['d_type'] :"undone";
?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>交车管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=jiaoche&op=index&d_type=undone" <?php if($_GET['d_type']=='undone'){echo "class=current";}?>><span>待审核</span></a></li>
        <li><a href="index.php?act=jiaoche&op=index&d_type=done" <?php if($_GET['d_type']=='done'){echo "class=current";}?>><span>已审核</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="jiaoche" />
    <input type="hidden" name="op" value="index" />
    <input type="hidden" name="d_type" value="<?php echo $_GET['d_type'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <th>订单编号</th>
        <th><input type="text" class="text" name="order_num" value="<?php echo trim($_GET['order_num']); ?>" /></th>
        <th><label for="add_time_from">上牌方式</label></th>
          <th>
          <select name="shangpai">
          	<option value="" >全部</option>
            <option value="1" <?php if($_GET['shangpai'] == '1'){?>selected<?php }?>>经销商上牌</option>
          	<option value="0" <?php if($_GET['shangpai'] == '0'){?>selected<?php }?>>客户本人上牌</option>
          </select>
          </th>
          <?php if($_GET['d_type']=='undone'){?>
          <th><label for="add_time_from">状态</label></th>
          <th>
          <select name="status">
          	<option value="" >全部</option>
          	<option value="1" <?php if($_GET['status'] == '1'){?>selected<?php }?>>售方已提交交车信息</option>
            <option value="2" <?php if($_GET['status'] == '2'){?>selected<?php }?>>客户已提交交车信息</option>
            <option value="3" <?php if($_GET['status'] == '3'){?>selected<?php }?>>双方已提交交车信息</option>
          	<option value="4" <?php if($_GET['status'] == '4'){?>selected<?php }?>>客户提交上牌信息超时</option>
          	
            <option value="5" <?php if($_GET['status'] == '5'){?>selected<?php }?>>售方已提交上牌信息</option>
            <option value="6" <?php if($_GET['status'] == '6'){?>selected<?php }?>>客户已提交上牌信息</option>
            <option value="7" <?php if($_GET['status'] == '7'){?>selected<?php }?>>双方已提交上牌信息</option>
          </select>
          </th>
          <?php }else{?>
          <td></td>
          <td></td>
          <?php }?>
        </tr>
        <tr>
        
          <th><label for="add_time_from">交车信息提交时间</label></th>
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
		<th>上牌方式</th>
		<?php if($_GET['d_type']=='undone'){?>
		<th>状态</th>
        <th>提交交车时间</th>
        <?php }else{?>
        <th>审核人</th>
        <th>审核时间</th>
        <?php }?>
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
        <?php if($_GET['d_type']=='undone'){?>
        <td>
        <?php 
        if($val['shangpai']==1){//经销商上牌
        	if($val['pdi_date_first']!=''){
        		$str = '售方已提交上牌信息';
        	}
        	if($val['user_date_first']!=''){
        		$str = '客户已提交上牌信息';
        	}
        	if($val['pdi_date_first']!='' && $val['user_date_first']!=''){
        		$str = '双方已提交上牌信息';
        	}
        	
        }else{//客户自由上牌
        	if($val['pdi_date_first']!=''){
        		$str = '售方已提交交车信息';
        	}
        	if($val['user_date_first']!=''){
        		$str = '客户已提交交车信息';
        	}
        	if($val['pdi_date_first']!='' && $val['user_date_first']!=''){
        		$str = '双方已提交交车信息';
        	}
        	
        	if($val['pdi_chepai']!=''){
        		$str = '售方已提交上牌信息';
        	}
        	if($val['user_chepai']!=''){
        		$str = '客户已提交上牌信息';
        	}
        	if($val['pdi_chepai']!='' && $val['user_chepai']!=''){
        		$str = '双方已提交上牌信息';
        	}
        	
        }
        echo $str;
        ?>
        </td>
        <td><?php echo $val['create_at'];?></td>
        <?php }else{?>
        <td><?php echo $val['hw_checker'];?></td>
        <td><?php echo $val['hw_check_date'];?></td>
        <?php }?>
        <td class="align-center"><a href="index.php?act=jiaoche&op=edit&id=<?php echo $val['id']; ?>"> 查看 </a></td>
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
