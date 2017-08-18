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
                <ul>
                    <li class="cur"><span>1</span><label>设置用户名</label></li>
                    <li><span>2</span><label>设置密码</label></li>
                    <li><span>3</span><label>注册成功</label></li>
                    <div class="clear"></div>
                </ul>
                <div class="form">
                    <div class="input-group">
                        <span class="input-group-addon">邮箱地址:</span>
                        <input data-toggle="tooltip" data-placement="bottom" name="email" type="text" required class="form-control" placeholder="请输入邮箱地址" title="请输入邮箱地址" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入邮箱地址</span>
                        <a ms-on-click="SendCode" data-s="发送邮件" data-send="重新获取($1)" class="input-group-addon btn btn-default sendcode email-code">发送邮件</a>
                        <div class="form-loading"></div>

                    </div>
                    <p><a href="{{ URL::route('regbyphone') }}" >使用手机注册&gt;&gt;</a></p>
                    <br>
                  
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/reg/reg-email", "module/common/common","bt"]);
    </script>
@endsection