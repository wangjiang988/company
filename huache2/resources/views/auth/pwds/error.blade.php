@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.not_login') @endsection
@section('content')
    <div class="box" ms-include-src="regheader"></div>

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                <!-- <ul>
                    <li class="cur">1.填写账号</li>
                    <li>2.验证身份</li>
                    <li>3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul> -->
                <div class="form">
                    <br><br><br>
                    <div class="tip-large tip-large-info">
                        <p class="juhuang">对不起，本次验证有效时间已过，</p>
                        <p class="juhuang">请重新申请！</p>
                    </div>
                    <br><br><br>
                    <p class="text-center">
                        <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block btn-white">重新找回密码</a>
                    </p>
                    <p class="text-gray tac fs12">
                        <span v-cloak class="juhuang">@{{countDownNum}}</span>秒后自动跳转找回密码页面
                    </p>
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
        seajs.use(["vendor/vue","module/pwd/password-step-6", "module/common/common","bt"],function(a,b,c){

        })
    </script>
@endsection
