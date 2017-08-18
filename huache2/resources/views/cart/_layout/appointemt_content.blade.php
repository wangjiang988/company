            <h1>尊敬的客户：</h1>
            @if($order->order_state == 401)
            <h1 class="ti psr">您的希望交车时间{{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午@endif，售方正在确认，请耐心等待。
            @if(is_numeric($order->orderAttr->non_xzj_list) || $order->orderBaojia->baojiaxzj->count())
            <a target="_blank" href="{{route('buy.show',['id'=>$order->id])}}"> <img class="adv-img" src="/themes/images/adv.png" alt=""></a></h1>
            @else
            </h1>
            @endif
            @elseif($order->order_state == 400)
            <h1 class="ti psr">我们怀着无比激动的心情告诉您：未来座驾正恭候您的检阅！动动键鼠就能完成预约，尽快哦～
            @if(is_numeric($order->orderAttr->non_xzj_list) || $order->orderBaojia->baojiaxzj->count())
            <a target="_blank" href="{{route('buy.show',['id'=>$order->id])}}"> <img class="adv-img" src="/themes/images/adv.png" alt=""></a></h1>
            @else
            </h1>
            @endif
            @elseif($order->order_state == 403)
            <h1 class="ti psr">售方已对交车时间再次反馈，请您尽快确认。
            @if(is_numeric($order->orderAttr->non_xzj_list) || $order->orderBaojia->baojiaxzj->count())
            <a target="_blank" href="{{route('buy.show',['id'=>$order->id])}}">
            <img class="adv-img" src="/themes/images/adv.png" alt=""></a></h1>
            @else
            </h1>
            @endif
            @elseif($order->order_state == 402)
            <h1 class="ti psr">为您服务岂可马虎？售方正在精挑细选VIP服务专员，请静候佳音。
            @if(is_numeric($order->orderAttr->non_xzj_list) || $order->orderBaojia->baojiaxzj->count())
            <a target="_blank" href="{{route('buy.show',['id'=>$order->id])}}">
            <img class="adv-img" src="/themes/images/adv.png" alt=""></a></h1>
            @else
            </h1>
            @endif
            @endif
            <?php $arr_tmp = [404,406];?>
            @if(in_array($order->order_state,$arr_tmp))
           <h1 class="ti psr ml50">您的VIP服务专员（<span class="juhuang">
           @if($order->orderAppoint->appoinWaiter)
           {{$order->orderAppoint->appoinWaiter->name}}，电话：{{$order->orderAppoint->appoinWaiter->mobile}}
           @if($order->orderAppoint->appoinWaiter->tel)
           / {{$order->orderAppoint->appoinWaiter->tel}}
           @endif
           @endif
           </span>），将在<span class="juhuang weight">
            @if($order->orderAppoint->system_day)
                {{date('Y年m月d日',strtotime($order->orderAppoint->system_data))}} @if($order->orderAppoint->system_day == 1) 全天@elseif($order->orderAppoint->system_day == 2) 上午@elseif($order->orderAppoint->system_day == 3)下午 @endif </span></p></td>
            @else
                @if($order->orderAppoint->is_feeback == 1)
                        {{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif
                @elseif($order->orderAppoint->is_feeback == 2)
                        {{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif
                @else
                {{date('Y年m月d日',strtotime($order->orderAppoint->default_data))}} @if($order->orderAppoint->default_day == 1) 全天@elseif($order->orderAppoint->default_day == 2) 上午@elseif($order->orderAppoint->default_day == 3)下午 @endif
                @endif
            @endif
                </span>恭候，<br><span class="ml-50">请作好按约前往的准备。如临时遇到意外情况，请及时与服务专员沟通。</span></h1>
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
             <?php $gc_name = explode('&gt;', $order->gc_name);?>
            <ul class="pdi-order-ul border ">
               <p class="fs14 ml10">
                   <span>{{$gc_name[0]}}</span>
                   <span class="ml5">&gt;</span>
                   <span class="ml5">{{$gc_name[1]}}</span>
                   <span class="ml5">&gt;</span>
                   <span class="ml5">{{$gc_name[2]}}</span>
                   <span class="ml30">{{$order->orderAttr->cart_color}}</span>
               </p>
            </ul>
            <div class="clear m-t-10"></div>
            <p class="tac"><a href="{{route('cart.order_detail',['id'=>$order->id])}}" class="tdu juhuang">查看订单总详情</a></p>
            <div class="clear m-t-10"></div>
            <table class="tbl c-tb tac">
                <tr>
                    <td class="" width="20%"><b class="fs16">经销商</b></td>
                    <td>{{$order->orderDealer->d_name}}</td>
                </tr>
                <tr>
                    <td><b class="fs16">交车地点</b></td>
                    <td>
                        {{$order->orderDealer->d_jc_place}}
                        <a href="http://gaode.com/search?query={{urlencode($order->orderDealer->d_jc_place)}}"  target="_blank" class="blue tdu fr">查看地图</a>
                        <div class="clear"></div>
                    </td>
                </tr>
                <tr>
                    <td><b class="fs16">您前往提车的方式</b></td>
                    <td>本人安排</td>
                </tr>
                <tr>
                    <td><b class="fs16">您的车辆回程方式</b></td>
                    <td>本人安排</td>
                </tr>
                <tr>
                    <td><b class="fs16">车辆开票价格</b></td>
                    <td>人民币{{number_format($order->orderPrice->car_price,2)}}元</td>
                </tr>
                <tr>
                    <td><b class="fs16">付款方式</b></td>
                    <td>全款（到店验车后，车主向经销商当场直接支付车辆开票价格之全部金额）</td>
                </tr>
                @if(in_array($order->order_state,$arr_tmp))
                <tr>
                    <td><b class="fs16">承诺上牌地区</b></td>
                    <?php $shangpai = getAreaName($order['shangpai_area'])?>
                    <td>{{$shangpai['parent_name'].$shangpai['name']}}</td>
                </tr>
                <tr>
                     <td><b class="fs16">上牌车主身份类别</b></td>
                     @if($order->orderAppoint->car_purpose == 1)
                     <?php $arr = ['上牌地本地注册企业（增值税一般纳税人）','上牌地本地注册企业（小规模纳税人）'];?>
                     <td>{{$arr[$order->orderAppoint->identity_type]}}</td>
                     @elseif($order->orderAppoint->car_purpose == 2)
                     <td>无
                    </td>
                     @else
                     <?php $type_id = getIdentity_id($order->orderAppoint->identity_type,true);?>
                      <?php $arr1 = [2,3,4,5,6,7,8,15]; $arr2=[10,11,12,13];?>
                      @if(array_search($order->orderAppoint->identity_type,$arr1))
                      <td>国内其他限牌城市户籍居民({{$type_id}})</td>
                      @elseif(array_search($order->orderAppoint->identity_type,$arr2))
                      <td>非中国大陆人士({{$type_id}})</td>
                      @else
                      <td>{{$type_id}}</td>
                      @endif
                     @endif
                </tr>
                @else

                @if($order->order_state == 402)
                <tr>
                    <td><b class="fs16">约定交车时间</b></td>
             @if($order->orderAppoint->system_day)
                 {{date('Y年m月d日',strtotime($order->orderAppoint->system_data))}} @if($order->orderAppoint->system_day == 1) 全天@elseif($order->orderAppoint->system_day == 2) 上午@elseif($order->orderAppoint->system_day == 3)下午 @endif </span></p></td>
             @else
                    @if($order->orderAppoint->is_feeback == 1)
                    <td>{{date('Y年m月d日',strtotime($order->orderAppoint->member_data))}} @if($order->orderAppoint->member_day == 1) 全天@elseif($order->orderAppoint->member_day == 2) 上午@elseif($order->orderAppoint->member_day == 3)下午 @endif </td>
                    @elseif($order->orderAppoint->is_feeback == 2)
                    <td>{{date('Y年m月d日',strtotime($order->orderAppoint->seller_data))}} @if($order->orderAppoint->seller_day == 1) 全天@elseif($order->orderAppoint->seller_day == 2) 上午@elseif($order->orderAppoint->seller_day == 3)下午 @endif </td>
                    @else
                     <td>{{date('Y年m月d日',strtotime($order->orderAppoint->default_data))}} @if($order->orderAppoint->default_day == 1) 全天@elseif($order->orderAppoint->default_day == 2) 上午@elseif($order->orderAppoint->default_day == 3)下午 @endif </td>
                    @endif
            @endif
                </tr>
                @else
                <tr>
                    <td><b class="fs16">售方交车时限</b></td>
                    <td>{{date('Y-m-d',strtotime($order->orderDate->jiaoche_at))}} 24:00:00</td>
                </tr>
                @endif

                @endif

                <?php $arr = [401,402,403,404,406];?>
                @if(in_array($order->order_state,$arr))
                <tr>
                    <td><b class="fs16">车辆用途</b></td>
                    @if($order->orderAppoint->car_purpose == 0)
                    <td>非营业个人客车</td>
                    @elseif($order->orderAppoint->car_purpose == 1)
                    <td>非营业企业客车</td>
                    @elseif($order->orderAppoint->car_purpose == 2)
                    <td>无</td>
                    @endif
                </tr>
                <tr>
                    <td><b class="fs16">计划上牌车主名称</b></td>
                    <td>{{$order->orderAppoint->owner_name}}</td>
                </tr>
                <tr>
                    <td><b class="fs16">提车人姓名</b></td>
                    <td>{{$order->orderAppoint->extract_name}}</td>
                </tr>
                <tr>
                    <td><b class="fs16">提车人手机号</b></td>
                    <td>{{$order->orderAppoint->extract_phone}}</td>
                </tr>
                @endif
            </table>