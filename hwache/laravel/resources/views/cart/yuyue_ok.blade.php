@extends('_layout.base')
@section('css')
  
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
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
    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li class="step-cur">预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content pdi">
                    <small>等待预约</small>
                    <i></i>
                    <small  class="juhuang">反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div style="overflow:visible;" class="container pos-rlt content r-pdi" ms-controller="item">

        <div style="overflow:visible;" class="wapper has-min-step">
            <p><b>尊敬的客户：</b></p>
            <p class="ti">您与经销商已就交车事宜全面达成共识，感谢您的大力支持！正在为您安排服务专员，敬请期待令人神往的尊驾一露尊容！</p>
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：</b>{{$order_num}}</p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b>{{ddate($order->created_at)}}</p></li>
                <li class="pdi-more">
                    <div class="psr fs14">
                      <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                         <b>更多</b>
                      </span>
                      <p class="tm tm-long" style="display: none;">
                        @if(count($cart_log)>0)     
								@foreach($cart_log as $k =>$v )
								<span>{{$v['msg_time']}}：{{$v['time']}}</span>
								@endforeach
						@endif
                        
                      </p>
                    </div>


                </li>
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
            <ul class="pdi-order-ul border">
                <li class="pdi-name">
                    <p class="fs14">{{$bj['brand'][0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$bj['brand'][1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$bj['brand'][2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$bj['body_color']}}</p></li>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang tdu " target="_blank">查看订单总详情</a></p>
            

        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-pdi", "module/common/common", "bt"]);
    </script>
@endsection