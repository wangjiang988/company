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
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">临牌条件</h2>
                       <div class="m-t-10"></div>
                        <form action="/dealer/ajaxsubmitdealer/save-step/{{$dealer_id}}" name="next-form" method="post">
                        <div class="ml20">
                            <p class="fs14"><b>上临时牌照（临时移动牌照）:  </b>客户自由办理</p>
                            <div class="m-t-10"></div>
                            <p class="fs14">
                                <b>代办车辆临时牌照（每次）服务费金额：</b>
                                <span class="ml10">￥</span>
                                <input name="dl_linpai_fee" maxlength="8" type="text" class="ml10 form-control w100 inline-block positive-integer" value="{{$dl_linpai_fee}}">
                                <span class="error-info error-div juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                <span class="error-info error-div ml10 gray-info fs14">请输入100的整数倍的数字</span>
                            </p>
                         </div>
                        <!--  <div class="tbl-list-tool-panle">
                              <p><b>上临时牌照（临时移动牌照）</b></p>
                              <label class="radio-label"><input type="radio" name="dl_linpai" id="" value='1' @if($dl_linpai == 1) checked @endif)><span>车辆临时移动牌照手续必须由经销商代办（适用条件：a.经销商代办上牌，且客户选择上临牌  b.客户本人上牌）</span>
                              </label>
                              <label class="radio-label"><input type="radio" name="dl_linpai" id="" @if($dl_linpai == 0) checked @endif) ><span>本地客户自由办理</span></label>
                              <div class="clear"></div>
                              <span class=""><b>代办车辆临时牌照（每次）服务费金额：  ￥</b></span>
                                    <div class="edit-wp psr">
                                        <input maxlength="8" type="text" name="dl_linpai_fee" value="{{$dl_linpai_fee}}">
                                        <span class="edit"></span>
                                         <div class="clear"></div>
                                    </div>
                             <div class="error-info mt20 ml100 none juhuang">*请输入数字格式，且须为100的整数倍，例如100</div>
                              <span class="ml10 gray-info">请输入100的整数倍的数字</span>
                              <div class="clear"></div>
                          </div>                        -->

                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                         <p class="tac">
                         @if($daili['dl_status'] == 4)
                         <a href="javascript:;" class="btn btn-danger fs18 btn btn-danger fs18 registrationCondition-adv registrationEvent">保存修改</a>
                         <p class="mt10 red tac"><span>温馨提示：</span>保存各项修改内容后，请到“竞争分析”页面一并提交重审！</p>
                         @else
                            <a href="javascript:;" class="btn btn-danger fs18 btn btn-danger fs18 registrationCondition-adv registrationEvent">修改</a>
                         @endif
                         <input type='hidden' name='step' value="4">
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
        seajs.use(["module/custom/custom-step4", "module/common/common", "bt"],function(a,b,c){

        });
  </script>

@endsection

