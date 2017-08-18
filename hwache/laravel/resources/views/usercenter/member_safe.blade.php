@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
				<h2 class="title">安全设置</h2>
                    
                   
                    <div class="content-wapper">
                        <!--//一到两个验证显示为弱，三个验证显示为中，四个验证显示为强-->
                        <div class="pwd-strong">
                            <label>安全程度：</label>
                            <span class="p-s-less pwdcur">弱</span>
                            <span class="p-s-normal pwdcur">中</span>
                            <span class="p-s-max">强</span>
                        </div>
                        <dl class="valite-dl">
                            <dt>
                                <span class="valite-ok"></span><label>登录密码</label>
                                互联网账号存在被盗风险，建议您定期更改密码以保护账户安全。
                            </dt>
                            <dd>
                                <a href="/user/memberSafe/passwd_phone_check" class="juhuang">修改</a>
                            </dd>
                            @if($memberInfo['email_verify']!=2)
                            <dt>
                                <span class="valite-none fl"></span>
                                <label class="fl">邮箱验证</label>
                                <span class="fl">您还未进行邮箱验证，在手机丢失或者停用，可采用邮箱验证方式
                                <br>
                                保证操作的安全性。
                                </span>

                            </dt>
                            <dd class="mulite">
                                <a href="/user/memberSafe/email_check" class="juhuang">验证</a>
                            </dd>
                            @else
                            <dt>
                                <span class="valite-ok"></span><label>邮箱验证</label>
                                您验证的邮箱：{{$memberInfo['member_email']}}
                            </dt>
                            <dd></dd>
                            @endif
                            <dt>
                                <span class="valite-ok fl"></span>
                                <label class="fl">手机验证</label>
                                <span class="fl">您验证的手机：{{$memberInfo['member_mobile']}}   若已丢失或者停用，建议立即更换。
                                <br>
                                手机号用于修改/找回密码。
                                </span>

                            </dt>
                            <dd class="mulite">
                                <a href="/user/memberSafe/phone_change" class="juhuang">更换</a>
                            </dd>
                             @if($memberInfo['name_verify']==2)
                            <dt>
                                <span class="valite-ok"></span><label>实名认证</label>
                                您已通过实名认证，进一步保障了您的支付/交易的安全。
                            </dt>
                            <dd>
                                <a href="/user/memberSafe/check_real_member" class="juhuang">查看</a>
                            </dd>
                            @else
                            <dt>
                                <span class="valite-none"></span><label>实名认证</label>
                                为了保障您支付/交易环境的安全性，在购车之前需要进行实名认证。
                            </dt>
                            <dd></dd>
                            @endif
                            <div class="clear"></div>
                        </dl>

                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection