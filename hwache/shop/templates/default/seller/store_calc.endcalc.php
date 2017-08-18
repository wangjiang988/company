<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<br>
<table class="ncsc-table-style">
  <thead>
    <tr>
      <th>结算完成时间</th>
      <th>订单号</th>
      <th>结算金额</th>
      <th>操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['list']) && is_array($output['list'])) { ?>
    <?php foreach($output['list'] as $v) { ?>
    <tr class="bd-line">
      <td><?php echo $v['date'];?></td>
      <td><a href="http://www.123.com/getmyorderdaili/<?php echo $v['order_num'];?>" target="_blank" ><?php echo $v['order_num'];?></a></td>
      <td><?php echo $v['inv_money'];?></td>
      <td>查看</td>
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

</script>