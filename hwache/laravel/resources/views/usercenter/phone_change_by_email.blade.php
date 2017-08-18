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
                        <div class="ml185 mt60">
                            <form action="" method="post" name="emailform">

                                <p class="p">
                                   已验证的邮箱：  {{$memberInfo['member_email']}}
                                </p>
                                <p class="fs14 m-t-10">
                                   <span class="pull-left">&nbsp;&nbsp;&nbsp;验证码：</span>
                                   <input type="text" name="code" placeholder="" class="form-control pay-control pull-left code">
                                   
                                   <img src="/user/makecode?t=" ms-on-click="getCodeImg(this)"  class="valite-code" alt="">
                                   <div class="clear"></div>
                                </p>
                                <p id="showerror" class="inputerror hide" style="margin-left: 55px;">*输入的验证码不正确</p>
                            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        		<input type="hidden" name="act_form" value='sub'>
                            </form>
                        </div>
                        <div class="mt60"></div>
                        <p class="tac"><a href="javascript:;" ms-on-click="SendEmailCheck" data-s="重新获取" data-send="重新获取($1)"  class="btn btn-s-md btn-danger fs16 btn-s w150">发送邮件</a></p>
                        <p class="tac"><a href="./phone_change" class="juhuang">使用已验证手机修改验证手机号>></a></p>
                        <div class="mt60"></div>

                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection