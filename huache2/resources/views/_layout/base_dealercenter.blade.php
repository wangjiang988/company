<!DOCTYPE html>
<html>
<head>
    <title>{{ $title or trans('common.www_title') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车网" />
    <meta name="keywords" content="华车网" />
    <meta name="author" content="llm" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/common.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/common/css/common.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/user.css')}}" rel="stylesheet" />
     <link href="{{asset('themes/admin.custom.css')}}" rel="stylesheet" />

    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>
<body id="web">

    @include('_layout.dealer_header')

    <div class="container m-t-86 pos-rlt content" ms-controller="custom">
        <div class="wapper has-min-step">
            <div class="box box-border flex-dislay">
                <div class="slide">
                    @include('_layout.base_dealer_left')
                </div>
                <div class="user-content custom-content">
                    @yield('content')
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="box">
            <footer class="footer">
                <div class="sp"></div>
                <div class="container psr foot-info foot-info-new ">
                    <p>&copy;CopyRight 2015-2017,苏州华车网络科技有限公司 版权所有</p>
                    <p>工信部备案号：<a href="http://www.miitbeian.gov.cn" target="_blank" class="blue">苏ICP备16001420号</a></p>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{asset('js/sea.js')}}"></script>
    <script src="{{asset('js/config.js')}}"></script>
    @yield('js')
    @section('zm')

</body>
</html>