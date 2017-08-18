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
                <li class="">订单号：{{$order['order_sn']}}</li>
                <li class=" text-right pull-right"><span class="">买车担保金未入账金额：<span class="juhuang">￥{{number_format($non_payment,2) }}</span></span> </li>
            </ul>

            <div class="clear m-t-10"></div>
            <hr class="dashed-2">
            <a href="{{route("paydeposit.online",['id'=>$order['id']])}}"><span class="tab ml50">线上支付</span></a>
            <a href="{{route("paydeposit.offline",['id'=>$order['id']])}}"><span class="tab ml50 tab-empty">银行转账</span></a>
            <div class="box-border pay-box">
                <form method="post" id="payForm">
                    <div class="clear m-t-10"></div>

                    <ul class="price-ul average">
                        <li @click="selectPayMethod(0,$event)"><span class="selectpay"></span><img src="/webhtml/order/themes/images/OK.ZF_03.png"/></li>
                        <li @click="selectPayMethod(1,$event)"><span></span><img src="/webhtml/order/themes/images/F.C_05.gif"/></li>
                        <li @click="selectPayMethod(2,$event)"><span></span><img src="/webhtml/order/themes/images/F.C_07.gif"/></li>
                        <input v-model="payMethod" type="hidden" name="paymethod" :value="payMethod">
                        <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>
                    <p class="tac fs14 mt50">温馨提示：支付机构可能对单笔/单日支付额度有限制。</p>
                    <p class="tac fs14 mt10">如超出限额，建议改用银行转账完成支付。</p>
                    <div class="m-t-10"></div>


                    <div class="box">
                        {{ csrf_field() }}
                        <div class="form-group psr pdi-control fs14 tac m-t-10 ml50 pl10">
                            <span>输入支付金额：￥</span>
                            <input @focus="initErrorStatus" v-model.number="price" :value="price" type="text" data-price-max="{{$non_payment}}" name="payprice" placeholder="0~{{number_format($non_payment,2) }}" class="form-control pay-control">
                            <span class="edit pay-edit"></span>
                            <a href="#" class="juhuang tdu">支付帮助</a>
                            <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==2}">您输入的不全是数字，不合规则，请重输！</p>
                            <p class="hide inputerror juhuang m-t-10 red fs14" :class="{show:errorStatus==3}">您输入的金额已超过订单所需金额，请重新输入！</p>
                        </div>
                        <div class="m-t-10"></div>
                        <p class="fs14 ml260 ml5" >支付时限：{{$pay_time_limit}}</p>
                        <div class="m-t-10"></div>
                        <div class="clear"></div>

                        <div class="clear"></div>

                    </div>


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
                            <span class="text inline-block-noi red hide timeout-text ml10">已超时：</span>
                            <div class="jishi jishi2  jishi-out timeout hide inline-block-noi ml5">
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

                    <div class="m-t-10 tac">
                        <p class="hide error red fs14 showerror" :class="{show:errorStatus==0}">支付金额不能为空哦～</p>
                        <a @click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  btn-auto mt10">确认支付订单{{$order['order_sn']}}买车担保金</a>
                        <a href="javascript:;" class="ml50 tdu juhuang fs14">返回</a>
                    </div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
            </form>

            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>



        </div>
    </div>
    </div>

@endsection
@section('footer')
    @include('HomeV2._layout.footer')
    @include('HomeV2._layout.login')
@endsection
@section('js')

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-online-payment",  "/webhtml/order/js/module/common/common"],function(v,u,c){
            u.init('{{date("Y-m-d H:i:s")}}','{{$pay_time_limit}}',function(){

            })

            u.initMaxPrice({{$non_payment}})
        })
    </script>
@endsection
