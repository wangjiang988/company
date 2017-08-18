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
  <form method="post" action="index.php?act=store_shuaka&op=edit">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="op" value="edit" />
    <input type="hidden" name="id" value="<?php echo $output['list']['id']; ?>" />
    <div class="ncsc-form-goods">
      <h3>刷卡设置</h3>
      <dl>
        <dt>信用卡免费刷卡次数<?php echo $lang['nc_colon'];?></dt>
        <dd id="gcategory">
          <input type="text" name="credit" value="<?php echo $output['list']['credit']; ?>">
        </dd>
      </dl>
      <dl>
        <dt>借记卡免费刷卡次数<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input type="text" name="debit" value="<?php echo $output['list']['debit']; ?>">
        </dd>
      </dl>
      <dl>
        <dt>信用卡超过免费刷卡次数收费标准<?php echo $lang['nc_colon'];?></dt>
        <dd>
        <input type="radio" name="credit_type" value="1" <?php if ($output['list']['credit_type']==1): ?>
            checked
          <?php endif ?>> <span>按刷卡额比例收费</span>
            <input type="text" name="credit_rate" value="<?php echo $output['list']['credit_rate']; ?>"> %
          <p>
          <input type="radio" name="credit_type" value="2" <?php if ($output['list']['credit_type']==2): ?>
            checked
          <?php endif ?>> <span>按定额收费</span>
             每次<input type="text" name="credit_quota" value="<?php echo $output['list']['credit_quota']; ?>">
          </p>
          <input type="radio" name="credit_type" value="3" <?php if ($output['list']['credit_type']==3): ?>
            checked
          <?php endif ?>> <span>两者均可</span>
        </dd>
      </dl>
      <dl>
        <dt>借记卡超过免费刷卡次数收费标准<?php echo $lang['nc_colon'];?></dt>
        <dd>
        <input type="radio" name="debit_type" value="1" <?php if ($output['list']['debit_type']==1): ?>
            checked
          <?php endif ?>> <span>按刷卡额比例收费</span>
            <input type="text" name="debit_rate" value="<?php echo $output['list']['debit_rate']; ?>"> %
          <p>
          <input type="radio" name="debit_type" value="2" <?php if ($output['list']['debit_type']==2): ?>
            checked
          <?php endif ?>> <span>按定额收费</span>
             每次<input type="text" name="debit_quota" value="<?php echo $output['list']['debit_quota']; ?>">
          </p>
          <input type="radio" name="debit_type" value="3" <?php if ($output['list']['debit_type']==3): ?>
            checked
          <?php endif ?>> <span>两者均可</span>
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="保存修改" />
      </label>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog-min.js"></script>

