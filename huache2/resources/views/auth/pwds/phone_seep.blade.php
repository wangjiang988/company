@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.not_login') @endsection
@section('content')
    <div class="box" ms-include-src="regheader"></div>

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                @include('auth.layout.pwd-nav')
                <div class="form">
                    <br><br><br>

                    @if($data['error_code'] == 1)

                    @if(empty($data['email']))
                        <table   v-cloak class="reg-form-tbl w620">
                            <tr>
                                <td class="text-gray">
                                    <div class="tip-large tip-large-info" style="width: 100%">
                                    @if($data['error_status'] ==2)
                                    <p>对不起，手机号{{ changeMobile($data['name']) }}找回密码功能可能异常，</p>
                                    <p>请联系客服解决～</p>
                                    @else
                                    <p>因输错次数过多，手机号{{ changeMobile($data['name']) }}找回密码功能已被保护，</p>
                                    <p>请半小时后再试～</p>
                                    @endif
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
                        <table class="reg-form-tbl w620"  v-cloak>
                            <tr>
                                <td class="text-gray">
                                  <div class="tip-large tip-large-info" style="width: 100%;background-position: 20px 3px;">
                                    @if($data['error_status'] ==2)
                                    <p>对不起，手机号{{ changeMobile($data['name']) }}找回密码功能可能异常，</p>
                                    <p>请联系客服解决～</p>
                                    @else
                                    <p>因输错次数过多，手机号{{ changeMobile($data['name']) }}找回密码功能已被保护，</p>
                                    <p>请半小时后再试～</p>
                                    @endif

                                   <p>您也可以改用绑定邮箱{{ $data['email'] }}找回密码哦～</p>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tac">
                                    <div>
                                         <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block fs16">确 定</a>
                                        <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger btn-auto inline-block btn-white btn-email fs16">使用邮箱找回密码</a>
                                     </div>
                                </td>
                            </tr>
                        </table>
                    @endif

                    <br><br><br>

                    @else
                   <!-- //账号未注册-->
                    <p class="text-center text-gray">您的手机号{{ changeMobile($data['name']) }}尚未注册，欢迎马上注册～</p>
                    <br><br><br>
                    <p class="text-center">
                        <a href="{{route('user.getReg')}}" class="btn btn-s-md btn-danger w120 inline-block">去注册 </a>
                        <a href="{{route('pwd.showResetForm')}}" class="btn btn-danger btn-auto inline-block ml20 btn-white">其他账号找回密码</a>
                    </p>
                    @endif
                </div>

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
        seajs.use(["vendor/vue","module/pwd/password-step-1"],function(a,b,c){

        });
    </script>
@endsection
