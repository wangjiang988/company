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

            {!! Form::open(['url'=>route('member.checkSms'),'role'=>'form','name'=>'code-phone']) !!}
                <div class="ml160 mt60">
                    <p class="p">已验证的手机：  {{ changeMobile($user->phone) }}  </p>
                    <phone-code class="phone-valite" phone="{{ $user->phone }}" sendurl="{{ route('member.sendSms') }}" sendtype="78585087" iscode="1" v-on:valite-code="getCode"></phone-code>
                    <div class="clear"></div>
                    <p :class="{inputerror:true, 'normal-warn':true, ml50:true, red:true, hide:!error}">手机验证码有误，请重新输入~</p>
                </div>
                {!! Form::hidden('phone',$user->phone) !!}
                {!! Form::hidden('template_code','78585087') !!}
                {!! Form::hidden('max','30') !!}
                <br><br>
                <p class="tac"><a @click="next" href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s w150">下一步</a></p>
                <div class="m-t-10"></div>
                @if($user->email)
                <p class="tac"><a href="{{ route('email.verify') }}" class="juhuang">使用已验证邮箱修改密码&gt;&gt;</a></p>
                @endif
            {!! Form::close() !!}
            <div class="m-t-10" v-for="i in 10"></div>
        </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
     
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-pwd-step1", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init('{{ route('account.reset',['type'=>'phone']) }}');
            $(".phone-valite .pull-left").eq(0).css({marginTop:'7px'}).end()
                                         .eq(2).css({marginTop:'0px'}).removeClass('mt-5')
                                                     
        })
    </script>
@endsection