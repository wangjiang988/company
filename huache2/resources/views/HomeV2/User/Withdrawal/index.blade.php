@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin  psr">

        <div class="yue-content psr">
            <span class="juhuang weight pl10 fl pre-fix">提现记录</span>
            <a href="{{ route('Withdrawal.Line') }}" class="fr ml10 btn-gray" target="_blank">提现路线</a>
            <a href="{{ route('Withdrawal.Ceiling') }}" class="fr btn-gray" target="_blank">提现额度</a>
            <div class="clear"></div>

            <div class="m-t-10"></div>
            {!! Form::open(['url'=>route('my.Withdrawal'),'method'=>'get']) !!}
            <div class="search-area psr">
                <div class="psa btn-control" style="top:-9px;">
                    <button type="submit" class="ml5 btn btn-danger btn-auto">查找</button>
                    <a href="{{ route('my.Withdrawal') }}" class="ml5 btn btn-danger sure btn-auto">重置</a>
                </div>
                <span>发生时间：</span>
                <div class="form-group psr">
                    <input @focus="selectStartTime" v-model="startTime" :value="startTime" type="text" name="start_date" class="form-control " >
                    <i class="rili"></i>
                </div>
                <span class="ml5">~</span>
                <div class="form-group psr ml5">
                    <input @focus="selectEndTime" v-model="endTime" :value="endTime" type="text" name="end_date" class="form-control">
                    <i class="rili"></i>
                </div>
                <span class="ml5">提现状态</span>
                <drop-down def-value="{{ $search['status'] }}" id="into-way-stauts" name="status" @receive-params="getWithdrawalState"  :list="withdrawalStateList" class="ml5 nofl" class-name="btn-dropdown-normal"></drop-dwon>
            </div>
            {!! Form::close() !!}
        </div>
        <!--没有记录-->
        @if($list->count() <=0)
        <table class="tbl tbl-time">
            <tr>
                <th width="120" class="fs16">申请时间</th>
                <th width="249" class="fs16">提现路线</th>
                <th width="120" class="fs16">提现金额</th>
                <th width="150" class="fs16">
                    <div class="psr">
                        <span>提现付款金额
                            <span class="psa juhuang fs12 ml5 help" @mouseover.prevent.stop="tip(0)" @mouseout.prevent.stop="tip(0)">i</span>
                        </span>
                        <div v-cloak class="tooltip fade bottom-left psa" :class="{in:tips[0].isShow}">
                            <div class="tooltip-arrow"></div>
                            <div class="tooltip-inner">
                                <p class="m0">提现付款金额 = 提现金额 - 银行手续费</p>
                            </div>
                        </div>
                    </div>
                </th>
                <th width="110" class="fs16">提现状态</th>
                <th width="120" class="fs16">状态<br>更新时间</th>
                <th width="110" class="fs16">操作</th>
            </tr>
            <tr>
                <td colspan="7">
                    <div class="mt20"></div>
                    <p class="p fs14">客官，暂无符合您查找条件的提现记录~~</p>
                    <div class="mt20"></div>
                </td>
            </tr>
        </table>
        <br>
        @else
        <table class="tbl tbl-time">
            <tr>
                <th width="120" class="fs16">申请时间</th>
                <th width="249" class="fs16">提现路线</th>
                <th width="120" class="fs16">提现金额</th>
                <th width="150" class="fs16">
                    <div class="psr">
                        <span>提现付款金额
                            <span class="psa juhuang fs12 ml5 help" @mouseover.prevent.stop="tip(0)" @mouseout.prevent.stop="tip(0)">i</span>
                        </span>
                        <div v-cloak class="tooltip fade bottom-left psa" :class="{in:tips[0].isShow}">
                            <div class="tooltip-arrow"></div>
                            <div class="tooltip-inner">
                                <p class="m0">提现付款金额 = 提现金额 - 银行手续费</p>
                            </div>
                        </div>
                    </div>
                </th>
                <th width="110" class="fs16">提现状态</th>
                <th width="120" class="fs16">状态<br>更新时间</th>
                <th width="110" class="fs16">操作</th>
            </tr>
            @foreach($list->items() as $key =>$item)
            <tr>
                <td>
                    <div class="time-tag psr">
                        <p class="p fs14">{{ $item->created_at }}</p>
                    </div>
                </td>
                <td>
                    <p class="p fs14">{{ $item->line_name }}</p>
                </td>
                <td>
                    <p class="p fs14">￥{{ number_format($item->money,2) }}</p>
                </td>
                <td>
                    <p class="p fs14">￥{{ number_format($item->true_money,2) }}</p>
                </td>
                <td>
                    <p class="p fs14">
                   {{show_withdraw_status($item->status)}}
                    </p>
                </td>
                <td>
                    <p class="p fs14">
                        @if(in_array($item->status,[1,5,6]))
                        {{ $item->updated_at }}
                        @endif
                    </p>
                </td>
                <td>
                    <p class="p fs14">
                        <a href="{{ route('Withdrawal.Detail',['id'=>$item->uw_id]) }}" class="btn btn-danger sure tbl-control btn-auto">查看</a>
                    </p>
                </td>
            </tr>
            @endforeach
        </table>
        @endif
        <div class="pageinfo ml200">
            {{ $list->render() }}
        </div>
        <div class="clear "></div>
        <div class="m-t-10" v-for="i in 5"></div>
        <table>
            <tr>
                <td width="80" valign="top">温馨提示：</td>
                <td>
                    <p>1.您可查询最近一年您从华车提现的明细记录。</p>
                    <p>2.选择的时间不能超过当前时间，且结束时间不能早于开始时间。</p>
                    <p>3.提现未成功，该提现付款金额自动重新转入可用余额。</p>
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
    <script src="{{ asset('webhtml/common/js/vendor/My97DatePicker/WdatePicker.js') }}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-withdrawal-record", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //*页面加载初始化当前时间
            //不设置initEndTime的时间，会自动调用本地时间（当前时间）
            u.initEndTime("{{ $search['end_date'] }}","{{ $search['start_date'] }}")
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