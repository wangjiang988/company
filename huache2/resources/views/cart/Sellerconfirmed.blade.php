@extends('HomeV2._layout.base2')
@section('css')
  <link rel="stylesheet" href="{{ asset('themes/bootstrap.css') }}"/>
  <link href="{{asset('themes/common.css')}}" rel="stylesheet" />
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')


    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content">
                    <small>选择产品</small>
                    <i></i>
                    <small>付诚意金</small>
                    <i></i>
                    <small class="juhuang" class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>
    <div class="container pos-rlt content" ms-controller="item">
         @include('cart.branch.shellfragment')
            <hr class="dashed">
            @if($order->orderattr->show_status == 3)
             <p class="fs14 ti">经销商正在确认，按平台规则，将在24小时内向您反馈结果。</p>

            <div class="time">
                <div class="jishi countdown jishi-long">
                    <span>0</span>
                    <span>0</span>
                    <span class="fuhao">:</span>
                    <span>0</span>
                    <span>0</span>
                    <span class="fuhao">:</span>
                    <span>0</span>
                    <span>0</span>
                </div>
            </div>
            @else
             <p class="fs14 ti"> 根据平台规则，经销商将再次核对车源，并在20分钟内向您反馈结果。</p>
            <div class="time">
                <div class="jishi countdown">
                    <span>0</span>
                    <span>0</span>
                    <span class="fuhao">:</span>
                    <span>0</span>
                    <span>0</span>
                </div>
            </div>
             @endif
            <p class="fs14 ti">如反馈提出任何修改，华车网<a href="#" class="juhuang tdu" style="padding-left:5px" ><img src="/themes/images/item/jxb.gif" /></a>将无条件向您赔偿歉意金人民币{{$order['earnest_price']}}元。您可选择终止订单且退还全部诚意金，也可选择接受修改的新条件继续订单，所获得歉意金补偿将可抵扣买车担保金余款。</p>
            <p class="fs14 ti">如反馈确认无误，下一步订单将进入“付担保金”环节，由您开始支付买车担保金余款人民币{{number_format($order['sponsion_price']-$order['order_earnest_price'],2)}}元（买车担保金￥{{number_format($order['sponsion_price'],2)}}  —
已付诚意金￥{{ config('orders.order_earnest_price')}}）。目前我们提供<a href="#" class="juhuang tdu">线上支付</a>和<a href="#" class="juhuang tdu">银行转账</a>两种支付方式供您选择。银行转账方式，您须在24小时内提交有效的银行汇款凭证。
线上支付方式，因第三方支付的支付限额可能受限影响支付成功，可分笔支付。您在当日付完第一笔后，支付时限自动延长到第三个自然日的24点，您可从容完成买车担保金余额支付。</p>
            <div class="tac">
            </div>
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
    <script src="/js/sea.js"></script>
    <script src="/js/config.js"></script>
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"],function(a,b,c){
            a.init('{{date('Y-m-d H:i:s',time())}}','{{$order->rockon_time}}',function(){
                //设置回调
            })
        })
    </script>
@endsection

