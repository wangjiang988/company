@extends('_layout.orders.base_order')
@section('title', '等待定期结算-用户管理-华车网')
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
                            <td><span class="weight blue">预计结算明细</span></td>
                       </tr>
                       <tr>
                            <td>
                                    <table class="tbl wp30 tac">
                                       <tr>
                                            <td width="50%"><span class="weight">项目</span></td>
                                            <td><span class="weight">收支金额</span></td>
                                       </tr>
                                       <tr>
                                            <td>售方服务费实得 </td>
                                            <td>
                                                <span class="fl">+</span>
                                                <span class="fr">￥500.00</span>
                                                <div class="clear"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>返还售方已得 </td>
                                            <td>
                                                <span class="fl">-</span>
                                                <span class="fr">￥230.00</span>
                                                <div class="clear"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="noborder ">
                                                 <p class="juhuang tar">预计结算金额：<b>￥270.00</b></p>
                                            </td>
                                        </tr>
                                    </table>

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
        seajs.use(["../common/js/vendor/vue.min","module/custom/custom-order-base", "module/common/common"],function(v,u,c){


        })
    </script>
@endsection
