@extends('HomeV2._layout.base2')
{{$title = "不接受特需客户终止"}}
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

    <div class="container pos-rlt content" >
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">很遗憾，您的订单已终止。</p>
            @include('cart.branch.security_comment')

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
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
         seajs.use(["/webhtml/common/js/vendor/vue.min","/js/module/common/common"],function(a,b,c){

        });
    </script>
@endsection
