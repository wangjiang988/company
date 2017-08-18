@extends('HomeV2._layout.base2')
@section('css')
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 psr">
        <div class="step pos-rlt">
             <p class="order-head-status">
                 <img src="/webhtml/custom/themes/images/xs-bg.png" alt="">
                 <span class="ml5 juhuang fs18"> 协商确认</span>
             </p>

        </div>
    </div>

    <div class="container pos-rlt content">


        <div class="clear"></div>
        <div class="wapper has-min-step">
            <div class="clear m-t-10"></div>
            <p>尊敬的客户：</p>
            <p class="ti">
            <?php $arr = [
                config('orders.order_sincerity_member_result'),
                config('orders.order_doposit_member_result'),
                config('orders.order_jiaoche_member_result')
            ];?>
            @if(in_array($order->order_state, $arr))
            感谢您已确认协商终止处理方案！售方还需要点时间确认，请耐心等待～
            @else
            本次购车因故即将终止，您、华车、售方三方协商完成处理方案，与您有关的内容如下，请确认。
            @endif
            </p>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class="fs14"><span class="weight">订单号：</span>{{$order->order_sn}}</td>
                    <td width="50%" class="fs14">
                        <span class="weight">订单时间：</span>{{$order->created_at}}
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <?php $gc_name = explode(" &gt;", $order->gc_name);?>
            <ul class="pdi-order-ul border ">
               <p class="fs14 ml10">
                   <span>{{$gc_name[0]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$gc_name[1]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$gc_name[2]}}</span>
                   <span class="ml30">{{$order->orderinfo->body_color}}</span>
               </p>
            </ul>

            <p class="tac m-t-10"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="juhuang tdu ">查看订单总详情</a></p>
            <h2 class="title"><span class="ml5 weight">处理方案</span></h2>

            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <table>
                <tr>
                    <td width="120" valign="top"><img src="/webhtml/common/images/jxb.gif" alt=""></td>
                    <?php $price = $order->limitConciliation(2);?>
                    <td>
                    @if(intval($price->seller_deposit_from_userjxb + $price->hwache_deposit_from_userjxb))
                        <p class="fs14 ul-prev">买车担保金赔偿￥{{$price->seller_deposit_from_userjxb + $price->hwache_deposit_from_userjxb}}</p>
                    @endif
                    @if(intval($price->return_user_available_deposit_from_userjxb))
                        <p class="fs14 ul-prev">退还可用余额￥{{$price->return_user_available_deposit_from_userjxb}}</p>
                    @endif
                    @if(intval($price['transfer_hwache_service_charge_from_userjxb']))
                        <p class="fs14 ul-prev">转付华车服务费￥{{$price['transfer_hwache_service_charge_from_userjxb']}}</p>
                    @endif
                    @if(intval($price['apology_money_from_sellerjxb']))
                        <p class="fs14 ul-prev">获得歉意金补偿￥{{$price['apology_money_from_sellerjxb']}}</p>
                    @endif
                    @if(intval($price['user_deposit_interest_from_sellerjxb']))
                        <p class="fs14 ul-prev">获得客户买车担保金利息补偿￥{{$price['user_deposit_interest_from_sellerjxb']}}</p>
                    @endif

                    </td>
                </tr>
                @if(intval($price->user_damage))
                <tr>
                    <td width="120" valign="top">
                        <div class="mt10"></div>
                        <span class="fs14">您可获得补偿：</span>
                    </td>
                    <td>
                        <div class="mt10"></div>
                        <p class="fs14 ul-prev">获得客户买车其他损失补偿￥{{$price->user_damage}}</p>
                    </td>
                </tr>
                @endif
            </table>
            <input type="hidden" name="phone">
            @if(!in_array($order->order_state, $arr))
            <div class=" tac psr center mt50" >
                <a href="#" class="p-gray">不同意？</a>
                <a @click="agree" href="javascript:;" class="btn btn-s-md btn-danger fs18 ml100">同 意</a>
            </div>

            <div id="phoneValite" class="popupbox">
                <div class="popup-title">验证身份终止提现</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14  tac">

                           <div class="tip-text fs14 text-left inline-block">
                                <p class="tac">您确定同意协商处理方案、并委托华车执行吗？</p>
                                <br>
                                <p class="ml20">手 机 号： {{changeMobile(Auth::user()->phone)}}</p>
                                <phone-code class="ml20" phone="{{Auth::user()->phone}}" sendurl="{{route('member.getcode')}}" sendtype="78545072" sn= "{{$order->order_sn}}" type="get" @valite-code="getCode"></phone-code>
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
                        <a href="javascript:;" @click="doAgree('{{route('store.negotiation',['id'=>$order->id])}}',{{$price->id}})" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>
            @endif


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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-confirm-negotiation", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection

