@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.header2')
@endsection
@section('content')

    <div class="container m-t-86 psr">
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
                <div class="m-content" style=" left: 187px;top:64px">
                    <small class="juhuang">正在支付</small>
                    <i></i>
                    <small>查收确认</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <div class="wapper has-min-step">
            <ul class="danbaojin-ul">
                <li class="wp33">订单号：{{$order['order_sn']}}</li>
                <li class="wp33">买车担保金未入账金额：<span class="juhuang">￥{{number_format($order['sponsion_price']-$order['earnest_price'],2)}}</span></li>
                <li class="wp33 text-right">可用余额：￥{{number_format($account['avaliable_deposit'],2)}}</li>
            </ul>
            <div class="m-t-10 clear"></div>
            <div class="box-border">
                <form action="" method="post" id="payDeposit">
                    {{ csrf_field() }}
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="pay-form wp46">
                        <p class="fs14">
                            <span class="pull-left">使用可用余额：￥{{number_format($account['avaliable_deposit'],2)}}</span>
                        </p>
                        <p class="fs14 m-t-10 clear">手机号：{{substr_replace($phone,'****',3,4)}}</p>
                        <phone-code ref="phonecode" @valite-code="getCode" phone="{{$phone}}" sendurl="{{ route('member.sendSms')}}" sendtype="78730074" sn="{{$order['order_sn']}}" iscode="1" max="6"></phone-code>
                        <div class="clearfix"></div>
                        <p class="tac hide inputerror juhuang m-t-10 fs14">*输入的验证码不正确</p>
                        <p></p>

                    </div>
                    <p class="fs14 ml260 mt20" >支付时限：{{$pay_time_limit}}</p>
                    <div class="fs14 tac ml260"  >
                        <span class="fs14 fl mt10 countdown">剩余时间：</span>
                        <div class="time m-t-10 fl inline-block">
                            <div class="jishi jishi2 countdown inline-block-noi">
                                <span>0</span>
                                <span>0</span>
                                <span class="fuhao">:</span>
                                <span>0</span>
                                <span>0</span>
                                <span class="fuhao">:</span>
                                <span>0</span>
                                <span>0</span>
                            </div>
                            <span class="text inline-block-noi red hide timeout-text">已超时:</span>
                            <div class="jishi jishi2  jishi-out timeout hide inline-block-noi">
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
                    <p class="red tac fs14 hide" :class="{show:codeStatus==2}">请输入正确的验证码～</p>
                    <p class="tac mt20">
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-auto" @click="surePay">确认支付买车担保金</a>
                        <a href="javascript:;" class="juhuang tdu ml50">返回</a>
                    </p>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                </form>

            </div>

        </div>
    </div>

@endsection
@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')

    <script type="text/javascript">
            seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-balance-buying",  "/webhtml/order/js/module/common/common"],function(v,u,c){
        u.init('{{date("Y-m-d H:i:s")}}','{{$pay_time_limit}}',function(){
            })
        })
    </script>
@endsection
