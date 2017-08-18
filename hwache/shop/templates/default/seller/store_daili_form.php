<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <p><span><a href="index.php?act=store_setting&op=add_dealer" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i>添加经销商</a></span></p>
  <table class="ncsc-table-style">
    <thead>
      <tr nc_type="table_header">
        <th>经销商名称</th>
        <th class="w200">归属地</th>
        <th class="w100">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['daili_dealer_list'])) { ?>
      <?php foreach ($output['daili_dealer_list'] as $val) { ?>
      <tr>
        <th colspan="20">&nbsp;&nbsp;经销商ID：<?php echo $val['d_id'];?></th>
      </tr>
      <tr>
        <td><span><?php echo $val['d_name']; ?></span></td>
        <td><span><?php echo $val['d_areainfo']; ?></span></td>
        <td class="nscs-table-handle">
          <span><!-- <a href="<?php echo urlShop('store_setting', 'del', array('id' => $val['id']));?>" class="btn-red"><i class="icon-trash"></i><p>删除</p></a> --></span>
        </td>
      </tr>
      <tr style="display:none;"><td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td></tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (!empty($output['daili_dealer_list'])) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['page']; ?> </div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
  <p><span><a href="index.php?act=store_setting&op=add_dealer" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i>添加经销商</a></span></p>
</div>
