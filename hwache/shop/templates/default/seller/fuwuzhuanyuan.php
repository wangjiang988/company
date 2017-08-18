<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <p><span><a href="index.php?act=fuwuzhuanyuan&op=add" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i>添加服务专员</a></span></p>
  <table class="ncsc-table-style">
    <thead>
      <tr nc_type="table_header">
        <th>姓名</th>
        <th>手机</th>
        <th >备用电话</th>
        <th >所属经销商</th>
        <th>备注</th>
      </tr>
    </thead>
    <tbody>
      
      <?php foreach ($output['file_list'] as $val) { ?>
      
      <tr>
        <td><span><?php echo $val['name']; ?></span></td>
        <td><span><?php echo $val['mobile']; ?></span></td>
        <td><span><?php echo $val['tel']; ?></span></td>
        <td >
          <span><?php echo $val['d_name']; ?></span>
        </td>
        <td><span><?php echo $val['notice']; ?></span></td>
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
