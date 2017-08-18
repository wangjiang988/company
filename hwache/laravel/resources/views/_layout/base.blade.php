<!DOCTYPE html>
<html>
<head>
<title>{{ $title or trans('common.www_title') }}</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="renderer" content="webkit">
<link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
<link href="{{asset('themes/common.css')}}" rel="stylesheet" />
<!--[if lt IE 9]>
<script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
@yield('css')
</head>
<body ms-controller="web">
@yield('nav')
    @yield('content')
    <footer class="footer">
    <div class="footer-menu container">
        <ul>

            <li>
                <h3>用户指南</h3>
                <a href="#">买车流程</a>
                <a href="#">诚信保障</a>
                <a href="#">注意事项</a>
            </li>
            <li>
                <h3>服务中心</h3>
                <a href="#">服务协议</a>
                <a href="#">平台规则</a>
                <a href="#">常见问题</a>
            </li>
            <li>
                <h3>关于我们</h3>
                <a href="#">平台简介</a>
                <a href="#">联系方式</a>
                <a href="#">发现职位</a>
            </li>
            <li>
                <h3>商务合作</h3>
                <a href="#">加盟方入口</a>
                <a href="#">媒体合作</a>
                <a href="#">友情链接</a>
            </li>
            <li>
              <div  class="qrcode"><p>加微信关注我们</p></div>
            </li>

        </ul>
    </div>
    <div class="sp"></div>
    <div class="container pos-rlt foot-info">
        <p>@ CopyRight 2014-{{ date('Y') }}, 苏州华车网络科技有限公司 版权所有</p>
        <p> 工业信息化部信息备案：苏ICP备14017673号-1 </p>
        <img src="/themes/images/common/beian.gif" class="beian" alt="">
    </div>
</footer>

<script src="{{asset('js/sea.js')}}"></script>
<script src="{{asset('js/config.js')}}"></script>
@yield('js')

@section('zm')
  @if(!session('user.is_login'))
  <div class="zm">
    <div id="login" class="">
      <div class="l-wapper">
        <div class="l-head">
          <div class="l-h-bg">用户登录<a href="javascript:;" ms-click="closeLogin" class="login-close"></a></div>
        </div>
        <div class="l-c">
          <br>
          <div class="input-group">
            <span class="input-group-addon">账号:</span>
            <input data-toggle="tooltip" data-placement="bottom" name="loginname" type="text" required class="form-control" placeholder="手机号/邮箱" title="手机号/邮箱" aria-describedby="basic-addon1">
            <span class="input-group-addon hide error">请正确输入</span>
          </div>
          <br>
          <div class="input-group">
            <span class="input-group-addon">密码:</span>
            <input data-toggle="tooltip" type="password" data-placement="top" name="loginpwd" type="text" required class="form-control" placeholder="请输入密码" title="请输入密码" aria-describedby="basic-addon1">
            <span class="input-group-addon hide error">请正确输入</span>
          </div>
          <br>
          {{--<div class="input-group code">
              <span class="input-group-addon">请输入验证码:</span>
              <input name="logincode" type="text" required class="form-control" placeholder="请输入验证码" title="请输入验证码" aria-describedby="basic-addon1">
              <span class="input-group-addon hide error">请正确输入</span>
              <span class="input-group-addon valite-code"><img src="{{ $_ENV['_CONF']['config']['shop_site_url'] }}/index.php?act=seccode&op=makecode&nchash=521e168c" /></span>
          </div>--}}
          <div class="pos-rlt">
            <button ms-on-click="SmipSubLoign" type="submit" class="btn btn-s-md btn-danger">立即登陆</button>
            <input type="hidden" id="hgPostUrl" value="{{ route('user.login') }}">
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
  @endif
@show
</body>
</html>
