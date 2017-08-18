@extends('_layout.base')
@section('css')
  <link href="{{ asset('themes/reg.css') }}" rel="stylesheet" />
@endsection
@section('nav')@include('_layout.nav')@endsection

@section('content')<div class="container m-t-86 pos-rlt" ms-controller="reg">
    <div class="wapper">
      <div class="hd">
        <ul>
          <li><span>1</span><label>设置用户名</label></li>
          <li class="cur"><span>2</span><label>设置密码</label></li>
          <li><span>3</span><label>注册成功</label></li>
          <div class="clear"></div>
        </ul>
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <form action="{{ route('user.reg.save_info') }}" name="pwdform" method="post">
          <div class="form form-pwd pos-rlt">
            <div class="pwd-strong">
              <label>密码强度：</label>
              <span class="p-s-max">强</span>
              <span class="p-s-normal">中</span>
              <span class="p-s-less">弱</span>
            </div>
            <div class="input-group">
              <span class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;密码:</span>
              <input ms-on-keydown="pwdStrong" data-toggle="tooltip" data-placement="bottom" name="pwd" type="password" required class="form-control" placeholder="密码由6-16个字符组成，区分大小写" title="密码由6-16个字符组成，区分大小写" aria-describedby="basic-addon1">
              <span class="input-group-addon hide error">请正确输入密码</span>
            </div>
            <br>
            <div class="input-group pos-rlt">
              <span class="input-group-addon">确认密码:</span>
              <input data-toggle="tooltip" data-placement="top" name="pwd2" type="password" required class="form-control" placeholder="确认密码" title="确认密码" aria-describedby="basic-addon1">
              <span class="input-group-addon hide error">两次输入密码不一致</span>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button ms-on-click="SetPwd" type="button" class="btn btn-s-md btn-danger">下一步</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script type="text/javascript">
    seajs.use(["module/reg/reg-phone-pwd", "module/common/common", "bt"]);
  </script>
@endsection