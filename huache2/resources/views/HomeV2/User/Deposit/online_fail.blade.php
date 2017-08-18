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
                <li class="first">诚意预约<i></i></li>
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content" style=" left: 187px;top:54px">
                    <small class="juhuang">等待支付</small>
                    <i></i>
                    <small>等待预约</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <div class="wapper has-min-step">

            <div class="tac">
                <div class="success-title wauto mauto inline-block mt50">
                    <span class="icon-large icon-error-large fl"></span>
                    <span class="inline-block ml20 fl weight juhuang mt10">抱歉，支付失败！</span>
                    <div class="clear"></div>
                </div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="wauto inline-block mauto fs14">
                    <p>本次未成功金额：￥{{number_format($money,2)}}</p>
                    <p>>> 请重新尝试，如有疑问，请参考<a href="#" class="juhuang tdu">支付帮助</a></p>
                </div>
                <p class="tac mt50"><a id="redirect-url" href="{{route('cart.editcar',['id'=>$id])}}" class="btn btn-s-md btn-danger fs16 ">确定</a></p>
                <p class="tac fs14"><span class="juhuang">@{{countDownNum}}</span>秒后自动跳转</p>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-pay-error",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection
