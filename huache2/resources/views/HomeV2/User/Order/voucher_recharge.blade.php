@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 psr content ">

        <!--  ################################# -->
        <div class="wapper has-min-step content-wapper">
            <p><b class="blue">充值</b></p>
            <div class="box box-border top-border  fs14 p20">
                <p>可用余额：￥{{ number_format($account->avaliable_deposit,2) }}</p>
                <a href="javascript:;" title="K.2.30可用余额充值线上支付.html"><span class="tab ml50 tab-empty">线上支付</span></a>
                <a href="javascript:;" title="K.2.33可用余额充值银行转账.html"><span class="tab ml50">银行转账</span></a>
                <div class="box-border pay-box">
                    <div class="clear"></div>
                    <h3 class="ul-prev-big">您可以使用网上银行、手机银行、银行柜面等途径直接转账至华车，公司收款账户信息如下：</h3>
                    <p class="fs14 ml50">开户行：( 江苏省苏州市 ) 招商银行苏州分行干将路支行</p>
                    <p class="fs14 ml50">帐 号：5129 0627 3310 301</p>
                    <p class="fs14 ml50"><span>户 名：苏州华车网络科技有限公司</span><a href="javascript:;" @click="sendCode" class="ml30 juhuang tdu">发送到手机</a></p>
                    <h3 class="ul-prev-big">温馨提示：</h3>

                    <div class="ml30 ul-prev-small">
                        <span class="red">请务必在汇款附言中注明：账户{{ Auth::user()->phone }}充值</div>
                    <div class="ml30 ul-prev-small">
                        入账金额和入账时间均以实际确认到账为准，若汇款信息不全或不符造成退回，请恕相关责任由您承担。
                    </div>

                    <div class="m-t-10"></div>
                    <h3 class="ul-prev-big">提交转账凭证和信息（单张银行转账回单，请制作1张清晰图片上传提交，如有多张回单，请分次提交。）</h3>
                    {!! Form::open(['url'=>route('recharge.receipt'),'role'=>'form','enctype'=>'multipart/form-data']) !!}
                        {!! Form::hidden('order_id',$account->order_id) !!}
                        <table class="fs14 ml20 tbl-bank">
                            

                            <tr>
                                <td align="right" width="135"><span class="red">*</span><span class="ml5">汇款银行：</span></td>
                                <td valign="middle">
                                    <div class="form-group psr pdi-control fs14">
                                        <input @focus="initBankInfo" @blur="checkBankInfo"  v-model="bankInfo" type="text" name="bank_name" :class="{'error-bg':isBankInfo}" placeholder="输入银行名称即可，例如：工商银行" class="form-control pay-control credentials--control">
                                        <span class="edit credentials-edit"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><span class="red">*</span><span class="ml5">汇款人账号：</span></td>
                                <td class="bank-area">
                                    <div class="mt-20">
                                        <div class="form-group psr pdi-control fs14  m-t-10 ">
                                            <div class="form-group psr pdi-control fs14">
                                                <input autocomplete="off" maxlength="50" @keydown="valiteBankNum" @focus="initBankNum" @blur="checkBankNum" v-model="bankNum" type="text" name="bank_code" class="form-control pay-control credentials--control" placeholder="输入的数字之间不允许出现空格" :class="{'form-control':true,'w125':true,'error-bg':isBankNum}">
                                                <span class="edit credentials-edit"></span>
                                            </div>
                                            <div class="display-bank-num-view display-bank-num-view-fix w420 top-36 red tac weight fs18" v-cloak v-show="bankNum.length > 0 && isInput">
                                                @{{bankNumView}}
                                            </div>
                                            <div class="display-bank-num-view display-bank-num-view-fix w420" v-cloak v-show="bankNumList.length > 0 && !isSelect">
                                                <p @click="selectBankNum(num)" v-for="num in bankNumList">@{{num}}</p>
                                            </div>
                                            <span class="hide psa bank-error" :class="{show:isBankError,inputerror:true,red:true,ml10:true}">请输入格式正确的内容</span>
                                        </div>
                                    </div>
                                </td>

                                <td>

                                </td>
                            </tr>


                            <tr>
                                <td align="right"><span class="red">*</span><span class="ml5">汇款人姓名：</span></td>
                                <td>
                                    <div class="form-group psr pdi-control fs14">
                                        <input type="text" @focus="initUserName" @blur="checkUserName"  v-model="userName" name="user_name" placeholder="" class="form-control pay-control credentials--control" :class="{'error-bg':isUserName}">
                                        <span class="edit credentials-edit"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><span class="red">*</span><span class="ml5">汇款金额：</span></td>
                                <td>
                                    <div class="form-group psr pdi-control fs14">
                                        <span class="psa credentials-symbol">￥</span>
                                        <input type="text" @focus="initPrice" @blur="checkPrice" :value="price" v-model="price" placeholder="输入金额，最多保留小数点后2位" class="form-control pay-control credentials--control" :class="{'error-bg':isPrice}">
                                        <span class="edit credentials-edit"></span>
                                        <input type="hidden" name="price" v-model="priceSource" :value="priceSource">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" valign="top"><span class="red">*</span><span class="ml5">上传凭证图片：</span></td>
                                <td>
                                    <span>（仅支持JPG、GIF、PNG、JPEG、BMP格式，尺寸小于5M）</span>
                                    <br><br>
                                    <div class="psr tac">
                                        <div class="bank-upload-pz-wapper tac ">
                                            <img  @click="upload" width="200" id="upload-img-bank" :src="isFile ? '/webhtml/user/themes/images/upload-error.png' : '/webhtml/user/themes/images/upload.gif'">
                                            <div class="fileinputlist"></div>
                                            <p id="fileerror" class="inputerror juhuang fs14 mt5 hide">您尚未上传任何文件，请先上传！</p>
                                            <input @change="previewFile($event,'upload-img-bank')" type="file" name="bank_voucher" id="upload-file" class="hide">
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        </table>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="clear"></div>
                    <div class="tac">
                        <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16 btn-s w100">提交</a>
                        <a href="{{ route('pay.recharge',['order_id'=>$account->order_id]) }}" class="fs16 juhuang tdu ml50">返回</a>
                    </div>
                    {!! Form::close() !!}

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

                    <div id="tipWin" class="popupbox">
                        <div class="popup-title"><span>发送短信</span></div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd tac" >
                                    <span class="tip-tag bp0"></span>
                                    <span class="tip-text mt10">确定向手机{{ chanageStr(Auth::user()->phone,4,-4,'****') }}发送收款账户短信吗？ </span>
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

                </div>
            </div>

        </div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-submit-documents", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.initUrl('{{ route('recharge.result') }}','{{ route('recharge.result') }}','{{ route('my.brankList') }}');
        })
    </script>
@endsection