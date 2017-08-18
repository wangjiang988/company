@extends('HomeV2._layout.base2')
{{$title = '裁判客户支付买车担保金违约'}}
@section('css')
    <link href="/themes/item-fix.css" rel="stylesheet" />
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
             @include('cart.branch.security_comment')

            <p class="tac m-t-10"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="juhuang tdu ">查看订单总详情</a></p>

            <table class="nobordertbl fs14" width="100%">
                <tr>
                    <td width="70" valign="top"><b>结束原因：</b></td>
                    <td><p>客户原因—客户支付买车担保金违约</p></td>
                </tr>
                <tr>
                    <td valign="top"><b>当前执行：</b></td>
                    <td>
                        <p>1.诚意金赔偿￥{{config('orders.order_earnest_price')}} </p>
                        @if(intval($settlement->return_user_available_deposit_from_userjxb))
                        <p>2.已退还可用余额￥{{$settlement->return_user_available_deposit_from_userjxb or ''}} </p>
                        @endif
                    </td>
                </tr>
            </table>
            <h2 class="title"><span class="ml5 weight">担保结算</span></h2>
            <p class="ml20"><img src="/webhtml/common/images/jxb.gif" alt=""></p>
            <div class="wp85">
                <table class="tbl">
                   <tr>
                        <td><p class="tac fs14"><b>买车担保金</b></p></td>
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
            </div>

            <div class="m-t-10"></div>
            <p class="fs14 ml20"><b>总收支：</b><span id="total-price-txt"></span></p>

            <div class="m-t-10"></div>
            <div class="wp85">
                <table class="tbl" id="tbl-total">
                   <tr>
                        <td><p class="tac fs14"><b>项目</b></p></td>
                        <td><p class="tac fs14"><b>收支金额</b></p></td>
                        <td><p class="tac fs14"><b>说明</b></p></td>
                        <td><p class="tac fs14"><b>时间</b></p></td>
                    </tr>
                    @forelse($order->orderAccount as $account)
                        <tr>
                            <td><p class="tac fs14">{{$account->from_remark}}</p></td>
                            <td><p class="tac fs14" data-type="{{($account->flow_type == 1) ? '-':'+'}}" data-price="{{$account->money}}">{{($account->flow_type == 1) ? '-':'+'}} ￥{{$account->money}}</p></td>
                            <td><p class="tac fs14">{{$account->remark}}</p></td>
                            <td><p class="tac fs14">{{$account->created_at}}</p></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            无
                        </td>
                    </tr>
                    @endforelse

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
            var _total = 0
            $("#tbl-total tr").slice(1).each(function(index,item){
                var _p = $(item).find("td:eq(1) p")
                var _price = _p.attr("data-price").replace("￥","").replace(/,/g,"")
                var _type = _p.attr("data-type")
                var _money = _type == "-" ?(-parseFloat(_price)) : parseFloat(_price)
                _total += _money
            })
            $("#total-price-txt").text(
                _total < 0 ?
                            "-"+u.formatMoney(-_total.toFixed(2),2,"￥")
                            :
                            u.formatMoney(_total.toFixed(2),2,"￥")
            )
        })
    </script>
@endsection