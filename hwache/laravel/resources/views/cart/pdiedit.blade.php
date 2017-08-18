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
                <li>付担保金<i></i></li>
                <li class="step-cur">预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content pdi">
                    <small class="juhuang">开始预约</small>
                    <i></i>
                    <small>反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">因经销商方面在交车准备时发生一些意外情况，希望修改部分订单内容，请您慎重选择处理方式，华车平台将尊重您的选择并充分保障您的权益！</p>


            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：{{$order_num}}</td>
                    <td width="50%">
                        <div class="psr fs14">
                          订单时间：{{ddate($created_at)}}
                          <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                             <b>更多</b>
                          </span>
                          <p class="tm tm-long" style="display: none;">
                            @if(count($cart_log)>0)     @foreach($cart_log as $k =>$v )
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
                <li class="pdi-color"><p class="fs14">{{$carmodelInfo['body_color']}}</p></li>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang tdu ">查看订单总详情</a></p>

            <hr class="dashed">
            <p class="fs14">经销商提议修改订单如下内容：</p>
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
                @foreach($modifyLogCarInfo as $k => $v)
                @if($v != $carInfoDetail[$k])
                <tr>
                    <td>
                        <p class="tac fs14">{{$carInfoDetailLang[$k]}}</p>
                    </td>
                    <td>
                        <p class="tac fs14">{{$carInfoDetail[$k]}}</p>
                    </td>
                    <td>
                        <p class="tac fs14">{{$v}}</p>
                    </td>
                </tr>
                @endif
                @endforeach
                @if(count($modifyLogXzj)>0)
                @foreach($modifyLogXzj as $k => $v)
                @if($v['num'] != $v['old_num'])
                <tr>
                    <td>
                        <p class="tac fs14">{{$v['xzj_title']}}</p>
                        <p class="tac fs14">（已装原厂选装精品）</p>
                    </td>
                    <td>
                        <p class="tac fs14">数量:{{$v['old_num']}}</p>
                    </td>
                    <td>
                        <p class="tac fs14">数量:{{$v['num']}}</p>
                    </td>
                </tr>
                @endif
                @endforeach
                @endif
                
                @if(count($modifyLogZengpin)>0)
                @foreach($modifyLogZengpin as $k => $v)
                @if($v['num'] != $v['old_num'])
                <tr>
                    <td>
                        <p class="tac fs14">{{$v['title']}}</p>
                        <p class="tac fs14">（免费礼品和服务）</p>
                    </td>
                    <td>
                        <p class="tac fs14">数量:{{$v['old_num']}}</p>
                    </td>
                    <td>
                        <p class="tac fs14">数量:{{$v['num']}}</p>
                    </td>
                </tr>
				@endif
                @endforeach
                @endif

            </table>
            <div class="clear"></div>
            <hr class="dashed">
            <p class="fs14">根据华车平台规则，在您全额支付客户买车担保金后经销商方面提出修改订单，您已获得下列补偿（订单结束后统一结算，退款提现手续按银行规定办理！）:</p>
            <p class="fs14">1.获得歉意金2补偿：+人民币499.00元；</p>
            <p class="fs14">2.获得客户买车担保金2利息补偿：+人民币101.00元</p>
            <p class="fs14">（2015-10-25~2015-11-3，8天，63125.00 X 8 X 0.02%=101.00元）</p>
            <form action="{{url('cart/savepdiedit')}}" method="post" name="form1">
            <input type="hidden" name="id" value="{{ $id}}">
            <input type="hidden" name="order_num" value="{{ $order_num}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="timeout" value="{{ $timeout}}">
            <p class="fs14">请您选择：</p>
            <table class="tbl2">
              <tr>
                <td valign="top" width="20" class="nopadding">
                    <input type="radio" class="jiaocheinput" name="jiaoche" id="" value="1">
                </td>
                <td valign="top" class="nopadding" width="80"><p class="fs14"><b>继续订单</b></p></td>
                <td valign="top" class="nopadding"><p class="fs14">您同意上述修改条件并继续订单</p></td>
              </tr>
              <tr>
                <td valign="top" width="20" class="nopadding">
                    <input type="radio" class="jiaocheinput" name="jiaoche" id="" value="2" checked="checked">
                </td>
                <td valign="top" class="nopadding" width="80"><p class="fs14"><b>放弃订单 </b></p></td>
                <td valign="top" class="nopadding"><p class="fs14">您不接受上述修改，很遗憾您将放弃此次订单，但客户买车担保金人民币63，125.00元将毫发无损退回您的可用账户中，供您下                 回购车使用（不买可退款提现，按银行规定办理！）</p></td>
              </tr>
              
            </table>

            <p class="fs14">
                <span class="fl">请在24小时内选择，超过时间未选，平台将默认按照放弃订单操作。</span>
                <div class="time fl">
                    <div class="jishi jishi2" id="countdown">
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
            </p>
            <p class="center">
                <input type="submit" value="提交" class="btn btn-s-md btn-danger fs16">
               
            </p>

            </form>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>
@endsection
@section('js')
   
    <script type="text/javascript">
        seajs.use(["module/item/item-wait", "module/common/common", "bt"],function(){
            $("#countdown").CountDown({
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