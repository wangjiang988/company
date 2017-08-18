@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">修改邮箱</span><div class="mt5"></div></h4>
        <div class="content-wapper ">
            <div class="hd">
                <ul>
                    <li class="cur"><span>1</span><label class="juhuang">验证身份</label></li>
                    <li><span>2</span><label>验证邮箱</label></li>
                    <li><span>3</span><label>修改完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            <div class=" mt60">
                <h1 class="success-title">
                    <table class="mauto">
                        <tr>
                            <td class="text-right"><span class="icon-large icon-success-large"></span></td>
                            <td class="text-left"><span class="inline-block c72 fs14 ml10">已发送验证邮件至：{{ changeEmail($user->email) }} </span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="fs14 c72">
                                <p class="mt10">邮件中的验证码有效时间24小时，请尽快登录邮箱获取。</p>
                            </td>
                        </tr>
                    </table>
                    <table class="mauto">
                        <tr>
                            <td valign="top" class="c72 fs14">
                                <span class="mt10 inline-block">邮箱验证码：</span>
                            </td>
                            <td valign="top">
                                <input @focus="initCode" v-model="code" maxlength="6" placeholder="请输入" type="text" name="code" :class="{'form-control':true,'custom-control':true,'error-bg':codeError || isEmpty}">
                                {{ csrf_field() }}
                            </td>
                            <td valign="top">
                                <a href="javascript:;" @click="sendMail" class="btn btn-s-md btn-danger fs16 sure ml20" style="margin-top: 0;">重新发送验证邮件</a>
                                <div class="" style="height:45px;">
                                <p class="gray hide fs14 nomargin " style="padding:5px;" :class="{show:sendSuccess}">邮件已成功发送</p>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2">
                                <p v-cloak v-show="codeError" class="inputerror normal-warn red text-left fs14"><span class="mt5 inline-block">验证码有误，请重新输入~</span></p>
                            </td>
                        </tr>
                    </table>
                </h1>
                <div class="m-t-10"></div><div class="m-t-10"></div>
                <p class="tac">
                    <a href="javascript:;" @click="next" class="btn btn-s-md btn-danger fs16 ">下一步</a>
                </p>
                <p class="tac p-gray">若无法收到邮件，请<a href="#" class="blue tdu">查看帮助</a></p>
            </div>


            <div id="sendErrorWin" class="popupbox">
                <div class="popup-title">温馨提示</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <div class="ml20">
                            <p class="gray">客官，验证邮件已重新发送至您的邮箱，如果邮箱未接收到验证邮件，</p>
                            <p class="gray">请您10分钟后再次点击重新发送验证邮件，给您带来的不便敬请谅解~</p>
                            <br>
                        </div>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="sure" class="btn btn-s-md btn-danger fs14  w100 inline-block ">确认</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>


            <div class="m-t-10" v-for="i in 15"></div>

        </div>

    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
    var email_send_time = '{{$user->isDate }}';
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-email-step2"],function(v,u,c){
            u.initEmail("{{ $user->email }}","{{ route('account.sendMail') }}","{{ route('upemail.seep4') }}","{{ route('account.checkEmail') }}");
        })
    </script>
@endsection