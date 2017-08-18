@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">修改邮箱</span><div class="mt5"></div></h4>
        <div class="content-wapper ">
            <div class="hd">
                <ul>
                    <li><span>1</span><label>验证身份</label></li>
                    <li class="cur"><span>2</span><label class="juhuang">验证邮箱</label></li>
                    <li><span>3</span><label>完成</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            {!! Form::open(['url'=>route('account.sendMail'),'role'=>'form','name'=>'code-email']) !!}
                <div class="ml160 mt60">
                    <p>
                        <span class="pull-left">请输入新邮箱： </span>
                        <input @focus="initEmail" @blur="checkEmail" type="text" v-model="email" name="email" :class="{'form-control':true, 'pull-left':true ,'mt-5':true, w300:true, 'error-bg':!isEmail}">
                        {!! Form::hidden('email-unique','1') !!}
                        <span v-cloak v-show="!isEmail" class="pull-left red ml100 mt5">邮箱格式不符，请重新输入~</span>
                        <span v-cloak v-show="errorEmail" class="pull-left red ml100 mt5 icon-error normal-error">邮箱已存在，请重新输入~！</span>
                    </p>
                    <div class="clear"></div>
                </div>
                <br><br>
                <p class="tac"><a @click="next" href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s w150">发送验证邮件</a></p>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-email-valite", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.initSendCount("10","{{ route('upemail.seep5') }}");
        })
    </script>
@endsection