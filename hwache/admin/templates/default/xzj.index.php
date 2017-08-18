<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
  <div class="item-title">
    <h3><a href="index.php?act=hgsoft&op=xzj">选装件管理</a></h3>
    <ul class="tab-base">
    <li><a class="current" href="javascript:;"><span>指定车型选装件列表</span></a></li>
    <li><a href="index.php?act=hgsoft&op=xzj_common"><span>通用选装件管理</span></a></li>
    <?php if($output['type']) {?>
    <li><a href="index.php?act=hgsoft&op=xzj_add&carid=<?php echo $output['carid'];?>"><span><?php echo $lang['nc_new'];?></span></a></li>
    <?php } ?>
    </ul>
  </div>
  </div>
  <div class="fixed-empty"></div>
  <div><?php echo $output['navCar'];?></div>
  <?php if(!empty($output['carList'])): ?>
  <table class="table tb-type2">
    <tbody>
    <?php foreach($output['carList'] as $k=>$v): ?>
    <tr>
      <td><a href="index.php?act=hgsoft&op=xzj&carid=<?php echo $v['gc_id']; ?>"><?php echo $v['gc_name']; ?></a></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php else: ?>
  <table class="table tb-type2">
    <thead>
    <tr class="thead">
      <th>名称</th>
      <th>原厂</th>
      <th>必须前装</th>
      <th>品牌</th>
      <th>型号</th>
      <th>每车最多数量</th>
      <th>厂商指导价</th>
      <th class="align-center"><?php echo $lang['nc_handle'];?></th>
    </tr>
    </thead>
    <tbody>
    <?php if ( !empty($output['xzj_list']) && is_array($output['xzj_list']) ) {?>
      <?php foreach ($output['xzj_list'] as $v) {?>
        <tr class="hover edit">
          <td><span class="red"><?php echo $v['xzj_title'];?></span></td>
          <td><?php if ($v['xzj_yc']){echo '是';}else{echo '否';} ?></td>
          <td><?php if ($v['xzj_front']){echo '是';}else{echo '否';} ?></td>
          <td><?php echo $v['xzj_brand']; ?></td>
          <td><?php echo $v['xzj_model']; ?></td>
          <td><?php echo $v['xzj_max_num']; ?></td>
          <td>¥ <?php echo $v['xzj_guide_price']; ?></td>
          <td class="w96 align-center"><a href="index.php?act=hgsoft&op=xzj_edit&id=<?php echo $v['id'];?>"><?php echo $lang['nc_edit'];?></a> | <a onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='index.php?act=hgsoft&op=xzj_del&id=<?php echo $v['id'];?>&carid=<?php echo $v['car_brand'];?>';}else{return false;}" href="javascript:void(0);"><?php echo $lang['nc_del'];?></a></td>
        </tr>
      <?php }?>
    <?php }else{ ?>
      <tr class="no_data">
        <td colspan="8"><?php echo $lang['nc_no_record'];?></td>
      </tr>
    <?php }?>
    </tbody>
    <?php if(!empty($output['xzj_list']) && is_array($output['xzj_list'])){ ?>
      <tfoot>
      <tr>
        <td id="dataFuncs" colspan="9">
          <div class="pagination"> <?php echo $output['page'];?> </div></td>
      <tr>
      </tfoot>
    <?php }?>
  </table>
  <?php endif;?>
</div>
