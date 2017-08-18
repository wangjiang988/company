@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    
                  
                    <h4 class="title"><span>修改密码</span></h4>

                    <div class="content-wapper ">
                        <div class="hd">
                           <ul>
                                <li><span>1</span><label>验证身份</label></li>
                                <li><span>2</span><label>修改登录密码</label></li>
                                <li class="cur"><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class=" has-min-step">
                              <div class="mt60"></div>
                              <h2 class="tac pay-s ">恭喜您，修改成功！</h2>
                              <div class="ml200 mt60"> 
                              </div>
                              <p class="tac">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 w150  fs16">确认</a>
                              </p>
                              <div class="mt60"></div>
                               
                        </div>


                        <div class="m-t-10"></div>
                        <div class="m-t-10"></div>
                        <div class="m-t-10"></div>

                    </div>
					</div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"]);
        setTimeout("window.location.href='/dealer/loginout'",3000)
	</script>
@endsection