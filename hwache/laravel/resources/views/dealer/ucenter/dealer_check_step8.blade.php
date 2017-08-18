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
                <div class="content-wapper nomagrain">
                    <h2 class="title weighttitle">补贴情况</h2>
                    <div class="m-t-10"></div>
                        <div class="tbl-list-tool-panle">
                            <p><b>国家节能补贴：</b></p>
                            <label class="radio-label"><input type="radio" name="bt_status" id="" disabled <?php if($bt_status==0){echo 'checked';} ?>><span>不提供</span></label>
                            <label class="radio-label">

                                <table  cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top">
                                            <input type="radio" name="bt_status" id="" value="1" disabled <?php if($bt_status==1){echo 'checked';} ?>>
                                            <span>提供</span>
                                            <p class="ml20">
                                                <input type="radio" name="bt_day" id="" value="1" disabled @if($bt_work_day <> 0) checked @endif>
                                                <span>经销商代办上牌的，交车上牌时当场兑现；由客户本人上牌的，上牌资料齐全后，经销商垫付给客户，</span>
                                                <br><span>时限</span>
                                                <input class="card-input card-txt-price" type="text" name="work_day" id="" disabled @if($bt_status==1 && $bt_work_day <> 0)value="{{$bt_work_day}}"
                                                @else value="" @endif>
                                                <span>个工作日。</span>
                                            </p>
                                            <p class="ml20">
                                                <input type="radio" name="bt_day" id="" value="1" disabled @if($bt_work_month <> 0) checked @endif>
                                                <span>上牌资料齐全后，经销商将所有资料交给汽车厂商，厂商直接付给客户，或者（厂商付经销商再由）</span>
                                                <br><span>经销商付给客户，时限</span>
                                                <input class="card-input card-txt-price" type="text" name="work_month" id="" @if($bt_status==1 && $bt_work_month <> 0)value="{{$bt_work_month}}"
                                                @else value="" @endif disabled>
                                                <span>个月。</span>
                                            </p>
                                            

                                        </td>

                                    </tr>
                                </table>
                            </label>
                        </div>
                        <p><b>地方政府置换补贴：</b><input type="checkbox" name="bt_gov" id="" value="1" disabled <?php if($bt_gov==1){echo 'checked';} ?>>可为客户提供协助</p>
                                            <p><b>厂家或经销商置换补贴：</b><input type="checkbox" name="bt_factory" id="" value="1" disabled <?php if($bt_factory==1){echo 'checked';} ?>>有</p>




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


<div class="box" ms-include-src="footernew"></div>

@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"]);
    </script>
@endsection


