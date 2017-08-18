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
                    <li>1.填写账号</li>
                    <li class="cur">2.验证身份</li>
                    <li>3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul>
                <div class="form">
                    <br><br><br>
                    <div class="tip-large tip-large-info tip-big-large" style="width:70%;">
                            <p>因今日申请邮箱验证但验证失败的次数过多，邮箱{{ changeEmail($data['name']) }}</p>
                            <p>
                                找回密码功能已被保护，请半小时后再试～
                            </p>
                            <br />
                            <p>
                                您也可尝试使用注册手机{{ chanageStr($data['phone'],4,strlen($data['phone']))}}找回密码哦～
                            </p>
                    </div>
                    <br><br><br>
                    <p class="text-center">

                        <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block">确定</a>

                        <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger btn-auto inline-block btn-white btn-email fs16">使用手机找回密码</a>

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

@section('js','')