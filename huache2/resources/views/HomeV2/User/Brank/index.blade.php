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
            {!! Form::open(['url'=>route('bank.save'),'class'=>'form-horizontal','role'=>'form','name'=>"user-file-bank",'enctype'=>'multipart/form-data']) !!}
                <table class="noborder-tbl wp100">
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <span class="ml5 fr mt5">开户行：</span>
                            <span class="red fr mt5">*</span>
                            <div class="clear"></div>
                        </td>
                        <td width="600" class="bank-area">
                            <province-city province-tmp="{{ $user->province }}" city-tmp="{{ $user->city }}" v-on:receive-params="getArea" def-value="请选择·开户地区" is-select-province="false"></province-city>
                            <div class="form-group psr pdi-control fs14  m-t-10 ml10">
                                <input @focus="initBankInfo" @blur="checkBankInfo" v-model="bankInfo" type="text" name="bank_address" placeholder="开户行信息" :class="{'form-control':true,'w125':true,'error-bg':isBankInfo}">
                                <span class="edit pz-edit"></span>
                                <p class="hide inputerror juhuang m-t-10 ml20">请正确输入开户行信息</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <span class="ml5 fr mt-10">账  号：</span>
                            <span class="red fr mt-10">*</span>
                            <div class="clear"></div>
                        </td>
                        <td width="600" class="bank-area">
                            <div class="mt-20" style="margin-left: 15px;">
                                <div class="form-group psr pdi-control fs14  m-t-10">
                                    <input maxlength="19" @keydown="valiteBankNum" @focus="initBankNum" @blur="checkBankNum" v-model="bankNum" type="text" name="bank_code" placeholder="输入的数字之间不允许出现空格" :class="{'form-control':true,'w125':true,'error-bg':isBankNum}">
                                    <div class="display-bank-num-view w315" v-cloak v-show="bankNum.length > 0 && isInput">
                                        @{{bankNumView}}
                                    </div>
                                    <span class="edit pz-edit" v-show="!isInput"></span>
                                    <span :class="{hide:!isBankError,inputerror:true,red:true,'m-t-10':true,ml10:true}">账号格式不正确，请重新输入</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <span>户  名：</span>
                        </td>
                        <td width="600" class="bank-area">
                            <span>{{ $user->last_name.$user->first_name }}</span><span class="p-gray ml10">（已实名认证的户名，不可再更改哦~）</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="150" align="right" valign="middle">
                            <div class="mt10 ">
                                <span class="red">*</span><span class="ml5">银行卡认证图片：</span>
                            </div>
                        </td>
                        <td width="600" class="bank-area">
                            <div class="mt10 ">
                                <span class="p-gray ">（仅支持JPG、GIF、PNG、JPEG、BMP格式，单张图片小于5M）</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="bank-card-wrapper mauto tac mt20">
                                <div class="bank-card-item-wrapper">
                                    <p class="text-left">银行卡正面</p>
                                    <img id="upload-img-bank" src="/themes/images/bank.png" alt="" width="164" height="96">
                                    <p class="tac mt10">
                                        <a @click="upload" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto">上传图片</a>
                                    </p>
                                    <input @change="previewFile($event,'upload-img-bank')" type="file" name="bank_img" id="upload-file" class="hide">
                                </div>

                                <div class="mt20">&nbsp;</div>
                                <p :class="{tac:true,red:true,hide:!isFile}">请上传符合要求的银行卡认证图片</p>
                                <p class="tac mt20">
                                    <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs14">提交</a>
                                </p>
                                <p class="tac mt20">
                                    <b>温馨提示：</b>提现退款一般为原路退回。在无法原路退回情况下，可转账至经实名认证的账户主人银行账户。
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-file-submit-bank", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.init("{{ route('bank.addShow',['id'=>0]) }}");
        })
    </script>
@endsection