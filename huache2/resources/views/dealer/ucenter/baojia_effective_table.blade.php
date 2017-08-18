 <table class="tbl tbl-blue wp100 mt20 content">
    <tbody>
      <tr>
        <th class="tac" width="99"><span>报价编号</span></th>
        <th class="tac" width="160"><span>经销商</span></th>
        <th class="tac" width="198"><span>报价车型</span></th>
        <th class="tac" width="168"><span>设定生效时间</span></th>
        <th class="tac" width="150"><span>操作</span></th>
      </tr>
      @if(count($lists) > 0)
      @foreach($lists as $list)
      <tr>
        <td class="tac"><a href="{{route('dealer.baojia',['type'=>'view','id'=>$list->bj_id,'step'=>1])}}" class="juhuang tdu">{{$list->bj_serial}}</a></td>
        <td class="tac"><span>{{$list->dealer_name}}</span></td>
        <td class="tac"><span>{{$list->gc_name}}</span></td>
        <td class="tac"><span>{{date('Y-m-d H:i:s',$list->bj_start_time)}}</span></td>
        <td class="tac">
            <a href="javascript:baojiaExcute('copy',{{$list->bj_id}});" class="juhuang tdu">复制</a>
            <a href="#" class="juhuang tdu ml10 stop" data-id="{{$list->bj_id}}">暂停</a>
            <a href="javascript:;" class="juhuang tdu ml10 susp" data-id="{{$list->bj_id}}">终止</a>
        </td>
      </tr>
      @endforeach
      @else
     @if(isset($type))
    <tr>
      <td colspan="5">
          <div class="m-t-10"></div>
          <p class="tac">暂无满足条件的等待生效的报价~</p>
          <div class="m-t-10"></div>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="5">
          <div class="m-t-10"></div>
          <p class="tac">暂无等待生效的报价~</p>
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