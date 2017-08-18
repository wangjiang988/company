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
                                <li class="cur"><span>1</span><label>验证身份</label></li>
                                <li><span>2</span><label>修改登录密码</label></li>
                                <li><span>3</span><label>完成</label></li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="ml160 mt60">
                            <form action="" method="post" name="pwdform">
                                <table class="tbl-pwd">
                                    <tr>
                                        <td align="right">
                                            <p>已验证的手机：</p>
                                        </td>
                                        <td align="left">
                                            <p>{{changeMobile($member['member_mobile'])}}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <p>手机验证码：</p>
                                        </td>
                                        <td align="left">
                                            <input type="text" name="ipt-code" placeholder="" class="form-control custom-control pull-left code">
                                            <a href="javascript:;" ms-on-click="SendCode('{{$member['member_mobile']}}',2)" data-s="重新获取" data-send="重新获取($1)" class="ml20 pull-left btn btn-s-md btn-danger fs14 btn-s bt sure btn-code" style="margin-top: -4px;">获取验证码</a>
                                            <div class="clear"></div>
                                            <div class="error-div mt5"><label>*验证码不正确，请重新输入</label></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <p>身份证后六位：</p>
                                        </td>
                                        <td align="left">
                                            <input type="text" name="ipt-num-card" placeholder="" class="form-control custom-control code">
                                            <div class="error-div mt5"><label>*输入不正确，请重新输入</label></div>
                                        </td>
                                    </tr>
                                </table>


                            </form>
                        </div>
                        <br /><br />
                        <p class="tac"><a href="javascript:;" ms-on-click="valitepwdmodify" data-s="重新获取" data-send="重新获取($1)"  class="btn btn-s-md btn-danger fs16 btn-s w150">下一步</a></p>
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