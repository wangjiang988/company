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
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li class="cur"><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                <div id="vue">
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">补贴情况</h2>
                       <div class="m-t-10"></div>
                        <form action="/dealer/ajaxsubmitdealer/save-step/{{$dealer_id}}" name="next-form" method="post">
                        <div class="tbl-list-tool-panle valite-1 nomargin">
                                <p><b>国家节能补贴：</b></p>
                                <div class="radio-label ml20 mt5"><input @click="nothing" v-model="provide" value="0" type="radio" name="bt_status" id=""><span>不提供</span></div>
                                <div class="radio-label ml20 mt5">

                                    <table  cellpadding="0" cellspacing="0">
                                        <tbody><tr>
                                            <td valign="top">
                                                <input @click="provides" type="radio" v-model="provide" name="bt_status" id="" value="1">
                                                <span>提供</span>
                                                <p class="ml20">
                                                    <input @click="selectItem(1)" v-model="item" type="radio" name="bt_time_status" id="" value="1"><span>经销商代办上牌的，交车上牌时当场兑现；由客户本人上牌的，上牌资料齐全后，经销商垫付给客户，</span>
                                                    <br><span>时限</span>
                                                    <input @focus="focusInput(1)" v-model="work" class="card-input card-txt-price" type="text" name="work_day" id="">
                                                    <span>个工作日。</span>
                                                    <span class="error-div red" :style=" {display:error1 ? 'inline-block' : 'none'}">*输入有误，请输入正确的整数格式，例如：2</span>
                                                    <span class="gray"  :style=" {display:!error1 ? 'inline-block' : 'none'}">（请输入整数）</span>
                                                </p>
                                                <p class="ml20">
                                                    <input @click="selectItem(2)" v-model="item" type="radio" name="bt_time_status" id="" value="2"><span>上牌资料齐全后，经销商将所有资料交给汽车厂商，厂商直接付给客户，或者（厂商付经销商再由）</span>
                                                    <br><span>经销商付给客户，时限</span>
                                                    <input @focus="focusInput(2)" v-model="time" class="card-input card-txt-price" type="text" name="work_month" id="">
                                                    <span>个月。</span>
                                                    <span class="error-div red" :style=" {display:error2 ? 'inline-block' : 'none'}">*输入有误，请输入正确的整数格式，例如：2</span>
                                                    <span class="gray"  :style=" {display:!error2 ? 'inline-block' : 'none'}">（请输入整数）</span>
                                                </p> 

                                            </td> 

                                        </tr>
                                    </tbody></table>
                                </div>
                            </div>
                            <p><b>地方政府置换补贴：</b><input type="checkbox" name="bt_gov" id="" value="1" <?php if($bt_gov==1){echo 'checked';} ?> >可为客户提供协助</p>
                            <p><b>厂家或经销商置换补贴：</b><input type="checkbox" name="bt_factory" id="" value="1" <?php if($bt_factory==1){echo 'checked';} ?> >有</p>
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            @if($daili['dl_status'] == 4)
                              <p class="tac">
                                <a @click="subsidy" href="javascript:;" class="btn btn-danger fs18 subsidy">保存修改</a>
                             </p>
                             <p class="mt10 red tac"><span>温馨提示：</span>保存各项修改内容后，请到“竞争分析”页面一并提交重审！</p>
                             @else
                              <p class="tac">
                                <a @click="subsidy" href="javascript:;" class="btn btn-danger fs18 subsidy">修改</a>
                             </p>
                             @endif
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='step' value="8">
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


                </div>
            </div>

       

 
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["module/vue.custom/dealer/step9","bt"],function(a){
            a.init({{$bt_status}},'{{$bt_work_day}}','{{$bt_work_month}}')
        })
    </script>
@endsection


