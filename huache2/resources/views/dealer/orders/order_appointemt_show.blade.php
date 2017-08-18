@extends('_layout.orders.base_order')
@section('title', '等待客户确认交车邀请-用户管理-华车网')
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
                    <small class="juhuang">发出通知</small>
                    <i></i>
                    <small>确认反馈</small>
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
                        <span class="blue ml5 weight">已发交车邀请的可交车时间</span>
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
                                  <span class="day" :class="{selected:time.selected}">
                                      @{{time.month}}-@{{time.day}}
                                      <br>
                                      @{{dayArray[time.week]}}
                                  </span>
                                  <span class="am-pm" :class="{selected:time.selected}">@{{ampmrevelist[time.select-1]}}</span>
                              </li>
                          </ol>
                    </div>



            </div>

        </div>

    </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-wait-confirm-invitation", "module/common/common"],function(v,u,c){
            var _timelist = {!!json_encode($order->orderDate->jiaoche_times) !!}
            u.initTimeList(_timelist)
            var _total = 0
            $("#tbl_sum tr").slice(1).each(function(index, el) {
                var _price = parseFloat($(el).find("td:last").attr("data-price"))
                _total+=_price

            });
            $("#sum_price span").html(u.format(_total))
        })
    </script>
@endsection
