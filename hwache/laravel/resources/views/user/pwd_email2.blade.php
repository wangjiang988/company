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
                    <li><span>1</span><label>查收邮件</label></li>
                    <li class="cur"><span>2</span><label>重置密码</label></li>
                    <li><span>3</span><label>修改成功</label></li>
                    <div class="clear"></div>
                </ul>
                <form action="/getpwdbyemail3" name="pwdform" method="post">
                <div class="form  login-form-div pwd-form-div">
                    <div class="input-group">
                        <span class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;新密码:</span>
                        <input data-toggle="tooltip" type="password" data-placement="bottom" name="pwd"  required class="form-control" placeholder="请输入密码" title="请输入密码" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入密码</span>
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">确认密码:</span>
                        <input data-toggle="tooltip" type="password" data-placement="bottom" name="pwd2"  required class="form-control" placeholder="请输入确认密码" title="请输入确认密码" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入确认密码</span>
                    </div>
                    <input type="hidden" name="email" value="{{ $email}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button ms-on-click="UpdatePwd" type="button" class="btn btn-s-md btn-danger">下一步</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/pwd/pwd-email-step2", "module/common/common","bt"]);
    </script>
@endsection