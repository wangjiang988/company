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
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span></a> </label>
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
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
			<div class="min-step">
                <div class="m-content" style=" left: 187px;">
                    <small class="juhuang">正在支付</small>
                    <i></i>
                    <small>查收确认</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
</div>
    <div class="container pos-rlt content" ms-controller="item">
	    
	    <!--//新的页面  -->
	    <div class="wapper has-min-step">

            <p>尊敬的客户：</p>
            <p class="ti">很高兴有好消息告诉您：经销商已完成车源的核对确认。您的心仪座驾即将揭开神秘的面纱，早点把Ta迎回家吧！</p>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：{{$order->order_num}}</td>
                    <td width="50%">
                        <div class="psr fs14">
                          订单时间：{{$order->created_at}}
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
                <li class="pdi-color"><p class="fs14">{{$body_color}}（{{$interior_color}}）</p></li>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{URL('orderoverview')}}/{{$order->order_num}}" target="_blank" class="juhuang tdu ">查看订单总详情</a></p>

            <hr class="dashed">
			@if($money_in>=session('order.money'))
	    	<form action="" method="post" name="form1">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}">
   			 <input type="hidden" name="pay_deposit"  value="yes">
   			  <input type="hidden" name="id"  value="{{$id}}">
            <table class="tbl fl" style="width: 80%;"> 
                <tr>
                    <td>
                        <p class="tac fs14"><b>买车担保金余款</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币{{ session('order.money') }}元（买车担保金￥{{ session('order.money') }} — 已付诚意金￥499.00元）</p>
                    </td>
                    
                </tr>
                <tr>
                    <td>
                        <p class="tac fs14"><b>您账户的可用余额</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币{{$money_in}}元<a href="#" class="juhuang">（查看）</a></p>
                    </td>
                </tr>
            </table>
            <div class="clear"></div>
            <div class="fs14">
                <span class="fl">请在本日内确认使用该余额支付买车担保金余款，如超时未确认平台将自动提交确认。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3 ">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
	    	
            <p class="center">
            	<input type="submit" class="btn btn-s-md btn-danger fs16" value="确认" />
            </p>
			</form>
			@else
            <table class="tbl fl" style="width: 80%;"> 
                <tr>
                    <td>
                        <p class="tac fs14"><b>买车担保金余款</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币{{ session('order.money') }}元（买车担保金￥{{ session('order.money') }} — 已付诚意金￥499.00元）</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tac fs14"><b>您账户的可用余额</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币{{$money_in}}元<a href="#" class="juhuang">（查看）</a></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tac fs14"><b>待付金额</b></p>
                    </td>
                    <td>
                        <p class="tal fs14"><b>人民币{{ session('order.money')-$money_in }}元</b>（已扣除可用账户余额）</p>
                    </td>
                </tr>
            </table>
            <div class="clear"></div>
            <div class="fs14">
                <p class="lh22"><span class="">一步到位<b>全额支付</b>，您可采用<b class="tdu">银行转账</b>方式，包括通过网上银行、手机银行或银行柜面向华车平台直接转账，您须在24小时内提交有效的银行</span></p>
                <span class="fl">汇款凭证。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="fs14">
                <p class="lh22"><span class=""><b class="tdu">线上支付</b>方式，因第三方支付的支付限额可能受限影响支付成功，可分多笔支付。您在当日付完第一笔后，支付时限自动延长到第三个自然日</span></p>
                <span class="fl">的24点，您可从容完成买车担保金余额支付。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <p class="fs14 lh22">请选择您的支付方式，并在上方时限内完成支付。超时未足额支付，根据华车平台规则将被判定违约：<b>订单终止，且诚意金赔偿售方</b>（已付的部分</p>
            <p class="fs14 lh22">买车担保金余额可按规则退还）！</p>
            <p class="tac">
                <a href="javascript:;" class="btn btn-s-md btn-danger sure fs16">银行转账</a>
                <a href="javascript:;" class="btn btn-s-md btn-danger sure ml20 fs16">线上支付</a>
            </p>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            @endif
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"],function(){
            $(".jishi").CountDown({
              startTime:'{{$starttime}}',
              endTime :'{{$endtime}}',
              timekeeping:'countdown',
              callback:function(){
                //$("form[name='form1']").submit()
              }
          })
        });
    </script>
@endsection