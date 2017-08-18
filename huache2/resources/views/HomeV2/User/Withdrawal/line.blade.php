@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p class="blue">提现路线</p>
            <div class="box box-border top-border p10">
                <table class="tbl mt20 tbl-gray-border">
                    <tr>
                        <th><span class="noweight">提现方式</span></th>
                        <th><span class="noweight">收款路线</span></th>
                        <th><span class="noweight">收款方账号户名</span></th>
                        <th><span class="noweight">状态</span></th>
                    </tr>
                    <tbody class="fs14" v-cloak>
                    @if(isset($line))
                        @foreach($line as $item)
                            @if($item->status ==0)<tr class="p-gray">@else<tr>@endif
                            <td>
                                @if($item->line_type ==2) 银行转账 @else  线上支付  @endif
                            </td>
                            <td>
                                @if($item->line_type ==2)
                                    @if($item->bank->province.$item->bank->city)
                                    ({{ $item->bank->province.$item->bank->city }}) {{ $item->bank->bank_address }}
                                        @else
                                        {{$item->account_name}}
                                    @endif
                                @elseif($item->line_type==1)
                                    支付宝
                                @elseif($item->line_type==3)
                                    微信支付
                                @else
                                    银联支付
                                @endif
                            </td>
                            <td>
                                @if($item->line_type ==2)
                                {{splitBrank('<?=$item->account_code?>')}} / {{ $item->bank->bank_register_name }}
                                    @else
                                    {{ $item->account_code }}
                                @endif
                            </td>
                            <td>
                            @if($item->status ==0)
                                  <span>已失效不可用</span>
                            @else
                                <span >
                                    @if($item->line_type ==2)
                                            @if($item->is_default ==1 && $item->activated==1 && $item->is_verify)
                                                <span class="blue">  已绑定账户 </span>
                                            @else
                                                @if($item->status = 1)
                                                     <span class="blue">  可使用
                                                         @if($item->bank->province.$item->bank->city)
                                                             <a class="juhuang" href="{{route('Withdrawal.Bank',$item->bank_id) }}">修改</a>
                                                             @else
                                                             <a class="juhuang" href="{{route('Withdrawal.Bank',$item->bank_id) }}">完善</a>
                                                         @endif
                                                     </span>
                                                @elseif($item->status = 2)
                                                <span class="blue">   已验证可使用</span>
                                                @endif
                                            @endif
                                        @else
                                            已验证可使用
                                    @endif
                                </span>
                             @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="4">
                            <p class="tac mt10 fs14">客官，尚未转入资金或绑定银行账户，所以暂无提现路线哦～</p>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>

                <div class="clear"></div>
                <table class="fs14 mt20">
                    <tr>
                        <td valign="top" width="70">温馨提示：</td>
                        <td>
                            <div class="tal">
                                <p>1.银行转账方式提现，如开户行信息不全只能使用银行“超级网银”向您转账，将产生由您承担的额外银行手续费，且“超级网银”并非所有银行都已接入，如因此导致退款，请恕已扣除手续费无法退还。建议您尽可能完善开户行信息后申请提现。</p>
                                <p>2.各提现路线的详细额度，参见<a href="{{ route('Withdrawal.Ceiling') }}" class="juhuang tdu" target="_blank">提现额度管理</a>。</p>
                                <p>3.如上述收款方信息登记有误或发生账户注销，请向华车客服报错。</p>
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