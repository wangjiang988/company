<table class="nobordertbl" width="100%">
    <tr>
        <td width="50%" class="fs14"><span class="weight">订单号：</span>{{$order->order_sn}}</td>
        <td width="50%" class="fs14">
            <span class="weight">订单时间：</span>{{$order->created_at}}
        </td>
    </tr>
</table>
<div class="clear m-t-10"></div>
<ul class="pdi-order-ul border ">
   <p class="fs14 ml10">
    <?php $car = explode('&gt;',$order->gc_name);?>
       <span>{{$car[0]}}</span>
       <span class="ml5">></span>
       <span class="ml5">{{$car[1]}}</span>
       <span class="ml5">></span> 
       <span class="ml5">{{$car[2]}}</span>
       <span class="ml30">{{$order->orderAttr->cart_color}}</span> 
   </p>
</ul>