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
                <li class="first step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content" style="top: 64px;">
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
            <div class="tac">
                <div class="success-title wauto mauto inline-block mt50">
                    <span class="icon-large icon-success-large fl"></span>
                    <span class="inline-block ml20 fl weight juhuang fs16">恭喜您充值成功！</span>
                    <div class="clear"></div>
                </div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="wauto inline-block mauto fs14">
                    <p>
                        <span class="">本次充值金额：￥{{number_format($money,2)}}</span><span class="ml50">提交时间：{{$time}}</span>
                    </p>

                </div>
                <p class="tac mt50"><a href="/member/pay/earnest/{{$id}}" id="redirect-url" class="btn btn-s-md btn-danger fs16 sure">立即支付诚意金</a></p>
                <p class="tac fs14" v-cloak><span class="juhuang">@{{countDownNum}}</span>秒后，自动前往支付诚意金</p>
            </div>


        </div>
    </div>
@endsection
@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-top-up-success",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection
