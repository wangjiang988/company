@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
		<h2 class="title">验证邮箱</h2>  
                    <div class="content-wapper">
                       
                        <div class="ml185 mt60">
                            <form action="" method="post" name="emailform">
                                <p class="p">
                                   <span class="pull-left">输入邮箱：</span>
                                   <input type="text" name="email" placeholder="" class="form-control pay-control pull-left email-input" value="{{$memberInfo['member_email']}}">
                                   <span class="inputerror ml10 hide">*邮箱格式不正确，请重新输入</span>
                                   <div class="clear"></div>
                                </p>
                                <p class="fs14 m-t-10">
                                   <span class="pull-left">&nbsp;&nbsp;&nbsp;验证码：</span>
                                   <input type="text" name="code" placeholder="" class="form-control pay-control pull-left code">
                                   <img src="/user/makecode?t=" ms-on-click="getCodeImg(this)"  class="valite-code" alt="">
                                   <div class="clear"></div>
                                </p>
                                <p id="showerror" class="inputerror hide" style="margin-left: 55px;">*输入的验证码不正确</p>
                            	 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>
                        </div>
                        <div class="mt60"></div>
                        <p class="tac"><a href="javascript:;" ms-on-click="ValiteEmail" data-s="重新获取" data-send="重新获取($1)"  class="btn btn-s-md btn-danger fs16 btn-s w150">发送邮件</a></p>
                        <div class="mt60"></div>

                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection