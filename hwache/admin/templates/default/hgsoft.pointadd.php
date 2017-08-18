<?php defined('InHG') or exit('Access Invalid!');?>
<style>
#region select{width:auto;}
#user_form .table .hide{display:none;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>城市参考点管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=hgsoft&op=point" ><span>管理</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation">城市地区:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop"><span id="region">
          <input type="hidden" value="" name="province_id" id="province_id">
          <input type="hidden" value="" name="city_id" id="city_id">
          <input type="hidden" value="" name="area_id" id="area_id" class="area_ids" />
          <input type="hidden" value="" name="area_info" id="area_info" class="area_names" /><select></select></span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">参考点:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><input type="text" value="" id="point" name="point" class="txt"></td>
        </tr>
        <tr>
          <td colspan="2">
            <div>确认位置后，直接在地图上点击获取位置的详细信息！</div>
            <div id="mapDiv" style="width:500px;height:300px;"></div>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/city_select/common_select.js" charset="utf-8"></script>
<script type="text/javascript">
var mapObj;
var lnglatXY;
var mapid='mapDiv';
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
//鼠标点击，获取经纬度坐标
function getLnglat(e){
  mapObj.clearMap();
  var x = e.lnglat.getLng();
  var y = e.lnglat.getLat();
  // alert(x + "," + y);
  $('#point').val(x+','+y);
  lnglatXY = new AMap.LngLat(x,y);
  geocoder();
}

loadScript();

$(function(){

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
