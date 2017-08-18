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
                                <li><span>1</span><label>验证身份</label></li>
                                <li class="cur"><span>2</span><label>修改手机号</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="ml185 mt60">
                            <p class="p">
                               <span class="pull-left">新的手机号：</span>
                               <input type="text" name="phone" placeholder="" class="form-control custom-control pull-left email-input">
                               <span class="inputerror ml10 hide">*手机号格式不正确，请重新输入</span>
                               <div class="clear"></div>
                            </p>

                            <p class="fs14 m-t-10">
                               <span class="pull-left">手机验证码：</span>
                               <input type="text" name="phonecode" placeholder="" class="form-control custom-control pull-left code">
                               <a href="javascript:;" ms-on-click="GetCode" data-s="重新获取" data-send="重新获取($1)" class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s bt sure btn-code">获取验证码</a>
                               <div class="clear"></div>
                             </p>
                             <p id="showerror" class="inputerror hide" style="margin-left: 83px;">*输入的验证码不正确或者修改号码与原始号码一致</p>

                        </div>
                        <p class="tac"><a href="javascript:;" ms-on-click="modifyPhone" class="btn btn-s-md btn-danger fs16 btn-s w150">下一步</a></p>


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