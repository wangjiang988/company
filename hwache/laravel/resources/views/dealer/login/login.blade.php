@extends('_layout.base_dealer')
@section('css')
  <link href="{{asset('themes/user.css')}}" rel="stylesheet" />
  <link href="{{asset('themes/admin.custom.login.css')}}" rel="stylesheet" />
@endsection

@section('nav')
@include('_layout.nav')
@endsection

@section('content')
  <div class="box" ms-include-src="cutomloginheader"></div>


    <div class="m-t-86 pos-rlt content custom-login" ms-controller="custom">
        <div class="container">
            <div class="wapper has-min-step">
                <form action="" name="customform">
                    <div class="login-panel">
                        <h2>经销商登录</h2>
                        <div class="custom-login-wrapper">
                            <div class="login-input-panel">
                                <label class="name"></label>
                                <input placeholder="用户名" type="text" name="loginname" value='system_seller'/>
                                <div class="clear"></div>
                            </div>
                            <div class="login-input-panel">
                                <label class="pwd"></label>
                                <input placeholder="密码" type="password" name="loginpwd" value='123456'/>
                                <div class="clear"></div>
                            </div>
                            <div class="login-input-panel panel-small">
                                <label class="codetag"></label>
                                <input placeholder="验证码" maxlength="4" class="txtcode" type="text" name="code" />
                                <div class="clear"></div>
                            </div>
                            <img class="codeimg" 
                                 onclick = "this.src='/makecode?t='+Math.random()" src="/makecode?t=" 
                                 alt=""/>
                            <div class="clear"></div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input ms-on-click="customlogin" type="button" value="登录" class="btn btn-s-md btn-danger btn-custom-login">
                            
                        </div>
                        <div class="custom-phone-valite-wrapper">
                            <div class="login-input-panel panel-small">
                                <input placeholder="请输入手机验证码" maxlength="11" class="txtphonevalite" type="text" name="phonevalite" />
                                <div class="clear"></div>
                            </div>
                            <input ms-on-click="customphonevalite" data-s="重新获取" data-send="重新获取($1)" type="button" value="获取手机验证码" class="btn btn-s-md btn-danger btn-custom-phone-valite">
                            <div class="clear"></div>
                            <input ms-on-click="customphonelogin" type="button" value="登录" class="btn btn-s-md btn-danger btn-custom-login">
                        </div>

                        <div class="error-tip"> 
                            <span class="error-txt">
                                输入的账号或者密码不正确请重新输入
                            </span>
                            <div class="clear"></div>
                        </div>
                    </div>
					<input type="hidden" name="phone" value="">
                </form>

                <div class="ruzhu-panel" ms-on-click="ruzhu"></div>
                <div class="clear"></div>

                <div id="ruzhu-tip" class="popupbox">
                    <div class="popup-title-large">
                        <span class="popup-close" ms-on-click="closepopup"></span>
                    </div>
                    <div class="popup-wrapper">
                        <div class="popup-content popup-content-ruzhu">
                            <p class="fs14 pd ti">       
                                   只要您拥有授权经销商的新车资源，并愿意遵照华车平台的规则（查看规则要点）提供产品和服务，华车平台都欢迎您与我们携起手来，共同促进市场释放更强大的潜在购买力。
                            </p>
                            <p class="fs14 ulli">  
                                <label>联系人：</label>李经理
                                <span></span>
                                <label>电话：</label>13889201920
                            </p>
                            <p class="fs14 ulli">  
                                <label>邮箱：</label>business@hWache.com
                            </p>

                        </div>
                        <div class="popup-control">
                           
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
@endsection
@section('js')
  <script type="text/javascript">
    seajs.use(["module/custom/custom_login", "module/common/common", "bt"]);
  </script>
@endsection