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

<div class="item-publish">
  <form method="post" id="add" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="dealer_id" value="<?php echo $output['dealer_id']; ?>" />
    <input type="hidden" name="car_brand" value="<?php echo $output['car_brand']; ?>" />
    <div class="ncsc-form-goods">
      <dl>
        <dt><i class="required">*</i>是否必须前装<?php echo $lang['nc_colon'];?></dt>
        <dd><input type="radio" name="xzj_front" value="1">是 <input type="radio" name="xzj_front" value="0" checked=""> 否</dd>
      </dl>
      <dl>
        <dt><i class="required"></i>名称<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <INPUT name='xzj_title' class='w300' type='text' value=''>
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>品牌<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="xzj_brand" value="">
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>型号<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="xzj_model" value="">
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>每车最多安装数<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="xzj_max_num" value="">
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>拥有数量<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="xzj_has_num" value="1">
        </dd>
      </dl>
      <dl>
        <dt><i class="required"></i>安裝费<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="xzj_fee" value="">
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>价格<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="xzj_price" value="">
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">

        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
