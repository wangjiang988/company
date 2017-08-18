@extends('_layout.orders.base_order')
@section('title', '等待反馈客户希望交车时间-用户管理-华车网')
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
                        <span class="red">* </span><span class="blue ml5 weight">待确认</span>
                    </h2>
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
                                  <span class="day" :class="{selected:time.selected}" >
                                      @{{time.month}}-@{{time.day}}
                                      <br>
                                      @{{dayArray[time.week]}}
                                  </span>
                                  <span class="am-pm" :class="{selected:time.selected}">@{{ampmrevelist[time.select-1]}}</span>
                              </li>
                          </ol>
                    </div>
                    <div class="m-t-10"></div>
                    <p class="fs14 ml20">客户希望交车时间：{{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif @if($order->orderAppoint->is_timeout) @else （免收超期费）@endif</p>
                    <div class="fs14 ml20">
                        <span class="fl">是否同意客户希望时间：</span>
                        <div class="fl">
                            <p><label><input v-model="agree" value="1" type="radio" name="agree" id=""><span class="ml5">同意 （客户希望交车时间即为约定交车日期）</span></label></p>
                            <form action="{{route('dealer.appoint.store',['id'=>$order->id])}}">
                            {{csrf_field()}}
                            <div>
                                <label class="fl"><input v-model="agree" value="0" type="radio" name="agree" id=""><span class="ml5">已与客户协商为：</span></label>
                                <div class="form-group psr fl">
                                    <input readonly="" v-model="negotiationTime" :value="negotiationTime" @focus="selectTime" type="text" :placeholder="negotiationTime" class="form-control " >
                                    <i class="rili"></i>
                                </div>
                                <drop-down class="ml20 fl" v-on:receive-params="selectAmPm" class-name="btn-sm disabled" def-value="" :list="ampmlist"></drop-down>
                                <span class="ml20 fl mt5">超期费：￥</span>
                                <input @focus="initPrice" v-model="price" :value="price" disabled type="text" placeholder="0.00" class="form-control fl w100 ml10">
                                <span class="red ml10 hide fl mt5" :class="{show:isError}">格式有误，请重输！</span>
                                <div class="clear">（须客户再次确认同意后，方为约定交车日期）</div>
                                <p></p>
                            </div>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear m-t-10"></div>
                    <div class="clear m-t-10"></div>
                    <p class="tac red hide" :class="{show:isEmpty}">提交内容不完整～</p>
                    <div class="tac psr">
                        <a href="javascript:;" class="gary psa fs14 a-noagree">无法达成一致？</a>
                        <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16">提交</a>
                    </div>

                    <div id="tipWin" class="popupbox">
                        <div class="popup-title"><span>提交确认</span></div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd tac" >
                                   <span class="tip-text mt10">您同意交车时间定为{{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}}<br>@if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif、且无超期费，确定吗？</span>
                                   <div class="clear"></div>
                                   <br>
                                <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a @click="doAgree" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                                <div class="clear"></div>

                                <div class="m-t-10"></div>
                            </div>
                        </div>
                    </div>

                    <div id="negotiationWin" class="popupbox">
                        <div class="popup-title"><span>提交确认</span></div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd tac" >
                                   <span class="tip-text mt10">您已与客户协商达成：<br>交车时间@{{negotiationTime}} @{{ampm}}、@{{formatMoney(price,2,"￥") == "￥0.00" ? "且无超期费" : "超期费"+formatMoney(price,2,"￥")}}，<br>确定吗？</span>
                                   <div class="clear"></div>
                                   <br>
                                <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a @click="doAgree" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                                <div class="clear"></div>

                                <div class="m-t-10"></div>
                            </div>
                        </div>
                    </div>



            </div>

        </div>

    </div>
    </div>
@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-waiting-feedback", "module/common/common"],function(v,u,c){
            var _timelist = {!! json_encode($car_times) !!}
            u.initTimeList(_timelist)
            u.initStartEndTime("{{date('Y-m-d', time())}}","2099-12-24")

        })
    </script>
@endsection
