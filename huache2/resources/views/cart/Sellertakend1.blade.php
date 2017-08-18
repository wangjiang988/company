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
    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
             <p class="order-head-status">！ 交易结束</p>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
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
               <p class="fs14 ml10">
               @php($gc = explode('&gt;',$order->gc_name))
                   <span>{{$gc[0]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$gc[1]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$gc[2]}}</span>
                   <span class="ml30">{{$order->orderattr->cart_color}}</span>
               </p>
            </ul>

            <p class="tac m-t-10"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="juhuang tdu ">查看订单总详情</a></p>

            <table class="nobordertbl fs14" width="100%">
                <tr>
                    <td width="70" valign="top"><b>结束原因：</b></td>
                    <td><p>客户原因—特别文件办理不能达成一致</p></td>
                </tr>
                <tr>
                    <td valign="top"><b>当前执行：</b></td>
                    <td>
                        <p>1.（无责）退还买车担保金￥499.00      </p>
                    </td>
                </tr>
            </table>
            <h2 class="title"><span class="ml5 weight">担保结算</span></h2>
            <p class="ml20"><img src="/themes/images/item/jxb.gif" alt=""></p>
            <div class="wp85">
                <table class="tbl">
                   <tr>
                        <td><p class="tac fs14"><b>买车担保金</b></p></td>
                        <td><p class="tac fs14"><b>进出金额</b></p></td>
                        <td><p class="tac fs14"><b>说明</b></p></td>
                        <td><p class="tac fs14"><b>时间</b></p></td>
                    </tr>
                    <tr>
                        <td><p class="tac fs14">冻结</p></td>
                        <td><p class="tac fs14">+ ￥499.00</p></td>
                        <td><p class="tac fs14"></p></td>
                        <td><p class="tac fs14">2017-02-23 15：23：21</p></td>
                    </tr>
                    <tr>
                        <td><p class="tac fs14">解冻</p></td>
                        <td><p class="tac fs14">- ￥499.00</p></td>
                        <td><p class="tac fs14">已退还可用余额（无责）</p></td>
                        <td><p class="tac fs14">2017-02-23 15：41：18</p></td>
                    </tr>
                </table>
            </div>

            <div class="m-t-10"></div>
            <p class="fs14 ml20"><b>总收支：</b>+￥499.00</p>

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
        seajs.use(["module/common/common", "bt"],function(a,b,c){

        })
    </script>
@endsection
