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
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>

                    <div class="content-wapper ">
                       <h2 class="title weighttitle">刷卡标准</h2>
                       <div class="m-t-10"></div>
                        <form action="/dealer/ajaxsubmitdealer/save-step/{{$dealer_id}}" method="post" name="next-form">
                        @if(isset($xyk_status))
                            <div class="tbl-list-tool-panle valite-3">
                              <p><b>单车付款刷信用卡免费次数：</b></p>
                              <div class="radio-label"><input type="radio" value="0" name="xyk_status" id=""
                             @if($xyk_status==0)
                             checked
                             @endif
                             ><span>不限</span></div>
                              <div class="radio-label">                                
                                    <table  cellpadding="0" cellspacing="0">
                                      <tbody><tr>
                                        <td valign="top">
                                          <input type="radio" name="xyk_status" value="1" @if($xyk_status==1) checked @endif>
                                          <input class="card-input card-txt-count" type="text" name="xyk_number"  @if($xyk_status==1)
                                             value="{{$xyk_number}}">
                                             @endif
                                        </td>

                                        <td valign="top">
                                            <span class="mt5 inlineblock">次；超出次数收费：</span>
                                            <span class="error-div"><label>*请输入整数格式的数字</label></span>
                                            <div class="clear"></div>
                                            <div class="ml50">
                                           <p>
                                             <input type="checkbox" name="xyk_per" id="" value="1" @if($xyk_status==1 && $xyk_per_num <> 0) checked @endif >
                                             <span>刷卡金额的</span>
                                             <input class="card-input card-txt-price max-valite" type="text" name="xyk_per_num" id=""  
                                             @if($xyk_status==1)
                                             @if($xyk_per_num==0)
                                             value=""
                                             @else 
                                                  value="{{$xyk_per_num}}"
                                                  @endif 
                                                  @endif />
                                             <span>%（百分之）</span>
                                                 <span class="ml10 gray-info">请输入最多保留小数点后一位的数字</span>
                                                 <span class="error-div"><label>*请输入数字格式,可保修小数点后一位,例:0.1</label></span>
                                           </p>
                                           <p>
                                             <input type="checkbox" name="xyk_yuan" id="" value="1" @if($xyk_yuan_num <> 0 && $xyk_status==1) checked @endif />
                                             <span>每次</span>
                                             <input class="card-input card-txt-price" type="text" name="xyk_yuan_num" id=""  @if($xyk_status==1)
                                             @if($xyk_yuan_num==0)
                                             value="" 
                                             @else
                                                  value="{{$xyk_yuan_num}}"
                                                  @endif 
                                                  @endif />
                                             <span>元（封顶）</span>
                                             <span class="ml10 gray-info">请输入10的整数倍的数字</span>
                                             <span class="error-div"><label>*请输入数字格式,且须为10的整数倍,例:100</label></span>
                                           </p>
                                           <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                           <p class="card-info total-error error-div"><span class="juhuang">请设置超出次数收费的百分比或者封顶金额~</span></p>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody></table> 
                              </div>
                          </div>
                         @else
                            <div class="tbl-list-tool-panle">
                              <p><b>单车付款刷信用卡免费次数：</b></p>
                             <div class="radio-label"><input type="radio" value="0" name="xyk_status" id=""><span>不限</span></div>
                             <div class="radio-label">

                                 <table  cellpadding="0" cellspacing="0">
                                     <tr>
                                         <td valign="top">
                                             <input type="radio" name="xyk_status" id="" value="1">
                                             <input class="card-input card-txt-count" type="text" name="xyk_number">
                                         </td>
                                         <td valign="top"><span>次；超出次数收费：</span></td>
                                         <td valign="top">
                                             <p>
                                                 <input type="checkbox" name="xyk_per" id="" value="1">
                                                 <span>刷卡金额的</span>
                                                 <input class="card-input card-txt-price max-valite" type="text" name="xyk_per_num" id="" value="" >
                                                 
                                                 <span>%（百分之）</span>
                                             </p>
                                             <p>
                                                 <input type="checkbox" name="xyk_yuan" id="" value="1">
                                                 <span>每次</span>
                                                 <input class="card-input card-txt-price" type="text" name="xyk_yuan_num" id="" value="" >
                                                  
                                                 <span>元（封顶）</span>
                                             </p>
                                             <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                             <p class="card-info total-error error-div"><span class="juhuang">请设置超出次数收费的百分比或者封顶金额~</span></p>
                                         </td>
                                     </tr>
                                 </table>
                             </div>
                         </div>
                         @endif
                         @if(isset($jjk_status))
                          <div class="tbl-list-tool-panle valite-3">
                              <p><b>单车付款刷借记卡免费次数：</b></p>
                              <div class="radio-label"><input type="radio" value="0" name="jjk_status" id="" @if($jjk_status==0) checked @endif ><span>不限</span></div>
                              <div class="radio-label">
                                
                                    <table  cellpadding="0" cellspacing="0">
                                      <tbody><tr>
                                        <td valign="top">
                                          <input type="radio" name="jjk_status" id="" value="1" @if($jjk_status==1)   checked @endif>
                                          <input class="card-input card-txt-count" type="text" name="jjk_number"  @if($jjk_status==1)
                                                  value="{{$jjk_number}}" >
                                                  @endif
                                        </td>
                                        <td valign="top">
                                            <span class="mt5 inlineblock">次；超出次数收费：</span>
                                            <span class="error-div"><label>*请输入整数格式的数字</label></span>
                                            <div class="clear"></div>
                                            <div class="ml50">
                                           <p>
                                             <input type="checkbox" name="jjk_per" id="" value="1" @if($jjk_status==1 && $jjk_per_num <> 0) checked @endif >
                                             <span>刷卡金额的</span>
                                             <input class="card-input card-txt-price max-valite" type="text" name="jjk_per_num" id="" @if($jjk_status==1)
                                             @if($jjk_per_num==0)
                                             value=""
                                             @else 
                                                  value="{{$jjk_per_num}}"
                                                  @endif 
                                                  @endif />
                                             <span>%（百分之）</span>
                                             <span class="ml10 gray-info">请输入最多保留小数点后一位的数字</span>
                                             <span class="error-div"><label>*请输入数字格式,可保修小数点后一位,例:0.1</label></span>
                                           </p>
                                           <p>
                                             <input type="checkbox" name="jjk_yuan" id="" value="1" @if($jjk_yuan_num <> 0 && $jjk_status==1) checked @endif >
                                             <span>每次</span>
                                             <input class="card-input card-txt-price" type="text" name="jjk_yuan_num" id="" @if($jjk_status==1)
                                             @if($jjk_yuan_num == 0)
                                             value=""
                                             @else
                                                  value="{{$jjk_yuan_num}}"
                                                  @endif
                                                  
                                                  @endif />
                                             <span>元（封顶）</span>
                                             <span class="ml10 gray-info">请输入10的整数倍的数字</span>
                                             <span class="error-div"><label>*请输入数字格式,且须为10的整数倍,例:100</label></span>
                                           </p>
                                           <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                           <p class="card-info total-error error-div"><span class="juhuang">请设置超出次数收费的百分比或者封顶金额~</span></p>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody> </table> 
                              </div>
                         </div>
                         @else
                            <div class="tbl-list-tool-panle">
                            <p><b>单车付款刷借记卡免费次数：</b></p>
                            <div class="radio-label"><input type="radio" value="0"name="jjk_status" id=""><span>不限</span></div>
                            <div class="radio-label">

                                <table  cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top">
                                            <input type="radio" name="jjk_status" id="" value="1" >
                                            <input class="card-input card-txt-count" type="text" name="jjk_number"
                                                  value="" >
                                                   
                                        </td>
                                        <td valign="top"><span>次；超出次数收费：</span></td>
                                        <td valign="top">
                                            <p>
                                                <input type="checkbox" name="jjk_per" id="" value="1" 
                                                <span>刷卡金额的</span>
                                                <input class="card-input card-txt-price max-valite" type="text" name="jjk_per_num" id=""
                                                  value="" >
                                                   
                                                <span>%（百分之）</span>
                                            </p>
                                            <p>
                                                <input type="checkbox" name="jjk_yuan" id="" value="1" 
                                                <span>每次</span>
                                                <input class="card-input card-txt-price" type="text" name="jjk_yuan_num" id=""
                                                  value="">
                                                <span>元（封顶）</span>
                                            </p>
                                            <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                            <p class="card-info total-error error-div"><span class="juhuang">请设置超出次数收费的百分比或者封顶金额~</span></p>
                                        </td>
                                      </tr>
                                    </table> 
                              </label>
                         </div>
                         @endif

                       
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                          @if($daili['dl_status'] == 4)
                          <p class="tac">
                          <a href="javascript:;" class="btn btn-danger fs18 chargeStandard">保存修改</a>
                         </p>
                         <p class="mt10 red tac"><span>温馨提示：</span>保存各项修改内容后，请到“竞争分析”页面一并提交重审！</p>
                         @else
                         <p class="tac">
                          <a href="javascript:;" class="btn btn-danger fs18 chargeStandard">修改</a>
                         </p>
                         @endif
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='step' value="7">
                         <input type='hidden' name='_token' value="{{csrf_token()}}">
                       </form>

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
                <div class="clear"></div>
            </div>
        </div>

@endsection

@section('js')
     <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt","module/custom/custom.dealer.step.common.fix.jquery",],function(a,b,c){
           
        });
    </script>
    
@endsection