@extends('_layout.base_dealer_v2')
@section('css','')
@section('nav')

@endsection

@section('content')
    <div class="custom-content custom-content-with-margin  psr">
        <p class="fs14 blue mt20">提交充值凭证</p>
        <hr class="dashed">
        <div class="tac">
            <div class="mt50">
                <h1 class="success-title">
                    <table class="mauto">
                        <tr>
                            <td><span class="icon-large icon-error-large h38 mt5"></span></td>
                            <td valign="top"><span class="inline-block ml20 weight fs18 juhuang mt-10">抱歉，本次提交充值凭证未成功！</span></td>
                        </tr>
                    </table>
                </h1>
                <div class="m-t-10"></div><div class="m-t-10"></div>

            </div>


            <p class="tac mt50">
                <a href="{{ route('dealer.funds','recharge_voucher') }}" class="btn btn-s-md btn-danger sure fs16 ">重新提交</a>
                <a href="{{ route('dealer.funds','capitalpool') }}" class="btn btn-s-md btn-danger fs16 sure ml50">查看可用余额</a>
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