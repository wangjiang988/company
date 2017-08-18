<!DOCTYPE html>
<html>
<head>
    <title>{{ $title or trans('common.www_title') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="description" content="华车网" />
    <meta name="keywords" content="华车网" />
    <meta name="author" content="llm" />
    <link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/common.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/user.css')}}" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
@yield('css')
</head>
<body ms-controller="web">

    <div class="box" >
        <nav class="navbar navbar-inverse navbar-fixed-top" >
            <div class="container">
                <div id="navbar" class="collapse navbar-collapse">
                    <div class="navbar-header pos-rlt">
                        <a class="navbar-brand logo" href="/">华车网</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a href="#baozhang">诚信保障</a></li>
                        <li><a href="#services">帮助中心</a></li>
                    </ul>
                    <ul class="nav navbar-nav control">
                        <li class=""><a href="{{ route('user.ucenter') }}">{{ session('user.member_name') }}</a><i></i></li>
                        <li><a href="{{ route('user.logout') }}">退出登录</a></li>
                    </ul>
                </div>

            </div>
        </nav>
    </div>

    <div class="container m-t-86 pos-rlt content" ms-controller="user">
        <div class="wapper has-min-step">
            <div class="box box-border">
                <div class="slide">
                     <div class="portlet-body">

                        <div class="panel-group accordion" id="accordion1">
                          <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1"> 我的华车 </a>
                                    </h4>
                                    <i class="fa fa-sort-up"></i>
                                </div>
                                <div id="collapse_1" class="panel-collapse <?php if(in_array($flag,array('memberInfo','memberSafe','memberBankAccount'))){echo 'in';}else{echo 'collapse';}?>">
                                    <div class="panel-body">
                                        <a href="/user/memberInfo" class="<?php if($flag == 'memberInfo'){echo 'menu-select';}?>">个人资料</a>
                                        <a href="/user/memberSafe/index" class="<?php if($flag == 'memberSafe'){echo 'menu-select';}?>">安全设置</a>
                                        <a href="/user/memberBankAccount" class="<?php if($flag == 'memberBankAccount'){echo 'menu-select';}?>">银行账户管理</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default ">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2"> 我的订单  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                <div id="collapse_2" class="panel-collapse <?php if($flag == 'memberOrder'){echo 'in';}else{echo 'collapse';}?>">
                                    <div class="panel-body" >
                                        <a href="/user/memberOrder" class="<?php if($flag == 'memberOrder'){echo 'menu-select';}?>">我的订单</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default ">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_3"> 我的财务  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                <div id="collapse_3" class="panel-collapse <?php if(in_array($flag,array('memberFinance','memberCash','memberInvoice','memberInvoiceList'))){echo 'in';}else{echo 'collapse';}?>">
                                    <div class="panel-body" >
                                        <a href="/user/memberFinance" class="<?php if($flag == 'memberFinance'){echo 'menu-select';}?>">我的余额</a>
                                        <a href="/user/memberCash" class="<?php if($flag == 'memberCash'){echo 'menu-select';}?>">我的提现</a>
                                        <a href="/user/memberInvoiceList" class="<?php if($flag == 'memberInvoice' || $flag == 'memberInvoiceList'){echo 'menu-select';}?>">我的发票</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default last">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapse_4"> 我的文件  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                <div id="collapse_4" class="panel-collapse <?php if(in_array($flag,array('memberFile','memberSpecialFile'))){echo 'in';}else{echo 'collapse';}?>">
                                    <div class="panel-body" >
                                        <a href="/user/memberFile" class="<?php if($flag == 'memberFile'){echo 'menu-select';}?>">我的文件</a>
                                        <a href="/user/memberSpecialFile" class="<?php if($flag == 'memberSpecialFile'){echo 'menu-select';}?>">提车所需文件格式下载</a>
                                    </div>
                                </div>
                            </div>
                        @yield('nav')

                        </div>
                    </div>
                </div>
                <div class="user-content">
                   @yield('content')
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

    </div>

    <div class="box">
        <footer class="footer">
        <div class="footer-menu container">
            <ul>

                <li>
                    <h3>用户指南</h3>
                    <a href="#">买车流程</a>
                    <a href="#">诚信保障</a>
                    <a href="#">注意事项</a>
                </li>
                <li>
                    <h3>服务中心</h3>
                    <a href="#">服务协议</a>
                    <a href="#">平台规则</a>
                    <a href="#">常见问题</a>
                </li>
                <li>
                    <h3>关于我们</h3>
                    <a href="#">平台简介</a>
                    <a href="#">联系方式</a>
                    <a href="#">发现职位</a>
                </li>
                <li>
                    <h3>商务合作</h3>
                    <a href="#">加盟方入口</a>
                    <a href="#">媒体合作</a>
                    <a href="#">友情链接</a>
                </li>
                <li>
                        <div  class="qrcode"><p>加微信关注我们</p></div>
                </li>

            </ul>
        </div>
        <div class="sp"></div>
        <div class="container pos-rlt foot-info">
            <p>@CopyRight 2014- 2015, 苏州华车网络科技有限公司   版权所有</p>
            <p> 工业信息化部信息备案：苏ICP备14017673号-1 </p>
        </div>
    </footer>

    <script src="{{asset('js/sea.js')}}"></script>
    <script src="{{asset('js/config.js')}}"></script>

    @yield('js')

    @section('zm')
    <div class="zm">
        <div id="login" class="">
            <div class="l-wapper">
                <div class="l-head">
                    <div class="l-h-bg">用户登录<a href="javascript:;" ms-click="closeLogin" class="login-close"></a></div>
                </div>
                <div class="l-c">
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">账号:</span>
                        <input data-toggle="tooltip" data-placement="bottom" name="loginname" type="text" required class="form-control" placeholder="请输入手机号码/邮箱" title="请输入手机号码/邮箱" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入</span>
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">密码:</span>
                        <input data-toggle="tooltip" type="password" data-placement="top" name="loginpwd" type="text" required class="form-control" placeholder="请输入密码" title="请输入密码" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入</span>
                    </div>
                    <br>
                    <div class="input-group code">
                        <span class="input-group-addon">请输入验证码:</span>
                        <input name="logincode" type="text" required class="form-control" placeholder="请输入验证码" title="请输入验证码" aria-describedby="basic-addon1">
                        <span class="input-group-addon hide error">请正确输入</span>
                        <span class="input-group-addon valite-code"></span>
                    </div>
                    <div class="pos-rlt">
                        <button ms-on-click="SmipSubLoign" type="submit" class="btn btn-s-md btn-danger">立即登陆</button>
                        <div class="smip-login-loading"></div>
                    </div>
                    <p class="reg-help">
                        <a href="forget.html">忘记密码?</a>
                        <a href="javascript:;" class="simp-reg">快捷注册</a>
                    </p>
                    <div class="reg-method">
                        <span>其他账号登陆</span>
                        <a href="javascript:;" class="l-sina">新浪登陆</a>
                        <a href="javascript:;" class="l-qq">QQ登陆</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>

</body>
</html>


