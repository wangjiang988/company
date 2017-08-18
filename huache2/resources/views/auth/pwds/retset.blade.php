@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.not_login') @endsection
@section('content') 
    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                <ul>
                    <li>1.填写账号</li>
                    <li>2.验证身份</li>
                    <li class="cur">3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul>
                <form action="{{ route('pwd.postRest') }}" id="password-step-4">
                    <div class="form" >
                        <br>
                        <table class="reg-form-tbl wauto mauto">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ csrf_token() }}" />
                            <tr>
                                <td align="right" width="">设置密码</td>
                                <td width="">
                                    <div class="input-wrapper">
                                        <input type="hidden" name="phone" value="{{ $data['phone'] }}" />
                                        {!! Form::hidden('password_confirmation','',['id'=>'password_confirmation']) !!}
                                        <input v-cloak v-show="isOpenEye" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" v-model="form.pwd" :value="form.pwd" placeholder="6-20位数字、符号或字母（区分大小写）组合" name="password" type="text" :class="{'form-input':true,'form-input-def-long':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}" />
                                        <div v-show="!isOpenEye" class="psr">
                                            <input v-cloak v-model="form.pwd" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" :value="form.pwd" type="password" :class="{'form-input':true,'form-input-def-long':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}"/>
                                            <span v-cloak v-show="!isPwdInput" class="pwd-see" @click="pwdSee">6-20位数字、符号或字母（区分大小写）组合</span>
                                        </div>
                                        <div class="icon-wrapper">
                                            <span v-cloak v-show="!isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-on pointer"></span>
                                            <span v-cloak v-show="isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-off pointer"></span>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td width="">
                                    <div class="psr">
                                        <div style="width:300px;top:5px;top:-11px;" class="pwd-strong psa nomargin" v-show="(pwdInputStatus == 0 || pwdInputStatus == 2) && form.pwd.length > 6">
                                            <label>密码强度：</label>
                                            <span :class="{'p-s-less':true, pwdcur:pwdStrongStatus==1}">低</span>
                                            <span :class="{'p-s-normal':true, pwdcur:pwdStrongStatus==2}">中</span>
                                            <span :class="{'p-s-max':true, pwdcur:pwdStrongStatus==3}">高</span>
                                        </div>
                                     
                                        <span style="top:-19px;width: 100px;left:-10px;" v-cloak class="form-error ib psa" v-show="pwdInputStatus==3">
                                            <i class="reg-icon reg-icon-error fl" style="position: relative;"></i>
                                            <span class="fl mt5 ml5">不足6位</span>
                                            <span class="clear"></span>
                                        </span>
                                        <span style="top:-19px" v-cloak class="reg-icon reg-icon-success psa" v-show="pwdInputStatus == 1"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-cloak v-show="!isOpenEye">
                                <td align="right">重复密码</td>
                                <td>
                                    <div class="psr inline-block">
                                        <input @blur="checkPwd2" @focus="setPwd2Status" v-model="form.pwd2" :value="form.pwd2" maxlength="20" name="password_confirmation" type="password" :class="{'form-input':true,'form-input-def-long':true,'error-bg':pwd2InputStatus==2 || pwd2InputStatus==3}"/>
                                        <span v-show="!isPwd2Input" class="pwd-see" @click="pwdSee">再输入一遍密码</span>
                                    </div>
                                    
                                </td>
                                <td>
                                    <div class="psr">
                                        <span style="width:300px;left:-12px;top:-19px;" v-cloak class="form-error ib psa" v-show="pwd2InputStatus==3">
                                            <i class="reg-icon reg-icon-error fl "  style="position: relative;"></i>
                                            <span class="fl mt5 ml5">两次密码输入不一致</span>
                                            <span class="clear"></span>
                                        </span>
                                        <span style="top:-19px;width: 100px;left:0px;" v-cloak class="form-error ib psa" v-show="pwd2InputStatus==1">
                                            <span v-cloak class="reg-icon reg-icon-success psa" v-show="pwd2InputStatus == 1"></span>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                        </table>
                        <br><br>
                        <div class="text-center">
                            <div class="countdown inline-block">
                                <div class="time-wrapper" v-cloak>
                                    <span>@{{time.minites[0]}}</span>
                                    <span>@{{time.minites[1]}}</span>
                                    <span class="symbol"><span>:</span></span>
                                    <span>@{{time.seconds[0]}}</span>
                                    <span>@{{time.seconds[1]}}</span>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <p class="tac"><button :disabled="isLoading" @click="send" type="button" class="btn btn-s-md btn-danger w120 mt20">下一步</button></p>


                    </div>

                </form>
            </div>
        </div>
        <br><br><br>


    </div>

    @endsection
    @section('footer')
        @include('HomeV2._layout.footer')
    @endsection

    @section('login','')

    @section('js')
        <script type="text/javascript">
            seajs.use(["vendor/vue","module/pwd/password-step-4", "module/common/common","bt"],function(a,b,c){
                b.initUrl("{{ route('pwd.pwdOver') }}","{{ route('pwd.pwdSuccess') }}");
                b.init('{{ $data['start_date'] }}','{{ $data['end_date'] }}','{{ $data['thisData'] }}');
            })
        </script>
    @endsection