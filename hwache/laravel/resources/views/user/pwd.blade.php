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
                <div class="form pwd-form-div">

                    <div class="input-group step">
                        <i ms-click="select(1)"></i>
                        <div class="step-c">
                            <h4>使用手机找回密码</h4>
                            <p>您的手机将收到验证码，本服务完全免费</p>
                            <span class="tag-1">接受验证码</span>
                            <span class="tag-2">设置新密码</span>
                        </div>
                    </div>
                  
                    <div class="input-group step">
                        <i ms-click="select(2)"></i>
                        <div class="step-c">
                            <h4>使用邮箱找回密码</h4>
                            <p>您的邮箱将收到一份邮件</p>
                            <span class="tag-1">查收邮件</span>
                            <span class="tag-2">设置新密码</span>
                        </div>
                    </div>
                    <input type="hidden" name="type" id="type">
                    <button ms-on-click="next" type="submit" class="btn btn-s-md btn-danger next">下一步</button>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/pwd/pwd", "module/common/common","bt"]);
    </script>
@endsection