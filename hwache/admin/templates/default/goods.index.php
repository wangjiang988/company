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

<div style="margin:20px" id="query_form">
    <form class="form-inline" role="form" method="post" name="formSearch" id="formSearch" action="index.php">
        <input type="hidden" value="goods" name="act">
        <input type="hidden" value="goods" name="op">
        <input type="hidden" value="1" name="query">
        <div class="form-group">
            <label class="control-label"><?=$lang['common_dealer_bj_number'];?></label>
            <input type="search" class="form-control" name="bj_serial" value="<?=isset($_GET['bj_serial'])?$_GET['bj_serial']:'';?>">
        </div>
        <div class="form-group">
            <label><?=$lang['common_name'];?></label>
            <input type="search" class="form-control" name="user_name" value="<?=isset($_GET['user_name'])?$_GET['user_name']:'';?>" style="width:100px">
        </div>
        <div class="form-group">
            <label><?=$lang['common_username'];?></label>
            <input type="search" class="form-control" name="user_realname" value="<?=isset($_GET['user_realname'])?$_GET['user_realname']:'';?>" style="width:100px">
        </div>
        <div class="form-group">
            <label><?=$lang['common_phone'];?></label>
            <input type="search" class="form-control" name="user_phone" value="<?=isset($_GET['user_phone'])?$_GET['user_phone']:'';?>">
        </div>
        <div class="form-group">
            <label><?=$lang['common_dealer_name'];?></label>
            <input type="search" class="form-control" name="dealer_name" value="<?=isset($_GET['dealer_name'])?$_GET['dealer_name']:'';?>">
        </div>
        <div class="form-group">
            <label><?=$lang['common_area'];?></label>
            <select class="form-control" name="dealer_area">
                <option value=""><?=$lang['common_bj_select']?></option>
                <?php foreach($output['area_list'] as $area):?>
                <option value="<?=$area['area_name'];?>" <?=(isset($_GET['dealer_area'])&&$_GET['dealer_area']==$area['area_name'])?'selected':'';?>><?=str_replace("	",'',$area['area_name']);?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="form-group" id="car_brand">
            <select class="form-control" name="brand_id">
                <option value=""><?=$lang['common_bj_brand_all'];?></option>
                <?php foreach($output['brand_list'] as $brand):?>
                    <option value="<?=$brand['gc_id'];?>" <?=(isset($_GET['brand_id'])&&$_GET['brand_id']==$brand['gc_id'])?'selected':'';?>><?=$brand['gc_name'];?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="form-group" id="car_series">
            <select class="form-control" name="gc_series" style="width: 104px;">
                <option value=""><?=$lang['common_car_series_all'];?></option>
                <?php if($output['gc_series']):?>
                    <?php foreach($output['gc_series'] as $gs):?>
                        <option value='<?=$gs['gc_name'];?>' <?=(isset($_GET['gc_series'])&&$_GET['gc_series']==$gs['gc_name'])?'selected':'';?>><a><span><?=$gs['gc_name'];?></span></a></option>
                    <?php endforeach;?>
                <?php endif;?>
            </select>
        </div>
        <div class="form-group" id="car_model">
            <select class="form-control" name="gc_name" style="width: 104px;">
                <option value=""><?=$lang['common_car_model_all'];?></option>
                <?php if($output['gc_name']):?>
                    <?php foreach($output['gc_name'] as $gn):?>
                        <option value='<?=$gn['gc_id'];?>' <?=(isset($_GET['gc_name'])&&$_GET['gc_name']==$gn['gc_id'])?'selected':'';?>><a><span><?=$gn['gc_name'];?></span></a></option>
                    <?php endforeach;?>
                <?php endif;?>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control" name="bj_status" id="bj_status">
                <option value=""><?=$lang['common_bj_status_all'];?></option>
                <?php foreach ($output['bj_status_list'] as $k=>$status) :?>
                     <option value="<?=$k;?>" <?=(isset($_GET['bj_status'])&&$_GET['bj_status']==$k)?'selected':'';?>><?=$status;?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control" name="bj_is_public" id="bj_is_public">
                <option value=""  <?=(isset($_GET['bj_is_public'])&&$_GET['bj_is_public']=="")?'selected':'';?>><?=$lang['common_bj_public_status_all'];?></option>
                <option value="1" <?=(isset($_GET['bj_is_public'])&&$_GET['bj_is_public']==1)?'selected':'';?>>正常</option>
                <option value="2" <?=(isset($_GET['bj_is_public'])&&$_GET['bj_is_public']==2)?'selected':'';?>>异常</option>
                <option value="0" <?=(isset($_GET['bj_is_public'])&&is_numeric($_GET['bj_is_public'])&&$_GET['bj_is_public']==0)?'selected':'';?>>无</option>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control" name="bj_is_xianche">
                <option value=""  <?=(isset($_GET['bj_is_xianche'])&&$_GET['bj_is_xianche']=="")?'selected':'';?>><?=$lang['common_car_spot_status_all'];?></option>
                <option value="1" <?=(isset($_GET['bj_is_xianche'])&&$_GET['bj_is_xianche']==1)?'selected':'';?>>现车</option>
                <option value="0" <?=(isset($_GET['bj_is_xianche'])&&is_numeric($_GET['bj_is_xianche'])&&$_GET['bj_is_xianche']==0)?'selected':'';?>>非现车</option>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control" name="bj_is_pass">
                <option value="" <?=(isset($_GET['bj_is_pass'])&&$_GET['bj_is_pass']=="")?'selected':'';?>><?=$lang['common_bj_check_status_all'];?></option>
                <option value="1" <?=(isset($_GET['bj_is_pass'])&&$_GET['bj_is_pass']==1)?'selected':'';?>>已人工审核</option>
                <option value="0" <?=(isset($_GET['bj_is_pass'])&&is_numeric($_GET['bj_is_pass'])&&$_GET['bj_is_pass']==0)?'selected':'';?>>未人工审核</option>
            </select>
        </div>
        <div class="form-group" style="width:44%">
            <label class="control-label"><?=$lang['common_bj_created_time'];?></label>
            <div class="input-group date form_date_start" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="common_bj_created_starttime" value="<?=isset($_GET['common_bj_created_starttime'])?$_GET['common_bj_created_starttime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>~
            <div class="input-group date form_date_end" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="common_bj_created_endtime" value="<?=isset($_GET['common_bj_created_endtime'])?$_GET['common_bj_created_endtime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>
        </div>
        <div class="form-group" style="width:44%">
            <label class="control-label"><?=$lang['common_bj_effectived_time'];?></label>
            <div class="input-group date form_date_start" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="common_bj_effectived_starttime" value="<?=isset($_GET['common_bj_effectived_starttime'])?$_GET['common_bj_effectived_starttime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>~
            <div class="input-group date form_date_end" style="width:40%"><input type="datetime" class="form-control form_datetime" readonly name="common_bj_effectived_endtime" value="<?=isset($_GET['common_bj_effectived_endtime'])?$_GET['common_bj_effectived_endtime']:'';?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>
        </div>
        <div class="form-group">
        <button type="button" class="btn btn-warning" id="search"><?=$lang['common_search'];?></button>
        <button type="button" class="btn btn-default" id="reset"><?=$lang['common_reset'];?></button>
        </div>
    </form>
    <?php require_once("goods.index.table.php"); ?>
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
        window.location.href="/index.php?act=goods&op=goods";
    })

    //查询
    var page=1;
    $("#search").click(function(){
        //校验参数
        var created_starttime = $("input[name='common_bj_created_starttime']").val();
        var created_endtime = $("input[name='common_bj_created_endtime']").val();
        var effectived_starttime = $("input[name='common_bj_effectived_starttime']").val();
        var effectived_endtime = $("input[name='common_bj_effectived_endtime']").val();

        if(created_starttime&&created_endtime&&created_starttime>created_endtime){
            showDialog('报价开始时间应该小于结束时间');
            return;
        }

        if(effectived_starttime&&effectived_endtime&&effectived_starttime>effectived_endtime){
            showDialog('生效开始时间应该小于结束时间');
            return;
        }

        getBaojiaTable(page);
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
            getBaojiaTable(page);
        }
    }
});


//查询报价
function getBaojiaTable(page)
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
