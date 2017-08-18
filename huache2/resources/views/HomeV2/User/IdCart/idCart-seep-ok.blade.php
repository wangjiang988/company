@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin marign-top-tab psr">
        @include('HomeV2._layout.myfile_nav')
        <div class="mt10"></div>
        <div class="content-wapper">
            <br><br>
            <h1 class="success-title"><span class="icon-large icon-success-large"></span><span class="inline-block ml20">认证成功</span></h1>
            <div class="clear"></div>
            <br><br>
            <p class="tac mt20">本账号只属于{{ $user->last_name.$user->first_name }} （{{ chanageStr($user->id_cart,4,-4) }}）</p>
            <div class="m-t-10" v-for="i in 12"></div>

        </div>

    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-file-submit-bank", "/webhtml/user/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection