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
    hr {height:2px;border:none;border-top:1px solid #000000}
</style>
<div class="row">
    <div class="col-sm-1"><a href="/index.php?act=order&op=index">订单管理</a></div>
    <div class="col-sm-2">查看订单</div>
    <div class="col-sm-1">></div>
    <div class="col-sm-2">查看工单</div>
</div>
<hr style="border-top:1px solid #0000C2"/>
<table class="table order-intro" >
    <tr>
        <td>订单号：<?=$output['order']['id'];?></td>
        <td></td>
        <td><a href="#" style="color:#FF8931">查看订单总详情</a></td>
        <td></td>
        <td>订单时间：</td>
        <td><?=$output['order']['created_at'];?></td>
        <td>结束时间：</td>
        <td><?=$output['order']['order_status']==99?$output['order']['updated_at']:'';?></td>
    </tr>
    <tr>
        <td>品牌/车系/车型 ：</td>
        <td colspan="5"><?=$output['baojia']['gc_name'];?></td>
        <td>基本配置</td>
        <td><a href="#">查看</a></td>
    </tr>
    <tr>
        <td>厂商指导价/整车型号/生产国别 / 座位数 / 排放标准：</td>
        <td colspan="7"><?='￥'.number_format($output['zhidaojia'],2).'/'.$output['car_goods_class']['vehicle_model'].'/'.$output['guobie'].'/'.$output['seat_num'].'/'.$output['paifang'];?></td>
    </tr>
    <tr>
        <td>是否现车 / 交车时限：</td>
        <td colspan="5"><?=$output['baojia']['bj_is_xianche']?'现车':'非现车'; ?>/<?=$output['appoint_car_time'];?></td>
        <td>车身颜色/内饰颜色</td>
        <td><?=$output['body_color'].'/'.$output['interior_color'];?></td>
    </tr>
</table>
<hr/>
<table class="table order-intro" >
    <tr>
        <td>售方用户名/姓名/手机号：</td>
        <td><?=$output['seller']['member_name'].'/'.$output['seller']['member_truename'].'/'.$output['seller']['member_mobile'];?></td>
        <td colspan="5">报价编号</td>
        <td><?=$output['baojia']['bj_id'];?></td>
    </tr>
    <tr>
        <td>经销商/所属地区/交车地点：</td>
        <td colspan="7"><?=$output['dealer']['d_name'].'/'.$output['dealer']['d_areainfo'].$output['dealer']['d_jc_place'];?></td>
    </tr>
    <tr>
        <td>车辆开票价格/客户买车定金/售方服务费：</td>
        <td colspan="7"><?=$output['price']['car_price'].'/'.$output['price']['client_hand_price'].'/'.$output['price']['agent_service_price'];?></td>
    </tr>
    <tr>
        <td>售方当前状态：</td>
        <td colspan="7"><?=$output['order_progress_status']['seller_progress'];?></td>
    </tr>
</table>
<hr/>
<table class="table order-intro" >
    <tr>
        <td>客户会员号/姓名/称呼/手机号：</td>
        <td colspan="7"><?=$output['user']['id'].'/'.$output['user']['extension']['last_name'].$output['user']['extension']['first_name'].'/'.$output['user']['extension']['call'].'/'.$output['user']['phone']?></td>
    </tr>
    <tr>
        <td>计划上牌地区/交车地点范围：</td>
        <td colspan="7"><?=$output['shangpai_area'].'/'.$output['area'];?></td>
    </tr>
    <tr>
        <td>华车车价/买车担保金/华车服务费：</td>
        <td colspan="7"><?=$output['price']['hwache_price'].'/'.$output['order']['sponsion_price'].'/'.$output['price']['hwache_service_price'];?></td>
    </tr>
    <tr>
        <td>客户当前状态：</td>
        <td colspan="7"><?=$output['order_progress_status']['user_progress'];?></td>
    </tr>
</table>

<h4 style="background-color: rgb(206, 239, 239);padding: 10px;"><font style="color:#3590C7">工单内容</font></h4>

<form class="form-horizontal" method="post"  enctype="multipart/form-data" onsubmit="return checkForm()">
    <input type="hidden" name="query" value="1">
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label"><b style="color:red">*</b>工单对象</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="target" value="3">售方
            </label>
            <label class="radio-inline">
                <input type="radio" name="target" value="2">客户
            </label>
            <label class="radio-inline">
                <input type="radio" name="target" value="1">平台
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label"><b style="color:red">*</b>主题</label>
        <div class="col-sm-10">
            <input type="text" class="form-control"  name="subject" id="subject" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">问题描述</label>
        <div class="col-sm-10">
            <textarea class="form-control" rows="3" name="content"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">证据</label>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="上传" d-i="0" name="files[0]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="上传" d-i="1" name="files[1]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="上传" d-i="2" name="files[2]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="上传" d-i="3" name="files[3]">
        </div>
        <div class="col-sm-2">
            <input type="file" class="form-control" placeholder="上传" d-i="4" name="files[4]">
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label"><b style="color:red">*</b>跟进处理部门</label>
        <div class="col-sm-10">
            <label class="radio-inline">
                <input type="radio" name="follow_depid" value="2">客服部
            </label>
            <label class="radio-inline">
                <input type="radio" name="follow_depid" value="1">财务部
            </label>
            <label class="radio-inline">
                <input type="radio" name="follow_depid" value="4">法务部
            </label>
            <label class="radio-inline">
                <input type="radio" name="follow_depid" value="3">运营部
            </label>
        </div>
    </div>
    <br/>
    <br/>

    <div class="form-inline text-center">
        <input type="submit" class="btn btn-default" value="提交新工单">
        <input type="button" class="btn btn-default" onclick="javascript:window.history.go(-1);" value="返回订单">
    </div>
</form>



<script>
    $(function(){
        $(".order-intro td").css("border","none");

        //隐藏上传图片按钮
        $("input[type=file]:gt(0)").hide();

        $("input[type=file]").change(function(){
            var i =parseInt($(this).attr("d-i"))+2;
            $("input[type=file]:lt("+i+")").show();
        });
    })

    function checkForm()
    {
        var target = $("input[name=target]:checked").val();
        if(target==""||target==undefined){
            alert("请填写工单对象");
            return false;
        }

        var subject = $("#subject").val()
        if(subject==""){
            alert("请填写主题");
            return false;
        }

        var follow_depid = $("input[name=follow_depid:checked").val();
        if(follow_depid==""||follow_depid==undefined){
            alert("请填写跟进部门");
            return false
        }
        return true;
    }
</script>