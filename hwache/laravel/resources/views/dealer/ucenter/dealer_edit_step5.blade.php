@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                    <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step custom-normal-flow-step">
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step0"><span>基本资料</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step1"><span>服务专员</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step2"><span>保险条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                   <div  class="content-wapper ">
                       <div id="vue">
                           <h2 class="title weighttitle">免费提供</h2>
                           <div class="m-t-10"></div>
                           <p><b>免费礼品和服务（常规）</b><a @click="servicelAdd" href="javascript:;" class="juhuang tdu ml10 controlModelAdd">添加</a></p>
                           <table  v-cloak id="controlModel" class="tbl custom-info-tbl">
                             <tbody>
                               <tr>
                                   <th class="tac">名称</th>
                                   <th class="tac">数量</th>
                                   <th class="last"> 
                                      操作
                                   </th>
                               </tr> 
                               
                              <?php 
                              $zengpinStr = '';
                              if(!empty($zengpin)){
                                foreach($zengpin as $k=>$v){
                                  $zengpinStr.="<li @click=\"selectEditElm('".$v['id']."','".$v['title']."')\" data-id='".$v['id']."'><a><span>".$v['title']."</span></a></li>";
                                }
                              }
                              ?>
                               <!--//循环输出-->
                               @if(count($myZengpin)>0)
                               @foreach($myZengpin as $k=>$v)
                               <tr class="def-temp" >
                                 <td class="tac" width="260" valign="middle">
                                    <div v-cloak class="btn-group btn-group-auto none" :class="switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                        <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                            <span class="dropdown-label"><span>${switchs[{{$k}}].valueDisp}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-company top32">
                                            <input type="hidden" name="zengpin" v-model="switchs[{{$k}}].id"  value="{{$v->zp_id}}"/>
                                            <?php echo $zengpinStr;?>
                                        </ul>
                                    </div> 
                                    <label v-cloak class="save-label" :class="!switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'" id="comm">${switchs[{{$k}}].valueTmp}</label>
                                 </td>
                                 <td class="tac" width="200" valign="middle">
                                    <div v-cloak class="checkbox-wrapper inline counter-wrapper none" :class="switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                        <span @click="minusEventEdit({{$k}})" class="prev">-</span>
                                        <input v-on:focus="killFocus" v-model="switchs[{{$k}}].countDisp" number  type="text" name="zengpin_num" id="zen_num" value="{{$v->dl_zp_num}}">
                                        <span @click="addEventEdit({{$k}})" class="next">+</span> 
                                    </div>
                                    <label v-cloak class="save-label" :class="!switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">${switchs[{{$k}}].countTmp}</label>
                                 </td>
                                 <td class="tac" width="200">
                                 
                                     <div v-cloak class="inline" :class="switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                         <a @click="modify('{{$k}}','{{$v->dl_zp_id}}','{{$daili['d_id']}}')" href="javascript:;" class="btn btn-danger save  zpxiugai" data-id="{{$v->dl_zp_id}}" data-did={{$daili['d_id']}} data-type="edit-zengpin" iscounter="1">保存</a> 
                                         <a @click="cancel({{$k}})" href="javascript:;" class="ml10 cancel ">取消</a>
                                     </div>
                                     <div v-cloak class="inline" :class="!switchs[{{$k}}].selectEventSwitch ? 'show' : 'hide'">
                                         <a @click="edit({{$k}})" href="javascript:;" class="ml10 edit" >修改</a>
                                         <a @click="del" href="javascript:;" class="ml10 del" data-id="{{$v->dl_zp_id}}" data-type="del-zengpin" dealer={{$daili['d_id']}}>删除</a>
                                     </div>  

                                 </td>
                               </tr>

                                @endforeach 
                                <!---->
                                <tr id='temp-file' style="display: none;" :style="{display:!nothing ? 'table-row' : 'none'}">
                                   <td class="tac" colspan="3">
                                       <div class="mt10"></div>
                                       <p>暂未添加任何的免费礼品和服务~</p>
                                   </td>
                                </tr>
                                @else
                                <tr id='temp-file' :style="{display:empty ? 'table-row' : 'none'}">
                                     <td class="tac" colspan="3">
                                           <div class="mt10"></div>
                                           <p>暂未添加任何的免费礼品和服务~</p>
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
                                               @foreach($zengpin as $k=>$v)
                                              <li @click="selectElmEvent('{{$v['title']}}','',{{$v['id']}})" data-id={{$v['id']}}><a><span>{{$v['title']}}</span></a></li>
                                              @endforeach
                                          </ul>

                                      </div> 
                                      <label v-cloak class="save-label" :class="!switched ? 'show' : 'hide'">${elm.html}</label>
                                   </td>
                                   <td class="tac" width="200" valign="middle">
                                      <div v-cloak class="checkbox-wrapper inline counter-wrapper" :class="isSelect ? 'show' : 'hide'">
                                          <span @click="minusEvent" class="prev">-</span>
                                          <input v-model="elm.count" v-on:focus="killFocus" number id='ttt' type="text" name="zengpin_nums" id="xx" value="1">
                                          <span @click="addEvent" class="next">+</span>
                                      </div>
                                      <label v-cloak class="save-label" :class="!switched ? 'show' : 'hide'">${elm.countHtml}</label>
                                   </td>
                                   <td class="tac" width="200">
                                       <div class="inline" v-cloak class="init" :class="isSelect ? 'show' : 'hide'">
                                         <a @click="save({{$daili['d_id']}},{{$daili['id']}})" href="javascript:;" class="btn btn-danger save zengpin " data-id="{{$daili['d_id']}}" daili-dealer-id="{{$id}}" data-did="{{$daili['d_id']}}">保存</a>
                                         <a @click="fade" href="javascript:;" class="ml10 cancel">取消</a>
                                         <!-- <a @click="modify" href="javascript:;" class="ml10 edit none">修改</a> -->
                                         <!-- <a @click="del({{$daili['d_id']}})" data-zp-id="" href="javascript:;" class="ml10 del none" data-type="del-zengpin">删除</a> -->
                                       </div>
                                       <span v-cloak @click="applyNew" class="init" :class="!isSelect ? 'show' : 'hide'">不在列表中？<a href="javascript:;" class="ml10 applynew">申请新项目</a></span>
                                   </td>
                                </tr>      

                                
                           </table>

                           
                           <div class="m-t-10"></div>
                          
                          

                           <div id="delControlModel" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <p class="fs14 pd  tac">确定要删除该免费项目吗？</p>
                                       <div class="m-t-10"></div>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="doDel" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 delControlModel">确认</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                           </div>

                           <div id="applyControlModel" class="popupbox">
                            <div class="popup-title">申请新项目</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <p class="fs14">       
                                        <table class="custom-form-tbl">
                                            <tr>
                                                <td width="70" valign="top" class="nopadding"><b>温馨提示：</b></td>
                                                <td class="nopadding">请输入该经销商其他的常规免费礼品或服务名称，由平台审核通过后可进行添加。</td>
                                            </tr>
                                        </table>
                                    </p>
                                    <form action="/dealer/ajaxsubmitdealer/add-project/{{$dealer_id}}" name="addServiceSpecialistForm" >
                                      <div class="m-t-10"></div>
                                      <div class="m-t-10"></div>
                                      <center>  
                                        <table class="custom-form-tbl" style="width: 70%">
                                            <tr>
                                                <td align="right" width="" valign="middle">
                                                    <label>名称：</label>
                                                </td>
                                                <td>
                                                  <input type="hidden" name="type" value="2">
                                                  <input v-model="apply.name" placeholder="" type="text" name="title" class="form-control custom-control " value="">
                                                  <p class="error-div" :class="apply.isName ? 'show' : 'hide'"><span class="juhuang">*请填写名称！</span></p>
      
                                                </td>
                                            </tr>
                                        </table>
                                      </center>
                                      <div class="m-t-10"></div>
                                      <div class="m-t-10"></div>
                                      <input type='hidden' name='_token' value="{{csrf_token()}}">
                                    </form>

                                </div>
                                <div class="popup-control">
                                    <a @click="doApplyNew" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sub-apply-new">提交</a>
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

                           <div id="tip-info-success" class="popupbox">
                             <div class="popup-title">温馨提示</div>
                             <div class="popup-wrapper">
                                 <div class="popup-content">
                                      <div class="m-t-10"></div>
                                      <p class="fs14 pd tac succeed constraint">
                                         <span class="tip-tag"></span>
                                         <span class="tip-text">您已成功提交工单！工单内容经平台审核后方可使用。</span>
                                         <div class="clear"></div>
                                      </p>
                                      <p class="fs14 tac">查看审核进度方请前往：便捷工具->工单进度管理</p>
                                      <div class="m-t-10"></div>
                                 </div>
                                 <div class="popup-control">
                                     <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure">确认</a>
                                     <div class="clear"></div>
                                 </div>
                             </div>
                           </div>
                         
                       </div>

                       
                    

                     </div>

         

@endsection

@section('js')
   
   <script type="text/javascript">
        seajs.use([/*"vendor/react.min","//cdn.bootcss.com/react/15.3.0/react-dom.js",*/"module/vue.custom/dealer/step6","bt"],function(a){
           @foreach($myZengpin as $k=>$v)
           a.init('{{$k}}',false,'{{$v->title}}','{{$v->zp_id}}','{{$v->dl_zp_num}}')
           @endforeach
        })
  </script>
  
@endsection