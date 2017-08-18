<!DOCTYPE html>
<html>
<head>
    <title>{{ $title or trans('common.www_title') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车" />
    <meta name="keywords" content="华车" />
    <meta name="author" content="llm" />
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link href="{{asset('/webhtml/user/themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/common/css/common.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/custom/themes/custom.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/admin.custom.css')}}" rel="stylesheet" />
    @yield('css')

    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="head-wrapper">
        <div class="head-container">
            <div class="head-logo-wrapper psr">
                <a class="logo" href="/">华车</a>
            </div>
            <ul class="head-login-wrapper">
             @if(session('user.member_id') && session('user.is_login'))
                <li class="loginout">
                    <label>欢迎您登上 <a href="/dealer/" class="juhuang">{{getSellerName(session('user.member_id'))}}</a>号华车！</label>
                    <a @click="loginOutConfirm" href="javascript:;" class="hc-status-loginout ml20">下车</a>
                    <div v-cloak v-show="isShowLoginOut" class="login-out-comfirm">
                        <p class="tac">确认退出吗？</p>
                        <div class="confirm">
                            <a @click="sureLoginOut(1)" href="javascript:;">确认退出</a>
                            <a @click="canceLoginOut" href="javascript:;" class="cancel">取消</a>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
        <div class="clear"></div>
    </div>
<div class="container m-t-86 pos-rlt content">
    <div class="wapper has-min-step">
        <div class="box box-border noborder nopadding flex-dislay">
            <div class="slide slide-fix">
                @include('_layout.base_dealer_left')
            </div>
            @yield('content')
            <div class="clear"></div>

        </div>
    </div>

</div>

@include('HomeV2._layout.footer')

<script src="{{asset('/webhtml/custom/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/custom/js/config.js')}}"></script>
@yield('js')
</body>
</html>