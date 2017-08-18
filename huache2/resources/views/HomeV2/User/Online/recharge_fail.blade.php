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
                    <div class="mt50">
                        <h1 class="success-title">
                            <table class="mauto">
                                <tr>
                                    <td><span class="icon-large icon-error-large"></span></td>
                                    <td><span class="inline-block ml20 weight fs18 juhuang">抱歉，充值失败！</span></td>
                                </tr>
                            </table>
                        </h1>
                        <div class="m-t-10"></div><div class="m-t-10"></div>

                    </div>

                    <div class="wauto inline-block mauto fs14">
                        <p>本次未成功金额：￥{{number_format($money,2)}}</p>
                        <p>>>   请重新尝试，如有疑问，请参考<a href="#" class="juhuang">支付帮助</a></p>
                    </div>
                    <p class="tac mt50">
                        <a href="/member/pay/online" class="btn btn-s-md btn-danger fs16 ">重新充值</a>
                        <a href="/member/balance" class="btn btn-s-md btn-danger fs16 sure ml50">查看可用余额</a>
                    </p>
                </div>
                <input type="hidden"   name="paymethod" value="{{$paymethod}}">
                <input type="hidden"   name="is_success" value="0">
                <input type="hidden"   name="money"  value="{{ number_format($money) }}">


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
