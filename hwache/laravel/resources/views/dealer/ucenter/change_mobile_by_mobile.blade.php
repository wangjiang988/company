@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    
                  
                    <h4 class="title"><span>修改手机号</span></h4>

                    <div class="content-wapper ">
                        <div class="hd">
                           <ul>
                                <li class="cur"><span>1</span><label>验证身份</label></li>
                                <li><span>2</span><label>修改手机号</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="ml160 mt60">
                            <form action="" method="post" name="mobileform">
                                <p class="p">已验证的手机：  {{changeMobile($member['member_mobile'])}}  </p>
                                <p class="fs14 m-t-10">
                                   <span class="pull-left">手机验证码：</span>
                                   <input type="text" name="code" placeholder="" class="form-control pay-control pull-left code">
                                   <a href="javascript:;" ms-on-click="SendCode('{{$member['member_mobile']}}',3)" data-s="重新获取" data-send="重新获取($1)" class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s bt sure btn-code" style="margin-top: -4px;">获取验证码</a>
                                   <div class="clear"></div>
                                </p>
                                <p id="showerror" class="inputerror hide" style="margin-left: 55px;">*输入的验证码不正确</p>
                            </form>
                        </div>
                        <br /><br />
                        <p class="tac"><a href="javascript:;" ms-on-click="CheckMobileCode('mobile')" data-s="重新获取" data-send="重新获取($1)"  class="btn btn-s-md btn-danger fs16 btn-s w150">下一步</a></p>
                        <p class="tac"><a href="/dealer/changemobile/byemail" class="juhuang">使用已验证邮箱修改手机号>></a></p>
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