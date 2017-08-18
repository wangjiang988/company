@extends('HomeV2._layout.base2')
@section('css')
<?php $arr=['发起减少协商','减少协商中']; $title = $arr[$order->xzjp_steps];?>
<link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
   @include('cart._layout.negotia_'.$order->xzjp_steps)
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-reducing-negotiation",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection


