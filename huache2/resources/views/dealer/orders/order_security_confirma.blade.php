@extends('_layout.orders.base_order')
@section('title', '等待客户确认订单修改-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 psr">
       <div class="cus-step">
           <div class="line stp-2"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><span>3</span><i>3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-2">
                    <small>等待到账</small>
                    <i></i>
                    <small class="juhuang">预约准备</small>
                </div>
            </div>
       </div>

        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                @include('dealer.orders._layout.content')
                    <p>温馨提示：订单完整内容，参见订单总详情。</p>
                    <h2 class="title">
                        <span class="blue ml5 weight">提议修改内容</span>
                    </h2>
                    <div class="fl wp70">
                @include('dealer.orders._layout.security_edit')
                    </div>
                    <div class="clear"></div>

                    <br><br>


            </div>

        </div>




    </div>
    </div>
@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/js/vendor/vue.min","module/custom/custom-order-base", "module/common/common"],function(v,u,c){
            u.init('{{date('Y-m-d H:i:s',time())}}',@if($order->orderDate->status)'{{date('Y-m-d H:i:s',strtotime("-7 days",strtotime($order->orderDate->jiaoche_at)))}}'@else'{{date('Y-m-d H:i:s',time())}}'@endif,function(){
              //设置回调
            })
        })
    </script>
@endsection
