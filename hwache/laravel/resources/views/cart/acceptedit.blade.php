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
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span> </a></label>
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
                <li class="step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content">
                    <small>选择产品</small>
                    <i></i>
                    <small>付诚意金</small>
                    <i></i>
                    <small class="juhuang" class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>

    <div  class="container pos-rlt content" ms-controller="item">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">因您对经销商办理上牌特别文件回复无法达成一致，很遗憾本订单已终止。退还的诚意金人民币{{$chengyijin}}元，已进入您的可用账户。</p>
            <hr class="dashed">
            <p class="fs14"><b>可用账户余额：</b>人民币1,368.00元<a href="#" class="juhuang">（查看）</a></p>
            <p class="fs14">您可在华车平台继续购车时使用上述余额，如不买车，可在《我的华车》中申请退款或提现（按银行规定办理）。</p>
            <p class="tal">
                <a href="javascript:;"  class="btn btn-s-md btn-danger sure">再选辆车</a>
            </p>
            
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>
@endsection
@section('js')
   
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"]);
    </script>
@endsection