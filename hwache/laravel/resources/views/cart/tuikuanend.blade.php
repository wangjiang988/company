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
                    <small>核实信息</small>
                    <i></i>
                    <small>办理退款</small>
                    <i></i>
                    <small class="juhuang">退款完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi"  ms-controller="item">
        
        <div class="wapper has-min-step">
                              
            <h1>尊敬的客户：</h1>
            <h1 class="ti">您的退款提现申请已提交。退款到账时间视第三方支付机构办理周期而定，一般不超过7个工作日如有任何问题请咨询您的第三方支付机构或<a href="#" class="tdu juhuang">联系我们</a>。   </h1>
            <br>
                        
            <div class="box">
                <div class="box-inner  box-inner-def">
                    <p><b>退款金额：</b>人民币****元</p>
                    <p><b>收款账户：  </b>线上原路退回 </p>
                    <p class="ifl"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p>支付宝：人民币680.94元</b></p>
                    </div>
                    <div class="clear"></div>
                    
                    <p class="ifl"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p>财付通：人民币566.00元</p>
                    </div>
                    <div class="clear"></div>
                    <p><b>退款时间：</b>2016-02-04  17:52:34</p>
                    <p>请您查收！谢谢！</p>
                    <p>
                        <a href="#" class="juhuang tdu ml20">申请开具发票</a>
                    </p>
                    <hr class="dashed">           
                    
                     <h1>尊敬的客户：</h1>
                     <h1 class="ti">很高兴告诉您，退款提现手续已办理完毕！</a></h1>
                     <br>
                     <p><b>退款金额：</b>人民币2,978.00元</p>
                     <p><b>退款方式：  </b>银行转账退回 </p>
                     <p class="ifl"><i class="yuan"></i></p>
                     <div class="ifl">
                        <p>开户行：（江苏省苏州市）中信银行苏州分行营业部</b></p>
                     </div>
                     <div class="clear"></div>
                    
                     <p class="ifl"><i class="yuan"></i></p>
                     <div class="ifl">
                        <p>账    号：<b>4226 1234 2222 321</b></p>
                     </div>
                     <div class="clear"></div>
                     <p class="ifl"><i class="yuan"></i></p>
                     <div class="ifl">
                        <p>户    名：徐清新</p>
                     </div>
                     <div class="clear"></div>
                     <p><b>退款金额：</b>人民币2,978.00元</p>
                  
                     <p>
                        <a href="#" class="juhuang tdu">查看退款凭证</a>
                        <a href="#" class="juhuang tdu ml150">申请开具发票</a>
                        <a href="#" class="juhuang tdu ml150">收款遇到问题</a>
                     </p>
                     <hr class="dashed">         

                     <h1>尊敬的客户：</h1>
                     <h1 class="ti">您的结算已完成！    </a></h1>
                     <br>
                     <p><span class="fl">可用余额：人民币2,478.00元</span><a href="javascript:;" class="btn btn-s-md btn-danger sure fl bt ml10">查看明细</a></p>
                     <p class="clear">
                        <a href="#" class="juhuang tdu ml20">申请开具发票</a>
                     </p>
                     <p class="tac">
                        <a href="javascript:;" class="btn btn-s-md btn-danger sure ml20 fs18">再买辆车</a>
                     </p>
                     <hr class="dashed">      


                    <ul class="pdi-order-ul">
                        <li class="pdi-sn">
                            <p class="fs14"><b>订单号：</b></p>
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
                        <li class="pdi-color"><p class="fs14">{{$bj['body_color']}}({{$bj['interior_color']}})</p></li>
                        <div class="clear"></div>
                    </ul>     

                    <p class="fs14 tac">
                        <a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a>
                    </p>
                    @if($order['cartAttr']['butie']==1)
			                <div class="fs14 m-t-10 clear">
			                @if($jiaoche['user_butie_get_date']=='')
			                    <span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['user_butie_date']}}    </span>
			                    <a ms-on-click="pdibutie" href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">发放补贴</a>
			                	<span class="fl">（尚未收到不必点击）。</span>
			                @else
			                	<a href="javascript:;" class="btn btn-s-md btn-danger oksure bt" >已收到补贴</a>
			                @endif
			                    <div class="clear"></div>
			                </div>
		            @endif
					
					<div id="pdi-tip" class="popupbox">
        			 <form action='{{ url('cart/ajax').'/'.$order_num.'/surebutie' }}' name='surebutieform' method='post' enctype="multipart/form-data">
                        <div class="popup-title">提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs14 pd ti">       
                                确定已收到约定的全部国家节能补贴吗？
                                </p>
                            </div>
                            <div class="popup-control">
                                <a ms-on-click="surebutie" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$order_num}}" name="order_num" >
                        <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      </form>
                    </div>
                    
                    
                    <p class="fs14">您的感受是我们奋斗的动力！</p>    
                    <p class="tac"><a href="/cart/pingjia/{{$order_num}}" class="btn btn-s-md btn-danger fs16" >敬请点评</a></p>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
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