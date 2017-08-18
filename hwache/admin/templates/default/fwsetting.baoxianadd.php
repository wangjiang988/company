<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>保险公司设置</h3>
      <ul class="tab-base"><li><a href="index.php?act=fwsetting&op=baoxian"><span>保险公司设置</span></a></li><li><a class="current"><span>添加</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td class="w120 tar">是否支持全国联保<?php echo $lang['nc_colon'];?></td>
          <td>
            <label><input type="checkbox" name="bx_is_quanguo"  value="1" /> </label>
          </td>
        </tr>
        <tr>
          <td class="tar">保险公司名称<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="bx_title" value="" />
          </td>
        </tr>
        <tr>
          <td class="tar">显示标题<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="bx_name" value="" />
          </td>
        </tr>
        
        
        <tr>
          <td class="tar">排序<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w60" name="bx_sort" value="255" />
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

  

  // 表单验证
  $("#submitBtn").click(function(){
    // 选装件名称
    if ($("input[name=bx_name]").val().length==0) {
      alert('请输入保险公司名称');
      $("input[name=bx_name]").focus();
      return false;
    };

    // 品牌
    if ($("input[name=bx_title]").val().length==0) {
      alert('请输入显示名称');
      $("input[name=bx_title]").focus();
      return false;
    };
    
    

    $("#form").submit();
  });

});
</script>