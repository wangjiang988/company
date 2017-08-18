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
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">刷卡标准</h2>
                       <div class="m-t-10"></div>
                         <div class="tbl-list-tool-panle">
                              <p><b>单车付款刷信用卡免费次数：</b></p>
							   <label class="radio-label"><input type="radio" name="card1" id="" disabled <?php if($xyk_status==0){echo 'checked';} ?>><span>不限</span></label>
                               <label class="radio-label">
                                
                                    <table cellpadding="0" cellspacing="0">
                                      <tbody><tr>
                                        <td valign="top">
                                          <input disabled="" type="radio" name="card1" id="" disabled <?php if($xyk_status==1){echo 'checked';} ?>> 
                                          <span class="disabled-span disabled-span-min">
                                          @if($xyk_status==1) {{$xyk_number}} @endif</span>
                                        </td>
                                        <td valign="top">
                                            <span class="mt5 inlineblock">次；超出次数收费：</span>
                                            <span class="error-div"><label>*请输入整数格式的数字</label></span>
                                            <div class="clear"></div>
                                            <div class="ml50">
                                               <p>
                                                 <input disabled="" type="checkbox" name="" id="" disabled @if($xyk_status==1 && $xyk_per_num <> 0) checked @endif>
                                                 <span>刷卡金额的</span>
                                                 <span class="disabled-span disabled-span-min">
                                                 @if($xyk_status==1 && $xyk_per_num <> 0) {{$xyk_per_num}} @endif</span>
                                                 <span>%（百分之）</span> 
                                               </p>
                                               <p>
                                                 <input disabled="" type="checkbox" name="" id="" disabled @if($xyk_yuan_num <> 0 && $xyk_status==1) checked @endif>
                                                 <span>每次</span>
                                                 <span class="disabled-span disabled-span-min">@if($xyk_status==1 && $xyk_yuan_num <> 0) {{$xyk_yuan_num}} @endif</span>
                                                 <span>元（封顶）</span> 
                                               </p>									 
                                             <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                         </td>
                                     </tr>
                                 </table>
                             </label>
                         </div>
                        <div class="tbl-list-tool-panle valite-3">
                            <p><b>单车付款刷借记卡免费次数：</b></p>
                            <label class="radio-label"><input type="radio" name="jjk_status" id="" disabled <?php if($jjk_status==0){echo 'checked';} ?>><span>不限</span>
                            </label>
                            <label class="radio-label">

                                <table  cellpadding="0" cellspacing="0">
								                  <tbody>
                                    <tr>
                                        <td valign="top">
                                            <input type="radio" name="jjk_status" id="" value="1" disabled <?php if($jjk_status==1){echo 'checked';} ?>>
											                      <span class="disabled-span disabled-span-min">@if($jjk_status==1) {{$jjk_number}} @else &nbsp; @endif</span>
                                        </td>
										                    <td valign="top">
                                            <span class="mt5 inlineblock">次；超出次数收费：</span>
                                            <span class="error-div"><label>*请输入整数格式的数字</label></span>
                                            <div class="clear"></div>
                                            <div class="ml50">
                                                <p>
                                                 <input disabled="" type="checkbox" name="" id="" @if($jjk_status==1 && $jjk_per_num <> 0) checked @endif>
                                                 <span>刷卡金额的</span>
                                                 <span class="disabled-span disabled-span-min">
                                                 @if($jjk_status==1 && $jjk_per_num<>0) {{$jjk_per_num}} @else &nbsp; @endif</span>
                                                 <span>%（百分之）</span>
                                                  
                                               </p>
                                               <p>
                                                 <input disabled="" type="checkbox" name="" id="" @if($jjk_status==1 && $jjk_yuan_num <> 0) checked @endif>
                                                 <span>每次</span>
                                                 <span class="disabled-span disabled-span-min">@if($jjk_status==1 && $jjk_yuan_num <> 0) {{$jjk_yuan_num}} @else &nbsp; @endif</span>
                                                 <span>元（封顶）</span> 
                                               </p>
                                               <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                             <div>
                                        </td>
                                      </tr>
                                    </tbody> 
                                  </table> 
                              </label>
                         </div>


                       
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


