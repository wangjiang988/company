@extends('_layout.base_dealer_v2')
@section('css','')

@section('content')
    <div class="custom-content custom-content-with-margin nomargin psr">

        <div class="yue-content psr">
            <a href="{{ route('dealer.funds','recharge_voucher') }}" class="btn btn-danger sure tbl-control psa btn-control btn-auto">> 去充值</a>
            <p class="pre-fix"><span class="ml10 juhuang weight">充值记录</span></p>
            {!! Form::open(['url'=>route('dealer.funds','recharge'),'method'=>'get','role'=>'form']) !!}
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="search-area">
                <span>提交时间</span>
                <div class="form-group psr ml10">
                    <input @focus="selectStartTime" name="start_date" v-model="startTime" :value="startTime" type="text" placeholder="" class="form-control " >
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span class="ml5">~</span>
                <div class="form-group psr ml5">
                    <input @focus="selectEndTime" name="end_date" v-model="endTime" :value="endTime" type="text" placeholder="" class="form-control">
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span class="ml20">入账状态</span>
                <drop-down def-value="{{ $search['status'] }}" id="into-way-stauts" name="status" @receive-params="getInfoWayStatus"  :list="intoWayList" class="ml5 nofl" class-name="btn-dropdown-normal"></drop-dwon>

            </div>
            <div class="search-area psr">
                <span>入账时间</span>
                <div class="form-group psr ml10">
                    <input @focus="selectStartTime2" name="start_create" v-model="startTime2" :value="startTime2" type="text" placeholder="" class="form-control " >
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span class="ml5">~</span>
                <div class="form-group psr ml5">
                    <input @focus="selectEndTime2" name="end_create" v-model="endTime2" :value="endTime2" type="text" placeholder="" class="form-control">
                    <i class="rili" @click="prevFocus"></i>
                </div>

                <button type="submit" class="ml20 btn btn-danger bt">查询</button>
                <a href="{{ route('dealer.funds','recharge') }}" class="ml5 btn btn-danger sure bt">重置</a>
                {!! Form::close() !!}
            </div>

            <div class="clear"></div>

        </div>


        <table class="tbl tbl-time tbl-gray">
            <tr>
                <th width="88" class="fs16">工单编号</th>
                <th width="100" class="fs16">提交时间</th>
                <th width="100" class="fs16">提交金额</th>
                <th width="110" class="fs16">提交凭证</th>
                <th width="112" class="fs16">充值方式</th>
                <th width="87" class="fs16">入账金额</th>
                <th width="100" class="fs16">确认<br>入账时间</th>
                <th width="95" class="fs16">入账状态</th>
            </tr>

            @if($pages->total() >0)
                @foreach($pages->items() as $key => $item)
                    @if($item->status==1)
                        <tr class="p-gray">
                    @else
                        <tr>
                            @endif
                            <td>
                                <p class="p fs14 tac">{{ $item->drb_id }}</p>
                            </td>
                            <td>
                                <p class="p fs14 tac"> {{ $item->created_at }}</p>
                            </td>
                            <td>
                                <p class="p fs14 tar">￥{{ number_format($item->money,2) }}</p>
                            </td>
                            <td>
                                <p class="p fs14">
                                    @if($item->voucher)
                                        <a href="{{ $item->voucher }}" target="_blank"><img src="{{ $item->voucher }}" width="25" alt="" /></a>
                                        {{ chanageStr($item->voucher,3,-3)}}
                                    @endif
                                </p>
                            </td>
                            <td>
                                <p class="p fs14">银行转账—{{chanageStr($bank->seller_bank_account,0,-4,'***')}} {{chanageStr($bank->sellerName,3,strlen($bank->sellerName),'***')}}</p>
                            </td>
                            <td>
                                <p class="p fs14 tar">
                                    @if(in_array($item->kefu_confirm_status,[2,3]))
                                        ￥{{ number_format($item->kefu_confirm_money,2) }}
                                    @elseif($item->kefu_confirm_status ==4)
                                         ￥0
                                    @endif
                                </p>
                            </td>
                            <td>
                                @if(in_array($item->kefu_confirm_status,[2,3,4]))<p class="p fs14 tac">{{ $item->updated_at }}</p>@endif
                            </td>
                            <td>
                                <p class="p fs14 tac">
                                    @if(in_array($item->kefu_confirm_status,[0,1]))
                                        正在核实
                                    @elseif(in_array($item->kefu_confirm_status,[2,3]))
                                        已入账
                                    @elseif($item->kefu_confirm_status==4)
                                        无此款项
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    <div class="mt20"></div>
                                    <p class="p fs14 tac">在选择的范围内未发现任何的充值记录~</p>
                                    <div class="mt20"></div>
                                </td>
                            </tr>
                        @endif
        </table>
        <br>

        <div class="pageinfo mauto wp100 tac">
            {{ $pages->appends($pageData)->render() }}
        </div>
        <div class="clear "></div>
        <div class="m-t-10" v-for="i in 5"></div>
        <table>
            <tr>
                <td width="80" valign="top">温馨提示：</td>
                <td>
                    <p>1.您可查询最近一年的账务明细。</p>
                    <p>2.选择的时间不能超过当前时间，且结束时间不能早于开始时间。</p>
                </td>
            </tr>
        </table>
        <div class="m-t-10" v-for="i in 5"></div>

    </div>
@endsection

@section('js')
    <style>
        .pageinfo * {
            margin-right: 0px; float: none;
        }
        .pagination{margin: 20px auto;}
    </style>

    <script src="{{ asset('/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js') }}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-top-up", "/webhtml/custom/js/module/common/common"],function(v,u,c){
            u.initEndTime("{{ $search['end_date'] }}","{{ $search['start_date'] }}","{{ $search['end_create'] }}","{{ $search['start_create'] }}");
        })
    </script>
@endsection