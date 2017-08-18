@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p class="blue">申请提现</p>
            <div class="box box-border top-border p10">
                <div class="ml50 fs14 mt10">
                        <p>可用余额：￥{{ number_format($avaliable_deposit,2) }}</p>
                        <p>提现总金额：<span class="blue weight">￥{{ number_format($avaliable_deposit , 2) }}</span></p>
                    <p class="p-gray fs12">为减少您提现收款的等待时间，提现暂时只支持当前全部可用余额的委托申请，即一次清空账户当前可用余额。</p>
                </div>
                <div class="split-blue"></div>

               {!! Form::open(['id'=>'withdrawal_application','role'=>'form']) !!}
                @if(isset($avaliable_deposit))
                    {!! Form::hidden('totalMoney',$avaliable_deposit) !!}
                    {!! Form::hidden('total_money',$avaliable_deposit) !!}
                    {!! Form::hidden('avaliable_deposit',$avaliable_deposit) !!}
                    {!! Form::hidden('user_id',$user_id) !!}
                    {!! Form::hidden('tel',env('SERVER_TEL')) !!}
                @endif
                <table class="tbl mt20">
                    <tr>
                        <th><span class="noweight">提现方式</span></th>
                        <th><span class="noweight">收款路线</span></th>
                        <th><span class="noweight">收款方账号 / 户名</span></th>
                        <th><span class="noweight">提现金额</span></th>
                        <th><span class="noweight">银行手续费</span></th>
                        <th>
                            <div class="psr">
                                <span class="noweight">提现付款金额
                                    <span class="psa juhuang fs12 ml5 help" @mouseover.prevent.stop="tip(0)" @mouseout.prevent.stop="tip(0)">i</span>
                                </span>
                                <div v-cloak class="tooltip fade bottom-left psa" :class="{in:tips[0].isShow}">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">提现付款金额 = 提现金额 - 银行手续费</p>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tbody class="fs14" v-cloak>
                    @if(isset($withdrawalLine))
                        @foreach($withdrawalLine as $key => $item)
                        <tr>
                            <td>
                                @if($item->bank_id==0)
                                    线上支付
                                @else
                                    银行转账
                                @endif
                                {!! Form::hidden('log_id[]',$item->ua_log_id) !!}
                                {!! Form::hidden('uwl_id[]',$item->uwl_id) !!}
                                {!! Form::hidden('ur_id[]',$item->ur_ids) !!}
                                {!! Form::hidden('money[]',$item->recharge_money) !!}
                                {!! Form::hidden('fee[]',$item->fee) !!}
                                {!! Form::hidden('avaliable_money[]',$item->avaliable_money) !!}
                                {!! Form::hidden('remark[]',$item->remark) !!}
                                {!! Form::hidden('type[]',$item->recharge_type) !!}
                                {!! Form::hidden('wichdraw_end_at[]',$item->wichdraw_end_at) !!}
                            </td>
                            <td>
                                @if($item->bank_id==0)
                                    {{ $item->recharge_name }}
                                @else
                                    <?=getTxBank($item->bank_id,$item->bank_name)?>
                                @endif
                            </td>
                            <td>
                                @if($item->bank_id>0)
                                    {{ $item->bank_account }} / {{ $item->user_bank_name }}
                                @else
                                    {{ $item->alipay_user_name }}
                                @endif
                            </td>
                            <td>￥{{ number_format($item->recharge_money,2) }}</td>
                            <td>  @if($item->bank_id>0 && $item->fee)
                                    ￥{{ $item->fee}}
                                  @else
                                    --
                                  @endif  
                                    </td>
                            <td>￥{{ number_format($item->recharge_money- $item->fee,2) }}</td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">
                                <div class="mt20"></div>
                                <p class="p fs14">客官，暂无符合您查找条件的提现记录~~</p>
                                <div class="mt20"></div>
                            </td>
                        </tr>
                    @endif

                    <tr style="display: none;">
                        <td>银行转账</td>
                        <td>
                            <p class="blue">实名认证绑定银行账户</p>
                        </td>
                        <td><a @click="authentication" href="javascript:;" class="juhuang">去绑定</a></td>
                        <td>???</td>
                        <td></td>
                        <td>
                            <div class="psr">
                                <span class="psr juhuang help" @mouseover.prevent.stop="tip(1)" @mouseout.prevent.stop="tip(1)">？</span>
                                <div v-cloak class="tooltip fade bottom-left psa bank-tip" :class="{in:tips[1].isShow}">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0">绑定银行账户后才能提现</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="fs14 mt20">
                    <tr>
                        <td valign="top" width="70">温馨提示：</td>
                        <td>
                            <div class="tal">
                                <p>1.已按提现规则对提现路线和额度进行分配与合并，不可修改，详细说明参见<a href="{{ route('Withdrawal.Ceiling') }}" class="juhuang tdu" target="_blank">提现额度管理</a>  。</p>
                                <p>2.所有收款方账号信息完整，方可一次性提交提现申请。</p>
                                <p>3.银行转账方式提现，如开户行信息不全只能使用银行“超级网银”向您转账，将产生由您承担的额外银行手续费，且“超级网银”并非所有银行都已接入，如因此导致退款，请恕已扣除手续费无法退还。<b>强烈建议您尽可能完善开户行信息后申请提现。</b></p>
                                <p>4.注明需通过绑定实名认证银行账户才能提现的路线，请在完成本华车账户实名认证、以及绑定银行账户的操作流程后，再提交提现申请。</p>
                                <p>5.因提现须人工逐笔核对、办理、耗时较长，我们将在您发起提现申请后的5个工作日内为您完成办理，每笔完成后均有短信提醒，敬请留意。</p>
                            </div>
                        </td>
                    </tr>
                </table>
                <p class="tac red hide fs14" :class="{show:!hasNoAuthentication}">提现路线中信息不全，请绑定银行账户后重新提交～</p>
                <p class="mt20 tac">
                    <button type="button" class="btn btn-danger" @if(isset($withdrawalLine)) @click="withdrawal" @else disabled="disabled" @endif>确认提现</button>
                    <a href="javascript:history.go(-1);" class="btn btn-danger sure ml50">返 回</a>
                </p>
                {!! Form::close() !!}
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>

                <div id="phoneValiteConfirm" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac">
                                <br>
                                <span class="tip-text fs14 text-left inline-block">客官，动动手指完善开户行信息就能少花冤枉钱，是否返回填一下呢？</span>
                            <div class="clear"></div>
                            <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:void(0);" @click="displayHide" class="btn btn-s-md btn-danger fs14 do btn-auto ">返回完善信息</a>
                            <a href="javascript:void(0);" @click="doShowPhoneValite" class="btn btn-s-md btn-danger fs14 sure btn-auto ml20 inline-block ">不返回了，扣就扣吧</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <div id="phoneValite" class="popupbox">
                    <div class="popup-title">验证身份确认提现</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14  tac">

                                <div class="tip-text fs14 text-left inline-block">
                            <p class="tac">确定按照上述方案申请提现吗？</p>
                            <br>
                            <p>手 机 号： {{ changeMobile(Auth::user()->phone) }}</p>
                            {!! Form::hidden('phone',Auth::user()->phone) !!}
                            <phone-code phone="{{ Auth::user()->phone }}" sendurl="{{ route('member.sendSms') }}" max="1000" sendtype="78795069" iscode="1" money="{{ $avaliable_deposit }}" tel="{{env('SERVER_TEL')}}" @valite-code="getCode" @valite-send-data='getData'></phone-code>
                            <div class="clear"></div>
                            <p class="tal ml100 mt10 red hide" :class="{show:isEmtpy}">请输入验证码</p>
                            <p class="tal ml100 mt10 red hide" :class="{show:isError}">验证码有误，请重新输入~</p>
                            <p class="tal ml100 mt10 red hide" :class="{show:isCodeError}">@{{error_msg}}</p>
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
                    </div>
                </div>
            </div>

            <div id="authentication" class="popupbox">
                <div class="popup-title">温馨提示</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14  tac">
                            <div class="tip-text fs14 text-left inline-block">
                        <p class="tac">客官，非常抱歉，绑定银行账户必须先完成实名认证哦～</p>
                    </div>
                    <div class="clear"></div>
                    <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="{{ route('auth.addShowIdCart') }}" class="btn btn-s-md btn-danger fs14 do btn-auto">立即实名认证</a>
                    <div class="clear"></div>
                    <p class="tac fs14 mt20"><span class="juhuang">@{{simpleCountDown}}</span>秒后自动跳转实名认证页面</p>
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
        var _checkIdCartUrl = "{{ route('user.isIdCart') }}";
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-withdrawal", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.initUrl("{{ route('Withdrawal.Application') }}",
                "{{ route('auth.addShowIdCart') }}",
                "{{ route('user.bank') }}",
                "{{ route('my.Withdrawal') }}",
                "{{ route('member.checkSms') }}"
            );
        })
    </script>
@endsection