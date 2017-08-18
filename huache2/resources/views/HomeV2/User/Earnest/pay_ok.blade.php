@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.header2')
@endsection
@section('content')

    <div class="container m-t-86 pos-rlt">
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
            <div class="tac">
                <div class="success-title wauto mauto inline-block mt50">
                    <span class="icon-large icon-success-large fl"></span>
                    <span class="inline-block ml20 fl weight juhuang fs16">恭喜您，诚意金￥499.00支付成功！</span>
                    <div class="clear"></div>
                </div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="wauto inline-block mauto fs14">
                    <p class="tac">
                        交易时间：{{$time}}
                    </p>

                </div>
                <p class="tac mt50"><a id="redirect-url" href="/cart/editcart/{{$id}}" class="btn btn-s-md btn-danger fs16">确定</a></p>
                <p class="tac fs14" v-cloak><span class="juhuang">@{{countDownNum}}</span>秒后，自动进入售方确认步骤</p>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-top-up-success2",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection
