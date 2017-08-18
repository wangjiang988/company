<table class="table table-hover table-bordered">
  <tr class="info">
    <th class="text-center">经销商编号</th>
    <th class="text-center">销售品牌</th>
    <th class="text-center">归属地区</th>
    <th class="text-center">经销商名称</th>
    <th class="text-center">营业地点</th>
    <th class="text-center">显示状态</th>
    <th class="text-center">操作</th>
  </tr>
  <?php if(!empty($output['dealer_list']) && is_array($output['dealer_list'])):?>
    <?php foreach($output['dealer_list'] as $k => $v):?>
      <tr class="hover edit text-center">
        <td><?php echo $v['d_id']; ?></td>
        <td><?php echo $v['gc_name']; ?></td>
        <td><?php echo str_replace("	",'',$v['d_areainfo']);?></td>
        <td><?php echo $v['d_name']; ?></td>
        <td><?php echo $v['d_yy_place']; ?></td>
        <td><?php if($v['d_is_show']){echo ' 显示';}else{echo '始终不显示';} ?></td>
        <td><a href="index.php?act=dealer&op=dealer_view&d_id=<?php echo $v['d_id']; ?>"><?php echo $lang['nc_edit']?></a>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr class="no_data">
      <td colspan="7"><?php echo $lang['nc_no_record']?></td>
    </tr>
  <?php endif;?>
</table>
<?php if(!empty($output['dealer_list']) && is_array($output['dealer_list'])): ?>
  <div class="pagination"> <?php echo $output['page'];?> </div>
<?php endif ?>