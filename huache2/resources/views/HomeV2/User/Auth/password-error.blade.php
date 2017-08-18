@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">修改密码</span><div class="mt5"></div></h4>
        <div class="content-wapper ">

            <div class="m-t-10" v-for="i in 3"></div>
            <div class=" mt60">
                <h1 class="success-title">
                    <table class="mauto">
                        <tr>
                            <td><span class="icon-large icon-error-large"></span></td>
                            <td><span class="inline-block ml20">对不起，本次修改登录密码失败！</span></td>
                        </tr>
                    </table>
                </h1>
                <div class="m-t-10"></div><div class="m-t-10"></div>
                <p class="tac">
                    <a href="{{ route('user.safe') }}" class="btn btn-s-md btn-danger fs16 sure">返回</a>
                    <a href="{{ route('mobile.verify') }}" class="btn btn-s-md btn-danger fs16 sure ml50">重新修改</a>
                </p>
                <p class="tac"><span class="juhuang">@{{simpleCountDown}}</span>秒后自动跳转账户安全页面</p>
            </div>
            <div class="m-t-10" v-for="i in 15"></div>
        </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-phone-step3", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init('{{ route('mobile.verify') }}');
        })
    </script>
@endsection