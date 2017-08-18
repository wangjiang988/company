@extends('_layout.base')

@section('css')
    <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet"/>
@endsection

@section('nav')@include('_layout.nav')@endsection

@section('content')
<div class="box" ms-include-src="itemheader"></div>

<div class="container m-t-86 pos-rlt">
    <div class="step pos-rlt">
        <ul>
            <li class="first">诚意预约<i></i></li>
            <li class="step-cur">付担保金<i></i></li>
            <li>预约交车<i></i></li>
            <li>付款提车<i></i></li>
            <li>退担保金<i></i></li>
            <li>完成评价<i></i></li>
            <div class="clear"></div>
        </ul>
        <div class="min-step">
            <div class="m-content" style=" left: 187px;">
                <small class="juhuang">正在支付</small>
                <i></i>
                <small>查收确认</small>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<div class="container pos-rlt content" ms-controller="pay">
    <div class="wapper has-min-step">
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <h2 class="tac pay-s ">恭喜您支付成功！</h2>
        <p class="fs14 tac mt20">
            <span>本次支付金额：￥{{ $payInfo->money }}</span>
            <span class="ml20">交易时间：{{ $payInfo->pay_time }}</span>
        </p>
        <p class="fs14 tac">@if($dopositMoney['payStatus'])您已经支付完成@else买车担保金余款待付金额：￥{{ $dopositMoney['surplusMoney'] }}（应付金额-已付金额）@endif</p>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <p class="tac mt60">
            @if(!$dopositMoney['payStatus'])<a href="{{ route('user.money.doposit', [$orderNum]) }}" class="btn btn-s-md btn-danger ml20 w150 fs16">继续支付</a>@endif
            <a href="{{ route('orderoverview', [$orderNum]) }}" class="btn btn-s-md btn-danger ml50 w150 sure fs16">查看订单</a>
        </p>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
    </div>

</div>

@endsection
