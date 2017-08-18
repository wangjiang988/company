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
            <td><p><b>归属地区：</b>{{$dealer['d_areainfo']}}</p></td>
            <?php $arr_comm = [402,404,406,405];?>
          @if(in_array($order->order_state,$arr_comm))
            @if($order->orderAppoint->system_day)
                  <td><p>约定交车时间：
                   @if($order->order_state == 404)
                   <span class="juhuang">
                   @endif
                   @if($order->order_state == 402)
                   <span class="weight">
                  @endif
                  {{date('Y年m月d日',strtotime($order->orderAppoint->system_data))}} @if($order->orderAppoint->system_day == 1) 全天@elseif($order->orderAppoint->system_day == 2) 上午@elseif($order->orderAppoint->system_day == 3)下午 @endif </span></p></td>
            @else
               @if($order->orderAppoint->is_feeback == 1)
                   <td><p>约定交车时间：
                   @if($order->order_state == 404)
                   <span class="juhuang">
                   @endif
                   @if($order->order_state == 402)
                   <span class="weight">
                   @endif
                   {{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif </span></p></td>
                @elseif($order->orderAppoint->is_feeback == 2)
                    <td><p>约定交车时间：
                   @if($order->order_state == 404)
                   <span class="juhuang">
                   @endif
                   @if($order->order_state == 402)
                   <span class="weight">
                   @endif
                    {{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif </span></p></td>
                @else
                    <td><p>约定交车时间：
                   @if($order->order_state == 404)
                   <span class="juhuang">
                   @endif
                   @if($order->order_state == 402)
                   <span class="weight">
                   @endif
                    {{date('Y年m月d日',strtotime($order->orderAppoint->default_data))}} @if($order->orderAppoint->default_day == 1) 全天@elseif($order->orderAppoint->default_day == 2) 上午@elseif($order->orderAppoint->default_day == 3)下午 @endif </span></p></td>
                @endif
            @endif
          @else
           @if(! is_null($order->orderDate))
            <td><p><b>交车时限：</b>{{date('Y年m月d日',strtotime($order->orderDate->jiaoche_at))}}</p></td>
           @endif
          @endif
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
            <td><p><b>加信宝当前冻结：</b>￥{{config('orders.order_earnest_price')
	    }}</p></td>
        </tr>

    </table>
</td>
<td>
    <div class="times psr">
    @if(!$order->orderXzjEdit->where('is_install',0)->isEmpty())
        <a href="{{route('dealer.access.list',['id'=>$order->id])}}" class="modify-tag">选装修改</a>
    @endif
        @if($order->order_state == 303)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：售方修改，等待客户确认</b></p>
        @elseif($order->order_state == 302 || $order->order_state == 301)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：等待售方交车邀请</b></p>
        @elseif($order->order_state == 400)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：售方已发交车邀请，等待客户反馈</b></p>
        @elseif($order->order_state == 401)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：等待售方确认交车时间</b></p>
        @elseif($order->order_state == 403)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：等待客户再确认交车时间</b></p>
        @elseif($order->order_state == 402 || $order->order_state == 404 || $order->order_state == 405)
         @if($order->order_state == 402)
         <p class="status tac  m-t-10"><b class="fs14">订单状态：等待售方补充预约信息</b></p>
         @elseif($order->order_state == 404 || $order->order_state == 405)
         <p class="status tac  m-t-10"><b class="fs14">订单状态：预约完毕，等待交车</b></p>
         @endif
          <p class="tac m-t-10">
          @if($order->orderAppoint->setDays() > 0)
          <span class="juhuang">交车还有{{$order->orderAppoint->setDays()}}天</span></p>
          @else
          <span class="juhuang red">交车时间已到</span></p>
          @endif
        @elseif($order->order_state == 406)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：等待客户交车确认</b></p>
        @elseif($order->order_state == 309)
        <p class="status tac  m-t-10"><b class="fs14">订单状态：交车时间已暂停</b></p>
        @endif
        <div class="clear"></div>
        @if($order->order_status !== 4 && $order->order_state != 309)
        <p class="tac fs14" style="margin-top: 10px;">交车邀请发出时限仅剩
            <span class="jishi countdown inline-block juhuang ">
                <span></span>
                <span>0</span>
                <span class="fuhao">天</span>
                <span>0</span>
                <span>0</span>
                <span class="fuhao">小时</span>
                <span>0</span>
                <span>0</span>
                <span class="fuhao">分</span>
                <span>0</span>
                <span>0</span>
                <span class="fuhao juhuang">秒</span>
            </span>
        </p>
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
        @if($order->order_status == 4)
        <?php $arr = [401,402,403,404,405,406];?>
        @if(in_array($order->order_state,$arr))
        {{$order->orderUsers->phone}}
        @else
        {{changeMobile($order->orderUsers->phone)}}
        @endif
        @else
        @if($order->orderAttr->or_contact)
        {{$order->orderUsers->phone or ''}}
        @else
        {{( ! empty($order->orderUsers->phone)) ? changeMobile($order->orderUsers->phone) : ''}}
        @endif
        @endif
        </p>
        <p class="pl20 pt"><b>客户承诺上牌地区： </b>
	 <?php $shangpai = getAreaName($order['shangpai_area'])?>
        {{$shangpai['parent_name'].$shangpai['name']}} </p>

     @if($order->order_status == 4 && $order->order_state>400)
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
          @if($order->orderAppoint->car_purpose == 1)
           <?php $arr = ['上牌地本地注册企业（增值税一般纳税人）','上牌地本地注册企业（小规模纳税人）'];?>
           <span class="fr w195">{{$arr[$order->orderAppoint->identity_type]}}</span>
           @elseif($order->orderAppoint->car_purpose == 2)
           <span class="fr w195">无
          </span>
           @else
           <?php $type_id = getIdentity_id($order->orderAppoint->identity_type,true);?>
            <?php $arr1 = [2,3,4,5,6,7,8,15]; $arr2=[10,11,12,13];?>
            @if(array_search($order->orderAppoint->identity_type,$arr1))
            <span class="fr w195">国内其他限牌城市户籍居民({{$type_id}})</span>
            @elseif(array_search($order->orderAppoint->identity_type,$arr2))
            <span class="fr w195">非中国大陆人士({{$type_id}})</span>
            @else
            <span class="fr w195">{{$type_id}}</span>
            @endif
           @endif
          <label class="clear"></label>
        </p>
       @endif
        <div class="clear"></div>
        <p class="pl20 pt"><b>买车担保金： </b>￥{{number_format($order->sponsion_price,2)}} </p>
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
            <td width="33%"><p><b>座位数：</b>{{$order->orderinfo->car_seating}}</p></td>
        </tr>
        <tr>
          <td width="33%"><p><b>车身颜色：</b>{{$order->orderinfo->body_color}}</p></td>
          <td width="33%"><p><b>内饰颜色：</b>{{$order->orderinfo->interior_color}}</p></td>
            <td width="33%"><p><b>排放标准：</b>{{$order->orderinfo->car_paifang}}</p></td>
        </tr>
        <tr>
            <td width="33%"><p><b>出厂年月：</b>
        @if(isset($editcarinfo['year_month']))
        {{$editcarinfo['year_month']}}
        @else
        @if($baojia['bj_is_xianche'])
        (不早于)<span>{{date("Y年m月",strtotime($baojia['bj_producetime']))}}</span>
        @else
        (不早于)<span>{{date("Y年m月", time())}}</span>
        @endif
        @endif
        </p></td>
                    <td width="33%"><p><b>行驶里程：</b>
        @if($order->orderinfo->mileage)
         {{$order->orderinfo->mileage}}公里
        @else
         @if($baojia['bj_is_xianche'])
        (不高于）<span>{{$baojia['bj_licheng']}}公里</span>
        @else
        (不高于)<span>20公里</span>
         @endif
        @endif
            </p></td>
            @if(!$baojia['bj_is_xianche'])
            <td width="33%"><p><b>交车周期：</b>{{$baojia['bj_jc_period']}}个月</p></td>
            @endif
        </tr>

        <tr>
            <td colspan="3"><hr class="dashed nomargin"></td>
        </tr>
        <tr>
            <td colspan="3">

              @if($baojia['bj_is_xianche'] && isset($originals['rpo']))
                <p>已装原厂选装精品：</p>
                <table class="tbl tbl3" id="tbl_sum">
                    <tr>
                       <th><p class="fs15">名称</p></th>
                       <th><p class="fs15">型号/说明</p></th>
                       <th><p class="fs15">厂家指导价</p></th>
                       <th><p class="fs15">数量</p></th>
                       <th><p class="fs15">附加价值</p></th>
                   </tr>
                   @if($editxzj && count($editxzj)>0)
                    @foreach($editxzj as $rpo)
                       @if($rpo['num'] != 0)
                   <tr>
                       <td class="tac">{{$rpo['xzj_title']}}</td>
                       <td class="tal">{{$rpo['xzj_model']}}</td>
                       <td class="tac">￥{{$rpo['xzj_guide_price']}}</td>
                       <td class="tac">{{$rpo['num']}}</td>
                       <td class="tac" data-price="{{$rpo['xzj_guide_price']*$rpo['num']}}">￥{{number_format($rpo['xzj_guide_price']*$rpo['num'],2)}}</td>
                   </tr>
                      @endif
                @endforeach
              </table>
                <h2 class="text-right pr150 fs15" id="sum_price"><b>合计价值：</b><span class="juhuang"></span></h2>

            </td>

          @else
                     @foreach($originals['rpo'] as $rpo)
                      @if($rpo['num'] != 0)
                   <tr>
                       <td class="tac">{{$rpo['xzj_title']}}</td>
                       <td class="tal">{{$rpo['xzj_model']}}</td>
                       <td class="tac">￥{{$rpo['xzj_guide_price']}}</td>
                       <td class="tac">{{$rpo['num']}}</td>
                       <td class="tac" data-price="{{$rpo['xzj_guide_price']*$rpo['num']}}">￥{{number_format($rpo['xzj_guide_price']*$rpo['num'],2)}}</td>
                   </tr>
                  @endif
                @endforeach
              </table>
                <h2 class="text-right pr150 fs15" id="sum_price"><b>合计价值：</b><span class="juhuang"></span></h2>

            </td>
            @endif
    @endif
        </tr>
    </table>
    <hr>

 @if(count($order->orderServer) || count($editzengpin)>0)
    <p>免费礼品或服务</p>
    <div class="fl wp70">
        <table class="tbl">
            <tr>
                <td><p class="tac fs14"><b>名称</b></p></td>
                <td><p class="tac fs14"><b>数量</b></p></td>
                <td><p class="tac fs14"><b>状态</b></p></td>
            </tr>
    @if($editzengpin && count($editzengpin)>0)
        @foreach($editzengpin as $orderserve)
          @if($orderserve['num'] != 0)
      <tr>
    <td><p class="tac fs14">{{$orderserve['zp_title']}}</p></td>
    <td><p class="tac fs14">{{$orderserve['num']}}</p></td>
    <td><p class="tac fs14">
        @if($orderserve['zp_status'])
        已安装
        @else
        /
        @endif
    </p></td>
    </tr>
       @endif
        @endforeach
   </table>
    @else
      @foreach($order->orderServer as $orderserve)
        @if($orderserve->num != 0)
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
         @endif
       @endforeach
    </table>
   @endif

@endif
    </div>
    <div class="clear"></div>

        <hr>
          <div class="fl w300">
          <p><b>客户投保：</b>
            待定
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
            待定
          </p>
          @if($shangpai_status == 1)
          <br>
            <p><b>客户本人上牌违约赔偿（约定）：</b>￥{{number_format($baojia['client_license_compensate'],2)}}</p><br>
           @endif
          </div>
        @if($order->order_state == 404 || $order->order_state == 402)
         <div class="fl ml200">
         <p><b>计划上牌车主名称：</b>{{$order->orderAppoint->owner_name}}</p>  <br>
          <p><b>上牌车主名称与提车人姓名是否一致：</b>
          @if($order->orderAppoint->car_purpose == 2 || $order->orderAppoint->owner_name != $order->orderAppoint->extract_name)
         否
        @else
        是
        @endif
         </p> <br>
         <p><b>提车人姓名：</b>{{$order->orderAppoint->extract_name}}</p>  <br>
          <p><b>提车人电话：</b>{{$order->orderAppoint->extract_phone}}</p>  <br>
        </div>
        @endif
       <div class="clear"></div>
        </td>
        </tr>

</tbody>
</table>