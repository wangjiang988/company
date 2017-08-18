<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>客户提交的特殊文件</h3>
      <ul class="tab-base"></ul>
    </div>
  </div>

  <div class="fixed-empty"></div>
  <form action="index.php" method="get">
    <input type="hidden" name="act" value="fwsetting">
    <input type="hidden" name="op" value="tellus">
    <select name="status" id="">
      <option value="0">未处理</option>
      <option value="1">已添加</option>
    </select>
    <input type="submit" value="提交">
  </form>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w270">文件名称</th>
          
          <th>客户</th>
          <th>状态</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td><?php echo $v['title']; ?></td>
          <td><?php echo $v['username']?></td>
          <td><?php if($v['status']==1){echo '已添加';}else{ echo "未处理";} ?></td>
          
          <td class="align-center"><a href="index.php?act=fwsetting&op=dotellus&id=<?php echo $v['id']?>">处理</a>  </td>
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