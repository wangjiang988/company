@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin  psr">

        <h2 class="title"><span class="pl10 juhuang weight fs14 pre-fix">提现记录  >  提现详情</span></h2>
        <div class="box mt10">
            @if($withdraw->recharge_type==2)
                @include("HomeV2.User.Withdrawal.tx_bank")
            @else
                @include("HomeV2.User.Withdrawal.tx_alipay")
            @endif
              <div class="clear "></div>
        <div class="m-t-10" v-for="i in 3"></div>
        <p class="tac">
            <a href="javascript:history.go(-1);" class="btn btn-danger sure">返回</a>
        </p>
        <div class="m-t-10" v-for="i in 5"></div>

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