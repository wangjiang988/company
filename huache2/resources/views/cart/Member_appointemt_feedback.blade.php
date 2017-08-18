@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '等待售方反馈希望交车时间 - 华车网';?>
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
                <li class="step-cur">预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content pdi">
                    <small>开始预约</small>
                    <i></i>
                    <small class="juhuang">反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>

        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
         @include('cart._layout.appointemt_content')
            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>

        </div>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-wait", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection
