@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                    <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step custom-normal-flow-step">
                             <li class="cur"><span>基本资料</span></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step1"><span>服务专员</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step2"><span>保险条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                             <div class="clear"></div>
                         </ul>
                    </div>

                    <div class="content-wapper ">
                       <div id="vue">
                           <h2 class="title weighttitle">基本资料</h2>
                           <div class="m-t-10"></div>
                           <table class="tbl custom-info-tbl" :class="isModifyBaseInfo ? 'hide' : 'show'">
                                 <tr>
                                     <td width="380"><b>销售品牌：</b>
                                     <span>{{$car_brand['gc_name']}}</span>
                                     </td>
                                     <td width="378"><b>归属地区：</b>
                                     <span>{{$dealer['d_areainfo']}}</span></td>
                                 </tr>
                                 <tr>
                                     <td width="380"><b>经销商：</b><span>{{$dealer['d_name']}}</span></td>
                                     <td width="378"><b>经销商编号：</b><span>{{$daili['d_id']}}</span></td>
                                 </tr>
                                 <tr>
                                     <td width="380"><b>营业地点：</b><span>{{$dealer['d_yy_place']}}</span></td>
                                     <td width="378"><b>交车地点：</b><span>{{$dealer['d_jc_place']}}</span></td>
                                 </tr>
                                 <tr>
                                     <td width="380"><b>经销商简称：</b><span>{{$daili['d_shortname']}}</span></td>
                                     <td width="378"><b>类别：</b><span>授权4S</span></td>
                                 </tr>
                                 <tr>
                                     <td width="380"><b>开户行：</b><span>{{$daili['dl_bank_addr']}}</span></td>
                                     <td width="378"><b>统一社会信用代码：</b><span>{{$daili['dl_code']}}</span></td>
                                 </tr>
                                 <tr>
                                     <td width="380"><b>账号：</b><span>{{$daili['dl_bank_account']}}</span></td>
                                     <td width="378"><b></b><span></span></td>
                                 </tr>
                           </table>
                           <div class="m-t-10"></div>
                           <p class="text-right" :class="isModifyBaseInfo ? 'hide' : 'show'">
                               <a @click="modifyBaseInfo" href="javascript:;" class="juhuang tdu base-info-edit">修改基本资料</a>
                               <a @click="delSeller" data-daili-dealer-id='{{$daili["id"]}}' data-dealer-id='{{$daili["d_id"]}}' href="javascript:;" class="juhuang tdu ml20  base-info-del">删除经销商</a>
                           </p>

                           <div id="delSeller" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                      <div class="m-t-10"></div>
                                      <p class="fs14 pd tac succeed error constraint">
                                         <span class="tip-tag" style="background-position: 0px 0px;"></span>
                                         <span class="tip-text">确定要删除此经销商相关的所有信息吗？</span>
                                         <div class="clear"></div>
                                      </p>
                                      <div class="m-t-10"></div>
                                  </div> 
                                  <div class="popup-control">
                                      
                                      <a @click="doDelSeller({{$daili['id']}},{{$daili['d_id']}})" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>
                          
                           <form  action="" name="edit-dealers-form" method="post">
                              <table class="custom-form-tbl ml-30 hide" :class="isModifyBaseInfo ? 'show' : 'hide'">
                                  <tr>
                                      <td align="right" width="150">
                                          <label><span>*</span>销售品牌：</label>
                                      </td>
                                      <td width="200">
                                          <div class="btn-group ">
                                               {{$car_brand['gc_name']}}
                                            </div> 
                                      
                                       </td>
                                       <td>
                                          <label><span>*</span>归属地区：</label>
                                          <div class="form-txt psr inlineblock">
                                             <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                                  <span>{{$dealer['d_areainfo']}}</span></span>

                                             </div>
                                             <div class="error-div"><label>请选择省份</label></div>
                                             <div class="error-div"><label>请选择城市</label></div>
                                             <div class="clear"></div>
                                          </div>
                                          <div class="error-div"><label>请选择销售品牌</label></div>
                                       </td>
                                  </tr>
                                  <tr>
                                      <td align="right">
                                          <label><span>*</span>经销商：</label>
                                      </td>
                                      <td colspan="2">
                                           <div class="btn-group ">
                                                <span>{{$dealer['d_name']}}</span></span>
                                              
                                               
                                            </div> 
                                            <div class="error-div"><label>请选择经销商</label></div>
                                       </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>营业地点：</label>
                                      </td>
                                      <td colspan="2">
                                          {{$dealer['d_yy_place']}}
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>交车地点：</label>
                                      </td>
                                      <td colspan="2">
                                          {{$dealer['d_jc_place']}}
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label><span>*</span>经销商简称：</label>
                                      </td>
                                      <td>
                                          {{$daili['d_shortname']}}
                                          <div class="error-div"><label>请输入经销商简称</label></div>
                                      </td>
                                      <td>
                                          <label>经销商编号：</label>
                                           {{$daili['d_id']}}
                                      </td>
                                   </tr>
                                    <tr>
                                      <td align="right">
                                          <label>类别：</label>
                                      </td>
                                      <td colspan="2">                                   
                                         <span>授权4s</span>               
                                          <input type='hidden' name='dl_type' value='1'>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>统一社会信用代码：</label>
                                      </td>
                                        <td colspan="2">
                                          <input v-model="formInput.code" placeholder="选填 统一社会信用代码由18位数字+字母组成" type="text" name="txtcode" placeholder="" maxlength="18" class="form-control custom-control custom-control-long" value="{{$daili['dl_code']}}" style="width: 460px!important;">
                                          <div class="error-div" :class="formValite.isSnDisplay ? 'show' : ''"><label>输入不正确，请重新输入 "统一社会信用代码由18位数字+字母组成"</label></div>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>开户行：</label>
                                      </td>
                                      <td colspan="2">
                                          <input v-model="formInput.bank" maxlength="50" placeholder="选填" type="text" name="bank" placeholder="" value="{{$daili['dl_bank_addr']}}" class="form-control custom-control" style="width: 460px!important;">
                                      </td>
                                       
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>账号：</label>
                                      </td>
                                      <td colspan="2"> 
                                          <input v-model="formInput.account" maxlength="30" placeholder="选填" type="text" name="account" placeholder="" data-def="{{$daili['dl_bank_account']}}" value="{{$daili['dl_bank_account']}}" class="form-control custom-control" style="width: 460px!important;">
                                          <div class="error-div" :class="formValite.isAccountDisplay ? 'show' : ''"><label>*请输入纯数字账号~</label></div>
                                      </td>
                                   </tr>
                                      <td colspan="3" align="center">
                                          <div class="m-t-10"></div>
                                          <div class="m-t-10"></div>
                                          <label class="add-info-tip"><span>*</span>为必填项</label>
                                          <div class="clear"></div>
                                          @if($daili['dl_status'] == 4)
                                          <a @click="subEditDealersForm({{$id}},0)" href="javascript:;" class="btn btn-s-md btn-danger fs18 " >保存修改</a>
                                          <a @click="modifyBaseInfo" href="javascript:;" class="ml20 btn btn-s-md btn-danger sure fs18 base-info-chance">返回</a>
                                          <p class="mt10 red tac"><span>温馨提示：</span>保存各项修改内容后，请到“竞争分析”页面一并提交重审！</p>
                                          @else
                                          <a @click="subEditDealersForm({{$id}},0)" href="javascript:;" class="btn btn-s-md btn-danger fs18 " >确定</a>
                                          <a @click="modifyBaseInfo" href="javascript:;" class="ml20 btn btn-s-md btn-danger sure fs18 base-info-chance">返回</a>
                                          @endif
                                           <input type='hidden' name='id' value="{{$id}}">
                                          <input type='hidden' name='step' value="0">
                                          <input type='hidden' name='_token' value="{{csrf_token()}}">
                                      </td>
                                   </tr>
                              </table>
                           </form>

                            <div id="noFandDelearsWin" class="popupbox">
                                <div class="popup-title">未找到经销商</div>
                                <div class="popup-wrapper">
                                    <div class="popup-content">
                                        <p class="fs14">       
                                            <table class="custom-form-tbl">
                                                <tr>
                                                    <td width="70" valign="top" class="nopadding"><b>温馨提示：</b></td>
                                                    <td class="nopadding">请核对授权经销商归属地区是否正确，如仍有问题，请提交给华车平台审核，通过后可进行添加。</td>
                                                </tr>
                                            </table>
                                        </p>
                                       
                                    </div>
                                    <div class="popup-control">
                                        <a  href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">告诉华车</a>
                                        <a  class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="delInfoWin" class="popupbox">
                                <div class="popup-title">温馨提示</div>
                                <div class="popup-wrapper">
                                    <div class="popup-content">
                                         <div class="m-t-10"></div>
                                         <p class="fs14 pd  tac wanner"> 确定要删除此经销商相关的所有信息吗？</p>
                                         <div class="m-t-10"></div>
                                    </div>
                                    <div class="popup-control">
                                        <a href="javascript:;" data-id="{{$daili['d_id']}}" ms-on-click="add_dealer_next_action" class="btn btn-danger fs18 chargeStandard">删除</a>
                                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
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
                                             <span class="tip-text">添加失败!</span>
                                           </center>
                                        </div>
                                    </div>
                                    <div class="popup-control">
                                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
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
                                             <span class="tip-text">添加成功!</span>
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
                   
                </div>
                <div class="clear"></div>
            </div>
        </div>



@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["module/vue.custom/dealer/edit.step1","bt"],function(a,b){
            a.init("{{$daili['dl_code']}}","{{$daili['dl_bank_addr']}}","{{$daili['dl_bank_account']}}")
        })
        /*seajs.use(["module/custom/custom_admin", "module/common/common","module/custom/custom.admin.jquery", "bt"]);*/
    </script>
@endsection


