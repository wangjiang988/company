@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
  <style>
    .form p a{width:190px;}
  </style>
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
  <div class="container m-t-86 pos-rlt">
    <div class="wapper">
      <div class="hd">
        <div class="form">
          <p>
            <a href="{{ route('user.reg.fill_email') }}">验证失败，请重新注册</a>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
