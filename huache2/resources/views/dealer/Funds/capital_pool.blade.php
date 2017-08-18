@extends('_layout.base_dealer_v2')
@section('css','')

@section('content')
    <div class="custom-content custom-content-with-margin nomargin psr">
        @if($account->avaliable_deposit >= 0)
        <!--正常情况-->
        <div class="yue-content psr">
            <p class="inline-block fl mt10">资金池可提现余额:
            @if($account->avaliable_deposit < 0)
                <span class="juhuang">-￥{{ number_format(-($account->avaliable_deposit),2) }}</span>
            @else
                <span class="juhuang">￥{{ number_format($account->avaliable_deposit,2) }}</span>
            @endif
            </p>
            <div class="control-yue inline-block fr">
                <a href="{{ route('dealer.funds','recharge') }}" class="btn btn-s-md btn-danger sure bt btn-auto">充值明细</a>
                <a href="{{ route('dealer.funds','withdrawal') }}" class="btn btn-s-md btn-danger sure bt btn-auto">提现明细</a>
            </div>
            <div class="clear"></div>
        </div>
        @elseif($overdraftLog['is_day'] ==1)
        <!--0>资金池可提现余额>= —授信额度金额-->
        <div class="yue-content psr">
            <p class="inline-block fl mt10">资金池可提现余额:
            @if($account->avaliable_deposit < 0)
                <span class="juhuang">-￥{{ number_format(-($account->avaliable_deposit),2) }}</span>
            @else
                <span class="juhuang">￥{{ number_format($account->avaliable_deposit,2) }}</span>
            @endif
            </p>
            <div class="countdown-wrapper">
                <div class="fs14 tac"  >
                    <span class="fs14 fl mt5 countdown">透支剩余时间</span>
                    <div class="time m-t-10 fl inline-block ml10">
                        <div class="jishi jishi2 countdown inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                        <span class="text inline-block-noi red hide timeout-text">已超时:</span>
                        <div class="jishi jishi2  jishi-out timeout hide inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="control-yue inline-block fr">
                <a href="{{ route('dealer.funds','recharge') }}" class="btn btn-s-md btn-danger sure bt btn-auto">充值明细</a>
                <a href="{{ route('dealer.funds','withdrawal') }}" class="btn btn-s-md btn-danger sure bt btn-auto">提现明细</a>
            </div>
            <div class="clear"></div>
        </div>
        @else
        <!--资金池可提现余额< —授信额度金额-->
        <div class="yue-content psr">
            <p class="inline-block fl mt10">资金池可提现余额:
            @if($account->avaliable_deposit < 0)
                <span class="red">-￥{{ number_format(-($account->avaliable_deposit),2) }}</span>
            @else
                <span class="red">￥{{ number_format($account->avaliable_deposit,2) }}</span>
            @endif
            </p>
            <div class="countdown-wrapper">
                <div class="fs14 tac"  >
                    <span class="fs14 fl mt5 countdown">资金不足已有</span>
                    <div class="timeout time mt5 fl inline-block ml10 red">
                        <span></span>
                        <span>0</span>
                        <span class="fuhao">天</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">小时</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">分</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">秒</span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="control-yue inline-block fr">
                <a href="{{ route('dealer.funds','recharge') }}" class="btn btn-s-md btn-danger sure bt btn-auto">充值明细</a>
                <a href="{{ route('dealer.funds','withdrawal') }}" class="btn btn-s-md btn-danger sure bt btn-auto">提现明细</a>
            </div>
            <div class="clear"></div>
        </div>
        @endif
        <!--查询列表-->
        <div class="yue-content psr">
            <hr class="dashed">
            <p class="pre-fix mt20"><span class="weight ml10">明细流水</span></p>
            <div class="m-t-10"></div>
            {!! Form::open(['url'=>route('dealer.funds','capitalpool'),'method'=>'get','role'=>'form']) !!}
            <div class="search-area" v-cloak>
                <span class="fl mt5">发生时间：</span>
                <div class="form-group psr fl ml5">
                    <input @focus="selectStartTime" name="start_date" v-model="startTime" :value="startTime" type="text" placeholder="" class="form-control" >
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span class="ml10 fl mt5">~</span>
                <div class="form-group psr ml10 fl">
                    <input @focus="selectEndTime" name="end_date" v-model="endTime" :value="endTime" type="text" placeholder="" class="form-control">
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span v-cloak @click="setBaseMonth(time)" v-for="(time,i) in simpleTimeList" class="time-simple-select fl ml10" :class="{'gray-bg':time.select,ml20:i==0}">@{{time.txt}}</span>
                <div class="clear"></div>
                <div class="mt10"></div>
                <span class="fl mt5 ml30">项目：</span>
                <input  type="text" name="item" value="{{ $search['item'] }}" placeholder="可输入关键词" class="form-control w179 fl" >
                <span class="fl mt5 ml20">说明：</span>
                <input  type="text" name="remark" value="{{ $search['remark'] }}" placeholder="可输入关键词" class="form-control w179 fl" >
                <button type="submit" class="fl ml20 btn btn-s-md btn-danger detial bt mt-2">查找</button>
                <a href="{{ route('dealer.funds','capitalpool') }}" class="fl ml20 btn btn-s-md btn-danger detial bt sure mt-2">重置</a>
            </div>
            {!! Form::close() !!}
            <div class="clear"></div>
        </div>


        <div class="clear"></div>
        @if($pages->total() <=0)
        <!--没有记录-->
        <table class="tbl tbl-time tbl-gray">
            <tr>
                <th width="248" class="fs16">发生时间</th>
                <th width="140" class="fs16">项目</th>
                <th width="281" class="fs16">说明</th>
                <th width="116" class="fs16">收支金额</th>
                <th width="110" class="fs16">可提现余额</th>
            </tr>
            <tr>
                <td colspan="5">
                    <div class="mt20"></div>
                    <p class="p fs14 tac">在选择的范围内并未有任何的交易记录~</p>
                    <div class="mt20"></div>
                </td>
            </tr>
        </table>
        @else
        <!--一年以上的交易记录，能查看到的记录只保留1年前该日的余额-->
        <table class="tbl tbl-time tbl-gray">
            <tr>
                <th width="248" class="fs16">发生时间</th>
                <th width="140" class="fs16">项目</th>
                <th width="281" class="fs16">说明</th>
                <th width="116" class="fs16">收支金额</th>
                <th width="110" class="fs16">可提现余额</th>
            </tr>
            @foreach($pages->items() as $key => $item)
            <tr>
                <td>
                    @if($key ==0 && $pages->currentPage() ==1)
                    <div class="time-tag psr">
                        <span></span>
                        <p class="p fs14 ml20">{{ $item->updated_at }}</p>
                    </div>
                    @else
                        <p class="p fs14 ml20">{{ $item->updated_at }}</p>
                    @endif
                </td>
                <td>
                    <p class="p fs14">{{ $item->item }}</p>
                </td>
                <td>
                    <p class="p fs14">@if($item->remark =='正在办理')
                   <span class="juhuang">{{ $item->remark }}</span>
                    @else
                     {{ $item->remark }}
                    @endif
                    </p>
                </td>
                <td>
                    <p class="p fs14">
                        <span class="fl">{{ $item->money_type }}</span><span class="fr">￥{{ number_format($item->money,2) }}</span>
                        <span class="clear"></span></p>
                </td>
                <td>
                    <p class="p fs14 tar">
                    @if($item->credit_avaiable < 0)
                    -￥{{ number_format(-($item->credit_avaiable),2) }}
                    @else
                    ￥{{ number_format($item->credit_avaiable,2) }}
                    @endif
                    </p>
                </td>
            </tr>
            @endforeach
            @if($gtYear['money'] > 0)
            <tr>
                <td>
                    <p class="p fs14 ml20">{{ $gtYear['dateTime'] }}</p>
                </td>
                <td>
                    <p class="p fs14">/ </p>
                </td>
                <td>
                    <p class="p fs14">/ </p>
                </td>
                <td>
                    /
                </td>
                <td>
                    <p class="p fs14 tar">￥{{ number_format($gtYear['money'],2) }}</p>
                </td>
            </tr>
             @endif
        </table>
        @endif
        <div class="pageinfo mauto wp100 tac">
            {{ $pages->appends($pageData)->links() }}
            <div class="clear"></div>
        </div>
        <div class="clear "></div>
        <div class="mt20"></div>
        <div class="clear"></div>
        <table class="fs14 mt20">
            <tr>
                <td valign="top" width="70">温馨提示：</td>
                <td>
                    <div class="tal">
                        <p>1.您可查询最近一年的账务明细。</p>
                        <p>2.选择的时间不能超过当前时间，且结束时间不能早于开始时间。</p>
                        <p>3.申请提现后，提现金额从可用余额中立即扣减，如办理不成功将自动退回。</p>
                    </div>
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
    <script src="{{asset('/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-pool", "/webhtml/custom/js/module/common/common"],function(v,u,c){
            @if($account->avaliable_deposit < 0)
                @if($overdraftLog['is_day'] ==1)
                    u.init($(".countdown"),null,"{{ $overdraftLog['start_date'] }}","{{ $overdraftLog['end_date'] }}",function(){
                        window.location.reload();
                    });
                @else
                    u.init($(".timeout"),"timeout","{{ $overdraftLog['start_date'] }}","{{ $overdraftLog['end_date'] }}",function(){
                        //console.log("timeout end")
                    });
                @endif
            @endif

            u.initEndTime("{{ $search['end_date'] }}","{{ $search['start_date'] }}");
            u.initSimpleSelect({{ $search['thisDate'] }});

            $(".slide").resize(function(){
                console.log("...")
            })
        })
    </script>
@endsection