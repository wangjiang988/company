@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin  psr">

        <div class="yue-content psr">
            <p>可用余额：￥{{ number_format($Account->avaliable_deposit,2) }}</p>
            <p class="gray">可用于在华车支付诚意金/买车担保金，不用可全额提现。</p>
            <div class="jxb-wrapper">
                <p>加信宝：￥{{ number_format($Account->PayTotal,2) }} <a href="{{ route('my.hwachePay') }}" class="blue ml10">查看</a></p>
                <p class="gray">当前冻结中的买车担保金。</p>
            </div>
            <div class="control-yue">
                <a href="{{route('pay.online')}}" class="btn btn-s-md btn-danger sure bt btn-auto">充值</a>
                @if($Account->avaliable_deposit >0)
                <a href="{{ route('Withdrawal.Application') }}" class="btn btn-s-md btn-danger sure bt btn-auto">提现</a>
                @else
                    <a href="javascript:;" class="btn btn-s-md btn-danger btn-disabled bt btn-auto">提现</a>
                @endif
                <!--提现余额为零 显示 btn-disabled按钮-->
            </div>
            <hr class="dashed">
            <p class="pre-fix"><span class="juhuang weight ml10">交易明细</span></p>
            <div class="m-t-10"></div>
            {!! Form::open(['url'=>route('my.myBalance'),'method'=>'get','role'=>'form']) !!}
            <div class="search-area"  v-cloak>
                <span class="fl mt5">发生时间：</span>
                <div class="form-group psr fl ml5">
                    <input @focus="selectStartTime" v-model="startTime" :value="startTime" name="start_date" type="text" placeholder="" class="form-control " >
                    <i class="rili"></i>
                </div>
                <span class="ml10 fl mt5">~</span>
                <div class="form-group psr ml10 fl">
                    <input @focus="selectEndTime" v-model="endTime" :value="endTime" name="end_date" type="text" placeholder="" class="form-control">
                    <i class="rili"></i>
                </div>
                <button type="submit" class="fl ml20 btn btn-s-md btn-danger detial bt">查找</button>
                <span v-cloak @click="setBaseMonth(time)" v-for="(time,index) in simpleTimeList" class="time-simple-select fl mt5" :class="{'gray-bg':time.select}">@{{time.txt}}</span>
            </div>
            {!! Form::close() !!}
        </div>
        @if($logs->total() <=0)
        <!--没有记录-->
        <table class="tbl tbl-time">
            <tr>
                <th width="248" class="fs16">发生时间</th>
                <th width="140" class="fs16">项目</th>
                <th width="281" class="fs16">说明</th>
                <th width="116" class="fs16">收支金额</th>
                <th width="110" class="fs16">可用余额</th>
            </tr>
            <tr>
                <td colspan="5">
                    <div class="mt20"></div>
                    <p class="p fs14">客官，上述时间段无交易记录哦~</p>
                    <div class="mt20"></div>
                </td>
            </tr>
        </table>
        <br>
        @else

        <table class="tbl tbl-time">
            <tr>
                <th width="248" class="fs16">发生时间</th>
                <th width="140" class="fs16">项目</th>
                <th width="281" class="fs16">说明</th>
                <th width="116" class="fs16">收支金额</th>
                <th width="110" class="fs16">可用余额</th>
            </tr>
            @foreach($logs->items() as $key => $item)
            <tr>
                <td>
                    @if($key ==0)
                    <div class="time-tag psr">
                        <span></span>
                        <p class="p fs14">{{ $item->created_at }}</p>
                    </div>
                    @else
                        <p class="p fs14">{{ $item->created_at }}</p>
                    @endif
                </td>
                <td>
                    <p class="p fs14">{{ $item->item }}</p>
                </td>
                <td>
              
                    @if($item->status ==0)
                    <span class="">{{ $item->remark }}</span>
                    @else
                    <p class="p fs14">{{ $item->remark }}</p>
                    @endif
                </td>
                <td>
                    <p class="p fs14">
                    <span class="fl ml10 weight">{{ $item->money_type }}</span>
                    <span class="fr">
                    ￥{{ number_format($item->money,2) }}
                    </span>
                    <div class="clear inline-block"></div>
                    </p>
                </td>
                <td>
                    <p class="p fs14 tar">￥{{ number_format($item->credit_avaliable,2) }}</p>
                </td>
            </tr>
            @endforeach
            @if(isset($gtYear))
                <tr>
                    <td>
                        <p class="p fs14">{{ $gtYear->dateTime }}</p>
                    </td>
                    <td>
                        <p class="p fs14">/</p>
                    </td>
                    <td>
                        <p class="p fs14">/</p>
                    </td>
                    <td>
                        <p class="p fs14">/</p>
                    </td>
                    <td>
                        <p class="p fs14">￥{{ number_format($gtYear->money,2) }}</p>
                    </td>
                </tr>
            @endif
        </table>
        @endif
        <div class="pageinfo wauto mauto tac">
            {{ $logs->render() }}
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

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <style>
    .pagination{margin: 0 auto;float: none;}
    .pagination *{margin: 0 auto;}
    </style>
    <script src="{{asset('webhtml/common/js/vendor/My97DatePicker/WdatePicker.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-balance", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //*页面加载初始化当前时间
            //不设置initEndTime的时间，会自动调用本地时间（当前时间）
            u.initEndTime("{{ $search['end_date'] }}","{{ $search['start_date'] }}",{{(strtotime($search['end_date'])-strtotime($search['start_date']))/86400}});
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