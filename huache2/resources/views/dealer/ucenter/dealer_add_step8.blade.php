@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                <div class="custom-set-flow-step-wrapper">
                    <ul class="custom-set-flow-step">
                        <li><span>基本资料</span></li>
                        <li><span>服务专员</span></li>
                        <li><span>保险条件</span></li>
                        <li><span>上牌条件</span></li>
                        <li><span>临牌条件</span></li>
                        <li><span>免费提供</span></li>
                        <li><span>杂费标准</span></li>
                        <li class="prev"><span>刷卡标准</span></li>
                        <li class="cur cur-step"><span>补贴情况</span></li>
                        <li class="last"><span>竞争分析</span></li>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div id="vue">
                    <div class="content-wapper ">
                        <h2 class="title weighttitle">补贴情况</h2>
                        <div class="m-t-10"></div>
                         @if($daili['dl_step'] == 8)
                         <form action="/dealer/ajaxsubmitdealer/next-step/{{$dealer_id}}" method="post" name="next-form">
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
    						<p><b>地方政府置换补贴：</b><input type="checkbox" name="bt_gov" id="" value="1">可为客户提供协助</p>
                            <p><b>厂家或经销商置换补贴：</b><input type="checkbox" name="bt_factory" id="" value="1">有</p>
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            <p class="tac">
                                 <a @click="subsidy" href="javascript:;" class="btn btn-danger fs18 subsidy isadd">下一步</a>
                                 <a href="/dealer/editdealer/check/{{$id}}/step7"  class="juhuang tdu ml5"><span>返回上一步</span></a>
                             </p>
                              <input type='hidden' name='id' value="{{$id}}">
                             <input type='hidden' name='_token' value="{{csrf_token()}}">
                        </form>
                        <div class="error-div tac" :class="error ? 'show' : 'hide'" id="inputerror"><label class="red">*请设置国家节能补贴提供的方式~</label></div>
                                                

                        @else
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
                              <p class="tac">
                                <a @click="subsidy" href="javascript:;" class="btn btn-danger fs18 subsidy">下一步</a>
                                <a href="/dealer/editdealer/check/{{$id}}/step7"  class="juhuang tdu ml5"><span>返回上一步</span></a>
                             </p>
                             <input type="hidden" name="type" value="check">
                             <input type='hidden' name='id' value="{{$id}}">
                             <input type='hidden' name='step' value="8">
                             <input type='hidden' name='_token' value="{{csrf_token()}}">
                        </form>
                        <div class="error-div tac" id="inputerror"><label class="red">*请设置国家节能补贴提供的方式~</label></div>
                                                
                     @endif
                    </div>
                </div>

            </div>


@endsection

@section('js')
   <script type="text/javascript">
        seajs.use(["module/vue.custom/dealer/step9","bt"],function(a){
            @if($daili['dl_step'] != 8)
            a.init({{$bt_status}},'{{$bt_work_day}}','{{$bt_work_month}}')
            @endif
        })
    </script>
@endsection


