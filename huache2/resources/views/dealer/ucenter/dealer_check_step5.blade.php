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
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">免费提供</h2>
                       <div class="m-t-10"></div>
                       <p><b>免费礼品或服务</b></p>
                       <table id="controlModel" class="tbl custom-info-tbl">
                         <tbody>
                           <tr>
                               <th class="tac">名称</th>
                               <th class="tac">数量</th>                              
                           </tr> 

                           <!--//循环输出-->
                           @if(count($myZengpin)>0)
                           @foreach($myZengpin as $k=>$v)
                           <tr class="def-temp">
                             <td class="tac" width="260" valign="middle">
                                 
                                <label class="save-label ">{{$v->title}}</label>
                             </td>
                             <td class="tac" width="200" valign="middle">
                                
                                <label class="save-label">{{$v->dl_zp_num}}</label>
                                &nbsp;
                             </td>
                           </tr>
                            @endforeach
 						            	@else
                          <tr id='temp-file'>
                       <td colspan="3">
                          暂未有没有提供项目的信息~
                       </td>
                       </tr>
                       @endif
                         </tbody>
                       </table>
                       <div class="m-t-10"></div>
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
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){
           
        });
	</script>
	
@endsection