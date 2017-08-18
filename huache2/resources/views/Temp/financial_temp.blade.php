@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
                <div class="user-content psr">
                @if($active == 'fuli')
                    <div class="yue-content psr">
                        <p class="pre-fix"><span class="juhuang weight ml10">福利</span></p>
                        <div class="m-t-10"></div>

                        <div class="clear"></div>
                        <p class="tac">
                            <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                            客官，代金券福利将在开放购买前到您的账户，请耐心等待哦～
                        </p>

                    </div>
                @elseif($active == 'yue')
                    <div class="yue-content psr">
                        <p class="pre-fix"><span class="juhuang weight ml10">余额</span></p>
                        <div class="m-t-10"></div>

                        <div class="clear"></div>
                        <p class="tac">
                            <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                            客官，账户中尚未发现可用的余额～
                        </p>
                    </div>
                @elseif($active == 'ruzhang')
                    <div class="yue-content psr">
                        <p class="pre-fix"><span class="juhuang weight ml10">转入</span></p>
                        <div class="m-t-10"></div>

                        <div class="clear"></div>
                        <p class="tac">
                            <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                            客官，您还没有转入过资金哦～
                        </p>
                    </div>
                @elseif($active == 'tixian')
                    <div class="yue-content psr">
                        <p class="pre-fix"><span class="juhuang weight ml10">提现</span></p>
                        <div class="m-t-10"></div>

                        <div class="clear"></div>
                        <p class="tac">
                            <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                            客官，没有可以提现的资金哦！
                        </p>
                    </div>
                @elseif($active=='fapiao')
                    <div class="yue-content psr">
                        <p class="pre-fix"><span class="juhuang weight ml10">发票</span></p>
                        <div class="m-t-10"></div>

                        <div class="clear"></div>
                        <p class="tac">
                            <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                            客官，暂无可以开具给您的发票哦～
                        </p>
                    </div>
                  @elseif($active=='download')
                    <div class="yue-content psr">
                      <p class="pre-fix"><span class="juhuang weight ml10">下载</span></p>
                      <div class="m-t-10"></div>

                      <div class="clear"></div>
                      <p class="tac">
                        <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                        客官，暂无供您下载的文件～
                      </p>
                    </div>
                @elseif($active=='add_price')
                    <div class="yue-content psr">
                      <p class="pre-fix"><span class="juhuang weight ml10">充值</span></p>
                      <div class="m-t-10"></div>

                      <div class="clear"></div>
                      <p class="tac">
                        <img src="/webhtml/user/themes/images/hasno.png" alt=""><br>
                        客官，您的资金先留着, 开放购买时再来充值吧～
                      </p>
                    </div>
                  @endif

                    <div class="m-t-10" v-for="i in 5"></div>

                </div>
                <div class="clear"></div>

            </div>
        </div>

    </div>

 @endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
    <script src="{{asset('/webhtml/user/js/sea.js')}}"></script>
    <script src="{{asset('/webhtml/user/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min",
            "/webhtml/user/js/module/user/user-bank-transfer",
            "/webhtml/user/js/module/common/common"],function(v,u,c){
            //页面加载的时候就设置该手机号发送了几次验证码
            //不发送验证码的情况
            //1.发送次数 >=3
            //2.发送间隔在2分钟之内
            u.initSendCount(0)
        })
    </script>
@endsection