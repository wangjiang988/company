<?php
/**
 * 选装件
 */
defined('InHG') or exit('Access Invalid!');
?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui-dialog.css" rel="stylesheet" type="text/css">
<style>
  .ncsc-form-goods dl dt { width: 15%; }
  .ncsc-form-goods dl dd { width: 82%; }
</style>

<div class="item-publish">
  <form method="post" id="xzj_add">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="op" value="add" />
    <div class="ncsc-form-goods">
      <h3>刷卡设置</h3>
      <dl>
        <dt>信用卡免费刷卡次数<?php echo $lang['nc_colon'];?></dt>
        <dd id="gcategory">
          <input type="text" name="credit" value="1">
        </dd>
      </dl>
      <dl>
        <dt>借记卡免费刷卡次数<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="debit" value="1">
        </dd>
      </dl>
      <dl>
        <dt>信用卡超过免费刷卡次数收费标准<?php echo $lang['nc_colon'];?></dt>
        <dd>
        <input type="radio" name="credit_type" value="1" checked=""> <span>按刷卡额比例收费</span>
            <input type="text" name="credit_rate" value=""> %
          <p>
          <input type="radio" name="credit_type" value="2"> <span>按定额收费</span>
             每次<input type="text" name="credit_quota" value="">
          </p>
          <input type="radio" name="credit_type" value="3"> <span>两者均可</span>
        </dd>
      </dl>
      <dl>
        <dt>借记卡超过免费刷卡次数收费标准<?php echo $lang['nc_colon'];?></dt>
        <dd>
        <input type="radio" name="debit_type" value="1" checked> <span>按刷卡额比例收费</span>
            <input type="text" name="debit_rate" value=""> %
          <p>
          <input type="radio" name="debit_type" value="2"> <span>按定额收费</span>
             每次<input type="text" name="debit_quota" value="">
          </p>
          <input type="radio" name="debit_type" value="3"> <span>两者均可</span>
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="添加" />
      </label>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog-min.js"></script>

