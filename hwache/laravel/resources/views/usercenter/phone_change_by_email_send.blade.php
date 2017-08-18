@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
				   <h2 class="title">验证邮箱修改手机号码</h2>
                    <div class="content-wapper">
                        <div class="hd">
                           <ul>
                                <li class="cur"><span>1</span><label>邮箱验证</label></li>
                                <li><span>2</span><label>修改手机号码</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="ml200 mt60">
                            <dl class="valite-dl">
                                <dt>
                                    <span class="valite-ok-2 fl"></span> 
                                    <span class="fl ml10">已发送邮件至：<b>{{$memberInfo['member_email']}}</b>
                                    <br>
                                    验证邮件在24小时内有效，请尽快登录您的邮箱进行验证。
                                    </span>
                                </dt>
                                <div class="clear"></div>
                            </dl>
                        </div>
                        <p class="tac">
                            <a href="javascript:;" class="btn btn-s-md btn-danger   ml20 wauto">查看验证邮件</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 wauto">重新发送验证邮件</a>
                        </p>

                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection