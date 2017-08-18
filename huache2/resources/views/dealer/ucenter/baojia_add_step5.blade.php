@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <div class="custom-offer-step-wrapper">
                        <ul>
                          <li>车型价格</li>
                          <li>车况说明</li>
                          <li class="prev">选装精品</li>
                          <li class="cur">收费标准</li>
                          <li class="last">其他事项</li>
                          <div class="clear"></div>
                        </ul>
                    </div>


                    <form action="/dealer/baojia/edit/{{$baojia['bj_id']}}/5" name="baojia-submit-form" method="post">
                        <input type='hidden' name='_token' value="{{csrf_token()}}">
                        <div class="content-wapper ml-10">


                          <div class="tbl-list-tool-panle valite-txt">
                              <p><span class="blue weight">一、上牌（车辆注册登记）：</span></p>
                              <p class="ml26">本地客户自由办理，异地客户本人办理</p>
                              <div>
                                    <span class="ml26 fl mt5"><b>代办上牌服务费金额 </b> ￥</span>
                                    <div class="edit-wp psr fl ml5">
                                        <input maxlength="8" class="abs" type="text" name="bj_shangpai_price" value="{{$dealerInfo['dl_shangpai_fee']}}">
                                        <span class="edit"></span>
                                    </div>
                                    <span class="mt5 error-info error-div fl ml10 juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                    <span class="mt5 fl ml10 gray-info">请输入100的整数倍的数字</span>
                                    <div class="clear"></div>
                              </div>
                           </div>

                           <div class="tbl-list-tool-panle  valite-txt" >
                              <p class="blue"><b>二、上临时牌照（临时移动牌照）</b></p>
                              <p class="ml26">本地客户自由办理</p>
                              <div>
                                    <span class="ml26 fl mt5"><b>代办车辆临时牌照（每次）服务费金额：</b> ￥</span>
                                    <div class="edit-wp psr fl ml5">
                                        <input maxlength="8" class="abs" type="text" name="bj_linpai_price" value="{{$dealerInfo['dl_linpai_fee']}}">
                                        <span class="edit"></span>
                                    </div>
                                    <span class="mt5 error-info error-div fl ml10 juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                    <span class="mt5 fl ml10 gray-info">请输入100的整数倍的数字</span>
                                    <div class="clear"></div>
                              </div>
                           </div>

                          <p class="blue"><b>三、其他收费（常规）</b><a href="javascript:;" class="juhuang tdu ml10 controlModelAdd">添加</a></p>
                          <table id="controlModel" class="tbl custom-info-tbl">
                             <tbody>
                               <tr>
                                   <th class="tac">费用名称</th>
                                   <th class="tac">金额</th>
                                   <th class="last">
                                      操作
                                   </th>
                               </tr>
                               @if(count($otherPrice)>0)
                               @foreach($otherPrice as $v)
                               <tr class="def-temp">
                                 <td class="tac" width="260" valign="middle">
                                    <div class="btn-group none btn-jquery-event-use-id btn-auto">
                                        <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                            <span class="dropdown-label"><span>--请选择--</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-company">
                                            <input type="hidden" name="giftname" >
                                            @if(count($allOtherPrice)>0)
                                            @foreach ($allOtherPrice as $otherprice)
                                            <li data-value="{{$otherprice['id']}}"><a><span>{{$otherprice['title']}}</span></a></li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <label class="save-label ">{{$v['other_name']}}</label>
                                 </td>
                                 <td class="tac" width="200" valign="middle">
                                    <div class="checkbox-wrapper inline counter-wrapper none">
                                        ￥
                                        <span class="prev none">-</span>
                                        <input maxlength="8" data-def="{{$v['other_price']}}" class="long" type="text" name="" id="" value="1">
                                        <span class="next none">+</span>
                                    </div>
                                    <label class="save-label"><span>￥</span>{{$v['other_price']}}</label>
                                    <div class="error-div"><label>*请输入数字格式,必须为100的整数倍,例:100</label></div>
                                 </td>
                                 <td class="tac" width="200">
                                     <div class="inline  ">
                                       <a href="javascript:;" class="btn btn-danger save hide" data-id="{{$v['id']}}" data-value="{{$v['other_id']}}">保存</a>
                                       <a href="javascript:;" class="ml10 edit" data-id="{{$v['id']}}" data-value="{{$v['other_id']}}">修改</a>
                                       <a href="javascript:;" class="ml10 cancel none" data-id="{{$v['id']}}" data-value="{{$v['other_id']}}">取消</a>
                                       <a href="javascript:;" class="ml10 del" data-id="{{$v['id']}}" data-value="{{$v['other_id']}}">删除</a>
                                     </div>
                                     &nbsp;
                                 </td>
                               </tr>
                               @endforeach
                               @else
                               <tr id="temp-file"><td colspan="3" class="tac"><div class="mt10"></div> <p>暂未添加任何的其他收费~</p></td></tr>
                               @endif

                            </tbody>
                          </table>

                          <div class="m-t-10"></div>

                          <p class="blue"><b>四、刷卡标准：</b></p>
                          <div class="tbl-list-tool-panle valite-3">
                              <p><b>单车付款刷信用卡免费次数：</b></p>
                              <div class="radio-label"><input type="radio" name="xyk_status" id=""  value='0' <?php if($dealerInfo['xyk_status']==0){echo 'checked';}?>><span>不限</span></div>
                              <div class="radio-label">

                                    <table cellpadding="0" cellspacing="0">
                                      <tbody>
                                      <tr>
                                        <td valign="top">
                                          <input type="radio" name="xyk_status" id="" value='1' <?php if($dealerInfo['xyk_status']==1){echo 'checked';}?>>
                                          <input maxlength="8" class="card-input card-txt-count abs" type="text" name="xyk_number" value="{{$dealerInfo['xyk_status']==1?$dealerInfo['xyk_number']:""}}">
                                        </td>

                                        <td valign="top">
            <span>次；超出次数收费：</span>
                                            <span class="error-div"><label>*请输入整数格式的数字</label></span>
                                            <div class="clear"></div>
                                            <div class="ml50">
                                           <p>
                                             <input type="checkbox" name="xyk[percent]" id="" {{$dealerInfo['xyk_per_num']>0?"checked":''}} class='clear-value'>
                                             <span>刷卡金额的</span>
                                             <input maxlength="8" class="card-input card-txt-price abs" type="text" name="xyk_per_num" id="" value="{{($dealerInfo['xyk_status']==1&&$dealerInfo['xyk_per_num']>0)?$dealerInfo['xyk_per_num']:""}}">
                                             <span>%（百分之）</span>
            <span class="ml10 gray-info">请输入最多保留小数点后一位的数字</span>
                                             <span class="error-div"><label>*请输入数字格式,可保修小数点后一位,例:0.1</label></span>
                                           </p>
                                           <p>
                                             <input type="checkbox" name="xyk[money]" id="" {{$dealerInfo['xyk_yuan_num']>0?"checked":''}} class='clear-value'>
                                             <span>每次</span>
                                             <input maxlength="8" class="card-input card-txt-price abs" type="text" name="xyk_yuan_num" id="" value="{{($dealerInfo['xyk_status']==1&&$dealerInfo['xyk_yuan_num']>0)?$dealerInfo['xyk_yuan_num']:""}}">
                                             <span>元（封顶）</span>
            <span class="ml10 gray-info">请输入10的整数倍的数字</span>
                                             <span class="error-div"><label>*请输入10的整数倍的数字</label></span>
                                           </p>
                                           <p class="card-info">温馨提示：可单选，也可以同时选择</p>
                                           <p class="card-info total-error error-div"><span class="juhuang">请设置超出次数收费的百分比或者封顶金额~</span></p>
            </div>
                                        </td>
                                      </tr>
                                    </tbody></table>
                              </div>
                          </div>
                          <div class="tbl-list-tool-panle valite-3">
                              <p><b>单车付款刷借记卡免费次数：</b></p>
                              <div class="radio-label"><input type="radio" name="jjk_status" id="" value='0' {{$dealerInfo['jjk_status']==0?"checked":""}}><span>不限</span></div>
                              <div class="radio-label mt10">

                                    <table cellpadding="0" cellspacing="0">
                                      <tbody><tr>
                                        <td valign="top">
                                          <input type="radio" name="jjk_status" id="" value='1' {{$dealerInfo['jjk_status']==1?"checked":""}}  >
                                          <input maxlength="8" class="card-input card-txt-count abs" type="text" name="jjk_number" value="{{$dealerInfo['jjk_status']==1?$dealerInfo['jjk_number']:""}}">
                                        </td>
                                        <td valign="top">
                                          <span>次；超出次数收费：</span>
                                            <span class="error-div"><label>*请输入整数格式的数字</label></span>
                                            <div class="clear"></div>
                                            <div class="ml50">
                                           <p>
                                             <input type="checkbox" name="" id="" {{$dealerInfo['jjk_status']==1?"checked":""}}  class='clear-value'>
                                             <span>刷卡金额的</span>
                                             <input maxlength="8" class="card-input card-txt-price abs max-valite" type="text" name="jjk_per_num" id="" value="{{$dealerInfo['jjk_status']==1?$dealerInfo['jjk_per_num']:""}}">
                                             <span>%（百分之）</span>
                                             <span class="ml10 gray-info">请输入最多保留小数点后一位的数字</span>
                                             <span class="error-div"><label>*请输入数字格式,可保修小数点后一位,例:0.1</label></span>
                                           </p>
                                           <p>
                                             <input type="checkbox" name="" id="" {{$dealerInfo['jjk_status']==1?"checked":""}} class='clear-value'>
                                             <span>每次</span>
                                             <input maxlength="8" class="card-input card-txt-price abs" type="text" name="jjk_yuan_num" id="" value="{{$dealerInfo['jjk_status']==1?$dealerInfo['jjk_yuan_num']:""}}">
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
                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>

                           <p class="tac">
                              <a href="/dealer/baojia/edit/{{$baojia['bj_id']}}/3" class="btn btn-danger sure fs18 ml20">返回上一步</a>
                                <a href="javascript:;" class="btn btn-danger fs18 ml20 baojia-submit-button" data-step='5' data-type='1'>下一步</a>
                                <a href="javascript:;" class="btn btn-danger sure fs18 ml20 baojia-submit-button" data-step='5' data-type='2'>保存并退出</a>
                                <a href="javascript:;" class="btn btn-danger sure fs18 ml20 reset-form">重置</a>
                            </p>

                        </div>

                    </form>
                    <div class="m-t-200"></div>

                      <div id="delControlModel" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd  tac">确定要删除此收费项目吗？</p>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 delControlModel">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="applyControlModel" class="popupbox">
                        <div class="popup-title">申请新项目</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs14">
                                    <table class="custom-form-tbl">
                                        <tr>
                                            <td width="70" valign="top" class="nopadding"><b>温馨提示：</b></td>
                                            <td class="nopadding">请输入该经销商其他的常规杂费名称，由平台审核通过后可进行添加。</td>
                                        </tr>
                                    </table>
                                </p>
                                <form action="/dealer/baojia/ajaxsubmit/apply-new-other-price" name="apply-form"  method="post">
                                  <input type='hidden' name='_token' value="{{csrf_token()}}">
                                  <div class="m-t-10"></div>
                                  <div class="m-t-10"></div>
                                  <center>
                                    <table class="custom-form-tbl" style="width: 70%">
                                        <tr>
                                            <td align="right" width="" valign="middle">
                                                <label>名称：</label>
                                            </td>
                                            <td>
                                              <input placeholder="" type="text" name="title" class="form-control custom-control ">
                                              <p class="error-div"><span class="juhuang">*请填写名称！</span></p>

                                            </td>
                                        </tr>
                                    </table>
                                  </center>
                                  <div class="m-t-10"></div>
                                  <div class="m-t-10"></div>
                                </form>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sub-apply-new">提交</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
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


                     <div id="tip-info-success" class="popupbox">
                         <div class="popup-title">温馨提示</div>
                         <div class="popup-wrapper">
                             <div class="popup-content">
                                  <div class="m-t-10"></div>
                                  <p class="fs14 pd tac succeed constraint">
                                     <span class="tip-tag"></span>
                                     <span class="tip-text">您已成功提交工单！工单内容经平台审核后方可使用。</span>
                                     <div class="clear"></div>
                                  </p>
                                  <p class="fs14 tac">查看审核进度方请前往：便捷工具->工单进度管理</p>
                                  <div class="m-t-10"></div>
                             </div>
                             <div class="popup-control">
                                 <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure">确认</a>
                                 <div class="clear"></div>
                             </div>
                         </div>
                     </div>

                </div>

@endsection
@section('js')
<script type="text/template" id="controlModel-tmpl">
        <tr class="def-add">
           <td class="tac" width="260" valign="middle">
              <div class="btn-group btn-jquery-event-use-id btn-auto">
                  <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                      <span class="dropdown-label"><span>--请选择--</span></span>
                      <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-company">
                      <input type="hidden" name="insuranceCompany" />
                      @if(count($allOtherPrice)>0)
                      @foreach ($allOtherPrice as $otherprice)
                      <li data-value="{{$otherprice['id']}}"><a><span>{{$otherprice['title']}}</span></a></li>
                      @endforeach
                      @endif
                  </ul>

              </div>
              <label class="save-label hide"></label>
           </td>
           <td class="tac" width="200" valign="middle">
              <div class="checkbox-wrapper inline counter-wrapper hide">
                  ￥
                  <span class="prev none">-</span>
                  <input class="long" type="text" name="" id="" value="" data-def="">
                  <span class="next none">+</span>
              </div>
              <label class="save-label hide"></label>
              <div class="error-div"><label>*请输入100的整数倍数字</label></div>
              <div class="gray tac none">请输入100的整数倍数字</div>
           </td>
           <td class="tac" width="200">
               <div class="inline hide ">
                 <a href="javascript:;" class="btn btn-danger save " data-id='0'>保存</a>
                 <a href="javascript:;" class="ml10 edit none">修改</a>
                 <a href="javascript:;" class="ml10 cancel"  data-id='0'>取消</a>
                 <a href="javascript:;" class="ml10 del none">删除</a>
               </div>
               <span class="init">不在列表中？<a href="javascript:;" class="ml10 applynew">申请新项目</a></span>
               &nbsp;
           </td>
       </tr>
    </script>


<script type="text/javascript">
var _bj_id = "{{$baojia['bj_id']}}"
seajs.use(["module/custom/custom_admin",
           "module/custom/custom.admin.common.jquery",
           "module/custom/custom.admin.charge.jquery",
            "module/common/common",
            "bt"],
           function(a,b,c){

});
</script>
@endsection