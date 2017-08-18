@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('themes/reg.adv.css')}}" rel="stylesheet" />
@endsection
@section('nav') @include('_layout.nav') @endsection
@section('content')

    <div class="box" ms-include-src="regheader"></div>

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">快捷注册</div>
                <div class="form">
                    <table class="reg-form-tbl">
                        <tr>
                            <td width="100" align="right">手机号</td>
                            <td>
                                <input id="txtPhone" maxlength="11" @blur.stop-1="checkPhone"  @focus="setPhoneStatus" v-model="form.phone" :value="form.phone" name="phone" type="text" placeholder="中国大陆手机为11位数字" :class="{'form-input':true,'form-input-def':true,'error-bg':checkPhoneCode > 1}" />
                                <span v-cloak class="form-error inline-block" v-show=" checkPhoneCode == 4">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt5">格式有误，请重新输入~</span>
                                    <span class="clear"></span>
                                </span>
                                <span v-cloak class="reg-icon reg-icon-success" v-show="checkPhoneCode == 1"></span>
                                <span v-cloak class="form-error inline-block" v-show="checkPhoneCode == 2">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt5">不可注册！请更换其他号码注册～</span>
                                    <span class="clear"></span>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td align="right">设置密码</td>
                            <td>
                                <div class="input-wrapper">
                                    <input v-cloak v-show="isOpenEye" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" v-model="form.pwd" :value="form.pwd" placeholder="6-20位数字、符号或字母（区分大小写）组合" name="pwd" type="text" :class="{'form-input':true,'form-input-def':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}" />
                                    <div v-show="!isOpenEye" class="psr">
                                        <input v-cloak v-model="form.pwd" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" :value="form.pwd" name="pwd" type="password" :class="{'form-input':true,'form-input-def':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}"/>
                                        <span v-show="!isPwdInput" class="pwd-see" @click="pwdSee">6-20位数字、符号或字母（区分大小写）组合</span>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span v-cloak v-show="isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-on pointer"></span>
                                        <span v-cloak v-show="!isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-off pointer"></span>
                                    </div>
                                </div>
                                <div class="pwd-strong" v-show="pwdInputStatus == 0 || pwdInputStatus == 2">
                                    <label>安全程度：</label>
                                    <span :class="{'p-s-less':true, pwdcur:pwdStrongStatus==1}">弱</span>
                                    <span :class="{'p-s-normal':true, pwdcur:pwdStrongStatus==2}">中</span>
                                    <span :class="{'p-s-max':true, pwdcur:pwdStrongStatus==3}">强</span>
                                </div>
                                <span v-cloak class="form-error inline-block" v-show="pwdInputStatus==3">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt5">不足6位</span>
                                    <span class="clear"></span>
                                </span>
                                <span v-cloak class="reg-icon reg-icon-success" v-show="pwdInputStatus == 1"></span>
                            </td>
                        </tr>
                        <tr v-cloak v-show="!isOpenEye">
                            <td align="right">重复密码</td>
                            <td>
                                <div class="psr inline-block">
                                    <input @blur="checkPwd2" @focus="setPwd2Status" v-model="form.pwd2" :value="form.pwd2" maxlength="20" name="pwd2" type="password" :class="{'form-input':true,'form-input-def':true,'error-bg':pwd2InputStatus==2 || pwd2InputStatus==3}"/>
                                    <span v-show="!isPwd2Input" class="pwd-see" @click="pwdSee">再输入一遍密码</span>
                                </div>
                                <span v-cloak class="form-error inline-block" v-show="pwd2InputStatus==3">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt5">两次密码输入不一致</span>
                                    <span class="clear"></span>
                                </span>
                                <span v-cloak class="reg-icon reg-icon-success" v-show="pwd2InputStatus == 1"></span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">验证码</td>
                            <td>
                                <input maxlength="6" :disabled="!isSendCode" @blur.stop-1="checkCode" @focus="initCodeStatus" v-model="form.code" :value="form.code"  placeholder="6位数字" name="code" type="text" :class="{'form-input':true, 'form-input-code':true,'error-bg':checkCodeStatus == 3}" />
                                <button  v-show="checkCodeStatus!=1" :disabled="!(checkPhoneCode == 1 && pwdStatus == 1 && isSame)" v-cloak @click="getCode" type="submit" class="btn btn-s-md btn-danger w120 inline-block ml60 btn-code">
                                    <span v-cloak v-show="isCanCountDown && checkCodeStatus != 1 " class="red">@{{countDownTime}}s</span>@{{sendCodeTxt}}
                                </button>
                                <span v-cloak class="reg-icon reg-icon-success" v-show="checkCodeStatus == 1"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="right"></td>
                            <td>
                                <span class="form-error inline-block">
                                    <i v-cloak class="reg-icon reg-icon-warn fl" v-show="checkCodeStatus == 2 && isSendCode"></i>
                                    <span class="fl mt5"><span v-cloak v-show="checkCodeStatus == 2 && isSendCode" class="ml5 red">输入的验证码有误，请重新输入～ </span>
                                    <span class="clear"></span>
                                </span>
                            </td>
                        </tr>
                    </table>

                    <p class="text-center mt20" v-show="!isAgree && isReg">
                        <span v-cloak class="form-error inline-block" >
                            <i class="reg-icon reg-icon-error fl"></i>
                            <span class="fl mt5">成功注册请接受注册协议～</span>
                            <span class="clear"></span>
                        </span>
                    </p>

                    <div class="text-center mt20 notice-count notice-count-long" v-show="noticeCount > 0 && isSendCode">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long gray">短信验证码已经发出，请注意查收短信，今日还可获取<span>@{{noticeCount}}次</span>验证码！</span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-none" v-show="noticeCount == 0 && isSendCode">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long gray">短信验证码已经发出，请注意查收短信，今日注册短信验证码发送次数已达上限！</span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-none" v-show="noticeCount < 0 && isNotCanSendNotice">
                        <span v-cloak class="form-error inline-block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long">对不起，今日申请而未使用的注册验证码次数过多，该手机号注册功能已被保护，请明日再试！或者您可改用其他手机继续注册。如有紧急需求可联系华车客服，谢谢～</span>
                            <span class="clear"></span>
                        </span>
                    </div>

                    <p :class="{fs14:true, 'text-center':true, mt20:!(!isAgree && isReg)}">
                        <input type="checkbox" v-model="agree" value="1" />
                        <span class="ml5">同意<a href="#" class="blue">《注册协议》</a></span>
                    </p>

                    <button @click="send" type="submit" class="btn btn-s-md btn-danger w120 mt20">注 册</button>
                </div>
            </div>
        </div>
        <br><br><br>


        <div id="reg-error-info" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac succeed error constraint">
                        <span class="tip-tag fixed-bg-zero"></span>
                        <span class="tip-text"> 对不起，今日申请而未使用的注册验证码次数过多，该手机号注册功能已被保护，请明日再试！或者您可改用其他手机继续注册。如有紧急需求可联系华车客服，谢谢～</span>
                    <div class="clear"></div>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确定</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <input id="_token" type="hidden" value="{{ csrf_token() }}" />

    </div>

@endsection

@section('footer')
    <div class="box" ms-include-src="footer"></div>
@endsection

@section('login')
  @include('HomeV2._layout.login')
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/reg/reg.adv", "module/common/common","bt"],function(a,b,c){
        })
    </script>
@endsection