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
                <li class="step-cur">付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content payment">
                    <small class="juhuang">
                    <?php if ($order['cart_sub_status']==409): ?>
                        预约完毕
                       <?php else: ?> 
                        正在交车
                    <?php endif ?>
                    </small>
                    <i></i>
                    <small>核实信息</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <form action="{{url('cart/savezhengyi')}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="order_num" value="{{ $order_num}}">
        <input type="hidden" name="id" value="{{ $id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="wapper has-min-step">
            <div class="step-n step-n-1"></div>
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：{{$order_num}}</b></p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b>{{ddate($order['created_at'])}}</p></li>
                <li class="pdi-more">
                    <div class="psr fs14">
                      <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                         <b>更多</b>
                      </span>
                      <p class="tm tm-long" style="display: none;width: 400px;">
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
                    <p class="fs14">{{$brand[0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$brand[1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$brand[2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$body_color}}（{{$interior_color}}）</p></li>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{url('orderoverview')}}/{{$order_num}}" target="_blank" class="juhuang tdu">查看订单总详情</a></p>
            <hr class="dashed">
            <p class="fs14 ti">您在提车过程中遇到问题，与经销商服务人员交涉仍无法解决，可向华车平台提请争议处理。华车平台将公正、高效推动争议解决，根据调查核实的结果和平台有关规则，作出判定结果。 </p>
            <p class="fs14"><b>1.您在提车中遇到哪方面的主要问题（如遇到多项可多选）</b></p>
            <div class="tbl">
                <ul class="bxlist">
                    <?php foreach ($wenti as $key => $value): ?>
                        <li style="width: 30%">
                            <p><input type="checkbox" name="wenti[]" class="radio" value="{{$value}}"></p>
                            <dl>
                                <dt>{{$value}}</dt>
                                <div class="clear"></div>
                            </dl>
                            <div class="clear"></div>
                        </li>
                    <?php endforeach ?>
                    

                    <div class="clear"></div>
                </ul>
            </div>
            <div class="clear"></div>
            <p class="fs14"><b>2.请详细说明问题细节和您的诉求</b></p>
            <textarea class="txtarea" name="content"></textarea>
            <p class="fs14"><b>3.请上传清楚反映上述问题的证据材料</b></p>
            <div>
                <span class="blue fl "></span>
                <span class="juhuang tdu cp fl ml10" ms-on-click="uploadForMuliteFileInput">上传</span>
                <input type="hidden" name="" id="hfFile">
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="tac">
               
               <input type="submit" value="提交" class="btn btn-s-md btn-danger">
            </div>
            <div class="tac">
                <input type="checkbox" name="" id="">
                <span class="fs14">本人愿对上述问题和证据的真实性承担一切责任！</span>
            </div>


            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
        </form>
    </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-payment-while", "module/common/common", "bt"]);
    </script>
@endsection
