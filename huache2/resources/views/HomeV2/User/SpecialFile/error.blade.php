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
            <div class="tac mauto">
                <h1 class="error-large fs18"><span class="ml10 juhuang">很遗憾，该文件好像并不需要，如有疑问请联系客服，谢谢~</span></h1>
            </div>
            @include('HomeV2._layout.special_temp')

        </div>
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

        })
    </script>
@endsection