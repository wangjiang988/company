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
            </td>
        </tr>
        <tr>
            <td><p><b>订单类别：</b>
	    @if($baojia['bj_is_xianche'])
            现车订单
            @else
            非现车订单
            @endif

	    </p><hr class="dashed">
             </td>
             @if($order->order_status == 0)
             <td>
                 <p><b>结束时间：</b>{{date('Y年m月d日',strtotime($order->updated_at))}}</p>
             </td>
             @endif
        </tr>
        <tr>
            <td colspan="2">
              <p>
                 <b>车型：</b>
		 <?php $car = explode('&gt;',$order->gc_name);?>
                 <span>{{$car[0]}}</span>
                 <span class="ml5">></span>
                 <span class="ml5">{{$car[1]}}</span>
                 <span class="ml5">></span>
                 <span class="ml5">{{$car[2]}}</span>
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
	    @if($baojia['bj_is_xianche'] && $baojia['bj_dealer_internal_id'])
            <td><p><b>内部车辆编号：</b>
	    {{$baojia['bj_dealer_internal_id']}}
	    </p></td>
	    @endif
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
            <td colspan="2"><p><b>车辆开票价格：</b>{{number_format($baojia['car_price'],2)}}</p></td>
        </tr>
        <tr>
            <td colspan="2"><p><b>客户买车定金：</b>{{number_format($baojia['client_hand_price'],2)}}</p></td>
        </tr>

         <tr>
            <td><p><b>我的服务费：</b>￥{{number_format($baojia['agent_service_price'],2)}}</p></td>
        </tr>

    </table>
</td>
<td>
    <div class="times psr">
        @if($order->order_state == 392 || $order->order_state == 390)
        <p class="status tac  m-t-10"><b class="fs14">
        订单状态：售方主动终止</b></p>
        @elseif($order->order_state == 391)
        <p class="status tac  m-t-10"><b class="fs14">
        订单状态：售方修改，客户不接受</b></p>
        @elseif($order->order_state == 394)
         <p class="status tac  m-t-10"><b class="fs14">
        订单状态：客户支付买车担保金违约</b></p>
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
        <p class="pl20 pt"><b>手机： </b>
        @if($order->orderAttr->or_contact)
        {{$order->orderUsers->phone}}
        @else
        {{changeMobile($order->orderUsers->phone)}}
        @endif
        </p>
        <p class="pl20 pt"><b>客户承诺上牌地区： </b>
	 <?php $shangpai = getAreaName($order['shangpai_area'])?>
        {{$shangpai['parent_name'].$shangpai['name']}} </p>
          <label class="clear"></label>
        </p>
        <div class="clear"></div>
        <p class="pl20 pt"><b>买车担保金： </b>￥{{number_format($order->sponsion_price,2)}} </p>
    </div>
</td>

</tr>

</tbody>
</table>

