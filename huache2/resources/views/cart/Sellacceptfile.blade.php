@extends('HomeV2._layout.base2')
@section('css')
    <link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/common.css')}}" rel="stylesheet" />
    <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('_layout.nav')
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

    <div class="container pos-rlt content" ms-controller="item">
       @include('cart.branch.shellfragment')
              @if($result['car']['editCarModel']=="Y")
            <?php $baojia = $order->orderBaojia;?>
            <p class="fs14">前方遇到了一点小风浪，我们一起去看看哦！</p>
            <table class="tbl fl" style="width: 80%;">
                <tr>
                    <th>
                        <p class="fs14">项目</p>
                    </th>
                    <th>
                        <p class="fs14">订单约定条件</p>
                    </th>
                    <th>
                        <p class="fs14">经销商提议修改为</p>
                    </th>
                </tr>
                 @if(count($result['car']['editcarinfo'])>0)
                            @foreach($result['car']['editcarinfo'] as $key=>$info)
                            @if($key == 'body_color')
                            <tr>
                                <td><p class="tac fs14">车身颜色</p></td>
                                <td><p class="tac fs14">{{$baojia['bj_body_color']}}</p></td>
                                <td><p class="tac fs14">{{$info}}</p></td>
                            </tr>
                            @endif
                            @if($key == 'interior_color')
                            <tr>
                                <td><p class="tac fs14">内饰颜色</p></td>
                                <td><p class="tac fs14">{{$result['car_info']['nside_color']}}</p></td>
                                <td><p class="tac fs14">{{$info}}</p></td>
                            </tr>
                            @endif
                            @if($key == 'year_month')
                            <tr>
                                <td><p class="tac fs14">出厂日期</p></td>
                                <td><p class="tac fs14">
                                 @if(!$baojia['bj_producetime'])
                                        (不早于){{date("Y年m月",strtotime($baojia['bj_producetime']))}}
                                @else
                                        (不早于){{date("Y年m月", time())}}
                                @endif</p></td>
                                <td><p class="tac fs14">{{$info}}</p></td>
                            </tr>
                            @endif
                            @if($key == 'mileage')
                            <tr>
                                <td><p class="tac fs14">行驶里程</p></td>
                                <td><p class="tac fs14">（@if(!$baojia['bj_is_xianche'])
                                        (不高于）{{$baojia['bj_licheng']}}公里
                                        @else
                                        (不高于)20公里
                                        @endif</p></td>
                                <td><p class="tac fs14">{{$info}}公里</p></td>
                            </tr>
                            @endif
                            @if($key == 'paifang')
                            <tr>
                                <td><p class="tac fs14">排放标准</p></td>
                                <td><p class="tac fs14">@if(!$result['car_info']['paifang'])
                                            国五
                                            @else
                                            国四
                                            @endif</p></td>
                                <td><p class="tac fs14">{{$info}}</p></td>
                            </tr>
                            @endif
                            @if(!$baojia['bj_is_xianche'])
                            @if($key == 'cycle')
                            <tr>
                                <td><p class="tac fs14">交车周期</p></td>
                                <td><p class="tac fs14">{{$baojia['bj_jc_period']}}个月</p></td>
                                <td><p class="tac fs14">{{$info}}个月</p></td>
                            </tr>
                            @endif
                            @endif

                            @endforeach
                            @endif
                            @if(count($result['car']['editxzj'])>0)
                            @foreach($result['car']['editxzj'] as $xzj)
                            @if ($xzj['num'] != $xzj['old_num'])
                            <tr>
                                <td><p class="tac fs14">{{$xzj['xzj_title']}}（已装原厂选装精品） </p></td>
                                <td><p class="tac fs14">数量{{$xzj['num']}}</p></td>
                                <td><p class="tac fs14">数量{{$xzj['old_num']}}</p></td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                            @if(count($result['car']['editzengpin'])>0)
                            @foreach($result['car']['editzengpin'] as $zengpin)
                            @if ($zengpin['num'] != $zengpin['old_num'])
                            <tr>
                                <td><p class="tac fs14">{{$zengpin['zp_title']}}（免费礼品或服务）</p></td>
                                <td><p class="tac fs14">数量{{$zengpin['num']}}</p></td>
                                <td><p class="tac fs14">数量{{$zengpin['old_num']}}</p></td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </table>
                    </div>
                    <br><br><br>
              @endif

            <div class="clear"></div>
            <hr class="dashed">
            <p class="fs14">根据华车平台规则，经销商方面提出修改订单，您已获得歉意金补偿人民币{{config('orders.order_earnest_price')}}元(在您账户的可用余额里了）。如订单继续执行，该金额可用于支买车担保金余款，如不买车可在【我的华车】中办理全额提现。</p>
            <p class="fs14">请您选择：</p>
            <div class="wait-order-status" ms-controller="orderStatus">
                <table class="tbl2">
                <form action="{{route('cart.status',['type'=>'subscribe','order_sn'=>$order['id']])}}" method="post" id="sellback">
                         {!!csrf_field()!!}
                  <tr>
                    <td valign="top" width="20" class="nopadding">
                        <input ms-duplex-boolean="continueOrder" value="true" type="radio" class="jiaocheinput" name="status" id="">
                    </td>
                    <td valign="top" class="nopadding" width="80"><p class="fs14"><b>继续订单</b></p></td>
                    <td valign="top" class="nopadding"><p class="fs14"> 您同意上述修改条件并继续订单，歉意金补偿人民币{{config('orders.order_earnest_price')}}元将可抵扣买车担保金余款，您只须再支付人民币12,024.00元（买车担保金 ￥13,022.00 — 已付诚意金￥{{config('orders.order_earnest_price')}} — 歉意金补偿￥499.00）即可完成买车担保金支付。</p></td>
                  </tr>
                  </form>
                  <form action="{{route('cart.end')}}" method="post" id="stops">
                         {!!csrf_field()!!}

                  <tr>
                  <input type="hidden" name="id" value="{{$order['id']}}">
                    <td valign="top" width="20" class="nopadding">
                        <input ms-duplex-boolean="continueOrder" value="false" type="radio" class="jiaocheinput" name="jiaoche" id="">
                    </td>
                    </form>
                    <td valign="top" class="nopadding" width="80"><p class="fs14"><b>放弃订单 </b></p></td>
                    <td valign="top" class="nopadding"><p class="fs14">您不接受上述修改，很遗憾您将放弃此次订单，但您的诚意金人民币{{config('orders.order_earnest_price')}}元将毫发无损退回您的可用余额，供您下回购车使用（不买车可在【我的华车】中办理全额提现）。</p></td>
                  </tr>

                </table>

                <p class="fs14">
                    <span>温馨提示：请在下方剩余时间内选择提交，超过时间未选将默认放弃订单操作。</span>
                    <p class="tac red hide" id="showhide">请选择是否继续订单！</p>
                    <br><br><br>
                    <div class="time">
                        <div class="jishi jishi-long countdown">
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
                    <a ms-on-click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16">提交</a>
                </p>

                <div id="stopWin" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac succeed error constraint">
                               <span class="tip-tag bp0"></span>
                               <span class="tip-text mt-10">确定放弃订单吗？</span>
                               <div class="clear"></div>
                               <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" ms-on-click="stopOrder" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <div id="sendWin" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac">
                               <br>
                               <span class="tip-text fs14 text-left inline-block">确定同意接受修改条件并继续订单吗？</span>
                               <div class="clear"></div>
                               <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" ms-on-click="doSend" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

            </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
    <script src="/js/sea.js"></script>
    <script src="/js/config.js"></script>
    <script type="text/javascript">
        seajs.use(["module/item/item-wait","module/item/item-wait-order-status-select", "module/common/common", "bt"],function(a,b,c){
        	a.init('{{date('Y-m-d H:i:s',time())}}','{{$order->rockon_time}}',function(){
        		//设置回调
        	})
        })
    </script>
@endsection

