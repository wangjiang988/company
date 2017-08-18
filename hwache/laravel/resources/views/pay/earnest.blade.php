@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet"/>
@endsection
@section('nav')
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      @yield('nav')
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
          <small class="juhuang">付诚意金</small>
          <i></i>
          <small class="">卖方确认</small>
        </div>
      </div>
    </div>
  </div>

  <div class="container pos-rlt content" ms-controller="pay">
    <!--//所剩余额可以支付  -->
    @if($userMoney)

      <div class="wapper has-min-step">
        <p class="fs14">>>应付诚意金：￥{{ $earnest }}</p>
        <div class="box-border pay-box">
          <form action="" method="post" name="payform">
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="pay-form">
              <p class="fs14">
                <span class="pull-left">可用余额：￥{{$userMoney}}</span>
                <span class="use-price">使用</span>
              </p>
              <p class="fs14 m-t-10">手机号：{{ $mobile }}</p>
              <p class="fs14 m-t-10">
                <span class="pull-left">手机验证码：</span>
                <input type="text" name="phonecode" placeholder=""
                       class="form-control pay-control pull-left pay-phone-code">
                <a href="#" ms-on-click="SendCode({{$mobile}})" data-s="重新获取" data-send="重新获取($1)"
                   class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s bt sure btn-code">获取验证码</a>
              <div class="clearfix"></div>
              <p class="tac hide inputerror juhuang m-t-10 fs14">*输入的验证码不正确</p>
              </p>

            </div>
            <div class="m-t-10"></div>
            <p class="fs14 " style="margin-left:260px;color:#cccccc;">为了保证支付的安全，需要输入手机验证码进行验证，若是您手机号已更换，请先
              <a href="/user/memberSafe/phone_change" class="juhuang tdu">修改</a>。
            </p>
            <div class="m-t-10"></div>
            <p class="tac">
              <a href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s" ms-on-click="surepay">确认支付</a>
            </p>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
          </form>
        </div>
      </div>
      @else
        <!--//余额不足 请充值  -->
      <div class="wapper has-min-step">
        <ul class="price-ul">
          <li><span>>>应付诚意金：￥499</span></li>
          <li>可用余额：￥{{$money_in}}<span class="juhuang">（查看）</span></li>
          <li class="last">可用余额：<span class="juhuang">￥{{$money_in}}</span><label>（余额不足，请充值到可用余额）</label></li>
          <div class="clear"></div>
        </ul>
        <span href="#" class="tab ml50">线上支付</span>
        <div class="box-border pay-box">
          <form action="" method="post" name="payform">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <ul class="price-ul average">
              <li ms-on-click="selectPayMethod"><span></span><img src="/themes/images/pay/F.C_03.gif"/>
              </li>
              <li ms-on-click="selectPayMethod"><span></span><img src="/themes/images/pay/F.C_05.gif"/>
              </li>
              <li ms-on-click="selectPayMethod"><span></span><img src="/themes/images/pay/F.C_07.gif"/>
              </li>
              <input type="hidden" name="paymethod">
              <div class="clear"></div>
            </ul>
            <div class="m-t-10"></div>

            <div class="box">
              <p class="fs14 pwenxin">
                温馨提醒：单次购车，线上预充值额度￥1,000.00，您的剩余额度
                <span class="juhuang">￥1,000.00</span>。
              </p>
              <p class="fs14 pnobuy">如未购车，线上预充值款可申请退款提现，且免手续费。</p>
              <div class="form-group psr pdi-control fs14 tac m-t-10">
                <span>充值金额：￥</span>
                <input type="text" data-price-max="1000" name="payprice" placeholder=""
                       class="form-control pay-control">
                <span class="edit pay-edit"></span>
                <a href="#" class="juhuang tdu">支付帮助</a>
                <p class="hide inputerror juhuang m-t-10">您输入的不是数字，不合规则，请重新输入！</p>
                <p class="hide inputerror juhuang m-t-10">您输入的金额已超过限额，请重新输入！</p>
              </div>
              <div class="m-t-10 tac">
                <p class="hide error showerror">请选择支付方式</p>
                <a ms-on-click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  ">确认充值</a>
              </div>
            </div>

            <div class="box">
              <h2>1.若是线上预充值剩余额度 &lt; 应付诚意金-可用余额，第一次显示：此时剩余的额度替换为：￥499.00</h2>
              <p class="fs14 pwenxin">
                温馨提示：单次购车，线上预充值额度￥1,000.00，您的剩余额度
                <span class="juhuang">￥50.00</span>。
              </p>
              <p class="fs14 pnobuy">平台为您开通诚意金专用线上充值额度￥499.00，该额度不可提现，若三个自然日未购车将</p>
              <p class="fs14 pnobuy">自动原路退回。</p>
              <div class="form-group psr pdi-control fs14 tac m-t-10">
                <span>充值金额：￥</span>
                <input type="text" data-price-max="1000" name="payprice" placeholder=""
                       class="form-control pay-control">
                <span class="edit pay-edit"></span>
                <a href="#" class="juhuang tdu">支付帮助</a>
                <p class="hide inputerror juhuang m-t-10">您输入的不是数字，不合规则，请重新输入！</p>
                <p class="hide inputerror juhuang m-t-10">您输入的金额已超过限额，请重新输入！</p>
              </div>
              <div class="m-t-10 tac">
                <p class="hide error showerror">请选择支付方式</p>
                <a ms-on-click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  ">确认充值</a>
              </div>
            </div>

            <div class="box">
              <h2>1.1当开通过线上充值额度，但未使用完</h2>
              <p class="fs14 pwenxin">温馨提示：单次购车，诚意金专用线上充值额度：￥499.00，您的剩余额度
                <span class="juhuang">￥100.00</span>。
              </p>
              <p class="fs14 pnobuy">若三个自然日未购车将自动原路退回。</p>
              <div class="form-group psr pdi-control fs14 tac m-t-10">
                <span>充值金额：￥</span>
                <input type="text" data-price-max="1000" name="payprice" placeholder=""
                       class="form-control pay-control">
                <span class="edit pay-edit"></span>
                <a href="#" class="juhuang tdu">支付帮助</a>
                <p class="hide inputerror juhuang m-t-10">您输入的不是数字，不合规则，请重新输入！</p>
                <p class="hide inputerror juhuang m-t-10">您输入的金额已超过限额，请重新输入！</p>
              </div>
              <div class="m-t-10 tac">
                <p class="hide error showerror">请选择支付方式</p>
                <a ms-on-click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  ">确认充值</a>
              </div>
            </div>

            <div class="box">
              <h2>2.若是线上预充值剩余额度 &lt; 应付诚意金-可用余额，不是第一次显示：</h2>
              <p class="fs14 pwenxin">温馨提示：单次购车，线上预充值额度￥1000.00，您的剩余额度<span class="juhuang">￥50.00</span>。
              </p>
              <p class="fs14 pnobuy">请使用银行转账方式充值到您的可用余额。</p>

              <div class="m-t-10 tac">
                <a ms-on-click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  ">去充值</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endif
  </div>
@endsection
@section('js')
  <script type="text/javascript">
    seajs.use(["module/pay/zhifu", "module/common/common", "bt"]);
  </script>
@endsection
