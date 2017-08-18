@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '客户结算确认';?>
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
            <h1 class="ti psr">恭喜您在华车的全程保障下顺利买到了心仪座驾！确认以下费用后，华车将马上退回您多余的买车担保金。</h1>
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
            <h2 class="title">
                <span class="blue ml5 weight"> 费用结算</span>
            </h2>
            <div class="ul-prev fs14">
                <p>买车担保金：￥{{number_format($order->sponsion_price,2)}} </p>
                <p>转付华车服务费：￥{{number_format($order->orderPrice->hwache_service_price,2)}} </p>
                <p>应退还可用余额：￥{{number_format($order->sponsion_price - $order->orderPrice->hwache_service_price,2)}} </p>
            </div>
            <div class="m-t-10" v-for="i ini 3"></div>
            <div class="tac psr">
                <a href="javascript:;" class="gary psa fs14 a-noagree">不同意？</a>
                <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16">同 意</a>
            </div>
            <div class="m-t-10" v-for="i ini 3"></div>

            <div id="phoneValite" class="popupbox">
                <div class="popup-title">确认结算</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14  tac">

                           <div class="tip-text fs14 text-left inline-block">
                                <p class="tac">使用买车担保金转付华车服务费￥{{number_format($order->orderPrice->hwache_service_price,2)}}，<br>
                                剩余买车担保金￥{{number_format($order->sponsion_price - $order->orderPrice->hwache_service_price,2)}}退回可用余额。<br>
                                您确定委托华车执行吗？<br>
                                </p>
                                <br>
                                <p class="ml20">手 机 号： {{changeMobile(Auth::user()->phone)}}</p>
                                <phone-code ref="phonecode" class="ml20" phone="{{Auth::user()->phone}}" @valite-code="getCode" type="get" sendtype="78715078" sn= "{{$order->order_sn}}" sendurl="{{route('member.getcode')}}" token="{{csrf_token()}}"></phone-code>
                                <div class="clear"></div>
                                <p class="tal ml100 mt10 red hide" :class="{show:isEmtpy}">请输入验证码</p>
                                <p class="tal ml100 mt10 red hide" :class="{show:isError}">验证码有误，请重新输入~</p>
                                <p class="tal ml100 mt10 red hide" :class="{show:isCodeError}">验证码已失效，请重新获取~</p>
                           </div>
                           <div class="clear"></div>
                           <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="doSend('{{route('store.Refund',['id'=>$order->id])}}')" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>



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

        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-settlement-confirmation", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection