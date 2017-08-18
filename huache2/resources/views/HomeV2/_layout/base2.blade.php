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
    <link href="{{asset('/themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/common/css/common.css')}}" rel="stylesheet" />
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>
<body>

@include('HomeV2._layout.header2')
@yield('content')
@yield('footer')
@include('HomeV2._layout.login')
@yield('js')

</body>
</html>


