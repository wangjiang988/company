﻿@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step">
                             <li><span>基本资料</span></li>
                             <li><span>服务专员</span></li>
                             <li><span>上牌条件</span></li>
                             <li><span>临牌条件</span></li>
                             <li><span>免费提供</span></li>
                             <li><span>杂费标准</span></li>
                             <li class="prev"><span>刷卡标准</span></li>
                             <li class="last cur cur-step last-step"><span>竞争分析</span></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">竞争分析</h2>
                       <div class="m-t-10"></div>
                       <p><b>位置最近的两家竞争4S店：</b></p>
                       <table class="tbl custom-info-tbl tbl-competitive">
                         <tbody>
                           <tr>
                               <th class="tac" width="170">地区</th>
                               <th class="tac" width="315">经销商名称</th>
                               <th class="tac" width="156">营业地点</th>
                               <th class="last" width="94"> 
                                  操作
                               </th>
                           </tr> 
 
                           <!--//循环输出-->
                             <tr class="def-temp">
                                  @if(!empty($analysis['one']))
                             <td class="tac" width="" valign="middle">
                                <div class="form-txt psr inlineblock none">
                                   <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                        <div class="province-wrapper btn btn-sm btn-default dropdown-toggle area-drop-btn" style="width:150px;">
                                            <span class="dropdown-label"><span>--请选择--</span></span>
                                            <span class="caret"></span>
                                        </div>
                                        <div class="dropdown-menu dropdown-select area-tab-div">
                                            <input type="hidden" name="province" value="t" />
                                            <input type="hidden" name="city" value="t" />
                                            <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                            <dl class="dl">
                                            @foreach($area as $are=>$ar)
                                                <dd data-id="{{$are}}">{{$ar['name']}}</dd>       
                                             @endforeach
                                              <div class="clear"></div>
                                            </dl>
                                            <dl class="dl test" style="display: none;">
                                            </dl>
                                            <div class="clear"></div>
                                        </div>
                                      </div>
                                   </div>

                                <label class="save-label">{{$analysis['one']->d_areainfo}}</label>

                                

                             </td>
                             <label class="dl_types"><input type="hidden" name="dealer" value="{{$daili['d_id']}}"></label>
                             <td class="tac" width="" valign="middle">
                                <div class="btn-group none  btn-group-auto">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company"  style="width:180px;">
                                        <span class="dropdown-label"><span>--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company top32">
                                    </ul>
                                   
                                </div>
                                @if(count($analysis)>0)
                                <label class="save-label">{{$analysis['one']->d_name}}</label>
                                @endif
                             </td>
                             <td class="tac" width="">
                                 <span>{{$analysis['one']->d_jc_place}}</span>                                
                                 &nbsp;
                             </td>
                             <td class="tac" width="">
                                 <div class="inline">
                                   <a href="javascript:;" is-add="1" class="btn btn-danger save none city " data-type= "1" data-id="" data-shi="">确定</a>
                                   <a href="javascript:;" class="ml10 edit-new">修改</a>
                                 </div>
                                 &nbsp;
                             </td> 
                              @else
                               <!--//循环输出-->
                            <tr class="def-temp">
                             <td class="tac" width="" valign="middle">
                                <div class="form-txt psr inlineblock">
                                   <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                        <div class="province-wrapper btn btn-sm btn-default dropdown-toggle area-drop-btn" style="width:150px;">
                                            <span class="dropdown-label"><span>--请选择--</span></span>
                                            <span class="caret"></span>
                                        </div>
                                        <div class="dropdown-menu dropdown-select area-tab-div">
                                            <input type="hidden" name="province" />
                                            <input type="hidden" name="city" />
                                            <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                            <dl class="dl">
                                            @foreach($area as $are=>$ar)
                                                <dd data-id="{{$are}}">{{$ar['name']}}</dd>       
                                             @endforeach
                                              <div class="clear"></div>
                                            </dl>
                                            <dl class="dl test" style="display: none;">
                                            </dl>
                                            <div class="clear"></div>
                                        </div>
                                      </div>
                                   </div>

                                <label class="save-label none"><input type="hidden" name="d_areainfo" value="t"></label>                          
                                <label class="dl_types"><input type="hidden" name="dealer" value="{{$daili['d_id']}}"></label>

                             </td>
                             <td class="tac" width="" valign="middle">
                                <div class="btn-group btn-group-auto">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company"  style="width:180px;">
                                        <span class="dropdown-label"><span>--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company">
                                    </ul> 
                                </div> 
                                <label class="save-label none">&nbsp;</label> 
                             </td>
                             <td class="tac" width="">
                                 <span></span>                                
                                 &nbsp;
                             </td> 
                             <td class="tac" width="">
                                 <div class="inline">
                                   <a href="javascript:;" class="btn btn-danger save city " data-type= "1" data-id="" data-shi="">确定</a>
                                   <a href="javascript:;" class="ml10 edit-new none">修改</a>
                                 </div>
                                 &nbsp;
                             </td>                      
                             @endif                   
                       
                           </tr>

                           <tr class="def-temp">
                           @if(!empty($analysis['two']))
                             <td class="tac" width="" valign="middle">
                                <div class="form-txt psr inlineblock none">
                                   <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                        <div class="province-wrapper btn btn-sm btn-default dropdown-toggle area-drop-btn" style="width:150px;">
                                            <span class="dropdown-label"><span>--请选择--</span></span>
                                            <span class="caret"></span>
                                        </div>
                                        <div class="dropdown-menu dropdown-select area-tab-div">
                                            <input type="hidden" name="province" value="t" />
                                            <input type="hidden" name="city" value="t" />
                                            <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                                   <dl class="dl">
                                            @foreach($area as $are=>$ar)
                                                <dd data-id="{{$are}}">{{$ar['name']}}</dd>       
                                             @endforeach
                                              <div class="clear"></div>
                                            </dl>
                                            <dl class="dl test" style="display: none;">
                                            </dl>
                                            <div class="clear"></div>
                                        </div>
                                      </div>
                                   </div>

                                <label class="save-label">{{$analysis['two']->d_areainfo}}</label>
                             </td>
                             <td class="tac" width="" valign="middle">
                                <div class="btn-group none  btn-group-auto">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company" >
                                        <span class="dropdown-label"><span class="dealer_name">--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company top32"> 

                                    </ul>
                                   
                                </div> 
                                @if(count($analysis)>0)
                                <label class="save-label">{{$analysis['two']->d_name}}</label>
                                @endif
                             </td>
                             <td class="tac" width="">
                                 <span>{{$analysis['two']->d_jc_place}}</span>
                                 &nbsp;
                             </td>
                             <td class="tac" width="">
                                 <div class="inline  ">
                                   <a href="javascript:;" is-add="1" class="btn btn-danger save none city " data-type= "2" data-id="" data-shi="">确定</a>
                                   <a href="javascript:;" class="ml10 edit-new ">修改</a>
                                 </div>
                                 &nbsp;
                             </td>
                             @else
                              <td class="tac" width="" valign="middle">
                                <div class="form-txt psr inlineblock">
                                   <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                        <div class="province-wrapper btn btn-sm btn-default dropdown-toggle area-drop-btn" style="width:150px;">
                                            <span class="dropdown-label"><span>--请选择--</span></span>
                                            <span class="caret"></span>
                                        </div>
                                        <div class="dropdown-menu dropdown-select area-tab-div">
                                            <input type="hidden" name="province" />
                                            <input type="hidden" name="city" />
                                            <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                                   <dl class="dl">
                                            @foreach($area as $are=>$ar)
                                                <dd data-id="{{$are}}">{{$ar['name']}}</dd>       
                                             @endforeach
                                              <div class="clear"></div>
                                            </dl>
                                            <dl class="dl test" style="display: none;">
                                            </dl>
                                            <div class="clear"></div>
                                        </div>
                                      </div>
                                   </div>

                                <label class="save-label none">&nbsp;</label>
                             </td>
                             <td class="tac" width="" valign="middle">
                                <div class="btn-group btn-group-auto">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company"  style="width:180px;">
                                        <span class="dropdown-label"><span class="dealer_name">--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company"> 

                                    </ul>
                                    <input type="hidden" name="dealer_name" />
                                </div> 
                                <label class="save-label none">苏州路虎</label>
                                
                             </td>
                             <td class="tac" width="">
                                 <span></span>
                                 &nbsp;
                             </td>
                                  <td class="tac" width="">
                                 <div class="inline">
                                   <a href="javascript:;" class="btn btn-danger save city " data-type= "2" data-id="" data-shi="">确定</a>
                                   <a href="javascript:;" class="ml10 edit-new none">修改</a>
                                 </div>
                                 &nbsp;
                             </td> 
                             @endif
                           </tr>

                           
 
                         </tbody>


                         </tbody>
                       </table>
					   

					  
                           <div class="m-t-10"></div>
                           <div class="error-div tac"><label>竞争关系的4S店必须有2家！找不到请把范围扩大到相邻地区~</label></div>
                          <div class="m-t-10"></div>

                      <div id="tip-succeed" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac succeed auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">操作成功!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="tip-error" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">操作失败!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>


                      <form name="next-form" method="post" action="/dealer/ajaxsubmitdealer/next-step/{{$dealer_id}}">
                       <p class="tac">
                          <a href="javascript:;" ms-on-click="add_dealer_next_action(10)" class="btn btn-danger fs18 next">提交</a>
                          <a href="/dealer/editdealer/check/{{$id}}/step7"  class="juhuang tdu ml5"><span>返回上一步</span></a>
                         </p>
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='_token' value="{{csrf_token()}}">
                       </form>



                    </div>

                </div>

                <div class="clear"></div>


            </div>
        </div>

                      

         </div>



@endsection

@section('js')
     <script type="text/javascript">
		    var _daili_dealer_id = "{{$id}}"
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.jquery", "module/common/common", "bt", "module/custom/custom.dealer.step.common.fix.jquery"],function(a,b,c,d){
            //$(".last-step").css("width","88px")
            $(".next").click(function(event) {
                 
                 $(".error-div").hide()
                 var options = {
                    type: 'post',
                    beforeSend:function(){

                    },
                    success: function(data) {
                       if(data.error_code ==10){
                          window.location.href="/dealer/editdealer/edit/"+data.code_id
                          return false;
                       }
                       else if(data.error_code == 0){
                           if (data.error_msg) {
                               $(".error-div").show()
                           }
                          // window.location.reload();
                       }
                    }
                 }
                 $("form").ajaxSubmit(options);
          
                  
            })
        });
    </script>

@endsection


