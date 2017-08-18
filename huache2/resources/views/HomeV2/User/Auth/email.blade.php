@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">修改密码</span><div class="mt5"></div></h4>
        <div class="content-wapper ">

            <div class="hd">
                <ul>
                    <li class="cur"><span>1</span><label class="juhuang">验证身份</label></li>
                    <li><span>2</span><label>修改登录密码</label></li>
                    <li><span>3</span><label>完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            
            {!! Form::open(['url'=>route('account.sendMail'),'class'=>'form-horizontal','role'=>'form']) !!}  
            <div class=" mt60">
                <p class="tac">已验证的邮箱：  {{ changeEmail($user->email) }}</p>
                <div class="m-t-10"></div><div class="m-t-10"></div>
                <p class="tac">
                    {!! Form::hidden('email',$user->email,['id'=>'email']) !!}
                    <a href="javascript:;" @click="sendMail" class="btn btn-s-md btn-danger fs16 ">发送验证邮件</a>
                </p>
                <p class="tac"><a href="{{ route('mobile.verify') }}" class="juhuang">使用已验证手机修改密码>></a></p>
            </div>
            {!! Form::close() !!}
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-email-step1", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init('{{ route('account.sendMail') }}','{{ route('account.checkEmail') }}');
        })
    </script>
@endsection