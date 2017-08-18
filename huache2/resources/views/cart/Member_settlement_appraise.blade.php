@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '客户退担保金';?>
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
                <li>付款提车<i></i></li>
                <li class="step-cur">退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>

        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti psr">
                <span>已退还可用余额￥{{number_format($order->sponsion_price - $order->orderPrice->hwache_service_price,2)}}</span>
                <span class="ml50">>></span>
                <a href="{{route('my.myBalance')}}" target="_blank" class="juhuang tdu ml50">查看可用余额</a>
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
            <hr class="dashed">
            <p class="fs14">您对本次购车之旅有何体会？盼望您告诉我们：</p>
            <form action="{{route('store.Comment',['id'=>$order->id])}}" method="post" id="comment">
            {{csrf_field()}}
                <table class="fs14">
                    <tr>
                        <td valign="middle">
                            华车服务：
                        </td>
                        <td>
                            <div class="form-item">
                              <div class="formItemDiff sele formItemDiffFirst psr"><div class="cot st1 cot-first"><p class="wps">1分 差<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff sele"><div class="cot "><p class="wps">2分 一般<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff sele"><div class="cot "><p class="wps">3分 好<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff"><div class="cot "><p class="wps">4分 很好<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff"><div class="cot "><p class="wps">5分 非常好<span class="ttip"></span></p></div></div>
                              <input type="hidden" name="hwache_service" value="3">
                            </div>
                        </td>
                    </tr>

                </table>
                <div class="clear m-t-10"></div>
                <table class="fs14">
                    <tr>
                        <td valign="middle">
                            售方服务：
                        </td>
                        <td>
                            <div class="form-item">
                              <div class="formItemDiff sele formItemDiffFirst psr"><div class="cot st1 cot-first"><p class="wps">1分 差<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff sele"><div class="cot "><p class="wps">2分 一般<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff sele"><div class="cot "><p class="wps">3分 好<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff"><div class="cot "><p class="wps">4分 很好<span class="ttip"></span></p></div></div>
                              <div class="formItemDiff"><div class="cot "><p class="wps">5分 非常好<span class="ttip"></span></p></div></div>
                              <input type="hidden" name="seller_service" value="3">
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="clear m-t-10"></div>
                <p class="fs14">您的感受：</p>
                <div class="mt5">
                    <textarea class="cont fl" maxlength="500" name="evaluate" @keyup="checkLength" @change="checkLength" placeholder="华车有独立客服倾听您的心声，您的隐私将不会向售方公开。" value=''></textarea>
                    <span class="gray fs14 ml10 fl mt120">500字/<span>@{{length}}</span></span>

                </div>
            </form>
            <div class="clear"></div>

            <div class="tac mt20">
                <p class="tac red hide">请选择对我们服务的印象～</p>
                <a href="javascript:;" class="btn btn-s-md btn-danger fs16" @click="send">提交评价</a>
            </div>



            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>


        </div>
    </div>
    <style>
    textarea::-webkit-input-placeholder,
    -moz-input-placeholder
    {color:#9fa0a0;}
    </style>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">

        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-refund-guarantees", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection