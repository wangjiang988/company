@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step">
                             <li class="prev"><span>基本资料</span></li>
                             <li class="cur cur-step"><span>服务专员</span></li>
                             <li><span>上牌条件</span></li>
                             <li><span>临牌条件</span></li>
                             <li><span>免费提供</span></li>
                             <li><span>杂费标准</span></li>
                             <li><span>刷卡标准</span></li>
                             <li class="last"><span>竞争分析</span></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <div id="vue">
                           <h2 class="title weighttitle">服务专员</h2>
                           <div class="m-t-10"></div>
                           <div class="tbl-list-tool-panle">
                              <label>姓名：</label>
                              <input v-model="searchForm.keyword" placeholder="" type="text" name="search-waitor-key" class="form-control custom-control" value="{{$search_value}}">
                              <input type="button" value="查找" class="btn btn-danger fs18 ml20" @click='searchWaitor'/> 
                              <a @click="addServiceSpecialist"  href="javascript:;" class="btn btn-danger fs18 ml20">新增专员</a>
                           </div>
                           <table class="tbl custom-info-tbl">
                             <tr>
                                 <th class="tac" width="45">编号</th>
                                 <th class="tac" width="80">姓名</th>                            
                                 <th class="tac" width="120">手机</th>
                                 <th class="tac" width="125">备用电话</th>
                                 <th class="tac" width="230">备注</th>
                                 <th class="last" width="135"> 
                                    操作
                                 </th>
                             </tr>
                             @if(count($waitor)>0)
                             @foreach($waitor as $k =>$v)
                             <tr id="tr-id-{{$v['id']}}">
                                 <td class="tac">{{$k+1}}</td>
                                 <td class="tac">{{$v['name']}}</td>
                                 <td class="tac">{{$v['mobile']}}</td>
                                 <td class="tac">{{$v['tel']}}</td>
                                 <td class="tac">
                                    <div class="remark-box">
                                      <div class="remark-wrapper">
                                          {{$v['notice']}}
                                      </div>
                                      <div class="showdiv">{{$v['notice']}}</div>
                                    </div>
                                 </td>
                                 <td class="tac">
                                     <a href="javascript:;" @click="viewServiceSpecialist({{$v['id']}},'{{$v['name']}}','{{$v['mobile']}}','{{$v['tel']}}','{{$v['notice']}}')" class="weight">查看</a>
                                     <a href="javascript:;" @click="editServiceSpecialist({{$v['id']}},'{{$v['name']}}','{{$v['mobile']}}','{{$v['tel']}}','{{$v['notice']}}')" class="ml10 weight">修改</a>
                                     <a href="javascript:;" @click="delServiceSpecialist({{$v['id']}})" class="ml10 weight">删除</a>
                                 </td>
                              </tr>
                              @endforeach
                              <tr class="hide">
                                 <td class="tac" colspan="7">
                                     <div class="m-t-10"></div>
                                     暂未添加服务专员的信息，去<a @click="addServiceSpecialist" href="javascript:;" class="juhuang tdu">添加</a>
                                     <div class="m-t-10"></div>
                                 </td>
                              </tr> 
                              @else      
                              <footer>
                                  
                                @if($search_value=='')
                                <tr>
                                   <td class="tac" colspan="7">
                                       <div class="m-t-10"></div>
                                       暂未添加服务专员的信息，去<a @click="addServiceSpecialist" href="javascript:;" class="juhuang tdu">添加</a>
                                       <div class="m-t-10"></div>
                                   </td>
                                </tr>
                                @else 
                                <tr>
                                   <td class="tac" colspan="7">
                                       <div class="m-t-10"></div>
                                       暂未找到您需要的服务专员~你可以选择添加，或者重新输入查询信息 
                                       <a @click="resetSearch" href="javascript:;" class="juhuang tdu return">返回</a>
                                       <div class="m-t-10"></div>
                                   </td>
                                </tr>
                                @endif
                              </footer>
                           @endif
                           </table>
                           <br><br>
                           <form name="next-form" method="" action="/dealer/ajaxsubmitdealer/next-step/{{$dealer_id}}">
                               <p class="tac">
                                  <a href="javascript:;" @click="serviceNextAction(2)" class="btn btn-danger fs18">下一步</a>
                                  <a href="/dealer/editdealer/check/{{$id}}/step0"  class="juhuang tdu ml5"><span>返回上一步</span></a>
                               </p>
                               
                                <p class="tac">
                                  温馨提示：此项为选填项目，不添加也可进入下一步
                               </p>
                               <input type="hidden" name="step" value="1">
                               <input type='hidden' name='id' value="{{$id}}">
                               <input type='hidden' name='_token' value="{{csrf_token()}}">
                           </form>
 
                           
                          <div id="addServiceSpecialist" class="popupbox">
                              <div class="popup-title">新增服务专员</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                      
                                      <form action="/dealer/ajaxsubmitdealer/add-waitor/{{$dealer_id}}" name="addServiceSpecialistForm" method="post">
                                        <table class="custom-form-tbl ml-15">
                                            <tr>
                                                <td align="right" width="">
                                                    <span class="juhuang">*</span>
                                                    <label>姓 名：</label> 
                                                </td>
                                                <td>
                                                    <input v-model="serviceSpecialist.cname" maxlength="6" placeholder="" type="text" name="specialist-name" class="form-control custom-control custom-txtbrand">
                                                    <div class="error-div" :class="serviceSpecialist.isName ? 'show' : ''"><label>*请输入服务专员名称</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="">
                                                    <span class="juhuang">*</span>
                                                    <label>手 机：</label>
                                                </td>
                                                <td>
                                                  <input v-model="serviceSpecialist.phone" maxlength="11" placeholder="" type="text" name="specialist-phone" class="form-control custom-control custom-txtbrand">
                                                  <div class="error-div" :class="serviceSpecialist.isPhone ? 'show' : ''"><label>*输入的手机号码格式不正确，请重新输入</label></div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td align="right" width="">
                                                    <label>备用电话：</label>
                                                </td>
                                                <td>
                                                   <input v-model="serviceSpecialist.tel" maxlength="13" placeholder="" type="text" name="specialist-tel" class="form-control custom-control custom-txtbrand">
                                                   <div class="error-div" :class="serviceSpecialist.isTel ? 'show' : ''"><label>*输入的手机号码格式不正确，请重新输入</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="">
                                                    <label>备 注：</label>
                                                </td>
                                                <td>
                                                    <textarea v-model="serviceSpecialist.remark" name="specialist-remark" id="" cols="30" rows="10" class="textarea"></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="hidden" name="id" value="{{$id}}">
                                        <input type='hidden' name='_token' value="{{csrf_token()}}">
                                      </form>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="doAddServiceSpecialist" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">新增</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

                          <div id="editServiceSpecialist" class="popupbox">
                              <div class="popup-title">修改服务专员</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                      
                                      <form action="/dealer/ajaxsubmitdealer/edit-waitor/{{$dealer_id}}" name="editServiceSpecialistForm" method="post">
                                        <table class="custom-form-tbl ml-15">
                                            <tr>
                                                <td align="right" width="">
                                                    <span class="juhuang">*</span>
                                                    <label>姓 名：</label>
                                                </td>
                                                <td>
                                                  <input v-model="editService.cname" maxlength="6" ms-attr-value='specialist.name' placeholder="" type="text" name="specialist-name" class="form-control custom-control custom-txtbrand">
                                                  <div class="error-div" :class="editService.isName ? 'show' : ''"><label>*请输入服务专员名称</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="">
                                                    <span class="juhuang">*</span>
                                                    <label>手 机：</label>
                                                </td>
                                                <td>
                                                  <input v-model="editService.phone" maxlength="11" ms-attr-value='specialist.phone'  placeholder="" type="text" name="specialist-phone" class="form-control custom-control custom-txtbrand">
                                                  <div class="error-div" :class="editService.isPhone ? 'show' : ''"><label>*输入的手机号码格式不正确，请重新输入</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="">
                                                    <label>备用电话：</label>
                                                </td>
                                                <td>
                                                  <input v-model="editService.tel" maxlength="13" ms-attr-value='specialist.tel'  placeholder="" type="text" name="specialist-tel" class="form-control custom-control custom-txtbrand">
                                                  <div class="error-div" :class="editService.isTel ? 'show' : ''"><label>*输入的手机号码格式不正确，请重新输入</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="">
                                                    <label>备 注：</label>
                                                </td>
                                                <td>
                                                    <textarea v-model="editService.remark" ms-attr-value-title="specialist.remark" name="specialist-remark" id="" cols="30" rows="10" class="textarea"></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                        <input v-model="editService.id" type='hidden' name='specialist-id' ms-attr-value='specialist.id'>
                                        <input type='hidden' name='_token' value="{{csrf_token()}}">
                                      </form>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="doEditServiceSpecialist" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确认修改</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

                          <div id="viewServiceSpecialist" class="popupbox">
                              <div class="popup-title">查看服务专员</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                      
                                      <table class="custom-form-tbl ml-15">
                                          <tr>
                                              <td align="right" width="150">
                                                  <label>姓 名：</label>
                                              </td>
                                              <td>${viewService.cname}</td>
                                          </tr>
                                          <tr>
                                              <td align="right" width="">
                                                  <label>手 机：</label>
                                              </td>
                                              <td>${viewService.phone}</td>
                                          </tr>
                                          <tr>
                                              <td align="right" width="">
                                                  <label>备用电话：</label>
                                              </td>
                                              <td>${viewService.tel}</td>
                                          </tr>
                                          <tr>
                                              <td align="right" width="" valign="top">
                                                  <label>备 注：</label>
                                              </td>
                                              <td>
                                                  <div class="remark-break">
                                                  ${viewService.remark}
                                                  </div>
                                              </td>
                                          </tr>
                                      </table>
                                       
                                  </div>
                                  <div class="popup-control">
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">确认</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

                          <div id="delServiceSpecialist" class="popupbox">
                              <div class="popup-title">删除账户信息</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <p class="fs14 pd  tac">确定要删除该服务专员吗？</p>
                                       <div class="m-t-10"></div>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="doDelServiceSpecialist({{$dealer_id}})" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确认</a>
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

                          <div id="tip-modify-succeed" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <div class="fs14 pd tac succeed auto">
                                         <center>
                                           <span class="tip-tag"></span>
                                           <span class="tip-text">修改成功!</span>
                                         </center>
                                      </div>
                                  </div>
                                  <div class="popup-control">
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

                          <div id="tip-del-succeed" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <div class="fs14 pd tac succeed auto">
                                         <center>
                                           <span class="tip-tag"></span>
                                           <span class="tip-text">删除成功!</span>
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

                          <div id="tip-modify-error" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <div class="fs14 pd tac error auto">
                                         <center>
                                           <span class="tip-tag"></span>
                                           <span class="tip-text">修改失败!</span>
                                         </center>
                                      </div>
                                  </div>
                                  <div class="popup-control">
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

                          <div id="tip-del-error" class="popupbox">
                              <div class="popup-title">温馨提示</div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                       <div class="m-t-10"></div>
                                       <div class="fs14 pd tac error auto">
                                         <center>
                                           <span class="tip-tag"></span>
                                           <span class="tip-text">删除失败!</span>
                                         </center>
                                      </div>
                                  </div>
                                  <div class="popup-control">
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                      <div class="clear"></div>
                                  </div>
                              </div>
                          </div>

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
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                          </div>
                      </div>

                    

                    </div>
@endsection

@section('js')

	 <script type="text/javascript">
          seajs.use(["module/vue.custom/dealer/step2","bt"],function(a){
             a.initSearach('{{$search_value}}')
          })
         
	</script>
@endsection