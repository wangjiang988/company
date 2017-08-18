@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
 
                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step">
                             <li><span>基本资料</span></li>
                             <li class="prev"><span>服务专员</span></li>
                             <li class="cur cur-step"><span>保险条件</span></li>
                             <li><span>上牌条件</span></li>
                             <li><span>临牌条件</span></li>
                             <li><span>免费提供</span></li>
                             <li><span>杂费标准</span></li>
                             <li><span>刷卡标准</span></li>
                             <li><span>补贴情况</span></li>
                             <li class="last"><span>竞争分析</span></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div id="vue">
                        <div class="content-wapper ">
                           
                           <h2 class="title weighttitle">保险条件</h2>
                           <div class="m-t-10"></div>
                           <div class="tbl-list-tool-panle">
                                <p><b>车辆首年商业保险</b></p>
                                <label @click="selectRadio(1)" class="radio-label"><input type="radio" name="baoxian" value='1' v-model="radio"><span>客户必须在经销商处投保（客户上牌地必须在保险公司理赔范围内）</span></label>
                                <label @click="selectRadio(0)" class="radio-label"><input type="radio" name="baoxian" value='0' v-model="radio"><span>客户自由投保</span></label>
                           </div>
                           <p><b>保险公司</b><a @click="addInsuranceCompany" href="javascript:;" class="juhuang tdu ml10">新增保险公司</a></p>
                           <table id="insuranceCompanyList" class="tbl custom-info-tbl">
                             <tbody>
                               <tr>
                                   <th class="tac">保险公司名称</th>
                                   <th class="tac">理赔范围</th>
                                   <th class="last"> 
                                      操作
                                   </th>
                               </tr> 
     
                               <!--//循环输出-->
                               @if(count($myBaoxian))
                               @foreach($myBaoxian as $k=>$v)
                               <tr class="def-temp" id="tr-{{$k}}">
                                 <td class="tac" width="352" valign="middle">
                                    <div v-cloak class="btn-group btn-group-auto none" :class="switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                        <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                            <span class="dropdown-label"><span>${switchs[{{$k}}].valueDisp}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-company top32">
                                            <input type="hidden" name="insuranceCompany" v-model="switchs[{{$k}}].id"  value="{{$v['bx_id']}}"/>
                                            @if(count($allBaoxian)>0)
          										              	@foreach($allBaoxian as $k1=>$v1)
              					                      <li @click.stop-1="selectEditElm({{$v1['bx_id']}},'{{$v1['bx_title']}}')" @click.stop-2="initModifyArea([1,{{$v1['bx_is_quanguo']}}],{{$k}})"><a><span>{{$v1['bx_title']}}</span></a></li>
              											          @endforeach	                
          										              @endif 
                                        </ul>
                                        
                                    </div> 
                                    <label v-cloak class="save-label" :class="!switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'" id="comm">${switchs[{{$k}}].valueTmp}</label>
                                 </td>
                                 <td class="tac" width="193" valign="middle">
                                      <div class="checkbox-wrapper inline none" :class="switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                          <label class="mt"><input disabled type="checkbox" name="scope-1" id="" value="local" v-model="switchs[{{$k}}].obj"><span>本地</span><span class="span-select-tip" class="switchs[{{$k}}].selectId == 0 ? 'show' : 'hide'"><span class="tip-head"></span><span class="tip-contents">该保险公司不支持全国通赔，不可选</span></span></label>
                                          <label class="ml mt"><input disabled type="checkbox" name="scope-1" id="" value="all" v-model="switchs[{{$k}}].obj" ><span>异地</span></label>
                                          <input type="hidden" name="insuranceCompany" :value="area[1]=='all' ? 1 : 0" />
                                      </div>
                                      <label v-cloak class="save-label" :class="!switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">${switchs[{{$k}}].countTmp}</label>
                                 </td>
                                 <td class="tac" width="190">
                                     <div v-cloak class="inline" :class="switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                         <a @click="modify('{{$k}}','{{$v['id']}}','{{$v['dealer_id']}}','{{$v['daili_dealer_id']}}')" href="javascript:;" class="btn btn-danger save" data-id="{{$v['id']}}" data-did={{$daili['id']}} >保存</a> 
                                         <a @click="cancel({{$k}})" href="javascript:;" class="ml10 cancel ">取消</a>
                                     </div>
                                     <div v-cloak class="inline" :class="!switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                         <a @click="edit({{$k}},{{$v['bx_is_quanguo']}})" href="javascript:;" class="ml10 edit" >修改</a>
                                         <a @click="del" href="javascript:;" class="ml10 del" data-bx="{{$v['bx_id']}}" >删除</a>
                                     </div> 
                                 </td>
                               </tr>
                               @endforeach
                               <tr id='temp-file' style="display: none;" :style="{display:!nothing ? 'table-row' : 'none'}">
                                   <td class="tac" colspan="3">
                                       <div class="mt10"></div>
                                       <p>暂未添加任何的保险公司~</p>
                                   </td>
                                </tr>
                               @else
                               <tr id='temp-file' :style="{display:empty ? 'table-row' : 'none'}">
                                  <td class="tac" colspan="3">
                                       <div class="mt10"></div>
                                       <p>暂未添加任何的保险公司~</p>
                                  </td>                   
                               </tr>
                               @endif
                               <!--添加用的模板-->
                                <tr v-cloak class="def-add"  :style="{display:isAdd ? 'table-row' : 'none'}">
                                   <td class="tac" width="260" valign="middle">
                                      <div class="btn-group btn-group-auto" :class="switched ? 'show' : 'hide'">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                              <span class="dropdown-label"><span v-cloak>${elm.value == '' ? '--请选择--' : elm.value}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-company top32">
                                              <input type="hidden" name="zengpin" :value="elm.value" />
                                              @if(count($allBaoxian)>0)
                                              @foreach($allBaoxian as $k1=>$v1)
                                              <li @click.stop-1="selectElmEvent('{{$v1['bx_title']}}',[1,{{$v1['bx_is_quanguo']}}],{{$v1['bx_id']}})" @click.stop-2="initArea([1,{{$v1['bx_is_quanguo']}}])" ><a><span>{{$v1['bx_title']}}</span></a></li>
                                              @endforeach                 
                                              @endif 
                                          </ul>

                                      </div> 
                                      <label v-cloak class="save-label" :class="!switched ? 'show' : 'hide'">${elm.html}</label>
                                   </td>
                                   <td class="tac" width="200" valign="middle">
                                      <div class="checkbox-wrapper inline none" :class="isSelect ? 'show' : 'hide'">
                                          <label class="mt"><input disabled type="checkbox" name="scope-1" id="" value="local" v-model="area"><span>本地</span><span class="span-select-tip"><span class="tip-head"></span><span class="tip-contents">该保险公司不支持全国通赔，不可选</span></span></label>
                                          <label class="ml mt"><input disabled type="checkbox" name="scope-1" id="" value="all" v-model="area" ><span>异地</span></label>
                                          <input type="hidden" name="insuranceCompany" :value="area[1]=='all' ? 1 : 0" />
                                      </div>
                                      <label v-cloak class="save-label" :class="!switched ? 'show' : 'hide'">${elm.countHtml}</label>
                                   </td>
                                   <td class="tac" width="200">
                                       <div class="inline" v-cloak class="init" :class="isSelect ? 'show' : 'hide'">
                                         <a @click="save({{$daili['d_id']}},{{$daili['id']}})" href="javascript:;" class="btn btn-danger save zengpin " >保存</a>
                                         <a @click="fade" href="javascript:;" class="ml10 cancel">取消</a>
                                       </div>
                                   </td>
                                </tr>  
                               

                             </tbody>
                           </table>
                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                           <form name="next-form" method="post" action="/dealer/ajaxsubmitdealer/next-step/{{$dealer_id}}">
                           <p class="tac">
                              <a href="javascript:;" @click="nextAction(3)" class="btn btn-danger fs18">下一步</a>
                              <a href="/dealer/editdealer/check/{{$id}}/step1" class="juhuang tdu ml5"><span>返回上一步</span></a>
                           </p>
                           <input type="hidden" name="step" value="2">
                           <input type='hidden' name='id' value="{{$id}}">
                           <input type='hidden' name=bx_type value="">
                           <input type='hidden' name='_token' value="{{csrf_token()}}">
                           </form>

     

                          <div id="delInsuranceCompany" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <p class="fs14 pd  tac">确定要删除此保险公司吗？</p>
                                       <div class="m-t-10"></div>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="doDel" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确认</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

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

                        
      
                        </div>
                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/vue.custom/dealer/step3","bt"],function(a){
           a.initRadio("{{$daili['dl_baoxian']}}","{{$type}}")
           @foreach($myBaoxian as $k=>$v)
           a.init('{{$k}}',false,"{{$v['bx_title']}}","{{$v['bx_id']}}","{{$v['bx_is_quanguo']}}")
           @endforeach
        });
	</script>

@endsection