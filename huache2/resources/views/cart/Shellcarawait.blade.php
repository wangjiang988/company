@extends('HomeV2._layout.base2')
@section('css')
    <link href="/themes/item-fix.css" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')

    <div class="container m-t-86 pos-rlt">
        <div class="step psr">
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
                <div class="m-content m-left-187">
                    <small>等待支付</small>
                    <i></i>
                    <small class="juhuang">等待预约</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            @if($order->order_state == config('orders.order_doposit_admin'))
            <h1 class="ti psr">
            十分抱歉，售方因遇到不可抗力（原因见下方说明）已向华车申请延后发出交车邀请，敬请谅解！
            @if(($order->orderAttr->non_xzj_list) || judgeXzj($order->bj_id, 0, 1))
            <a target="_blank" href="{{route('buy.show',['id'=>$order->id])}}"> <img class="adv-img" src="/themes/images/adv.png" alt=""></a>
            @endif
            </h1>
            @else
            <h1 class="ti psr">买车担保金已支付成功，下个步骤将由售方向您发出交车邀请，请静候佳音
            @if(($order->orderAttr->non_xzj_list) || judgeXzj($order->bj_id, 0, 1))
            <a target="_blank" href="{{route('buy.show',['id'=>$order->id])}}"><img class="adv-img" src="/themes/images/adv.png" alt=""></a>
            @endif
            </h1>
            @endif
            <div class="clear m-t-10"></div>
            <table class="nobordertbl wp100">
                <tr>
                    <td width="50%" class="fs14 weight">订单号：{{$order->order_sn}}</td>
                    <td width="50%" class="fs14">
                        <span class="weight">订单时间：</span>{{$order->created_at}}
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>

            <ul class="pdi-order-ul border ">
            <?php $car = explode('&gt;',$order->gc_name); ?>
               <p class="fs14 ml10">
                   <span>{{$car[0]}}</span>
                   <span class="ml5">&gt;</span>
                   <span class="ml5">{{$car[1]}}</span>
                   <span class="ml5">&gt;</span>
                   <span class="ml5">{{$car[2]}}</span>
                   <span class="ml30">{{$order->orderattr->cart_color}}</span>
               </p>
            </ul>
            <div class="clear m-t-10"></div>
            <p class="tac"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="tdu juhuang">查看订单总详情</a></p>
            <div class="clear m-t-10"></div>

            <!--车价-->
            <div class="box">
                <div class="title">
                    <label ms-click="toggleContent">一、车价</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def">
                    <p>您的华车车价：人民币<span class="juhuang">{{$order->hwache_price}}</span>元({{num_to_rmb($order->hwache_price)}})</p>
                    <table>
                        <tr>
                            <td valign="top">其中包括：</td>
                            <td>
                                <p>1. <span class="juhuang">车辆开票价格</span> （您须在经销商处全额支付）：人民币<span class="juhuang">{{number_format($order->orderPrice->car_price,2)}}</span>元</p>
                                <p>2. <span class="juhuang">华车服务费</span>（订单完成后从买车担保金中扣除）：人民币<span class="juhuang">{{number_format($order->orderPrice->hwache_service_price,2)}}元</span></p>
                            </td>
                        </tr>
                    </table>
                    <div class="psr flow-chart">
                        <img src="/themes/images/flow-chart.png" alt="">
                        <span class="hwache-price">（￥{{$order->hwache_price}}）</span>
                        <span class="service-price">（￥{{number_format($order->orderPrice->hwache_service_price,2)}}
                        ）</span>
                        <span class="invoice-price">（￥{{number_format($order->orderPrice->car_price,2)}}）</span>
                        <span class="assure-price">（￥{{number_format($order->sponsion_price,2)}}）</span>
                        <span class="invoice-price-next">（￥{{number_format($order->orderPrice->car_price,2)}}）</span>
                        <span class="service-price-next">（￥{{number_format($order->orderPrice->hwache_service_price,2)}}）</span>
                        <span class="hwache-price-next">（￥{{$order->hwache_price}}）</span>
                        <span class="service-price-last">（￥{{number_format($order->orderPrice->hwache_service_price,2)}}）</span>
                        <span class="assure-service-price">（￥{{number_format($order->sponsion_price-$order->orderPrice->hwache_service_price,2)}}）</span>
                        <span class="assure-price-last">（￥{{number_format($order->sponsion_price,2)}}）</span>
                    </div>
                </div>
            </div>

            <!--交车时限与地点-->
            <div class="box mt20">
                <div class="title">
                    <label ms-click="toggleContent">二、交车时限与地点</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def">
                    <table class="tbl text-center">
                        <tr>
                            <td width="200"><span class="fs16 weight">交车地点范围约定</span></td>
                            <td>
    <?php $result =$order->orderScope->toArray();
    $data = [
            $result['province1_name'] . $result['area1_name'],
            $result['province2_name'] . $result['area2_name'],
            $result['province3_name'] . $result['area3_name']
        ];
    ?>                       {{implode('、',$data)}}
                            </td>
                        </tr>
                        <tr>
                            <td width="200"><span class="fs16 weight">经销商</span></td>
                            <td><span class="juhuang">{{$order->orderDealer->d_name}}</span></td>
                        </tr>
                        <tr>
                            <td width="200"><span class="fs16 weight">交车地点</span></td>
                            <td>
                                <div class="psr">
                                    <span class="juhuang">{{$order->orderDealer->d_jc_place}}</span>
                                    <a href="http://gaode.com/search?query={{urlencode($order->orderDealer->d_jc_place)}}"  target="_blank" class="psa blue map-link">查看地图</a>
                                </div>
                            </td>
                        </tr>
                   @if($order->order_state == config('orders.order_doposit_admin'))
                        <tr>
                            <td width="200"><span class="fs16 weight">交车时限</span></td>
                            <td><span class="juhuang">{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict))}}24：00：00</span></td>
                        </tr>
                        <tr>
                            <td width="200"><span class="fs16 weight">原交车邀请发出时限</span></td>
                        <td><span class="">
                          @if($order->is_xianche)
                          {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-8*24*3600)}}24：00：00
                          @else
                          {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-7*24*3600)}}24：00：00
                          @endif
                        </span>
                        </td>
                        </tr>
                        </table>
                        <p class="fs14"> <span class="fs16 blue">延后发出交车邀请原因：</span>{{$order->orderDate->reason}}</p>
                    <p class="fs16 blue">特别说明</p>
                    <hr class="dashed nomargin" />
                    <div class="mt10 ml20">
                        <p> 1.因不可抗力造成售方延后发送交车邀请，根据平台规则，售方该违约责任将可豁免。</p>
                        <p> 2.售方正全力准备交付给您的车辆，争取尽快给您发出交车邀请、并在约定交车时限前向您交车。</p>
                        <p> 3.万一发生无法交车的结果，售方将与您协商补偿事宜，华车将协助保障您获得有关权益。</p>
                    </div>
                @else
                </table>
                    @if($order->orderBaojia->bj_is_xianche && $order->orderAttr->new_file_days==0)
                    <p class="ti">您订购的是现车，平台规定售方应在7个自然日内向您发出交车邀请（<span class="juhuang">交车邀请发出时限：{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-8*24*3600)}}24：00：00</span>），15个自然日内可向您移交符合订单要求的汽车产品（<span class="juhuang">交车时限：{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict))}}24：00：00</span>）。具体提车日期的商定将尊重您的意见。</p>
                    @elseif($order->orderAttr->new_file_days)
                    <p class="ti">因售方为您办理特别文件需额外花费{{$order->orderAttr->new_file_days}}个自然日，售方应在<span class="juhuang">{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict))}}24：00：00（交车时限）</span>前，向您移交符合订单要求的汽车产品，并且在上述交车时限前，至少提前7个自然日，也就是<span class="juhuang">
                  @if($order->is_xianche)
                  {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-8*24*3600)}}24：00：00
                  @else
                  {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-7*24*3600)}}24：00：00
                  @endif
                    （交车邀请发出时限）</span>前，向您发出交车邀请。具体提车日期的商定将尊重您的意见。但若由于您配合延误所造成的交期延后，则不在平台的保障范围内。</p>
                    @else
                    <p class="ti">您的订单约定交车周期是{{$order->orderBaojia->bj_jc_period}}个月，售方应在<span class="juhuang">{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict))}}24：00：00（交车时限）</span>前，向您移交符合订单要求的汽车产品，并且在上述交车时限前，至少提前7个自然日，也就是<span class="juhuang">{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-7*24*3600)}}24：00：00（交车邀请发出时限）</span>前，向您发出交车邀请。具体提车日期的商定将尊重您的意见。</p>
                    @endif
                    <p class="fs16 blue">特别保障</p>
                    <div class="mt10">
                        <p class="ti">1.如果售方超时未发交车邀请且无不可抗力原因，则属售方违约：订单终止，加信宝退还您所有已付买车担保金、并向您提供下列补额外偿：歉意金人民币{{config('orders.order_earnest_price')}}元 +  买车担保金人民币{{number_format($order->sponsion_price,2)}}元冻结期间的利息（日利率万分之二） </p>
                        <p class="ti">2.如果等待期间售方向您提出各种订单修改，您在自动获得上述歉意金补偿和买车担保金利息补偿的基础上，对修改方案满意的，可选择继续订单，执行修改方案；如果不满意，可选择终止订单，无条件退还所有已付买车担保金。</p>
                    </div>
                    @endif
                </div>
            </div>

            <!--上牌-->
            @if($order->orderAttr->is_arrangement)
            <div class="box mt20">
                <div class="title">
                    <label ms-click="toggleContent">三、上牌</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def">
                    <table class="tbl">
                        <tr>
                            <td width="200" class="tac"><span class="fs16 weight">上牌服务约定</span></td>
                            <td>
                        @if($order->orderAttr->is_arrangement && $order->orderAttr->shangpai_status ==2)
                                <p>接受安排（<span class="juhuang">指定上牌</span>）</p>
                                <p>您须委托经销商代办上牌手续，且向经销商支付下方标准的服务费。</p>
                        @elseif($order->orderAttr->is_arrangement && $order->orderAttr->shangpai_status ==3)
                                <p>接受安排（<span class="juhuang">自选上牌</span>）</p>
                                <p>您在收到交车通知后选择：或者由您亲自办理上牌手续，或者委托售方代办，并向其支付下方标准的服务费。</p>
                        @elseif($order->orderAttr->is_arrangement && $order->orderAttr->shangpai_status ==1)
                         <p>接受安排（<span class="juhuang">本人上牌</span>）</p>
                         <p>您须亲自办理上牌手续，经销商不代办。</p>
                        @endif
                            </td>
                        </tr>
                        @if($order->orderAttr->shangpai_status != 1)
                        <tr>
                            <td width="200" class="tac"><span class="fs16 weight">上牌服务费约定</span></td>
                            <td><span class="">（不高于）人民币￥{{number_format($order->shangpai_price,2)}}元</span></td>
                        </tr>
                        @endif

                    </table>


                </div>
            </div>
            @endif

               @if($order->orderBaojia->bj_linpai == 1 && $order->orderAttr->shangpai_status ==1)
                <div class="box mt20">
                    <div class="title">
                        <label ms-click="toggleContent">上临时牌照</label>
                        <em></em>
                        <code class="besure"></code>
                    </div>
                    <div class="box-inner  box-inner-def">
                        <table class="tbl">
                            <tr>
                                <td width="249" class="tac"><span class="fs16 weight">上临时牌照约定</span></td>
                                <td>
                                    <p>指定服务</p>
                                    <p>根据由您本人亲自上牌的，须在提车时委托售方代办临时移动牌照。</p>
                                </td>
                            </tr>
                            <tr>
                                <td width="" class="tac"><span class="fs16 weight">本次是否由经销商代办临牌</span></td>
                                <td>是</td>
                            </tr>
                            <tr>
                                <td width="" class="tac"><span class="fs16 weight">上牌服务费约定</span></td>
                            <td>（不高于）人民币{{number_format($order->orderPrice->agent_temp_numberplate_price,2)}}元</td>
                            </tr>
                        </table>

                    </div>
                </div>
                @endif

            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>

        </div>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-wait","/js/module/common/common"],function(v,u,c){

        });
    </script>
@endsection
