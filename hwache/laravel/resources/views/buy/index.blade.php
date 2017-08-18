@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
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

<div class="container" style="margin: 0px auto;margin-top: 100px;width: 300px; ">
  <form action="{{ url('cart') }}" method="post" ms-submit="submitForm">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
      <label>姓名：<input type="text" name="username" ms-duplex-string="username"></label>
    </div>
    <div class="form-group">
      <label>手机：<input type="text" name="phone" ms-duplex-string="phone"></label>
    </div>
    <input type="submit" class="btn btn-default" value="提交" style="height: 40px;">
  </form>
  
</div>
@endsection

@section('js')
  <script type="text/javascript">
        seajs.use(["module/buy/index", "module/common/common", "bt"]);
    </script>
@endsection