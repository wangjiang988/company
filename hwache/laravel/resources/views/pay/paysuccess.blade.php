@extends('_layout.base')
@section('css')
<link href="{{asset('themes/pay.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span> </a></label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
<div class="container m-t-86 pos-rlt" ms-controller="pay">
        <div class="wapper">
          
                
            <div class="form">
                <div class=" w900 hd">
                    <h3>交易结果信息：</h3>
                    
                </div>
                <div class="bank-wapper noborder">
                    <h4>恭喜你，您的支付已经完成！
                        
                    </h4>
                    <p>交易金额：499元 交易日期：2015-9-26</p>
                    <small><i>10</i> 秒后，页面会自动返回</small>
                    <div class="clear"></div>
                </div>
                 
            </div>
            
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/pay/pay-sucess", "module/common/common","bt"]);
    </script>
@endsection