@extends('_layout.orders.base_order')
@section('title', '等待客户结算确认-用户管理-华车网')
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
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金赔偿</p></td>
                            <td><p class="tac fs14">2017-02-25 00:00:01</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">冻结</p></td>
                            <td><p class="tac fs14">+ ￥499.00</p></td>
                            <td><p class="tac fs14">歉意金2</p></td>
                            <td><p class="tac fs14">2017-02-25 09:26:46</p></td>
                        </tr>
                        <tr>
                            <td><p class="tac fs14">解冻</p></td>
                            <td><p class="tac fs14">- ￥499.00</p></td>
                            <td><p class="tac fs14">已退还可提现余额</p></td>
                            <td><p class="tac fs14">2017-02-23 15：41：18</p></td>
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
