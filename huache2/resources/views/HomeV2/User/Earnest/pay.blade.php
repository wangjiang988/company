@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.header2')
@endsection
@section('content')

    <div class="container m-t-86  pos-rlt">
        <div class="step  pos-rlt">
            <ul>
                <li class="first step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content">
                    <small>选择产品</small>
                    <i></i>
                    <small class="juhuang">付诚意金</small>
                    <i></i>
                    <small>售方确认</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <div class="wapper has-min-step">
            <form action="/member/payearnest/balance" method="post" id="payEarnest">
                {{ csrf_field() }}
                <input type="hidden" name="order_id" value="{{$order_id}}">
                <div class="clear"></div>
                <div class="box fs14">
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <p><span class="fl">>>应付诚意金：<b class="juhuang">￥499.00</b></span><span class="fr">可用余额：￥{{$user_account['avaliable_deposit']}}</span></p>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                    <div class="box-border">
                        <div class="mt20"></div>
                        <table width="580" class="mauto ">
                        <?php if($voucher):?>
                            <tr>
                                <td colspan="2">
                                    <label class="m0i noweight"><input class="ml20" checked="" disabled="" type="checkbox" readonly="" name="" id=""><span class="ml5">使用代金券</span></label>
                                </td>
                            </tr>
                            <tr>
                                <td class="tac">
                                    <div class="m-t-10"></div>
                                    <div class="vouchers-wrapper " v-cloak>
                                        <span class="juhuang weight">@{{vouchersObj.price}}</span>
                                        <span class="ml50">@{{vouchersObj.type}}</span>
                                        <span class="ml50"><b>有效期至</b><span class="juhuang">@{{vouchersObj.time}}</span> <span class="ml5">24点</span></span>
                                    </div>
                                    <input type="hidden" name="voucher_id" v-model="voucher_id" :value="voucher_id">
                                    <div class="tal ml20">
                                        <p class="mt20 juhuang show" :class="{hidenone:isSelectVouchers}">恭喜您即将享受福利：当前可使用最大金额代金券自动为您奉上～</p>
                                        <div class="mt20" v-show="isSelectVouchers"></div>
                                        <label class="m0i"><input class="" checked="" disabled="" type="checkbox" readonly="" name="" id=""><span class="ml5">使用可用余额中的<span class="juhuang" id="dym-price">￥499.00</span></span></label>
                                        <p class="mt10">手机号：{{substr_replace($phone,'****',3,4)}}</p>
                                        <phone-code ref="phonecode" sendurl="{{ route('member.sendSms')}}" sendtype="78750079" iscode="1" max="6"  @valite-code="getCode" phone="{{$phone}}"></phone-code>
                                    </div>
                                </td>
                                <td valign="top">
                                    <a @click="chanceVouchers" href="javascript:;" class="juhuang inline-block mt50">换券</a>
                                </td>
                            </tr>
                        <?php else:?>
                            <tr>
                                <td class="tac">
                                    <div class="tal ml20">
                                        <label class="m0i"><input class="" checked="" disabled="" type="checkbox" readonly="" name="" id=""><span class="ml5">使用可用余额中的<span class="juhuang">￥499.00</span></span></label>
                                        <p class="mt10">手机号：{{substr_replace($phone,'****',3,4)}}</p>
                                        <phone-code ref="phonecode" sendurl="{{ route('member.sendSms')}}" sendtype="78750079" iscode="1" max="6"  @valite-code="getCode" phone="{{$phone}}"></phone-code>
                                    </div>
                                </td>
                            </tr>
                        <?php endif;?>
                        </table>

                        <div class="center mt50">
                            <p class="tac red show" :class="{hidenone:!isCodeError}">请输入正确的验证码～</p>
                            <a href="javascript:;" @click="vouchersPay" class="btn btn-s-md btn-danger fs16">确认支付诚意金</a>
                            <a href="javascript:history.go(-1)" class="juhuang tdu ml50">返回</a>
                        </div>
                        <div class="m-t-10"></div>
                    </div>

                </div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>

                <div id="vouchersWin" class="popupbox">
                    <div class="popup-title">更换代金券</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac">
                                <span class="tip-tag bp0"></span>
                            <div class="tip-text mt10 ml20">
                                <span class="fl mt5">请选择：</span>
                                <drop-down ref="vouchers" @receive-params="getVouchers" name="hf" id="hf-id" def-value="" class-name="btn-dropdown-default" :list="vouchersList"></drop-down>
                                <div class="clear"></div>

                            </div>
                            <div class="clear"></div>
                            <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a @click="sureVouchers" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure">确定</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100 sure ml50">取消</a>
                            <div class="clear"></div>
                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-balance-payment",  "/js/module/common/common"],function(v,u,c){
            u.initVouchersList(<?=json_encode($voucher);?>)
            u.initVouchersObj(<?=json_encode($vouchers_default);?>)
            u.isVoucher(@if(count($voucher)) true @endif)
            $(".dropdown-toggle").css({height:"34px"})

        })
    </script>
@endsection
