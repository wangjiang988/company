<?php defined('InHG') or exit('Access Invalid!');?>
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<!--<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
<style>
  .table-center{
    vertical-align:middle;
    text-align: center
  }
</style>

<div style="margin:20px" id="query_form">
  <form class="form-inline" role="form" method="post" name="formSearch" id="formSearch" action="">
    <input type="hidden" value="order" name="act">
    <input type="hidden" value="index" name="op">
    <input type="hidden" value="1" name="query">
    <div class="form-group">
      <label class="control-label">订单号</label>
      <input type="search" class="form-control" name="order_id" style="width:100px"  value="<?=isset($_GET['order_id'])?$_GET['order_id']:'';?>">
    </div>
    <div class="form-group">
      <label>客户手机</label>
      <input type="search" class="form-control" name="user_phone" value="<?=isset($_GET['user_phone'])?$_GET['user_phone']:'';?>" style="width:100px">
    </div>
    <div class="form-group">
      <label>售方用户名</label>
      <input type="search" class="form-control" name="seller_name" value="<?=isset($_GET['seller_name'])?$_GET['seller_name']:'';?>" style="width:100px">
    </div>
    <div class="form-group">
      <label>经销商</label>
      <input type="search" class="form-control" name="dealer_name" style="width:146px" value="<?=isset($_GET['dealer_name'])?$_GET['dealer_name']:'';?>">
    </div>
    <div class="form-group" style="width:38%">
      <label class="control-label">订单时间</label>
      <div class="input-group date form_date_start" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="order_create_starttime" value="<?=isset($_GET['order_create_starttime'])?$_GET['order_create_starttime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>~
      <div class="input-group date form_date_start" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="order_create_endtime" value="<?=isset($_GET['order_create_endtime'])?$_GET['order_create_endtime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>
    </div>
    <div class="form-group" id="car_brand">
      <label class="control-label">品牌</label>
      <select class="form-control" name="brand_id" style="width:150px" >
        <option value="">不限</option>
        <?php foreach($output['brand_list'] as $brand):?>
          <option value="<?=$brand['gc_id'];?>" <?=(isset($_GET['brand_id'])&&$_GET['brand_id']==$brand['gc_id'])?'selected':'';?>><?=$brand['gc_name'];?></option>
        <?php endforeach;?>
      </select>
    </div>
    <div class="form-group" id="car_series">
      <label class="control-label">车系</label>
      <select class="form-control" name="gc_series" style="width:150px" >
        <option value="">不限</option>
        <?php if($output['gc_series']):?>
          <?php foreach($output['gc_series'] as $gs):?>
            <option value='<?=$gs['gc_name'];?>' <?=(isset($_GET['gc_series'])&&$_GET['gc_series']==$gs['gc_name'])?'selected':'';?>><a><span><?=$gs['gc_name'];?></span></a></option>
          <?php endforeach;?>
        <?php endif;?>
      </select>
    </div>
    <div class="form-group" id="car_model">
      <label class="control-label">车型</label>
      <select class="form-control" name="gc_name" style="width:280px" >
        <option value="">不限</option>
        <?php if($output['gc_name']):?>
          <?php foreach($output['gc_name'] as $gn):?>
            <option value='<?=$gn['gc_id'];?>' <?=(isset($_GET['gc_name'])&&$_GET['gc_name']==$gn['gc_id'])?'selected':'';?>><a><span><?=$gn['gc_name'];?></span></a></option>
          <?php endforeach;?>
        <?php endif;?>
      </select>
    </div>
    <div class="form-group" style="width:38%">
      <label class="control-label">结束时间</label>
      <div class="input-group date form_date_start" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="order_finished_starttime" value="<?=isset($_GET['order_finished_starttime'])?$_GET['order_finished_starttime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>~
      <div class="input-group date form_date_start" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="order_finished_endtime" value="<?=isset($_GET['order_finished_endtime'])?$_GET['order_finished_endtime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>
    </div>
    <div class="form-group">
      <label class="control-label">客户订单状态</label>
      <select class="form-control" name="user_order_status" id="user_order_status_select" style="width:200px">
        <option value=""  <?=(isset($_GET['bj_is_public'])&&$_GET['bj_is_public']=="")?'selected':'';?>>不限</option>
          <?php foreach($output['user_order_pri_status_list'] as $user_pri_status):?>
              <option value="<?=$user_pri_status['pri_status'];?>" <?=(isset($_GET['user_order_status'])&&$_GET['$user_pri_status']==$user_pri_status['pri_status'])?'selected':'';?>><?=$user_pri_status['user_progress'];?></option>
          <?php endforeach;?>
      </select>
    </div>
    <div class="form-group">
      <select class="form-control" name="user_order_state" id="user_order_state" style="width:316px">
        <option value=""  <?=(isset($_GET['user_order_state'])&&$_GET['user_order_state']=="")?'selected':'';?>>不限</option>
          <?php if($output['user_order_sub_status_list']):?>
              <?php foreach($output['user_order_sub_status_list'] as $user_sub_status):?>
                  <option value="<?=$user_sub_status['sub_status'];?>" <?=(isset($_GET['user_order_state'])&&$_GET['user_order_state']==$user_sub_status['sub_status'])?'selected':'';?>><?=$user_sub_status['remark'];?></option>
              <?php endforeach; ?>
          <?php endif;?>
      </select>
    </div>
    <div class="form-group" style="width:45%">
      <label class="control-label">约定交车时间&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <div class="input-group date form_date_end" style="width:37%"><input type="datetime" class="form-control form_datetime" readonly name="appoint_car_starttime" value="<?=isset($_GET['appoint_car_starttime'])?$_GET['appoint_car_starttime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>~
      <div class="input-group date form_date_end" style="width:37%"><input type="datetime" class="form-control form_datetime" readonly name="appoint_car_endtime" value="<?=isset($_GET['appoint_car_endtime'])?$_GET['appoint_car_endtime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>
    </div>
    <div class="form-group">
      <label class="control-label">售方订单状态</label>
      <select class="form-control" name="seller_order_status" id="seller_order_status_select" style="width:200px">
        <option value=""  <?=(isset($_GET['seller_order_status'])&&$_GET['seller_order_status']=="")?'selected':'';?>>不限</option>
          <?php foreach($output['seller_order_pri_status_list'] as $seller_pri_status):?>
           <option value="<?=$seller_pri_status['pri_status'];?>" <?=(isset($_GET['seller_order_status'])&&$_GET['seller_order_status']==$seller_pri_status['pri_status'])?'selected':'';?>><?=$seller_pri_status['seller_progress'];?></option>
          <?php endforeach;?>
      </select>
    </div>
    <div class="form-group" >
      <select class="form-control" name="seller_order_state" id="seller_order_state" style="width:316px">
        <option value=""  <?=(isset($_GET['seller_order_state'])&&$_GET['seller_order_state']=="")?'selected':'';?>>不限</option>
          <?php if($output['seller_order_sub_status_list']):?>
          <?php foreach($output['seller_order_sub_status_list'] as $seller_sub_status):?>
            <option value="<?=$seller_pri_status['sub_status'];?>" <?=(isset($_GET['seller_order_state'])&&$_GET['seller_order_state']==$seller_pri_status['sub_status'])?'selected':'';?>><?=$seller_pri_status['remark'];?></option>
          <?php endforeach; ?>
          <?php endif;?>
        <option value="2" <?=(isset($_GET['bj_is_public'])&&$_GET['bj_is_public']==2)?'selected':'';?>>异常</option>
        <option value="0" <?=(isset($_GET['bj_is_public'])&&is_numeric($_GET['bj_is_public'])&&$_GET['bj_is_public']==0)?'selected':'';?>>无</option>
      </select>
    </div>
    <div class="form-group">
      <label class="control-label">选装修改协商中</label>
      <select class="form-control" name="xzjp_is_install">
        <option value=""  <?=(isset($_GET['xzjp_is_install'])&&$_GET['xzjp_is_install']=="")?'selected':'';?>>不限</option>
        <option value="1" <?=(isset($_GET['xzjp_is_install'])&&$_GET['xzjp_is_install']==1)?'selected':'';?>>是</option>
        <option value="0" <?=(isset($_GET['xzjp_is_install'])&&is_numeric($_GET['xzjp_is_install'])&&$_GET['xzjp_is_install']==0)?'selected':'';?>>否</option>
      </select>
    </div>
    <div class="form-group">
      <label class="control-label">当前客户超时</label>
      <select class="form-control" name="user_pay_timeout">
        <option value=""  <?=(isset($_GET['user_pay_timeout'])&&$_GET['user_pay_timeout']=="")?'selected':'';?>>不限</option>
        <option value="1" <?=(isset($_GET['user_pay_timeout'])&&$_GET['user_pay_timeout']==1)?'selected':'';?>>是</option>
        <option value="0" <?=(isset($_GET['user_pay_timeout'])&&is_numeric($_GET['user_pay_timeout'])&&$_GET['user_pay_timeout']==0)?'selected':'';?>>否</option>
      </select>
    </div><br/>
    <div class="form-group">
      <label class="control-label">未了结工单</label>
      <select class="form-control" name="order_in_negotiation">
        <option value="" <?=(isset($_GET['order_in_negotiation'])&&$_GET['order_in_negotiation']=="")?'selected':'';?>>不限</option>
        <option value="1" <?=(isset($_GET['order_in_negotiation'])&&$_GET['order_in_negotiation']==1)?'selected':'';?>>客户</option>
        <option value="2" <?=(isset($_GET['order_in_negotiation'])&&$_GET['order_in_negotiation']==2)?'selected':'';?>>售方</option>
        <option value="3" <?=(isset($_GET['order_in_negotiation'])&&$_GET['order_in_negotiation']==3)?'selected':'';?>>平台</option>
      </select>
    </div>
    <div class="form-group">
      <label class="control-label">当前处理部门</label>
      <select class="form-control" name="conciliate_department">
        <option value=""  <?=(isset($_GET['conciliate_department'])&&$_GET['conciliate_department']=='')?'selected':'';?>>全部</option>
        <option value="1"  <?=(isset($_GET['conciliate_department'])&&$_GET['conciliate_department']==1)?'selected':'';?>>财务部</option>
        <option value="2" <?=(isset($_GET['conciliate_department'])&&$_GET['conciliate_department']==2)?'selected':'';?>>客服部</option>
        <option value="3" <?=(isset($_GET['conciliate_department'])&&$_GET['conciliate_department']==3)?'selected':'';?>>运营部</option>
        <option value="4" <?=(isset($_GET['conciliate_department'])&&$_GET['conciliate_department']==4)?'selected':'';?>>法务部</option>
      </select>
    </div>
    <div class="form-group" style="margin-left:600px">
      <button type="button" class="btn btn-warning" id="search">查找</button>
      <button type="button" class="btn btn-default" id="reset">重置</button>
      <a href="<?=url('order','exportOrder',$output['uri'])?>"><button class="btn btn-primary form-control">导出</button></a>
