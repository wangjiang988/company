<table class="tbl custom-info-tbl">
       <tbody>
         <tr>
             <th width="176" class="tac">订单号/时间/<br>经销商</th>
             <th width="191" class="tac">品牌/车系/车型规格</th>
             <th width="129" class="tac">车辆开票价格/客<br>户姓名/上牌地区</th>
             <th width="130" class="tac">订单状态</th>
             <th width="138" class="last">操作</th>
         </tr>
         @if(!$lists->isEmpty())
         @foreach($lists as $list)
         <tr class="font-12">
           <td class="tac"  valign="middle">
              <p>hc{{$list->order_sn}}</p>
              <p class="psr">
                  <span class="sj">{{$list->created_at}}</span>
              </p>
              <p>{{($list->dealer_name) ? $list->dealer_name : $list->d_name}}</p>
           </td>
          <?php $brand = explode('&gt;',$list->gc_name) ?>
           <td class="tac"  valign="middle">
              <p>{{$brand[0]}}</p>
              <p>{{$brand[1]}}</p>
              <p>{{$brand[2]}}</p>
           </td>
           <td class="tac" >
              <p><span class="">￥{{number_format($list->orderPrice->car_price,2)}}</span></p>
              <p>{{getMemberName($list->user_id)}}</p>
              <?php $Areaname = array_unique(getAreaName($list->shangpai_area));?>
              <p>{{$Areaname['parent_name'] or ''}}{{$Areaname['name'] or ''}}</p>
           </td>
           <td class="tac" >
           <?php $comment = $list->orderStatus;?>
           <p>{{$comment->seller_progress}}</p>
           @foreach(explode('-', $comment->seller_remark) as $status)
           <p class="gray">{{$status}}</p>
           @endforeach
           </td>
           <td class="tac" >
             @if($list->xzjp_steps == 1)
              <div class="countdown-wapper">
                <span data-time1="{{date('Y-m-d H:i:s',time())}}" data-time2="{{date('Y-m-d H:i:s',strtotime($list->xzjp_updated_at))}}" class="countdown-red inline-block red  ">
                   @if( isVerifyDay($list->xzjp_updated_at))
                    <span class="fl"></span>
                    <span class="fl">0</span>
                    <span class="fuhao fl">天</span>
                  @endif
                  @if( isVerifyHour($list->xzjp_updated_at))
                    <span class="fl">0</span>
                    <span class="fl">0</span>
                    <span class="fuhao fl">小时</span>
                  @endif
                    <span class="fl">0</span>
                    <span class="fl">0</span>
                    <span class="fuhao fl">分</span>
                    <span class="fl">0</span>
                    <span class="fl">0</span>
                    <span class="fuhao red fl">秒</span>
                </span>
                <div class="clear"></div>
              </div>
             @endif
              <a href="{{route('dealer.activelist',['id'=>$list->id])}}" class="btn btn-danger  btn-auto sure nomargintop ">我要反馈</a>
               @if(in_array($list->order_state, [2011,2012]))
                 <?php $end_time = $list->rockon_time;?>
               @elseif(in_array($list->order_state, [301,302]))
                <?php $end_time = $list->orderinfo->car_astrict;?>
               @elseif(in_array($list->order_state, [401,402]))
                 <?php $end_time = $list->orderinfo->car_jiaoche_at;?>
               @endif
               @if(in_array($list->order_state, [2011,2012,301,302,401,402]) && intval($end_time) && (time()<strtotime($end_time)))
              <div class="countdown-wapper">
                <span data-time1="{{date('Y-m-d H:i:s',time())}}" data-time2="{{date('Y-m-d H:i:s',strtotime($end_time))}}" class="countdown inline-block blue  ">
                  @if( isVerifyDay($end_time))
                    <span class="fl"></span>
                    <span class="fl">0</span>
                    <span class="fuhao fl">天</span>
                  @endif
                  @if( isVerifyHour($end_time))
                    <span class="fl">0</span>
                    <span class="fl">0</span>
                    <span class="fuhao fl">小时</span>
                  @endif
                    <span class="fl">0</span>
                    <span class="fl">0</span>
                    <span class="fuhao fl">分</span>
                    <span class="fl">0</span>
                    <span class="fl">0</span>
                    <span class="fuhao blue fl">秒</span>
                </span>
                <div class="clear"></div>
              </div>
              @endif
           </td>
         </tr>
         @endforeach
       @else
       <tr>
           <td colspan = 5 class="tac">暂无</td>
       </tr>
       @endif

       </tbody>
 </table>

 <div class="pageinfo  tac fenye">
  {!!$lists->render()!!}
  </div>