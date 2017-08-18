@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '再确认交车时间 - 华车网';?>
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
        <form action="{{route('store.Again',['id'=>$order->id])}}">
        {{csrf_field()}}
            <div class="wapper has-min-step">
            @include('cart._layout.appointemt_content')
                <div class="clear m-t-10"></div>
                <h2 class="title"><span class="red">*</span><span class="blue ml5 weight fs16">请确认交车时间及费用</span></h2>
                <p class="ul-prev"><span class=" fs14">交车时间：{{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif @if($order->orderAppoint->out_price) <br>超期费：￥{{$order->orderAppoint->out_price}} @endif</span></p>
                <div class="clear m-t-10"></div>
                <div class="clear m-t-10"></div>
                <div class="tac psr">
                    <a href="javascript:;" class="gary psa fs14 a-noagree">不同意?</a>
                    <a @click="agree" href="javascript:;" class="btn btn-s-md btn-danger fs16">同意</a>
                </div>
                <div class="clear m-t-10"></div>
                <div class="clear m-t-10"></div>
                <div class="clear m-t-10"></div>

                <div id="tipWin" class="popupbox">
                    <div class="popup-title"><span>确认同意</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                               <span class="tip-text mt10">{{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif @if($order->orderAppoint->out_price) 超期费：￥{{$order->orderAppoint->out_price}} @endif。<br><br>您确定同意吗？</span>
                               <div class="clear"></div>
                               <br>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a @click="doSend" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                            <div class="clear"></div>

                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>

                <div id="successWin" class="popupbox">
                    <div class="popup-title"><span>已同意</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac  constraint succeed" >
                               <span class="tip-tag bp0"></span>
                               <span class="tip-text mt10">感谢您提交的预约信息！</span>
                               <div class="clear"></div>
                               <br>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" @click="reload" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                            <div class="clear"></div>
                            <p class="p-gray mt10 fs14"><span class="red">@{{countDownNum}}</span>秒后自动关闭本弹窗</p>
                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>

                <div id="errorWin" class="popupbox">
                    <div class="popup-title"><span>提交未成功</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac succeed constraint error">
                               <span class="tip-tag bp0"></span>
                               <span class="tip-text mt10">抱歉，预约信息提交未成功，请重新尝试～</span>
                               <div class="clear"></div>
                               <br>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" @click="reload" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                            <div class="clear"></div>
                            <p class="p-gray mt10 fs14"><span class="red">@{{countDownNum}}</span>秒后自动关闭本弹窗</p>
                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-reconfirm-time-delivery", "/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection
