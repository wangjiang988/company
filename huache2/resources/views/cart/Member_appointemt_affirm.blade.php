@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '等待确认交车邀请 - 华车网';?>
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
                    <small class="juhuang">开始预约</small>
                    <i></i>
                    <small>反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>

        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
            @include('cart._layout.appointemt_content')
            <form action="{{route('store.Reply',['id'=>$order->id])}}">
            {{csrf_field()}}
                <hr class="dashed">
                <h2 class="title"><span class="red">*</span><span class="blue ml5 weight fs16">以下均为需要您回复的内容</span></h2>
                <p class="ul-prev" :class="{'ul-prev-red':isTimeEmpty}"><span class=" fs14">售方邀请您在下列推荐日期中选择一个尊驾最合适的提车吉日：</span></p>
                <div class="time-wrapper box-inner-def">
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
                <p class="fs14 mt10">温馨提示：影响提车日期选择的因素可能包括购车资金到位时间、周六周日节假日车管所休息无法上牌等。此外，在某些情况下，亦有可能当日无法完成所有提车、上路、上牌手续，建议您对其后时间统筹考虑。</p>
                <p class="fs14">
                    <span class="fl mt5">如上述日期实在都无法前往，请把您可安排的最接近时间反馈售方进行协商：</span>
                    <div class="form-group psr fl">
                        <datepicker ref="datepicker" :colorlist="colorList" @gettime="getTime" el="timeSelect" curdate="{{date('Y-m-d', time())}}"></datepicker>
                        <input id="timeSelect" readonly="" v-model="negotiationTime" :value="negotiationTime" type="text" :placeholder="negotiationTime" class="form-control " >
                        <i class="rili"></i>
                    </div>
                    <drop-down ref="rili" class="ml20" v-on:receive-params="selectAmPm" class-name="btn-sm disabled" def-value="" :list="ampmlist"></drop-down>
                </p>
                <div class="clear"></div>
                <p class="fs14 hide" :class="{show:isShow}">特别提示：该日期距当前日较远，因超期提车将增加经销商额外成本，可能须由您作相应补偿，由售方在后续反馈中与您商定。</p>
                <p class="ul-prev" :class="{'ul-prev-red':isUseEmpty}"><span class=" fs14">您座驾的计划上牌地区：  <b class="fs16">{{getProvinceCityNames($order->shangpai_area)}} </b></span></p>
                <div class="fs14 ml20">
                    <span class="fl mt5">请选择车辆用途：</span>
                    <drop-down class="ml5" v-on:receive-params="getVehicleUse" def-value="非营业个人客车（私家车）" :list="vehicleUseList"></drop-down>
                    <span class="p-gray fl mt5 ml5">（若不是，请更换）</span>
                </div>
                <div class="clear"></div>

                <div v-show="vehicleUse==2" class="fs14 ml20 mt10 div-cate">
                    <p><label><input type="radio" name="yt" id="" value="0"><span class="ml5 noweight">上牌地本地注册企业（增值税一般纳税人）</span></label></p>
                    <p><label><input type="radio" name="yt" id="" value="1"><span class="ml5 noweight">上牌地本地注册企业（小规模纳税人）</span></label></p>
                </div>
                <div class="clear"></div>
                <table v-cloak v-show="vehicleUse==1" width="100%" class="tbl-type fs14 mt10 tbl-category">
                    <tr>
                        <td width="132" valign="top" align="right">
                            <p>车主身份类别：</p>
                        </td>
                        <td class="tal">
                            <p><input v-model="step6.isParentSelect" type="radio" data-id="1" name="shangpai" id="shangpai" value="2" @click="setDefIdentity"><label class="fn" for="shangpai">上牌地本市户籍居民</label></p>
                            <p><input v-model="step6.isParentSelect" type="radio" data-id="2" name="shangpai" id="qita" value="1"><label class="fn" for="qita">其他</label></p>
                            <p class="pl20"><input @click="selectParant(0)" type="radio" data-id="3" name="" value="1" v-model="step6.childVal"><label class="fn" for="">@{{step6.child[0].txt}}</label></p>
                            <div class="pl20">
                                <input @click="selectParant(1)" type="radio" data-id="4" name="" value="2" v-model="step6.childVal" class="fl" />
                                <!--国内其他限牌城市户籍居民-->
                                <label class="fn fl" for="">@{{step6.child[1].txt}}</label>
                                <ul class="city">
                                    <!--国内其他限牌城市户籍居民 北京上海广州天津杭州贵阳深圳苏州-->
                                    <li v-for="(item,index) in step6.child[1].list" @click="selectHouseholdRegistration(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i>@{{item.txt}}</li>
                                    <li class="red" v-show="step6.isAgree && step6.childVal == 2  && step6.householdRegistration == '' ">请选择户籍所在城市</li>
                                    <input type="hidden" name="huji" id="huji" :value="step6.householdRegistration" v-model="step6.householdRegistration">
                                    <div class="clear"></div>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <p class="pl20">
                                <!--中国军人-->
                                <input @click="selectParant(3)" type="radio" data-id="5" name="" value="3" v-model="step6.childVal"><label class="fn" for="">@{{step6.child[2].txt}}</label>
                            </p>
                            <div class="pl20">
                                <!--非中国大陆人士-->
                                <input @click="selectParant(4)" type="radio" data-id="6" name="" value="4" v-model="step6.childVal" class="fl" />
                                <label class="fn fl" for="">@{{step6.child[3].txt}}</label>
                                <ul class="city">
                                    <li v-for="(item,index) in step6.child[3].list" @click="selectForeign(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i>@{{item.txt}}</li>
                                    <li class="red" v-show="step6.isAgree && step6.childVal == 4  && step6.foreign == '' ">请选择您的身份</li>
                                    <input type="hidden" name="waiji" id="waiji" v-model="step6.foreign">
                                    <div class="clear"></div>
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </td>
                    </tr>
                </table>

                <input type="hidden" name="vehicleUse" :value="vehicleUseTxt" v-model="vehicleUseTxt">


                <div class="clear"></div>
                <div class="fs14 ml20 mt20">
                    <span class="fl mt5 ml40">车主名称：</span>
                    <div class="form-group psr fl">
                        <input v-model="ownerName" type="text" placeholder="请输入，例如：苏州华车网络科技有限公司" class="form-control w300" >
                    </div>
                </div>
                <div class="clear"></div>
                <p class="fs14" style="margin-left: 62px;">郑重提示：该名称亦将用作经销商给您开具车辆销售发票的抬头，填写提交后无法随意变更哦！</p>

                <div class="fs14 ml20" v-show="vehicleUse==1" >未来车主预备亲自前往提车吗？
                    <label @click="loadUser">
                        <input v-model="tiche" value="1" :disabled="ownerName == ''" type="radio" name="soure"><span class="noweight">是，亲自去</span>
                    </label>
                    <label class="ml20">
                        <input v-model="tiche" value="0" type="radio" name="soure"><span class="noweight">否，委托他人提车</span>
                    </label>
                </div>

                <div class="fs14 ml20 mt10">
                    <span class="fl mt5">预备前往提车的提车人姓名：</span>
                    <div class="form-group psr fl">
                        <input :disabled="tiche==-1 && vehicleUse==1" v-model="carUserName" type="text" placeholder="请输入" class="form-control" >
                    </div>
                    <span class="fl mt5 ml100 fs14">提车人手机号：</span>
                    <input type="hidden" name="phone" value="{{Auth::user()->phone}}">
                    <div class="form-group psr fl">
                        <input :disabled="tiche==-1 && vehicleUse==1" @focus="initPhone" maxlength="11" v-model="phone" type="text" placeholder="请输入11位数字" class="form-control" >
                    </div>
                    <span class="red ml10 fl fs14 mt5 hide" :class="{show:!isPhone}">请正确输入手机号</span>
                </div>
                <div class="clear"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <p class="red tac hide" :class="{show:isEmpty}">必填内容还不完整呢～</p>
                <div class="tac">
                    <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16">提交</a>
                </div>

                <div id="tipWin" class="popupbox">
                    <div class="popup-title"><span>提交确认</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                               <span class="tip-text mt10">确定提交预约反馈内容吗？</span>
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
                    <div class="popup-title"><span>提交成功</span></div>
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



            </form>

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
<script src="{{asset('/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-confirmation-offer", "/js/module/common/common"],function(v,u,c){
            var _timelist = {!! json_encode($car_times) !!}
            var _timetype = {!!json_encode($type_times)!!}
            u.initTimeList(_timelist)
            u.initStartEndTime("2017-5-2","2017-5-23")
            u.initColorList(_timetype)
        });
    </script>
@endsection