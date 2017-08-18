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
            <table class="noborder-tbl wp100 ml100">
                <tr>
                    <td width="150" align="right" valign="middle">
                        <p class="mt10">开户行：</p>
                    </td>
                    <td width="600" class="bank-area">
                        （{{$bank->province.$bank->city.$bank->district}}）{{$bank->bank_address}}
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="middle">
                        <p class="mt10">账 号：</p>
                    </td>
                    <td width="600" class="bank-area">
                        {{ chanageStr($bank->bank_code,4,-4) }}
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="middle">
                        <p class="mt10">户 名：</p>
                    </td>
                    <td width="600" class="bank-area">
                        {{ $user->last_name.$user->first_name}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="bank-card-wrapper mauto mt20 ml150">
                            <div class="bank-card-item-wrapper">
                                <img id="upload-img-bank" src="{{ getImgidToImgurl($bank->bank_img) }}" alt="" width="164" height="96">
                            </div>
                        </div>
                    </td>
                </tr>

            </table>

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

        })
    </script>
@endsection