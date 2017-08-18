<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>选装件管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=hgsoft&op=xzj"><span>指定车型选装件列表</span></a></li>
        <li><a href="index.php?act=hgsoft&op=xzj_common"><span>通用选装件管理</span></a></li>
        <li><a class="current" href="javascript:;"><span>添加通用选装件</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td class="w120 tar">是否必须前装<?php echo $lang['nc_colon'];?></td>
          <td>
            <label><input type="checkbox" name="front" /> 必须前装</label>
          </td>
        </tr>
        <tr>
          <td class="tar">名称<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="name" value="" />
          </td>
        </tr>
        <tr>
          <td class="tar">品牌<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="brand" value="" />
          </td>
        </tr>
        <tr>
          <td class="tar">型号<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="model" value="" />
          </td>
        </tr>
        <tr>
          <td class="tar">每车最多安装数<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w60" name="max_num" value="" />
          </td>
        </tr>
        <tr>
          <td class="tar">排序<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w60" name="sort" value="255" />
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td></td>
          <td>
            <a id="submitBtn" class="btn" href="javascript:void(0);"> <span><?php echo $lang['nc_submit'];?></span> </a>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){

  // 数量 价格
  $("input[name=max_num],input[name=guide_price]").keyup(function(){
    var t = $(this);
    if (isNaN(t.val())) {
      t.val('');
    };
  });

  // 表单验证
  $("#submitBtn").click(function(){
    // 选装件名称
    if ($("input[name=name]").val().length==0) {
      alert('请输入名称');
      $("input[name=name]").focus();
      return false;
    };

    // 品牌
    if ($("input[name=brand]").val().length==0) {
      alert('请输入品牌');
      $("input[name=brand]").focus();
      return false;
    };
    // 型号
    if ($("input[name=model]").val().length==0) {
      alert('请输入型号');
      $("input[name=model]").focus();
      return false;
    };
    // 最多安装数
    if ($("input[name=max_num]").val().length==0) {
      alert('请输入最多安装数');
      $("input[name=max_num]").focus();
      return false;
    };

    $("#form").submit();
  });

});
</script>