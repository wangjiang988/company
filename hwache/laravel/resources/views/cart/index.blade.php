@extends('_layout.base')
@section('css')
<link href="{{asset('themes/theme.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
                <span class="location pos-abt" ms-on-mouseover="showLocation" ms-on-mouseout="hideLocation">
                    <label>{{ session('area.area_name') }}</label>
                    <i></i>
                    <div class="loca-c">
                        <a href="javascript:;" ms-click="selectArea(1)">南京</a>
                        <a href="javascript:;" ms-click="selectArea(166)">苏州</a>
                        <a href="javascript:;" ms-click="selectArea">无锡</a>
                        <a href="javascript:;" ms-click="selectArea">常州</a>
                        <a href="javascript:;" ms-click="selectArea">淮安</a>
                        <a href="javascript:;" ms-click="selectArea">连云港</a>
                        <a href="javascript:;" ms-click="selectArea">南通</a>
                        <a href="javascript:;" ms-click="selectArea">盐城</a>
                        <a href="javascript:;" ms-click="selectArea">扬州</a>
                        <a href="javascript:;" ms-click="selectArea">镇江</a>
                        <a href="javascript:;" ms-click="selectArea">泰州</a>
                        <a href="javascript:;" ms-click="selectArea">徐州</a>
                        <p></p>
                        <a href="javascript:;" ms-click="selectArea">安徽</a>
                        <a href="javascript:;" ms-click="selectArea">北京</a>
                        <a href="javascript:;" ms-click="selectArea">福建</a>
                        <a href="javascript:;" ms-click="selectArea">甘肃</a>
                        <a href="javascript:;" ms-click="selectArea">广东</a>
                        <a href="javascript:;" ms-click="selectArea">广西</a>
                        <a href="javascript:;" ms-click="selectArea">贵州</a>
                        <a href="javascript:;" ms-click="selectArea">海南</a>
                        <a href="javascript:;" ms-click="selectArea">河北</a>
                        <a href="javascript:;" ms-click="selectArea">河南</a>
                        <a href="javascript:;" ms-click="selectArea">黑龙江</a>
                        <a href="javascript:;" ms-click="selectArea">湖北</a>
                        <a href="javascript:;" ms-click="selectArea">湖南</a>
                        <a href="javascript:;" ms-click="selectArea">吉林</a>
                        <a href="javascript:;" ms-click="selectArea">江苏</a>
                        <a href="javascript:;" ms-click="selectArea">江西</a>
                        <a href="javascript:;" ms-click="selectArea">辽宁</a>
                        <a href="javascript:;" ms-click="selectArea">内蒙古</a>
                        <a href="javascript:;" ms-click="selectArea">宁夏</a>
                        <a href="javascript:;" ms-click="selectArea">青海</a>
                        <a href="javascript:;" ms-click="selectArea">山东</a>
                        <a href="javascript:;" ms-click="selectArea">山西</a>
                        <a href="javascript:;" ms-click="selectArea">陕西</a>
                        <a href="javascript:;" ms-click="selectArea">上海</a>
                        <a href="javascript:;" ms-click="selectArea">四川</a>
                        <a href="javascript:;" ms-click="selectArea">天津</a>
                        <a href="javascript:;" ms-click="selectArea">西藏</a>
                        <a href="javascript:;" ms-click="selectArea">新疆</a>
                        <a href="javascript:;" ms-click="selectArea">云南</a>
                        <a href="javascript:;" ms-click="selectArea">浙江</a>
                        <a href="javascript:;" ms-click="selectArea">重庆</a>

                    </div>
                </span>
            </div>
            <ul class="nav navbar-nav">
                <li class=""><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class=""><a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}">{{ $_SESSION['member_name'] }}</a><i></i></li>
                <li><a href="{{ route('logout') }}">退出登录</a></li>
            @else
                <li class=""><a ms-click="login" href="javascript:;">快速登陆</a><i></i></li>
                <li><a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a></li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
  <div style="margin-top:100px;">
    <form action="{{ url('cart/pay') }}" method="post">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <p>您购买的车为：XXXXXX</p>
    <p>请支付诚意金：￥ {{ $chengyijin }}</p>
    <p>
      <label><input type="radio" name="alipay" checked="checked"> 支付宝</label>
    </p>
    <p><input type="submit" class="btn btn-default" value="立即支付" /></p>
    </form>
  </div>
@endsection
@section('js')
<script type="text/javascript">
            seajs.use(["module/index/index", "module/common/common","bt"]);
        </script>
@endsection
