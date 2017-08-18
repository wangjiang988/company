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
<div class="container">
<br/>
<form class="form-horizontal" method="post" action="">
    <input type="hidden" name="query" value="1">
    <input type="hidden" name="order_id" value="<?=$output['order_id'];?>">
    <div class="form-group">
        <label class="control-label col-sm-3">订单时间</label>
        <div class="input-group date form_date_start col-sm-6">
            <input type="datetime" class="form-control form_datetime" name="system_data">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
    </div>
    <div class="form-group">
        <select class="col-sm-offset-3 col-sm-6" id="system_day" name="system_day">
            <option value="1" selected="selected">上午/下午</option>
            <option value="2">上午</option>
            <option value="3">下午</option>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">超期费:￥</label>
        <div class="input-group col-sm-6">
            <input type="number" class="form-control" name="system_out_price">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-1">
            <button type="button" class="btn btn-info" onclick="confirm_appoint_car_time()">确定</button>
        </div>
        <div class="col-sm-1">
            <input type="reset" class="btn btn-default" value="取消">
        </div>
    </div>
</form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
$(function(){
    $('.form_date_start').datepicker({
    language: "zh-CN",
    format: 'yyyy-mm-dd',
    weekStart: 1,
    autoclose: 1,
    todayHighlight: true,
    forceParse: 0,
    pickerPosition: "bottom-left",
    clearBtn: 1
    });
})

//检查表单
function confirm_appoint_car_time()
{
    var system_data = $("input[name=system_data]").val();
    if(system_data==undefined || system_data==''){
        layer.msg('请选择交车时间');
        return false;
    }
    var system_out_price = $("input[name=system_out_price]").val();
    if(system_out_price==undefined || system_out_price==''){
        layer.msg('请选择超期费');
        return false;
    }

    //提示用户确认下信息
    layer.confirm('与双方确认已达成<br/>交车时间：'+system_data+'<br/>超期费：'+system_out_price,{
        btn:['确认','取消'],
        title:'确认交车时间'
        }, function(){
            $("form").submit();
        }, function(){
            layer.close();
        });
}
</script>
