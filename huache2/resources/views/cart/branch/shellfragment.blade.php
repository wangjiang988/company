        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <?php $status = $order->orderAttr->show_status;?>
            @if($order['order_state'] == 2023)
            <p class="ti">感谢您对经销商办理特别文件的特别谅解！</p>
            @else
            @if($status == 0)
            <p class="ti">华车平台<a href="#" class="juhuang tdu" style="padding-left:5px"><img src="/themes/images/item/jxb.gif"></a>于<!--paytime-->收到您支付的诚意金人民币{{ config('orders.order_earnest_price')}}元，您的购车之旅正式启航了！</p>
            @elseif($status == 1)
            <p class="ti">华车平台<a href="#" class="juhuang tdu" style="padding-left:5px"><img src="/themes/images/item/jxb.gif"></a>于<!--paytime-->收到您支付的诚意金人民币{{ config('orders.order_earnest_price')}}元，
            您同时提交的本人上牌所需特别文件</p>
            <p>要求为：{{$order->orderAttr->file_comment}}。</p>
            <p class="ti">上牌服务约定：接受安排。本次购车的上牌服务将由经销商代办，上牌服务费约定人民币{{$order['shangpai_price']}}元，您只需要配合提供常规上牌文件。</p>
            @elseif($status == 2)
            <p class="ti">华车平台<a href="#" class="juhuang tdu" style="padding-left:5px"><img src="/themes/images/item/jxb.gif"></a>于<!--paytime-->收到您支付的诚意金人民币{{config('orders.order_earnest_price')}}元.
            <p class="ti">上牌服务约定：接受安排。本次购车的上牌服务将由经销商代办，上牌服务费约定人民币{{$order['shangpai_price']}}元，您只需要配合提供常规上牌文件。</p>
            @elseif($status == 3)
            <p class="ti">华车平台<a href="#" class="juhuang tdu" style="padding-left:5px"><img src="/themes/images/item/jxb.gif"></a>于<!--paytime-->收到您支付的诚意金人民币{{ config('orders.order_earnest_price')}}元,您同时提交的本人上牌所需特别文件要求为:{{$order->orderAttr->file_comment}} 。
            @endif
            @endif

            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class="weight fs14">订单号：{{$order['order_sn']}}</td>
                    <td width="50%">
                        <div class="psr weight fs14">
                          订单时间：{{$order['created_at']}}
                        </div>
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <ul class="pdi-order-ul border ">
            <?php $gc_name = explode('&gt;', $order['gc_name']);?>
             <p class="fs14 ml10">
	               <span>{{$gc_name[0]}}</span>
	               <span class="ml5">></span>
	               <span class="ml5">{{$gc_name[1]}}</span>
	               <span class="ml5">></span>
	               <span class="ml5">{{$gc_name[2]}}</span>
	               <span class="ml30">{{$order->orderAttr->cart_color}}</span>
             </p>
                <div class="clear"></div>
            </ul>

            <p class="tac m-t-10"><a href="{{route('cart.order_detail',['id'=>$order['id']])}}" class="juhuang tdu ">查看订单总详情</a></p>
