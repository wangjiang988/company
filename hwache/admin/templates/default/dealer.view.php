<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<style>
#region select{width:auto;}
#user_form .table .hide{display:none;}
tr td label{margin-right:10px}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>经销商管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=dealer&op=index" ><span>管理</span></a></li>
        <li><a href="index.php?act=dealer&op=dealer_add" ><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr class="noborder">
        <td><label class="required">显示状态:</label><?=$output['dealer_info']['d_is_show']?'显示':'始终不显示';?></td>
      </tr>
      <tr class="noborder">
        <td><label class="required">类别:</label>授权4s</td>
        <td><label>经销商编号:</label><?=$output['dealer_info']['d_id'];?></td>
      </tr>
      <tr class="noborder">
        <td><label class="required">销售品牌:</label><?=$output['dealer_info']['gc_name'];?></td>
      </tr>
      <tr class="noborder">
        <td><label class="required">归属地区:</label><?=str_replace("	",'',$output['dealer_info']['d_areainfo']);?></td>
      </tr>
        <tr class="noborder">
          <td><label class="required">经销商名称:</label><?=$output['dealer_info']['d_name'];?></td>
        </tr>
        <tr class="noborder">
          <td><label class="required">营业地点:</label><?=$output['dealer_info']['d_yy_place'];?></td>
          <td><label class="required">经度坐标:</label><?=$output['dealer_info']['d_lngx'];?></td>
          <td><label class="required">纬度坐标:</label><?=$output['dealer_info']['d_lngy'];?></td>
        </tr>
        <tr class="noborder">
          <td><label class="required">交车地点:</label>
              <?php if($output['dealer_info']['d_yy_place']==$output['dealer_info']['d_jc_place']):?>
              与营业地点相同</td>
          <?php else:?>
            <?=$output['dealer_info']['d_jc_place'];?></td>
            <td class="required"><label>经度坐标:</label><?=$output['dealer_info']['d_lngx'];?></td>
            <td class="required"><label>纬度坐标:</label><?=$output['dealer_info']['d_lngy'];?></td>
          <?php endif;?>
        </tr>
      <tr class="noborder">
        <td><label class="required">联系电话:</label><?=$output['dealer_info']['d_tel'];?></td>
      </tr>
      <tr class="noborder">
        <td><label class="required">备注:</label><?=$output['dealer_info']['d_dealer_content'];?></td>
      </tr>
      <tr class="noborder">
        <td><label class="required">编辑提交人:</label><?=$output['admin']['admin_truename'];?></td>
        <td><label class="required">最后编辑时间:</label><?=$output['dealer_info']['d_updated_at'];?></td>
      </tr>
      <?php if($output['dealer_info']['d_is_show']==0):?>
      <tr class="noborder">
        <td><label class="required">不显示原因:</label><?=$output['dealer_info']['d_is_hide_reason'];?></td>
      </tr>
      <?php endif;?>
      </tbody>
    </table>
  </form>
</div>
<div class="text-center">
  <?php if($output['dealer_info']['d_is_show']):?>
    <input type="hidden" name="d_id" id="d_id" value="<?=$output['dealer_info']['d_id'];?>">
    <button type="button" id="ban_button" class="btn btn-info">不显示</button>
  <?php endif;?>
    <button type="button" id="return_button" onclick="window.history.go(-1)" class="btn btn-default">返回</button>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript">
  $(function() {
    $(".table td").css("border", "none");
  })

  $("#ban_button").click(function(){
    layer.prompt({'title':'请输入始终不显示原因',formType:2},function(val, index){
      layer.close(index);

      $.ajax({
        type: 'post',
        url:   '/index.php?act=dealer&op=ajax_hidedealer',
        data : {
          d_id:$("#d_id").val(),
          d_is_hide_reason:val
        },
        dataType: 'json',
        success : function(result){
          if(result.error_code){
            layer.msg(result.error_msg);
          }else{
            window.location.reload();
          }
        }
      });


    });
  })

</script>
