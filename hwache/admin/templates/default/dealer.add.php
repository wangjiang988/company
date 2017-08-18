<?php defined('InHG') or exit('Access Invalid!');?>
<style>
#region select{width:auto;}
#user_form .table .hide{display:none;}
table tr td label b{color:red;margin-left: 50px;display: none}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>经销商管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=dealer&op=index" ><span>管理</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" enctype="multipart/form-data" method="post" onsubmit="return checkForm()">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr class="noborder">
        <td><label class="validation">显示状态:</label>显示</td>
      </tr>
      <tr class="noborder">
        <td><label class="required">类别:</label>授权4s</td>
      </tr>
      <tr class="noborder">
        <td><label class="validation">销售品牌:</label>
          <select class="form-control" name="brand_id" id="brand_id">
            <option value="">请选择</option>
            <?php foreach($output['brand_list'] as $brand):?>
              <option value="<?=$brand['gc_id'];?>"><?=$brand['gc_name'];?></option>
            <?php endforeach;?>
          </select>
          <label><b id="brand_id_error">*请选择品牌</b></label>
        </td>
      </tr>
      <tr>
        <td class="required"><label class="validation">归属地区:</label><span id="region">
          <input type="hidden" value="" name="province_id" id="province_id">
          <input type="hidden" value="" name="city_id" id="city_id">
          <input type="hidden" value="" name="area_id" id="area_id" class="area_ids" />
          <input type="hidden" value="" name="area_info" id="area_info" class="area_names" />
            <select></select></span>
          <label><b id="area_info_error">*请选择归属地区</b></label>
        </td>
      </tr>
        <tr class="noborder">
          <td class="required"><label class="validation" for="name">经销商名称:</label>
          <input type="text" value="" name="name" id="dealer_name" class="txt" style="width:300px">
          <label><b id="dealer_name_error">*请选择经销商名称</b></label>
          </td>
        </tr>
        <tr>
          <td class="required"><label class="validation">营业地点:</label>
            <label><b id="yy_place_error">*请选择经销商名称</b></label>
          </td>
        </tr>
        <tr class="noborder">
          <td class="vatop"><input type="text" value="" id="yy_place" name="yy_place" class="txt" style="width:300px">
          <input type="button" id="hg01" value="点击加载地图"><input type="button" id="hg02" class="hide" value="关闭地图"> X坐标：<input type="text" value="" id="lngx" name="lngx" readonly class="txt"> Y坐标：<input type="text" value="" id="lngy" name="lngy" readonly class="txt"></td>
        </tr>
        <tr id="hg01map" class="hide">
          <td>
            <div>确认位置后，直接在地图上点击获取位置的详细信息，营业地点可以手动修改！</div>
            <div id="mapDiv" style="width:500px;height:300px;"></div>
          </td>
        </tr>
        <tr>
          <td class="required"><label>交车地点:</label> <label><input type="checkbox" name="jc_yy_same" checked="checked" id="jc_yy_same" disabled="disabled">与营业地点相同</label></td>
        </tr>
        <tr class="noborder hide">
          <td class="vatop"><input type="text" value="" id="jc_place" name="jc_place" class="txt"  style="width:300px">
            <input type="button" id="hg03" value="点击加载地图"><input type="button" id="hg04" class="hide" value="关闭地图"> X坐标：<input type="text" id="jc_lngx" name="jc_lngx" readonly class="txt"> Y坐标：<input type="text" id="jc_lngy" name="jc_lngy" readonly class="txt"></td>
        </tr>
        <tr id="hg02map" class="hide">
          <td>
            <div>确认位置后，直接在地图上点击获取位置的详细信息，营业地点可以手动修改！</div>
            <div id="mapDiv2" style="width:500px;height:300px;"></div>
          </td>
        </tr>
      <tr>
        <td class="required"><label>联系电话:</label>
          <input type="number" value="" id="tel" name="tel" class="txt" style="width:300px">
        </td>
      </tr>
        <tr>
          <td colspan="2" class="required"><label>备注:</label><textarea name="dealer_content" id="dealer_content" cols="30" rows="10" style="width:500px;height:100px;margin-left:28px"></textarea></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot" style="text-align: center">
          <td><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a>
          <a href="JavaScript:void(0);" onclick="window.history.go(-1)" class="btn" id="submitBtn"><span>返回</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/city_select/common_select.js" charset="utf-8"></script>
