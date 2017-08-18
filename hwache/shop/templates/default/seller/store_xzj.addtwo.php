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
  <li><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>车型经销商</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>选装件报价</h2>
  </li>
</ul>
<div class="item-publish">
  <form method="post" id="xzj_add">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="dlxzjid" value="<?php echo $output['dlxzjid'] ?>" />
    <div class="ncsc-form-goods">
      <h3>选装件</h3>
      <dl>
        <dt>车型品牌<?php echo $lang['nc_colon'];?></dt>
        <dd><?php echo $output['carTitle'];?></dd>
      </dl>
      <dl>
        <dt>经销商<?php echo $lang['nc_colon'];?></dt>
        <dd><?php echo $output['dealerTitle'];?></dd>
      </dl>
      <dl>
        <dt>车型选装件管理<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <p>原厂选装件</p>
          <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
            <thead>
              <tr>
                <th class="w10"></th>
                <th class="w50">名称</th>
                <th>原厂</th>
                <th>品牌</th>
                <th>型号</th>
                <th>每车最多安装数</th>
                <th>数量</th>
                <th>厂商指导价</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($output['xzjCarList'] as $k => $v): ?>
              <tr id="xzj<?php echo $v['id'];?>" data-yc="<?php if($v['xzj_yc']){echo 1;}else{echo 0;}?>" data-guideprice="<?php echo $v['xzj_guide_price'];?>">
                <td><input type="checkbox" name="xzj[<?php echo $v['id'] ?>][id]" value="<?php echo $v['id'] ?>"></td>
                <td><?php echo $v['xzj_title'];?></td>
                <td><?php if($v['xzj_yc']){echo '是';}else{echo '-';}?></td>
                <td><?php if($v['xzj_yc']){echo '-';}else{echo $v['xzj_brand'];}?></td>
                <td><?php echo $v['xzj_model'];?></td>
                <td><?php echo $v['xzj_max_num'];?></td>
                <td><input type="text" value="1" name="xzj[<?php echo $v['id'] ?>][has_num]"></td>
                <td><?php echo $v['xzj_guide_price'];?></td>
              </tr>
            <?php endforeach;?>
            </tbody>
          </table>
          
          
        </dd>
      </dl>
      
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="保存" />
      </label>
    </div>
  </form>
</div>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog-min.js"></script>
<script>
  $(function(){
    var _msg = "",
        _id  = null,
        //
        _d = function() {
          var d = dialog({
            content: _msg
          }).show();
          setTimeout(function () {
            d.close().remove();
          }, 2000);
        };
    // 计算总价格
    var _count = function() {
      if (_id !== null) {
        var _p = 0,
            _e = $("#"+_id),
            _discount = parseInt(_e.find("input[data-hg='discount']").val()),
            _fee = parseFloat(_e.find("input[data-hg='fee']").val());
        if (_e.attr("data-yc") == 1) {
          var _gp = parseFloat(_e.attr("data-guideprice"));
          _p = _gp * (_discount / 100) + _fee;
          _e.find("input[data-hg='price']").val(_p);
        };
      };
    };
    // 安装费
    $("input[data-hg='fee']").blur(function() {
      _id = $(this).parent().parent().attr("id");
      var _fee = parseFloat($(this).val());
      if (!isNaN(_fee)) {
        if (_fee < 0) {
          $(this).val("0");
        } else {
          var _currentfee = Math.floor(_fee*100)/100;
          $(this).val(_currentfee);
        };
        // 更新总价格
        _count();
      }
    });
    // 折扣率
    $("input[data-hg='discount']").blur(function() {
      _id = $(this).parent().parent().attr("id");
      var _discount = parseInt($(this).val());
      if (!isNaN(_discount)) {
        if (_discount > 100 || _discount < 0) {
          $(this).val("100");
        } else {
          $(this).val(_discount);
        };
        // 更新总价格
        _count();
      }
    });
    // 折扣率
    $("input[data-hg='price']").blur(function() {
      var _fee = parseFloat($(this).val());
      if (!isNaN(_fee)) {
        if (_fee < 0) {
          $(this).val("0");
        } else {
          var _currentfee = Math.floor(_fee*100)/100;
          $(this).val(_currentfee);
        };
      }
    });



  });
</script>
