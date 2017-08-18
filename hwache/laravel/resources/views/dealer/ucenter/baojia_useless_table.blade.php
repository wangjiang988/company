<table class="tbl tbl-blue wp100 mt20" id="bj-list">
    <tbody>
        <tr>
            <th class="tac" width="99"><span>报价编号</span></th>
            <th class="tac" width="198"><span>报价车型</span></th>
            <th class="tac" width="160"><span>失效时间</span></th>
            <th class="tac" width="168"><span>失效原因</span></th>
            <th class="tac" width="150"><span>操作</span></th>
        </tr>
        @if($list->count())
        @foreach($list as $l)
            <tr data-status="2">
                <td class="tac"><a href="/dealer/baojia/view/{{$l['bj_id']}}/1" class="juhuang tdu"> {{$l['bj_serial']}}</a></td>
                <td class="tac"><span>{{str_replace('&gt;','',$l['gc_name'])}}</span></td>
                <td class="tac"><span>{{$l['bj_update_time']}}</span></td>
                <td class="tac"><span>{{$l['bj_reason']}}<br></span></td>
                <td class="tac">
                    <a href="javascript:baojiaExcute('copy',{{$l['bj_id']}})" class="juhuang tdu">复制</a>
                </td>
            </tr>
        @endforeach
        @else
        <tr>
            <td colspan="5">
                <div class="m-t-10"></div>
                <p class="tac">暂无失效报价~</p>
                <div class="m-t-10"></div>
            </td>
        </tr>
    @endif
    </tbody>
</table>

<div class="tac">{!!$list->render()!!}</div>