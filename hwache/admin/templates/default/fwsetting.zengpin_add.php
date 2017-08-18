<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>赠品设置</h3>
      <ul class="tab-base"><li><a href="index.php?act=fwsetting&op=zengpin"><span>赠品设置</span></a></li><li><a class="current"><span>添加</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        
        <tr>
          <td class="tar">赠品名称<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="title" value="" />
          </td>
        </tr>
        <!--<tr>
          <td class="tar">价值<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="price" value="" /> 元
          </td>
        </tr>
        <tr>
          <td class="tar">所属车型<?php echo $lang['nc_colon'];?></td>
          <td><select name="brand_id" id="brand_id">
              <option value="0"><?php echo $lang['nc_please_choose'];?>...</option>
              <?php if(!empty($output['parent_list']) && is_array($output['parent_list'])){ ?>
              <?php foreach($output['parent_list'] as $k => $v){ ?>
              <option <?php if($output['gc_parent_id'] == $v['gc_id']){ ?>selected='selected'<?php } ?> value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>-->
<!--        <tr>-->
<!--          <td class="tar">备注--><?php //echo $lang['nc_colon'];?><!--</td>-->
<!--          <td>-->
<!--            <textarea name="beizhu" id="" cols="30" rows="10"></textarea>-->
<!--          </td>-->
<!--        </tr>-->
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