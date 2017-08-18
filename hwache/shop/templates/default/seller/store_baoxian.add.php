<?php
/**
 * 经销商代理
 * 报价保险添加模板
 */
defined('InHG') or exit('Access Invalid!');
?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style>
  .ncsc-form-goods dl dt { width: 15%; }
  .ncsc-form-goods dl dd { width: 82%; }
</style>
<ul class="add-goods-step">
  <li class="current"><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>选择保险公司</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>填写保险费率</h2>
  </li>
</ul>
<div class="item-publish">
  <form method="post" id="add" action="<?php if ($output['edit_baoxian']) { echo urlShop('store_baoxian', 'edit');} else { echo urlShop('store_baoxian', 'add');}?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncsc-form-goods">
      <dl>
        <dt><i class="required">*</i>标题<?php echo $lang['nc_colon'];?></dt>
        <dd><input type="text" class="text w300" name="title" value="<?php echo $bx['title'];?>"></dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>选择保险公司<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <select name="co_id" id="">
            <?php foreach($output['bxCom'] as $k=>$v){ ?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php } ?>
          </select>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>是否启用<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
              <li><label><input type="radio" name="enable" value="1" checked="checked">启用</label></li>
              <li><label><input type="radio" name="enable" value="0">不启用</label></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>是否为默认保险<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
              <li><label><input type="radio" name="is_default" value="1" >是</label></li>
              <li><label><input type="radio" name="is_default" value="0" checked="checked">否</label></li>
          </ul>
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="下一步，设置保险费率" />
      </label>
    </div>
  </form>
</div>
