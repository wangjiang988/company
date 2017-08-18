@extends('HomeV2._layout.base2')
@section('css')
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')

    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
             <p class="order-head-status">！ 交易结束</p>
        </div>
    </div>

    <div class="container pos-rlt content">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">很遗憾，您的订单已终止。</p>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class="fs14"><span class="weight">订单号：</span>{{$order->order_sn}}</td>
                    <td width="50%" class="fs14">
                        <span class="weight">订单时间：</span>{{$order->created_at}}
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <ul class="pdi-order-ul border ">
            <?php $gc_name = explode(" &gt;", $order->gc_name);?>
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

            <table class="nobordertbl fs14" width="100%">
                <tr>
                    <td width="70" valign="top"><b>结束原因：</b></td>
                    <td><p>协商终止</p></td>
                </tr>
                <tr>
                <?php $price = $order->limitConciliation(2);?>
                    <td valign="top"><b>当前执行：</b></td>
                    <td>
                    @if(intval($price->seller_deposit_from_userjxb + $price->hwache_deposit_from_userjxb))
                        <p>买车担保金赔偿￥{{$price->seller_deposit_from_userjxb + $price->hwache_deposit_from_userjxb}}</p>
                    @endif
                    @if(intval($price->return_user_available_deposit_from_userjxb))
                        <p>退还可用余额￥{{$price->return_user_available_deposit_from_userjxb}}</p>
                    @endif
                    @if(intval($price['transfer_hwache_service_charge_from_userjxb']))
                        <p>转付华车服务费￥{{$price['transfer_hwache_service_charge_from_userjxb']}}</p>
                    @endif
                    @if(intval($price['apology_money_from_sellerjxb']))
                        <p>获得歉意金补偿￥{{$price['apology_money_from_sellerjxb']}}</p>
                    @endif
                    @if(intval($price['user_deposit_interest_from_sellerjxb']))
                        <p>获得客户买车担保金利息补偿￥{{$price['user_deposit_interest_from_sellerjxb']}}</p>
                    @endif
                    @if(intval($price['user_damage']))
                        <p>获得客户买车其他损失补偿￥{{$price['user_damage']}}</p>
                    @endif
                    </td>
                </tr>
            </table>
            <h2 class="title"><span class="ml5 weight">担保结算</span></h2>
            <p class="ml20"><img src="/webhtml/common/images/jxb.gif" alt=""></p>
            <div class="wp85">
            @if($jiaxinbaos->count())
                <table class="tbl">
                   <tr>
                        <td><p class="tac fs14"><b>买车担保金</b></p></td>
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

            </div>

            <div class="m-t-10"></div>
            <p class="fs14 ml20"><b>总收支：</b>-￥4,489.00</p>
            @endif

            <div class="m-t-10"></div>
            <div class="wp85">
                <table class="tbl">
                   <tr>
                        <td><p class="tac fs14"><b>项目</b></p></td>
                        <td><p class="tac fs14"><b>收支金额</b></p></td>
                        <td><p class="tac fs14"><b>说明</b></p></td>
                        <td><p class="tac fs14"><b>时间</b></p></td>
                    </tr>
                    <tr>
                        <td><p class="tac fs14">获得歉意金补偿</p></td>
                        <td><p class="tac fs14"><span class="fl">+</span><span class="fr">￥12.00</span></p><div class="clear"></div></td>
                        <td><p class="tac fs14">售方终止订单</p></td>
                        <td><p class="tac fs14">2017-02-23 15:26:22</p></td>
                    </tr>
                    <tr>
                        <td><p class="tac fs14">获得买车担保金利息补偿</p></td>
                        <td><p class="tac fs14"><span class="fl">+</span> <span class="fr">￥499.00</span></p><div class="clear"></div></td>
                        <td><p class="tac fs14">2017-02-23～2017-02-25，3天</p></td>
                        <td><p class="tac fs14">2017-02-25 09:26:46</p></td>
                    </tr>
                    <tr>
                        <td><p class="tac fs14">获得客户买车其他损失补偿</p></td>
                        <td><p class="tac fs14"><span class="fl">+</span><span class="fr">￥5,000.00</span></p><div class="clear"></div></td>
                        <td><p class="tac fs14"></p></td>
                        <td><p class="tac fs14">2017-02-25 09:26:46</p></td>
                    </tr>

                </table>
            </div>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-wait", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection