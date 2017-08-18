@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
  <div class="container m-t-86 pos-rlt" ms-controller="reg">
    <div class="wapper">
      <div class="hd">
        <ul>
          <li><span>1</span><label>设置用户名</label></li>
          <li><span>2</span><label>设置密码</label></li>
          <li class="cur"><span>3</span><label>注册成功</label></li>
          <div class="clear"></div>
        </ul>
        <div class="form">
          <h3>恭喜你，注册成功！</h3>
          <p>
            <a href="{{ route('user.ucenter') }}">进入会员中心</a>
            <a href="{{ route('user.member_info') }}">完善个人资料</a>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script type="text/javascript">
    seajs.use(["module/reg/reg-phone-pwd", "module/common/common", "bt"]);
  </script>
@endsection