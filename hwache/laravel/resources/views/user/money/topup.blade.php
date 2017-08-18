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
            <div class="input-group">
              <span class="input-group-addon">充值方式:</span>
              @foreach($payType as $v)
              <label><input type="radio" name="pay" value="{{ $v['name'] }}"> {{ $v['title'] }}</label>
              @endforeach
            </div>
            <div class="input-group">
              <span class="input-group-addon">充值金额:</span>
              <input data-toggle="tooltip" data-placement="bottom" name="price" type="text"  class="form-control" placeholder="充值金额" title="充值金额" aria-describedby="basic-addon1">
            </div>
            <div class="pos-rlt">
              <input type="submit" class="btn btn-s-md btn-danger" value="下一步" />
            </div>
            <div class="pos-rlt">
              当前最大可充值金额{{ $userMoney }}
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
