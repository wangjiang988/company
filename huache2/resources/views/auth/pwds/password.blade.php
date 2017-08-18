@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.not_login') @endsection
@section('content')


    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                @include('auth.layout.pwd-nav')
                <form action="{{ route('pwd.showResetForm') }}" id="password-step-1" method="post">
                    <div class="form" v-cloak>
                        <br>
                        <table class="reg-form-tbl" style="width: auto">
                            <tr>
                                <td width="100" align="right">账号</td>
                                <td>
                                    <div class="psr">
                                        <input @focus="setPhone" @blur="changPhone" id="txtPhone" maxlength="30" v-model="form.phone" :value="form.phone" name="phone" type="text" placeholder="手机号 / 邮箱名" :class="{'form-input':true,'form-input-def':true,'error-bg':phoneStatus==2}" />
                                        <span class="ml10 psa red wp100 mt10" v-cloak v-show="phoneStatus==2">请输入账号~</span>
                                        <span class="ml10 psa red wp100 mt10" v-cloak v-show="phoneStatus==3">格式有误，请重新输入～</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">验证码</td>
                                <td>
                                    <div class="psr">
                                        <div class="input-wrapper">
                                            <input @keydown.13="send" @focus="setCode" @blur="changCode"  v-model="form.code" :value="form.code" maxlength="4" placeholder="请输入左侧验证码" name="code" type="text" :class="{'form-input':true,'form-input-def':true,'error-bg':codeStatus == 2}" />
                                            <img id="codeimg" height="38" onclick="this.src='{{route('makecode')}}?r='+Math.random()" src="{{route('makecode')}}" />
                                            <img class="refresh-code" @click="refreshCode" src="http://www.hwache.cn/themes/images/common/code-refresh.png" />
                                        </div>
                                        <div class="psa code-error-psi" :class="{'code-error-psi-2':codeStatus==3}">
                                            <span v-cloak class="red" v-show="codeStatus==2">请输入验证码~</span>
                                            <span v-cloak class="red" v-show="codeStatus==3">输入有误，请重新输入～</span>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"></td>
                                <td align="left">
                                     <button :disabled="isLoading" @click="send" type="button" class="btn btn-s-md btn-danger fl btn-pwd">下一步</button>
                                </td>
                            </tr>

                        </table>

                    </div>
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
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
        seajs.use(["vendor/vue","module/pwd/password-step-1" ],function(a,b,c){

        });
    </script>
@endsection
