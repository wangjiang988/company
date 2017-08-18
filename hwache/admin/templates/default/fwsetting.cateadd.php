<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>文件设置</h3>
      <ul class="tab-base"><li><a href="index.php?act=fwsetting&op=files"><span>交车需要的文件设置</span></a></li>
      <li><a href="index.php?act=fwsetting&op=fileadd"><span>添加资料</span></a></li>
      <li><a class="current"><span>添加场合</span></a></li></ul>

    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        
        <tr>
          <td class="tar">场合名称<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="cate" value="" />
          </td>
        </tr>
        <tr>
          <td class="tar">是否正规场合<?php echo $lang['nc_colon'];?></td>
          <td>
            <select name="regular" id="">
              <option value="1" selected="">正规场合</option>
              <option value="0" >特殊文件</option>
            </select>
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
    if ($("input[name=cate]").val().length==0) {
      alert('请输入场合名称');
      $("input[name=cate]").focus();
      return false;
    };
    

    $("#form").submit();
  });

});
</script>