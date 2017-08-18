<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>随车工具管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=fwsetting&op=suiche"><span>随车工具列表</span></a></li>
          <li><a class="current"><span>添加</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post" action="index.php">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="act" value="fwsetting" />
    <input type="hidden" name="op" value="suicheedit" />
    <table class="table tb-type2">
      <tbody>
      	<tr>
      	<!--<td class="w120 tar">通用:</td>
      	<td>
      		<input type="checkbox" value="1" name="public" <?php //if($output['suicheInfo']['public']==1){ echo "checked";}?>>
      		是否是通用的随车工具
      	</td>-->
      	</tr>
      	<?php 
      	if($output['suicheInfo']['public']==1){ 
      		$style = "style='display:none'";
      	}else{
      		$style = '';
      	}
      	?>
        <tr>
          <td class="tar">名称</td>
          <td>
            <input type="text" class="w300" name="title" value="<?php echo $output['suicheInfo']['title'];?>" />
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
    <input type="hidden"  name="car_name" value="" />
    <input type="hidden"  name="id" value="<?php echo $output['suicheInfo']['id'];?>" />
  </form>
</div>
<script>
$(function(){
	// $("input[name=public]").click(function(){
	// 	if($(this).is(':checked')==true){
	// 		$(".unpublic").css('display','none');
	// 	}else{
	// 		$(".unpublic").css('display','');
	// 	}
	// })
	$("select[name='car_brand']").change(function(){
		var _brand = $(this).val();
		if(_brand>0){
			$.getJSON("index.php?act=fwsetting&op=ajax_get&type=car&brand="+_brand, function(data){
				var _str = '<option value="0">请选择车系</option>';
				  $.each(data,function(index,item){
					  _str = _str +"<option value='"+item.gc_id+"'>"+item.gc_name+"</option>";
					  
				  })
				  $("select[name='car_chexi']").html(_str);
				  $("select[name='car_chexing']").html('<option value="0">请选择车型</option>');
			});
		}
	})

	$("select[name='car_chexi']").change(function(){
		var _brand = $(this).val();
		if(_brand>0){
			$.getJSON("index.php?act=fwsetting&op=ajax_get&type=car&brand="+_brand, function(data){
				var _str = '<option value="0">请选择车型</option>';
				  $.each(data,function(index,item){
					  _str = _str +"<option value='"+item.gc_id+"'>"+item.gc_name+"</option>";
					  
				  })
				  $("select[name='car_chexing']").html(_str);
			});
		}
	})

	$("#submitBtn").click(function(){
		var _car_brand_id = $("select[name='car_brand']").val();
		var _car_chexi_id = $("select[name='car_chexi']").val();
		var _car_chexing_id = $("select[name='car_chexing']").val();
		var _car_brand_text = $("select[name='car_brand']").find("option:selected").text();
		var _car_chexi_text = $("select[name='car_chexi']").find("option:selected").text();
		var _car_chexing_text = $("select[name='car_chexing']").find("option:selected").text();
		
		if($("input[name=public]").is(':checked')==false){
			if((_car_brand_id==0 || _car_chexi_id==0 || _car_chexing_id ==0)){
				alert('车品牌、车系、车型必须都要选择');
				return false;
			}
			$("input[name='car_name']").val(_car_brand_text+"/"+_car_chexi_text+"/"+_car_chexing_text);
			
		}
		if($("input[name=title]").val() ==''){
			alert('随车工具名称不能为空');
			return false;
		}
		if($("input[name=num]").val() ==''){
			alert('随车工具数量不能为空');
			return false;
		}
		$("#form").submit();
	})


});
</script>