<table class="tbl tbl-blue wp100 mt20 content">
<tbody>
  <tr>
    <th class="tac" width="130"><span>报价编号</span></th>
    <th class="tac" width="198"><span>报价车型</span></th>
    <th class="tac" width="130"><span>裸车开票价格</span></th>
    <th class="tac" width="168"><span>下架原因</span></th>
    <th class="tac" width="150"><span>操作</span></th>
  </tr>
  @if(count($lists) > 0)
  @foreach($lists as $list)
  <tr data-status="2">
    <td class="tac"><a href="{{route('dealer.baojia',['type'=>'view','id'=>$list->bj_id,'step'=>1])}}" class="juhuang tdu">{{$list->bj_serial}}</a></td>
    <td class="tac"><span>{{$list->gc_name}}</span></td>
    <td class="tac"><span>￥{{preg_replace('/\B(?=(?:\d{3})+\b)/',',',$list->bj_lckp_price)}}</span></td>
    <td class="tac"><span>{{$list->bj_reason}}</span></td>
    <td class="tac">
        <a href="javascript:baojiaExcute('copy',{{$list->bj_id}});" class="juhuang tdu" data-id="{{$list->bj_id}}">复制</a>
        @if($list->bj_status == 2 && !in_array($list->bj_status_change_code,get_system_suspect_code()))
        <a href="javascript:;" class="juhuang tdu ml10 renew" data-id="{{$list->bj_id}}">恢复</a>
        @endif
        <a href="javascript:;" class="juhuang tdu ml10 susp" data-id="{{$list->bj_id}}">终止</a>
    </td>
  </tr>
  @endforeach
  @else
   @if(isset($type))
    <tr>
      <td colspan="5">
          <div class="m-t-10"></div>
          <p class="tac">暂无满足条件的暂时下架的报价~</p>
          <div class="m-t-10"></div>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="5">
          <div class="m-t-10"></div>
          <p class="tac">暂无暂时下架的报价~</p>
          <div class="m-t-10"></div>
      </td>
    </tr>
    @endif
 @endif
</tbody>
</table>
<div class="tac">
{!!$lists->render()!!}
</div>