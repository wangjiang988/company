@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">修改验证手机号</span><div class="mt5"></div></h4>
        <div class="content-wapper ">
            <div class="hd">
                <ul>
                    <li><span>1</span><label>验证身份</label></li>
                    <li class="cur"><span>2</span><label class="juhuang">修改验证手机号</label></li>
                    <li><span>3</span><label>完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            {!! Form::open(['url'=>route('upmobile.seep4'),'role'=>'form','name'=>'code-phone']) !!}
                <div class="ml160 mt60">
                    <p>
                        <span class="pull-left">新的手机号：</span>
                        <input @focus="initPhone" @blur="checkPhone" type="text" maxlength=11 v-model="phone" name="phone" :class="{'form-control':true, 'pull-left':true ,code:true, w150:true, 'error-bg':!isPhone}">
                        <span v-cloak v-show="!isPhone" class="pull-left red ml20">手机号格式不正确，请重新输入</span>
                        <span v-cloak v-show="errorPhone" class="pull-left red ml20 icon-error normal-error">手机号已被其他账户使用！</span>
                    </p>
                    <div class="clear"></div>
                    <phone-code ref="phoneCode" is-sum-send-count="true" class="valite-phone-code" :phone="phone" sendurl="{{ route('member.sendSms') }}" sendtype="78575071" iscode="1" max="6" is-sum-send-count="true" v-on:valite-send-count="getSendCount" v-on:valite-code="getCode"></phone-code>
                    <div class="clear"></div>
                    <p :class="{inputerror:true, 'normal-warn':true, ml50:true, red:true, hide:!error}">验证码有误，请重新输入~</p>
                    <div class="mt10">
                        <p v-cloak v-show="sendCount == 4" >短信验证码已经发出，请注意查收短信，还可获取<span class="red">1次</span>验证码！</p>
                        <p v-cloak v-show="sendCount >= 5" >短信验证码已经发出，请注意查收短信，短信验证码发送次数<br>已达最大次数，可使用<a href="{{ route('upmobile.seep2') }}" class="blue tdu">绑定的邮箱修改手机号</a>~</p>
                    </div>
                </div>
                {!! Form::hidden('template_code','78575071') !!}
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-phone-step2", "/webhtml/user/js/module/common/common"],function(v,u,c){

            u.initSendCount("{{ route('upmobile.seep5') }}","{{ route('upmobile.seep6') }}","{{ route('member.checkUser') }}");
        })
    </script>
@endsection