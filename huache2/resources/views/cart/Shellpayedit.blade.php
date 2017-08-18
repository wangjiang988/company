@extends('HomeV2._layout.base')
@section('css')
    <link href="/themes/bootstrap.css" rel="stylesheet" />
    <link href="/themes/common.css" rel="stylesheet" />
    <link href="/themes/item-fix.css" rel="stylesheet" />
@endsection
@section('nav')
    @include('_layout.nav')
@endsection
@section('content')
   <div class="box" ms-include-src="itemheader"></div>

    <div class="container m-t-86 pos-rlt">
        <div class="step psr">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content m-left-187">
                    <small>等待支付</small>
                    <i></i>
                    <small class="juhuang">等待预约</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti psr">非常抱歉，售方在准备交车时发现一些状况，您是否可以接受以便早日提车呢？<img class="adv-img" src="/themes/images/adv.png" alt=""></h1>
            <div class="clear m-t-10"></div>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class="fs14 weight">订单号：{{$order->order_sn}}</td>
                    <td width="50%" class="fs14">
                        <span class="weight">订单时间：</span>{{$order->created_at}}
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <ul class="pdi-order-ul border ">
               <p class="fs14 ml10">
               <?php $car = explode('&gt;',$order->gc_name);?>
                   <span>{{$car[0]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$car[1]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$car[2]}}</span>
                   <span class="ml30">{{$order->orderAttr->cart_color}}</span>
               </p>
            </ul>
            <div class="clear m-t-10"></div>
            <p class="tac"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="tdu juhuang">查看订单总详情</a></p>
            <div class="clear m-t-10"></div>
            <hr class="dashed">
            <p class="fs14">提议修改的内容如下：</p>
            @include('dealer.orders._layout.security_edit')
            <div class="clear"></div>
            <hr class="dashed">
            <p class="fs14">根据华车平台规则，在您全额支付买车担保金后售方提出修改订单，您已自动获得下列补偿（在您账户的可用余额里了）：</p>
            <p class="fs14">1.获得歉意金补偿：人民币499.00元；</p>
            <p class="fs14">2.获得买车担保金利息补偿：人民币12.00元；<br>（2016-02-23～2016-02-25，3天，20000x3x0.02%=12.00）</p>

            <p class="fs14 weight mt20">请您选择：</p>
            <form action="" method="post" name="order-status-form">
            {{csrf_field()}}
                <div class="wait-order-status">
                    <table class="tbl2">
                      <tr>
                        <td valign="top" width="20" class="nopadding">
                            <input v-model="continueOrder" value="true" type="radio" class="jiaocheinput" name="jiaoche" id="">
                        </td>
                        <td valign="top" class="nopadding" width="80"><p class="fs14"><b>继续订单</b></p></td>
                        <td valign="top" class="nopadding"><p class="fs14"> 您同意上述修改条件，继续执行订单。</p></td>
                      </tr>
                      <tr>
                        <td valign="top" width="20" class="nopadding">
                            <input v-model="continueOrder" value="false" type="radio" class="jiaocheinput" name="jiaoche" id="">
                        </td>
                        <td valign="top" class="nopadding" width="80"><p class="fs14"><b>放弃订单 </b></p></td>
                        <td valign="top" class="nopadding"><p class="fs14">不接受上述修改，很遗憾您将放弃本订单，但您的买车担保金人民币20,000.00元将毫发无损退回您的可用余额中，您下回购车使用（不买车可在【我的华车】中办理全额提现。）</p></td>
                      </tr>

                    </table>
                    <p><b>温馨提示：</b>如您在售方交车邀请发出时限前仍未回复，将默认放弃订单操作。</p>
                    <p class="tac red hide" id="showhide">请选择是否继续订单！</p>

                    <p class="center mt50">
                        <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16">提交</a>
                    </p>

                    <div id="stopWin" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd tac succeed  constraint">
                                   <span class="tip-tag bp0"></span>
                                   <span class="tip-text mt10">确定放弃订单吗？</span>
                                   <div class="clear"></div>
                                   <br>
                                </p>
                                <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" @click="stopOrder({{$order->id}})" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

                    <div id="sendWin" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd tac">
                                   <br>
                                   <span class="tip-text fs14 text-left inline-block">确定同意接受修改条件并继续订单吗？</span>
                                   <div class="clear"></div>
                                   <br>
                                </p>
                                <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" @click="doSend({{$order->id}})" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>


            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>


        </div>
    </div>

    @endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
    <script type="text/javascript">

        seajs.use(["/js/vendor/vue.min","/js/module/custom/order-wait-subscribe", "module/common/common"],function(v,u,c){

        });
    </script>
@endsection