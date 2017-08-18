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

            <div class="tac">
                <div class="success-title wauto mauto inline-block mt50">
                    <span class="icon-large icon-success-large fl"></span>
                    <span class="inline-block ml20 fl">恭喜您成功提交！</span>
                    <div class="clear"></div>
                </div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="wauto inline-block mauto fs14">
                    <p>
                        <span class="">本次提交金额：￥{{$money}}</span><span class="ml50">提交时间：{{$time}}</span>
                    </p>
                </div>
                <p class="tac mt50"><a href="{{route('cart.editcar',['id'=>$id])}}" class="btn btn-s-md btn-danger fs16 sure">返回查看订单</a></p>
                <p class="tac fs14"><span class="juhuang">@{{countDownNum}}</span>秒后自动跳转订单页面</p>
            </div>


        </div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
    </div>


@endsection
@section('footer')
    @include('HomeV2._layout.footer')
    @include('HomeV2._layout.login')
@endsection
@section('js')

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-top-up-success2",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection
