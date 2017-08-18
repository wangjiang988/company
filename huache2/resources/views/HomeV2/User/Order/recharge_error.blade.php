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
                    <div class="mt50">
                        <h1 class="success-title">
                            <table class="mauto">
                                <tr>
                                    <td><span class="icon-large icon-error-large"></span></td>
                                    <td><span class="inline-block ml20 weight fs18 juhuang">抱歉，本次提交银行转账凭证未成功！</span></td>
                                </tr>
                            </table>
                        </h1>
                        <div class="m-t-10"></div><div class="m-t-10"></div>

                    </div>

                    <div class="wauto inline-block mauto fs14">
                        <p>本次未成功金额：￥{{ number_format($money) }}</p>
                        <p>>>   请重新尝试，如仍有问题，请<a href="#" class="juhuang">联系我们</a></p>
                    </div>
                    <p class="tac mt50">
                        <a href="{{ route('pay.recharge',['voucher','order_id'=>$order_id]) }}" class="btn btn-s-md btn-danger sure fs16 ">重新提交</a>
                        <a href="{{ route('my.myBalance') }}" class="btn btn-s-md btn-danger fs16 sure ml50">查看可用余额</a>
                    </p>
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