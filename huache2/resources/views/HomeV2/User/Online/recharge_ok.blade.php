@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('/webhtml/user/themes/user.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.header2')
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p><b class="blue">充值</b></p>
            <div class="box box-border top-border  fs14 p20">
                <div class="tac">
                    <div class="success-title wauto mauto inline-block mt50">
                        <span class="icon-large icon-success-large fl"></span>
                        <span class="inline-block ml20 fl">恭喜您充值成功！</span>
                        <div class="clear"></div>
                    </div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="wauto inline-block mauto fs14">
                        <p>
                            <span class="">本次充值金额：￥{{number_format($money,2)}}</span><span class="ml50">提交时间：{{$time}}</span>
                        </p>
                    </div>
                    <p class="tac mt50">
                        <a href="/member/pay/online" class="btn btn-s-md btn-danger fs16 sure">继续充值</a>
                        <a href="/member/balance" class="btn btn-s-md btn-danger fs16 ml50">查看可用余额</a>
                        <a href="/" class="btn btn-s-md btn-danger fs16 sure ml50">马上购车</a>
                    </p>
                </div>
                    <input type="hidden"  name="paymethod" value="{{$paymethod}}">
                    <input type="hidden" name="is_success" value="1">
                    <input type="hidden" name="money" value="{{ number_format($money) }}">




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
@section('js')

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base",  "/js/module/common/common"],function(v,u,c){
        })
    </script>
@endsection
