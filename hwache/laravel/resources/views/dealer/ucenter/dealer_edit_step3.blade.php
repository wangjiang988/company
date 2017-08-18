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
                             <li class="cur"><a hrf="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>

                    <div class="content-wapper ">
                       <h2 class="title weighttitle">上牌条件</h2>
                       <div class="m-t-10"></div>
                        <form action="/dealer/ajaxsubmitdealer/save-step/{{$dealer_id}}" method="post" name="next-form">
                            <div class="tbl-list-tool-panle valite-xx">
                              <p><b>上牌（车辆注册登记）：</b></p>
                              <label class="radio-label"><input type="radio" name="dl_shangpai" id="" value='1' checked 
                              @if($daili['dl_shangpai'] == 1) checked="checked"; @endif
                              ><span>本地客户上牌手续必须由经销商代办</span>
                              </label>
                              <label class="radio-label"><input type="radio" name="dl_shangpai" id="" @if($daili['dl_shangpai'] == 0) checked="checked"; @endif ><span>本地客户自由办理</span></label>
                              <div class="clear"></div>
                              <span class=" fl"><b>代办上牌服务费金额</b>  ￥</span> 
                                    <div class="edit-wp psr  fl ml5">
                                        <input maxlength="8" type="text" name="dl_shangpai_fee" value="{{$daili['dl_shangpai_fee']}}">

                                        <span class="edit"></span>
                                       
                                    </div>
                              <span class="error-info error-div fl ml10 juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                              <span class="fl ml10 gray-info fs14">请输入100的整数倍的数字</span>
                              <div class="clear"></div>
                          </div>
                          <div class="tbl-list-tool-panle valite-yy">
                              <p class=""><b>客户本人上牌违约赔偿</b></p>
                              <label class="radio-label ml26"><input type="radio" name="dl_shangpai_object" id="" value='1' @if($daili['dl_shangpai_object'] == 1) checked="checked"; @endif ><span>有此要求</span><span class="ml10"><b>客户本人上牌违约赔偿金额：</b>￥</span>
                                  <div class="edit-wp psr ml5">
                                      <input maxlength="8" class="noweight" type="text" name="dl_shangpai_object_fee" value="{{($daili['dl_shangpai_object'] == 1 && $daili['dl_shangpai_object_fee']>0)?$daili['dl_shangpai_object_fee']:''}}">
                                      <span class="edit"></span>
                                       <span class="error-info error-div juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                       <span class="error-info show ml10 gray-info fs14">请输入100的整数倍的数字</span>
                                  </div>
                              </label>
                              <label class="radio-label ml26"><input type="radio" name="dl_shangpai_object" id="" value='0' @if($daili['dl_shangpai_object'] == 0) checked="checked"; @endif><span>无此要求</span></label>
                         </div>


                       
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                         <p class="tac">
                         @if($daili['dl_status'] == 4)
                         <a href="javascript:;"  class="btn btn-danger fs18 registrationEvent">保存修改</a><p class="mt10 red tac"><span>温馨提示：</span>保存各项修改内容后，请到“竞争分析”页面一并提交重审！</p>
                         @else
                            <a href="javascript:;"  class="btn btn-danger fs18 registrationEvent">修改</a>
                         @endif
                         </p>
                         <input type='hidden' name='step' value="3">
                         <input type='hidden' name='id' value="{{$id}}">
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

@endsection

@section('js')
   <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){
           
        });
  </script>
  
@endsection


