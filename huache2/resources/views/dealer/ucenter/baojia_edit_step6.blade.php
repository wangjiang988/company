@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <div class="custom-offer-step-wrapper">
                        <ul>
                          <li>车型价格</li>
                          <li>车况说明</li>
                          <li>选装精品</li>
                          <li class="last-prev">收费标准</li>
                          <li class="last last-cur">其他事项</li>
                          <div class="clear"></div>
                        </ul>
                    </div>


                    <form action="/dealer/baojia/edit/{{$baojia['bj_id']}}/6" name="baojia-submit-form" method="post">
                    	<input type='hidden' name='_token' value="{{csrf_token()}}">
                        <div class="content-wapper ">
                           <div class="box">
                               <p><span class="blue weight">一、商业保险</span></p>
                               <div class="tbl-list-tool-panle valite-1">
                                <p class="fs14 ml20">客户自由投保</p>
                           </div>
                           <div class="box">
                               <p><span class="blue weight">二、移交内容</span></p>
                               <div class="tbl-list-tool-panle">
                                  <p class="ml20"><b>随车工具：</b>
                                  @foreach($suicheInfo['tools'] as $tool)
                                  {{$tool['title']}}
                                  @if (!$loop->last)
                                   、
                                   @endif
                                  @endforeach
                                  </p>
                                  <p class="ml20"><b>随车移交文件：</b>
                                  @foreach($suicheInfo['files'] as $file)
                                  {{$file['title']}}
                                  @if (!$loop->last)
                                   、
                                   @endif
                                  @endforeach
                                  </p>
                                  <p class="ml10">以上如有疑问，请<a href="#" class="juhuang tdu">联系我们</a></p>
                               </div>
                           </div>
                           <div class="box">
                               <p><span class="blue weight">三、生效设置</span></p>
                               <label class="radio-label ml50 noweight">
                                 <input type="radio" name="fulltime" id="" value='1' checked>
                                 <span>立即生效，始终有效
                                   <span class="smip-gray">（设置工作时间之外、资金池可提现余额不足、主动下架操作、被客户购买后库存为
                                      <span class="ml17">零，则报价信息将自动下</span>
                                   </span>
                                 </span>
                               </label>
                               <label class="radio-label ml50 noweight">
                                 <input type="radio" class="time-set" name="fulltime" value='0' id="" >
                                 <span>按设置生效及有效时间
                                   <span class="smip-gray">（设置工作时间之外、资金池可提现余额不足、主动下架操作、被客户购买后库存
                                      <span class="ml17">为零，则报价信息将自动下架）</span>
                                   </span>
                                 </span>
                               </label>
                               <div class="no-offer ml50">
                                   <span class="fl mt5">开始时间</span>
                                   <div class="btn-group m-r time-sl fl ml10">
                                      <div class="form-group psr pdi-control">
                                        <input readonly="" type="text" placeholder="" class="form-control select-time-control start" onfocus="WdatePicker({startDate:'1900-01-01',dateFmt:'yyyy-MM-dd' });" name='start_time_1' value="{{time()>strtotime(date('Y-m-d').' 17:00:00')?date('Y-m-d',strtotime('+1 day')):date('Y-m-d')}}">
                                        <i class="rili"></i>
                                      </div>
                                   </div>
                                   <div class="btn-group m-r time-sl fl ml20">
                                      <div class="form-group psr pdi-control">
                                        <input  type="text" placeholder="{" class="form-control work-control am" value="{{time()>strtotime(date('Y-m-d').' 17:00:00')?'09:00':date('H:i')}}" name='start_time_2'>
                                        <i class="icon-times"></i>
                                      </div>
                                   </div>
                                   <div class="clear"></div>
                               </div>
                               <div class="no-offer ml50">
                                   <span class="fl mt5">结束时间</span>
                                   <div class="btn-group m-r time-sl fl ml10">
                                      <div class="form-group psr pdi-control">
                                        <input readonly="" type="text" placeholder="" class="form-control select-time-control start" onfocus="WdatePicker({startDate:'1900-01-01',dateFmt:'yyyy-MM-dd' });" value="{{time()>strtotime(date('Y-m-d').' 17:00:00')?date('Y-m-d',strtotime('+1 day')):date('Y-m-d')}}" name='end_time_1'>
                                        <i class="rili"></i>
                                      </div>
                                   </div>
                                   <div class="btn-group m-r time-sl fl ml20">
                                      <div class="form-group psr pdi-control">
                                        <input type="text" placeholder="" class="form-control work-control am" value="17:00" name='end_time_2'>
                                        <i class="icon-times"></i>
                                      </div>
                                   </div>
                                   <div class="clear"></div>
                               </div>
                               <div class="error-div tac" id="timerror"><label class="red">*结束时间不得早于开始时间，请重新选择~</label></div>
                           </div>

                           <div class="box">
                               <div class="mt20">
                                  <span class="blue weight">四、台数设置</span>

								 @if(!$baojia['bj_dealer_internal_id'])
                                  <div class="ml20 checkbox-wrapper inline counter-wrapper">
                                      <span class="prev tac">-</span>
                                      <input class="" type="text" name="bj_num" id="" value="{{$baojia['bj_num']}}" max="9999">
                                      <span class="next tac">+</span>
                                  </div>
                                  @else
                                  <!--//2.“经销商内部车辆编号（选填）” 为空时，显示为\\-->
                                  <div class="ml20 checkbox-wrapper inline counter-wrapper">
                                      <input class="" disabled='' type="text" name="bj_num" id="" value="1" max="1">  </div>
                                  @endif


                               </div>
                               <div class="tbl-list-tool-panle">
                                  <p class="ml20 mt10"><span class="smip-gray">(相同报价条件可设置多台，当一台被客户购买后自动扣减库存数，库存数不为零则报价不失效。）</span></p>
                               </div>
                           </div>

                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                           <p class="tac">
                           		<a href="/dealer/baojia/edit/{{$baojia['bj_id']}}/5" class="btn btn-danger sure fs18 ml20">返回上一步</a>
                                <a href="javascript:;" class="btn btn-danger fs18 ml20 baojia-submit-button" data-step='6' data-type='1'>提交</a>
                           	  	<a href="javascript:;" class="btn btn-danger sure fs18 ml20 reset-form">重置</a>
                            </p>

                        </div>

                    </form>
                    <div class="m-t-200"></div>

                    <div id="SelectTimeWin" class="popupbox" am-start="09:00" pm-start="13:00">
                      <div class="popup-title">选择时间</div>
                      <div class="popup-wrapper">
                          <div class="popup-content">
                               <div class="m-t-10"></div>
                               <div class="time-fn-box">
                                   <div class="time-show-wrapper time-hours fl">
                                       <div class="minus">-</div>
                                       <div class="center-c">
                                           <div class="center-split"></div>
                                           <span class="center-txt double"  min="0" max="24" id="time-hour-str">12</span>
                                       </div>
                                       <div class="add">+</div>
                                   </div>
                                   <div class="colon fl">:</div>
                                   <div class="time-show-wrapper fl"> 
                                       <div class="center-c mt25">
                                           <div class="center-split"></div>
                                           <span class="center-txt" min="0" max="5" id="time-minute-str-1">0</span>
                                       </div> 
                                   </div>
                                   <div class="time-show-wrapper fl ml10" > 
                                       <div class="center-c mt25">
                                           <div class="center-split"></div>
                                           <span class="center-txt" min="0" max="9" id="time-minute-str-2">0</span>
                                       </div> 
                                   </div>
                                   <div class="clear"></div>
                               </div>
                               <div class="m-t-10"></div>
                               <div class="error-div tac" id="time-select-error">
                                   <label class="red">*结束时间不得早于开始时间，请重新选择~</label>
                               </div>

                          </div>
                          <div class="popup-control">
                              <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确定</a>
                              <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                              <div class="clear"></div>
                          </div>
                      </div>
                  </div>





                </div>

@endsection

@section('js')
<script src="{{asset('js/vendor/DatePicker/WdatePicker.js')}}"></script>
<script type="text/javascript">
seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.matters.jquery", "module/common/common", "bt"],function(a,b,c){
    $(".counter-wrapper").modifiedBox()
  });
</script>
@endsection