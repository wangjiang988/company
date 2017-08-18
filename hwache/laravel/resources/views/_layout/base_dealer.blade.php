<!DOCTYPE html>
<html>
<head>
    <title>{{ $title or trans('common.www_title') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/common.css')}}" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>
<body ms-controller="web">
@yield('nav')
    @yield('content')
    <footer class="footer">
    <div class="footer-menu container">
        <ul>

            <li>
                <h3>用户指南</h3>
                <a href="#">买车流程</a>
                <a href="#">诚信保障</a>
                <a href="#">注意事项</a>
            </li>
            <li>
                <h3>服务中心</h3>
                <a href="#">服务协议</a>
                <a href="#">平台规则</a>
                <a href="#">常见问题</a>
            </li>
            <li>
                <h3>关于我们</h3>
                <a href="#">平台简介</a>
                <a href="#">联系方式</a>
                <a href="#">发现职位</a>
            </li>
            <li>
                <h3>商务合作</h3>
                <a href="#">加盟方入口</a>
                <a href="#">媒体合作</a>
                <a href="#">友情链接</a>
            </li>
            <li>
                    <div  class="qrcode"><p>加微信关注我们</p></div>
            </li>

        </ul>
    </div>
    <div class="sp"></div>
    <div class="container pos-rlt foot-info">
        <p>@CopyRight 2014- 2015, 苏州华车网络科技有限公司   版权所有</p>
        <p> 工业信息化部信息备案：苏ICP备14017673号-1 </p>
    </div>
</footer>
<script src="{{asset('js/sea.js')}}"></script>
<script src="{{asset('js/config.js')}}"></script>
@yield('js')
</body>

</html>
