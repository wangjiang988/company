<table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['common_ubs'];?></th>
        <th><?php echo $lang['common_name'];?></th>
        <th class="align-center"><?php echo $lang['common_dealer_name']; ?></th>
        <th class="align-center"><?php echo $lang['common_brand_name']; ?></th>
        <th class="align-center"><?php echo $lang['common_area'];?></th>
        <th class="align-center"><?php echo $lang['common_time'];?></th>
        
        <th class="align-center"><?php echo $lang['common_status_name'];?></th>
        <th class="align-center"><?php echo $lang['common_operate'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['class_list'])>0){?>
      <?php foreach($output['class_list'] as $order){?>
      <tr class="hover">
        <td><?php echo $order['id'];?></td>        
        <td><?php echo $order['member_name'];?></td>
        <td class="nowrap align-center"><?php echo $order['d_name'];?></td>
        <td class="nowrap align-center"><?php echo $order['gc_name'];?></td>
        <td class="nowrap align-center"><?php echo $order['d_areainfo'];?></td>
        <td class="align-center"><?php echo date("Y-m-d H:i:s",$order['dl_add_time']);?></td>
        <td class="align-center"><?php
          if($order['dl_status'] == 1) {
          	echo "待审核";
          } 
          if($order['dl_status'] == 2) {
             echo "审核通过";
          }
          if($order['dl_status'] == 4) {
             echo "审核不通过";
          }    	
		 ?>
		 </td>
        <td class="w144 align-center"><a href="index.php?act=commons_manage&op=basic_info&dl_id=<?php echo $order['id'];?>"><?php echo $lang['nc_view'];?></a>&nbsp
        <a href="index.php?act=commons_manage&op=edit_info&dl_id=<?php echo $order['id'];?>"><?php echo $lang['common_edit'];?></a>
       </td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="17"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
      </tr>
    </tfoot>
  </table>
  <div class="pagination"><?php echo $output['page'];?> </div>