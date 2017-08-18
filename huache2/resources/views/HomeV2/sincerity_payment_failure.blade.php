@extends('HomeV2._layout.base2')
@section('css')
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 psr">
        <div class="step psr">
            <ul>
                <li class="step-cur">诚意预约<i></i></li>
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
                    <small class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>
    <div class="container psr content item tac">
        <br><br>
        <h1 style="background: url(/webhtml/common/images/pay-error.gif) no-repeat" class="juhuang fs18 tac mt50 wauto inline-block mauto weight">
            <p class="tal ml70">对不起，您未在上架销售时段完成支付，订购暂未成功，请见谅！</p>
            <p class="tal ml70">下一时段可以继续尝试哦～～～</p>
        </h1>
        <br><br>
        <table class="wauto mauto">
            <tr>
                <td valign="top">每日上架销售时段（北京时间）：</td>
                <td>
                    <p>09:00-12:00</p>
                    <p>13:00-17:00</p>
                </td>
            </tr>
        </table>
        <br><br>
        <a href="/" class="btn btn-danger fs16">返回首页</a>
        <br><br><br><br><br><br>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-wait", "/js/module/common/common"],function(v,u,c){
            //是否是限牌
           
        })
    </script>
@endsection

