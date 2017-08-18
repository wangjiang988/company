@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
<h2 class="title">修改密码</h2>  
                    <div class="content-wapper">
                       <div class="hd">
                           <ul>
                                <li class="cur"><span>1</span><label>验证身份</label></li>
                                <li><span>2</span><label>修改登录密码</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <form action="" method="post" name="passwd_form">
                        <div class="ml260 mt60">
                            <p class="p">已验证的手机：  {{$memberInfo['member_mobile']}}</p>
                            <p class="fs14 m-t-10">
                               <span class="pull-left">手机验证码：</span>
                               <input type="text" name="phonecode" placeholder="" class="form-control pay-control pull-left code">
                               <a href="javascript:;" ms-on-click="SendCode({{$memberInfo['member_mobile']}})" data-s="重新获取" data-send="重新获取($1)" class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s bt sure btn-code">获取验证码</a>
                               <div class="clear"></div>
                             </p>
                             <p id="showerror" class="inputerror hide" style="margin-left: 83px;">*输入的验证码不正确</p>

                        </div>
                        <p class="tac"><a href="javascript:;" ms-on-click="changePwByPhoneCheck" class="btn btn-s-md btn-danger fs16 btn-s w150">下一步</a></p>
                        <p class="tac"><a href="/user/memberSafe/passwd_email_check" class="juhuang">使用已验证邮箱修改密码>></a></p>
                        <div class="m-t-10"></div>
                        <div class="m-t-10"></div>
                        <div class="m-t-10"></div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="act_form" value='sub'>
                        </form>
                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection
                    