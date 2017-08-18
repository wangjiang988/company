@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                     
                    <form action="/dealer/work_time" method="post">
                        <div class="content-wapper ">
                          
                           <div class="m-t-10"></div>
                           <div class="report-form
                             @if(count($data)<>0)
                            none
                            @endif
                           ">
                               <dl class="work-list-wrapper">
                                   <dt class="fl">可报价常规日期(可多选)：    </dt>
                                   <dd class="fl day">
                                       <lable><input type="checkbox" name="day_1" id="" value="1"><span>周一</span></lable>
                                       <lable><input type="checkbox" name="day_2" id="" value="1"><span>周二</span></lable>
                                       <lable><input type="checkbox" name="day_3" id="" value="1"><span>周三</span></lable>
                                       <lable><input type="checkbox" name="day_4" id="" value="1"><span>周四</span></lable>
                                       <div class="clear"></div>
                                       <lable><input type="checkbox" name="day_5" id="" value="1"><span>周五</span></lable>
                                       <lable><input type="checkbox" name="day_6" id="" value="1"><span>周六</span></lable>
                                       <lable><input type="checkbox" name="day_7" id="" value="1"><span>周日</span></lable>
                                       <lable><input id="checkall" type="checkbox" name=""><span>全选</span></lable>
                                        <input type='hidden' name='_token' value="{{csrf_token()}}">

                                   </dd>
                                   <div class="clear"></div>
                                   <div class="error-div tac m-t-10"><label class="red">*请选择可报价常规日期</label></div>
                               </dl>

                               <div class="m-t-10"></div>
                               <dl class="work-list-wrapper">
                                   <dt class="fl">报价日报价有效时段：</dt>
                                   <dd class="fl ">
                                       <span>上午开始时间</span>
                                       <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input value="09:00" type="text" placeholder="09:00" class="form-control work-control am" name="am_start">
                                            <i class="icon-times"></i>
                                          </div>
                                       </div>
                                       <span class="ml20">上午结束时间</span>
                                       <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input value="12:00" type="text" placeholder="12:00" class="form-control work-control am end" name="am_end">
                                            <i class="icon-times"></i>
                                          </div>
                                       </div>
                                       <div class="split-blue"></div>
                                       <div class="clear"></div>
                                       <span>下午开始时间</span>
                                       <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input value="13:00" type="text" placeholder="13:00" class="form-control work-control pm" name="pm_start">
                                            <i class="icon-times"></i>
                                          </div>
                                       </div>
                                       <span class="ml20">下午结束时间</span>
                                       <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input value="17:00" type="text" placeholder="17:00" class="form-control work-control pm end" name="pm_end">
                                            <i class="icon-times"></i>
                                          </div>
                                       </div>
                                   </dd>
                                   <div class="clear"></div>
                                   <div class="error-div tac m-t-10"><label class="red">*请选择报价日报价有效时段</label></div>
                                   <div class="error-div tac m-t-10"><label class="red">*结束时间不得早于开始时间，请重新选择~</label></div>
                               </dl>

                               <div class="m-t-10"></div>
                               <dl class="work-list-wrapper">
                                   <dt class="fl">不报价休息日程：</dt>
                                   <dd class="fl ">
                                       <div class="no-offer">
                                           <span>1. 开始时间</span>
                                           <div class="btn-group m-r time-sl">
                                              <div class="form-group psr pdi-control">
                                                <input readonly="" name="rest_1_start" type="text" placeholder="2017-01-01" value="2017-01-01" class="form-control select-time-control start" onfocus="WdatePicker({startDate:'2017-01-01',dateFmt:'yyyy-MM-dd' });">
                                                <i class="rili"></i>
                                              </div>
                                           </div>
                                           <span class="ml20">结束时间</span>
                                           <div class="btn-group m-r time-sl">
                                              <div class="form-group psr pdi-control">
                                                <input readonly="" name="rest_1_end" type="text" placeholder="2017-01-01" value="2017-01-01" class="form-control select-time-control end" onfocus="WdatePicker({startDate:'2017-01-01',dateFmt:'yyyy-MM-dd' });">
                                                <i class="rili"></i>
                                              </div>
                                           </div>
                                           <a href="#" class="juhuang tdu reset">重置</a>
                                       </div>
                                       <div class="split-blue"></div>
                                       <div class="clear"></div>
                                       <div class="no-offer">
                                           <span>2. 开始时间</span>
                                           <div class="btn-group m-r time-sl">
                                              <div class="form-group psr pdi-control">
                                                <input readonly="" name="rest_2_start" type="text" placeholder="2017-01-01" value="2017-01-01" class="form-control select-time-control start" onfocus="WdatePicker({startDate:'2017-01-01',dateFmt:'yyyy-MM-dd' });">
                                                <i class="rili"></i>
                                              </div>
                                           </div>
                                           <span class="ml20">结束时间</span>
                                           <div class="btn-group m-r time-sl">
                                              <div class="form-group psr pdi-control">
                                                <input readonly="" name="rest_2_end" type="text" placeholder="2017-01-01" value="2017-01-01" class="form-control select-time-control end" onfocus="WdatePicker({startDate:'2017-01-01',dateFmt:'yyyy-MM-dd' });">
                                                <i class="rili"></i>
                                              </div>
                                           </div>
                                           <a href="#" class="juhuang tdu reset">重置</a>
                                       </div>
                                       
                                       <div class="error-div tac m-t-10"><label class="red">*设置的时间有误，请重新选择~</label></div>
                                       <div class="error-div tac"><label class="red">*结束时间不得早于开始时间，请重新选择~</label></div>
                                       <div class="error-div tac"><label class="red">*不报价休息日程的日期不能有交叉，请重新选择</label></div>

                                   </dd>
                                   <div class="clear"></div>

                               </dl>
                               <div class="m-t-10"></div>
                               <div class="m-t-10"></div>
                               <p class="tac mt10">
                                   <a href="javascript:;" class="btn btn-danger fs16 btn-work">确认</a>
                                   <a href="javascript:;" class="btn btn-danger fs16 sure ml50">返回</a>
                                </p>
                           </div>

                            <div class="report-result 
                            @if(count($data)==0)
                            none
                            @endif
                            ">
                                <p><b><span class="blue">当前日期：</span><span id="time-span">2016-03-09 周四</span></b></p>
                                <table class="tbl custom-info-tbl">
                                 <input type="hidden" name="dealer_id" value="{{$dealer_id}}">
                                @if(count($data)>0)
                                    <tr>
                                        <td width="180" class="tac"><label class="nomargin">可报价常规日期</label></td>
                                        <td width="595">
                                            <dl class="work-list-wrapper work-list-wrapper-split">
                                               <dd class="fl day">                          
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_1'] == 1) checked="checked" @endif 
                                                   ><span>周一</span></lable>
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_2'] == 1) checked="checked" @endif
                                                   ><span>周二</span></lable>
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_3'] == 1) checked="checked" @endif
                                                   ><span>周三</span></lable>
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_4'] == 1) checked="checked" @endif
                                                   ><span>周四</span></lable>
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_5'] == 1) checked="checked" @endif
                                                   ><span>周五</span></lable>
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_6'] == 1) checked="checked" @endif
                                                   ><span>周六</span></lable>
                                                   <lable><input type="checkbox" name="" id="" disabled
                                                   @if($data['day_7'] == 1) checked="checked" @endif
                                                   ><span>周日</span></lable>
                                               </dd>
                                               <div class="clear"></div>
                                            </dl>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td width="180" class="tac"><label class="nomargin">报价日报价有效时段</label></td>
                                        <td width="595">
                                             <div class="row">
                                                 <div class="col-md-3 col-xs-3  ml10">上午开始 <span class="time-dlp">{{$data['am_start']}}</span></div>
                                                 <div class="col-md-3 col-xs-3">上午结束 <span class="time-dlp">{{$data['am_end']}}</span></div>
                                             </div>
                                             <div class="row mt10">
                                                 <div class="col-md-3 col-xs-3  ml10">下午开始  <span class="time-dlp">{{$data['pm_start']}}</span></div>
                                                 <div class="col-md-3 col-xs-3">下午结束 <span class="time-dlp">{{$data['pm_end']}}</span></div>
                                             </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="180" class="tac"><label class="nomargin">不报价休息日程</label></td>
                                        <td width="595">
                                             <div class="row">
                                                 <div class="col-md-4 col-xs-4  ml10">1. 开始日 <span class="time-dlp-long">{{$data['rest_1_start']}}</span> </div>
                                                 <div class="col-md-4 col-xs-4">结束日  <span class="time-dlp-long">{{$data['rest_1_end']}}</span></div>
                                             </div>
                                             <div class="row mt10">
                                                 <div class="col-md-4 col-xs-4  ml10">2. 开始日 <span class="time-dlp-long">{{$data['rest_2_start']}}</span> </div>
                                                 <div class="col-md-4 col-xs-4">结束日 <span class="time-dlp-long">{{$data['rest_2_end']}}</span></div>
                                             </div>                                            
                                        </td>
                                    </tr>
                                 @else
                                    <tr>
                                        <td width="180" class="tac"><label class="nomargin">可报价常规日期</label></td>
                                        <td width="595">
                                            <dl class="work-list-wrapper work-list-wrapper-split">
                                               <dd class="fl day">
                                                   <lable><input type="checkbox" name="" id=""><span>周一</span></lable>
                                                   <lable><input type="checkbox" name="" id=""><span>周二</span></lable>
                                                   <lable><input type="checkbox" name="" id=""><span>周三</span></lable>
                                                   <lable><input type="checkbox" name="" id=""><span>周四</span></lable>
                                                   <lable><input type="checkbox" name="" id=""><span>周五</span></lable>
                                                   <lable><input type="checkbox" name="" id=""><span>周六</span></lable>
                                                   <lable><input type="checkbox" name="" id=""><span>周日</span></lable>
                                               </dd>
                                               <div class="clear"></div>
                                            </dl>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td width="180" class="tac"><label class="nomargin">报价日报价有效时段</label></td>
                                        <td width="595">
                                             <div class="row">
                                                 <div class="col-md-3 col-xs-3  ml10">上午开始 <span class="time-dlp">9:00</span></div>
                                                 <div class="col-md-3 col-xs-3">上午结束 <span class="time-dlp">12:00</span></div>
                                             </div>
                                             <div class="row mt10">
                                                 <div class="col-md-3 col-xs-3  ml10">下午开始  <span class="time-dlp">13:00</span></div>
                                                 <div class="col-md-3 col-xs-3">下午结束 <span class="time-dlp">17:00</span></div>
                                             </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="180" class="tac"><label class="nomargin">不报价休息日程</label></td>
                                        <td width="595">
                                             <div class="row">
                                                 <div class="col-md-4 col-xs-4  ml10">1. 开始日 <span class="time-dlp-long">2017-01-01</span> </div>
                                                 <div class="col-md-4 col-xs-4">结束日  <span class="time-dlp-long">2017-01-01</span></div>
                                             </div>
                                             <div class="row mt10">
                                                 <div class="col-md-4 col-xs-4  ml10">2. 开始日 <span class="time-dlp-long">2017-01-01</span> </div>
                                                 <div class="col-md-4 col-xs-4">结束日 <span class="time-dlp-long">2017-01-01</span></div>
                                             </div>
                                        </td>
                                    </tr>
                                @endif
                                </table>
                                <p class="text-right"><a href="javascript:;" class="juhuang tdu modify-work">修改</a></p>
                            </div>
                            
                            
                        </div>
                        
                    </form>
                    <div class="m-t-200"></div>
                
                  <div id="SelectTimeWin" class="popupbox" am-start="09:00" pm-start="13:00" pm="0">
                      <div class="popup-title">选择时间</div>
                      <div class="popup-wrapper">
                          <div class="popup-content">
                               <div class="m-t-10"></div>
                               <div class="time-fn-box">
                                   <div class="time-show-wrapper time-hours fl">
                                       <div class="minus">-</div>
                                       <div class="center-c">
                                           <div class="center-split"></div>
                                           <span class="center-txt double">12</span>
                                       </div>
                                       <div class="add">+</div>
                                   </div>
                                   <div class="colon fl">:</div>
                                   <div class="time-show-wrapper fl">
                                       <div class="minus">-</div>
                                       <div class="center-c">
                                           <div class="center-split"></div>
                                           <span class="center-txt min" min="0" max="5">0</span>
                                       </div>
                                       <div class="add">+</div>
                                   </div>
                                   <div class="time-show-wrapper fl ml10">
                                       <div class="minus">-</div>
                                       <div class="center-c">
                                           <div class="center-split"></div>
                                           <span class="center-txt bit" min="0" max="9">0</span>
                                       </div>
                                       <div class="add">+</div>
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
                <div class="clear"></div>
            </div>
        </div>

@endsection

@section('js')
<script type="text/javascript">
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.work.jquery","vendor/DatePicker/WdatePicker.js","module/common/common", "bt"],function(a,b,c){
         
        });
</script>
    
@endsection