</div>
  </form>
  <?php require_once("order.index.table.php"); ?>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script type="text/javascript">
  var SITEURL = "<?php echo SHOP_SITE_URL; ?>";


  $(function(){
    $('.form_date_start').datepicker({
      language: "zh-CN",
      format: 'yyyy-mm-dd',
      weekStart: 1,
      autoclose: 1,
      todayHighlight: true,
      forceParse: 0,
      pickerPosition: "bottom-left",
      clearBtn: 1,
      endDate:'0d'
    });

    $('.form_date_end').datepicker({
      language: "zh-CN",
      format: 'yyyy-mm-dd',
      weekStart: 1,
      autoclose: 1,
      todayHighlight: true,
      forceParse: 0,
      pickerPosition: "bottom-left",
      clearBtn: 1
    });

    //reset表单
    $("#reset").click(function(){
      window.location.href="/index.php?act=order&op=index";
    })

    //查询
    var page=1;
    $("#search").click(function(){
      //校验参数
      var order_create_starttime = $("input[name='order_create_starttime']").val();
      var order_create_endtime = $("input[name='order_create_endtime']").val();
      var order_finished_starttime = $("input[name='order_finished_starttime']").val();
      var order_finished_endtime = $("input[name='order_finished_endtime']").val();
      var appoint_car_starttime = $("input[name='appoint_car_starttime']").val();
      var appoint_car_endtime = $("input[name='appoint_car_endtime']").val();

      if(order_create_starttime&&order_create_endtime&&order_create_starttime>order_create_endtime){
        showDialog('订单时间开始时间应该小于结束时间');
        return;
      }

      if(order_finished_starttime&&order_finished_endtime&&order_finished_starttime>order_finished_endtime){
        showDialog('结束时间开始时间应该小于结束时间');
        return;
      }

      if(appoint_car_starttime&&appoint_car_endtime&&appoint_car_starttime>appoint_car_endtime){
        showDialog('约定交车时间开始时间应该小于结束时间');
        return;
      }

      getOrdersTable(page);
    })

    //分页时传参
    $(document).on('click', '.pagination a', function (e) {
      var page = $(this).attr('href').split('curpage=')[1];
      getBaojiaTable(page);
      e.preventDefault();
    });

    //选择品牌
    $("#car_brand select").change(function() {
      var brand_id = $("#car_brand select").val();
      if(brand_id>0){
        $.getJSON("/index.php?act=goods&op=ajaxcardata&brand_id="+brand_id+"&type=brand", function(data){
          var  str='';
          $.each(data,function(item,index){
            str=str+"<option value='"+index.gc_name+"'><a><span>"+index.gc_name+"</span></a></option>";
          })
          $("#car_series select option:gt(0)").remove();
          $("#car_series select option:first").after(str);
          if(str==''){
            alert('没有对应的车系');
          }
        });
      }else{
        $("#car_series select option:gt(0)").remove();
        $("#car_model select option:gt(0)").remove();
      }
    })

    //选择车系
    $("#car_series select").change(function() {
      var brand_id = $("#car_brand select").val();
      var gc_series = $("#car_series select").val();
      if(gc_series != ''){
        $.getJSON("/index.php?act=goods&op=ajaxcardata&brand_id="+brand_id+"&gc_series="+gc_series+"&type=car_series", function(data){
          var  str='';
          $.each(data,function(item,index){
            str=str+"<option value='"+index.gc_id+"'><a><span>"+index.gc_name+"</span></a></option>";
          })
          $("#car_model select option:gt(0)").remove();
          $("#car_model select option:first").after(str);
          if(str==''){
            alert('没有对应的车系');
          }
        });
      }else{
        $("#car_model select option:gt(0)").remove();
      }
    })

      //选择用户订单主状态
      $("#user_order_status_select").change(function(){
          var user_pri_status = $("#user_order_status_select").val();
          if(user_pri_status != '') {
              $.getJSON("/index.php?act=order&op=ajax_order_status&pri_status=" + user_pri_status + "&type=1", function (data) {
                  var str = '';
                  $.each(data, function (item, index) {
                      str = str + "<option value='" + index.sub_status + "'><a><span>" + index.remark + "</span></a></option>";
                  })
                  $("#user_order_status_select select option:gt(0)").remove();
                  $("#user_order_status_select select option:first").after(str);
                  if (str == '') {
                      alert('没有对应的子状态');
                  }
              });
          }
      })

      //选择售方订单主状态
      $("#seller_order_status_select").change(function(){
          var seller_pri_status = $("#seller_order_status_select").val();
          if(seller_pri_status != '') {
              $.getJSON("/index.php?act=order&op=ajax_order_status&pri_status=" + seller_pri_status + "&type=2", function (data) {
                  var str = '';
                  $.each(data, function (item, index) {
                      str = str + "<option value='" + index.sub_status + "'><a><span>" + index.remark + "</span></a></option>";
                  })
                  $("#seller_order_status_select select option:gt(0)").remove();
                  $("#seller_order_status_select select option:first").after(str);
                  if (str == '') {
                      alert('没有对应的子状态');
                  }
              });
          }
      })



    //选择报价状态
    $("#bj_status").change(function(){
      var bj_status = $("#bj_status").val();
      if(bj_status==4 ||bj_status==5){
        $("#bj_is_public").find("option").eq(1).attr("disabled","disabled");
        $("#bj_is_public").find("option").eq(2).attr("disabled","disabled");
      }else{
        $("#bj_is_public").find("option").eq(1).removeAttr("disabled");
        $("#bj_is_public").find("option").eq(2).removeAttr("disabled");
      }
    })
  });

  $(window).on('hashchange', function() {
    if (window.location.hash) {
      var page = window.location.hash.replace('#', '');
      if (page == Number.NaN || page <= 0) {
        return false;
      } else {
          getOrdersTable(page);
      }
    }
  });

  //查询报价
  function getOrdersTable(page)
  {
    //记录ajax请求url
    var url="/index.php?"+$("form").serialize()+ "&curpage=" + page;
    history.pushState(null, '', url.replace("&query=1",""));

    $.ajax({
      url: url,
      datatype:'json',
    }).done(function (result) {
      $(".table").remove();
      $(".pagination").remove();
      $("#formSearch").after(result)
    })
  }
</script>
