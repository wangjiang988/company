@extends('_layout.base')
@section('css')
<link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
                <span class="reg-h pos-abt" ms-on-mouseover="showLocation" ms-on-mouseout="hideLocation">
                   找回密码
                </span>
            </div>
            <ul class="nav navbar-nav">

            </ul>
            <ul class="nav navbar-nav control">
                {{-- <li class=""><a ms-click="login" href="javascript:;">快速登陆</a><i></i></li> --}}
                <li><a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a></li>
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
<div class="container m-t-86 pos-rlt" ms-controller="reg">
        <div class="wapper">
            <div class="hd">
                <ul>
                    <li class="cur"><span>1</span><label>接受效验码</label></li>
                    <li><span>2</span><label>重置密码</label></li>
                    <li><span>3</span><label>修改成功</label></li>
                    <div class="clear"></div>
                </ul>
                <div class="form  login-form-div pwd-form-div">
                    <div class="input-group">
                        <span class="input-group-addon">手机号:</span>
                        <input data-toggle="tooltip" data-placement="bottom" name="phone" type="text" required class="form-control" placeholder="请输入手机号码" title="请输入手机号码" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入手机号</span>
                    </div>
                    <br>
                    <div class="input-group pos-rlt">
                        <span class="input-group-addon">验证码:</span>
                        <input data-toggle="tooltip" data-placement="top" name="code" type="text" required class="form-control" placeholder="验证码" title="验证码" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入验证码</span>
                        <a ms-on-click="SendCode" data-s="发送验证码" data-send="重新获取($1)" class="input-group-addon btn btn-default sendcode" >发送验证码</a>
                        <div class="form-loading"></div>
                    </div>
                    <button ms-on-click="SendAndSetPwd" type="submit" class="btn btn-s-md btn-danger">下一步</button>
                    <p><a href="{{ URL::route('getpwdbyemail') }}" class="use">使用邮箱修改密码&gt;&gt;</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/pwd/pwd-phone-step1", "module/common/common","bt"]);
    </script>
@endsection