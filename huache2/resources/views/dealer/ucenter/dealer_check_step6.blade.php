﻿@extends('_layout.base_dealercenter')
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
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">杂费标准</h2>
                       <div class="m-t-10"></div>
                       <p><b>其他收费（常规）</b></p>
                       <table id="controlModel" class="tbl custom-info-tbl">
                         <tbody>
                           <tr>
                               <th class="tac">费用名称</th>
                               <th class="tac">金额</th>                              
                           </tr> 
 
                           <!--//循环输出-->
                           @if(count($myzafei)>0)
                            @foreach($myzafei as $k =>$v)
                           <tr class="def-temp">
                             <td class="tac" width="260" valign="middle">
                                <div class="btn-group none">
                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                        <span class="dropdown-label"><span>--请选择--</span></span>
                                        <span class="caret"></span>
                                    </button>
                                   
                                    
                                </div> 
                                <label class="save-label ">{{$v['title']}}</label>
                             </td>
                             <td class="tac" width="200" valign="middle">￥
                                <div class="checkbox-wrapper inline counter-wrapper none">
                                    <span class="prev none">-</span>
                                    <input class="long" type="hidden" name="fees_id" id="" value="{{$v['id']}}">
                                    <span class="next none">+</span>
                                </div>
                                <label class="save-label">{{$v['other_price']}}</label>
                                &nbsp;
                             </td>
                           
                           </tr>
                           @endforeach
                           @else
                           <tr id='temp-file'>
                           <td colspan="3">
                           暂未有没有提供项目的信息~
                           </td>
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
                    seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){

                    });
                </script>

@endsection


