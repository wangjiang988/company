<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.job_title') }} - {{ $title or '' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="@yield('description')" />
    <meta name="keywords" content="@yield('keywords')" />
    <meta name="author" content="llm" />
    <link href="{{asset('/webhtml/user/themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/common/css/common.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/user/themes/user.css')}}" rel="stylesheet" />
    @yield('css')
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body ms-controller="web">
@yield('nav')

@yield('content')

@yield('footer')
@yield('login')

<script src="{{asset('/webhtml/user/js/sea.js')}}" ></script>
<script src="{{asset('/webhtml/user/js/config.js')}}"></script>
@yield('js')
</body>
</html>