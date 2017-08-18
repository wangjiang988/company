<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
 table.search {
      margin: 5px 0px;
}
 .search td select{
      margin-left: 10px;
      margin-right: 25px;
      width:200px;
      height: 30px;
 }
 .search td select option {
  height: 30px;
 }
 .search td button{
      padding:3px 20px;
      background-color:rgb(255,137,49);
      border: 1px solid rgb(246,142,61);
      border-radius: 4px;
      color: white;
      height: 30px;
      cursor: pointer;
 }
 .thead th {
  border: solid 1px #DEEFFB;
 }
 .nobdb{
      margin-top: 30px;
 }
 .nobdb td{
  border:1px solid rgb(229,229,229);
 }
 .add_tr{
  display: none;
 }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['centralcity'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="comment">
   <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="centralcity" />
    <input type="hidden" name="op" value="index" />
    <table class="search">
      <tbody>
      <tr>
         <td><label style="font-size:15px">城市:</label></td>
         <td>
           <select name="id" class="searchs">
           <option value="">不限</option>
            <?php foreach($output['citydatas'] as $key=>$citydata){?>
            <?php if($citydata['id'] == $_GET['id']) { ?>
              <option selected="selected" value=<?php echo $citydata['id'];?>><?php echo $citydata['area_name'];}?></option>
            <option value=<?php echo $citydata['id'];?>><?php echo $citydata['area_name'];}?></option>
           </select>
         </td>
          </form>
         <td>
           <label>
             <button type="button" class="add_area">新增</button>
           </label>
         </td>
      </tr>
      </tbody>
    </table>
  <div id="content"> 
    <table class="table tb-type2 nobdb" border="1px">
    <thead>
      <tr class="thead" style="background-color:rgb(197,235,255);height:45px">
        <th class="align-center" width="20%"><?php echo $lang['centralcity_number'];?></th>
        <th class="align-center"><?php echo $lang['centralcity_name'];?></th>
        <th class="align-center" width="30%"><?php echo $lang['common_operate']; ?></th>
      </tr>
    </thead>
    <tbody class="comments">
    <tr style="display:none;"></tr>
    <?php foreach($output['citydata'] as $key=>$citydata) { ?>
     <tr>
       <td class="align-center"><?php echo $key+1;?></td>
       <td class="align-center"><?php echo $citydata['area_name'];?></td>
       <td class="align-center"><a href="javascript:void(0)" class="delcity" data-id="<?php echo $citydata['id'];?>">删除</a></td>
     </tr>
    <?php } ?>
    </tbody>
    </table>
    <div class="pagination"><?php echo $output['page'];?> </div>
    </div>


<div class="add_tr">
  <select id="province" name="area_id"> 
      <option value="">----请选择省份----</option>
      <?php foreach($output['province_lists'] as $lists) {?>
      <option value="<?php echo $lists['area_id'];?>"><?php echo $lists['area_name'];?></option>
      <?php }?> 
  </select> 
   <select class="city" style="margin-left:20px"> 
   <option value="">----请选择城市----</option> 
   </select> 
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.form.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(document).ready(function () { 
  $(document).delegate('#province', 'change', function(event) {
    var _html = ''
    $('.error').html('');
    var area_id = $(this).find("option:checked").val();
    if (area_id == '') {
      $('.city').find('option').slice(1).remove();
      var temp = "<option value=''>--请选择城市--</option>"
      $('.city').html(temp)
      return false;
    }
    $.ajax({
      url: 'index.php?act=centralcity&op=citylist',
      type: 'get',
      dataType: 'json',
      data:{
        area_id: $(this).find("option:checked").val()
      }
    })
    .done(function(data) {
      $.each(data,function(index, value) {
        _html += '<option value='+value.area_id+'>'+value.area_name+'</option>'
      });
      $('.city').html('').append(_html);
    });  
   });

  $(document).delegate('.city', 'change', function(event) {
        $('.error').html('');
    });
 });

$('.add_area').click(function(event) {
 var judge = $(".comments").find("option:selected").text()
 if (judge) {
   return false;
 }
  var num = $(".comments tr").length;
  var temp = $('.add_tr').html()
  var _html = ''
  _html = '<tr style="height:50px;"><td class="align-center">'+num+'</td><td class="align-center">'+temp+'<p class="error"></p></td><td class="align-center"><a href="javascript:void(0)" onclick="save(this)">保存</a>&nbsp&nbsp&nbsp&nbsp&nbsp<a href="javascript:void(0)" onclick="cancel(this)">取消</a></td></tr>'
   $('.comments').append(_html);
});

function cancel($this){
  $($this).parents('tr').remove();
  $('.city').find('option').slice(1).remove();
  var temp = "<option value=''>--请选择城市--</option>"
  $('.city').html(temp)
}

function save($this){
  var area_id = $($this).parents('tr').find(".city option:checked").val()
  if (area_id == '') {
   // alert('请选择城市');
    $('.error').html('').append('请选择城市');
    return false;
  }

  $.ajax({
    url: 'index.php?act=centralcity&op=savacity',
    type: 'POST',
    dataType: 'json',
    data: {area_id: area_id},
  })
  .done(function(data) {
    if (data.error_code == 1) {
        location.reload();
    } 
    if (data.error_code == 0) {
      //alert('重复添加');
      $('.error').html('').append('选择的城市已存在,请重新选择');
    }
  })
  
}

$(".delcity").click(function(event) {
   _this = $(this);
    $.ajax({
    url: 'index.php?act=centralcity&op=delarea',
    type: 'post',
    dataType: 'json',
    data: {id: $(this).attr( 'data-id')},
  })
  .done(function(data) {
     if (data.error_code == 1) {
      _this.parents('tr').css('display','none')
     }
  });
});


$('.searchs').change(function(event) {
    $('#formSearch').submit();
});

</script>

