@extends('_layout.base')
@section('css')
<link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span></a> </label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
<div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content" style=" left: 187px;">
                    <small>正在支付</small>
                    <i></i>
                    <small class="juhuang" class="">查收确认</small>
                    <div class="clear"></div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper">  
        	<p>&nbsp;</p>
            <p>尊敬的客户：</p>
            <p>华车网平台已收到您在规定时间内支付的买车担保金人民币 {{$money}} 元，您的购车计划正顺利推进中！</p>
            <?php if ($baojia['bj_producetime']): ?>
                <p>【现车】</p>
            <p>根据平台规则，经销商应在7日内向您发出交车邀请，15日内可向您移交符合订单要求的车辆（具体提车日期尊重您的安排）。</p>
            <?php endif ?>
            <?php if ($baojia['bj_jc_period']): ?>
                <p>【非现车】</p>
            <p>根据平台规则，经销商应在约定交车日期（{{$new_date}}）前，至少提前7日，也就是{{$date_tiqian}}前向您发出交车邀请，在约定交车日期</p>
            <p>（{{$new_date}}）前向您移交符合订单要求的车辆（具体提车日期尊重您的安排）。</p>
            <?php endif ?>

            
            <p>如果经销商超时，您将有权取消订单，退还已付买车担保金，并获得华车网加信宝提供的额外补偿：</p>
            <p>歉意金人民币{{$jine['qianyijin']}}元和买车担保金人民币{{$guarantee}}元冻结期间的利息（日利率万分之二）。</p>
            <p>如您对随后经销商提出的延期交车修改方案满意，您也可在获得歉意金和前期买车担保金利息的基础上，选择不退还买车担保金，执行修改方案，</p>
            <p>华车网加信宝继续为您保驾护航！</p>
            <p>请您暂时按捺一下拥有心仪座驾的急迫性情，期待我们为您精心准备得妥妥哒！</p>
            <div class="split">
                <a href="{{url('orderoverview')}}/{{$order_num}}" target="_blank">查看我的订单</a>
                <a href="#myorder">打造更个性的座驾</a>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-pay-2-sucess", "module/common/common", "bt"]);
    </script>
@endsection