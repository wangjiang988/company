<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>其他保险设置</h3>
      <ul class="tab-base"><li><a href="index.php?act=fwsetting&op=otherBaoxian"><span>其他商业保险设置</span></a></li><li><a class="current"><span>添加</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        
        <tr>
          <td class="tar">保险名称<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="title" value="" />
          </td>
        </tr>
        
        <tr>
          <td class="tar">主险类别<?php echo $lang['nc_colon'];?></td>
          <td><select name="cate_id" id="cate_id">
              <?php foreach ($output['zhuxian'] as $key => $value): ?>
                <option value="<?php echo $key ?>" ><?php echo $value ?></option>
              <?php endforeach ?>

            </select></td>
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
    if ($("input[name=title]").val().length==0) {
      alert('请输入赠品名称');
      $("input[name=bx_name]").focus();
      return false;
    };
    

    $("#form").submit();
  });

});
</script>