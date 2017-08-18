@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.not_login') @endsection
@section('content')
    <div class="box" ms-include-src="regheader"></div>

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                <ul>
                    <li>1.填写账号</li>
                    <li class="cur">2.验证身份</li>
                    <li>3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul>
                <form action="{{ route('mail.mailForm') }}" id="password-step-2">
                    <div class="form">
                        <br>
                        <table v-show="errorCount < 10" v-cloak class="reg-form-tbl w620">
                            <tr>
                                <td width="100" align="right" valign="top">
                                    <span class="tag-success"></span>
                                    {{ csrf_field() }}
                                </td>
                                <td class="text-gray">
                                    <p>验证邮件发送成功！请进入您的邮箱{{ changeEmail($data['email']) }}查收邮件！</p>
                                    <div class="countdown inline-block">
                                        <div class="time-wrapper" v-cloak>
                                            <span class="text">本次有效验证时间<span class="total-time red">20</span>分钟 仅剩</span>
                                            <span>@{{time.minites[0]}}</span>
                                            <span>@{{time.minites[1]}}</span>
                                            <span class="symbol"><span>:</span></span>
                                            <span>@{{time.seconds[0]}}</span>
                                            <span>@{{time.seconds[1]}}</span>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <p>
                                        <span>没收到？>></span>
                                        <button @click="reSend" type="button" class="btn btn-s-md btn-danger btn-auto inline-block btn-normal-orange">重新发送邮件</button>
                                    </p>
                                    <p>
                                        <span>有问题？>></span>
                                        <a href="#" class="blue">查看帮助</a>
                                    </p>

                                </td>
                            </tr>


                        </table>

                        <table v-show="errorCount == 10"  v-cloak class="reg-form-tbl w620">
                            <tr>
                                <td width="100" align="right" valign="middle">
                                    <span class="tag-info"></span>
                                </td>
                                <td class="text-gray">
                                     <p>因输错次数过多，手机号{{ changeMobile($data['phone']) }}找回密码功能已被保护，</p>
                                     <p>请半小时后再试～</p>
                                </td>
                            </tr>
                        </table>

                        <table class="reg-form-tbl w620 hide" v-show="errorCount == 10"  v-cloak>
                            <tr>
                                <td width="100" align="right" valign="middle">
                                    <span class="tag-info"></span>
                                </td>
                                <td class="text-gray">
                                     <p>因今日申请邮箱验证但验证失败的次数过多，邮箱{{ $data['email'] }}</p>
                                     <p>找回密码功能已被保护，请半小时后再试～</p>
                                     <p>您也可尝试使用注册手机******找回密码哦～</p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <div>
                                         <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block">确 定</a>
                                         <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger btn-auto inline-block btn-white btn-email">使用手机找回密码</a>
                                     </div>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" v-model="form.phone" :value="form.phone" />
                        <div id="reSendWin" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  <div class="m-t-10"></div>
                                  <p class="fs14 pd tac">
                                     <br>
                                     <span class="tip-text">向邮箱@{{form.phone}}重发验证邮件，确定吗？</span>
                                     <div class="clear"></div>
                                     <br>
                                  </p>
                                  <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <button type="button" :disabled="isSimpleLoading" @click="sendCode" class="btn btn-s-md btn-danger fs14 w100 do inline-block">确认</button>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block btn-white">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                        </div>

                        <div id="sendCodeWin" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  <div class="m-t-10"></div>
                                  <p class="fs14 pd tac">
                                     <br>
                                     <span class="tip-text fs14 text-left inline-block">已向您的邮箱{{ changeEmail($data['email']) }}重发验证邮件，请尽快进行验证～</span>
                                     <div class="clear"></div>
                                     <br>
                                  </p>
                                  <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 inline-block">确认</a>
                                  <div class="clear"></div>
                                  <p class="text-gray">
                                      <span>@{{countDownNum}}</span>秒后自动关闭
                                  </p>
                              </div>
                          </div>
                        </div>

                        <div id="sendErrorWin" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  <div class="m-t-10"></div>
                                  <p class="fs14 pd tac succeed error constraint">
                                     <span class="tip-tag" style="background-position: 0px 0px;"></span>
                                     <span class="tip-text">已多次发送验证邮件，对不起，不能重复申请了～</span>
                                     <div class="clear"></div>
                                     <br>
                                  </p>
                                  <div class="m-t-10"></div>
                             </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ">确认</a>
                                  <div class="clear"></div>
                                  <p class="text-gray">
                                      <span>@{{countDownNum}}</span>秒后自动关闭
                                  </p>
                              </div>
                          </div>
                        </div>
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
        seajs.use(["vendor/vue","module/pwd/password-step-3-2-1"],function(a,b,c){
            b.init("{{ $data['email'] }}","{{ route('mail.sendAjax') }}","{{ route('pwd.pwdOver') }}","{{ route('mail.mailSuccess') }}");
            b.initCountDown('{{ $data['start_date'] }}' , '{{ $data['end_date'] }}' , '{{ $data['thisData'] }}');
        })
    </script>
@endsection
