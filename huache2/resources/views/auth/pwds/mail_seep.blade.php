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
                    <li class="cur">1.填写账号</li>
                    <li>2.验证身份</li>
                    <li>3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul>
                <form role="form" method="POST" action="{{ route('mail.mailForm') }}">
                    <div class="form">
                        <br><br><br>
                        <p class="text-center text-gray">确定{{ $data['name'] }}是您在华车账户绑定的邮箱吗？</p>
                        <br><br><br>
                        <input type="hidden" name="email" value="{{ $data['name'] }}" />
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ csrf_token() }}" />
                        <p class="text-center">
                            <button type="submit" class="btn btn-s-md btn-danger w120 inline-block">确 定 </button>
                            <a href="{{ route('pwd.showResetForm') }}" class="btn btn-danger btn-auto inline-block ml20 btn-white">返 回</a>
                        </p>
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
        seajs.use(["vendor/vue","module/common/common","bt"],function(a,b,c){

        })
    </script>
@endsection