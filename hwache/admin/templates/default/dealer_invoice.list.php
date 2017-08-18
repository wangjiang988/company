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
        <li><a href="index.php?act=dealer_calc&op=invoice_list&i_type=5&invoice_type=done" class="<?php if($_GET['i_type']==5){echo 'current';}?>"><span>已完成开票</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="dealer_calc" />
    <input type="hidden" name="op" value="invoice_list" />
    <input type="hidden" name="i_type" value="<?php echo $_GET['i_type'];?>" />
    <input type="hidden" value="<?php echo $output['invoice_type'];?>" name="invoice_type" />
    
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <td><select name="type">
            <option value="member_name" <?php if($_GET['type'] == 'member_name'){?>selected<?php }?>>用户名</option>
             <option value="member_truename" <?php if($_GET['type'] == 'member_truename'){?>selected<?php }?>>用户姓名</option>
          </select></td>
        <td><input type="text" class="text" name="key" value="<?php echo trim($_GET['key']); ?>" /></td>
          <td>&nbsp;</td>
          <td><label>订单号</label></td>
          <td><input class="txt " type="text" value="<?php echo $_GET['order_num'];?>"  name="order_num">
          <?php if($_GET['invoice_type']=='done'){?>
          <td><label>发票编号</label></td>
          <td><input class="txt " type="text" value="<?php echo $_GET['inv_no'];?>"  name="inv_no">          
          <?php }?>
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
        <th>用户名</th>
        <th>用户姓名</th>
        <th>订单编号</th>
        <th>结算金额</th>
        
        <?php if($_GET['invoice_type']=='undo'){?>
        <th>同意结算时间</th>
        <?php }else{?>
        <th>发票编号</th>
        <th>开票时间</th>
        <?php }?>
        <th>在手可用文件数</th>
        <th class="align-center">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (is_array($output['list']) && !empty($output['list'])) { ?>
      <?php foreach ($output['list'] as $key => $val) { ?>
      <tr class="bd-line" >
      	<td><?php echo $val['id'] ?></td>
      	<td><?php echo $val['member_name'] ?></td>
        <td><?php echo $val['member_truename'] ?></td>
        <td><?php echo $val['order_num'] ?></td>
        <td><?php echo '待计算' ?></td>
        
        <?php if($_GET['invoice_type']=='undo'){?>
        <td><?php echo $val['pdi_calc_date'] ?></td>
        <?php }else{?>
        <td><?php echo $val['inv_no'] ?></td>
        <td><?php echo $val['date'] ?></td>
        <?php }?>
        
        
        <td>
        <?php echo $val['calc_file'] ?>
        </td>        
        <td class="align-center">
        <a href="index.php?act=dealer_calc&op=show_invoice&order_num=<?php echo $val['order_num']; ?><?php if(!empty($val['inv_id'])){echo "&inv_id=".$val['inv_id'];}?>"> 查看 </a>
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
    $('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });
});

</script>
