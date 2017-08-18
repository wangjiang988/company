@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin  psr">
        {!! Form::open(['url'=>route('my.RecordedAt'),'method'=>'get']) !!}
        <div class="yue-content psr">
            <p class="pre-fix"><span class="ml10 juhuang weight">转入记录</span></p>
            <div class="m-t-10"></div>
            <div class="search-area">
                <span>提交时间</span>
                <div class="form-group psr">
                    <input @focus="selectStartTime" v-model="startTime" :value="startTime" name="create_start" type="text" placeholder="" class="form-control " >
                    <i class="rili"></i>
                </div>
                <span class="ml5">~</span>
                <div class="form-group psr ml5">
                    <input @focus="selectEndTime" v-model="endTime" :value="endTime" name="create_end" type="text" placeholder="" class="form-control">
                    <i class="rili"></i>
                </div>
                <span class="ml5">转入方式</span>
                <drop-down def-value="{{ $search['pay_status'] }}" id="into-way-stauts" name="pay_status" @receive-params="getInfoWayStatus"  :list="intoWayList" class="ml5 nofl" class-name="btn-dropdown-normal"></drop-dwon>

            </div>
            <div class="search-area psr">
                <div class="psa btn-control">
                    <button type="submit" class="ml5 btn btn-danger btn-auto">查找</button>
                    <a href="{{ route('my.RecordedAt') }}" class="ml5 btn btn-danger sure btn-auto">重置</a>
                </div>
                <span>入账时间</span>
                <div class="form-group psr">
                    <input @focus="selectStartTime2" v-model="startTime2" :value="startTime2" name="start_date" type="text" placeholder="" class="form-control" >
                    <i class="rili"></i>
                </div>
                <span class="ml5">~</span>
                <div class="form-group psr ml5">
                    <input @focus="selectEndTime2" v-model="endTime2" :value="endTime2" name="end_date" type="text" placeholder="" class="form-control">
                    <i class="rili"></i>
                </div>

                <span class="ml5">入账状态</span>
                <drop-down def-value="{{ $search['status'] }}" id="billing-stauts" name="status" @receive-params="getBillingStatus" :list="billingStateList" class="ml5 nofl" class-name="btn-dropdown-normal"></drop-dwon>

            </div>

            <div class="clear"></div>

        </div>
        {!! Form::close() !!}
        <!--没有记录-->
        <br>
        <table class="tbl tbl-time">
            <tr>
                <th width="120" class="fs16">提交时间</th>
                <th width="110" class="fs16">提交金额</th>
                <th width="249" class="fs16">转入方式</th>
                <th width="279" class="fs16">转入用途</th>
                <th width="110" class="fs16">入账金额</th>
                <th width="120" class="fs16">确认<br>入账时间</th>
                <th width="110" class="fs16">操作</th>
            </tr>
            @if($list->total() >0)
                @foreach($list->items() as $key => $item)
                 @if($item->status ==0)<tr class="p-gray">@else<tr>@endif
                <td>
                    @if($key==0)
                        <div class="time-tag psr">
                            <span class="top16"></span>
                            <p class="p fs14">{{ $item->created_at }}</p>
                        </div>
                        @else
                        <p class="p fs14">{{ $item->created_at }}</p>
                    @endif
                </td>
                <td>
                    <p class="p fs14">
                        @if($item->recharge)
                        ￥{{ number_format($item->recharge->money,2)}}
                        @else
                        ￥{{ number_format($item->money,2)}}
                        @endif

                    </p>
                </td>
                <td>
                    <p class="p fs14">
                        {{ $item->remark }}
                    </p>
                </td>
                <td>
                    <p class="p fs14">{{ $item->usage }}</p>
                </td>
                <td>
                    <p class="p fs14">
                    @if($item->recharge)
                        @if( in_array($item->recharge->status,[2,3] ) )
                            ￥{{ number_format($item->recharge->recharge_money,2) }}
                        @elseif( in_array($item->recharge->status,[4] ) )
                            ￥0.00
                        @endif
                    @else
                     ￥{{ number_format($item->money,2)}}
                    @endif
                    </p>
                </td>
                <td>
                    <p class="p fs14"> 
                    @if($item->recharge)
                        @if(in_array($item->recharge->status,[2,3,4]))
                            {{ $item->recharge->recharge_confirm_at }}
                        @endif
                    @else
                      {{ $item->updated_at }}
                    @endif
                       
                    </p>
                </td>
                <td>
                    <p class="p fs14">
                        <a href="{{ route('RecordedAt.detail',['id'=>$item->ua_log_id]) }}" class="btn btn-danger sure tbl-control btn-auto">查看</a>
                    </p>
                </td>
            </tr>
                @endforeach
            @else
                <tr>
                <td colspan="7">
                    <div class="mt20"></div>
                    <p class="p fs14">客官，暂无符合您查找条件的转入记录~</p>
                    <div class="mt20"></div>
                </td>
                </tr>
            @endif
        </table>
        <br>

        <div class="pageinfo wauto mauto tac">
            {{ $list->render() }}
            <div class="clear"></div>
        </div>
        <div class="clear "></div>
        <div class="m-t-10" v-for="i in 5"></div>
        <table>
            <tr>
                <td width="80" valign="top">温馨提示：</td>
                <td>
                    <p>1.您可查询最近一年您主动向华车转入资金的明细记录。</p>
                    <p>2.选择的时间不能超过当前时间，且结束时间不能早于开始时间。</p>
                </td>
            </tr>
        </table>
        <div class="m-t-10" v-for="i in 5"></div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <style>
    .pagination{margin: 0 auto;float: none;}
    .pagination *{margin: 0 auto;}
    </style>
    <script src="{{ asset('webhtml/common/js/vendor/My97DatePicker/WdatePicker.js') }}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-record", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //*页面加载初始化当前时间
            //不设置initEndTime的时间，会自动调用本地时间（当前时间）
            u.initEndTime(
                "{{ $search['createEndDate']}}",
                "{{ $search['createStartDate']}}",
                "{{ $search['end_date']}}",
                "{{ $search['start_date']}}"
            )
            $(".box-border").css({
                'border-right':0,
                'border-bottom':0,
            })
            $(".slide").css({
                'border-bottom':"1px solid #ddd",
            })
        })
    </script>
@endsection