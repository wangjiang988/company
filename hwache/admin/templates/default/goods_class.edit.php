<?php defined('InHG') or exit('Access Invalid!');?>

<style>
  .rowform .txt.w80{width:80px;}
  .rowform .txt.w120{width:120px;}
  .clear{clear: both;}
  .mt20{margin-top: 20px;}
  .add-wrapper input{height: 25px;float: left;width: 220px;padding-left: 5px;outline: none;}
  .add-wrapper img{margin-left: 10px;margin-bottom: 10px;}
  .add-wrapper p:after{clear: both;}
   .add-wrapper a{border:0px;background: transparent;padding: 0;margin: 0;}
  .list-wrapper{position: relative;}
  .list-wrapper i{position: absolute;font-style: normal;left:203px;top:2px;color: #f00;display: none;width: 25px;height: 25px;cursor: pointer;text-align: center;}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['goods_class_index_class'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=goods_class&op=goods_class"><span><?php echo $lang['nc_manage'];?></span></a></li>
        <li><a href="index.php?act=goods_class&op=goods_class_add"><span><?php echo $lang['nc_new'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_edit'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['goods_class_edit_prompts_one'];?></li>
            <li><?php echo $lang['goods_class_edit_prompts_two'];?></li>
            <li><?php echo $lang['goods_class_edit_prompts_three'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form id="goods_class_form" name="goodsClassForm" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gc_id" value="<?php echo $output['class_array']['gc_id'];?>" />
    <input type="hidden" name="gc_parent_id" id="gc_parent_id" value="<?php echo $output['class_array']['gc_parent_id'];?>" />
    <input type="hidden" name="old_type_id" value="<?php echo $output['class_array']['type_id'];?>">
    <input type="hidden" name="depth" value="<?php echo $output['depth'];?>">
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="gc_name validation" for="gc_name"><?php echo $lang['goods_class_index_name'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" maxlength="200" value="<?php echo $output['class_array']['gc_name'];?>" name="gc_name" id="gc_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
            <td colspan="2" class="required"><label class="gc_name validation" for="vehicle_model"><?php echo $lang['goods_class_vehicle_model'];?>:</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform"><input type="text" maxlength="200" value="<?php echo $output['class_array']['vehicle_model'];?>" name="vehicle_model" id="vehicle_model" class="txt"></td>
            <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2"><label for="pic"><?php echo '分类图片';?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo $output['class_array']['pic'];?>"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' />
                <input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="pic" type="file" class="type-file-file" id="pic" size="30" hidefocus="true" nc_type="change_pic">
            </span></td>
          <td class="vatop tips">建议尺寸100px * 100px</td>
        </tr>
        <tr style="display:none;">
          <td colspan="2" class="required" id="gcategory">
            <label for="gc_name"><?php echo $lang['goods_class_add_type'];?>:</label>
            <select class="class-select">
              <option value="0"><?php echo $lang['nc_please_choose'];?>...</option>
              <?php if(!empty($output['gc_list'])){ ?>
              <?php foreach($output['gc_list'] as $k => $v){ ?>
              <?php if ($v['gc_parent_id'] == 0) {?>
              <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
            <?php echo $lang['nc_quickly_targeted'];?>
          </td>
        </tr>
        <tr style="display:none;" class="noborder">
          <td class="vatop rowform">
          <input type="hidden" name="t_name" id="t_name" value="<?php echo $output['class_array']['type_name'];?>" />
          <input type="hidden" name="t_sign" id="t_sign" value="" />
          <div style="position:relative; max-height:240px; overflow: hidden;" id="type_div"><div>
          	<dl style="margin:10px 0;">
          	<dd style="display:inline-block; margin-right:10px;">
          	  <input type="radio" name="t_id" value="0" <?php if($output['class_array']['type_id'] == 0){?>checked="checked"<?php }?> />
              <span><?php echo $lang['goods_class_null_type'];?></span>
          	</dd>
          	</dl>
            <?php if(!empty($output['type_list'])){?>
            <?php foreach($output['type_list'] as $k=>$val){?>
            <?php if(!empty($val['type'])){?>
            <dl style="margin:10px 0;"><dt id="type_dt_<?php echo $k;?>" style="font-weight:600;"><?php echo $val['name']?></dt>
            <?php foreach($val['type'] as $v){?>
            <dd style="display:inline-block; margin-right:10px;">
              <input type="radio" name="t_id" value="<?php echo $v['type_id']?>" <?php if($output['class_array']['type_id'] == $v['type_id']){?>checked="checked"<?php }?> />
              <span><?php echo $v['type_name'];?></span>
            </dd>
            <?php }?>
            </dl>
            <?php }?>
            <?php }?>
            <?php }?>
          </div></div>
          <div class=" mtm"><input type="checkbox" name="t_associated" value="1" checked="checked" id="t_associated" /><label for="t_associated"><?php echo $lang['goods_class_edit_related_to_subclass'];?></label></div>
          </td>
          <td class="vatop tips"><?php echo $lang['goods_class_add_type_desc_one'];?><a onclick="window.parent.openItem('type,type,goods')" href="JavaScript:void(0);"><?php echo $lang['nc_type_manage'];?></a><?php echo $lang['goods_class_add_type_desc_two'];?></td>
        </tr>
        <?php
          // 第三级别车型专用数据
          if($output['depth'] == 1) {
        ?>
        <?php
          // 循环查询出来的自定义字段数据
          foreach ($output['carmodel_fields'] as $k => $v) {
        ?>
        <tr>
          <td colspan="2" class="required"><label><?php echo $v['title']; ?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <?php
              switch ($v['type']) {
                case 'text' :
                case 'number' :
                case 'float' :
            ?>
            <p><input name="carmodel[<?php echo $v['name'] ?>]" value="<?php echo $output['car_info'][$v['name']] ?>" type="text" class="txt w120 hg-validate" /></p>
            <?php
                break;
                case 'datetime' :
            ?>
            <p><input name="carmodel[<?php echo $v['name'] ?>]" value="<?php echo $output['car_info'][$v['name']] ?>" type="text" class="txt w120 hg-validate" onclick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM'})" /></p>
            <?php
                break;
                case 'textarea' :
            ?>
            <p><textares name="carmodel[<?php echo $v['name'] ?>]"><?php echo $output['car_info'][$v['name']] ?></textares></p>
            <?php
                break;
                case 'editor' :
            ?>
            <p><textares name="carmodel[<?php echo $v['name'] ?>]"><?php echo $output['car_info'][$v['name']] ?></textares></p>
            <?php
                break;
                case 'radio' :
            ?>
            <p>
              <?php
              if (!empty($v['setting'])) {
                $arr = unserialize($v['setting']);
                $i = 1;
                foreach ($arr as $key => $value) {
              ?>
                <label><input type="radio" name="carmodel[<?php echo $v['name']; ?>]"<?php if(isset($output['car_info'][$v['name']])&&$output['car_info'][$v['name']]==$key){echo ' checked="checked"';}else if($i==1){echo ' checked="checked"';}?> value="<?php echo $key; ?>" ><?php echo $value; ?></label>&nbsp;&nbsp;
              <?php
                $i++;
                }
              } else {
              ?>
              <textarea name="carmodel[<?php echo $v['name']; ?>]" rows="6"><?php echo $output['car_info'][$v['name']] ?></textarea>
              <?php
              }
              ?>
            </p>
            <?php
                break;
                case 'checkbox' :
            ?>
            <p>
              <?php
              if (!empty($v['setting'])) {
                $arr = unserialize($v['setting']);
                foreach ($arr as $key => $value) {
              ?>
                <label><input type="checkbox" name="carmodel[<?php echo $v['name']; ?>][]" value="<?php echo $key; ?>" ><?php echo $value; ?></label>&nbsp;&nbsp;
              <?php
                }
              } else {
              ?>
              <textarea name="carmodel[<?php echo $v['name']; ?>]" rows="6"><?php echo $output['car_info'][$v['name']] ?></textarea>
              <?php
              }
              ?>
            </p>
            <?php
                break;
                case 'select' :
            ?>
            <p>
              <?php
              if (!empty($v['setting'])) {
                $arr = unserialize($v['setting']);
              ?>
              <select name="carmodel[<?php echo $v['name']; ?>]">
                <?php foreach ($arr as $key => $value) { ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
              <?php
              } else {
              ?>
              <textarea name="carmodel[<?php echo $v['name']; ?>]" rows="6"><?php echo $output['car_info'][$v['name']] ?></textarea>
              <?php
              }
              ?>
            </p>
            <?php
              }
            ?>
          </td>
          <td class="vatop tips"><?php echo $v['desc'];?></td>
        </tr>

        <?php
          } // 循环自定义字段结束
        ?>
        <!-- <tr>
          <td colspan="2" class="required"><label for="gc_sort">出厂日期:</label></td>
        </tr> -->
        <!-- <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['chuchang_time'] ;?>" name="chuchang_time" id="chuchang_time" class="txt" onclick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM'})"></td>
          <td class="vatop tips"></td>
        </tr> -->
            <tr>
              <td colspan="2"><label for="detail-img"><?php echo '详情图片';?>:</label></td>
            </tr>
            <tr class="noborder">
              <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/preview.png">
            <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.'/'.$output['class_array']['detail_img'];?>"></div>
            </span><span class="type-file-box">
                  <input type='text' name='textfield2' id='textfield2' class='type-file-text' />
                <input type='button' name='button2' id='button2' value='' class='type-file-button' />
            <input name="detail_img" type="file" class="type-file-file" id="detail_img" size="30" hidefocus="true" nc_type="change_pic">
            </span></td>
              <td class="vatop tips">建议尺寸300px * 300px</td>
            </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort">备注:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><?php showEditor('beizhu',$output['class_array']['beizhu']);?></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort">官方详细参数链接:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['official_url'];?>" name="official_url" id="official_url" class="txt"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort">交强险:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['qiangxian'];?>" name="qiangxian" id="qiangxian" class="txt"> 元</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort">车船使用税:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['chechuan'];?>" name="chechuan" id="chechuan" class="txt"> 元</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort">车辆购置税:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['gouzhi'];?>" name="gouzhi" id="gouzhi" class="txt"> %</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort">汽车排量:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['pailiang'];?>" name="pailiang" id="pailiang" class="txt"> mL</td>
        </tr>


         <tr>
          <td colspan="2" class="required"><label for="gc_sort">随车工具:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop tips">
              <div class="tool-add-wrapper" data-type=2>
                <div class="tool-add-item">
      <?php if(count($output['vehicles']['tools'])) {?>
      <?php foreach($output['vehicles']['tools'] as $index=>$tool) {?>
      <?php if($index != count($output['vehicles']['tools']) - 1) { ?>
      <p class="add-wrapper list-wrapper">
        <input type="text" data-id="<?php echo $tool['id'];?>" value="<?php echo $tool['title'];?>" readonly="" class="list-item">
        <i>x</i>
        <a href="javascript:;"><img style="visibility: hidden;" src="/templates/default/images/add.png" /></a>
      </p>
      <?php } else {?>
        <p class="add-wrapper list-wrapper">
        <input type="text" data-id="<?php echo $tool['id'];?>" value="<?php echo $tool['title'];?>" readonly="" class="list-item">
        <i>x</i>
        <a href="javascript:;"><img src="/templates/default/images/add.png" /></a>
      </p>
      <?php } } } else { ?>
      <p class="add-wrapper empty-add-wrapper">
                    <input type="text" name="" id="" class="add-item">
                    <a href="javascript:;"><img src="/templates/default/images/add.png" /></a>
      </p>
      <?php } ?>

                </div>
                <div class="clear"></div>
              </div>
          </td>
        </tr>

        <tr>
          <td colspan="2" class="required"><label for="gc_sort">随车移交文件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
                        <div class="tool-add-wrapper" data-type=1>
                <div class="tool-add-item">
      <?php if(count($output['vehicles']['files'])) {?>
      <?php foreach($output['vehicles']['files'] as $index=>$tool) {?>
      <?php if($index != count($output['vehicles']['files']) - 1) { ?>
      <p class="add-wrapper list-wrapper">
        <input type="text" data-id="<?php echo $tool['id'];?>" value="<?php echo $tool['title'];?>" readonly="" class="list-item">
        <i>x</i>
        <a href="javascript:;"><img style="visibility: hidden;" src="/templates/default/images/add.png" /></a>
      </p>
      <?php } else {?>
        <p class="add-wrapper list-wrapper">
        <input type="text" data-id="<?php echo $tool['id'];?>" value="<?php echo $tool['title'];?>" readonly="" class="list-item">
        <i>x</i>
        <a href="javascript:;"><img src="/templates/default/images/add.png" /></a>
      </p>
      <?php } } } else { ?>
      <p class="add-wrapper empty-add-wrapper">
                    <input type="text" name="" id="" class="add-item">
                    <a href="javascript:;"><img src="/templates/default/images/add.png" /></a>
      </p>
      <?php } ?>

                </div>
                <div class="clear"></div>
              </div>
          </td>
        </tr>
        <?php
          } // 第三级别车型信息结束
        ?>
        <tr>
          <td colspan="2" class="required"><label for="gc_sort"><?php echo $lang['nc_sort'];?>:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['class_array']['gc_sort'] == ''?0:$output['class_array']['gc_sort'];?>" name="gc_sort" id="gc_sort" class="txt"></td>
          <td class="vatop tips"><?php echo $lang['goods_class_add_update_sort'];?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot"><td colspan="15" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/datetime/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>

<script type="text/template" id="add-new">
  <p class="add-wrapper empty-add-wrapper">
    <input type="text" name="" id="" class="add-item">
    <a href="javascript:;"><img src="/templates/default/images/add.png" /></a>
  </p>
</script>
<script>
$(document).ready(function(){
    $('#type_div').perfectScrollbar();
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
    if($("#goods_class_form").valid()){
      $("#goods_class_form").submit();
		}
	});

	$("#pic").change(function(){
		$("#textfield1").val($(this).val());
	});
    $("#detail_img").change(function(){
        $("#textfield2").val($(this).val());
    });
	$('input[type="radio"][name="t_id"]').change(function(){
		// 标记类型时候修改 修改为ok
		var t_id = <?php echo $output['class_array']['type_id'];?>;
		if(t_id != $(this).val()){
			$('#t_sign').val('ok');
		}else{
			$('#t_sign').val('');
		}

		if($(this).val() == '0'){
			$('#t_name').val('');
		}else{
			$('#t_name').val($(this).next('span').html());
		}
	});

	$('#goods_class_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            gc_name : {
                required : true,
                remote   : {
                url :'index.php?act=goods_class&op=ajax&branch=check_class_name',
                type:'get',
                data:{
                    gc_name : function(){
                        return $('#gc_name').val();
                    },
                    gc_parent_id : function() {
                        return $('#gc_parent_id').val();
                    },
                    gc_id : '<?php echo $output['class_array']['gc_id'];?>'
                  }
                }
            },
            vehicle_model:{
                required : true,
            },
            gc_sort : {
                number   : true
            }
        },
        messages : {
             gc_name : {
                required : '<?php echo $lang['goods_class_add_name_null'];?>',
                remote   : '<?php echo $lang['goods_class_add_name_exists'];?>'
            },
            vehicle_model  : {
                required : '<?php echo $lang['vehicle_model_add_name_null'];?>'
            },
            gc_sort  : {
                number   : '<?php echo $lang['goods_class_add_sort_int'];?>'
            }
        }
    });

    // 类型搜索
    $("#gcategory > select").live('change',function(){
    	type_scroll($(this));
    });
});
var typeScroll = 0;
function type_scroll(o){
	var id = o.val();
	if(!$('#type_dt_'+id).is('dt')){
		return false;
	}
	$('#type_div').scrollTop(-typeScroll);
	var sp_top = $('#type_dt_'+id).offset().top;
	var div_top = $('#type_div').offset().top;
	$('#type_div').scrollTop(sp_top-div_top);
	typeScroll = sp_top-div_top;
}
gcategoryInit('gcategory');


  $('.add_color').click(function(){
    var name = $(this).attr('data-name');
    var html = '<p>&nbsp;&nbsp;<input name="'+name+'[]" value="" type="text" class="txt w80 hg-validate" />&nbsp;&nbsp;<a href="javascript:void(0);" class="hgdel">删除</a></div>';
    $(this).parent().before(html);
  });

  $('.hgdel').live('click',function(){
    $(this).parent().remove();
  });

  $('#butie').click(function(){
    var f = $(this).attr('checked');
    if(f=='checked') {
      $('#hg-butie').show();
    } else {
      $('#hg-butie').hide();
    }
  });

  // function hg_validate() {
  //   $('.hg-validate').each(function(){
  //     if($(this).val()=='') {
  //       alert('请全部填写!');
  //       return false;
  //     }
  //   });
  //   if($('#butie').attr('checked')=='checked') {
  //     var p = Number($('#butie_price').val());
  //     if(isNaN(p) || p==0) {
  //       return false;
  //     }
  //   }
  // }

</script>
  <script>
    //添加列表 鼠标悬浮显示小红叉
    $(".tool-add-wrapper")
    .delegate('.list-item','mouseover', function(event) {
      //判断是否是编辑状态
      if ($(this).attr("readonly"))
        $(this).next().css({display:'block'})
    })
    //添加列表 鼠标离开隐藏小红叉
    .delegate('.list-item','mouseout', function(event) {
      //判断是否是编辑状态
      /*var _this = $(this)
      setTimeout(function(){},1000)
        if (_this.attr("readonly"))
          _this.next().hide()*/

    })
    .delegate('i','mouseout', function(event) {
      $(this).hide()
    })
    //添加列表 文本框双击进去编辑状态同时隐藏小红叉
    .delegate('.list-item','dblclick', function(event) {
      $(this).removeAttr('readonly').focus().next().hide()
    })
    //添加列表 文本框获取焦点事件
    .delegate('.list-item','focus', function(event) {
      $(this).attr('data-remark',$(this).val())
    })
    //添加列表 文本框失去焦点事件 解除可编辑状态
    .delegate('.list-item','blur', function(event) {
      //判断内容有没有改变
      var _this = $(this)
      if ($(this).val() != $(this).attr("data-remark")) {
        $.ajax({
          url: 'index.php?act=goods_class&op=updateVehicle',
          type: 'post',
          dataType: 'json',
          data: {
            id: _this.attr("data-id"),
            brand_id: $("input[name='gc_id']").val(),
            title: _this.val(),
          },
        })
        .done(function(data) {
          if (data.error_code == 3) {
            alert('修改失败,非法操作');
          }
        })
      }else
      $(this).attr('readonly',"readonly")
    })
    //添加列表 小红叉点击删除事件
    .delegate('i','click', function(event) {
      var _this   = $(this)
       var type = $(this).parents('.tool-add-wrapper').attr("data-type")
       var _parent = _this.parent()
      var id = _this.prev().attr('data-id')
      $.ajax({
        url: 'index.php?act=goods_class&op=deleteVehicle',
        type: 'post',
        dataType: 'json',
        data: {
          type: type,
          id: id
        },
      })
      .done(function(data) {
        if (data.error_code == 2) {
          _parent.slideUp(300,function(){
          if (_parent.next()[0])
            _parent.next().find("img").show()
          else
            _parent.prev().find("img").removeAttr('style')
          _parent.remove()

        })
        } else {
          alert('删除失败,超时!')
        }
      })

    })
    .delegate('img','click', function(event) {
      //判断是否是空列表新增
      if ($(this).parent().prev()[0].tagName == "I") {
        $(this).css("visibility","hidden").parents(".tool-add-item").next().after($("#add-new").html())
      }

    })
    .delegate('.add-item','blur', function(event) {

        if ($.trim($(this).val()) == "") return
        var _this = $(this)
      var type =  $(this).parents('.tool-add-wrapper').attr("data-type")
      $.ajax({
        url: 'index.php?act=goods_class&op=addVehicle',
        type: 'post',
        dataType: 'json',
        data: {
          type: type,
          title: _this.val(),
          brand_id: $("input[name='gc_id']").val()
        },
      })
      .done(function(data) {
        if (data.error_code == 2) {
          var _html = `
                <p class="add-wrapper list-wrapper">
                  <input data-id="{1}" type="text" value="{0}" readonly="" class="list-item">
                  <i>x</i>
                  <a href="javascript:;"><img src="/templates/default/images/add.png" /></a>
                </p>
        `
        var _parent = _this.parent()
        //console.log(_parent)
        _this.parents('.tool-add-wrapper').find(".tool-add-item").append(_html.replace("{0}",_this.val()).replace("{1}",data.id))
        //console.log(_this.parents('.tool-add-wrapper').find(".tool-add-item")[0])
        _parent.remove()
       }else if(data.error_code == 1) {
          alert('重复添加!');
       }else{
         alert('网络超时,稍后再试');
       }
      })

    })

  </script>