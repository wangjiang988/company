@extends('_layout.base_dealer_v2')
@section('css','')
@section('nav')

@endsection

@section('content')
    <div class="custom-content custom-content-with-margin nomargin psr">
        <p class="blue mt20">提交充值凭证</p>
        <hr class="dashed">
        <h3 class="ul-prev-big fs14">
            <p class="noweight lh22 mt-5">请使用您已认证并绑定的<span class="juhuang">本人银行账户（{{chanageStr($bank->seller_bank_account,0,-4,'***')}}/{{chanageStr($bank->sellerName,3,strlen($bank->sellerName),'***')}}）</span>，采用网上银行、手机银行、银行柜面等途径直接转账至华车，公司收款账户信息如下：</p>
        </h3>
        <br>
        <p class="fs14 ml50">开户行：（江苏省苏州市）招商银行苏州分行干将路支行</p>
        <p class="fs14 ml50">帐 号：5129 0627 3310 301</p>
        <p class="fs14 ml50"><span>户 名：苏州华车网络科技有限公司</span><a href="javascript:;" @click="sendCode" class="ml30 juhuang tdu" >发送到手机</a></p>

        <h3 class="ul-prev-big fs14">温馨提示：</h3>
        <div class="ml30 ul-prev-small">
            <span class="red">请务必在汇款附言中注明：本人充值。</div>
        <div class="ml30 ul-prev-small">
            入账金额和入账时间均以实际确认到账为准，若汇款信息不全或不符造成退回，请恕相关责任由您承担。
        </div>


        <h3 class="ul-prev-big fs14">提交转账凭证和信息（单张银行转账回单，请制作1张清晰图片上传提交，如有多张回单，请分次提交。）</h3>

        {!! Form::open(['url'=>route('dealer.rechargeVoucher'),'role'=>'form','enctype'=>'multipart/form-data']) !!}
            <table class="fs14 ml20 tbl-bank">
                <tr>
                    <td align="right"><span class="red">*</span><span class="ml5">充值金额：</span></td>
                    <td>
                        <div class="form-group psr pdi-control fs14 fl">
                            <span class="psa credentials-symbol">￥</span>
                            {!! Form::hidden('price','',['id'=>'t_price']) !!}
                            <input type="text" @focus="initPrice" @blur="checkPrice" :value="price" v-model="price" placeholder="输入金额，最多保留小数点后2位" class="form-control pay-control credentials--control" :class="{'error-bg':priceEmpty}">
                            <span class="edit credentials-edit"></span>
                            <input type="hidden" name="hfprice" v-model="priceSource" :value="priceSource">
                        </div>
                        <span class="red hide wauto fl ml10 mt5" :class="{show:isPrice}">格式有误！请重新输入</span>
                        <div class="clear"></div>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="top"><span class="red">*</span><span class="ml5">上传凭证图片：</span></td>
                    <td>
                        <span>（仅支持JPG、GIF、PNG、JPEG、BMP格式，尺寸小于5M）</span>
                        <br><br>

                        <div class="psr tac" style="width: 420px;">
                            <div class="bank-upload-pz-wapper tac ">
                                <div class="clear"></div>
                                <img  @click="upload" width="200" id="upload-img-bank" :src="isFile ? '/webhtml/custom/themes/images/upload-error.png' : '/webhtml/custom/themes/images/upload.gif'">
                                <div class="fileinputlist"></div>
                                <p id="fileerror" class="inputerror juhuang fs14 mt5 hide">您尚未上传任何文件，请先上传！</p>
                                <input @change="previewFile($event,'upload-img-bank')" type="file" name="voucher" id="upload-file" class="hide">
                            </div>
                        </div>

                    </td>
                </tr>
            </table>
        {!! Form::close() !!}
        <div class="m-t-10" v-for="i in 3 "></div>
        <div class="tac">
            <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s w100">提交</a>
            <a href="{{ route('dealer.funds','recharge') }}" class="btn btn-s-md btn-danger fs16 btn-s w100 sure ml50">返回</a>
        </div>


        <div id="tipWin" class="popupbox">
            <div class="popup-title"><span>发送短信</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        {!! Form::hidden('phone',getSellerInfo(session('user.member_id'))) !!}
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">确定向手机{{ changeMobile(getSellerInfo(session('user.member_id'))) }}发送收款账户短信吗？ </span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doSendCode" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure ml50">取消</a>
                    <div class="clear"></div>

                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>

        <div id="successWin" class="popupbox">
            <div class="popup-title"><span>发送短信</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">短信发送成功！<br>接收短信一般不超过2分钟，<br>长时间未收到可再申请重发～</span>
                    </p>
                </div>
                <div class="popup-control">
                    <p><span class="red">@{{countDownNum}}</span>秒后自动关闭</p>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure">关闭</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div id="errorWin" class="popupbox">
            <div class="popup-title"><span>提示</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">客官，您点击的太频繁啦，可以稍后重新发送哦！</span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" class="sure btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <div class="clear"></div>

                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>

        <div id="endWin" class="popupbox">
            <div class="popup-title"><span>提示</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">客官，今天的短信发送次数已用光，<br>请明天再来发送吧！</span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" class="sure btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>

        <div id="subWin" class="popupbox">
            <div class="popup-title"><span>提交凭证</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">确定您准备提交的内容无误并立即提交吗？ </span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure ml50">取消</a>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>

        <div class="m-t-10" v-for="i in 15"></div>
    </div>
@endsection

@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-submit-charging-credentials", "/webhtml/custom/js/module/common/common"],function(v,u,c){
            u.initSendCount(0);
            u.initUrl("{{ route('dealer.funds','recharge_success') }}","{{ route('dealer.funds','recharge_error') }}","{{route('member.sendSms')}}");
        })
    </script>
@endsection