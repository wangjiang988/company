<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <p><span><a href="index.php?act=store_files&op=add_file" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i>添加资料</a></span></p>
  <table class="ncsc-table-style">
    <thead>
      <tr nc_type="table_header">
        <th>文件名称</th>
        <th>是否本人</th>
        <th >使用场合</th>
        <th >操作</th>
      </tr>
    </thead>
    <tbody>
      
      <?php foreach ($output['file_list'] as $val) { ?>
      
      <tr>
        <td><span><?php echo $val['title']; ?></span></td>
        <td><span><?php echo $val['isself']==1?'是':'否'; ?></span></td>
        <td><span><?php echo $val['cate']; ?></span></td>
        <td class="nscs-table-handle">
          <span><a href="<?php echo urlShop('store_files', 'del', array('id' => $val['id']));?>" class="btn-red"><i class="icon-trash"></i><p>删除</p></a></span>
        </td>
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
