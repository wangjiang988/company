@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">验证邮箱</span><div class="mt5"></div></h4>
        <div class="content-wapper ">
            <div class="hd">
                <ul>
                    <li><span>1</span><label>验证身份</label></li>
                    <li><span>2</span><label>验证邮箱</label></li>
                    <li class="cur"><span>3</span><label class="juhuang">完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>

            <div class="mt60">
                <h1 class="success-title"><span class="icon-large icon-success-large"></span><span class="inline-block ml20">恭喜您，修改成功！</span></h1>
                <div class="m-t-10"></div>
                <p class="tac">验证的邮箱{{ changeEmail($user->email) }}</p>
                <div class="m-t-10"></div>
                <p class="tac"><a href="{{ route('user.safe') }}" class="btn btn-s-md btn-danger fs16 sure">确认</a></p>
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
            u.init('{{ route('user.safe') }}');
        })
    </script>
@endsection