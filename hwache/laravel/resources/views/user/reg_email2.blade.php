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
                   注册账号
                </span>
            </div>
            <ul class="nav navbar-nav">

            </ul>
            <ul class="nav navbar-nav control">
                <li class=""><a ms-click="login" href="javascript:;">快速登陆</a><i></i></li>
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
            
                <div class="form">
                    <h3 class="email-sucess">邮件发送成功，请进入邮箱查收邮件！</h3>
                    <p>
                        <a href="/regbyemail">重新发送邮件</a>
                    </p>
                    <p>
                        <label><span>*</span>如果没有成功收到邮件，点击重新发送</label>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["module/reg/reg-email-send-sucess", "module/common/common", "bt"]);
    </script>
@endsection