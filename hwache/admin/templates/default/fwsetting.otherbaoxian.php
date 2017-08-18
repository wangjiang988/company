<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>其他商业保险设置</h3>
      <ul class="tab-base"><li><a class="current"><span>其他商业保险</span></a></li><li><a href="index.php?act=fwsetting&op=otherbaoxianadd"><span>添加</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w270">保险名称</th>
          
          <th>所属主险</th>
          
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td><?php echo $v['baoxian_name']; ?></td>
          
          <td><?php echo $v['zhuxian']?></td>
          
          <td class="align-center"><a href="index.php?act=fwsetting&op=otherbaoxianedit&id=<?php echo $v['id']?>">编辑</a>  </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="5"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <tr class="tfoot">
          <td colspan="20"><div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  <div class="clear"></div>
</div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>