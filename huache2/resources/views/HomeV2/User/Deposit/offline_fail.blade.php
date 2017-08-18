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

                <div class="mt50">
                    <h1 class="success-title">
                        <table class="mauto">
                            <tr>
                                <td><span class="icon-large icon-error-large"></span></td>
                                <td><span class="inline-block ml20 weight fs18 juhuang">抱歉，本次提交银行转账凭证未成功！</span></td>
                            </tr>
                        </table>
                    </h1>
                    <div class="m-t-10"></div><div class="m-t-10"></div>

                </div>

                <div class="wauto inline-block mauto fs14">
                    <p>>>   请重新尝试，如仍有问题，请<a href="#" class="juhuang">联系我们</a></p>
                </div>
                <p class="tac mt50"><a href="OK.ZF.10银行转账支付余款提交凭证.html" class="btn btn-s-md btn-danger fs16 sure">重新提交</a></p>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-bank-transfer-error",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection
