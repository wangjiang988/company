@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
 
                    <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step custom-normal-flow-step">
                             <li><span><a href="/dealer/editdealer/edit/{{$id}}/step0">基本资料</a></span></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step1"><span>服务专员</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li class="last cur"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
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
                               <th class="tac">地区</th>
                               <th class="tac">经销商名称</th>
                               <th class="tac">营业地点</th>
                             
                           </tr> 
 
                           <!--//循环输出-->
                           @if(!empty($analysis['one']))
                           <tr class="def-temp">
                             <td class="tac" width="174" valign="middle">
                                <div class="form-txt psr inlineblock none">
                                </div>

                                <label class="save-label">{{$analysis['one']->d_areainfo}}</label>
                             </td>
                             <td class="tac" width="220" valign="middle">
                                <div class="btn-group none ">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company"  style="width:180px;">
                                        <span class="dropdown-label"><span>--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company">
                                    </ul>
                                    <input type="hidden" name="" />
                                </div> 
                                <label class="save-label">{{$analysis['one']->d_name}}</label>
                             </td>
                             <td class="tac" width="230">
                                 <span>{{$analysis['one']->d_jc_place}}</span>
                                 &nbsp;
                             </td>
                            
                           </tr>
                           @endif

                            @if(!empty($analysis['one']))
                           <tr class="def-temp">
                             <td class="tac" width="174" valign="middle">
                                <div class="form-txt psr inlineblock none">
                            
                                   </div>

                                <label class="save-label">{{$analysis['two']->d_areainfo}}</label>
                             </td>
                             <td class="tac" width="220" valign="middle">
                                <div class="btn-group none">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company"  style="width:180px;">
                                        <span class="dropdown-label"><span>--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-company"> 
                                       
                                    </ul>
                                    <input type="hidden" name="" />
                                </div> 
                                <label class="save-label">{{$analysis['two']->d_name}}</label>
                             </td>
                             <td class="tac" width="230">
                                 <span>{{$analysis['two']->d_jc_place}}</span>
                                 &nbsp;
                             </td>
                            
                           </tr>
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
        </div>


@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){

        });
    </script>

@endsection


