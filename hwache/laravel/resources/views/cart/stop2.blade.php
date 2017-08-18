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
                    <small class="juhuang">开始预约</small>
                    <i></i>
                    <small>反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">
<?php if ($stoptime['timeout']==1): ?>
    因经销商未在规定时间内发出交车通知，很抱歉本订单已终止。</p>
  <?php else: ?>  
    <p class="ti">因经销商提出了终止订单要求，很抱歉本订单已终止。</p>
<?php endif ?>
            
            
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：</b>{{$order_num}}</p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b>{{ddate($created_at)}}</p></li>
                <li class="pdi-more">
                    <div class="psr fs14">
                      <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                         <b>更多</b>
                      </span>
                      <p class="tm tm-long" style="display: none;width: 400px;">
                        <span>诚意金确认进入加信宝时间：2015-10-28  10：45：04</span>
                        <span>...</span>
                        <span>经销商提议终止订单时间：2015-10-28  10：45：04</span>
                        <span>经销商交车通知超时自动终止订单时间：2015-10-28  10：45：04</span>
                      </p>
                    </div>


                </li>
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
            <ul class="pdi-order-ul border">
                <li class="pdi-name">
                    <p class="fs14">{{$brand[0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$brand[1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$brand[2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$bodycolor}}({{$interior_color}})</p></li>
                <div class="clear"></div>
            </ul>


            
            <table class="tbl fl" style="width: 60%;">
                <tr>
                    <td>
                        <p class="tal fs14">买车担保金人民币12,345.00元（已退还至您的可用账户）</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tal fs14">获得的歉意金补偿人民币499.00元（已进入您的可用账户）</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tal fs14">获得的买车担保金利息补偿人民币123.00元（已进入您的可用账户）</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tal fs14">获得的歉意金2补偿人民币499.00元（已进入您的可用账户）</p>
                    </td>
                </tr>  
            </table>
            <div class="clear"></div>
            <p class="fs14"><b>可用账户余额：</b>人民币19,268.00元<a href="#" class="juhuang">（查看）</a></p>
            <p class="fs14">您可在华车平台继续购车时使用上述余额，如不买车，可在《我的华车》中申请退款或提现（按银行规定办理）。</p>


            <p class="tal">
                <a href="javascript:;" class="btn btn-s-md btn-danger sure">再选辆车</a>
            </p>


            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>
@endsection
@section('js')
   
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"]);
    </script>
@endsection