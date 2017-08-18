@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
			<h2 class="title">修改密码</h2>
                    <div class="content-wapper">
                         
                         <div class="wapper has-min-step">
                              <div class="mt60"></div>
                              <h2 class="tac pay-s ">恭喜您，验证成功！</h2>
                              <div class="mt20"> 
                                    <p class="tac">验证的邮箱138**2191@163.com</p>
                              </div>
                              <p class="tac mt60">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 w150  fs16">确认</a>
                              </p>
                              <div class="mt60"></div>
                               
                        </div>

                    </div>
@endsection      

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection