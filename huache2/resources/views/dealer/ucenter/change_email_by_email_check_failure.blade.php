@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    
                  
                    <h4 class="title"><span>修改邮箱</span></h4>

                    <div class="content-wapper ">
                        <div class="hd">
                           <ul>
                                <li><span>1</span><label>验证身份</label></li>
                                <li class="cur"><span>2</span><label>修改邮箱</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class=" has-min-step">
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
                              </p>
                              <div class="m-t-10"></div>
                              <div class="m-t-10"></div>
                              <div class="m-t-10"></div>
                              
                              <input type="hidden" name="redicturl" value="F.C.1余额不足充值页面.html">
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
	</script>
@endsection