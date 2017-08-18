@extends('_layout.orders.base_order')
@section('title',$title)
@section('content')
    <div class="container content m-t-86 pos-rlt ">

    <div class="container pos-rlt">
        <div class="step pos-rlt">
             <p class="order-head-status text-center">交易结束</p>
        </div>
    </div>
    <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                @include('dealer.orders._layout.order_negotiation')


                    <table class="tbl">
                        <tbody>

                            <tr>
                                <th colspan="2" class="tal juhuang"><label class="weight fs16">交易结果</label></th>
                            </tr>
                            <tr>
                                <td colspan="2" class="tal"><p class="fs14"><b>结束原因：</b>协商终止</p></td>
                            </tr>
                            <tr>
                            <?php $price = $order->limitConciliation(2);?>
                                <td class="tal fs14 norightborder" valign="top" width="100"><b>当前执行：</b></td>
                                <td class="noleftborder">
                            @if(intval($price->seller_deposit_from_userjxb))
                               <p class="fs14">获得客户买车定金补偿￥{{$price->seller_deposit_from_userjxb}}</p>
                            @endif
                            @if(intval($price->transfer_seller_service_charge_from_userjxb))
                               <p class="fs14">售方服务费实得￥{{$price->transfer_seller_service_charge_from_userjxb}}</p>
                            @endif
                            @if(intval($price->apology_money_from_sellerjxb))
                               <p class="fs14">歉意金赔偿￥{{$price->apology_money_from_sellerjxb}}</p>
                            @endif
                            @if(intval($price->user_deposit_interest_from_sellerjxb))
                               <p class="fs14">客户买车担保金利息赔偿￥{{$price->user_deposit_interest_from_sellerjxb}}</p>
                            @endif
                            @if(intval($price->return_user_avaiable_from_sellerjxb))
                               <p class="fs14">已退还可提现余额￥{{$price->return_user_avaiable_from_sellerjxb}}</p>
                            @endif
                            @if(intval($price->user_damage))
                               <p class="fs14">客户买车其他损失赔偿￥{{$price->user_damage}}</p>
                            @endif
                            @if(intval($price->hwache_damage))
                               <p class="fs14">华车平台损失赔偿￥{{$price->hwache_damage}}</p>
                            @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                 @if($jiaxinbaos->count())
                    <table class="tbl">
                       <tr>
                            <td colspan="4"><img src="/webhtml/common/images/jxb.gif" alt=""></td>
                       </tr>
                       <tr>
                            <td><p class="tac fs14"><b>冻结状态</b></p></td>
                            <td><p class="tac fs14"><b>进出金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        @foreach($jiaxinbaos as $jiaxinbao)
                        <tr>
                            <td><p class="tac fs14">
                                @if($jiaxinbao->type == 10)
                                冻结
                                @elseif($jiaxinbao->type == 20)
                                解冻
                                @endif
                            </p></td>
                            <td><p class="tac fs14">
                                @if($jiaxinbao->type == 10)
                                -
                                @elseif($jiaxinbao->type == 20)
                                +
                                @endif
                                ￥{{$jiaxinbao->money}}</p></td>
                            <td><p class="tac fs14">{{$jiaxinbao->item}}</p></td>
                            <td><p class="tac fs14">{{$jiaxinbao->created_at}}</p></td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
                    <table class="tbl">
                       <tr>
                            <td colspan="6"><label class="weight fs16 juhuang">结算信息</label></td>
                       </tr>
                       <tr>
                            <td rowspan="5"><p class="tac fs14"><b>总收益</b></p></td>
                            <td rowspan="5"><p class="tac fs14"><b>- ￥2,589.00</b></p></td>
                            <td><p class="tac fs14"><b>项目</b></p></td>
                            <td><p class="tac fs14"><b>收支金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">歉意金赔偿</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-27 10:20:51</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">客户买车担保金利息2赔偿</p></td>
                            <td><p class="tac fs14">- ￥12.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-28 15:41:18</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">获得客户买车定金补偿</p></td>
                            <td><p class="tac fs14">+ ￥3,000.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-03-06 11:57:31</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">获得赔偿返还</p></td>
                            <td><p class="tac fs14">+ ￥100.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-03-06 11:57:31</p></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <p class="fs14"><b>结算金额：</b>￥0</p>
                            </td>
                       </tr>
                    </table>

            </div>

    </div>

    </div>
@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-order-base", "module/common/common"],function(v,u,c){

        })
    </script>
@endsection