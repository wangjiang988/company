@extends('HomeV2._layout.base')
@section('css')
    <link href="/webhtml/common/css/bootstrap.css" rel="stylesheet" />
    <link href="/webhtml/common/css/common.css" rel="stylesheet" />
    <link href="/webhtml/order/themes/item-fix.css" rel="stylesheet" />
@endsection
@section('nav')
   @include('HomeV2._layout.header2')
@endsection
@section('content')

    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content" style="left: 187px;top: 55px;">
                    <small class="juhuang">等待支付</small>
                    <i></i>
                    <small>等待预约</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <div class="wapper has-min-step">
            <p>尊敬的客户：</p>
            <p class="ti">该由您支付剩下的买车担保金啦！越快付完越早提车哦～</p>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class="fs14 weight">订单号：{{$order['order_sn']}}</td>
                    <td width="50%" class="fs14">
                        <span class="weight">订单时间：</span>{{$order['created_at']}}
                    </td>
                </tr>
            </table>
            <div class="clear m-t-10"></div>
            <ul class="pdi-order-ul border ">
                <p class="fs14 ml10">
                    <?php $gc_name = explode(' &gt;',$order['gc_name']);?>
                    <span>路虎</span>
                    <span class="ml5"></span>
                    <span class="ml5">{{$gc_name[0]}}</span>
                    <span class="ml5"></span>
                    <span class="ml5">{{$gc_name[1]}} {{$gc_name[2]}}</span>
                    <span class="ml30">{{$order_info['body_color']}}</span>
                </p>
            </ul>

            <p class="tac m-t-10"><a href="{{route('cart.order_detail',['id'=>$order['id']])}}" class="juhuang tdu ">查看订单总详情</a></p>

            @if($mode==1)
            <div class="box">
                <hr class="dashed">
                <table class="tbl">
                    <tr>
                        <td><p class="tal fs14"><b>买车担保金余款</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($order['sponsion_price']-$order['earnest_price'],2)}}元（买车担保金￥{{number_format($order['sponsion_price'],2)}} — 已付诚意金￥{{number_format($order['earnest_price'],2)}}元）</p></td>
                    </tr>
                    <tr>
                        <td><p class="tal fs14"><b>您账户的可用余额</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($user_account['avaliable_deposit'],2)}}元<a href="{{route('user.home')}}" class="juhuang">（查看）</a></p></td>
                    </tr>
                </table>
                <p>您的可用余额足够支付买车担保金余额，请在{{$pay_time_limit}}  前及时确认支付。</p>
                <div class="time m-t-10">
                    <div class="jishi countdown jishi-long">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                    <span class="text inline-block-noi red hide timeout-text">已超时:</span>
                    <div class="jishi jishi-long jishi-out timeout hide inline-block-noi">

                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <p class="center">
                    <a href="{{route('pay.deposit',['id'=>$order['id']])}}" class="btn btn-s-md btn-danger fs16">我要支付</a>
                </p>
            </div>
            @endif

            @if($mode==2)
            <div class="box">
                <hr class="dashed">
                <table class="tbl">
                    <tr>
                        <td><p class="tal fs14"><b>买车担保金余款</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($order['sponsion_price']-$order['earnest_price'],2)}}元（买车担保金{{number_format($order['sponsion_price'],2)}} — 已付诚意金￥{{number_format($order['earnest_price'],2)}}元）</p></td>
                    </tr>
                    <tr>
                        <td><p class="tal fs14"><b>您账户的可用余额</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($user_account['avaliable_deposit'],2)}}元<a href="{{route('user.home')}}" class="juhuang">（查看）</a></p></td>
                    </tr>
                </table>
                <div class="psr">
                    <p class="lh30 fs14">一步到位完成支付，建议您采用<b class="tdu">银行转账</b>方式，包括通过网上银行、手机银行或银行柜面向华车平台直接转账。</p>
                    <p class="lh30 fs14"><b class="tdu">线上支付</b>方式，因不同支付机构可能存在某些支付限额，如影响到您的支付成功，可分多笔支付。</p>
                    <p class="lh30 fs14">第一笔买车担保金余款须在24小时内提交支付！提交成功后尾款支付时限可延长到第三个自然日（{{date("Y-m-d",strtotime($order['updated_at'])+24*3600*3)}}）的24点。</p>
                    <div class="m-t-10"></div>
                    <p class="tac fs14">首笔买车担保金余款提交支付时限：{{$pay_time_limit}}</p>
                    <div class="m-t-10"></div>
                    <div class="time psr">
                        <div class="jishi jishi2 countdown jishi-long inline-block-noi ml-150">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>

                        <div class="jishi text-left jishi2 jishi-long jishi-out timeout hide inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                        <span class="text inline-block-noi red hide timeout-text timeout-text-psa">(已超时)</span>
                    </div>
                    <div class="m-t-10"></div>
                    <div class="clear"></div>
                    <p class="lh30 fs14">请选择您的支付方式，并在上方时限内完成支付。超时未足额支付，根据华车平台规则将判定您违约。违约结果：<b>订单终止、诚意金赔偿售方，</b><br>但已付的其他买车担保金将退还您的可用余额。</p>
                </div>

                <p class="center m-t-10">
                    <a href="{{route('paydeposit.offline',['id'=>$order['id']])}}" class="btn btn-s-md btn-danger ml20 btn-juhuang fs16">>> 银行转账</a>
                    <a href="{{route('paydeposit.online',['id'=>$order['id']])}}" class="btn btn-s-md btn-danger ml20 btn-juhuang fs16">>> 线上支付</a>
                </p>

            </div>
            @endif

            @if($mode==3)
            <div class="box">
                <hr class="dashed">
                <table class="tbl">
                    <tr>
                        <td><p class="tal fs14"><b>买车担保金余款</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($order['sponsion_price']-$order['earnest_price'],2)}}元（买车担保金￥{{number_format($order['sponsion_price'],2)}} — 已付诚意金￥{{number_format($order['earnest_price'],2)}}元）</p></td>
                    </tr>
                    <tr>
                        <td><p class="tal fs14"><b>您账户的可用余额</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($user_account['avaliable_deposit'],2)}}元<a href="{{route('user.home')}}" class="juhuang">（查看）</a></p></td>
                    </tr>
                </table>
                <p>您可使用您的可用余额支付第一笔买车担保金余款，请于{{$pay_time_limit}}前及时确认支付。</p>
                <div class="time m-t-10">
                    <div class="jishi countdown jishi-long">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                    <span class="text inline-block-noi red hide timeout-text">已超时:</span>
                    <div class="jishi jishi-long jishi-out timeout hide inline-block-noi">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <div class="m-t-10"></div>
                <p class="center">
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs16">使用可用余额支付</a>
                </p>
            </div>
            @endif

            @if($mode==4)
            <div class="box">
                <hr class="dashed">
                <table class="tbl2 wp100">
                    <tr>
                        <td width="50%"><p class="tal fs14">买车担保金：人民币{{number_format($order['sponsion_price'],2)}}元</p></td>
                        <td><p class="tal fs14">您账户的可用余额：人民币{{number_format($user_account['avaliable_deposit'],2)}}元<a href="{{route('user.home')}}" class="juhuang">（查看）</a></p></td>
                    </tr>
                    <tr>
                        <td width="50%"><p class="tal fs14">已提交支付金额：人民币{{number_format($pay_log['prepaid'],2)}}元<a href="{{route('my.myBalance')}}" class="juhuang">（查看）</a></p></td>
                        <td>
                            <div class="psr psr-wapper">
                                <p class="tal fs14">
                                    <span class="psr">已入账金额：<span class="tip">i</span></span>人民币{{number_format($pay_log['confirmed_payment'],2)}}元
                                </p>
                                <div class="tooltip fade bottom psa rz-bottom-fix" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0i">平台已核实的到账实际金额</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <div class="psr psr-wapper">
                                <p class="tal fs14">
                                    <span class="psr">待付金额：<span class="tip">i</span></span>人民币<span class="weight juhuang fs16">{{number_format($pay_log['non_payment'],2)}}</span>元
                                </p>
                                <div class="tooltip fade bottom psa bottom-fix" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0i">待付金额=买车担保金-已提交支付金额</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="psr psr-wapper">
                                <p class="tal fs14">
                                    <span class="psr">未入账金额：<span class="tip">i</span></span>人民币{{number_format($pay_log['non_confirm_payment'],2)}}元
                                </p>
                                <div class="tooltip fade bottom psa" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0i">未入账金额=买车担保金-已入账金额</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                </table>

                <p class="tac">支付时限：{{$pay_time_limit}}</p>
                <div class="time m-t-10">
                    <span class="text inline-block-noi countdown">剩余时间：</span>
                    <div class="jishi countdown jishi-long inline-block-noi">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                    <span class="text inline-block-noi red hide timeout-text">已超时:</span>
                    <div class="jishi jishi-long jishi-out timeout hide inline-block-noi">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>
                <br><br>
                <p>温馨提示：超时未足额支付，根据华车平台规则将判定您违约。违约结果：订单终止、诚意金赔偿售方、已付的其他买车担保金退还您的可用余额。</p>

                <p class="center m-t-10">
                    <a href="{{route('pay.deposit',['id'=>$order['id']])}}" class="btn btn-s-md btn-danger fs16">使用可用余额支付</a>
                    <a href="{{route('paydeposit.offline',['id'=>$order['id']])}}" class="btn btn-s-md btn-danger ml50 btn-juhuang fs16">>> 银行转账</a>
                    <a href="{{route('paydeposit.online',['id'=>$order['id']])}}" class="btn btn-s-md btn-danger ml50 btn-juhuang fs16">>> 线上支付</a>
                </p>
            </div>
            @endif

            @if($mode==5)
            <hr class="dashed">
            <div class="clear"></div>
            <div class="box">
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <table class="tbl">
                    <tr>
                        <td><p class="tal fs14"><b>买车担保金余款</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($order['sponsion_price']-$order['earnest_price'],2)}}元（买车担保金￥{{number_format($order['sponsion_price'])}} — 已付诚意金￥{{number_format($order['earnest_price'],2)}}元）</p></td>
                    </tr>
                    <tr>
                        <td><p class="tal fs14"><b>您账户的可用余额</b></p></td>
                        <td><p class="tal fs14">人民币{{number_format($user_account['avaliable_deposit'],2)}}元</p></td>
                    </tr>
                </table>
                <div class="m-t-10"></div>
                <p class="tac juhuang show" :class="{hidenone:isSelectVouchers}">恭喜您即将享受福利：当前可使用最大金额代金券自动为您奉上～</p>
                <form method="post" id="payDeposit" action="{{route('pay.deposit',['id'=>$order['id']])}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="voucher_id" v-model="voucher_id" :value="voucher_id">
                </form>

                <table width="580" class="mauto fs14">
                    <tr>
                        <td>
                            <label class="m0i"><input checked="" disabled="" type="checkbox" readonly="" name="" id=""></label>
                        </td>
                        <td class="tac ">
                            <div class="vouchers-wrapper" v-cloak>
                                <span class="juhuang weight">@{{vouchersObj.price}}</span>
                                <span class="ml50">@{{vouchersObj.type}}</span>
                                <span class="ml50"><b>有效期至</b><span class="juhuang">@{{vouchersObj.time}}</span> <span class="ml5">24点</span></span>
                            </div>
                        </td>
                        <td>
                            <a @click="chanceVouchers" href="javascript:;" class="juhuang">换券</a>
                        </td>
                    </tr>
                </table>
                <div class="psr fs14 mt20">
                    <p>使用代金券支付第一笔买车担保金余款后，其他余款的支付时限将延长到第三个自然日的24点。</p>
                    <span>请注意第一笔买车担保金余款的支付时限：{{$pay_time_limit}}。</span>
                    <div class="time m-t-10 psa vouchers-time">
                        <div class="jishi countdown jishi2 inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                        <div class="jishi jishi2 jishi-out timeout hide inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                    </div>
                </div>
                <p class="center mt50">
                    <a href="javascript:;" @click="vouchersPay" class="btn btn-s-md btn-danger fs16">使用该代金券支付</a>
                </p>
            </div>
            @endif

            @if($mode==6)
            <hr class="dashed">
            <div class="clear"></div>
            <div class="box">
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <table class="tbl2 wp100">
                    <tr>
                        <td width="50%"><p class="tal fs14">买车担保金：人民币{{number_format($order['sponsion_price'],2)}}元</p></td>
                        <td><p class="tal fs14">您账户的可用余额：人民币{{number_format($user_account['avaliable_deposit'],2)}}元<a href="{{route('user.home')}}" class="juhuang">（查看）</a></p></td>
                    </tr>
                    <tr>
                        <td width="50%"><p class="tal fs14">已提交支付金额：人民币{{number_format($pay_log['prepaid'],2)}}元<a href="{{route('my.myBalance')}}" class="juhuang">（查看）</a></p></td>
                        <td>
                            <div class="psr psr-wapper">
                                <p class="tal fs14">
                                    <span class="psr">已入账金额：<span class="tip">i</span></span>人民币{{number_format($pay_log['confirmed_payment'],2)}}元
                                </p>
                                <div class="tooltip fade bottom psa rz-bottom-fix" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0i">平台已核实的到账实际金额</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td >
                            <div class="psr psr-wapper">
                                <p class="tal fs14">
                                    <span class="psr">待付金额：<span class="tip">i</span></span>人民币<span class="weight juhuang fs16">{{number_format($pay_log['non_payment'],2)}}</span>元
                                </p>
                                <div class="tooltip fade bottom psa bottom-fix" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0i">待付金额=买车担保金-已提交支付金额</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="psr psr-wapper">
                                <p class="tal fs14">
                                    <span class="psr">未入账金额：<span class="tip">i</span></span>人民币{{number_format($pay_log['non_confirm_payment'],2)}}元
                                </p>
                                <div class="tooltip fade bottom psa" >
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner">
                                        <p class="m0i">未入账金额=买车担保金-已入账金额</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                </table>
                <div class="m-t-10"></div>
                <p class="tac juhuang show" :class="{hidenone:isSelectVouchers}">恭喜您即将享受福利：当前可使用最大金额代金券自动为您奉上～</p>
                <form method="post" id="payDeposit" action="{{route('pay.deposit',['id'=>$order['id']])}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="voucher_id" v-model="voucher_id" :value="voucher_id">
                </form>

                <table width="580" class="mauto fs14">
                    <tr>
                        <td>
                            <label class="m0i"><input checked="" disabled="" type="checkbox" readonly="" name="" id=""></label>
                        </td>
                        <td class="tac ">
                            <div class="vouchers-wrapper" v-cloak>
                                <span class="juhuang weight">@{{vouchersObj.price}}</span>
                                <span class="ml50">@{{vouchersObj.type}}</span>
                                <span class="ml50"><b>有效期至</b><span class="juhuang">@{{vouchersObj.time}}</span> <span class="ml5">24点</span></span>
                            </div>
                        </td>
                        <td>
                            <a @click="chanceVouchers" href="javascript:;" class="juhuang">换券</a>
                        </td>
                    </tr>
                </table>
                <div class="psr fs14 mt20">
                    <p class="tac">支付时限：{{$pay_time_limit}}</p>
                    <div class="time m-t-10">
                        <span class="text inline-block-noi countdown">剩余时间：</span>
                        <div class="jishi jishi2 countdown inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                        <span class="text inline-block-noi red hide timeout-text">已超时:</span>
                        <div class="jishi jishi2 jishi-out timeout hide inline-block-noi">
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                            <span class="fuhao">:</span>
                            <span>0</span>
                            <span>0</span>
                        </div>
                    </div>
                </div>
                <div class="m-t-10"></div>
                <p class="fs14">温馨提示：超时未足额支付，根据华车平台规则将判定您违约。违约结果：订单终止、诚意金赔偿售方，但已付的其他买车担保金将退还您的可用余额。</p>
                <div class="m-t-10"></div>
                <p class="center mt50">
                    <a href="javascript:;" @click="vouchersPay" class="btn btn-s-md btn-danger fs16">使用该代金券支付</a>
                </p>
            </div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            @endif

            <div id="vouchersWin" class="popupbox">
                <div class="popup-title">更换代金券</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14 pd tac">
                            <span class="tip-tag bp0"></span>
                        <div class="tip-text mt10 ml20">
                            <span class="fl mt5">请选择：</span>
                            <drop-down ref="vouchers" @receive-params="getVouchers" name="hf" id="hf-id" def-value="--请选择--" class-name="btn-dropdown-default" :list="vouchersList"></drop-down>
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

            <div id="successWin" class="popupbox">
                <div class="popup-title">订购成功</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14 pd tac  constraint" :class="{succeed:successStatus!=0,error:successStatus==0,mauto:successStatus==0}">
                            <span class="tip-tag bp0"></span>
                            <span class="tip-text mt10" v-show="successStatus == 0">很抱歉，该代金券使用未成功，<br>请重新支付～</span>
                            <span class="tip-text mt10" v-show="successStatus == 1">恭喜您，使用代金券成功！</span>
                            <span class="tip-text mt10" v-show="successStatus == 2">恭喜您，使用代金券成功！<br>本代金券未用完金额仍属于您，<br>已生成一张新的代金券～</span>
                        <div class="clear"></div>
                        <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="reload" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                        <div class="clear"></div>
                        <p class="p-gray mt10"><span class="red">@{{countDownNum}}</span>秒后自动关闭本弹窗</p>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-item-pay", "/webhtml/order/js/module/common/common.js"],function(v,u,c){
            u.init('{{date('Y-m-d H:i:s',time())}}','{{date('Y-m-d',strtotime($order['rockon_time'])).' 24:0:0'}}',function(){
            })
            u.initVouchersList(<?=json_encode($voucher);?>)
            u.initVouchersObj(<?=json_encode($vouchers_default);?>)

            $(".dropdown-toggle").css({height:"34px"})
        });

    </script>
@endsection

