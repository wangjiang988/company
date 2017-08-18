@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step custom-normal-flow-step">
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step0"><span>基本资料</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step1"><span>服务专员</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <div id="vue">
                           <h2 class="title weighttitle">服务专员</h2>
                           <div class="m-t-10"></div>
                           <div class="">
                              <label>姓名：</label>
                              <input v-model="searchForm.keyword" placeholder="" type="text" name="search-waitor-key" class="form-control custom-control" value="{{$search_value}}">
                              <input type="button" value="查找" class="btn btn-danger fs18 ml20" @click='searchWaitor'/> 
                           </div>
                           <div class="mt20"></div>
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
                             <tr ms-attr-waitor-id="{{$v['id']}}">
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
                                 </td>
                              </tr>
                              @endforeach
                              @else
                              
                              
                              
                              
                              <footer>
                                 <!--//没有任何服务专员的时候显示-->
                                 @if($search_value=='')
                                 <tr>
                                   <td class="tac" colspan="7">
                                       <div class="m-t-10"></div>
                                       	暂未添加服务专员的信息
                                       <div class="m-t-10"></div>
                                   </td>
                                </tr>
                                @else
                                <!--//搜索的时候没有任何服务专员的时候显示-->
                                <tr>
                                   <td class="tac" colspan="7">
                                       <div class="m-t-10"></div>
                                       暂未找到您需要的服务专员~你可以选择添加，或者重新输入查询信息 <a @click="resetSearch" href="javascript:;" class="juhuang tdu return">返回</a>
                                       <div class="m-t-10"></div>
                                   </td>
                                </tr>
                                @endif
                              </footer>
                           @endif
                           </table>
                           <br><br>
                            <p class="tac">
                              <a href="javascript:;" class="btn btn-danger oksure fs18">等待审核</a>
                           </p>
                            <div class="m-t-10"></div>
                           <p class="tac"><b class="juhuang">温馨提示：</b>经销商基本信息审核中，审核通过后您可进行下一步常规车型等设置</p>
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
                       </div>

                    </div>
                <div class="clear"></div>
            </div>  

                    

@endsection

@section('js')
	 <script type="text/javascript">
          seajs.use(["module/vue.custom/dealer/step2","bt"],function(a){
             a.initSearach('{{$search_value}}')
          }) 
  </script>
@endsection