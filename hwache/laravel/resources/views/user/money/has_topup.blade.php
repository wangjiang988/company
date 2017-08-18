@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/reg.css')}}" rel="stylesheet" />
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
  <div class="container m-t-86 pos-rlt">
    <div class="wapper">
      <div class="hd">
        <h4 class="login-title">充值</h4>
        <div class="form login-form-div">
          <form action="{{ route('user.money.topup') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="serial_id" value="{{ $serialId }}">
            <div class="input-group">
              <span class="input-group-addon">充值方式:</span>
              <label>{{ $payType['title'] }}</label>
            </div>
            <div class="input-group">
              <span class="input-group-addon">充值金额:</span>
              {{ $money }}
            </div>
            <div class="pos-rlt">
              <input type="submit" class="btn btn-s-md btn-danger" value="下一步" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
