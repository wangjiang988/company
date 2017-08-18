@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('/webhtml/user/themes/user.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.header2')
@endsection
@section('content')

    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p><b class="blue">充值</b></p>
            <div class="box box-border top-border  fs14 p20">
                <p>可用余额：￥{{$user_account['avaliable_deposit']}}</p>
                <a href="{{ route('pay.online')}}"><span class="tab ml50">线上支付</span></a>
                <a href="{{ route('pay.recharge')}}"><span class="tab ml50 tab-empty">银行转账</span></a>
                <div class="box-border pay-box">
                        <div class="clear m-t-10"></div>

                        <ul class="price-ul average">
                            <li @click="selectPayMethod(0,$event)"><span class="selectpay"></span><img src="/webhtml/order/themes/images/OK.ZF_03.png"/></li>
                            <li @click="selectPayMethod(1,$event)"><span></span><img src="/webhtml/order/themes/images/F.C_05.gif"/></li>
                            <li @click="selectPayMethod(2,$event)"><span></span><img src="/webhtml/order/themes/images/F.C_07.gif"/></li>
                            <div class="clear"></div>
                        </ul>
                        <div class="clear"></div>
                        @if((1000-$charge_online_limit)>0 && $charge_online_limit<=1000)
                        <form  method="post" name="payform" id="payform">
                            <input v-model="payMethod" type="hidden" name="paymethod" :value="payMethod">
                            {{ csrf_field() }}
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
                            <tr>
                                <td></td>
                                <td>
                                    如需充值更多，建议使用银行转账方式。
                                </td>
                            </tr>
                        </table>
                        <div class="m-t-10"></div>

                        <div class="box">

                            <div class="form-group psr pdi-control fs14 tac m-t-10 ml50 pl10">
                                <span>充值金额：  ￥</span>
                                <input @focus="initErrorStatus" v-model.number="price" :value="price" type="text" data-price-max="{{1000-$charge_online_limit}}" name="payprice" placeholder="0~{{number_format(1000-$charge_online_limit,2)}}" class="form-control pay-control">
                                <span class="edit pay-edit-left"></span>
                                <a href="#" class="juhuang tdu">支付帮助</a>
                                <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==2}">您输入的不全是数字，不合规则，请重输！</p>
                                <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==3}">您输入的金额已超过限额，请重新输入！</p>
                            </div>
                            <div class="m-t-10"></div>

                            <div class="m-t-10"></div>
                            <div class="clear"></div>

                            <div class="clear"></div>

                        </div>



                        <div class="m-t-10 tac">
                            <p class="hide error red fs14 showerror" :class="{show:errorStatus==0}">充值金额不能为空哦～</p>
                            <a @click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  btn-auto mt10">确认向本账户{{$phone}}充值</a>
                            <a href="javascript:history.go(-1);" class="ml50 tdu juhuang fs14">返回</a>
                        </div>
                        </form>
                        @else
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
                                    您可使用银行转账方式充值哦～
                                </td>
                            </tr>
                        </table>

                        <div class="m-t-10 tac">
                            <a href="/member/recharge" class="btn fs16  btn-danger mt10">> > 去银行转账</a>
                            <a href="javascript:window.history.go(-1);" class="ml50 tdu juhuang fs14">返回</a>
                        </div>
                        @endif
                        <div class="m-t-10"></div>
                        <div class="m-t-10"></div>
                        <div class="m-t-10"></div>


                </div>


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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-available-balance-top-up-online-payment",  "/js/module/common/common"],function(v,u,c){
            u.initMaxPrice({{1000-$charge_online_limit}})
        })
    </script>
@endsection
