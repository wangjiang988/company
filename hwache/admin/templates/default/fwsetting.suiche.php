<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>随车工具设置</h3>
      <ul class="tab-base">
      	<li><a class="current"><span>随车工具列表</span></a></li>
      	<li><a href="index.php?act=fwsetting&op=suicheedit&id=0"><span>添加</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  	<form action="" method="get" name="suiche-form">
  	<input type="hidden" name="act" value="fwsetting" />
    <input type="hidden" name="op" value="suiche" />
  	<table class="table" style="width:880px">
  	<tr>
  		<td>名称:<input type="text"  name='title' value="<?php echo $_GET['title'];?>" style="width:220px;"></td>
  		<td>
  			<input type="submit" name="search" value="搜索" style="width:80px">
  		</td>
  	</tr>
  	</table>
  	</form>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>名称</th>
<!--          <th class="w270">车型</th>-->
<!--          <th>随车类型</th>-->
<!--					<!--<th>是否通用</th>-->
<!--          <th>数量</th>-->
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td><?php echo $v['title']?></td>
<!--          <td>--><?php //echo $v['car_name']; ?><!--</td>-->
<!--          <td>--><?php //echo $v['type'];?><!--</td>-->
          <!--<td><?php //echo $v['public']==1?"通用":"不通用";?></td>-->
<!--          <td>--><?php //echo $v['num']?><!--</td>-->
          <td class="align-center">
          <a href="index.php?act=fwsetting&op=suicheedit&id=<?php echo $v['id']?>">编辑</a>
            
        <!--  <a href="index.php?act=fwsetting&op=suichedel&id=<?php /*echo $v['id']*/?>">删除</a>-->
          <a href="javascript:confirm_del(<?=$v['id']?>);">删除</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="5"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <tr class="tfoot">
          <td colspan="20"><div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  <div class="clear"></div>
</div>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script>
$(function(){
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

	})



});


function confirm_del(id)
{
    parent.layer.confirm('确定删除么？？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        location.href='index.php?act=fwsetting&op=suichedel&id='+id;
        closeLayer();
    }, function(){
        closeLayer();
    });
}
</script>