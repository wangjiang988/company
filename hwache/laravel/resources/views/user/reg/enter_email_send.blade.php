@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
  <style>
    .ms-controller,
    .ms-important,
    [ms-controller],
    [ms-important] {
      visibility: hidden;
    }
  </style>
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
  <div class="container m-t-86 pos-rlt" ms-controller="reg">
    <div class="wapper">
      <div class="hd">

        <div class="form">
          <h3 class="email-sucess">邮件发送成功，请进入邮箱查收邮件！</h3>
          <p>
            <a href="javascript:;" ms-click="resend('{{ route('user.reg.send_email') }}', '{{ $email }}')">重新发送邮件</a>
          </p>
          <p ms-visible="resendInfo">已经重新发送邮件，请查收！</p>
          <p>
            <label><span>*</span>如果没有成功收到邮件，点击重新发送</label>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script type="text/javascript">
    seajs.use(["module/reg/reg-email-send-sucess", "module/common/common", "bt"]);
  </script>
@endsection