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
                <li class="step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content">
                    <small>选择产品</small>
                    <i></i>
                    <small>付诚意金</small>
                    <i></i>
                    <small class="juhuang" class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>

    <div  class="container pos-rlt content" ms-controller="item">
        <form action="{{url('cart/confirmeidt')}}" method="post" name="form1">
        <input type="hidden" name="id" value="{{ $id}}">
        <input type="hidden" name="order_num" value="{{ $order_num}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="timeout" value="{{$timeout}}">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">华车平台<a href="#" class="juhuang tdu" style="padding-left:5px"><img src="{{asset('themes/images/item/jxb.gif')}}"></a>于<!--paytime-->收到您支付的诚意金人民币{{$chengyijin}}元，
            <?php if (empty($wenjian)): ?>
                您的购车之旅正式启航了！
            <?php else: ?>
                您同时提交的本人上牌所需特别文件:
                {{$wenjian}}。
            <?php endif ?>
            </p>
            

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
            <?php if (!empty($wenjian)): ?>
                <input type="hidden" name="texu" value="1">
                <hr class="dashed">
                <p class="fs14"><b>经销商对您的特别文件办理事项作了如下回复，请选择您要办理的内容（可多选）：</b></p>
                <?php foreach ($ziliao as $key => $value): ?>
                    <?php if ($value['ok']==1): ?>
                        <p class="psr fs14">
                        <input type="hidden" name="ziliao[{{$key}}][fee]" value="{{$value['fee']}}">
                        <input type="hidden" name="ziliao[{{$key}}][days]" value="{{$value['days']}}">
                        <div class="radio i-checks">
                            <label class="fs14">
                                <input type="checkbox" name="ziliao[{{$key}}][title]" value="{{$value['title']}}" checked="">
                                <i></i>
                                {{$value['title']}}： 可以办理        办理费用：人民币{{$value['fee']}}元        需要时间{{$value['days']}}天 
                            </label>
                        </div>
                        </p>
                    <?php else: ?>
                        <p class="psr fs14">
                            <i class="no"></i>
                            {{$value['title']}}： 恕无法办理
                        </p>
                    <?php endif ?>
                <?php endforeach ?>
                
                

                <p class="fs14">
                    <span class="fl">请在30分钟内确认，超过时间未确认将默认为终止订单，退诚意金（无歉意金补偿）操作。</span>
                    <div class="time fl">
                        <div class="jishi jishi2 jishi3">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                    </div>
                    <div class="clear"></div>
                </p>
                <div class="split tac btn-sp m-t-10">
                <input type="radio" name="wenjian" value="1" >
                    <a href="#myorder">就办这些，继续订单</a>
                <input type="radio" name="wenjian" value="0" checked="" >
                    <a href="#myorder">不买了，退诚意金</a>
                </div>
            <?php endif ?>
            

            <?php if (!empty($editcarinfo)): ?>
                <hr class="dashed">
                <input type="hidden" name="xiugai" value="1">
            <p class="fs14">经销商还对订单部分内容提出修改，根据平台规则，您已获得歉意金补偿人民币{{$qianyijin}}元（在您的可用账户里了）！该金额您可在华车平台继续购车时使用，如不买也可在《我的华车》中申请全额提现。</p>
            <table class="tbl fl" style="width: 80%;">
                <tr>
                    <th>
                        <p class="fs14">项目</p>                    </th>
                    <th>
                        <p class="fs14">订单约定条件</p>                    </th>
                    <th>
                        <p class="fs14">经销商提议修改为</p>                    </th>
                </tr>
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
                      <td><p class="tac fs14">{{$bj_producetime}}</p></td>
                      <td><p class="tac fs14">{{$editcarinfo['chuchang']}}</p></td>
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
                <!--选装件-->
                <?php if(count($xzj)>0){?>
                <?php foreach ($xzj as $key => $value): ?>
                    <?php if($value['old_num']==$value['num']) continue; ?>
                    <tr>
                        <td><p class="tac fs14">{{$value['xzj_title']}}<br/>（已装原厂选装精品）</p></td>
                      <td><p class="tac fs14">{{$value['old_num']}}</p></td>
                        <td><p class="tac fs14">{{$value['num']}}</p></td>
                    </tr>
                <?php endforeach ?>
                <?php }?>
                <!--礼品-->
                <?php if(count($zengpin)>0){?>
                <?php foreach ($zengpin as $key => $value): ?>
                    <?php if($value['old_num']==$value['num']) continue; ?>
                    <tr>
                        <td><p class="tac fs14">{{$value['title']}}<br/>（免费礼品和服务）</p></td>
                      <td><p class="tac fs14">{{$value['old_num']}}</p></td>
                        <td><p class="tac fs14">{{$value['num']}}</p></td>
                    </tr>
                <?php endforeach ?>
				<?php } ?>
            </table>
            <div class="clear"></div>
             
            <p class="fs14">请您选择：</p>
            <table class="tbl2">
              <tr>
                <td valign="top" width="20" class="nopadding">
                    <input type="radio" class="jiaocheinput" name="jiaoche" value="1" id="">
                </td>
                <td valign="top" class="nopadding" width="80"><p class="fs14"><b>继续订单</b></p></td>
                <td valign="top" class="nopadding"><p class="fs14"> 您同意上述修改条件并继续订单，歉意金补偿人民币{{$qianyijin}}.00元将可抵扣买车担保金余款，您只须再支付人民币{{$guarantee-$chengyijin-$qianyijin}}元（买车担保金 ￥{{$guarantee}} — 已付诚意金￥{{$chengyijin}} — 歉意金补偿￥{{$qianyijin}}）即可完成买车担保金支付。</p></td>
              </tr>
              <tr>
                <td valign="top" width="20" class="nopadding">
                    <input type="radio" class="jiaocheinput" name="jiaoche" value="0" id="">
                </td>
                <td valign="top" class="nopadding" width="80"><p class="fs14"><b>放弃订单 </b></p></td>
                <td valign="top" class="nopadding"><p class="fs14"> 您不接受上述修改，很遗憾您将放弃此次订单，但您的诚意金人民币{{$chengyijin}}元将毫发无损退回您的可用账户中，供您下回购车使用（不买可退款，按银行规定退至本人银行卡）。</p></td>
              </tr>
              
            </table>

            <p class="fs14">如超时将默认为放弃订单。</p>
            
            <?php endif ?>
            
            <p class="center">
            <input type="submit" value="提交" class="btn btn-s-md btn-danger fs16">
                
            </p>

            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
        </form>
    </div>
@endsection
@section('js')

    <script type="text/javascript">
        seajs.use(["module/custom/custom_order","module/item/item-wait", "module/common/common", "bt"],function(){
            $(".jishi3").CountDown({
              startTime:'{{$starttime}}',
              endTime :'{{$endtime}}',
              timekeeping:'countdown',
              callback:function(){
                $("input[name=timeout]").val(1);
                $("form[name='form1']").submit()
              }
          })
        });
    </script>
@endsection