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
    <div class="col-sm-1"><a href="mailto:#">订单管理</a></div>
    <div class="col-sm-2">查看订单</div>
</div>
<hr style="border-top:1px solid #0000C2"/>
<table class="table order-intro" >
    <tr>
        <td>订单号：</td>
        <td></td>
        <td><a href="">查看订单总详情</a></td>
        <td></td>
        <td>订单时间：</td>
        <td></td>
        <td>结束时间：</td>
        <td></td>
    </tr>
    <tr>
        <td>品牌/车系/车型 ：</td>
        <td colspan="5"></td>
        <td>基本配置</td>
        <td><a href="">查看</a></td>
    </tr>
    <tr>
        <td>厂商指导价/整车型号/生产国别 / 座位数 / 排放标准：</td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td>是否现车 / 交车时限：</td>
        <td colspan="5"></td>
        <td>基本配置</td>
        <td><a href="">查看</a></td>
    </tr>
</table>
<hr/>
<table class="table order-intro" >
    <tr>
        <td>售方用户名/姓名/手机号：</td>
        <td></td>
        <td colspan="5">报价编号</td>
        <td></td>
    </tr>
    <tr>
        <td>经销商/所属地区/交车地点：</td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td>车辆开票价格/客户买车定金/售方服务费：</td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td>售方当前状态：</td>
        <td colspan="7"></td>
    </tr>
</table>
<hr/>
<table class="table order-intro" >
    <tr>
        <td>客户会员号/姓名/称呼/手机号：</td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td>计划上牌地区/交车地点范围：</td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td>华车车价/买车担保金/华车服务费：</td>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td>客户当前状态：</td>
        <td colspan="7"></td>
    </tr>
</table>
<hr/>
<div>
    <form class="form-inline">
        <div class="form-group">
            <label>相关工单</label>
        </div>
        <div class="form-group">
            <select class="form-control">
                <option>全部状态</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control">
                <option>全部对象</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
            </select>
        </div>
    </form>
</div>

<table class="table table-bordered">
    <tr class="info">
        <td>工单编号</td>
        <td>工单时间</td>
        <td>工单对象</td>
        <td width="40%">主题</td>
        <td>当前处理部门</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
</table>
<br/>
<br/>

<div class="form-inline text-center">
    <button type="button" class="btn btn-default">新建工单</button>
    <button type="button" class="btn btn-default">返回</button>
    <button type="button" class="btn btn-default">停止交车邀请倒计时</button>
    <button type="button" class="btn btn-default">设定交车时间</button>
</div>


<script>
    $(function(){
        $(".order-intro td").css("border","none");
    })
</script>