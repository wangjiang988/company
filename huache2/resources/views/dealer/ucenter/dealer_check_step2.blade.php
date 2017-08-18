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
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step2"><span>保险条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">保险条件</h2>
                       <div class="m-t-10"></div>
                       <div class="tbl-list-tool-panle">
                            <p><b>车辆首年商业保险</b></p>
                            <label class="radio-label"><input type="radio" name="baoxian" id="" value='1' <?php if($daili['dl_baoxian']==1){echo 'checked';}?> disabled><span>客户必须在经销商处投保（客户上牌地必须在保险公司理赔范围内）</span></label>
                            <label class="radio-label"><input type="radio" name="baoxian" id="" value='0' <?php if($daili['dl_baoxian']==0){echo 'checked';}?> disabled><span>客户自由投保</span></label>
                       </div>
                       <p><b>保险公司</b></p>
                       <table id="insuranceCompanyList" class="tbl custom-info-tbl">
                         <tbody>
                           <tr>
                               <th class="tac">保险公司名称</th>
                               <th class="tac">理赔范围</th>                              
                           </tr> 
 
                           <!--//循环输出-->
                           @if(count($myBaoxian))
                           @foreach($myBaoxian as $k=>$v)
                           <tr class="def-temp">
                             <td class="tac" width="352" valign="middle">
                                <div class="btn-group none">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                        <span class="dropdown-label"><span>{{$v['bx_title']}}</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company">
                                        <input type="hidden" name="insuranceCompany" />
                                        @if(count($allBaoxian)>0)
											         @foreach($allBaoxian as $k1=>$v1)
					                      <li ms-on-click-1="selectEvent" ms-on-click-2="initClaimsScope({{$v1['bx_id']}},[1,{{$v1['bx_is_quanguo']}}])"><a><span>{{$v1['bx_title']}}</span></a></li>
										           	@endforeach	                
										           @endif 
                                    </ul>
                                    
                                </div> 
                                <label class="save-label ">{{$v['bx_title']}}</label>
                             </td>
                             <td class="tac" width="193" valign="middle">
                                <div class="checkbox-wrapper inline none">
                                    <label class="mt"><input disabled type="checkbox" name="scope-1" id=""><span>本地</span><span class="span-select-tip"><span class="tip-head"></span><span class="tip-contents">该保险公司不支持全国通赔，不可选</span></span></label>
                                    <label class="ml mt"><input disabled type="checkbox" name="scope-1" id=""><span>异地</span></label>
                                    <input type="hidden" name="insuranceCompany" />
                                </div>
                                <label class="save-label ">
                                	@if($v['bx_is_quanguo']==1)
                                		本地、异地
                                	@else
                                		本地
                                	@endif
                                </label>
                                &nbsp;
                             </td>
                           
                           </tr>
                           @endforeach
 						               @endif
                         </tbody>
                       </table>
                       <div class="m-t-10"></div>
                       <div class="m-t-10"></div>
                        <p class="tac">
                          <a href="javascript:;" class="btn btn-danger oksure fs18">等待审核</a>
                       </p>
                        <div class="m-t-10"></div>
                        <p class="tac"><b class="juhuang">温馨提示：</b>经销商基本信息审核中，审核通过后您可进行下一步常规车型等设置</p>
                    </div>
                      </div>
                <div class="clear"></div>
            </div>  
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"],function(a,b,c){
           a.initInsuranceCompany()
        });
	</script>
	
@endsection