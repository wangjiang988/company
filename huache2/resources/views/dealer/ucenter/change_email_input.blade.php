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
                                <li><span>2</span><label>修改邮箱</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="ml160 mt60">
                            <form action="" method="post" name="emailform">
                                <p class="p">
                                   <span class="pull-left">输入邮箱：</span>
                                   <input type="text" name="email" placeholder="" class="form-control pay-control pull-left email-input">
                                   <span class="inputerror ml10 hide">*邮箱格式不正确，请重新输入</span>
                                   <div class="clear"></div>
                                </p>
                                <p class="fs14 m-t-10">
                                   <span class="pull-left">&nbsp;&nbsp;&nbsp;验证码：</span>
                                   <input type="text" name="code" placeholder="" class="form-control pay-control pull-left code">
                                   <img src="/makecode?t=" ms-on-click="this.src='/makecode?t='+Math.random()"  class="valite-code" alt="">
                                   <div class="clear"></div>
                                </p>
                                <p id="showerror" class="inputerror hide" style="margin-left: 55px;">*输入的验证码不正确</p>
                            </form>
                        </div>
                        <div class="mt60"></div>
                        <p class="tac"><a href="javascript:;" ms-on-click="ValiteEmail" data-s="重新获取" data-send="重新获取($1)"  class="btn btn-s-md btn-danger fs16 btn-s w150">发送邮件</a></p>




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