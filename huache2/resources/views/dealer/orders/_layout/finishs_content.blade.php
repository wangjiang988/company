  <table class="tbl custom-info-tbl">
       <tbody>
         <tr>
             <th class="tac">订单号/时间/<br>经销商</th>
             <th class="tac">品牌/车系/车型规格</th>
             <th class="tac">车辆开票价格</th>
             <th class="tac">订单状态</th>
             <th class="last">操作</th>
         </tr>
         @if(!$lists->isEmpty())
         @foreach($lists as $list)
         <tr class="font-12">
           <td class="tac" width="110" valign="middle">
              <p>hc{{$list->order_sn}}</p>
              <p class="psr">
                  <span class="sj">{{$list->created_at}}</span>
              </p>
              <p>{{($list->dealer_name) ? $list->dealer_name : $list->d_name}}</p>
           </td>
          <?php $brand = explode('&gt;',$list->gc_name) ?>
           <td class="tac" width="193" valign="middle">
              <p>{{$brand[0]}}</p>
              <p>{{$brand[1]}}</p>
              <p>{{$brand[2]}}</p>
           </td>
           <td class="tac" width="118">
              <p><span class="">￥{{number_format($list->orderPrice->car_price,2)}}</span></p>
           </td>
           <td class="tac" width="118">
           <?php $comment = $list->orderStatus;?>
           <p>{{$comment->seller_progress}}</p>
           @foreach(explode('-', $comment->seller_remark) as $status)
           <p class="gray">{{$status}}</p>
           @endforeach
           </td>

           <td class="tac" width="98">
              <a href="{{route('dealer.activelist',['id'=>$list->id])}}" class="btn btn-danger  btn-auto sure nomargintop" >查看</a>
           </td>
         </tr>
         @endforeach
       @else
       <tr>
          <td colspan=4 class="tac">暂无</td>
       </tr>
       @endif
       </tbody>
 </table>

  <div class="pageinfo tac fenye">
  {!!$lists->render()!!}
  </div>