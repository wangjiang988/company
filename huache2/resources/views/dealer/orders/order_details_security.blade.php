@extends('_layout.orders.base_order')
@section('title', '等待买车担保金到位-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 pos-rlt " ms-controller="custom">
       <div class="cus-step">
           <div class="line stp-2"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><span>3</span><i>3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-2">
                    <small class="juhuang">等待到账</small>
                    <i></i>
                    <small>预约准备</small>
                </div>
            </div>
       </div>


        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16 weight">订单信息</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                      <table class="tbl2" width="100%">
                                        <tr>
                                            <td  width="50%"><p><b>订单号：</b>{{$order['order_sn']}}</p></td>
                                            <td  width="50%">
                                                <b>订单时间：</b>{{$order['created_at']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>订单类别：</b>
                                              @if($baojia['bj_is_xianche'])
                                            现车订单
                                            @else
                                            非现车订单
                                            @endif
                                            </p><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                              <p>
                                                 <b>车型：</b>
                                                 <span>{{$order['gc_name']}}</span>
                                              </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p><b>整车型号： </b>{{$baojia['vehicle_model']}}</p></td>
                                            <td><p><b>厂商指导价：</b>￥{{number_format($car_info['zhidaojia'],2)}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车辆类别：</b>全新中规车整车</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>数量：</b>1台</p></td>
                                            <td>
                                            @if($baojia['bj_dealer_internal_id'])
                                            <p><b>内部车辆编号：</b>{{$baojia['bj_dealer_internal_id']}}</p>
                                            @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <?php $dealer = $order->orderDealer ?>
                                            <td colspan="2"><p><b>经销商：</b>{{$dealer['d_name']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>营业地点：</b>{{$dealer['d_yy_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>交车地点：</b>{{$dealer['d_jc_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>归属地区：</b>{{$dealer['d_areainfo']}}</p></td>

                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车辆开票价格：</b>￥{{number_format($baojia['car_price'],2)}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>客户买车定金：</b>￥{{number_format($baojia['client_hand_price'],2)}}</p></td>
                                        </tr>

                                         <tr>
                                            <td><p><b>我的服务费：</b>￥{{number_format($baojia['agent_service_price'],2)}}</p></td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <div class="times">
                                        <p class="status tac  m-t-10"><b class="fs14">订单状态：等待足额买车担保金进入加信宝</b></p>
                                        <div class="jd">
                                            <div class="jd-wapper">
                                                <span>90%</span>
                                                <label for="" class="line">
                                                  <label for="" class="line-cur" style="width: 90%"><i></i></label>
                                                </label>
                                                <p class="tac m-t-10"><a href="{{route('dealer.order.detail',['order_id'=>$order['id']])}}" class="juhuang tdu">查看订单总详情</a></p>
                                            </div>
                                        </div>

                                        <hr class="dashed mt69">
                                        <p class="pl20 pt"><b>客户承诺上牌地区： </b>
                                        <?php $shangpai = getAreaName($order['shangpai_area'])?>
                                        @if($shangpai['parent_name'] === $shangpai['name'])
                                        {{$shangpai['name']}} </p>
                                        @else
                                        {{$shangpai['parent_name'].$shangpai['name']}} </p>
                                        @endif
                                    </div>
                                </td>

                            </tr>

                        </tbody>
                    </table>

                     <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class="weight fs16">商品说明</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td width="33%"><p><b>基本配置：</b><a href="/img/{{base64_encode(env('UPLOAD_URL').'/'.$baojia['detail_img'])}}" class="blue tdu">查看</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>
                                             @if(!$car_info['guobie'])
                                            进口
                                            @else
                                            国产
                                            @endif
                                            </p></td>
                                            <td width="33%"><p><b>座位数：</b>5</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>
                                @if(isset($car['editcarinfo']['body_color']))
                                           {{$car['editcarinfo']['body_color']}}
                                            @else
                                            {{$baojia['bj_body_color']}}
                                            @endif
                                            </p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>
                                @if(isset($car['editcarinfo']['interior_color']))
                                        {{$car['editcarinfo']['interior_color']}}
                                        @else
                                            {{$car_info['nside_color']}}
                                        @endif
                                            </p></td>
                                            <td width="33%"><p><b>排放标准：</b>
                                @if(isset($car['editcarinfo']['paifang']))
                                         {{$car['editcarinfo']['paifang']}}
                                        @else
                                           @if(!$car_info['paifang'])
                                            国五
                                            @else
                                            国四
                                            @endif
                                        @endif
                                            </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>出厂年月：</b>
                                @if(isset($car['editcarinfo']['year_month']))
                                    {{$car['editcarinfo']['year_month']}}
                                    @else
                                        @if($baojia['bj_is_xianche'])
                                        (不早于)<span>{{date("Y年m月",strtotime($baojia['bj_producetime']))}}</span>
                                        @else
                                        (不早于)<span>{{date("Y年m月", time())}}</span>
                                        @endif
                                    @endif
                                        </p></td>
                                                    <td width="33%"><p><b>行驶里程：</b>
                                @if(isset($car['editcarinfo']['mileage']))
                                    {{$car['editcarinfo']['mileage']}}
                                    @else
                                        @if($baojia['bj_is_xianche'])
                                        (不高于）<span>{{$baojia['bj_licheng']}}公里</span>
                                        @else
                                        (不高于)<span>20公里</span>
                                        @endif
                                    @endif
                                            </p></td>
                                            @if(!$baojia['bj_is_xianche'])
                                            <td width="33%"><p><b>交车周期：</b>
                                @if(isset($car['editcarinfo']['cycle']))
                                    {{$car['editcarinfo']['cycle']}}
                                    @else
                                    {{$baojia['bj_jc_period']}}
                                    @endif
                                    个月</p></td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                    @if($baojia['bj_is_xianche'] && count($originals) && isset($originals['rpo']))
                        <p>已装原厂选装精品：</p>
                        <table class="tbl tbl3">
                            <tr>
                               <th><p class="fs15">名称</p></th>
                               <th><p class="fs15">型号/说明</p></th>
                               <th><p class="fs15">厂家指导价</p></th>
                               <th><p class="fs15">数量</p></th>
                               <th><p class="fs15">附加价值</p></th>
                           </tr>
                         @if(count($car['editxzj'])>0 && $car['editxzj'])
                            @foreach($car['editxzj'] as $xzj)
                            @if($xzj['num'] != 0)
                            <tr>
                               <td class="tac">{{$xzj['xzj_title']}}</td>
                               <td class="tal">{{$xzj['xzj_model']}}</td>
                               <td class="tac">￥{{$xzj['xzj_guide_price']}}</td>
                               <td class="tac">{{$xzj['num']}}</td>
                               <td class="tac">￥{{number_format($xzj['xzj_guide_price']*$xzj['num'],2)}}</td>
                           </tr>
                           @endif
                           @endforeach
                        @else
                           @foreach($originals['rpo'] as $rpo)
                           @if($rpo['num'] != 0)
                           <tr>
                               <td class="tac">{{$rpo['xzj_title']}}</td>
                               <td class="tal">{{$rpo['xzj_model']}}</td>
                               <td class="tac">￥{{$rpo['xzj_guide_price']}}</td>
                               <td class="tac">{{$rpo['num']}}</td>
                               <td class="tac">￥{{number_format($rpo['xzj_guide_price']*$rpo['num'],2)}}</td>
                           </tr>
                           @endif
                           @endforeach
                        @endif
                        </table>
                        <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang">￥{{number_format($originals['rpo_sum'],2)}}</span></h2>
                        </td>
                         @endif
                        </tr>
                </table>
                <hr>
                 @if(count($order->orderServer))
                <p>免费礼品或服务</p>
                <div class="fl wp70">
                    <table class="tbl">
                        <tr>
                            <td><p class="tac fs14"><b>名称</b></p></td>
                            <td><p class="tac fs14"><b>数量</b></p></td>
                            <td><p class="tac fs14"><b>状态</b></p></td>
                        </tr>
                    @if(count($car['editzengpin'])>0 && $car['editzengpin'])
                        @foreach($car['editzengpin'] as $zengpin)
                        @if($zengpin['num'] != 0)
                        <tr>
                            <td><p class="tac fs14">{{$zengpin['zp_title']}}</p></td>
                            <td><p class="tac fs14">{{$zengpin['num']}}</p></td>
                            <td><p class="tac fs14">
                            @if($zengpin['zp_status'])
                            已安装
                            @else
                            /
                            @endif
                            </p>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    @else
                        @foreach($order->orderServer as $orderserve)
                        <tr>
                            <td><p class="tac fs14">{{$orderserve->zp_title}}</p></td>
                            <td><p class="tac fs14">{{$orderserve->num}}</p></td>
                            <td><p class="tac fs14">
                            @if($orderserve->is_install)
                            已安装
                            @else
                            /
                            @endif
                            </p></td>
                        </tr>
                        @endforeach
                    @endif
                    </table>
                    @endif
                </div>
                                    <div class="clear"></div>

                                    <hr>
                                    <p><b>客户投保：</b>
                                    @if($baojia['bj_baoxian'])
                                    是
                                    @else
                                    待定
                                    @endif
                                    </p>  <br>
                                    <p><b>代办上牌：</b>
                            <?php $shangpai_status=$order->orderAttr['shangpai_status']?>
                                    @if($shangpai_status == 2)
                                    是（￥{{number_format($order['shangpai_price'],2)}}）
                                    @elseif($shangpai_status == 1)
                                    否
                                    @elseif($shangpai_status == 3)
                                    待定
                                    @endif
                                    </p>  <br>
                                    <p><b>代办临牌：</b>
                                    @if($baojia['bj_linpai'] == 1)
                                    是（￥{{number_format($baojia['agent_temp_numberplate_price'],2)}}）
                                    @else
                                    待定
                                    @endif
                                  </p>
                                  @if($shangpai_status == 1)
                                  <br>
                                    <p><b>客户本人上牌违约赔偿（约定）：</b>￥{{number_format($baojia['client_license_compensate'],2)}}</p><br>
                                   @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="ml20">
                    @if($car['editCarModel']=="Y")
                    @if(count($car['ziliao']))
                     <h2 class="title"><span class="ml5"><b>已反馈的客户当地上牌特殊文件（等待客户选择）</b></span></h2>
                     @foreach($car['ziliao'] as $value)
                        <p>{{$value['title']}}证明：
                        @if($value['ok'] == 'Y')
                        可以办理，办理费用：人民币{{$value['fee']}}元，
                        办理将延后交车时限：{{$value['day']}}个自然日
                        @else
                        无法办理
                        @endif
                        </p>
                    @endforeach
                    @endif
                    @endif

            </div>

        </div>




    </div>

    <div class="box" ms-include-src="footernew"></div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom.order.step1", "module/common/common", "bt"],function(a,b,c){
          /*a.init('2016-3-14 16:14:35','2016-3-14 16:15:25',function(){
            //设置回调
          })*/
        })
    </script>
@endsection
