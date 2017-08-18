<!DOCTYPE html>
<html>
<head>
    <title>等待发出交车邀请-用户管理-华车网</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车网" />
    <meta name="keywords" content="华车网" />
    <meta name="author" content="llm" />
    <link href="/themes/bootstrap.css" rel="stylesheet" />
    <link href="/webhtml/common/css/common.css" rel="stylesheet" />
    <link href="/webhtml/custom/themes/custom.css" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="./js/vendor/DatePicker/WdatePicker.js"></script>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    @include('_layout.dealer_header')

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
                        <span class="red">*</span><span class="blue ml5 weight">待处理</span>
                    </h2>
                    <p class="ul-prev">可交车时间（请选择至少1个日期，建议多提供几个以便客户快速决策）</p>
                    <form action="/custom/sendInvitation" method="post" name="time-form">
                      {{ csrf_field() }}
                      <input type="hidden" name="order_id" value="{{$order->id}}">
                      <input type="hidden" name="jiaoche_times" id="jiaoche_time">
                      <div class="time-wrapper">
                          <div v-cloak :class="{hide:timeList.length <= 15}">
                              <a @click="pushLeft" href="javascript:;" class="fl push left-push" :class="{end:marginLeft==0}"></a>
                              <a @click="pushRight" href="javascript:;" class="fr push right-push" v-if="timeList.length>15" :class="{end:timeList.length - 15 == click}"></a>
                          </div>
                          <div class="clear"></div>
                          <ol class="time" :style="{'margin-left':marginLeft+'px'}">
                              <input type="hidden" value="" name="SolicitationTime" id="SolicitationTime">
                              <li v-cloak v-for="time in timeList" :class="{disable:time.disabled}">
                                  <input type="hidden" :name="'time['+time.month+'.'+time.day+']id'" :value="time.monthSelect + '|' + time.amSelect + '|' + time.pmSelect">
                                  <span class="day" :class="{selected:time.monthSelect}">@{{time.month}}-@{{time.day}}</span><span class="am"  :class="{selected:time.amSelect}"    @click="selectAm(time)">上午</span><span class="pm"  :class="{selected:time.pmSelect}"    @click="selectPm(time)">下午</span>
                              </li>
                          </ol>
                      </div>
                      <div class="fs14">
                          <label><input v-model="ckAll" @click="checkAll" type="checkbox" name="" id="ck-all"><span class="ml5">全选</span></label>
                          <label><input v-model="ckWork" @click="checkWorkTime" type="checkbox" name="" id="ck-work-date" class="ml20"><span class="ml5">我的工作日</span></label>
                      </div>
                    </form>
                    <br><br>
                    <p class="center" id="btn-control-wapper">
                        <a href="javascript:;" @click="send" class="btn btn-s-md btn-danger btn-auto">发出交车邀请</a>
                        <a @click="modify" href="javascript:;" class="btn btn-s-md btn-danger btn-auto ml20 btn-white">修改或终止</a>
                    </p>
                    <p class="tac red fs14" v-cloak v-show="!timeSelect">可交车时间还没选哦～</p>

                    <div class="modifydiv hide">
                        <form action="{{route('dealer.store_edit',['id'=>$order->id])}}" method="post" id="user-form-step-1">
                         {{ csrf_field() }}
                         <input type="hidden" name="code" v-model="code">
                          <p class="fs14 m-t-10"><b>请选择：</b></p>
                          <table class="tbl2">
                            <tr>
                              <td valign="top" width="20" class="nopadding">
                                  <input v-model="modifyOrStop" value="false" type="radio" class="jiaocheinput" name="status" id="">
                              </td>
                              <td class="nopadding"><p class="fs14">无法交车，终止订单。</p></td>
                            </tr>
                            <tr>
                              <td valign="top" width="20" class="nopadding">
                                  <input v-model="modifyOrStop" value="true" class="jiaocheinput" checked type="radio" name="status" id="">
                              </td>
                              <td class="nopadding">
                                  <div >
                                      <p class="fs14 ">部分内容修改后（如客户同意）可继续订单。请提交修改内容：</p>
                                      <div class="drop-down-wrapper">
                                          <span class="fl spantitle">车身颜色：</span>
                                          <input type="hidden" name="carinfo[body][old_body_color]" value="@if(isset($editcarinfo['body_color'])){{$editcarinfo['body_color']}}@else{{$baojia['bj_body_color']}}@endif">
                                          <drop-down v-on:receive-params="selectBodyColor" is-high-light="true" def-value="@if(isset($editcarinfo['body_color'])){{$editcarinfo['body_color']}}@else{{$baojia['bj_body_color']}}@endif
                                          " :list="bodyColorList"></drop-down>
                                          <input type="hidden" name="carinfo[body][body_color]" v-model="BodyColor">
                                          <div class="clear m-t-10"></div>
                                      </div>
                                      <div class="drop-down-wrapper">
                                          <span class="fl spantitle">内饰颜色：</span>
                                          <input type="hidden" name="carinfo[inter][old_interior_color]" value="@if(isset($editcarinfo['interior_color'])){{$editcarinfo['interior_color']}}
                                          @else{{$car_info['nside_color']}}
                                          @endif">
                                          <drop-down name="carinfo[inter][interior_color]" v-on:receive-params="selectInterior" is-high-light="true" def-value="
                                          @if(isset($editcarinfo['interior_color'])){{$editcarinfo['interior_color']}}
                                          @else{{$car_info['nside_color']}}
                                          @endif" :list="interiorColorList">
                                          </drop-down>

                                          <input type="hidden" name="carinfo[inter][interior_color]" v-model="Interiorcolor">
                                          <div class="clear m-t-10"></div>
                                      </div>

                                      <div class="drop-down-wrapper">
                                          <span class="fl spantitle">出厂年月：</span>
                                          <input type="hidden" name="carinfo[year][old_year]" value="{{date('Y',strtotime($baojia['bj_producetime']))}}">
                                          <drop-down v-on:receive-params="selectYear" is-high-light="true" class-name="btn-auto btn-sm" def-value="{{date('Y',strtotime($baojia['bj_producetime']))}}年" :list="yearList"></drop-down>
                                          <span class="ml5 fl" id="month">
                                          <input type="hidden" name="carinfo[month][old_month]" value="{{date('m',strtotime($baojia['bj_producetime']))}}">
                                              <drop-down v-on:receive-params="selectMonth2" is-high-light="true" class-name="btn-auto btn-sm" def-value="{{date('m',strtotime($baojia['bj_producetime']))}}月" :list="monthList"></drop-down>
                                          </span>
                                          <input type="hidden" name="carinfo[year][year]" v-model="Year">
                                          <input type="hidden" name="carinfo[month][month]" v-model="Month">
                                          <div class="clear m-t-10"></div>
                                      </div>
                                      <div class="drop-down-wrapper">
                                          <span class="fl spantitle">行驶里程：</span>
                                          <input type="hidden" name="carinfo[licheng][old_licheng]" value="
                                          @if(isset($editcarinfo['mileage'])){{$editcarinfo['mileage']}}
                                          @else{{$baojia['bj_licheng']}}
                                          @endif">
                                          <drop-down  v-on:receive-params="selectKm" is-high-light="true" class-name="btn-auto btn-sm" def-value="
                                          @if(isset($editcarinfo['mileage'])){{$editcarinfo['mileage']}}@else{{$baojia['bj_licheng']}}
                                          @endif" :list="kmList">
                                          </drop-down>
                                          <input type="hidden" name="carinfo[licheng][licheng]" v-model="selectkm">
                                          <span class="fl spantitle ml10">公里</span>
                                          <div class="clear m-t-10"></div>
                                      </div>
                                      <div class="drop-down-wrapper">
                                          <span class="fl spantitle">交车时限：</span>
                                          <input type="hidden" name="carinfo[jiaoche][old_jiaoche_at]" value="{{date('Y年m月d日',strtotime($order->orderDate->jiaoche_at))}}">
                                          <input type="hidden" name="carinfo[jiaoche][jiaoche_at]" v-model='selectDated'>
                                          <drop-down-date v-on:receive-params="selectDate" is-high-light="true" def-value="{{date('Y年m月d日',strtotime($order->orderDate->jiaoche_at))}}" min-date="{{date('Y年m月d日',strtotime($order->orderDate->jiaoche_at))}}" max-date="2099年12月30日" start-date="2017年3月18日"></drop-down-date>
                                          <div class="clear m-t-10"></div>
                                      </div>

                                  </div>

                              </td>
                            </tr>
                          </table>
                    @if($baojia['bj_is_xianche'] && (isset($originals['rpo'])))
                          <p class="fs14 ml20">已装原厂选装精品：</p>

                          <table class="tbl bgtbl ml20">
                              <tr>
                                  <th width="120">名称</th>
                                  <th width="320">型号/说明</th>
                                  <th width="120">厂商指导价</th>
                                  <th width="108">数量</th>
                                  <th width="120">附加价值</th>
                              </tr>
                      @if(!empty($editxzj) && count($editxzj)>0)
                        @foreach($editxzj as $key=>$rpo)
                              <tr data-id="3">
                                  <td class="tac">{{$rpo['xzj_title']}}</td>
                                  <td class="tac">{{$rpo['xzj_model']}}</td>
                                  <td class="tac">￥{{$rpo['xzj_guide_price']}}</td>
                                  <td>
                                      <input type="hidden" name="xzj[{{$key}}][xzj_id]" value="{{$rpo['xzj_id']}}">
                                      <input type="hidden" name="xzj[{{$key}}][xzj_title]" value="{{$rpo['xzj_title']}}">
                                      <input type="hidden" name="xzj[{{$key}}][xzj_guide_price]" value="{{$rpo['xzj_guide_price']}}">
                                      <input type="hidden" name="xzj[{{$key}}][old_num]" value="{{$rpo['num']}}">
                                      <input type="hidden" name="xzj[{{$key}}][xzj_model]" value="{{$rpo['xzj_model']}}">
                                      <div class="xuan">
                                          <div class="x-w">
                                              <a @click="prev" class="prev">-</a>
                                              <input data-price="{{$rpo['xzj_guide_price']}}" type="text" readonly value="{{$rpo['num']}}" class="input" def-value="0" name="xzj[{{$key}}][num]">
                                              <a @click="next($event,{{$rpo['num']}})" class="next">+</a>
                                          </div>
                                      </div>
                                  </td>
                                  <td class="tac">￥{{number_format($rpo['xzj_guide_price']*$rpo['num'],2)}}</td>
                              </tr>
                              @endforeach
                        @else
                          @foreach($originals['rpo'] as $key=>$rpo)
                              <tr data-id="4">
                                  <td class="tac">{{$rpo['xzj_title']}}</td>
                                  <td class="tac">{{$rpo['xzj_model']}}</td>
                                  <td class="tac">
                                    {{formatMoney(<?php echo $rpo['xzj_guide_price'] ?>,2,"￥")}}
                                  </td>
                                  <td>
                                      <div class="xuan">
                                          <input type="hidden" name="xzj[{{$key}}][xzj_id]" value="{{$rpo['xzj_id']}}">
                                          <input type="hidden" name="xzj[{{$key}}][xzj_title]" value="{{$rpo['xzj_title']}}">
                                          <input type="hidden" name="xzj[{{$key}}][xzj_guide_price]" value="{{$rpo['xzj_guide_price']}}">
                                          <input type="hidden" name="xzj[{{$key}}][old_num]" value="{{$rpo['num']}}">
                                          <input type="hidden" name="xzj[{{$key}}][xzj_model]" value="{{$rpo['xzj_model']}}">
                                          <div class="x-w">
                                              <a @click="prev" class="prev">-</a>
                                              <input data-price="{{$rpo['xzj_guide_price']}}" readonly  type="text" value="{{$rpo['num']}}" class="input" def-value="0" name="xzj[{{$key}}][num]">
                                              <a @click="next($event,{{$rpo['num']}})" class="next">+</a>
                                          </div>
                                      </div>
                                  </td>
                                  <td class="tac">￥{{number_format($rpo['xzj_guide_price']*$rpo['num'],2)}}</td>
                              </tr>
                              @endforeach
                          @endif
                          </table>
                          <p class="ml20">
                              <small class="wp45 fr tar di mr150"><span>合计价值：<label></label></span></small>
                              <input type="hidden" name="price">
                          </p>
                      @endif
                @if(count($order->orderServer) || count($editzengpin)>0)
                          <p class="fs14 ml20">免费礼品和服务：</p>
                          <table class="tbl bgtbl ml20 bgtbl-mini">
                              <tr>
                                  <th>名称</th>
                                  <th>数量</th>
                                  <th>状态</th>
                              </tr>
                      @if($editzengpin && count($editzengpin)>0)
                            @foreach($editzengpin as $key=>$orderserve)
                              <tr data-id="3">
                                  <td>{{$orderserve['zp_title']}}</td>
                                  <td align="center">
                                      <div class="xuan">
                                          <input type="hidden" name="zengpin[{{$key}}][zp_title]" value="{{$orderserve['zp_title']}}">
                                          <input type="hidden" name="zengpin[{{$key}}][old_num]" value="{{$orderserve['num']}}">
                                          <input type="hidden" name="zengpin[{{$key}}][zp_status]" value="{{$orderserve['zp_status']}}">
                                          <div class="x-w">
                                              <a @click="prev2" class="prev">-</a>
                                              <input type="text" readonly value="{{$orderserve['num']}}" name="zengpin[{{$key}}][num]" def-value="0" class="input">
                                              <a @click="next2($event,{{$orderserve['num']}})" class="next">+</a>
                                          </div>
                                      </div>
                                  </td>
                                  <td class="tac">
                                    <p class="fs14">
                                    @if($orderserve['zp_status'])
                                    已安装
                                    @else
                                    /
                                    @endif
                                    </p>
                                  </td>
                              </tr>
                              @endforeach
                        @else
                             @foreach($order->orderServer as $key=>$orderserve)
                              <tr data-id="3">
                                  <td>{{$orderserve->zp_title}}</td>
                                  <td align="center">
                                      <div class="xuan">
                                          <input type="hidden" name="zengpin[{{$key}}][zp_title]" value="{{$orderserve->zp_title}}">
                                          <input type="hidden" name="zengpin[{{$key}}][old_num]" value="{{$orderserve->num}}">
                                          <input type="hidden" name="zengpin[{{$key}}][zp_status]" value="{{$orderserve->is_instal}}">
                                          <div class="x-w">
                                              <a @click="prev2" class="prev">-</a>
                                              <input type="text" readonly value="{{$orderserve->num}}" name="zengpin[{{$key}}][num]" def-value="0" class="input">
                                              <a @click="next2($event,{{$orderserve->num}})" class="next">+</a>
                                          </div>
                                      </div>
                                  </td>
                                  <!--//计算技术-->
                                  <td class="tac">
                                    <p class="fs14">
                                       @if($orderserve->is_instal)
                                          已安装
                                          @else
                                          /
                                          @endif
                                    </p>
                                  </td>
                              </tr>
                      @endforeach
                    @endif
                    </table>
                  @endif
                          <br><br>
                          <div class="tac">
                            <input type="checkbox" v-model="agreeList" value="1" checked="" name="" id=""><span class="fn fs14">同意支付歉意金赔偿</span>
                          </div>
                          <div class="center mt10">
                            <a href="javascript:;" @click="sureModify" class="btn btn-s-md btn-danger fs16 btn-white">确认变更订单</a>
                            <a href="javascript:;" @click="noModify" class="btn btn-s-md btn-danger fs16 ml20 btn-empty">放弃修改，返回</a>
                          </div>
                          <p class="tac red m-t-10" v-show="!agree && isSend">
                            变更订单须同意支付相关赔偿！
                          </p>
                          <p class="tac red m-t-10" v-show="agree && isSend && noChange">
                            无变更内容，请返回！
                          </p>
                        </form>
                    </div>

            </div>

        </div>

        <div id="modifyWin" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac succeed constraint constraint-large">
                       <span class="tip-tag bp0"></span>
                       <span class="tip-text">
                       修改订单，将立即赔偿客户：<br>
                       1.歉意金￥499.00；<br>
                       2.客户买车担保金利息￥566.98！<br>
                       如客户不接受修改而终止订单，还<br>
                       将赔偿华车平台所有损失￥868.78！<br><br>
                       确定接受上述赔偿继续修改订单吗？
                       </span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
               </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doModifySure" class="btn btn-s-md btn-danger fs14 w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml50">取消</a>
                    <div class="clear"></div>

                </div>
            </div>
        </div>

        <div id="stopWin" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac succeed constraint constraint-large">
                       <span class="tip-tag bp0"></span>
                       <span class="tip-text">
                       终止订单，将立即赔偿：<br>
                       1.歉意金￥499.00；<br>
                       2.客户买车担保金利息￥566.98！<br>
                       3.华车平台所有损失￥868.78！<br>
                       <br>
                       确定接受上述赔偿且终止订单吗？
                       </span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
               </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="stopOrder" class="btn btn-s-md btn-danger fs14 w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100  ml50">取消</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div id="sendWin" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac">
                       <br>
                       <span class="tip-text fs14 text-left inline-block">确定向客户发出交车邀请吗？</span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>
                </div>
            </div>
          </div>

          <div id="sendCodeWin" class="popupbox">
            <div class="popup-title">验证</div>
            <div class="popup-wrapper">
            <form  id="stopOrder" method="post">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 tac">
                       <br>
                       <span class="tip-text fs14 text-left inline-block">
                           为保证操作安全，已向您的手机{{changeMobile($order->orderMember->member_mobile)}}  <br>
                           发送短信验证码，请提交核实，谢谢～ <br><br>
                           <phone-code sendurl="/dealer/order/getcode" type="POST" token="{{csrf_token()}}" v-on:send-status="sendEnd" v-on:valite-code="getCode"  sendtype="78570085" sn="{{$order->order_sn}}" phone="{{$order->orderMember->member_mobile}}"></phone-code>
                           <div class="clear"></div>
                           <p :class="{inputerror:true, 'normal-warn':true, ml50:true, red:true, hide:!error ,fs14:true}">验证码有误，请重新输入~</p>
                       </span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                </form>
                <div class="popup-control">
                    <a href="javascript:;" @click="doSendCode" class="btn btn-s-md btn-danger fs14 do w100 ">提交</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>
                </div>
            </div>
          </div>

    </div>
    </div>

    @include('HomeV2._layout.footer')
    @include('HomeV2._layout.login')


    <script src="/js/sea.js"></script>
    <script src="/js/config.js"></script>
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
      Config = {
      'routes':{
        'stopurl' : '{{route('dealer.stoporder_two',['id'=>$order->id])}}',
        'submiturl' : '{{route('dealer.store_edit',['id'=>$order->id])}}'
      }
    }
        seajs.use(["/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-order-prepare-prev", "module/common/common"],function(v,u,c){
            u.init('{{date('Y-m-d H:i:s',time())}}',@if($order->orderDate->status)'{{date('Y-m-d H:i:s',strtotime("-7 days",strtotime($order->orderDate->jiaoche_at)))}}'@else'{{date('Y-m-d H:i:s',time())}}'@endif,function(){
              //设置回调
            })
            u.initBodyColor([
              @foreach($colors['body'] as $body)
              {!!json_encode($body)!!},
              @endforeach
              ])
            u.initInteriorColor([
              @foreach($colors['inter'] as $inter)
              {!!json_encode($inter)!!},
              @endforeach
              ])
              @if(isset($editcarinfo['year_month']))
            u.initYearMonth('{{$editcarinfo['year_month']}}')
              @else
            u.initYearMonth({{date('Y,m',strtotime($baojia['bj_producetime']))}})
              @endif
            u.initKm(@if(isset($editcarinfo['mileage'])){{$editcarinfo['mileage']}}@else{{$baojia['bj_licheng']}}@endif)
            u.initTimeList({!! json_encode($time) !!})
            u.setWorkList({!! json_encode($work) !!})
        })
    </script>

</body>
</html>
