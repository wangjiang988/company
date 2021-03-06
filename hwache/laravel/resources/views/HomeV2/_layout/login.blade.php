<div class="zm" id="smiple-login">
    <form action="/user/login" id="smiple-login-form">
        <input type='hidden' name='_token' value="{{csrf_token()}}">
        <div id="login" class="">
            <div class="l-wapper">
                <div class="l-head">
                    <div class="l-h-bg">用户登录<a href="javascript:;" ms-click="closeLogin" class="login-close"></a></div>
                </div>
                <br>
                <table width="100%" class="tbl-simple-login">
                    <tr>
                        <td align="right">账号</td>
                        <td>
                            <input @keydown.13="simpleLoign" maxlength="20" name="name" v-model="form.phone" :value="form.phone" type="text" :class="{'form-control':true, 'smiple-login-input':true, ml10:true,'error-bg':loginNameStatus == 2}" placeholder="手机号 / 邮箱名" title="手机号 / 邮箱名">
                            <span v-cloak v-show="loginNameStatus == 3" class="red fs14">格式不对</span>
                        </td>
                    </tr>
                    <tr v-cloak v-show="isOpenEye">
                        <td align="right">密码</td>
                        <td>
                            <div class="input-wrapper">
                                <input @keydown.13="simpleLoign" maxlength="20" type="text" v-model="form.pwd" :value="form.pwd" :class="{'form-control':true, 'smiple-login-input':true, ml10:true,'error-bg':pwdStatus == 2}" placeholder="如有字母请区分大小写" title="如有字母请区分大小写">
                                <a target="_blank" class="fs14" href="{{ route('user.reg.reset_pwd') }}">忘记密码?</a>
                                <div class="icon-wrapper">
                                    <span v-cloak v-show="isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-on pointer"></span>
                                    <span v-cloak v-show="!isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-off pointer"></span>
                                </div> 
                            </div>
                        </td>
                    </tr>        
                    <tr v-cloak v-show="!isOpenEye">
                        <td align="right">密码</td>
                        <td>
                            <div class="input-wrapper">
                                <input @keydown.13="simpleLoign" v-model="form.pwd" :value="form.pwd" maxlength="20" name="pwd" type="password" :class="{'form-control':true, 'smiple-login-input':true, ml10:true,'error-bg':pwdStatus == 2}"/>
                                <span v-show="!isPwdInput"class="pwd-see" @click="pwdSee">如有字母请区分大小写</span>
                                <a target="_blank" class="fs14" href="{{ route('user.reg.reset_pwd') }}">忘记密码?</a>
                                <div class="icon-wrapper">
                                    <span v-cloak v-show="isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-on pointer"></span>
                                    <span v-cloak v-show="!isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-off pointer"></span>
                                </div> 
                            </div> 
                        </td>
                    </tr>
                    <tr v-cloak v-show="isCodeValite">
                        <td align="right"></td>
                        <td>
                            <div class="input-wrapper">
                                <img id="codeimg" height="38" onclick="this.src='http://user.hwache.cn/index.php?act=seccode&amp;op=makecode&amp;nchash=521e168c&amp;r='+Math.random()"  src="http://user.hwache.cn/index.php?act=seccode&op=makecode&nchash=521e168c" />
                                <img @click="refreshCode" src="./themes/images/common/code-refresh.png" />
                                <input @keydown.13="simpleLoign" v-model="form.code" :value="form.code" maxlength="4" placeholder="请输入左侧验证码" name="code" type="text" :class="{'form-control':true, 'smiple-login-input':true, 'smiple-login-input-code':true,'error-bg':codeStatus == 2}" />
                            </div>
                        </td>
                    </tr>

                </table>
                <div class="l-c">  
                    <div class="text-center mt20 notice-count notice-count-long" v-cloak v-show="loginSuccess">
                        <div v-cloak class="form-error block text-center" >
                            登陆成功
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-long" v-cloak v-show="freezeCount == 1 && loginErrorCount == 10">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long red ">
                                对不起，因输错次数过多，该账号登录功能<br>
                                已被保护，请明日再试！如有紧急问题可联<br>
                                可联系华车客服，谢谢～
                            </span> 
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-long" v-cloak v-show="isFreeze">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long red ">
                                对不起，因输错次数过多，该账号登录功能<br>
                                已被保护，请20分钟后再试！如有紧急问题<br>
                                可联系华车客服，谢谢～
                            </span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-long" v-cloak v-show="isLoginTips && loginStatus == 2 ">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long red ">
                                账号或密码又错了，还有@{{10-loginErrorCount}}次机会啦，<br>
                                请慎重输入哦～
                            </span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-long" v-cloak v-show="loginStatus == 3 && !isLoginTips && !isFreeze && !(freezeCount == 1 && loginErrorCount == 10)">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long red ">
                                账号或密码错误，请重新尝试！
                            </span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-long" v-cloak v-show="loginStatus == 2 && !isLoginTips && !isFreeze && !(freezeCount == 1 && loginErrorCount == 10)">
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long red ">
                                账号或密码错误，请重新尝试！
                            </span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="text-center mt20 notice-count notice-count-long hide" >
                        <div v-cloak class="form-error block" >
                            <i class="reg-icon reg-icon-warn fl"></i>
                            <span class="fl mt5 inline-block notice-info-long red ">
                                对不起，因输错次数过多，该账号登录功能已被保护，请明日再试！如有紧急问题可联系华车客服，谢谢～
                            </span>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="pos-rlt">
                        <button @click="simpleLoign" :disabled="isLoading || loginErrorCount == 10" type="button" class="btn btn-s-md btn-danger btn-simple-login">登 录</button>
                        <div :class="{'smip-login-loading':true,show:isLoading}"></div>
                    </div>
                    <p class="reg-help">
                        <span class="psa gray"><input v-model="rememberMe" value="1" type="checkbox"/><span class="ml5 gray">记住登录帐号</span></span>
                        <a href="{{ route('user.reg.fill_mobile') }}" class="simp-reg" target="_blank">快捷注册</a>
                    </p>
                     
                </div>
            </div>
        </div>
    </form>
</div>