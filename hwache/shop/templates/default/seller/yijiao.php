<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <p><span><a href="index.php?act=yijiao&op=add" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i>添加资料</a></span></p>
  <table class="ncsc-table-style">
    <thead>
      <tr nc_type="table_header">
        <th>名称</th>
        <th>数量</th>
        <th >所属分类</th>
        <th>备注</th>
        <th>所属车型</th>
      </tr>
    </thead>
    <tbody>
      
      <?php foreach ($output['file_list'] as $val) { ?>
      
      <tr>
        <td><span><?php echo $val['title']; ?></span></td>
        <td><span><?php echo $val['num']; ?></span></td>
        <td><span><?php echo $val['type']; ?></span></td>
        <td><span><?php echo $val['notice']; ?></span></td>
        <td><span><?php echo $val['gc_name']; ?></span></td>
      </tr>
      <tr style="display:none;"><td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td></tr>
      <?php } ?>
      
    </tbody>
    <tfoot>
      <?php  if (!empty($output['file_list'])) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['page']; ?> </div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
  
</div>
