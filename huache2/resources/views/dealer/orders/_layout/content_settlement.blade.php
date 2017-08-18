 <table class="tbl">
    <tbody>
        <tr>
            <th colspan="2" class="tal juhuang"><label class=" fs16 weight">订单信息</label></th>
        </tr>
        <tr>
            <td width="660">
                <table class="tbl2" width="100%">
                    <tr>
                        <td  width="50%"><p><b>订单号：</b>{{$order->order_sn}}</p></td>
                        <td  width="50%">
                            <b>订单时间：</b>{{$order->created_at}}
                    </tr>
                    <tr>
                        <td colspan="2"><p><b>订单类别：</b>
                        @if($order->is_xianche)
                        现车订单
                        @else
                        非现车订单
                        @endif
                        </p><hr class="dashed"></td>
                    </tr>
                    <tr>
                    <?php $car = explode('&gt;',$order->gc_name);?>
                        <td colspan="2">
                          <p>
                             <b>车型：</b>
                             <span>{{$car[0]}}</span>
                             <span class="ml5">></span>
                             <span class="ml5">{{$car[1]}}</span>
                             <span class="ml5">></span>
                             <span class="ml5">{{$car[2]}}</span>
                          </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>整车型号： </b>{{$order->orderGoodsClass->vehicle_model}}</p></td>
                        <td><p><b>厂商指导价：</b>￥{{number_format(unserialize(($order->orderInfoprice['value'])),2)}}</p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p><b>车辆类别：</b>全新中规车整车</p></td>
                    </tr>
                    <tr>
                        <td><p><b>数量：</b>1台</p></td>
                        @if($order->orderBaojia->bj_dealer_internal_id)
                        <td><p><b>内部车辆编号：</b>{{$order->orderBaojia->bj_dealer_internal_id}}</p></td>
                        @endif
                    </tr>

                    <tr>
                        <td colspan="2"><hr class="dashed nomargin"></td>
                    </tr>
                    <?php $dealer = $order->orderDealer ?>
                    <tr>
                        <td colspan="2"><p><b>经销商：</b>{{$dealer['d_name']}}</p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p><b>营业地点：</b>{{$dealer['d_yy_place']}}</p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p><b>交车地点：</b>{{$dealer['d_jc_place']}}</p></td>
                    </tr>
                    <tr>
                        <td><p><b>归属地区：</b>{{$dealer['d_areainfo']}}</p></td>
                        <td>
                             <p><b>约定交车时间：</b>
                             @if($order->orderAppoint->is_feeback == 1)
                              {{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif
                              @elseif($order->orderAppoint->is_feeback == 2)
                              {{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif
                              @else
                              {{date('Y年m月d日',strtotime($order->orderAppoint->default_data))}} @if($order->orderAppoint->default_day == 1) 全天@elseif($order->orderAppoint->default_day == 2) 上午@elseif($order->orderAppoint->default_day == 3)下午 @endif
                              @endif
                             </p>
                         </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr class="dashed nomargin"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p><b>车辆开票价格：</b>￥{{number_format($order->orderPrice->car_price,2)}}</p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p><b>客户买车定金：</b>￥{{number_format($order->orderPrice->client_hand_price,2)}}</p></td>
                    </tr>

                     <tr>
                        <td><p><b>我的服务费：</b>￥{{number_format($order->orderPrice->agent_service_price,2)}}</p></td>
                       @if($order->order_status == 5)
                        <td><p class="juhuang"><b>加信宝当前冻结：</b>￥0</p></td>
                       @endif
                    </tr>

                </table>
            </td>
            <td>
                <div class="times psr">
                   @if($order->order_state == config('orders.order_settlement_have'))
                    <p class="status tac  m-t-10"><b class="fs14">订单状态：已结算，等待收入入账</b></p>
                    @elseif($order->order_state == config('orders.order_settlement_Income'))
                    <p class="status tac  m-t-10"><b class="fs14">订单状态：收入已入账</b></p>
                    @elseif($order->order_state == config('orders.order_settlement_security'))
                    <p class="status tac  m-t-10"><b class="fs14">订单状态：等待定期结算</b></p>
                    @elseif($order->order_state == config('orders.order_jiaoche_all'))
                    <p class="status tac  m-t-10"><b class="fs14">订单状态：等待客户结算确认</b></p>
                    @endif

                    <p class="tac m-t-10"><a href="{{route('dealer.order.detail',['order_id'=>$order->id])}}" class="juhuang tdu">查看订单总详情</a></p>

                    <hr class="dashed">
                    <p class="pl20 pt"><b>客户姓名： </b>
                    @if($order->orderuserextion)
                    {{$order->orderuserextion->last_name}}**
                    @if($order->orderuserextion->call)
                    ({{$order->orderuserextion->call}})
                    @endif
                    @else
                    客官{{substr($order->orderUsers->phone,-4)}}
                    @endif
                    </p>
                    <p class="pl20 pt"><b>手机： </b>{{$order->orderUsers->phone}}</p>
                    <p class="pl20 pt"><b>客户承诺上牌地区： </b>
            <?php $shangpai = getAreaName($order['shangpai_area'])?>
            {{$shangpai['parent_name'].$shangpai['name']}}</p>
                    <p class="pl20 pt"><b>车主车辆用途： </b>
                    @if($order->orderAppoint->car_purpose == 0)
                    非营业个人客车
                    @elseif($order->orderAppoint->car_purpose == 1)
                    非营业企业客车
                    @elseif($order->orderAppoint->car_purpose == 2)
                    无
                    @endif
                   </p>
                    <p class="pl20 pt">
                      <b>车主身份类别： </b>
                      <span class="fr w195">
                     @if($order->orderAppoint->car_purpose == 1)
                   <?php $arr = ['上牌地本地注册企业（增值税一般纳税人）','上牌地本地注册企业（小规模纳税人）'];?>
                   {{$arr[$order->orderAppoint->identity_type]}}
                   @elseif($order->orderAppoint->car_purpose == 2)
                   无
                   @else
                   <?php $type_id = getIdentity_id($order->orderAppoint->identity_type,true);?>
                    <?php $arr1 = [2,3,4,5,6,7,8,15]; $arr2=[10,11,12,13];?>
                    @if(array_search($order->orderAppoint->identity_type,$arr1))
                    国内其他限牌城市户籍居民({{$type_id}})
                    @elseif(array_search($order->orderAppoint->identity_type,$arr2))
                    非中国大陆人士({{$type_id}})
                    @else
                    {{$type_id}}
                    @endif
                    @endif
                      </span>
                      <label class="clear"></label>
                    </p>
                    <div class="clear"></div>
                    <p class="pl20 pt"><b>计划上牌车主名称： </b>{{$order->orderAppoint->owner_name}} </p>
                </div>
            </td>

        </tr>

    </tbody>
</table>