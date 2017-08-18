@extends('_layout.base')
@section('css')
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
    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content" style=" left: 187px;">
                    <small class="juhuang">正在支付</small>
                    <i></i>
                    <small>查收确认</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">很高兴您与经销商就订单部分修改达成共识，您的心仪座驾即将揭开神秘面纱，早点把Ta迎回家吧！</p>


            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：{{$order_num}}</td>
                    <td width="50%">
                        <div class="psr fs14">
                          订单时间：{{ddate($order->created_at)}}
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
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <ul class="pdi-order-ul border ">
                <li class="pdi-name">
                    <p class="fs14">{{$brand[0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$brand[1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$brand[2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$body_color}}（{{$interior_color}}）</p></li>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{url('orderoverview')}}/{{$order_num}}" class="tdu juhuang" target="_blank">查看订单总详情</a></p>

            <hr class="dashed">
            <p class="fs14">订单部分修改内容如下：</p>
            <table class="tbl fl" style="width: 80%;">
                <tr>
                    <th>
                        <p class="fs14">项目</p>
                    </th>
                    <th>
                        <p class="fs14">原约定内容</p>
                    </th>
                    <th>
                        <p class="fs14">现约定内容</p>
                    </th>
                </tr>
                <?php if(isset($editcarinfo) && count($editcarinfo)>0):?>
                <?php if ($body_color<>$editcarinfo['body_color']): ?>
                    <tr>
                        <td>
                            <p class="tac fs14">车身颜色</p>                    </td>
                      <td><p class="tac fs14">{{$body_color}}</p></td>
                        <td><p class="tac fs14">{{$editcarinfo['body_color']}}</p></td>
                    </tr>
                <?php endif ?>
                
                <?php if ($interior_color<>$editcarinfo['interior_color']): ?>
                    <tr>
                      <td><p class="tac fs14">内饰颜色</p></td>
                      <td><p class="tac fs14">{{$interior_color}}</p></td>
                      <td><p class="tac fs14">{{$editcarinfo['interior_color']}}</p></td>
                    </tr>
                <?php endif ?>
                
                <?php if ($paifangTitle<>$editcarinfo['paifang']): ?>
                    <tr>
                      <td><p class="tac fs14">排放标准</p></td>
                      <td><p class="tac fs14">{{$paifangTitle}}</p></td>
                      <td><p class="tac fs14">{{$editcarinfo['paifang']}}</p></td>
                    </tr>
                <?php endif ?>
                <?php if ($bj_producetime<>$editcarinfo['chuchang']): ?>
                    <tr>
                      <td><p class="tac fs14">出厂年月</p></td>
                      <td><p class="tac fs14">(不早于){{$bj_producetime}}</p></td>
                      <td><p class="tac fs14">(不早于){{$editcarinfo['chuchang']}}</p></td>
                    </tr>
                <?php endif ?>
                
                <?php if ($bj_jc_period<>$editcarinfo['zhouqi']): ?>
                    <tr>
                        <td>
                            <p class="tac fs14">交车周期</p>
                            </td>
                      <td><p class="tac fs14">{{$bj_jc_period}}</p></td>
                        <td><p class="tac fs14">{{$editcarinfo['zhouqi']}}</p></td>
                    </tr>
                <?php endif ?>
                <?php if ($bj_licheng<>$editcarinfo['licheng']): ?>
                    <tr>
                        <td>
                            <p class="tac fs14">里程</p>
                            </td>
                      <td><p class="tac fs14">（不高于）{{$bj_licheng}} 公里</p></td>
                        <td><p class="tac fs14">（不高于）{{$editcarinfo['licheng']}} 公里</p></td>
                    </tr>
                <?php endif ?>
				<?php endif ?>

            </table>
            <div class="clear"></div>
            <hr class="dashed clear">

            <table class="tbl fl" style="width: 80%;"> 
                <tr>
                    <td>
                        <p class="tac fs14"><b>买车担保金余款</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币{{$guarantee-$chengyijin}}元（买车担保金￥{{$guarantee}} — 已付诚意金￥{{$chengyijin}}）</p>
                    </td>
                    
                </tr>
                <tr>
                    <td>
                        <p class="tac fs14"><b>您的可用账户余额</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币13,000.00元<a href="#" class="juhuang">（查看）</a></p>
                    </td>
                </tr>
            </table>
            <div class="clear"></div>
            <div class="fs14">
                <span class="fl">请在本日内确认使用该余额支付买车担保金余款，如超时未确认平台将自动提交确认。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <p class="center">
                <a href="{{url('pay/deposit')}}/{{$order_num}}" class="btn btn-s-md btn-danger fs16">确认</a>
            </p>

            <table class="tbl fl" style="width: 80%;"> 
                <tr>
                    <td>
                        <p class="tac fs14"><b>买车担保金余款</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币12,523.00元（买车担保金￥13,022.00 — 已付诚意金￥499.00元）</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tac fs14"><b>您的可用账户余额</b></p>
                    </td>
                    <td>
                        <p class="tal fs14">人民币3,258.00元<a href="#" class="juhuang">（查看）</a></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="tac fs14"><b>待付金额</b></p>
                    </td>
                    <td>
                        <p class="tal fs14"><b>人民币9,265.00元</b>（已扣除可用账户余额）</p>
                    </td>
                </tr>
            </table>
            <div class="clear"></div>
            <div class="fs14">
                <p class="lh22"><span class="">一步到位<b>全额支付</b>，您可采用<b class="tdu">银行转账</b>方式，包括通过网上银行、手机银行或银行柜面向华车平台直接转账，您须在24小时内提交有效的银行</span></p>
                <span class="fl">汇款凭证。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="fs14">
                <p class="lh22"><span class=""><b class="tdu">线上支付</b>方式，因第三方支付的支付限额可能受限影响支付成功，可分多笔支付。您在当日付完第一笔后，支付时限自动延长到第三个自然日</span></p>
                <span class="fl">的24点，您可从容完成买车担保金余额支付。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <p class="fs14 lh22">请选择您的支付方式，并在上方时限内完成支付。超时未足额支付，根据华车平台规则将被判定违约：<b>订单终止，且诚意金赔偿售方</b>（已付的部分</p>
            <p class="tac">
                <a href="javascript:;" class="btn btn-s-md btn-danger sure">银行转账</a>
                <a href="javascript:;" class="btn btn-s-md btn-danger sure ml20">线上支付</a>
            </p>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>
@endsection
@section('js')
   
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"],function(){
            $(".jishi3").CountDown({
              startTime:'{{$starttime}}',
              endTime :'{{$endtime}}',
              timekeeping:'countdown',
              callback:function(){
                $("form[name='form1']").submit()
              }
          })
        });
    </script>
@endsection