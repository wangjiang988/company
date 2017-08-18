@extends('_layout.orders.base_order')
@section('title', '交车邀请超时而终止-用户管理-华车网')
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
                                <td colspan="2" class="tal"><p class="fs14"><b>结束原因：</b>售方原因—售方主动终止</p></td>
                            </tr>
                            <tr>
                                <td class="tal fs14 norightborder" valign="top" width="100"><b>当前执行：</b></td>
                                <td class="noleftborder">
                                   <p>1.歉意金2赔偿￥499.00</p>
                                   <p>2.客户买车担保金利息2赔偿￥16.00</p>
                                   <p>3.华车平台损失赔偿￥588.00</p>
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
                        <tr>
                            <td><p class="tac fs14">冻结</p></td>
                            <td><p class="tac fs14">+ ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金</p></td>
                            <td><p class="tac fs14">2017-02-23 15：23：21</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">+ ￥12.00</p></td>
                            <td><p class="tac fs14">客户买车担保金利息，2017-02-23～2017-02-25，3天</p></td>
                            <td><p class="tac fs14">2017-02-25 00:00:01</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金赔偿</p></td>
                            <td><p class="tac fs14">2017-02-25 09:26:46</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金赔偿</p></td>
                            <td><p class="tac fs14">2017-02-23 15：41：18</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥12.00</p></td>
                            <td><p class="tac fs14">客户买车担保金利息，2017-02-23～2017-02-25，3天</p></td>
                            <td><p class="tac fs14">2017-02-25 09:26:46</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">冻结</p></td>
                            <td><p class="tac fs14">+ ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金2</p></td>
                            <td><p class="tac fs14">2017-02-25 09:31:29</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金赔偿</p></td>
                            <td><p class="tac fs14">2017-02-23 15：41：18</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">冻结</p></td>
                            <td><p class="tac fs14">+ ￥8.00</p></td>
                            <td><p class="tac fs14">客户买车担保金利息2，2017-02-26～2017-02-27，2天</p></td>
                            <td><p class="tac fs14">2017-02-28 00:00:01</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">冻结</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金2赔偿</p></td>
                            <td><p class="tac fs14">2017-02-27 10:20:51</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥8.00</p></td>
                            <td><p class="tac fs14">客户买车担保金利息2赔偿，2017-02-26～2017-02-27，2天</p></td>
                            <td><p class="tac fs14">2017-02-27 10:20:51</p></td>
                        </tr>
                    </table>

                    <table class="tbl">
                       <tr>
                            <td colspan="6"><label class="weight fs16 juhuang">结算信息</label></td>
                       </tr>
                       <tr>
                            <td rowspan="4"><p class="tac fs14"><b>总收益</b></p></td>
                            <td rowspan="4"><p class="tac fs14"><b>- ￥1,110.00</b></p></td>
                            <td><p class="tac fs14"><b>项目</b></p></td>
                            <td><p class="tac fs14"><b>收支金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">售方加信宝赔偿总额</p></td>
                            <td><p class="tac fs14">- ￥1,018.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-27 10:20:51</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">华车平台损失赔偿</p></td>
                            <td><p class="tac fs14">- ￥588.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-28 15:41:18</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">获得赔偿返还</p></td>
                            <td><p class="tac fs14">+ ￥496.00</p></td>
                            <td><p class="tac fs14">结算后</p></td>
                            <td><p class="tac fs14">2017-03-06 11:57:31</p></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <p class="fs14"><b>结算金额：</b>￥0</p>
                                <p class="fs14"><b>结算后收支合计：</b>+ ￥496.00</p>
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
