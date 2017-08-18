@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '完成评价';?>
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt">
        <div class="step psr">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li class="step-cur">完成评价<i></i></li>
                <div class="clear"></div>
            </ul>

        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti psr">
                华车以靠谱电商为己任，以加信社会为愿景。我们深深知道：正是客户对华车的一份份厚爱，才给了我们成长的源源动力。我们将以行动不断提升客户购车体验，为客户也为社会创造更大的价值。感谢您以实际行动为信用社会作出的贡献，感恩有您的一路相伴！您的购车之旅，已成为我们长久珍藏美好记忆～
            </h1>
            <p class="tar mt20"><a href="#" class="tdu juhuang">查看订单总详情</a></p>
            <div class="timeline-wrapper">
                <div class="time-tpl-wapper">
                    <div class="time-tpl-item time-tpl-right">
                        <p>{{$arr_times['reserva_time'] or ''}}</p>
                        <p class="juhuang">诚意预约</p>
                    </div>
                    <div class="time-tpl-item time-tpl-left">
                        <p>{{$arr_times['security_time'] or ''}}</p>
                        <p class="juhuang">付担保金</p>
                    </div>
                    <div class="time-tpl-item time-tpl-right">
                        <p>{{$arr_times['delivery_time'] or ''}}</p>
                        <p class="juhuang">预约交车</p>
                    </div>
                    <div class="time-tpl-item time-tpl-left">
                        <p>{{$arr_times['pickup_time'] or ''}}</p>
                        <p class="juhuang">付款提车</p>
                    </div>
                    <div class="time-tpl-item time-tpl-right">
                        <p>{{$arr_times['refund_time'] or ''}}</p>
                        <p class="juhuang">退担保金</p>
                    </div>
                    <div class="time-tpl-item time-tpl-left">
                        <p>{{date('Y年m月d日',strtotime($order->orderComment->created_at))}}</p>
                        <p class="juhuang">完成评价</p>
                    </div>
                    <div class="time-over">
                        <p class="juhuang weight">一路平安！</p>
                    </div>
                </div>
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
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">

        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-wait", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection
