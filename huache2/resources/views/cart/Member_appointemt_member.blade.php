@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '等待售方交车确认 - 华车网';?>
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt">
        <div class="step psr">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li class="step-cur">付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>

        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti psr">
                <span class="fl">您的动作真快啊</span>
                <img class="fl good-img" src="/webhtml/order/themes/images/good.png" alt="">
                <span class="fl">售方可能太忙，还没来得及告诉我们交车信息呢！ 您若着急可以催一催服务专员哦～</span>
            </h1>
            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>
            <table class="nobordertbl wp100">
                <tr>
                    <td width="50%" class="fs14 weight">订单号：{{$order->order_sn}}</td>
                    <td width="50%" class="fs14">
                        <span class="weight">订单时间：</span>{{$order->created_at}}
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <?php $gc_name = explode('&gt;', $order->gc_name);?>
            <ul class="pdi-order-ul border ">
               <p class="fs14 ml10">
                   <span>{{$gc_name[0]}}</span>
                   <span class="ml5">&gt;</span>
                   <span class="ml5">{{$gc_name[1]}}</span>
                   <span class="ml5">&gt;</span>
                   <span class="ml5">{{$gc_name[2]}}</span>
                   <span class="ml30">{{$order->orderAttr->cart_color}}</span>
               </p>
            </ul>
            <div class="clear m-t-10"></div>
            <p class="tac"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="tdu juhuang">查看订单总详情</a></p>
            <div class="clear m-t-10"></div>


            <table class="tbl tac ">
                <tr>
                    <td>
                        <p class="tac fs16"><b>经销商</b></p>
                    </td>
                    <td>
                        <p class="tac ">{{$order->orderDealer->d_name}}</p>
                    </td>

                </tr>
                <tr>
                    <td>
                        <p class="tac fs16"><b>服务专员</b></p>
                    </td>
                    <td>
                        <p class="tac ">@if($order->orderAppoint->appoinWaiter){{$order->orderAppoint->appoinWaiter->name}}@endif</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tac fs16"><b>电话</b></p>
                    </td>
                    <td>
                        <p class="tac ">
                        @if(isset($order->orderAppoint->appoinWaiter->mobile))
                        {{$order->orderAppoint->appoinWaiter->mobile}} @if($order->orderAppoint->appoinWaiter->tel)/{{$order->orderAppoint->appoinWaiter->tel}}
           @endif
           @endif
           </p>
                    </td>

                </tr>
            </table>


            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>


        </div>
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

        });
    </script>
@endsection