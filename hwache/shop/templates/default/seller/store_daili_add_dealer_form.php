<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">选择经销商</a></li>
  </ul>
</div>
<!-- <form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="dealer" />
    <input type="hidden" name="op" value="index" />
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['store_goods_index_store_goods_class'];?></th>
      <td class="w160"><select name="stc_id" class="w150">
          <option value="0"><?php echo $lang['nc_please_choose'];?></option>
          <?php if(is_array($output['store_goods_class']) && !empty($output['store_goods_class'])){?>
          <?php foreach ($output['store_goods_class'] as $val) {?>
          <option value="<?php echo $val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $val['stc_id']){ echo 'selected=selected';}?>><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $child_val['stc_id']){ echo 'selected=selected';}?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php }?>
          <?php }?>
        </select></td>
      <th>
        <select name="search_type">
          <option value="0" <?php if ($_GET['type'] == 0) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_name'];?></option>
          <option value="1" <?php if ($_GET['type'] == 1) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_no'];?></option>
          <option value="2" <?php if ($_GET['type'] == 2) {?>selected="selected"<?php }?>>平台货号</option>
        </select>
      </th>
      <td class="w160"><input type="text" class="text w150" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form> -->
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th>经销商名称</th>
      <th class="w200">归属地</th>
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['dealer_list'])) { ?>
    <?php foreach ($output['dealer_list'] as $val) { ?>
    <tr>
      <th colspan="20">&nbsp;&nbsp;经销商ID：<?php echo $val['d_id'];?></th>
    </tr>
    <tr>
      <td><span><?php echo $val['d_name']; ?></span></td>
      <td><span><?php echo $val['d_areainfo']; ?></span></td>
      <td class="nscs-table-handle">
        <span><a href="<?php echo urlShop('store_setting', 'select_dealer', array('id' => $val['d_id']));?>" class="btn-blue"><i class="icon-edit"></i><p>选择</p></a></span>
      </td>
    </tr>
    <tr style="display:none;"><td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td></tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['dealer_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>