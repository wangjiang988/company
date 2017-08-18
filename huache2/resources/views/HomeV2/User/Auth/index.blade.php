@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h2 class="blue-title pb0">安全设置</h2>
        <div class="content-wapper">

            <div class="pwd-strong">
                <label>设置强度</label>
                <span class="p-s-less pwdcur">低</span>

                @if($user->authNum ==4)
                    <span class="p-s-normal pwdcur">中</span>
                    <span class="p-s-max pwdcur">高</span>
                @elseif($user->authNum ==3)
                    <span class="p-s-normal pwdcur">中</span>
                    <span class="p-s-max">高</span>
                @else
                   <span class="p-s-normal">中</span>
                    <span class="p-s-max">高</span>
                @endif
            </div>
            <dl class="valite-dl">
                <dt>
                    <span class="valite-ok"></span><label>登录密码</label>
                    互联网界木有绝对安全的账户，密码经常改改更安全，但别改得自己都忘了哈。
                </dt>
                <dd>
                    <a href="{{ route('mobile.verify') }}" class="juhuang">修改</a>
                </dd>
                @if($user->email == null || $user->email =='')
                <dt>
                    <span class="valite-none fl"></span>
                    <label class="fl">邮箱验证</label>
                    <span class="fl">万一手机出状况，邮箱也可挑大梁。绑定一下，有备无患！ </span>
                </dt>
                <dd class="mulite">
                    <a href="{{ route('email.add') }}" class="juhuang">验证</a>
                </dd>
                @else
                <dt>
                    <span class="valite-ok"></span><label>邮箱验证</label>
                    邮箱：{{ changeEmail($user->email) }}已绑定喽！更换邮箱要及时来修改哦！
                </dt>
                <dd class="mulite">
                    <a href="{{ route('upemail.seep2') }}" class="juhuang">修改</a>
                </dd>
                @endif

                <dt>
                    <span class="valite-ok fl"></span>
                    <label class="fl">手机验证</label>
                    <span class="fl">手机：{{ changeMobile($user->phone) }}已验证喽！若已丢失或停用，记得马上来更换！</span>
                </dt>
                <dd class="mulite">
                    <a href="{{ route('upmobile.seep1') }}" class="juhuang">更换</a>
                </dd>
                @if($user->is_id_verify == null || $user->is_id_verify==0)
                    <dt>
                        <span class="valite-none"></span><label>实名认证</label>
                        从此账户只为您服务，服务您一生一世！
                    </dt>
                    <dd class="mulite">
                        <a href="{{ route('auth.addShowIdCart') }}" class="juhuang">认证</a>
                    </dd>
                @else
                    @if($user->is_id_verify ==1)
                        <dt>
                            <span class="valite-ok"></span><label>实名认证</label>
                            您的专属华车已经启程，服务您一生一世！
                        </dt>
                        <dd>
                            <a href="{{ route('auth.showIdCart') }}" class="juhuang">查看</a>
                        </dd>
                    @elseif($user->is_id_verify ==2)
                        <dt>
                            <span class="valite-none"></span><label>实名认证</label>
                            正在审核，请稍候~
                        </dt>
                        <dd class="mulite">
                            <a href="{{ route('auth.showIdCart') }}" class="juhuang">查看</a>
                        </dd>
                    @else
                        <dt>
                            <span class="valite-none"></span><label>实名认证</label>
                            很遗憾，未通过，再试试吧~
                        </dt>
                        <dd class="mulite">
                            <a href="{{ route('auth.addShowIdCart') }}" class="juhuang">再次认证</a>
                        </dd>
                    @endif
                @endif
                <div class="clear"></div>
            </dl>

        </div>
        <div class="m-t-10" v-for="i in 10"></div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login')@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-valite", "/webhtml/user/js/module/common/common"],function(v,u,c){


        })
    </script>
@endsection