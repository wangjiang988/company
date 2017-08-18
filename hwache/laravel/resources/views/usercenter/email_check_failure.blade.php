@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
			<h2 class="title">验证邮箱</h2>
                    <div class="content-wapper">
                         
                         <div class="wapper has-min-step">
                              <div class="m-t-10"></div>
                              <div class="m-t-10"></div>
                              <h2 class="tac pay-s pay-error">抱歉，验证失败！</h2>
                              <div class="ml200 mt20">
                                    <p><b>可能原因：</b></p>
                                    <p>1.您已点击过此链接，链接失效</p>
                                    <p>2.您的邮箱验证超时，请注意在24小时内进入邮箱进行验证</p>

                              </div>
                              <p class="tac">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 wauto fs16">重新发送验证邮件</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 wauto fs16">返回修改邮箱</a>
                              </p>
                              <div class="m-t-10"></div>
                              <div class="m-t-10"></div>
                              <div class="m-t-10"></div>
                              
                              <input type="hidden" name="redicturl" value="F.C.1余额不足充值页面.html">
                        </div>

                    </div>
@endsection                  

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection