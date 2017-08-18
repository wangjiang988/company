@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
  <div class="container m-t-86 pos-rlt" ms-controller="login">
    <div class="wapper">
      <div class="hd">
        <h4 class="login-title">用户登录</h4>
        <div class="form login-form-div">
          <div class="input-group">
            <span class="input-group-addon">账&nbsp;&nbsp;&nbsp;&nbsp;户:</span>
            <input data-toggle="tooltip" data-placement="bottom" name="login-name" type="text"  class="form-control" placeholder="手机号/邮箱" title="请输入账号" aria-describedby="basic-addon1">
            <span class="input-group-addon hide error">请正确输入账号</span>
          </div>
          <br>
          <div class="input-group">
            <span class="input-group-addon">密&nbsp;&nbsp;&nbsp;&nbsp;码:</span>
            <input data-toggle="tooltip" data-placement="bottom" name="login-pwd" type="password"  class="form-control" placeholder="密码" title="请正确输入密码" aria-describedby="basic-addon1">
            <span class="input-group-addon hide error">请正确输入密码</span>
          </div>
          <br>
          {{--<div class="input-group pos-rlt">
            <span class="input-group-addon">验证码:</span>
            <input data-toggle="tooltip" data-placement="top" name="login-code" type="text"  class="form-control" placeholder="验证码" title="验证码" aria-describedby="basic-addon1">
            <span class="input-group-addon hide error">请正确输入验证码</span>
            <span class="input-group-addon valite-code"><img onclick="this.src='{{ $_ENV['_CONF']['config']['shop_site_url'] }}/index.php?act=seccode&op=makecode&nchash=521e168c'+Math.random()" src="{{ $_ENV['_CONF']['config']['shop_site_url'] }}/index.php?act=seccode&op=makecode&nchash=521e168c"></span>
            <div class="form-loading"></div>
          </div>--}}
          <div class="pos-rlt">
            <button ms-click="SendAndSetPwd" type="submit" class="btn btn-s-md btn-danger">登陆</button>
            <div class="smip-login-loading"></div>
          </div>
          <p class="reg-help">
            <a href="{{ route('user.reg.reset_pwd') }}">忘记密码?</a>
            <a href="{{ route('user.reg.fill_mobile') }}" class="simp-reg">快捷注册</a>
          </p>
          <div class="reg-method">
            <span>其他账号登陆</span>
            <a href="javascript:;" class="l-sina">新浪登陆</a>
            <a href="javascript:;" class="l-qq">QQ登陆</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
  <script type="text/javascript">
    var config = {
      postUrl: '{{ route("user.login") }}',
      nextUrl: '{{ route("user.ucenter") }}'
    };
    seajs.use(["module/login/login", "module/common/common","bt"]);
  </script>
@endsection