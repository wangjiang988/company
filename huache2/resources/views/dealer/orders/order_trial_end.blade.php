@extends('_layout.orders.base_order')
@section('title', '裁判客户支付买车担保金违约-用户管理-华车网')
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
                   @include('dealer.orders._layout.content_cancel')


                    <table class="tbl">
                        <tbody>

                            <tr>
                                <th colspan="2" class="tal juhuang"><label class="weight fs16">交易结果</label></th>
                            </tr>
                            <tr>
                                <td colspan="2" class="tal"><p class="fs14"><b>结束原因：</b>客户原因一客户支付买车担保金违约</p></td>
                            </tr>
                            <tr>
                                <td class="tal fs14 norightborder" valign="top" width="100"><b>当前执行：</b></td>
                                <td class="noleftborder">
                                @if(intval($settlement->return_user_avaiable_from_sellerjxb))
                                   <p>1.已退还可提现余额￥
                                   {{$settlement->return_user_avaiable_from_sellerjxb or ''}}</p>
                                   <p>2.获得诚意金补偿￥299.00</p>
                                @else
                                <p>1.获得诚意金补偿￥299.00</p>
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>


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
                        @forelse($order->orderjiaxinbao as $jiaxinbao)
                        <tr>
                        <td><p class="tac fs14">{{$jiaxinbao->item}}</p></td>
                        <td><p class="tac fs14">{{($jiaxinbao->type == 20) ? '+' : '-'}} ￥{{$jiaxinbao->money}}</p></td>
                        <td><p class="tac fs14">{{$jiaxinbao->description}}</p></td>
                        <td><p class="tac fs14">{{$jiaxinbao->created_at}}</p></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                            无
                        </td>
                        </tr>
                        @endforelse
                    </table>

                    <table class="tbl">
                       <tr>
                            <td colspan="6"><label class="weight fs16 juhuang">结算信息</label></td>
                       </tr>
                       <tr>
                            <td rowspan="{{count($order->orderAccount)+1}}"><p class="tac fs14"><b>总收益</b></p></td>
                            <td rowspan="{{count($order->orderAccount)+1}}"><p class="tac fs14"><b>- ￥1,110.00</b></p></td>
                            <td><p class="tac fs14"><b>项目</b></p></td>
                            <td><p class="tac fs14"><b>收支金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        @foreach($order->orderAccount as $account)
                        <tr>
                            <td><p class="tac fs14">{{$account->from_remark}}</p></td>
                            <td><p class="tac fs14">{{($account->flow_type == 1) ? '-':'+'}} ￥{{$account->money}}</p></td>
                            <td><p class="tac fs14">{{$account->remark}}</p></td>
                            <td><p class="tac fs14">{{$account->created_at}}</p></td>
                        </tr>
                        @endforeach
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
        seajs.use(["../common/js/vendor/vue.min","module/custom/custom-order-base", "module/common/common"],function(v,u,c){

        })
    </script>
@endsection
