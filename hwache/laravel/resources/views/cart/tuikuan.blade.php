@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span> </a></label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
<div class="container m-t-86 pos-rlt" >
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li class="step-cur">退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content back">
                    <small>核实金额</small>
                    <i></i>
                    <small class="juhuang">办理退款</small>
                </div>
            </div>
        </div>
    </div>
<div class="container pos-rlt content r-pdi"  ms-controller="item">

        <div class="wapper has-min-step" style="overflow: visible;">
                              
          <div class="wapper has-min-step"  style="overflow: visible;"> 
          
                <ul class="pdi-order-ul">
                    <li class="pdi-sn">
                        <p class="fs14"><b>订单号：</b>{{$order_num}}</p>
                    </li>
                    <li class="pdi-time"><p class="fs14"><b>订单时间：</b>{{$order['cartBase']['created_at']}}</p></li>
                    <li class="pdi-more">
                        <div class="psr fs14">
                          <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                             <b>更多</b>
                          </span>
                          <p class="tm tm-long" style="display: none;">
                            @if(count($cart_log)>0)     
								@foreach($cart_log as $k =>$v )
								<span>{{$v['msg_time']}}：{{$v['time']}}</span>
								@endforeach
							@endif
                          </p>
                        </div>


                    </li>
                    <div class="clear"></div>
                </ul>
                <div class="clear"></div>
                <ul class="pdi-order-ul border">
                    <li class="pdi-name">
                        <p class="fs14">{{$bj['brand'][0]}}</p>
                    </li>
                    <li class="pdi-type"><p class="fs14">{{$bj['brand'][1]}}</p></li>
                    <li class="pdi-title"><p class="fs14">{{$bj['brand'][2]}}</p></li>
                    <li class="pdi-color"><p class="fs14">{{$bj['body_color']}}</p></li>
                    <div class="clear"></div>
                </ul>     

                <p class="fs14 tac">
                    <a href="#" class="juhuang tdu">查看订单总详情</a>
                </p>
                <hr class="dashed">     

            </div>         
            <div class="box">
               
                <div class="box-inner  box-inner-def">
                    <h1>尊敬的客户： </h1>
                    <p class="fs14 ti">本次购车应退还您的余额：人民币2,600.00元</p>
                    <p class="fs14 ti">入您账户可用余额的时间：2016-06-17 11:10:10</p>
                    <div class="m-t-10"></div>
                    <p class="tac m-t-10">
                        <a href="/user/memberFinance" class="btn btn-s-md btn-danger sure  bt wauto">查看我的余额</a>
                        <a href="/user/memberCash" class="btn btn-s-md btn-danger sure  bt ml20">申请提现</a>
                        <a href="/user/memberInvoiceList" class="btn btn-s-md btn-danger sure  bt ml20">申请开票</a>
                        <a href="/" class="btn btn-s-md btn-danger sure  bt ml20">再选辆车</a>
                    </p>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <form action="{{url('cart/pingjia')}}" method="post">
                    <table>
                        <tr>
                            <td valign="middle">
                                <p class="m0"><b>华车平台的服务：</b></p>
                            </td>
                            <td>
                                <div class="form-item">
                                  <div class="formItemDiff formItemDiffFirst sele"><div class="cot st1 cot-first"><p class="wps">1分 很不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">2分 不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">3分 一般<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">4分 满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">5分 很满意<span class="ttip"></span></p></div></div>
                                  <input type="hidden" name="hwache_service" value="">
                                </div>
                            </td>
                        </tr>
                         
                    </table>
                  
                    <table>
                        <tr>
                            <td valign="middle">
                                <p class="m0"><b>经销商的服务：</b></p>
                            </td>
                            <td>

                                <div class="form-item" style="padding-left: 14px;">
                                  <div class="formItemDiff sele formItemDiffFirst psr"><div class="cot st1 cot-first"><p class="wps">1分 很不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">2分 不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">3分 一般<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">4分 满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">5分 很满意<span class="ttip"></span></p></div></div>
                                  <input type="hidden" name="seller_service" value="">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <p><b>您的感受：</b></p>
                    <textarea class="cont" placeholder="华车平台将有独立客服倾听您的心声，您的个人隐私、您的评论身份不会向经销商公开。" name='evaluate'></textarea>
                    <p class="tal">
                        <input type="submit" data-grounp="" class="btn btn-s-md btn-danger fs16" value="提交">
                    </p>
                    <input type="hidden" name="order_id" value="{{ $order['cartBase']['id']}}">
                    <input type="hidden" name="order_num" value="{{$order_num}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
               
            </div>
        
        </div>
    
@endsection
@section('js')    
    <script type="text/javascript">
        seajs.use(["module/item/item-back-guarantees", "module/common/common", "bt"]);
    </script>
@endsection