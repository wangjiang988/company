@extends('_layout.base_dealer_v2')
@section('css','')

@section('content')
    <div class="custom-content custom-content-with-margin nomargin psr">
        <div class="yue-content psr">
            <span class="juhuang weight pl10 fl pre-fix">提现记录</span>
            <a href="{{ route('dealer.funds','application') }}" class="fr btn btn-danger sure btn-auto">> 去提现</a>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            {!! Form::open(['url'=>route('dealer.funds','withdrawal'),'method'=>'get','role'=>'form']) !!}
            <div class="search-area psr">
                <div class="psa btn-control r-23">
                    <button type="submit" class="ml5 btn btn-danger btn-auto mt0">查找</button>
                    <a class=" btn btn-danger sure btn-auto mt0" href="{{ route('dealer.funds','withdrawal') }}">重置</a>
                </div>
                <span>发生时间：</span>
                <div class="form-group psr">
                    <input @focus="selectStartTime" name="start_date" v-model="startTime" :value="startTime" type="text" placeholder="" class="form-control " >
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span class="ml5">~</span>
                <div class="form-group psr ml5">
                    <input @focus="selectEndTime" name="end_date" v-model="endTime" :value="endTime" type="text" placeholder="" class="form-control">
                    <i class="rili" @click="prevFocus"></i>
                </div>
                <span class="ml5">提现状态</span>
                <drop-down def-value="{{ $search['status'] }}" id="into-way-stauts" name="status" @receive-params="getWithdrawalState"  :list="withdrawalStateList" class="ml5 nofl" class-name="btn-dropdown-normal"></drop-dwon>
            </div>
            {!! Form::close() !!}
        </div>



        <table class="tbl tbl-time tbl-gray tac">
            <tr>
                <th width="100" class="fs16">工单编号</th>
                <th width="160" class="fs16">申请时间</th>
                <th width="218" class="fs16">提现路线</th>
                <th width="123" class="fs16">提现金额</th>
                <th width="97" class="fs16">提现状态</th>
                <th width="160" class="fs16">状态更新时间</th>
            </tr>
            @if($pages->total() > 0)
                @foreach($pages->items() as $key => $item)
                    <tr>
                        <td>
                            <p class="p fs14">{{ $item->dwb_id }}</p>
                        </td>
                        <td>
                            <p class="p fs14">{{ $item->created_at }}</p>
                        </td>
                        <td>
                            <p class="p fs14">银行转账—{{chanageStr($item->bank_account,0,-4,'***')}}/{{chanageStr($item->daili_bank_name,3,strlen($item->daili_bank_name),'***')}}</p>
                        </td>
                        <td>
                            <p class="p fs14 tar">￥{{ number_format($item->money,2) }}</p>
                        </td>
                        <td>
                            <p class="p fs14">
                                @if(in_array($item->kefu_confirm_status,[0,3,4]))
                                    正在办理
                                @endif
                                @if(in_array($item->kefu_confirm_status,[1,2])) 已完成 @endif
                                @if($item->kefu_confirm_status ==5)  未成功  @endif
                            </p>
                        </td>
                        <td>
                            <p class="p fs14">
                                @if(in_array($item->kefu_confirm_status,[0,3]))
                                    <a @click="applyStop({{ $item->da_log_id }})" href="javascript:;" class="blue tdu">申请终止</a>
                                @endif
                                @if(in_array($item->kefu_confirm_status,[1,2,5]))
                                    <br />{{ $item->updated_at }}
                                @endif
                            </p>
                        </td>
                    </tr>
                @endforeach
            @else
            <tr>
                <td colspan="6">
                    <div class="mt20"></div>
                    <p class="p fs14 tac">暂无符合您查找条件的提现记录~~</p>
                    <div class="mt20"></div>
                </td>
            </tr>
            @endif
        </table>

        <div class="pageinfo ml200">
            {{ $pages->appends($pageData)->render() }}
        </div>
        <div class="clear "></div>
        <div class="m-t-10" v-for="i in 5"></div>
        <table>
            <tr>
                <td width="80" valign="top">温馨提示：</td>
                <td>
                    <p>1.您可查询最近一年您从华车提现的明细记录。</p>
                    <p>2.选择的时间不能超过当前时间，且结束时间不能早于开始时间。</p>
                    <p>3.提现未成功，该提现付款金额自动重新转入可用余额。</p>
                </td>
            </tr>
        </table>

        <div id="successWin" class="popupbox">
            <div class="popup-title"><span>终止提现申请成功</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac  constraint succeed" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">您的提现申请已终止。</span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="http://www.hc.local:88/dealer/prices/withdrawal" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                    <div class="clear"></div>
                    <p class="p-gray mt10 fs14"><span class="red">@{{countDownNum}}</span>秒后自动刷新提现记录页面</p>
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
                        <span class="tip-text mt10">
                            抱歉，本提现申请已经办理，无法终止了。

                        </span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:window.location.reload()" class="btn btn-s-md btn-danger fs14 do w100 sure">关 闭</a>
                    <div class="clear"></div>
                    <p class="p-gray mt10 fs14"><span class="red">@{{countDownNum}}</span>秒后自动刷新提现记录页面</p>
                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>

        <div id="phoneValite" class="popupbox">
            <div class="popup-title">验证身份终止提现</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14  tac">
                        <div class="tip-text fs14 text-left inline-block">
                    <p class="tac">确定要终止本次提现申请吗？</p>
                    <br>
                    <p class="ml20">手 机 号： {{ changeMobile(getSellerInfo(session('user.member_id'))) }}</p>
                    {!! Form::hidden('phone',getSellerInfo(session('user.member_id'))) !!}
                    <phone-code class="ml20" phone="{{ getSellerInfo(session('user.member_id')) }}" sendurl="{{ route('member.sendSms') }}" sendtype="78760078" iscode="1" @valite-code="getCode"></phone-code>
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
</div>

@endsection

@section('js')
    <script src="{{ asset('/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js') }}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-withdrawal", "/webhtml/custom/js/module/common/common"],function(v,u,c){
            //*页面加载初始化当前时间
            //不设置initEndTime的时间，会自动调用本地时间（当前时间）
            u.initEndTime("{{ $search['end_date'] }}","{{ $search['start_date'] }}");
            u.initUrl("{{ route('dealer.funds','put_withdrawal') }}","{{ route('dealer.funds','withdrawal') }}","{{ route('member.checkSms') }}");
        })
    </script>
@endsection