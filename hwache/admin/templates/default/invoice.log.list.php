<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
   <div class="fixed-bar">
    <div class="item-title">
      <h3>发票管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=invoice&op=index&i_type=1" class="<?php if($_GET['i_type']==1){echo 'current';}?>"><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=invoice&op=index&i_type=2" class="<?php if($_GET['i_type']==2){echo 'current';}?>"><span><?php echo '完成处理';?></span></a></li>
        <li><a href="index.php?act=invoice&op=index&i_type=3" class="<?php if($_GET['i_type']==3){echo 'current';}?>"><span><?php echo '超时未开';?></span></a></li>
        <li><a href="index.php?act=invoice&op=invoice_manage_list" class="<?php if($_GET['i_type']==4){echo 'current';}?>"><span><?php echo '空白发票';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>发票管理</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>发票管理备注。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th class="align-center">操作时间</th>
        <th class="align-center">操作人</th>
        <th class="align-center">操作</th>
      </tr>
     </thead>
     <?php foreach($output['log'] as $K=>$v){?>
      <tr >
        <td class="align-center"><?php echo $v['date'];?></td>
        <td class="align-center"><?php echo $v['op_name'];?></td>
        <td class="align-center"><?php echo $v['note'];?></td>
      </tr>
      <?php }?>
      
     <tr>
        <td colspan=3>
        <div class="pagination">
        <?php echo $output['show_page'];?>
        </div>
        </td>
        
      </tr>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">

</script>
