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
                    <li class="cur"><span>1</span><label class="juhuang">提交资料</label></li>
                    <li><span>2</span><label>核实信息</label></li>
                    <li><span>3</span><label>完成认证</label></li>
                    <div class="clear"></div>
                </ul>
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
                                <input @focus="initRealyName" @blur="checkRealyName" maxlength="20" v-model="realyName" type="text" name="real_name" placeholder="真实姓名" :class="{'form-control':true,'w125':true,'error-bg':isRealyName}">
                                <span class="edit pz-edit"></span>
                            </div>
                            <span class="p-gray ml10"  v-show="!isNameError">为了安全，一经认证，无法修改哦</span>
                            <span class="red ml10" v-cloak v-show="isNameError">真实姓名必须为中文</span>
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
                                    <input maxlength="18" @focus="initNumId" @blur="checkNumId" v-model="numId" type="text" name="id_cart" placeholder="18位（数字或大写字母）" :class="{'form-control':true,'w125':true,'error-bg':isNumId}">
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
                                <div class="numid-item-wrapper psr">
                                    <p class="text-left">身份证正面</p>
                                    <div :class="{show:isLoadingImg}" class="psa loading-status"></div>
                                    <img id="upload-img-bank" src="/themes/images/numid.png" alt="" width="134" height="101">
                                    <p class="tac mt10">
                                        <a @click="upload" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">上传图片</a>
                                    </p>
                                    <input @change="previewFile($event,'upload-img-bank')" type="file" name="id_facade_img" id="upload-file" class="hide">
                                </div>

                                <div class="numid-item-wrapper psr">
                                    <div :class="{show:isLoadingImg}" class="psa loading-status"></div>
                                    <p class="text-left">身份证反面</p>
                                    <img id="upload-img-hand-bank" src="/themes/images/numid.png" alt="" width="134" height="101">
                                    <p class="tac mt10">
                                        <a @click="uploadHand" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">上传图片</a>
                                    </p>
                                    <input @change="previewFile($event,'upload-img-hand-bank')" type="file" name="id_behind_img" id="upload-file-hand" class="hide">
                                </div>

                                <div class="numid-item-wrapper psr">
                                    <p class="text-left">手持身份证</p>
                                    <div :class="{show:isLoadingImg}" class="psa loading-status"></div>
                                    <img id="upload-img-hand-numid" src="/themes/images/numid.png" alt="" width="134" height="101">
                                    <p class="tac mt10">
                                        <a @click="uploadNumIdHand" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">上传图片</a>
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
                                <p :class="{tac:true,red:true,hide:!isFile}">请上传符合要求的身份证认证图片~</p>
                                <p class="tac mt20">
                                    <button @click="send" type="button" class="btn btn-s-md btn-danger fs14">提交</button>
                                </p>
                                
                            </div>

                        </td>
                    </tr>
                </table>
            {!! Form::close() !!}
        </div>
        <div id="errorWin" class="popupbox">
            <div class="popup-title">上传失败</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac">
                    <div class="tip-text mt10 ml20 tac">
                         客官，非常抱歉，图片没有上传成功<br>重试一下吧~
                    </div>
                    <div class="clear"></div>
                    <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure">关闭</a>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                </div>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-file-authentication"],function(v,u,c){
            u.init("{{ route('auth.showIdCart') }}");
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