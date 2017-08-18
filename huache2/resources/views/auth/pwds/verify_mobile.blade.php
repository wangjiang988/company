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
                    <li class="cur">2.验证身份</li>
                    <li>3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul>
                <form action="{{ route('pwd.verifyCode') }}" id="password-step-2">
                    {!! Form::hidden('template_code','78575070') !!}
                    <div class="form">
                        <br>
                        <table v-show="errorCount < 10" v-cloak class="reg-form-tbl w620">
                            <tr>
                                <td width="100" align="right" valign="top">
                                    <span class="tag-success"></span>
                                    <input type='hidden' name='_token' value="{{csrf_token()}}">
                                </td>
                                <td class="text-gray">
                                    <p>已向您的手机{{ changeMobile($data['name']) }}发送短信验证码！</p>
                                    <div class="countdown inline-block">
                                        <div class="time-wrapper" v-cloak>
                                            <span class="text">本次有效验证时间<span class="total-time">20</span>分钟 仅剩</span>
                                            <span>@{{time.minites[0]}}</span>
                                            <span>@{{time.minites[1]}}</span>
                                            <span class="symbol"><span>:</span></span>
                                            <span>@{{time.seconds[0]}}</span>
                                            <span>@{{time.seconds[1]}}</span>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <p>收到的验证码
                                        <input type="hidden" name="phone" :value="form.phone" />
                                        <input @keydown.13="endSub" @focus="setCode" @blur="changCode" maxlength="6" v-model="form.code" :value="form.code" name="code" type="text" placeholder="6位数字" :class="{'form-input':true,'form-input-def':true,'form-input-code':true,'ml20':true,'error-bg':codeStatus==2}" />
                                        <button :disabled="isLoading" @click="send" type="button" class="btn btn-s-md btn-danger btn-auto inline-block ml20">验证，下一步</button>
                                    </p>
                                    <span v-cloak class="red ml120" v-show="codeStatus==2">请输入验证码~</span>
                                    <span v-cloak class="red ml120" v-show="codeStatus==3">对不起，输入有误，您还有@{{totalCount-errorCount}}次验证机会～</span>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-gray">
                                    <div class="inline-block ml60">
                                        <span>没收到？>></span>
                                        <button @click="reSend" type="button" class="btn btn-s-md btn-danger btn-auto inline-block btn-normal-white">申请重发</button>
                                    </div>
                                    <div class="inline-block ml95">
                                        <span>有问题？>></span>
                                        <a href="#" class="blue">查看帮助</a>
                                    </div>
                                </td>
                            </tr>

                        </table>

                        @if(empty($data['email']))
                        <table v-show="errorCount == 10"  v-cloak class="reg-form-tbl w620">
                            <tr>
                                <td class="text-gray">
                                    <div class="tip-large tip-large-info" style="width: 100%">
                                       <p>因输错次数过多，手机号{{ changeMobile($data['name']) }}找回密码功能已被保护，</p>
                                       <p>请半小时后再试～</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tac">
                                    <div>
                                         <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block fs16">确 定</a>
                                     </div>
                                </td>
                            </tr>
                        </table>
                        @else
                        <table class="reg-form-tbl w620" v-show="errorCount == 10"  v-cloak>
                            <tr>
                                <td class="text-gray">
                                  <div class="tip-large tip-large-info" style="width: 100%">
                                     <p>因输错次数过多，手机号{{ changeMobile($data['name']) }}找回密码功能已被保护，</p>
                                     <p>请半小时后再试～</p>
                                      @if($data['email'])
                                     <p>您也可以改用绑定邮箱{{ $data['email'] }}找回密码哦～</p>
                                      @endif
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tac">
                                    <div>
                                         <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block fs16">确 定</a>
                                        @if($data['email'])
                                         <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger btn-auto inline-block btn-white btn-email fs16">使用邮箱找回密码</a>
                                        @endif
                                     </div>
                                </td>
                            </tr>
                        </table>
                        @endif
                        <input type="hidden" v-model="form.phone" :value="form.phone" />
                        <div id="reSendWin" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  <div class="m-t-10"></div>
                                  <p class="fs14 pd tac">
                                     <br>
                                     <span class="tip-text">申请向手机<span class="juhuang nofloat">@{{form.phone}}</span>重发验证码，确定吗？</span>
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
                                     <span class="tip-text fs14">已向您的手机@{{form.phone}}重发原验证码，请尽快输入～</span>
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
                                     <span class="tip-text">已多次发送该验证码，对不起，不能重复申请了～</span>
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
        seajs.use(["vendor/vue","module/pwd/password-step-2", "bt"],function(a,b,c){
            b.init("{{ $data['name'] }}","{{ route('member.sendSms') }}","{{route('pwd.pwdOver')}}","{{route('pwd.mobileAnswer')}}");
            b.initCountDown('{{ $data['start_date'] }}','{{ $data['end_date'] }}','{{ $data['thisData'] }}','{{ route('time_out.freeze')}}');
            b.initErrorCount('{{ $data['count'] }}');
        })
    </script>
@endsection
