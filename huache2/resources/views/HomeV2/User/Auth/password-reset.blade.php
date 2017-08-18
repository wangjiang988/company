@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">修改密码</span><div class="mt5"></div></h4>
        <div class="content-wapper ">
            <div class="hd">
                <ul>
                    <li><span>1</span><label>验证身份</label></li>
                    <li class="cur"><span>2</span><label class="juhuang">修改登录密码</label></li>
                    <li><span>3</span><label>完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            {!! Form::open(['url'=>'','role'=>'form']) !!}
            <div class="form" v-cloak>
                <br><br>
                <table class="pwd-tbl ml50"  style="width:700px;">
                    <tr>
                        <td width="101" align="right">原密码</td>
                        <td width="380">
                            <div class="input-wrapper ml20">
                                <input v-cloak  @blur="checkPwdS" @focus="setPwd3Status" maxlength="20" v-model="form.pwdS" :value="form.pwd" placeholder="" name="old_pwd" type="text" :class="{'form-input':true,'form-input-def':true,'error-bg':pwd3InputStatus==2 || pwd3InputStatus==3}" />
                            </div>
                            
                        </td>
                        <td width="219">
                            <span v-cloak class="form-error" v-show="pwd3InputStatus==3">
                                <i class="error-icon error-icon-error fl"></i>
                                <span class="fl mt5">不足6位</span>
                                <span class="clear"></span>
                            </span>
                            <span v-cloak class="form-error" v-show="sourcePwd == 2">原密码输入错误，请重新尝试~</span>
                            <span v-cloak class="form-error" v-show="sourcePwd == 3">原密码输入错误，您还有@{{count}}次机会~</span>
                            <span v-cloak class="error-error reg-icon-success" v-show="pwdInputStatus == 1"></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">设置密码</td>
                        <td>
                            <div class="input-wrapper ml20 ">
                                <input v-cloak v-show="isOpenEye" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" v-model="form.pwd" :value="form.pwd2" placeholder="6-20位数字、符号或字母（区分大小写）组合" name="pwd" type="text" :class="{'form-input':true,'form-input-def':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}" />
                                <div v-show="!isOpenEye" class="psr">
                                    <input v-cloak v-model="form.pwd" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" :value="form.pwd" name="pwd" type="password" :class="{'form-input':true,'form-input-def':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}"/>
                                    <span v-show="!isPwdInput" class="pwd-see" @click="pwdSee">6-20位数字、符号或字母（区分大小写）组合</span>
                                </div>
                                <div class="icon-wrapper">
                                    <span v-cloak v-show="isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-on pointer">>隐藏</span>
                                    <span v-cloak v-show="!isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-off pointer">>显示</span>
                                </div>
                            </div>
                            
                        </td>
                        <td>
                            <div style="margin-left:0;" class="pwd-strong " v-show="seePwdStrong && pwdInputStatus != 1">
                                <label>密码强度：</label>
                                <span :class="{'p-s-less':true, pwdcur:pwdStrongStatus==1}">低</span>
                                <span :class="{'p-s-normal':true, pwdcur:pwdStrongStatus==2}">中</span>
                                <span :class="{'p-s-max':true, pwdcur:pwdStrongStatus==3}">高</span>
                            </div>
                            <span v-cloak class="form-error " v-show="pwdInputStatus==3">
                                <i class="error-icon error-icon-error fl"></i>
                                <span class="fl mt5">不足6位</span>
                                <span class="clear"></span>
                            </span>
                            <span v-cloak class="error-icon error-icon-success " v-show="pwdInputStatus == 1"></span>
                        </td>
                    </tr>
                    <tr v-cloak v-show="!isOpenEye">
                        <td align="right">重复密码</td>
                        <td>
                            <div class="psr inline-block ml20">
                                <input @blur="checkPwd2" @focus="setPwd2Status" v-model="form.pwd2" :value="form.pwd2" maxlength="20" name="pwd2" type="password" :class="{'form-input':true,'form-input-def':true,'error-bg':pwd2InputStatus==2 || pwd2InputStatus==3}"/>
                                <span v-show="!isPwd2Input" class="pwd-see" @click="pwdSee">再输入一遍密码</span>
                            </div>
                            
                        </td>
                        <td>
                            <span v-cloak class="form-error" v-show="pwd2InputStatus==3">
                                <i class="error-icon error-icon-error fl"></i>
                                <span class="fl mt5">两次密码输入不一致</span>
                                <span class="clear"></span>
                            </span>
                            <span v-cloak class="error-icon error-icon-success" v-show="pwd2InputStatus == 1"></span>
                        </td>
                    </tr>
                </table>
                <p class="tac"><button @click="send" type="button" class="btn btn-s-md btn-danger w120 mt20">下一步</button></p>
            </div>
            {!! Form::close() !!}
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>

        </div>

    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-pwd-step2", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init('{{ route('account.reset') }}','{{ route('account.pwdEnd',['success'=>'ok']) }}','{{ route('account.pwdEnd',['success'=>'error']) }}');
        })
    </script>
@endsection