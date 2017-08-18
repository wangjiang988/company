@extends('_layout.orders.base_order')
@section('title', '售方收入已入账-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 psr">
       <div class="cus-step">
           <div class="line stp-5"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><i class="cur-step cur-step-4">4</i></li>
               <li class="last end"><i class="cur-step cur-step-5">5</i></li>
           </ul>

       </div>

        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                    @include('dealer.orders._layout.content_settlement')

                    <table class="tbl">
                       <tr>
                            <td colspan="6"><label class="weight fs16 juhuang">结算信息</label></td>
                       </tr>
                       <tr>
                            <td rowspan="5"><p class="tac fs14"><b>总收益</b></p></td>
                            <td rowspan="5"><p class="tac fs14"><b>- ￥360.00</b></p></td>
                            <td><p class="tac fs14"><b>项目</b></p></td>
                            <td><p class="tac fs14"><b>收支金额</b></p></td>
                            <td><p class="tac fs14"><b>说明</b></p></td>
                            <td><p class="tac fs14"><b>时间</b></p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">售方加信宝赔偿总额</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-27 10:20:51</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">获得诚意金补偿</p></td>
                            <td><p class="tac fs14">+ ￥299.00</p></td>
                            <td><p class="tac fs14"></p></td>
                            <td><p class="tac fs14">2017-02-28 15:41:18</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">返还售方已得</p></td>
                            <td><p class="tac fs14">- ￥110.00</p></td>
                            <td><p class="tac fs14">结算后</p></td>
                            <td><p class="tac fs14">2017-03-06 11:57:31</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">返还售方已得</p></td>
                            <td><p class="tac fs14">- ￥50.00</p></td>
                            <td><p class="tac fs14">结算后</p></td>
                            <td><p class="tac fs14">2017-03-06 11:57:31</p></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <p class="fs14"><b>结算金额：</b>￥270.00</p>
                                <p class="fs14"><b>结算后收支合计：</b>+ ￥300.00</p>
                            </td>
                       </tr>
                    </table>




            </div>

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
