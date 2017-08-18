@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin marign-top-tab psr">
        @include('HomeV2._layout.myfile_nav')
        <div class="mt10"></div>
        <div class="content-wapper">
            <div class="hd hd-card">
                <ul>
                    <li><span>1</span><label>提交资料</label></li>
                    <li class="cur"><span>2</span><label class="juhuang">核实信息</label></li>
                    <li><span>3</span><label>完成认证</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            <br><br>
            <p class="juhuang tac fs16">客官，感谢您的提交，文件已在审核的路上，请您耐心等待哦~</p>
            <div class="mauto">
                <table class="noborder-tbl wauto">
                    <tr>
                        <td width="350" align="right" valign="middle">
                            <p class="mt10">真实姓名：  </p>
                        </td>
                        <td  class="bank-area">
                            {{ $user->last_name.$user->first_name }}
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="middle">
                            <p class="mt10">身份证号： </p>
                        </td>
                        <td class="bank-area">
                            {{ chanageStr($user->id_cart,4,-4) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="bank-card-wrapper mauto mt20 ml30">
                                <div class="bank-card-item-wrapper tac">
                                    <img src="{{ getImgidToImgurl($user->sc_id_cart_img) }}" alt="" width="134" height="101">
                                </div>
                                <div class="bank-card-item-wrapper tac">
                                    <img src="{{ getImgidToImgurl($user->id_facade_img) }}" alt="" width="134" height="101">
                                </div>
                                <div class="bank-card-item-wrapper tac">
                                    <img src="{{ getImgidToImgurl($user->id_behind_img) }}" alt="" width="134" height="101">
                                </div>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>

        </div>
        <div class="m-t-10" v-for="i in 5"></div>

    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common"],function(v,u,c){
            $(".box-border").css({
                'border-right':0,
                'border-bottom':0,
            })
            $(".slide").css({
                'border-bottom':"1px solid #ddd",
            })
        })
    </script>
@endsection