@extends('_layout.orders.base_order')
@section('title',$title)
@section('content')
    <div class="container content m-t-86 pos-rlt ">

    <div class="container pos-rlt">
        <div class="step pos-rlt">
             <p class="order-head-status text-center">
             <img src="/webhtml/custom/themes/images/xs-bg.png" alt="">
             协商确认
             </p>
        </div>
    </div>
    <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                    @include('dealer.orders._layout.order_negotiation')

                    <h2 class="title"><span class="red">*</span><span class="ml5">待确认处理方案</span></h2>

                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <form action="{{route('dealer.deal.consult',['id'=>$order->id])}}" method="post" id="manger">
                    {!! csrf_field() !!}
                    <table>
                        <tr>
                            <td width="120" valign="top"><img src="/webhtml/common/images/jxb.gif" alt=""></td>
                            <td>
                            <?php $price = $order->limitConciliation(2);?>
                            @if(intval($price->seller_deposit_from_userjxb))
                               <p class="fs14 ul-prev">获得客户买车定金补偿￥{{$price->seller_deposit_from_userjxb}}</p>
                            @endif
                            @if(intval($price->transfer_seller_service_charge_from_userjxb))
                               <p class="fs14 ul-prev">售方服务费实得￥{{$price->transfer_seller_service_charge_from_userjxb}}</p>
                            @endif
                            @if(intval($price->apology_money_from_sellerjxb))
                               <p class="fs14 ul-prev">歉意金赔偿￥{{$price->apology_money_from_sellerjxb}}</p>
                            @endif
                            @if(intval($price->user_deposit_interest_from_sellerjxb))
                               <p class="fs14 ul-prev">客户买车担保金利息赔偿￥{{$price->user_deposit_interest_from_sellerjxb}}</p>
                            @endif
                            @if(intval($price->return_user_avaiable_from_sellerjxb))
                               <p class="fs14 ul-prev">已退还可提现余额￥{{$price->return_user_avaiable_from_sellerjxb}}</p>
                            @endif
                            @if(intval($price->user_damage))
                               <p class="fs14 ul-prev">客户买车其他损失赔偿￥{{$price->user_damage}}</p>
                            @endif

                            </td>
                        </tr>
                        @if(intval($price->hwache_damage))
                        <tr>
                            <td width="120" valign="top">
                                <div class="mt10"></div>
                                <span class="fs14">其他：</span>
                            </td>
                            <td>
                                <div class="mt10"></div>
                                <p class="fs14 ul-prev">华车平台损失赔偿￥{{$price->hwache_damage}}</p>
                            </td>
                        </tr>
                        @endif
                        <input type="hidden" name="consult_id" value="{{$price->id}}">
                    </table>
                    </form>
                    <?php $arr = [
                        config('orders.order_sincerity_member_result'),
                        config('orders.order_doposit_negotiation'),
                        config('orders.order_sincerity_negotiation'),
                        config('orders.order_jiaoche_negotiation'),
                        config('orders.order_doposit_member_result'),
                        config('orders.order_jiaoche_member_result')
                    ];?>
                    @if(in_array($order->order_state, $arr))
                    <div class=" tac psr center mt50" >
                        <a @click="agree" href="javascript:;" class="btn btn-s-md btn-danger fs18 ml100">同 意</a>
                    </div>
                    @endif
                    <div id="tipWin" class="popupbox">
                        <div class="popup-title">确认同意</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14  tac">
                                   <div class="tip-text fs14 text-left ">
                                        <p class="tac">确定同意该协商处理方案吗？</p>
                                        <br>
                                        <div class="clear"></div>
                                   </div>
                                   <div class="clear"></div>
                                   <br>
                                </p>
                                <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" @click="doAgree" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                                <div class="clear"></div>
                                <div class="m-t-10"></div>
                            </div>
                        </div>
                    </div>

            </div>
    </div>

    </div>
@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-confirms-negotiation", "module/common/common"],function(v,u,c){

        })
    </script>
@endsection
