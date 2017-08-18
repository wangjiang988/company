@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
<div class="yue-content psr">
                        <p>可用余额：￥100.00</p>
                        <p class="gray">账户余额是您在华车平台上可用于支付诚意金/买车担保金的金额。</p>
                        <div class="control-yue">
                            <a href="#" class="btn btn-s-md btn-danger sure bt">充值</a>
                            <a href="#" class="btn btn-s-md btn-danger sure bt">提现</a>
                            <a href="#" class="btn btn-s-md btn-danger oksure bt">提现</a>
                        </div>
                        <hr class="dashed"> 
                        <div class="search-area">
                            <span>开始时间：</span>
                            <div class="form-group psr">
                                <input style="" type="text" placeholder="2015年10月10号" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                <i class="rili"></i>
                            </div>
                            <span class="ml20">结束时间：</span>
                            <div class="form-group psr">
                                <input style="" type="text" placeholder="2015年10月10号" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                <i class="rili"></i>
                            </div>
                            <a href="#" class="ml20 btn btn-s-md btn-danger detial bt">查看明细</a>
                        </div>
                    </div>
                    

                    <div class="content-wapper">

                        <table class="tbl tbl-time">
                            <tr>
                                <th width="248" class="fs16">发生时间</th>
                                <th width="281" class="fs16">资金说明</th>
                                <th width="116" class="fs16">收支金额</th>
                                <th width="110" class="fs16">可用余额</th>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="mt20"></div>
                                    <p class="p fs14">您在选择的时间内并未有任何的交易记录~</p>
                                    <div class="mt20"></div>
                                </td>
                               
                            </tr> 
                        </table>
                        <br>
                        <table class="tbl tbl-time">
                            <tr>
                                <th width="248" class="fs16">发生时间</th>
                                <th width="281" class="fs16">资金说明</th>
                                <th width="116" class="fs16">收支金额</th>
                                <th width="110" class="fs16">可用余额</th>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">/</p>
                                </td>
                                <td>
                                    <p class="p fs14">/</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥1,000.00</p>
                                </td>
                            </tr>
                        </table>
                        <br>

                        <table class="tbl tbl-time">
                            <tr>
                                <th width="248" class="fs16">发生时间</th>
                                <th width="281" class="fs16">资金说明</th>
                                <th width="116" class="fs16">收支金额</th>
                                <th width="110" class="fs16">可用余额</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="time-tag psr">
                                        <span></span>
                                        <p class="p fs14">2016-02-01 12：23：34</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="p fs14">充值（支付宝）</p>
                                </td>
                                <td>
                                    <p class="p fs14">+￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">提现（银行转账<a href="#" class="juhuang">正在办理</a>）</p>
                                </td>
                                <td>
                                    <p class="p fs14">-￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">退还诚意金</p>
                                    <p class="p fs14">（订单号：125678910198）</p>
                                </td>
                                <td>
                                    <p class="p fs14">+￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">退还买车担保金</p>
                                    <p class="p fs14">（订单号：125678910198）</p>
                                </td>
                                <td>
                                    <p class="p fs14">+￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">获得歉意金补偿</p>
                                    <p class="p fs14">（订单号：125678910198）</p>
                                </td>
                                <td>
                                    <p class="p fs14">+￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">支付买车担保金</p>
                                    <p class="p fs14">（订单号：125678910198）</p>
                                </td>
                                <td>
                                    <p class="p fs14">-￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-02-01 12：23：34</p>
                                </td>
                                <td>
                                    <p class="p fs14">支付诚意金</p>
                                    <p class="p fs14">（订单号：125678910198）</p>
                                </td>
                                <td>
                                    <p class="p fs14">-￥100.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥100.00</p>
                                </td>
                            </tr>
                           
                           
                        </table>
                        <div class="pageinfo ml150">
                            <a href="#">上一页</a>
                            <a href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <span class="empty">...</span>
                            <a href="#">下一页</a>
                            <label>共100页，到第</label>
                            <input type="text" class="num" value="1">
                            <label>页</label>
                            <input type="button" class="go" value="Go">
                        </div>
                        <div class="clear "></div>
                        <div class="mt20"></div>
                        <p class="ml10">温馨提示： </p>
                        <p class="ml10">1.您可查询最近一年的账务明细。</p>
                        <p class="ml10">2.选择的时间不能超过当前时间，且结束时间不能早于开始时间。</p>

                    </div> 
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection