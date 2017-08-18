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
                <div class="form">
                    <br><br><br>
                    <div class="tip-large tip-large-info tip-big-large">
                        @if($data['error_code'] ==1)
                            <p>对不起，邮箱{{ changeEmail($data['name']) }}找回密码功能{{ ($data['error_status'] ==2) ? '可能异常' : '已被保护' }}，</p>
                            <p>
                                {{($data['error_status'] ==2) ? '请联系客服解决～' : '请半小时后再试～' }}
                            </p>
                        @else
                            <p>对不起，{{ $data['name'] }}非绑定账号邮箱，</p>
                            <p>请核对后重新提交～</p>
                        @endif
                    </div>
                    <br><br><br>
                    <p class="text-center">
                    {{-- todo 缺失客户联系方式页面--}}
                    @if($data['error_code'] ==1)
                        <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block">确定</a>
                    @else
                        <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger w120 inline-block">返回重填</a>
                    @endif
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

@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue", "module/common/common","bt"],function(a,b,c){

        })
    </script>
@endsection
