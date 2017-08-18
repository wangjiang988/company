<div class="ml20">
    <table class="tbl" style="width: initial; margin:0 auto;">
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
                <span class="fs14">线上支付</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">收款路线</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->pay_type }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">收款方账户</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->alipay_user_name }}</span>
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
                <b class="fs14">提现付款金额</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($withdraw->money,2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">工单编号</b>
            </td>
            <td>
                <span class="fs14">{{ $withdraw->ulog_id }}</span>
            </td>
        </tr>
    </table>
</div>

<table class="tbl">
        <caption class="psr">
            <img src="{{ asset('webhtml/user/themes/images/into-detail.png') }}" />
            <img src="{{ asset('webhtml/user/themes/images/into-handle.png') }}" class="psa right240" />
        </caption>
        <tr>
            <th width="140">
                <b class="fs14">工单编号</b>
            </th>
            <th width="96">
                <span class="fs14" >提现金额</span>
            </th>
            <th width="96">
                <span class="fs14" >提现付款金额</span>
            </th>
            <th width="196">
                <span class="fs14" >对应入账流水号</span>
            </th>
            <th width="85">
                <span class="fs14" >提现状态</span>
            </th>
            <th width="155">
                <span class="fs14" >状态更新时间</span>
            </th>
            <th width="130">
                <span class="fs14" >备注</span>
            </th>
        </tr>
        @if(isset($withdraw->recharges))
            @foreach($withdraw->recharges as $sub_item)
            <tr>
                <td><span class="fs14">{{$withdraw->ulog_id}}</span></td>
                <td><span class="fs14">￥{{$sub_item->money}}</span></td>
                <td><span class="fs14">￥{{$sub_item->recharge_money}}</span></td>
                <td class="word"><span class="fs14 word">{{$sub_item->trade_no}}</span></td>
                <td><span class="fs14">{{get_user_withdraw_status($withdraw->status)}}</span></td>
                <td><span class="fs14">{{$withdraw->updated_at}}</span></td>
                <td><span class="fs14">{{$withdraw->remark}}</span></td>
            </tr>
            @endforeach
        @endif
    </table>