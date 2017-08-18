<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车网" />
    <meta name="keywords" content="华车网" />
    <meta name="author" content="llm" />
    <link href="{{asset('/webhtml/common/css/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('/themes/common.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/common/css/common.css')}}" rel="stylesheet" />
    <link href="{{asset('/webhtml/custom/themes/custom.css')}}" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="./js/vendor/DatePicker/WdatePicker.js"></script>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
@include('_layout.dealer_header')
@yield('content')
    @include('HomeV2._layout.footer')
    <script src="{{asset('js/sea.js')}}"></script>
    <script src="{{asset('js/config.js')}}"></script>
    @yield('js')
</body>
</html>