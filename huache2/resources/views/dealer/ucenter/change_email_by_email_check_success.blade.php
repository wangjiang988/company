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
                         <div class="ml200 mt60">
                            <dl class="valite-dl">
                                <dt>
                                    <span class="valite-ok-2 fl"></span> 
                                    <span class="fl ml10">已发送邮件至：<b>{{session('need_to_modify_email')}}</b>
                                    <br>
                                    验证邮件在24小时内有效，请尽快登录您的邮箱进行验证。
                                    </span>
                                </dt>
                                <div class="clear"></div>
                            </dl>
                        </div>

                        <br /><br />
                        <p class="tac">
                            <a href="javascript:;" class="btn btn-s-md btn-danger   ml20 wauto">查看验证邮件</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 wauto">重新发送验证邮件</a>
                        </p>

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