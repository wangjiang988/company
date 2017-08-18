@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p><b class="blue">充值</b></p>
            <div class="box box-border top-border  fs14 p20">
                <div class="tac">
                    <div class="success-title wauto mauto inline-block mt50">
                        <span class="icon-large icon-success-large fl"></span>
                        <span class="inline-block ml20 fl weight">恭喜您成功提交！</span>
                        <div class="clear"></div>
                    </div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="wauto inline-block mauto fs14">
                        <p><span class="">本次提交金额：￥{{ number_format($money) }}</span><span class="ml50">提交时间：{{ $created_at }}</span>
                        </p>
                        <p>华车将在核实该笔入账后更新入账信息，请留意您的可用余额变化。</p>
                    </div>
                    <p class="tac mt50">
                        <a href="{{ route('pay.recharge',['voucher','order_id'=>$order_id]) }}" class="btn btn-s-md btn-danger fs16 sure">继续提交</a>
                        <a href="{{ route('my.RecordedAt') }}" class="btn btn-s-md btn-danger fs16 ml50">查看我的转入</a>
                        <a href="/" class="btn btn-s-md btn-danger fs16 sure ml50">马上购车</a>
                    </p>

                    <input type="hidden"  name="paymethod" value="4">
                    <input type="hidden" name="is_success" value="1">
                    <input type="hidden" name="money" value="{{ number_format($money) }}">

                </div>




                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>



            </div>
        </div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection