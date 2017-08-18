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
                <li>退担保金<i></i></li>
                <li class="step-cur">完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content complete">
                    <small class="juhuang">敬请点评</small>
                    <i></i>
                    <small>感谢评价</small> 
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi"  ms-controller="item">

        <div class="wapper has-min-step" style="overflow: visible;">
                              
                     
            <div class="box">
               
                <div class="box-inner  box-inner-def">
                <form action="{{url('cart/pingjia')}}" method="post">  
                    <table>
                        <tr>
                            <td width="100" class="tac"><b>订单号</b></td>
                            <td width="350" class="tac"><b>订单内容</b></td>
                            <td width="225" class="tac"><b>生效时间</b></td>
                            <td width="225" class="tac"><b>完成时间</b></td>
                        </tr>
                    </table>
                    <table class="tbl">
                        <tbody>
                           
                            <tr>
                                <td width="100"><p class="tac fs14 ">{{$order_num}}</p></td>
                                <td width="350"><p class="tac fs14 ">{{$bj['brand'][0]}}+{{$bj['brand'][1]}}+{{$bj['brand'][2]}}</p></td>
                                <td width="225"><p class="tac fs14 ">{{$order['cartBase']['created_at']}}</p></td>
                                <td width="225"><p class="tac fs14 ">{{$order['cartBase']['updated_at']}}</p></td>
                            </tr> 
                            
                        </tbody>
                    </table>
                    <table>
                        <tr>
                            <td valign="middle">
                                <p class="m0"><b>华车网的服务：</b></p>
                            </td>
                            <td>
                                <div class="form-item">
                                  <div class="formItemDiff formItemDiffFirst sele"><div class="cot st1 cot-first"><p class="wps">1分 很不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">2分 不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">3分 一般<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff "><div class="cot "><p class="wps">4分 满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">5分 很满意<span class="ttip"></span></p></div></div>
                                  <input type="hidden" name="hwache_service" value="3">
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
                                <div class="form-item">
                                  <div class="formItemDiff sele formItemDiffFirst psr"><div class="cot st1 cot-first"><p class="wps">1分 很不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">2分 不满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff sele"><div class="cot "><p class="wps">3分 一般<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">4分 满意<span class="ttip"></span></p></div></div>
                                  <div class="formItemDiff"><div class="cot "><p class="wps">5分 很满意<span class="ttip"></span></p></div></div>
                                  <input type="hidden" name="seller_service" value="3">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <p><b>您的感受：</b></p>
                    <textarea class="cont" placeholder="华车网将有独立客服倾听您的心声，您的个人隐私、您的评论身份不会向经销商公开。" name="evaluate"></textarea>
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
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-back-guarantees", "module/common/common", "bt"]);
    </script>
@endsection
