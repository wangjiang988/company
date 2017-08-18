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
<ul class="add-goods-step">
  <li class="current"><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>车型经销商</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>选装件报价</h2>
  </li>
</ul>
<div class="item-publish">
  <form method="post" id="xzj_add">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncsc-form-goods">
      <h3>选装件</h3>
      <dl>
        <dt>车型品牌<?php echo $lang['nc_colon'];?></dt>
        <dd id="gcategory">
          <input type="hidden" class="mls_id" name="class_id" value="" />
          <input type="hidden" class="mls_name" name="class_name" value="" />
          <select class="class-select">
            <option value="0"><?php echo $lang['nc_please_choose'];?>...</option>
            <?php if(!empty($output['xzjSelect'])): ?>
            <?php foreach($output['xzjSelect'] as $k => $v): ?>
            <option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
            <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <p id="err" style="display:none;"></p>
        </dd>
      </dl>
      <dl>
        <dt>经销商<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <select name="d_id" id="d_id">
          <?php foreach($output['dealer_list'] as $k=>$v){ ?>
            <option value="<?php echo $v['d_id'];?>"><?php echo $v['d_name'];?></option>
          <?php } ?>
        </select>
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="下一步" />
      </label>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog-min.js"></script>
<script>
  $(function(){
    var _carid = 0,
        _dealerid = $('#d_id').val(),
        _submitOk = false,
        _msg      = '请选择车型';
    // 车型选择
    $(".class-select").live("change", function(){
      var _this = $(this)
      if ($(".class-select").length == 3) {
        _carid = parseInt(_this.val());
        if (!isNaN(_carid)) {
          _ajax();
        }
      };
    });

    // 经销商
    $("#d_id").change(function(){
      _dealerid = $(this).val();
      if ($(".class-select").length == 3) {
        _ajax();
      } else {
        _d();
      }
    });

    var _ajax = function() {
      $.getJSON("<?php echo urlShop('store_xzj', 'ajax_get_xzj_cardealer')?>",{carid:_carid,dealerid:_dealerid}, function(d){
        if (d.code == 1) {
          // 可以添加
          _submitOk = true;
        } else {
          // 没有选装件
          _submitOk = false;
          _msg      = d.msg;
          _d();
        };
      });
    }

    $("#xzj_add").submit(function(){
      if (!_submitOk) {
        _d();
        return false;
      }
    });

    var _d = function() {
      var d = dialog({
        content: _msg
      }).show();
      setTimeout(function () {
        d.close().remove();
      }, 2000);
    }

  });

  // 所属分类
  gcategoryInit('gcategory');
</script>
