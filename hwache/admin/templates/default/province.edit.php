<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
 .comment {
   margin-left: 40px;
 }
 .comment p{
   margin-top:20px;
 }
 .comment p b {
  padding-right:10px;
 }
 .align-center{
  height: 30px;
 }
 select{
  width: 150px;
 }
 .footer{
  margin-top: 60px;
  margin-left: 260px;
  display: none;
 }
 .footer label{
    margin-right: 30px;
 }
 .footer button{
    padding: 3px 25px;
    border-radius: 3px;
    border:1px solid gray;
    cursor: pointer;
 }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['provinces_index'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="comment">
  <form action="index.php?act=provinces&op=save" method="post" id="formSearch">
   <input type="hidden" name="act" value="provinces">
   <input type="hidden" name="op" value="edit">
    <p><b>省份:</b><span><?php echo $output['area'][0]['area_name'];?></span></p>
    <p><b>*周边省份:</b><a href="javascript:void(0);" class="controlModelAdd">添加</a></p>
    <table border="1" style="width:420px;margin-top:20px;" class="content">
      <tr class="align-center">
        <td>编号</td>
        <td>周边省份名称</td>
        <td width="20%">操作</td>
      </tr>
      <?php foreach($output['area_other'] as $key=>$area_other) {?>
      <tr class="align-center">
        <td><?php echo $key+1;?></td>
        <td><?php echo $area_other['area_name'];?></td>
        <td><a href="javascript:void(0)" class="delarea" data-id="<?php echo $area_other['area_id'];?>">删除</a></td>
      </tr>
      <?php }?>
    </table>
  </div>
  <input type="hidden" name="area_id" value="<?php echo $output['area'][0]['area_id'];?>">
  </form>
  <div class="footer">
  <p style="color:red;margin-bottom:20px;font-size:15px" class="error"></p>
    <label><button onclick="checkUser()">提交</button></label>
    <label><button onclick="javascript:history.go(-1)">返回</a></button></label>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.form.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(".controlModelAdd").click(function(){
  var num = $(".content tr").length;
  var _html = '';
  var result = '';
   $.ajax({
    url: 'index.php?act=provinces&op=ajaxarea',
    type: 'POST',
    dataType: 'json',
    data: {  area_id : $('input[name=area_id]').val() }
  })
  .done(function(data) {
     $.each(data,function(index, el) {
         result += '<option value='+el.area_id+'>'+el.area_name+'</option>';
     });
      var _html = '<tr class="align-center"><td>'+num+'</td><td><select name="areas_id[]"><option value="">请选择</option>'+result+'</select></td><td class="tests"></td></tr>'
     $('.content').append(_html);
  });
  $('.footer').css('display', 'block');
  })

$(document).delegate('select', 'change', function(event) { 
 var _html = '<a href="JavaScript:void(0)" class="cancel">取消</a>'
  $(this).parents('tr').find('.tests').text('');
  $(this).parents('tr').find('.tests').append(_html);
  $('.error').text('');
});

$(document).delegate('.cancel', 'click', function(event) { 
     $(this).parents('tr').css('display', 'none');
     $(this).parents('tr').html('');
    if ($(".content td").length == 3 ) {
    $('.footer').css('display', 'none');
   }
})


function isRepeat(arr){
     var hash = {};
     for(var i in arr) {
         if(hash[arr[i]])
              return true;
         hash[arr[i]] = true;
     }
     return false;
}


function checkUser(){
  var area_id = $('input[name=area_id]').val();
  var att=[];
  var number = '';
  var data =$('#formSearch').serializeArray();
  $.each(data, function () {
    att.push(this['value'])
  if (this['value'] == '') {
    $('.error').text('').append('请选择省份'); 
    number = 'error';
  }
  })
  if (number == 'error') {
       return false;
  }
  if(isRepeat(att)) {
    $('.error').text('').append('维护的周边省份不能重复,请重新添加');
    return false;
  }
  var options = {
    dataType: 'json',
    success:function(data) {
       if (data.error_code == 1) {
          location.reload();
          // window.location.href="/index.php?act=provinces&op=index"; 
       } else {
             alert('操作失败');
     }
    }
  }
  $("#formSearch").ajaxSubmit(options);
   
}

$(".delarea").click(function(event) {
   _this = $(this);
    $.ajax({
    url: 'index.php?act=provinces&op=delarea',
    type: 'post',
    dataType: 'json',
    data: {area_id: $(this).attr( 'data-id')},
  })
  .done(function(data) {
     if (data.error_code == 1) {
      _this.parents('tr').css('display','none')
     }
  });
});


</script>

