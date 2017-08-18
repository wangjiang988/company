<!--正常情况-->
@if($find->status==1)
<div class="fl wp40 ml20">
    <table class="tbl" style="width:313px;">
        <caption>
            <img src="{{ asset('webhtml/user/themes/images/into-sub-bg.png') }}" />
        </caption>
        <tr>
            <td width="100">
                <b class="fs14">提交时间</b>
            </td>
            <td width="213">
                <span class="fs14" >{{ $find->created_at }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">{{ $find->item }}</b>
            </td>
            <td>
                <span class="fs14">{{ $find->remark }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">
                    银行账号
                </b>
            </td>
            <td>
                <span class="fs14">
                    {{ $find->bank_account }}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">汇款人户名</b>
            </td>
            <td>
                <span class="fs14">{{ $find->user_bank_name }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提交金额</b>
            </td>
            <td>
                <span class="fs14">￥{{ number_format($find->apply_money,2) }}</span>
            </td>
        </tr>
        <tr style="display: none;">
            <td>
                <b class="fs14">提交用途</b>
            </td>
            <td>
                <span class="fs14">支付买车担保金<br>（订单号：233432343）</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提交凭证</b>
            </td>
            <td>
                <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width:213px;">
                    <span class="fs14"><img width="100" src="{{ $find->voucher }}" /><br><span class="ml5">{{ $find->voucher }}</span></span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">工单编号</b>
            </td>
            <td>
                <span class="fs14">{{ $find->ua_log_id }}</span>
            </td>
        </tr>
    </table>
</div>
<div class="fl wp40 ml100">
    <table class="tbl" style="width:313px;">
        <caption>
            <img src="{{ asset('webhtml/user/themes/images/into-into-bg.png') }}" />
        </caption>
        <tr>
            <td width="100">
                <b class="fs14">确认入账时间</b>
            </td>
            <td width="213">
                <span class="fs14" >{{ $find->updated_at }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方银行</b>
            </td>
            <td>
                <span class="fs14">{{ $find->bank_name }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方账号</b>
            </td>
            <td>
                <span class="fs14">{{ $find->bank_account }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方户名</b>
            </td>
            <td>
                <span class="fs14">{{ $find->user_bank_name }}</span>
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
                <b class="fs14">银行凭证号</b>
            </td>
            <td>
            <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width:213px;">
                <span class="fs14">{{ $find->voucher }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账状态</b>
            </td>
            <td>
                <span class="fs14">
                    已入账
                </span>
            </td>
        </tr>
    </table>
</div>
@else
<div class="clear"></div>
<!--入账状态“正在核实”-->
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
                <span class="fs14" ></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方银行</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方账号</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方户名</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账金额</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入用途</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">银行凭证号</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账状态</b>
            </td>
            <td>
                <span class="fs14">正在核实</span>
            </td>
        </tr>
    </table>
</div>
<!--入账状态“无此款项”场景-->
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
                <span class="fs14" >2016-02-02 12:23:34</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方银行</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方账号</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入方户名</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账金额</b>
            </td>
            <td>
                <span class="fs14">￥0.00</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">转入用途</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">银行凭证号</b>
            </td>
            <td>
                <span class="fs14"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">入账状态</b>
            </td>
            <td>
                <span class="fs14">无此款项</span>
            </td>
        </tr>
    </table>
</div>
<div class="clear"></div>
<!--如果是平台将客户提交拆分而成的已入账转入记录-->
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
                <span class="fs14 p-gray" >2016-02-01 12:23:34</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">汇款银行</b>
            </td>
            <td>
                <span class="fs14 p-gray">中国建设银行</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">汇款人账号</b>
            </td>
            <td>
                <span class="fs14 p-gray">4212 7894 7892 6237 209</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">汇款人户名</b>
            </td>
            <td>
                <span class="fs14 p-gray">上海志和贸易有限公司</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提交金额</b>
            </td>
            <td>
                <span class="fs14 p-gray"></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提交用途</b>
            </td>
            <td>
                <span class="fs14 p-gray">支付买车担保金<br>（订单号：233432343）</span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">提交凭证</b>
            </td>
            <td>
                <span class="fs14 p-gray"><img src="{{ asset('webhtml/user/themes/images/upload-img.png') }}" /><span class="ml5">xxxx图片.jpg</span></span>
            </td>
        </tr>
        <tr>
            <td>
                <b class="fs14">工单编号</b>
            </td>
            <td>
                <span class="fs14 p-gray">1468974465123</span>
            </td>
        </tr>
    </table>
</div>
@endif