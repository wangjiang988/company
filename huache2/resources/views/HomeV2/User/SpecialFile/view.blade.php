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
            <h1 class="success-title">
                <table class="mauto">
                    <tr>
                        <td><span class="icon-large icon-error-large"></span></td>
                        <td><span class="inline-block ml20">待审核</span></td>
                    </tr>
                </table>
            </h1>

            <p class="juhuang tac fs16">客官，感谢您的提交，文件已在审核的路上，请您耐心等待哦~</p>
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