@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
<div class="container m-t-86 pos-rlt content ">
    <div class="wapper has-min-step content-wapper">
        <p><b class="blue">充值</b></p>
        <div class="box box-border top-border  fs14 p20">
            <p>可用余额：￥{{ number_format($account->avaliable_deposit,2) }}</p>
            <a href="{{ route('pay.online')}}"><span class="tab ml50 tab-empty">线上支付</span></a>
            <a href="{{ route('pay.recharge')}}"><span class="tab ml50">银行转账</span></a>
            <div class="box-border pay-box">

                <h3 class="ul-prev-big">您可以使用网上银行、手机银行、银行柜面等途径直接转账至华车，公司收款账户信息如下：</h3>
                <br>
                <p class="fs14 ml50">开户行：( 江苏省苏州市 ) 招商银行苏州分行干将路支行</p>
                <p class="fs14 ml50">帐 号：5129 0627 3310 301</p>
                <p class="fs14 ml50">
                    <span>户 名：苏州华车网络科技有限公司</span>
                    <a href="javascript:;" @click="sendCode" class="ml30 juhuang tdu">发送到手机</a>
                </p>
                <br>
                <h3 class="ul-prev-big">温馨提示：</h3>
                <div class="ml30 ul-prev-small">
                    <span class="red">请务必在汇款附言中注明：账户{{ Auth::user()->phone }}充值</div>
                <div class="ml30 ul-prev-small">
                    入账金额和入账时间均以实际确认到账为准，若汇款信息不全或不符造成退回，请恕相关责任由您承担。
                </div>

                <div class="m-t-10"></div>



                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="tac">
                    <a href="javascript:;" title="OK.ZF.13支付帮助.html" class="fs16 juhuang tdu">支付帮助</a>
                    <a href="{{ route('pay.recharge',['receipt','order'=>$account->order_id]) }}" title="K.2.34充值银行转账提交凭证.html" class="btn btn-s-md btn-danger ml50 fs16 btn-s">已充值，上传凭证</a>
                    <a href="{{ route('my.myBalance') }}" class="fs16 juhuang tdu ml50">返回</a>
                </div>

                <div id="tipWin" class="popupbox">
                    <div class="popup-title"><span>发送短信</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                            {!! Form::hidden('phone',Auth::user()->phone) !!}
                                <span class="tip-tag bp0"></span>
                                <span class="tip-text mt10">确定向手机{{ chanageStr(Auth::user()->phone,4,-4,'******') }}发送收款账户短信吗？ </span>
                            <div class="clear"></div>
                            <br>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" @click="doSendCode" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure ml50">取消</a>
                            <div class="clear"></div>

                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>

                <div id="successWin" class="popupbox">
                    <div class="popup-title"><span>发送短信</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                                <span class="tip-tag bp0"></span>
                                <span class="tip-text mt10">短信发送成功！<br>接收短信一般不超过2分钟，<br>长时间未收到可再申请重发～</span>
                            </p>
                        </div>
                        <div class="popup-control">
                            <p><span class="red">@{{countDownNum}}</span>秒后自动关闭</p>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure">关闭</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <div id="errorWin" class="popupbox">
                    <div class="popup-title"><span>提示</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                                <span class="tip-tag bp0"></span>
                                <span class="tip-text mt10">客官，您点击的太频繁啦，可以稍后重新发送哦！</span>
                            <div class="clear"></div>
                            <br>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" class="sure btn btn-s-md btn-danger fs14 do w100">确定</a>
                            <div class="clear"></div>

                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>

                <div id="endWin" class="popupbox">
                    <div class="popup-title"><span>提示</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                                <span class="tip-tag bp0"></span>
                                <span class="tip-text mt10">客官，今天的短信发送次数已用光，<br>请明天再来发送吧！</span>
                            <div class="clear"></div>
                            <br>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" class="sure btn btn-s-md btn-danger fs14 do w100">确定</a>
                            <div class="clear"></div>
                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-bank-transfer", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //页面加载的时候就设置该手机号发送了几次验证码
            //不发送验证码的情况
            //1.发送次数 >=3
            //2.发送间隔在2分钟之内
            u.initSendCount(0);
            u.initSetUrl("{{route('member.sendSms')}}");
        })
    </script>
@endsection