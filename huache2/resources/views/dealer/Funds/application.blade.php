@extends('_layout.base_dealer_v2')
@section('css','')
@section('nav')

@endsection

@section('content')
    <div class="custom-content custom-content-with-margin nomargin  psr">
        <div class="yue-content psr">
            <span class="blue weight pl10 fl">申请提现</span>
            <div class="clear"></div>
            <hr class="dashed">
            <div class="m-t-10"></div>
        </div>
        <h3 class="ul-prev-big fs14">收款账户：</h3>
        <div class="ml50">
            <p><b>开户行：（{{ $bank->seller_bank_city_str }}）{{ $bank->seller_bank_addr }}</b></p>
            <p><b>账 号：{{ $bank->seller_bank_account }}</b></p>
            <p><b>户 名：{{ $bank->sellerName }}</b></p>
        </div>
        <h3 class="ul-prev-big fs14">可提现余额：￥{{ number_format($account->avaliable_deposit,2) }}</h3>

        {!! Form::open(['role'=>'form','id'=>'withdrawal_application']) !!}
        <div class="ml50">
            <span class="red fl mt5">*</span>
            <span class="ml5 fl mt5">提现金额：￥</span>
            {!! Form::hidden('avaliable_deposit',$account->avaliable_deposit) !!}
            {!! Form::hidden('user_id',$user_id,['id'=>'user_id']) !!}
            {!! Form::hidden('seller_bank_addr',$bank->seller_bank_addr) !!}
            {!! Form::hidden('seller_bank_account',$bank->seller_bank_account) !!}
            {!! Form::hidden('sellerName',$bank->sellerName) !!}
            {!! Form::hidden('money','',['id'=>'price_money']) !!}
            <input @focus="initPrice" @blur="setPrice" v-model="price" type="text" placeholder="0~{{ number_format($account->avaliable_deposit,2) }}" class="form-control w179 fl ml10 " :class="{'error-bg':priceEmpty}">
            <span class="mt5 fl ml10 red hide " :class="{show:!isPrice}">格式有误，请重新输入～</span>
            <div class="clear"></div>
        </div>
        <h3 class="ul-prev-big fs14"> 本次提现手续费：  {!! Form::hidden('fee',$fee,['id'=>'fee']) !!}
            <span>￥ {{$fee}}</span>（{{$template->description}}）</h3>
        <div class="m-t-10"></div>
        <div class="m-t-10"></div>
        {!! Form::close() !!}
        <div class="tac">
            @if($account->avaliable_deposit <=0)
                <a href="javascript:;" class="btn btn-s-md btn-danger bt" style="background: #ccc; border-color: #ccc; color:#000;">提现</a>
            @else
                <a @click="withdrawal" href="javascript:;" class="btn btn-s-md btn-danger bt">提现</a>
            @endif
            <a href="javascript:;" class="btn btn-s-md btn-danger sure bt ml50" onclick="history.go(-1)">返回</a>
        </div>

        <div id="phoneValite" class="popupbox">
            <div class="popup-title">验证身份确认提现</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14  tac">

                        <div class="tip-text fs14 text-left inline-block">
                    <p class="tac">本次提现金额￥@{{ price }}，提现手续费
                    <span>￥ @{{fee}}</span>
                    <br>确定提现吗？</p>
                    <br>
                    <p class="ml20">手 机 号： {{ changeMobile(getSellerInfo(session('user.member_id'))) }}</p>
                    {!! Form::hidden('phone', getSellerInfo(session('user.member_id')) ) !!}
                    <phone-code class="ml20" phone="{{ getSellerInfo(session('user.member_id')) }}" sendurl="{{ route('member.sendSms') }}" sendtype="78535082" iscode="1" @valite-code="getCode"></phone-code>
                    <div class="clear"></div>
                    <p class="tal ml100 mt10 red hide" :class="{show:isEmtpy}">请输入验证码</p>
                    <p class="tal ml100 mt10 red hide" :class="{show:isError}">验证码有误，请重新输入~</p>
                    <p class="tal ml100 mt10 red hide" :class="{show:isCodeError}">验证码已失效，请重新获取~</p>
                </div>
                <div class="clear"></div>
                <br>
                </p>
                <div class="m-t-10"></div>
            </div>
            <div class="popup-control">
                <a href="javascript:;" @click="doWithdrawal" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                <div class="clear"></div>
                <div class="m-t-10"></div>
            </div>
        </div>
    </div>

    <div id="successWin" class="popupbox">
        <div class="popup-title"><span>提现申请成功</span></div>
        <div class="popup-wrapper">
            <div class="popup-content">
                <div class="m-t-10"></div>
                <p class="fs14 pd tac  constraint succeed" >
                    <span class="tip-tag bp0"></span>
                    <span class="tip-text mt10">恭喜您提现申请成功！</span>
                <div class="clear"></div>
                <br>
                <div class="m-t-10"></div>
            </div>
            <div class="popup-control">
                <a href="{{ route('dealer.funds','withdrawal') }}" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                <div class="clear"></div>
                <p class="p-gray mt10 fs14"><span class="red">@{{countDownNum}}</span>秒后自动关闭本弹窗</p>
                <div class="m-t-10"></div>
            </div>
        </div>
    </div>

    <div id="errorWin" class="popupbox">
        <div class="popup-title"><span>温馨提示</span></div>
        <div class="popup-wrapper">
            <div class="popup-content">
                <div class="m-t-10"></div>
                <p class="fs14 pd tac succeed constraint error">
                    <span class="tip-tag bp0"></span>
                    <span class="tip-text mt10">@{{error_msg}}</span>
                <div class="clear"></div>
                <br>
                <div class="m-t-10"></div>
            </div>
            <div class="popup-control">
                <a href="{{ route('dealer.funds','application') }}" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                <div class="clear"></div>
                <p class="p-gray mt10 fs14"><span class="red">@{{countDownNum}}</span>秒后自动关闭本弹窗</p>
                <div class="m-t-10"></div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
            seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-apply-withdrawal", "/webhtml/custom/js/module/common/common"],function(v,u,c){
                u.initMaxprice({{ $account->avaliable_deposit }})
                u.initWithdrawalCount({{$txtCount}});
                u.initWithdrawalFee({{$fee}});
                u.initUrl("{{ route('dealer.applicationWithdrawal') }}","{{ route('dealer.funds','withdrawal') }}","{{ route('dealer.funds','application') }}","{{ route('member.checkSms') }}");
            })
    </script>
@endsection