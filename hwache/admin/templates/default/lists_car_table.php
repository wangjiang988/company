<table border="1" width="100%" class="table table-hover">
   <tr align="center" height="50px">
      <td><b>车系</b></td>
      <td><b>车型规格</b></td>
      <td><b>厂商指导价</b></td>
      <td><b>操作</b</td>
   </tr>
   <?php if(count($output['car_lists'])>0) {?>
   <?php foreach($output['car_lists'] as $car_list) { ?>
    <tr align="center" height="40px">
     <td><?php echo $car_list['gc_name'];?></td>
      <td><?php echo $car_list['staple_name'];?></td>
      <td><?php echo '￥'.unserialize($car_list['value']);?></td>
      <td><a href="index.php?act=commons_manage&op=car_info&dl_id=<?php echo $car_list['daili_dealer_id'];?>&gc_id=<?php echo $car_list['gc_id_3'];?>&sp_id=<?php echo $car_list['staple_id'];?>">查看</a></td>
   </tr>
   <?php } } else {?>
     <tr>
        <td colspan="4" height="40px">无</td>
     </tr>
   <?php }?>
</table>