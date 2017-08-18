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
                                <li class="cur"><span>2</span><label>修改登录密码</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="form form-pwd pos-rlt">
                            <div class="mt60"></div>
                            <div class="input-group">
                                <span class="">&nbsp;&nbsp;&nbsp;新密码：</span>
                                <input ms-on-keydown="pwdStrong" name="pwd" type="password" class="pwdtxt" placeholder="6-20个字符，建议字母、数字和符号两种以上组合" >
                                <p class="hide inputerror mt10 ml68">请输入6~20个字符，建议字母、数字和符号两种以上组合</p>
                            </div>
                            <div class="pwd-strong pwd-modify-wapper">
                                <label>安全程度：</label>
                                <span class="p-s-less">弱</span>
                                <span class="p-s-normal">中</span>
                                <span class="p-s-max">强</span>
                            </div>
                            <div class="input-group pos-rlt">
                                <span class="">确认密码：</span>
                                <input name="pwd2" type="password" class="pwdtxt" placeholder="确认密码" >
                                <p class="hide inputerror mt10 ml68">*两次密码输入不一致</p>
                            </div>
                            <p class="tac">
                                <button ms-on-click="SetPwd" type="submit" class="w150 btn btn-s-md btn-danger">下一步</button>
                            </p>
                        </div>

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