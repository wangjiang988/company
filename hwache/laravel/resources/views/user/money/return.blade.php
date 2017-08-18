@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
  <div class="container m-t-86 pos-rlt">
    <div class="wapper">
      <div class="hd">
        <h4 class="login-title">完成支付</h4>
      </div>
    </div>
  </div>
@endsection
