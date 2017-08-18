<p class="gray-bg pl5">线上支付</p>
<div class="fl wp40 ml20">
    <table class="tbl">
        <caption>
            <img src="{{ asset('webhtml/user/themes/images/into-sub-bg.png') }}" />
        </caption>
        <tr>
            <td width="100">
                <b class="fs14">提交时间</b>
            </td>
            <td>
                <span class="fs14" >{{ $find->created_at }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">支付方式</b>
            </td>
            <td>
                <span class="fs14">支付宝</span>
            </td>
        </tr>
        <!-- <tr>
            <td>
                <b class="fs14">账户</b>
            </td>
            <td>
                <span class="fs14">{{ $find->alipay_user_name }}</span>
            </td>
        </tr> -->
        <tr>
            <td>
                <b class="fs14">提交金额</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($find->money,2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提交用途</b>
            </td>
            <td>
                <span class="fs14">{{ $find->remark }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">交易编号</b>
            </td>
            <td>
                <span class="fs14">{{ $find->item_id }}</span>
            </td>
        </tr>
    </table>
</div>
<div class="fl wp40 ml100">
    <table class="tbl">
        <caption>
            <img src="{{ asset('webhtml/user/themes/images/into-into-bg.png') }}" />
        </caption>
        <tr>
            <td width="100">
                <b class="fs14">确认入账时间</b>
            </td>
            <td>
                <span class="fs14" >{{ $find->updated_at }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">支付方式</b>
            </td>
            <td>
                <span class="fs14">支付宝</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方账号</b>
            </td>
            <td>
                <span class="fs14">{{ $find->alipay_user_name }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <b class="fs14">入账金额</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($find->money,2) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入用途</b>
            </td>
            <td>
                <span class="fs14">{{ $find->remark }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账流水号</b>
            </td>
            <td>
                <span class="fs14">{{ Carbon\Carbon::parse($find->created_at)->format('Ymd-').$find->ua_log_id }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账状态</b>
            </td>
            <td>
                <span class="fs14">已入账</span>
            </td>
        </tr>
    </table>
</div>