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

                <div class="mt50">
                    <h1 class="success-title">
                        <table class="mauto">
                            <tr>
                                <td><span class="icon-large icon-error-large"></span></td>
                                <td><span class="inline-block ml20 weight fs18 juhuang tal">对不起，您未在上架销售时段完成支付，订购暂未成功，请见谅！<br>下一时段可以继续尝试哦～～～</span></td>
                            </tr>
                        </table>
                    </h1>
                    <div class="m-t-10"></div><div class="m-t-10"></div>

                </div>

                <div class="wauto inline-block mauto fs14">
                    <table>
                        <tr>
                            <td valign="top">每日上架销售时段（北京时间）：</td>
                            <td>
                                <p>09:00-12:00</p>
                                <p>13:00-17:00</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <p class="tac mt50">
                    <a href="/" class="btn btn-s-md btn-danger fs16">返回首页</a>
                </p>
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