<script type="text/javascript">
  function checkForm()
  {
    $("label b").hide();
    var flag =true;
    var brand_id =$("#brand_id").val();
    if(brand_id == ""){
      flag = false;
      $("#brand_id_error").show();
    }
    var area_info = $("#area_info").val();
    if(area_info == ""){
      flag = false;
      $("#area_info_error").show();
    }
    var dealer_name = $("#dealer_name").val();
    if(dealer_name == ""){
      flag = false;
      $("#dealer_name_error").show();
    }
    var yy_place = $("#yy_place").val();
    if(yy_place == ""){
      flag = false;
      $("#yy_place_error").show();
    }else{
      var lngx = $("#lngx").val();
      var lngy = $("#lngy").val();
      if(lngx == "" || lngy==""){
        flag = false;
        $("#yy_place_error").show();
      }
    }

    return flag;
  }


var mapObj;
var lnglatXY;
var mapid,place,hgx,hgy;
  // 地图异步加载函数
  function init() {
      var opt = {
          resizeEnable: true,
          view:new AMap.View2D({
            zoom:12
          })
      }
      mapObj = new AMap.Map(mapid, opt);
      AMap.event.addListener(mapObj,'click',getLnglat); //点击事件
      //地图中添加地图操作ToolBar插件
      mapObj.plugin(['AMap.ToolBar'],function(){
        //设置地位标记为自定义标记
        var toolBar = new AMap.ToolBar();
        mapObj.addControl(toolBar);
      });

  }

  function loadScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://webapi.amap.com/maps?v=1.3&key=cbd5503fcd99dc0ae25f68f0f1a00cfa&callback=init";
    document.body.appendChild(script);
  }

  function geocoder() {
      var MGeocoder;
      //加载地理编码插件
      mapObj.plugin(["AMap.Geocoder"], function() {
          MGeocoder = new AMap.Geocoder({
              radius: 1000,
              extensions: "all"
          });
          //返回地理编码结果
          AMap.event.addListener(MGeocoder, "complete", geocoder_CallBack);
          //逆地理编码
          MGeocoder.getAddress(lnglatXY);
      });
      //加点
      var marker = new AMap.Marker({
          map:mapObj,
          icon: "http://webapi.amap.com/images/0.png",
          position: lnglatXY,
          offset: new AMap.Pixel(-5,-30)
      });
     // mapObj.setFitView();
  }
  //回调函数
  function geocoder_CallBack(data) {
      var address;
      //返回地址描述
      address = data.regeocode.formattedAddress;
      //返回结果拼接输出
      // alert(address);
      $('#'+place).val(address);
      // var yy_place = $('#yy_place').val();
      // if(yy_place == address || yy_place == '') {
      //   $('#yy_place').val(address);
      // }
  }
  //鼠标点击，获取经纬度坐标
  function getLnglat(e){
    mapObj.clearMap();
    var x = e.lnglat.getLng();
    var y = e.lnglat.getLat();
    // alert(x + "," + y);
    $('#'+hgx).val(x);
    $('#'+hgy).val(y);

    lnglatXY = new AMap.LngLat(x,y);
    geocoder();
  }


$(function(){

  // 地图异步加载
  $('#hg01').click(function(){
    mapid = 'mapDiv';
    place = 'yy_place';
    hgx   = 'lngx';
    hgy   = 'lngy';
    $(this).hide();
    $('#hg02').show();
    $('#hg01map').show();
    loadScript();
  });
  // 添加标记
  $('#hg02').click(function(){
    $(this).hide();
    $('#hg01map').hide();
    $('#hg01').show();
  });

  // 地图异步加载
  $('#hg03').click(function(){
    mapid = 'mapDiv2';
    place = 'jc_place';
    hgx   = 'jc_lngx';
    hgy   = 'jc_lngy';
    $(this).hide();
    $('#hg04').show();
    $('#hg02map').show();
    loadScript();
  });
  // 添加标记
  $('#hg04').click(function(){
    $(this).hide();
    $('#hg02map').hide();
    $('#hg03').show();
  });

  // 交车地点和营业地点相同与否的判断
  $('#jc_yy_same').click(function(){
    var checked = $(this).attr('checked');
    if(checked == 'checked') {
      $(this).parent().parent().parent().next().hide();
    } else {
      $(this).parent().parent().parent().next().show();
    }
  });

  // 城市联动加载
  regionInit("region");

  //按钮先执行验证再提交表单
  $("#submitBtn").click(function(){
    var s1 = $('#region>select').eq(0).val();
    var s2 = $('#region>select').eq(1).val();
    if(s1>0) $('#province_id').val(s1);
    if(s2>0) $('#city_id').val(s2);
    $("#user_form").submit();
  });

});
</script>
