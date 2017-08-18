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
                    <li><span>1</span><label>接受效验码</label></li>
                    <li><span>2</span><label>重置密码</label></li>
                    <li class="cur"><span>3</span><label>修改成功</label></li>
                    <div class="clear"></div>
                </ul>
                <div class="form  login-form-div pwd-form-div">
                    <h3>恭喜您的操作已完成！</h3>
                    <p>
                        <a href="/">返回首页</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/pwd/pwd-phone-step3", "module/common/common","bt"]);
    </script>
@endsection