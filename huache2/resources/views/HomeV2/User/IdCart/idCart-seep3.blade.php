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
            <div class="tac mauto">
                <h1 class="error-large fs16"><span class="ml10 juhuang">审核未通过，请重新提交~</span></h1>
                <p class="tac p-gray">审核未通过原因：填写的账号信息与开户行不符</p>
                <hr class="dashed">
            </div>
             {!! Form::open(['url'=>route('auth.postIdCart'),'name'=>'user-file-bank','role'=>'form','enctype'=>'multipart/form-data']) !!}
                <table class="noborder-tbl wp100">
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <span class="ml5 fr mt5">真实姓名：</span>
                            <span class="red fr mt5">*</span>
                            <div class="clear"></div>
                        </td>
                        <td width="600" class="bank-area">
                            <div class="form-group psr pdi-control fs14  m-t-10">
                                <input @focus="initRealyName" @blur="checkRealyName" maxlength="20" v-model="realyName" type="text" name="real_name" value="{{$user->last_name.$user->first_name}}" placeholder="真实姓名" :class="{'form-control':true,'w125':true,'error-bg':isRealyName}">
                                <span class="edit pz-edit"></span>
                            </div>
                            <span class="p-gray ml10">一经认证，将无法修改</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <span class="ml5 fr mt-10"> 身份证号：</span>
                            <span class="red fr mt-10">*</span>
                            <div class="clear"></div>
                        </td>
                        <td width="600" class="bank-area">
                            <div class="mt-20">
                                <div class="form-group psr pdi-control fs14  m-t-10 ">
                                    <input maxlength="18" @focus="initNumId" @blur="checkNumId" v-model="numId" type="text" name="id_cart" value="{{$user->id_cart}}" placeholder="18位（数字或大写字母）" :class="{'form-control':true,'w125':true,'error-bg':isNumId}">
                                    <span class="edit pz-edit"></span>
                                    <span :class="{hide:!isNumError,inputerror:true,red:true,'m-t-10':true,ml10:true}">您输入的身份证格式不符，请重新输入</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <span class="ml5 fr "> 身份证认证图片：</span>
                            <span class="red fr ">*</span>
                            <div class="clear"></div>
                        </td>
                        <td width="600" class="bank-area">
                            <span class="p-gray ">（仅支持JPG、GIF、PNG、JPEG、BMP格式，单张图片小于5M）</span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">

                            <div class="bank-card-wrapper mauto tac mt20">
                                <div class="numid-item-wrapper">
                                    <p class="text-left">身份证正面</p>
                                    <img id="upload-img-bank" src="{{ getImgidToImgurl($user->id_facade_img) }}" alt="" width="134" height="101">
                                    <p class="tac mt10">
                                        <a @click="upload" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">重新上传</a>
                                    </p>
                                    <input @change="previewFile($event,'upload-img-bank')" type="file" name="id_facade_img" id="upload-file" class="hide">
                                </div>

                                <div class="numid-item-wrapper">
                                    <p class="text-left">身份证反面</p>
                                    <img id="upload-img-hand-bank" src="{{ getImgidToImgurl($user->id_behind_img) }}" alt="" width="134" height="101">
                                    <p class="tac mt10">
                                        <a @click="uploadHand" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">重新上传</a>
                                    </p>
                                    <input @change="previewFile($event,'upload-img-hand-bank')" type="file" name="id_behind_img" id="upload-file-hand" class="hide">
                                </div>

                                <div class="numid-item-wrapper">
                                    <p class="text-left">手持身份证</p>
                                    <img id="upload-img-hand-numid" src="{{ getImgidToImgurl($user->sc_id_cart_img) }}" alt="" width="134" height="101">
                                    <p class="tac mt10">
                                        <a @click="uploadNumIdHand" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">重新上传</a>
                                    </p>
                            <input @change="previewFile($event,'upload-img-hand-numid')" type="file" name="sc_id_cart_img" id="upload-numid-hand" class="hide">
                                </div>

                                <div class="numid-item-wrapper">
                                    <img src="/themes/images/hand-numid.jpg" width="200" alt="">
                                    <p class="tac mt10 red">
                                        手持身份证示例
                                    </p>
                                </div>
                                <div class="mt20">&nbsp;</div>
                                <p :class="{tac:true,red:true,hide:!isFile}">请上传符合要求的银行卡认证图片</p>
                                <p class="tac mt20">
                                    <button @click="send" type="button" class="btn btn-s-md btn-danger fs14">重新提交</button>
                                </p>
                                <p class="tac mt20">
                                    <a href="#" class="blue">《华车保护个人信息承诺》</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            {!! Form::close() !!}
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-file-authentication", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init("{{ route('auth.showIdCart') }}");
        })
    </script>
@endsection