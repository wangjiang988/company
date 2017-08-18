@extends('_layout.base_dealer_v2')
@section('css','')
@section('nav')

@endsection

@section('content')
    <div class="custom-content custom-content-with-margin  psr">
        <p class="fs14 blue mt20">提交充值凭证</p>
        <hr class="dashed">
        <div class="tac">
            <div class="success-title wauto mauto inline-block mt50">
                <span class="icon-large icon-success-large fl"></span>
                <span class="inline-block ml20 fl weight juhuang">恭喜您成功提交！</span>
                <div class="clear"></div>
            </div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="wauto inline-block mauto fs14">
                <p><span class="">本次提交金额：￥{{ number_format($success->money,2) }}</span><span class="ml50">提交时间：{{ $success->created_at }}</span>
                </p>
                <p>华车将在核实该笔入账后更新入账信息，请留意您的可用余额变化。</p>
            </div>
            <p class="tac mt50">
                <a href="{{ route('dealer.funds','recharge_voucher') }}" class="btn btn-s-md btn-danger fs16 sure">继续提交</a>
                <a href="{{ route('dealer.funds','recharge') }}" class="btn btn-s-md btn-danger fs16 ml50">查看充值记录</a>

            </p>
        </div>

        <div class="m-t-10" v-for="i in 15"></div>

    </div>
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue.min","", "module/common/common"],function(v,u,c){
            u.init();
        })
    </script>
@endsection