@extends('_layout.base_dealer_v2')
@section('css','')
@section('nav')
@endsection

@section('content')
    <div class="custom-content custom-content-with-margin nomargin psr">
        <div class="yue-content psr">
            <p class="pre-fix mt20"><span class="weight ml10">资金信息</span></p>
            <div class="m-t-10"></div>
            <p class="fs16">
                <span>总资产：<b>￥{{ number_format($account->total_deposit+$account->temp_deposit+$account->jxbTotal+$account->basic_deposit,2) }}</b></span>
                <!--0>资金池可提现余额>= —授信额度金额时
                <span>总资产：<b class="juhuang">￥69,186.95</b></span>-->
                <!--资金池可提现余额< —授信额度金额时
                <span>总资产：<b class="red">￥69,186.95</b></span>-->
                <span class="ml200">平台授信额度：<b>￥{{ number_format($account->credit_line,2) }}</b></span>
            </p>
            <p class="fs14 ml10">
                固定保证金：￥{{ number_format($account->basic_deposit,2) }}
            </p>
            <p class="fs14 ml10">
                <span class="w300">资金池可提现余额：
                @if($account->avaliable_deposit < 0)
                -￥{{ number_format(-($account->avaliable_deposit),2) }}
                @else
                ￥{{ number_format($account->avaliable_deposit,2) }}
                @endif
                </span>
                <a href="{{ route('dealer.funds','capitalpool') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">余额明细</a>
                <a href="{{ route('dealer.funds','recharge') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">充值明细</a>
                <a href="{{ route('dealer.funds','withdrawal') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">提现明细</a>
            </p>
            <p class="fs14 ml10">
                     <span class="w300">
                     @if($account->temp_deposit >0)
                        临时冻结可提现余额：￥{{ number_format($account->temp_deposit,2) }}
                     @endif
                     </span>
                     <span class="ml20" style="width: 82px;display:inline-block; margin-right: 5px;">&nbsp;</span>
                     <a href="{{ route('dealer.funds','recharge_voucher') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">> 去充值</a>
                    <a href="{{ route('dealer.funds','application') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">> 去提现</a>
            </p>
            <p class="fs14 ml10">
                <span class="w300">加信宝浮动保证金：￥{{ number_format($account->jxbTotal,2) }}</span>
                <a href="{{ route('dealer.funds','pay') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">查看</a>
            </p>
            <div class="clear"></div>
            <p class="pre-fix mt20">
                <span class="weight w300 ml10">结算信息</span>
                <a href="{{ route('dealer.funds','settlement') }}" class="btn btn-s-md btn-danger sure bt btn-auto ml20">查看</a>
            </p>
            <div class="m-t-10"></div>
            <p class="fs14 mb20">
                <span class="w300">结算文件剩余数：{{$filecount}}</span>
            </p>
            <p class="fs14">
                <span class="w100">2017-06</span>
                <span class="w300">预计结算总金额：￥//TODO</span>
            </p>
            @if($last_settlement)
            <p class="fs14">
                <span class="w100">{{$last_settlement->year_month}}</span>
                <span class="w300">服务费收入：￥{{$last_settlement->service_income}}</span>
            </p>
            @endif
            <div class="clear"></div>
        </div>


        <div class="clear "></div>
        <div class="mt20"></div>
        <div class="clear"></div>


        <div class="m-t-10" v-for="i in 5"></div>
    </div>
@endsection
@section('js')

    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-overview", "/webhtml/custom/js/module/common/common"],function(v,u,c){
            u.init($(".countdown"),null,"2017-5-3 10:15:19","2017-5-3 19:35:29",function(){
                console.log("countdown end")
            })
            u.init($(".timeout"),"timeout","2017-5-3 19:35:29","2017-5-3 10:15:19",function(){
                console.log("timeout end")
            })
        })
    </script>
@endsection