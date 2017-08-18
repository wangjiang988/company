<table class="table table-hover table-bordered" style="text-align: center">
  <tr class="info">
    <td rowspan="2" style="vertical-align: middle;">订单号</td>
    <td rowspan="2" style="vertical-align: middle;">订单时间</td>
    <td>客户手机</td>
    <td>客户订单状态</td>
    <td rowspan="2" style="vertical-align: middle;">操作 </td>
  </tr>
  <tr class="info">
    <td class="table-center">售方用户名</td>
    <td class="table-center">售方订单状态</td>
  </tr>
  <?php if($output['orders_list']):?>
    <?php foreach ($output['orders_list'] as $key => $value): ?>
      <tr class="hover edit">
        <td rowspan="2" style="vertical-align: middle;"><?=$value['order_sn'];?></td>
        <td rowspan="2" style="vertical-align: middle;"><?=$value['created_at'];?></td>
        <td><?=$value['phone'];?></td>
        <td><?=$value['user_progress'].'>'.$value['remark'];?></td>
        <td rowspan="2" style="vertical-align: middle;"><a href="index.php?act=order&op=show_order&id=<?=$value['id'];?>">查看</a></td>
      </tr>
      <tr class="hover">
        <td class="table-center"><?=$value['d_name'];?></td>
        <td class="table-center"><?=$value['seller_progress'].'>'.$value['remark'];?></td>
      </tr>
    <?php endforeach ?>
  <?php else:?>
    <tr class="no_data">
      <td colspan="7"><?php echo $lang['nc_no_record']?></td>
    </tr>
  <?php endif;?>
</table>
<?php if($output['orders_list']):?>
  <div class="pagination"> <?php echo $output['page'];?> </div>
<?php endif;?>