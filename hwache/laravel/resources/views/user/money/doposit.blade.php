@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet"/>
@endsection

@section('nav')@include('_layout.nav')@endsection

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

  <div class="container pos-rlt content" ms-controller="pay">
    <!--//所剩余额可以支付  -->
    @if($useDoposit)
      <div class="wapper has-min-step">
        <ul class="danbaojin-ul">
          <li>订单号：{{ $orderNum }}</li>
          <li>买车担保金余额应付金额：￥{{ $doposit }}</li>
          <li class="last"><a href="{{ route('orderoverview', [$orderNum]) }}" class="juhuang tud">查看订单</a></li>
        </ul>
        <form action="{{ route('user.money.postDoposit') }}" method="post" name="payform">
          {{ csrf_field() }}
          <input type="hidden" name="order_num" value="{{ $orderNum }}">
          <div class="m-t-10"></div>
          <div class="m-t-10"></div>
          <div class="box-border">
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="pay-form">
              <p class="fs14">
                <span class="pull-left">可用余额：￥{{ $userMoney }}</span>
                <span class="use-price">使用</span>
              </p>
              <p class="fs14 m-t-10">手机号：{{ $mobile }}</p>
              <p class="fs14 m-t-10">
                <span class="pull-left">手机验证码：</span>
                <input type="text" name="phonecode" placeholder="" class="form-control pay-control pull-left pay-phone-code">
                <a href="#" ms-on-click="SendCode({{ $mobile }})" data-s="重新获取" data-send="重新获取($1)" class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s bt sure btn-code">获取验证码</a>
              </p>
              <div class="clearfix"></div>
              <p class="tac hide inputerror juhuang m-t-10 fs14">*输入的验证码不正确</p>
              <p></p>

            </div>
            <p class="fs14 ml260" style="color:#ccc;">为了保证支付的安全，需要输入手机验证码进行验证，若是您手机号已更换，请先<a href="/user/memberSafe/phone_change" class="juhuang tdu">修改</a>。</p>
            @if($firstPay)
            <p class="fs14 ml260" >第一次支付时限：2016-04-20  24:00:00</p>
            <p class="fs14 tac ml260"  >
              <span class="fs14 fl mt10">剩余时间：</span>
              <div class="time fl">
                <div class="jishi wp100" id="countdown">
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
            </p>
            @endif
            <p class="tac"><a href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s" ms-on-click="surepay">确认支付</a></p>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
          </div>
        </form>
      </div>
      @else
      <!--//余额不足 请充值  -->
      <div class="wapper has-min-step">
        <ul class="order-simp-ul">
          <li class="first-1">订单号：{{ $orderNum }}</li>
          <li class="second-1"><span class="pull-left">买车担保金余额应付金额：￥{{ $doposit }}</span> <a href="{{ route('orderoverview', [$orderNum]) }}" class="juhuang tdu pull-right">查看订单</a><div class="clear"></div></li>
          <div class="clear"></div>
        </ul>
        <div class="clear m-t-10"></div>
        <ul class="order-simp-ul psr">
          <li class="first-1">已付金额：
              <span class="sj" style="width: 90px;text-align: left;"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
              ￥{{ session('doposit.detail.paidMoney') }}
              </span>
            <div class="tm detail tm-fix">
              <table class="tbl2">
                @foreach($dopositList as $k => $v)
                <tr>
                  <td width="150">第{{ $k+1 }}次支付：￥{{ $v->money }} </td>
                  <td width="100">{{ config("pay.{$v->pay_type}.title") }}</td>
                  <td>{{ $v->pay_time }}</td>
                </tr>
                @endforeach
              </table>
              <span>  </span>
            </div>
          </li>
          <li class="second-1">
            买车担保金余额待付金额：<span class="juhuang">￥{{ session('doposit.detail.surplusMoney') }}</span> （应付金额-已付金额）
          </li>
          <div class="clear"></div>
        </ul>
        <div class="clear m-t-10"></div>
        <hr class="dashed-2">
        <span class="tab ml50">线上支付</span>
        <a href="#"><span class="tab ml50 tab-empty">银行转账</span></a>
        <div class="box-border pay-box">
          <form action="{{ route('user.money.postDoposit') }}" method="post" name="payform">
            {{ csrf_field() }}
            <input type="hidden" name="order_num" value="{{ $orderNum }}">
            <div class="clear m-t-10"></div>
            <ul class="price-ul average">
              @foreach($payType as $k => $v)<li ms-on-click="selectPayMethod('{{ $k }}')"><span></span><img src="{{ asset("themes/images/pay/{$k}.gif") }}"/></li>
              @endforeach
              <input type="hidden" name="payType" id="payType">
              <div class="clear"></div>
            </ul>
            <div class="m-t-10"></div>

            <div class="box">
              <div class="form-group psr pdi-control fs14 tac m-t-10">
                <span>输入支付金额：￥</span>
                <input type="text" data-price-max="{{ session('doposit.detail.surplusMoney') }}" id="payprice" name="payprice" placeholder="" class="form-control pay-control">
                <span class="edit pay-edit"></span>
                <a href="#" class="juhuang tdu">支付帮助</a>
                <p class="fs14 juhuang mt5">请注意单笔/单日支付的限额，可分多次支付</p>
                <p class="hide inputerror juhuang m-t-10">您输入的不是数字，不合规则，请重新输入！</p>
                <p class="hide inputerror juhuang m-t-10"> 输入金额不得高于待付金额，请重新输入！</p>
                <p class="hide inputerror juhuang m-t-10"> 输入金额不得低于最低金额，请重新输入！</p>
              </div>
              <div class="m-t-10"></div>
              <p class="fs14 ml260" >余额最晚支付时限：{{ $payEndTime }}</p>
              <div class="m-t-10"></div>

              @include('user.money.timeout')
            </div>

            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>

          </form>
        </div>

      </div>
    @endif
  </div>
@endsection
@section('js')
  <script type="text/javascript">
    var config = {
      sendCodeUrl : '{{ route('user.sms.getcode') }}',
      priceScope: '1~{{ session('doposit.detail.surplusMoney') }}'
    };
    seajs.use(["module/pay/zhifu", "module/common/common", "bt"],function(){
      @if($timeOut)
      $("#timeout").CountDown({
        startTime:'{{ $nowTime }}',
        endTime :'{{ $payEndTime }}',
        timekeeping:'timeout'
      });
      @else
      $("#countdown").CountDown({
        startTime:'{{ $nowTime }}',
        endTime :'{{ $payEndTime }}',
        timekeeping:'countdown'
      });
      @endif
    });
  </script>
@endsection
