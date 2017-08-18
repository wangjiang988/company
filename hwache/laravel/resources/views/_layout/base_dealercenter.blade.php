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
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/common.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/user.css')}}" rel="stylesheet" />
     <link href="{{asset('themes/admin.custom.css')}}" rel="stylesheet" />
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>
<body id="web">

    <div class="box" >
        <nav class="navbar navbar-inverse navbar-fixed-top" >
            <div class="container">
                <div id="navbar" class="collapse navbar-collapse">
                    <div class="navbar-header pos-rlt">
                        <a class="navbar-brand logo" href="/">华车网</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li><a href="/dealer">首页</a></li>
                        <li><a href="#baozhang">诚信保障</a></li>
                        <li><a href="#services">帮助中心</a></li>
                    </ul>
                    <ul class="nav navbar-nav control">
                        <li class=""><a href="{{ route('dealer.ucenter') }}">{{ session('user.seller_name') }}</a><i></i></li>
                        <li><a href="{{ route('dealer.loginout') }}">退出登录</a></li>
                    </ul>
                </div>

            </div>
        </nav>
    </div>


    <div class="container m-t-86 pos-rlt content" ms-controller="custom">
        <div class="wapper has-min-step">
            <div class="box box-border">
                <div class="slide">
                     <div class="portlet-body">



                        <div class="panel-group accordion" id="accordion1">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle icon-user" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1"> 我的信息 </a>
                                    </h4>
                                    <i class="fa fa-sort-up"></i>
                                </div>
                                <div id="collapse_1" class="panel-collapse <?php if($flag=='memberInfo' || $flag=='modifyPassword'){echo 'in"';}else{ echo 'collapse';}?>">
                                    <div class="panel-body">
                                        <a href="/dealer/member_info" <?php if($flag=='memberInfo'){echo 'class="menu-select"';}?>>基本信息</a>
                                        <a href="/dealer/modify_password" <?php if($flag=='modifyPassword'){echo 'class="menu-select"';}?>>修改密码</a>

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default ">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle icon-set" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2"> 常用管理  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                @if(isset($daili_dealer_id))
                                <div id="collapse_2" class="panel-collapse 
                                <?php if($flag=='editDealer' || $flag=='carmodel'.$daili_dealer_id || $flag=='information'.$daili_dealer_id || $flag=='worktime'.$daili_dealer_id || $flag=='custorfile'.$daili_dealer_id || $flag=='surance'.$daili_dealer_id){echo 'in"';}else{ echo 'collapse';}?>">
                                @else
                                <div id="collapse_2" class="panel-collapse 
                                <?php if($flag=='editDealer' || $flag=='carmodel' || $flag=='information' || $flag=='worktime' || $flag=='custorfile' || $flag=='surance'){echo 'in"';}else{ echo 'collapse';}?>">
                                @endif           
                                    <div class="panel-body" >                                   

                                      @foreach ($view['common_info'] as $comm)  
                                        <a style="background: url({{'http://upload.123.com/'.$comm['detail_img']}}) 15px 50%  no-repeat;background-size:27px" data-toggle="collapse" data-parent="#collapse_2" href="#collapse_x{{$comm['id']}}"
                                        @if(isset($daili_dealer_id) && $comm['id'] == $daili_dealer_id)
                                        class="psr"
                                        @else class="psr collapsed"
                                        @endif >{{$comm['d_shortname']}}<i class="fa fa-sort-up"></i></a>
                                        <div id="collapse_x{{$comm['id']}}"
                                         @if(isset($daili_dealer_id) && $comm['id'] == $daili_dealer_id)
                                        class="panel-collapse in"
                                        @else
                                        class="panel-collapse collapse"
                                        @endif>
                                           
                                            @if($comm['dl_status'] == 4 )
                                            <a  href="{{route('dealer.editdealer',['type'=>'edit','id'=>$comm['id']])}}" <?php if($flag=='information'.$comm['id']){echo 'class="review-return"';}?>>审核退回</a>
                                            @else
                                            <a href="{{route('dealer.editdealer',['type'=>'edit','id'=>$comm['id']])}}" <?php if($flag=='information'.$comm['id']){echo 'class="menu-select"';}?>>基本信息</a>
                                            @endif
                                            @if($comm['dl_status'] == 2)
                                            <a href="/dealer/carmodel/{{$comm['d_id']}}" <?php if($flag=='carmodel'.$comm['id']){echo 'class="menu-select"';}?>>常用车型</a>
                                            <a href="{{route('dealer.surance',[$comm['d_id']])}}" <?php if($flag=='surance'.$comm['id']){echo 'class="menu-select"';} ?>>保险设定</a>
                                            <a href="{{route('dealer.custorfile',[$comm['d_id']])}}" <?php if($flag=='custorfile'.$comm['id']){echo 'class="menu-select"';} ?> >客户文件</a>
                                            <a href="{{route('dealer.worktime',[$comm['d_id']])}}" <?php if($flag == 'worktime'.$comm['id']){echo 'class="menu-select"';} ?> >工作时段</a>
                                             @endif                        
                                        </div>

                                       @endforeach  

                                        <a href="/dealer/editdealer/add/0" <?php if($flag=='editDealer'){echo 'class="menu-select"';}?>>增加经销商</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default ">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle icon-price" data-toggle="collapse" data-parent="#accordion1" href="#collapse_3"> 报价管理  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                <div id="collapse_3" class="panel-collapse  <?php if(in_array($flag,array('baojia-new','baojia-unfinished','baojia-to-be-effective','baojia-online','baojia-suspensive','baojia-useless'))){echo 'in"';}else{ echo 'collapse';}?>"">
                                    <div class="panel-body" >
                                        <a  data-toggle="collapse" data-parent="#collapse_3" href="#collapse_x2" class="psr">新建报价({{$view['baojiaCount']['unfinished']}})<i class="fa fa-sort-up"></i></a>
                                        <div id="collapse_x2" class="panel-collapse in">
                                            <a href="/dealer/baojia/edit/0/1" class="idt <?php if($flag=='baojia-new'){echo 'cur-select-step';}?>">开始新建</a>
                                            <a href="/dealer/baojialist/unfinished" class="idt <?php if($flag=='baojia-unfinished'){echo 'cur-select-step';}?>">新建未完({{$view['baojiaCount']['unfinished']}})</a>
                                         </div>                                                                              
                                            <a href="{{route('dealer.baojialist','effective')}}" class="<?php if($flag=='baojia-to-be-effective'){echo 'cur-select-step';}?>">等待生效({{$view['baojiaCount']['effective']}})</a>
                                            <a href="{{route('dealer.baojialist','online')}}" class="<?php if($flag=='baojia-online'){echo 'cur-select-step';}?>">正在报价({{$view['baojiaCount']['online']}})</a>
                                            <a href="{{route('dealer.baojialist','suspensive')}}" class="<?php if($flag=='baojia-suspensive'){echo 'cur-select-step';}?>">暂时下架({{$view['baojiaCount']['suspensive']}})</a>
                                            <a href="{{route('dealer.baojialist','useless')}}" class="<?php if($flag=='baojia-useless'){echo 'cur-select-step';}?>">失效报价({{$view['baojiaCount']['useless']}})</a>
                                        

                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle icon-order" data-toggle="collapse" data-parent="#accordion1" href="#collapse_4"> 订单管理  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                <div id="collapse_4" class="panel-collapse collapse">
                                    <div class="panel-body" >
                                        <a href="#">订单管理</a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default last">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle icon-money" data-toggle="collapse" data-parent="#accordion1" href="#collapse_5"> 资金管理  </a>
                                    </h4>
                                    <i class="fa fa-sort-down"></i>
                                </div>
                                <div id="collapse_5" class="panel-collapse collapse">
                                    <div class="panel-body" >
                                        <a href="#">资金管理</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="user-content custom-content">
                    @yield('content')
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
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
    
    </div>

</body>
</html>