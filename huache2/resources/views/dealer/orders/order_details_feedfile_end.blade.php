@extends('_layout.orders.base_order')
@section('title', '客户不接受特需被终止-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 pos-rlt " ms-controller="custom">

        <div class="container pos-rlt">
            <div class="step pos-rlt">
                 <p class="order-head-status text-center">交易结束</p>
            </div>
        </div>

        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                    <table class="tbl tbl-order-info">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16 weight">订单信息</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                     <table class="tbl2" width="100%">
                                        <tr>
                                            <td  width="50%"><p><b>订单号：</b>{{$order['order_sn']}}</p></td>
                                            <td  width="50%">
                                                <b>订单时间：</b>{{$order['created_at']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p><b>订单类别：</b>
                        @if($baojia['bj_is_xianche'])
                                            现车订单
                                            @else
                                            非现车订单
                                            @endif
                                            </p><hr class="dashed"></td>
                                            <td>
                                                <p>
                                                    <b>结束时间:</b>
                                                </p>
                                                {{date('Y年m月d日',strtotime($order->updated_at))}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                              <p>
                                                 <b>车型：</b>
                                                 <span>{{$order['gc_name']}}</span>
                                              </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p><b>整车型号： </b>{{$baojia['vehicle_model']}}</p></td>
                                            <td><p><b>厂商指导价：</b>￥{{number_format($car_info['zhidaojia'],2)}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车辆类别：</b>全新中规车整车</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>数量：</b>1台</p></td>
                                            <td>
                                            @if($baojia['bj_dealer_internal_id'])
                                            <p><b>内部车辆编号：</b>{{$baojia['bj_dealer_internal_id']}}</p>
                                            @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <?php $dealer = $order->orderDealer ?>
                                            <td colspan="2"><p><b>经销商：</b>{{$dealer['d_name']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>营业地点：</b>{{$dealer['d_yy_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>交车地点：</b>{{$dealer['d_jc_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>归属地区：</b>{{$dealer['d_areainfo']}}</p></td>

                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车辆开票价格：</b>{{number_format($baojia['car_price'],2)}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>客户买车定金：</b>{{number_format($baojia['client_hand_price'],2)}}</p></td>
                                        </tr>

                                         <tr>
                                            <td><p><b>我的服务费：</b>￥{{number_format($baojia['agent_service_price'],2)}}</p></td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <div class="times">
                                        <p class="status tac  m-t-10"><b class="fs14">订单状态：特别文件办理不能达成一致</b></p>

                                        <p class="tac m-t-10"><a href="{{route('dealer.order.detail',['order_id'=>$order['id']])}}" class="juhuang tdu">查看订单总详情</a></p>

                                        <hr class="dashed mt69">
                                        <p class="pl20 pt"><b>客户承诺上牌地区： </b>
                                        <?php $shangpai = getAreaName($order['shangpai_area'])?>
                                        @if($shangpai['parent_name'] === $shangpai['name'])
                                        {{$shangpai['name']}} </p>
                                        @else
                                        {{$shangpai['parent_name'].$shangpai['name']}} </p>
                                        @endif
                                    </div>
                                </td>

                            </tr>

                        </tbody>
                    </table>

                    <table class="tbl">
                        <tbody>

                            <tr>
                                <th colspan="2" class="tal juhuang"><label class="weight fs16">交易结果</label></th>
                            </tr>
                            <tr>
                                <td colspan="2" class="tal"><p class="fs14"><b>结束原因：</b>客户原因—特别文件办理不能达成一致</p></td>
                            </tr>
                            <tr>
                                <td class="tal fs14 norightborder" valign="top" width="100"><b>当前执行：</b></td>
                                <td class="noleftborder">
                                   <p>1.退还浮动保证金￥499.00    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <table class="tbl">
                       <tr>
                            <td colspan="4"><img src="./themes/images/item/jxb.gif" alt=""></td>
                       </tr>
                       <tr>
                            <td><p class="tac fs14"><b>冻结状态</b></p></td>
                            <td><p class="tac fs14"><b>进出金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">冻结</p></td>
                            <td><p class="tac fs14">+ ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金</p></td>
                            <td><p class="tac fs14">2017-02-23 15：23：21</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">已退还可提现余额</p></td>
                            <td><p class="tac fs14">2017-02-23 15：41：18</p></td>
                        </tr>
                    </table>

                    <table class="tbl">
                       <tr>
                            <td colspan="6"><label class="weight fs16 juhuang">结算信息</label></td>
                       </tr>
                       <tr>
                            <td rowspan="2"><p class="tac fs14"><b>总收益</b></p></td>
                            <td rowspan="2"><p class="tac fs14"><b>￥0</b></p></td>
                            <td><p class="tac fs14"><b>项目</b></p></td>
                            <td><p class="tac fs14"><b>收支金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">售方加信宝赔偿总额</p></td>
                            <td><p class="tac fs14">￥0</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-23 15：23：21</p></td>
                        </tr>

                        <tr>
                            <td colspan="6">
                                <p class="fs14"><b>结算金额：</b>￥0</p>
                                <p class="fs14"><b>结算后收支合计：</b>+ ￥1,001.00</p>
                            </td>
                       </tr>
                    </table>
            </div>

        </div>

    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/common/common", "bt"],function(a,b,c){

        })
    </script>
@endsection
