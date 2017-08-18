<div class="fl wp40 ml20">
    <table class="tbl">
        <caption>
            <img src="{{ asset('webhtml/user/themes/images/into-apply.png') }}" />
        </caption>
        <tr>
            <td width="100">
                <b class="fs14">申请时间</b>
            </td>
            <td>
                <span class="fs14" >{{ $withdraw->created_at }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提现方式</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->remark }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">收款路线</b>
            </td>
            <td>
                <?=getWichdrawalLineIdToString($withdraw->line_id) ?>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">收款方账号</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->line->account_code}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">收款方户名</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->line->account_name }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提现金额</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($withdraw->money,2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">银行手续费</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($withdraw->fee,2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提现付款金额</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($withdraw->money-$withdraw->fee,2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">工单编号</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->uw_id }}</span>
            </td>
        </tr>
    </table>
</div>

<div class="fl wp40 ml100">
    <table class="tbl">
        <caption>
            <img src="{{ asset('webhtml/user/themes/images/into-handle.png') }}" />
        </caption>
        <tr>
            <td width="100">
                <b class="fs14">提现状态</b>
            </td>
            <td>
                <span class="fs14" >
                    @if(in_array($withdraw->status,[1,5,6]))
                    已完成
                    @elseif(in_array($withdraw->status,[0,2,3]))
                    正在办理
                    @else
                    未成功
                    @endif
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">状态更新时间</b>
            </td>
            <td>
                <span class="fs14">@if(in_array($withdraw->status,[1,5,6])){{ $withdraw->updated_at }}@endif</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">备注</b>
            </td>
            <td>
                @if(in_array($withdraw->status,[1,5,6]))
                    @if($withdraw->voucher !='')
                    <span class="fs14">
                        <img src="{{ $withdraw->voucher }}" />
                        <span class="ml5">{{ $withdraw->voucher }}</span>
                    </span>
                    @else
                        <span class="fs14">{{ $withdraw->remark }}</span>
                    @endif
                @elseif($withdraw->status == 4 )
                    <span class="fs14">提现付款金额已重新转入可用余额</span>
                @endif
            </td>
        </tr>
        </table>
</div>