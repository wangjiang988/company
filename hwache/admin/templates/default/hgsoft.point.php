<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>城市参考点管理</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="index.php?act=hgsoft&op=pointadd" ><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th class="align-center">省市地区</th>
        <th class="align-center">坐标</th>
        <th class="align-center">操作</th>
      </tr>
    <tbody>
      <?php if(!empty($output['point_list']) && is_array($output['point_list'])){ ?>
      <?php foreach($output['point_list'] as $k => $v){ ?>
      <tr class="hover member">
        <td class="align-center"><p class="name"><strong><?php echo $v['title']; ?></strong></p></td>
        <td class="align-center"><?php echo $v['point']; ?></td>
        <td class="align-center"><a href="index.php?act=hgsoft&op=pointedit&id=<?php echo $v['id']; ?>"><?php echo $lang['nc_edit']?></a></td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="10"><?php echo $lang['nc_no_record']?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot class="tfoot">
      <?php if(!empty($output['point_list']) && is_array($output['point_list'])){ ?>
      <tr>
        <td colspan="16">
          <div class="pagination"> <?php echo $output['page'];?> </div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('member');$('#formSearch').submit();
    });
});
</script>
