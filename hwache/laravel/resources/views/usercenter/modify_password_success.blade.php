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
                                <li><span>1</span><label>验证身份</label></li>
                                <li><span>2</span><label>修改登录密码</label></li>
                                <li class="cur"><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                         <div class="wapper has-min-step">
                              <div class="mt60"></div>
                              <h2 class="tac pay-s ">恭喜您，修改成功！</h2>
                              <div class="ml200 mt60"> 
                              </div>
                              <p class="tac">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 w150  fs16">确认</a>
                              </p>
                              <div class="mt60"></div>
                               
                        </div>

                    </div>
@endsection   

@section('js')
	 <script type="text/javascript">
		window.setTimeout("window.location.href = '/user/login';",3000)
		        
	</script>
@endsection