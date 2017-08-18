@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '已订购的选装精品';?>
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 psr">
        <div class="step pos-rlt">
             <p class="order-head-status">
                 <a href="{{route('buy.show',['id'=>$order->id])}}"><span class="blue fs18">打造个性座驾</span></a>
                 <span class="ml5 blue fs18">></span>
                 <span class="ml5 blue fs18">已订购的精品</span>
             </p>
             <div class="xzj-status-wrapper">
             @if($order->xzjp_steps == 1)
                 <a href="{{route('parts.negotia',['id'=>$order->id])}}" class="xzj-status-item">
                     <span class="xzj-icon xzj-negotiation"></span>
                     <span class="xzj-txt">
                         <span class="xzj-num">{{$order->orderXzjEdit->where('is_install',0)->count()}}</span>
                         <span class="xzj-status">协商中</span>
                     </span>
                 </a>
                 @endif
                 @if(!$order->orderXzjEdit->where('is_install','<>',0)->isEmpty())
                 <a href="{{route('parts.logs',['id'=>$order->id])}}" class="xzj-status-item ml20">
                     <span class="xzj-icon xzj-record"></span>
                     <span class="xzj-txt">
                         <span class="xzj-status">协商<br>记录</span>
                     </span>
                 </a>
                 @endif
             </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <p class="mt20 ml50"><span>您订购的精品，售方已为您备货，交车时付款移交。又有看中的？ 请<a href="{{route('buy.show',['id'=>$order->id])}}" class="blue tdu">返回</a>继续订购。</span></p>
        <div class="clear"></div>
        <div class="wapper has-min-step">
            <div class="clear m-t-10"></div>
            <table  class="tbl tbl-blue tbl-xzj">
                <tr>
                    <th width="130"><b>品牌</b></th>
                    <th width="130"><b>名称</b></th>
                    <th width="250"><b>型号/说明</b></th>
                    <th width="110"><b>含安装费<br>折后总单价</b></th>
                    <th width="95"><b>已订件数</b></th>
                    <th width="97"><b>金额</b></th>
                    <th width="160"><b>订购时间</b></th>
                </tr>
                @foreach($order->orderXzj->sortByDesc('xzj_type')->sortByDesc('xzj_id') as $xzj)
                @if($xzj->color_type)
                <tr>
                @if($xzj->xzj_type)
                    <td><p class="tac">原厂</p></td>
                @else
                    <td><p class="tac">{{$xzj->xzj_brand}}</p></td>
                @endif
                    <td><p class="tac">{{$xzj->xzj_title}}</p></td>
                    <td><p class="tac">{{$xzj->xzj_model}}</p></td>
                    <td><p class="tac">￥{{sprintf("%.2f",($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee))}}</p></td>
                    <td><p class="tac">{{$xzj->xzj_num}}</p></td>
                    <td><p class="tac price" data-price="{{sprintf("%.2f",(($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee) * $xzj->xzj_num))}}">￥{{sprintf("%.2f",(($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee) * $xzj->xzj_num))}}</p></td>
                    <td><p class="tac">{{$xzj->created_at}}</p></td>
                </tr>
                @else
                <tr style="color: #AEAEAE">
                @if($xzj->xzj_type)
                    <td><p class="tac">原厂</p></td>
                @else
                    <td><p class="tac">{{$xzj->xzj_brand}}</p></td>
                @endif
                    <td><p class="tac">{{$xzj->xzj_title}}</p></td>
                    <td><p class="tac">{{$xzj->xzj_model}}</p></td>
                    <td><p class="tac">￥{{sprintf("%.2f",(($xzj->xzj_guide_price + $xzj->xzj_fee)*$order->orderBaojia->bj_xzj_zhekou/100))}}</p></td>
                    <td><p class="tac">{{$xzj->xzj_num}}</p></td>
                    <td><p class="tac price" data-price="{{sprintf("%.2f",(($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee)* $xzj->xzj_num))}}">￥{{sprintf("%.2f",(($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee) * $xzj->xzj_num))}}</p></td>
                    <td><p class="tac">{{$xzj->created_at}}</p></td>
                </tr>
                @endif
                @endforeach
            </table>
            <p class="mt20 text-right fs14">
                合计：<b id="sum-total">￥2,500.00</b>
                <span v-for="i in 20">&nbsp;</span>
            </p>
            <div class="mt50 tac psr">
                <a href="javascript:;" @click="dontWant()" class="psa dont-want p-gray">不想要了？</a>
                <a href="{{route('buy.show',['id'=>$order->id])}}" class="mt50 btn btn-s-md btn-danger fs18 sure">返回</a>
            </div>




            <div id="errorWin" class="popupbox">
                <div class="popup-title">温馨提示</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14 pd tac succeed constraint">
                           <span class="tip-tag bp0"></span>
                           <span class="tip-text mt10">非常抱歉，您还有正在协商中的事项，等售方回复后再提交新项目吧！</span>
                           <div class="clear"></div>
                           <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="{{route('buy.show',['id'=>$order->id])}}" class="btn btn-s-md btn-danger fs14 w100 ml20 inline-block ">返回</a>
                        <div class="clear"></div>
                        <p class="fs14"><span class="red">@{{countDownNum}}</span>秒后自动返回</p>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>



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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-options-to-order-products",  "/js/module/common/common"],function(v,u,c){
            @if($order->xzjp_steps == 1)
             u.initNegotiation(true)
             @else
             u.url("{{route('parts.negotia',['id'=>$order->id])}}")
             @endif
        })
    </script>
@endsection


