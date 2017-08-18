@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin  psr">
        <div class="yue-content psr">
            <p class="pre-fix"><span class="ml10 juhuang weight">转入记录  >  转入详情</span></p>
            @if($find->pay_type !=1)
                <div class="m-t-10"></div>
                <p class="gray-bg pl5">
                   <?php
                        $payTypes = ['账户余额','支付宝','银行转帐','商家补偿','代金券'];
                        echo $payTypes[$find->pay_type];
                    ?>
                </p>
                <div class="clear"></div>
            @endif
        </div>

        <div class="box">
            @if($find->pay_type==2)
                @include('HomeV2.User.RecordedAt.bank')
            @endif
            <div class="clear"></div>
            @if($find->pay_type==1)
                @include('HomeV2.User.RecordedAt.alipay')
            @endif
        </div>
        <div class="clear "></div>

        <div class="m-t-10" v-for="i in 3"></div>
        <p class="tac">
            <a href="javascript:history.go(-1);" class="btn btn-danger sure">返回</a>
        </p>
        <div class="m-t-10" v-for="i in 5"></div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common"],function(v,u,c){
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