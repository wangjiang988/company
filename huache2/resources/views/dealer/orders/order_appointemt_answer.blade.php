@extends('_layout.orders.base_order')
@section('title', '等待客户再确认交车时间-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 psr">
       <div class="cus-step">
           <div class="line stp-3"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>

       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-3">
                    <small>发出通知</small>
                    <i></i>
                    <small class="juhuang">确认反馈</small>
                    <i></i>
                    <small>预约完毕</small>
                </div>
            </div>
       </div>


        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                   @include('dealer.orders._layout.content')
                    <p>温馨提示：订单完整内容，参见订单总详情。</p>
                    <div class="m-t-10"></div>
                    <h2 class="title">
                        <span class="blue ml5 weight">客户反馈</span>
                    </h2>
                    <p class="fs14">计划上牌车主名称：{{$order->orderAppoint->owner_name}}</p>
                    <p class="fs14">上牌车主名称与提车人姓名是否一致：
                    @if($order->orderAppoint->car_purpose == 2 || $order->orderAppoint->owner_name != $order->orderAppoint->extract_name)
                    否
                    @else
                    是
                    @endif
                    </p>
                    <p class="fs14">提车人姓名：{{$order->orderAppoint->extract_name}}</p>
                    <p class="fs14">提车人电话：{{$order->orderAppoint->extract_phone}}</p>


                    <div class="m-t-10"></div>
                    <h2 class="title">
                        <span class="blue ml5 weight">客户正在确认</span>
                    </h2>
                    <p class="fs14">提议的交车时间</p>
                    <div class="time-wrapper box-inner-def box-inner-fix">
                          <div v-cloak :class="{hide:timeList.length <= 15}">
                              <a @click="pushLeft" href="javascript:;" class="fl push left-push" :class="{end:marginLeft==0}"></a>
                              <a @click="pushRight" href="javascript:;" class="fr push right-push" :class="{end:timeList.length - 15 == click}"></a>
                          </div>
                          <div class="clear"></div>
                          <ol class="time" :style="{'margin-left':marginLeft+'px'}">
                              <input type="hidden" value="" name="SolicitationTime" id="SolicitationTime">
                              <li v-cloak v-for="time in timeList">
                                  <input type="hidden" name="time" :value="time.select">
                                  <span class="day" :class="{selected:time.selected}" @click="selectMonth(time)">
                                      @{{time.month}}-@{{time.day}}
                                      <br>
                                      @{{dayArray[time.week]}}
                                  </span>
                                  <span class="am-pm" :class="{selected:time.selected}" @click="selectMonth(time)">@{{ampmrevelist[time.select-1]}}</span>
                              </li>
                          </ol>
                    </div>
                    <div class="m-t-10"></div>
                    <p class="fs14 ml20">客户希望交车时间：{{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif @if($order->orderAppoint->is_timeout) @else （免收超期费）@endif</p>
                    <p class="ul-prev fs14">
                      客户正在确认的交车时间：{{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif     <br>
                      <span class="ml70">@if($order->orderAppoint->out_price) <br>客户正在确认的超期费：￥{{$order->orderAppoint->out_price}} @endif</span>
                    </p>
                    <div class="clear m-t-10"></div>
                    <div class="clear m-t-10"></div>




            </div>

        </div>

    </div>
    </div>
@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-wait-customer-confirm", "module/common/common"],function(v,u,c){
            var _timelist = {!! json_encode($car_times) !!}
            u.initTimeList(_timelist)
            u.initStartEndTime("2017-5-2","2017-5-23")

        })
    </script>
@endsection