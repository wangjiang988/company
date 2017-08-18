@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.header2') @endsection
@section('content')

<div style="width:400px;height: 80px;right:0;top:0;z-index: 999;background: #fff;position: fixed"></div>

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                <ul>
                    <li>1.填写账号</li>
                    <li>2.验证身份</li>
                    <li>3.重置密码</li>
                    <li class="cur">4.完成</li>
                    <div class="clear"></div>
                </ul>

                <div class="form">
                    <br>
                    <table class="reg-form-tbl w355">
                        <tr>
                            <td width="100" align="right" valign="top">
                                <span class="tag-success"></span>
                            </td>
                            <td class="text-gray">
                                <p class="weight juhuang">恭喜您，密码已更新！</p>
                            </td>
                        </tr>
                    </table>

                    <br><br><br>
                    <p class="text-center">
                        <a href="javascript:;" @click="toLogin" class="btn btn-s-md btn-danger w120 mt20">>> 去登录</a>
                    </p>
                    <p class="text-gray tac fs12 " v-if="isend">
                        <span v-cloak class="juhuang">@{{countDownNum}}</span>秒后自动跳转登录页面
                    </p>

                </div>

            </div>
        </div>
        <br><br><br>


    </div>

@endsection
@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login')
    @include('HomeV2._layout.login')
@endsection

@section('js')
    <script type="text/javascript">

        seajs.use(["/webhtml/common/js/vendor/vue.min","module/pwd/password-step-5", "module/common/common","bt"],function(a,b,c){
                $('.head-login-wrapper').hide();
        })
    </script>
@endsection