@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p class="blue">提现额度</p>
            <div class="box box-border top-border p10">
                <div class="ml20 fs14 mt10">
                    <p v-cloak><b>待用提现额度合计：￥{{ number_format($avaliable_deposit,2) }}</b></p>
                    <p class="p-gray fs12">待用提现额度之和</p>
                </div>
                <p class="blue">提现收款方额度明细说明</p>
                <div class="split-blue"></div>
                <span class="pull-right">
                    <label><input checked="" type="checkbox" name="" id=""><span class="noweight blue fs14 ml5">只看有排序</span></label>
                </span>
                <div class="clear"></div>
                @if($avaliable_deposit <=0)
                <!--没有记录就显示下面的话-->
                <div class="box list-empty-wrapper">
                    <p class="tac weight fs16 mt50">客官，您向华车转入资金后才有提现额度哦～</p>
                    <div class="m-t-10" v-for="i in 5"></div>
                </div>
                @else
                <table class="tbl mt20 tbl-gray-border">
                    <tr>
                        <th><span class="noweight">转入时间</span></th>
                        <th><span class="noweight">转入方式</span></th>
                        <th><span class="noweight">转入方<br>（提现收款方）</span></th>
                        <th><span class="noweight">提现额度有效时限</span></th>
                        <th><span class="noweight">转入金额<br>（提现总额度）</span></th>
                        <th><span class="noweight">已提现额度</span></th>
                        <th><span class="noweight">待用提现额度</span></th>
                        <th><span class="noweight">提现排序</span></th>
                    </tr>
                    <tbody class="fs14" v-cloak>
                    @foreach($withdrawalCeiling->items() as $key => $item)
                    <tr>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            @if($item->recharge_type==2)银行转账@else 线上支付 @endif
                        </td>
                        <td class="blue-bg">
                            @if($item->recharge_type==2)
                                ({{ $item->bank_name }}){{ chanageStr($item->bank_account,3,-4) }}
                                @else
                                {{ $item->recharge_type_name}}{{ chanageStr($item->alipay_user_name,3,-4)}}
                            @endif
                        </td>
                        <td>{{ $item->consume->wichdraw_end_at }}</td>
                        <td>￥{{ number_format($item->recharge_money,2) }}</td>
                        <td>
                            @if(isset($item->withdraw))
                                ￥{{ number_format($item->withdraw->money,2) }}
                            @else
                                @{{formatMoney(0,2,"￥")}}
                            @endif
                        </td>
                        <td class="blue-bg">@if(isset($item->withdraw))
                                                ￥{{ number_format($item->recharge_money-$item->withdraw->money,2) }}
                                            @else
                                                ￥{{ number_format($item->recharge_money,2) }}
                                            @endif
                        </td>
                        <td class="blue-bg">{{ $key+1 }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pageinfo ml200">
                    {{ $withdrawalCeiling->render() }}
                </div>
                <div class="clear"></div>
                @endif

                <table class="fs14 mt20">
                    <tr>
                        <td valign="top" width="70">温馨提示：</td>
                        <td>
                            <div class="tal">
                                <p>1.根据银行和税务相关监管的要求，华车提现资金的收款方原则上应与转入方账户一致。</p>
                                <p>2.提现额度的使用，按照转入时间从近到远的顺序进行，即新转入的先使用提现额度。</p>
                                <p>3.线上支付的提现额度有效时限，视您所用的支付机构退款有效期而定；所有超过一年的转入款，待用提现额度自动归零。</p>
                                <p>4.提现收款方的详细信息，参见<a href="{{ route('Withdrawal.Line') }}" class="juhuang tdu" target="_blank">提现路线管理</a>。</p>
                                <p>5.在提现时如待用提现额度不足，可通过绑定实名认证华车账号主人的银行账户，从而完成剩余金额的提现。</p>
                            </div>
                        </td>
                    </tr>
                </table>
                <p class="mt20 tac">
                    <a href="javascript:history.go(-1);" class="btn btn-danger sure ml50">关闭</a>
                </p>

                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common"],function(v,u,c){


        })
    </script>
@endsection