<?php defined('InHG') or exit('Access Invalid!');?>
<style>
#region select{width:auto;}
#user_form .table .hide{display:none;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>经销商管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=dealer&op=index" ><span>管理</span></a></li>
		<li><a href="index.php?act=dealer&op=dealer_add" ><span>增加</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>编辑</span></a></li>
		
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
	<input type="hidden" name="d_id" value="<?php echo $output['dealer_array']['d_id'];?>" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">经销商名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['dealer_array']['d_name'];?>" name="name" id="name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">归属地:</label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop"><span id="region">
          <input type="hidden" value="<?php echo $output['dealer_array']['d_sheng'];?>" name="province_id" id="province_id">
          <input type="hidden" value="<?php echo $output['dealer_array']['d_shi'];?>" name="city_id" id="city_id">
          <input type="hidden" value="<?php echo $output['dealer_array']['d_xian'];?>" name="area_id" id="area_id" class="area_ids" />
          <input type="hidden" value="<?php echo $output['dealer_array']['d_areainfo'];?>" name="area_info" id="area_info" class="area_names" /><select></select></span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="email">电子邮件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['dealer_array']['d_email'];?>" id="email" name="email" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="tel">联系电话:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['dealer_array']['d_tel'];?>" id="tel" name="tel" class="txt"></td>
          <td class="vatop tips">固定电话或者手机</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">营业地点:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['dealer_array']['d_yy_place'];?>" id="yy_place" name="yy_place" class="txt"></td>
          <td class="vatop tips"><input type="button" id="hg01" value="点击加载地图"><input type="button" id="hg02" class="hide" value="关闭地图"> X坐标：<input type="text" value="<?php echo $output['dealer_array']['d_lngx'];?>" id="lngx" name="lngx" readonly class="txt"> Y坐标：<input type="text" value="<?php echo $output['dealer_array']['d_lngy'];?>" id="lngy" name="lngy" readonly class="txt"></td>
        </tr>
        <tr id="hg01map" class="hide">
          <td colspan="2">
            <div>确认位置后，直接在地图上点击获取位置的详细信息，营业地点可以手动修改！</div>
            <div id="mapDiv" style="width:500px;height:300px;"></div>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>交车地点:</label> <label><input type="checkbox" name="jc_yy_same" checked="checked" id="jc_yy_same">与营业地点相同</label></td>
        </tr>
        <tr class="noborder hide">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['dealer_array']['d_jc_place'];?>" id="jc_place" name="jc_place" class="txt"></td>
          <td class="vatop tips"><input type="button" id="hg03" value="点击加载地图"><input type="button" id="hg04" class="hide" value="关闭地图"> X坐标：<input type="text" id="jc_lngx" name="jc_lngx" value="<?php echo $output['dealer_array']['d_jc_lngx'];?>" class="txt"> Y坐标：<input type="text" id="jc_lngy" name="jc_lngy" value="<?php echo $output['dealer_array']['d_jc_lngy'];?>" class="txt"></td>
        </tr>
        <tr id="hg02map" class="hide">
          <td colspan="2">
            <div>确认位置后，直接在地图上点击获取位置的详细信息，营业地点可以手动修改！</div>
            <div id="mapDiv2" style="width:500px;height:300px;"></div>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>商业保险是否必须在销售商处购买:<input type="checkbox" name="baoxian" id="baoxian" <?php if($output['dealer_array']['d_baoxian']){?>checked="checked"<?php }?>></label></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label>本地客户是否必须由销售商提供上牌服务:<input type="checkbox" name="shangpai" id="shangpai" <?php if($output['dealer_array']['d_shangpai']){?>checked="checked"<?php }?>></label></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label>临时车辆移动牌照是否必须由销售商提供办理服务:<input type="checkbox" name="linpai" id="linpai" <?php if($output['dealer_array']['d_linpai']){?>checked="checked"<?php }?>></label></td>
        </tr>
        <tr class="noborder hide" id="linpaimore">
          <td class="vatop rowform"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>是否交车时有其他收费项目:<input type="checkbox" name="fee" id="fee" <?php if($output['dealer_array']['d_jc_fee']){?>checked="checked"<?php }?>></label></td>
        </tr>
        <tr class="noborder hide" id="feemore">
          <td class="vatop rowform"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>是否提供赠品或免费附加服务:<input type="checkbox" name="free" id="free" <?php if($output['dealer_array']['d_jc_free']){?>checked="checked"<?php }?>></label></td>
        </tr>
        <tr class="noborder hide" id="freemore">
          <td class="vatop rowform"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>限购城市是否可办理上牌指标:<input type="checkbox" name="xg_shangpai" id="xg_shangpai" <?php if($output['dealer_array']['d_xg_shangpai']){?>checked="checked"<?php }?>></label></td>
        </tr>
        <tr class="noborder hide" id="xg_shangpaimore">
          <td class="vatop rowform"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>国家补贴车型销售商是否可办国家节能补贴:<input type="checkbox" name="butie" id="butie" <?php if($output['dealer_array']['d_butie']){?>checked="checked"<?php }?>></label></td>
        </tr>
        <tr class="noborder hide" id="butiemore">
          <td class="vatop rowform"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>所属地区同类竞争性销售商（不同实际控制人）数量:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="<?php echo $output['dealer_array']['d_dealer_num'];?>" id="dealer_num" name="dealer_num" class="txt" ></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>竞争性经销商备注:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2"><textarea name="dealer_content" id="dealer_content" cols="30" rows="10" style="width:500px;height:100px;"><?php echo $output['dealer_array']['d_dealer_content'];?></textarea></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/city_select/common_select.js" charset="utf-8"></script>
<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
  $('#member_avatar').val(picname);
  $('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname);
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
