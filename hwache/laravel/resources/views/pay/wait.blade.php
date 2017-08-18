@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
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
<div class="clear"></div>
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content">
                    <small>选择产品</small>
                    <i></i>
                    <small>付诚意金</small>
                    <i></i>
                    <small class="juhuang" class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p>华车平台<a href="#" class="juhuang tdu" style="padding-left:5px"><img src="{{asset('themes/images/item/jxb.gif')}}"></a>于{{$earnest_time}}收到您支付的诚意金人民币{{$sysmoney['chengyijin']}}元，
            <?php if (!empty($wenjian)): ?>
                您同时提交的本人上牌所需特别文件要求为：
                <?php 
                $str='';
                foreach ($wenjian as $key => $value){
                        $str.=$value.',';
                    }
                    echo substr($str,0, -1).'。';
                 ?>

            <?php else: ?>
                您的购车之旅正式启航了！
            <?php endif ?>
            </p>
            <?php if ($order->first_shangpai==1): ?>
                <p class="ti">上牌服务约定：接受安排。本次购车的上牌服务将由经销商代办，上牌服务费约定人民币{{$shangpai_price}}元，您只需要配合提供
常规上牌文件。</p>
            <?php endif ?>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：{{$order_num}}</td>
                    <td width="50%">
                        <div class="psr fs14">
                          订单时间：{{ddate($order->created_at)}}
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
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <ul class="pdi-order-ul border ">
                <li class="pdi-name">
                    <p class="fs14">{{$brand[0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$brand[1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$brand[2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$bodycolor}}({{$interior_color}})</p></li>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{URL('orderoverview')}}/{{$order_num}}" class="juhuang tdu " target="_blank">查看订单总详情</a></p>

            <hr class="dashed">
            <p class="fs14 ti"> 经销商正在确认，按平台规则，将在24小时内向您反馈结果。</p>
            
            <div class="time">
                <div class="jishi">
                    <span>0</span>
                    <span>0</span>
                    <span class="fuhao">:</span>
                    <span>0</span>
                    <span>0</span>
                </div>
            </div>
            <?php if ($order->first_shangpai<>1): ?>
                <p class="fs14 ti">如经销商反馈无法办理上述文件，或者您无法接受反馈的文件办理时间或办理费用，您可选择终止订单，但您只能退还诚意金，而无法获得歉意金补偿。</p>
            <?php endif ?>
            <p class="fs14 ti">如经销商反馈提出其他修改内容，华车平台<a href="#" class="juhuang tdu" style="padding-left:5px" ><img src="{{asset('themes/images/item/jxb.gif')}}" /></a>将无条件向您赔偿歉意金人民币{{$sysmoney['chengyijin']}}元,您可选择终止订单且退还全部诚意金，也可选择接受修 改的新条件继续订单，所获得歉意金补偿将可抵扣买车担保金余款。</p>
            <p class="fs14 ti">       订单继续执行的下一个环节“付担保金”，由您开始支付买车担保金余款人民币{{$money-$sysmoney['chengyijin']}}元（买车担保金￥{{$money}}  —  
已付诚意金￥{{$sysmoney['chengyijin']}}）。目前我们提供<a href="#" class="juhuang tdu">线上支付</a>和<a href="#" class="juhuang tdu">银行转账</a>两种支付方式供您选择。银行转账方式，您须在24小时内提交有效的银行汇款凭证。
线上支付方式，因可能对支付限额有一定限制， 您在当日最大限额内完成首笔付款后，如买车担保金余款仍未付完，可申请分日支付，在随后几
日内全部付完即可。</p>
         
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"],function(){
            $(".jishi").CountDown({
                startTime:'{{$starttime}}',
                endTime :'{{$endtime}}',
                timekeeping:'countdown'
            })
        });
    </script>
@endsection