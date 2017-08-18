<table class="tbl tbl-blue wp100 mt20 content">
  <tbody>
    <tr>
      <th class="tac" width="168"><span>最新保存时间</span></th>
      <th class="tac" width="160"><span>经销商</span></th>
      <th class="tac" width="198"><span>报价车型</span></th>
      <th class="tac" width="99"><span>车身颜色</span></th>
      <th class="tac" width="150"><span>操作</span></th>
    </tr>
    @if(count($lists)>0)
    @foreach($lists as $da)
    <tr>
      <td class="tac"><span>{{$da->bj_update_time}}</span></td>
      <td class="tac"><span>{{$da->dealer_name}}</span></td>
      <td class="tac"><span>{{$da->gc_name}}</span></td>
      <td class="tac"><span>{{$da->bj_body_color}}</span></td>
      <td class="tac">
          <a href="{{route('dealer.baojia',['type'=>'edit','id'=>$da->bj_id,'step'=>$da->bj_step])}}" class="juhuang tdu edit-solution"data-value="{{$da->bj_id}}">查看</a>
          <a href="javascript:;" class="juhuang tdu ml10 del" data-value="{{$da->bj_id}}">删除</a>
      </td>
    </tr>
    @endforeach
    @else
    @if(isset($type))
    <tr>
      <td colspan="5">
          <div class="m-t-10"></div>
          <p class="tac">暂无满足条件的新建未完的报价~</p>
          <div class="m-t-10"></div>
      </td>
    </tr>
    @else
    <tr>
      <td colspan="5">
          <div class="m-t-10"></div>
          <p class="tac">暂无新建未完的报价~</p>
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