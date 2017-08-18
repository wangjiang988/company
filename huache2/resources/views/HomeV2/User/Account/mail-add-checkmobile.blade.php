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
                    <li class="cur"><span>1</span><label class="juhuang">验证身份</label></li>
                    <li><span>2</span><label>验证邮箱</label></li>
                    <li><span>3</span><label>完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            {!! Form::open(['url'=>route('member.checkSms'),'role'=>'form','name'=>'code-phone']) !!}
                <div class="ml160 mt60 psr">
                    <p class="p">已验证的手机：  {{ changeMobile($user->phone) }}  <span class="p-gray ml20">若无法收到短信，请<a href="#" class="blue tdu">查看帮助</a></span></p>
                    <phone-code class="phone-valite" phone="{{ $user->phone }}" sendurl="{{ route('member.sendSms') }}" sendtype="78650062" iscode="1" v-on:valite-code="getCode"></phone-code>
                    <div class="clear"></div>
                    <p class="psa" :class="{inputerror:true, 'normal-warn':true, ml50:true, red:true, hide:!error}">验证码有误，请重新输入~</p>
                </div>
                {!! Form::hidden('phone',$user->phone) !!}
                {!! Form::hidden('template_code','78650062') !!}
                {!! Form::hidden('max',30) !!}
                <br><br>
                <p class="tac"><a @click="next" href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s w150">下一步</a></p>
                <div class="m-t-10"></div>
            {!! Form::close() !!}
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>

        </div>

    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-phone-step1", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init("{{ route('email.success-addEmail') }}");
            $(".phone-valite .pull-left").eq(0).css({marginTop:'7px'}).end()
                                         .eq(2).css({marginTop:'0px'}).removeClass('mt-5')
        })
    </script>
@endsection