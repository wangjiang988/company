@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.header2')
@endsection
@section('content')

    <div class="container m-t-86 psr">
        <div class="step psr">
            <ul>
                <li class="first step-cur">诚意预约<i></i></li>
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
                    <small>售方确认</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <div class="wapper has-min-step fs14">
            <p class="tac">
                @if($voucher)
                <span>可用代金券最大金额：￥{{$vouchers_max_price}}</span>
                @endif
                <span class="fl">>>应付诚意金：<b class="juhuang">￥499.00</b></span>
                <span class="fr tal">可用余额：￥{{number_format($user_account['avaliable_deposit'],2)}} <br>
                差额：<b class="juhuang">￥{{number_format(499-$user_account['avaliable_deposit']-$vouchers_max_price,2)}}</b> <br>
                （可用余额不足，请先充值）
                </span>
            </p>

            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <span class="tab ml50">线上支付</span>
            <div class="box-border pay-box">
                <form action="/member/payearnest/charge" method="post" name="payform" id="payform">
                    <input type="hidden" name="order_id" value="{{$order_id}}">
                    {{ csrf_field() }}
                    <div class="clear m-t-10"></div>

                    <ul class="price-ul average">
                        <li @click="selectPayMethod(0,$event)"><span class="selectpay"></span><img src="/webhtml/order/themes/images/OK.ZF_03.png"/></li>
                        <li @click="selectPayMethod(1,$event)"><span></span><img src="/webhtml/order/themes/images/F.C_05.gif"/></li>
                        <li @click="selectPayMethod(2,$event)"><span></span><img src="/webhtml/order/themes/images/F.C_07.gif"/></li>
                        <input v-model="payMethod" type="hidden" name="paymethod" :value="payMethod">
                        <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>
                    @if(1000-$charge_online_limit>499-$user_account['avaliable_deposit']-$vouchers_max_price)
                    <table class="ml50 mt20">
                        <tr>
                            <td width="80">温馨提示：</td>
                            <td>
                                单次购车，线上预充值额度￥1,000.00，您的剩余额度<span class="juhuang">￥{{number_format(1000-$charge_online_limit,2)}}</span>。
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                如未购车，线上预充值款可免费退款提现。
                            </td>
                        </tr>
                    </table>
                    <div class="m-t-10"></div>

                    <div class="box">

                        <div class="form-group psr pdi-control fs14 tac m-t-10 ml50 pl10">
                            <span>充值金额：  ￥</span>
                            <input @focus="initErrorStatus" v-model.number="price" :value="price" type="text" data-price-max="{{1000-$charge_online_limit}}" data-price-min="{{499-$user_account['avaliable_deposit']-$vouchers_max_price}}" name="payprice" placeholder="{{number_format(499-$user_account['avaliable_deposit']-$vouchers_max_price,2)}}~{{number_format(1000-$charge_online_limit,2)}}" class="form-control pay-control">
                            <span class="edit pay-edit"></span>
                            <a href="#" class="juhuang tdu">支付帮助</a>
                            <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==2}">您输入的不全是数字，不合规则，请重输！</p>
                            <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==3}">您输入的金额已超过限额，请重新输入！</p>
                            <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==4}">您输入的金额不够支付诚意金，请重新输入！</p>
                        </div>
                        <div class="m-t-10"></div>

                        <div class="m-t-10"></div>
                        <div class="clear"></div>

                        <div class="clear"></div>

                    </div>
                    <div class="m-t-10 tac">
                        <p class="hide error red fs14 showerror" :class="{show:errorStatus==0}">支付金额不能为空哦～</p>
                        <a @click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  btn-auto mt10">确认向本账户{{$phone}}充值</a>
                        <a href="/" class="ml50 tdu juhuang fs14">返回</a>
                    </div>
                    @else:
                    <table class="ml50 mt20">
                        <tr>
                            <td width="80">温馨提示：</td>
                            <td>
                                单次购车，线上预充值额度￥1,000.00，您的剩余额度<span class="juhuang">￥{{number_format(1000-$charge_online_limit,2)}}</span>。
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                线上预充值剩余额度不够啦！但您可使用银行转账方式充值哦～
                            </td>
                        </tr>
                    </table>
                    <div class="m-t-10 tac">
                        <a href="/member/recharge" class="btn fs16  btn-danger mt10">> > 去银行转账</a>
                        <a href="/" class="ml50 tdu juhuang fs14">返回</a>
                    </div>
                    @endif
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>


            </div>

        </div>
    </div>

@endsection
@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-sincerity-gold-online-payment",  "/js/module/common/common"],function(v,u,c){
            u.initPrice({{1000-$charge_online_limit}},{{499-$user_account['avaliable_deposit']-$vouchers_max_price}})
        })
    </script>
@endsection
