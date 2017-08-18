@extends('HomeV2._layout.base2')
@section('css')
  <link href="{{asset('/themes/search.css')}}" rel="stylesheet" />
  <link href="{{asset('webhtml/order/themes/calc.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('_layout.nav')
@endsection
@section('content')
    <div class="box-wrapper">
        <div class="search-panel-box">
            <div class="container m-t-86 pos-rlt" style="width:998px">

                <div class="search-def-option">
                    <ul>

                        <li>
                            <label>品牌：</label>
                            <dl>
                                <dt class="s-area">

                                    <p>{{$brand[0]}}</p>
                                </dt>

                            </dl>
                        </li>

                        <li class="ml">
                            <label>车系：</label>
                            <dl>
                                <dt class="s-area">

                                    <p>{{$brand[1]}}</p>
                                </dt>
                            </dl>
                        </li>

                        <li class="ml">
                            <label>车型规格：</label>
                            <dl>
                                <dt class="s-chexing">
                                    <p>{{$brand[2]}}</p>
                                </dt>

                            </dl>
                        </li>

                        <li class="clear"></li>

                        <li>
                            <label>上牌城市：</label>
                            <dl>
                                <dt class="s-chexing">
                                    <p>{{$city}} <span>
                                    @if($xianpai==1)
                                    （限牌城市的牌照指标须自备）
                                    @endif</span></p>
                                </dt>
                            </dl>
                        </li>

                        <li class="ml">
                            <label>车辆用途：</label>
                            <dl>
                                <drop-down @receive-params="getYongTu" def-value="非营业个人客车（私家车）" :list="yongtuList"></drop-down>
                            </dl>
                        </li>

                        <li class="clear"></li>
                    </ul>
                </div>
            </div>
        </div>

        <form class="CalcForm" ms-controller="calc" name="CalcForm">
            <div class="container calc-wrapper">
                <div class="info pos-rlt tac">
                    <p class="fs18"><b>落地价计算器</b></p>
                    <p>车辆落地估算花费总金额： <span class="fs20">￥<span class="calc-total mr77"></span></span>总预备资金：<span class="fs18">￥<span class="calc-prev-total"></span></span></p>
                    <small>(此结果仅供参考，部分项目各地有差异，实际花费以发生项目实际缴费为准)</small>
                </div>

                <table cellspacing="0" cellpadding="0" class="tbl" v-cloak>
                    <tbody>
                        <tr>

                            <th width="188" rowspan="2">项目名称</th>
                            <th width="435" colspan="3">花费金额</th>
                            <th width="276" rowspan="2">备注</th>
                        </tr>
                        <tr>
                            <th width="140">增</th>
                            <th width="140">减</th>
                            <th width="140">平</th>
                        </tr>
                        <tr>
                            <td class="prev-title">华车车价</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <span class="fixed-value">{{number_format($car_hw_price,2)}}</span>
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>车辆开票价+华车服务费</td>
                        </tr>
                        <tr>
                            <td class="prev-title">车辆购置税</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value empty" :class="{txt:!isQita}" value="{{number_format($gouzhishui,2)}}" data-value="{{number_format($gouzhishui,2)}}"/>
                                    <!-- <span class="fixed-value">19,914.53</span> -->
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><span :class="{hide:isQita}">购车款/(1+17%)×购置税率({{$gzsRate}}%)</span><span class="hide" :class="{show:isQita}">请填写金额</span> </td>
                        </tr>
                        <tr>
                            <td class="prev-title">车船使用税</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value empty" :class="{txt:!isQita}" value="{{number_format($chechuanshui,2)}}" data-value="{{number_format($chechuanshui,2)}}" />
                                    <!-- <span class="fixed-value">480.00</span> -->
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><span :class="{hide:isQita}">排量：{{$pailiang}}L</span><span class="hide" :class="{show:isQita}">请填写金额</span> </td>
                        </tr>
                        @if($shangpai['type']==1)
                        <tr>
                            <td class="prev-title">上牌费</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value" value="" />
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>请填写您估算的金额</td>
                        </tr>
                        @else
                        <tr>
                            <td class="prev-title">上牌费</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value" value="{{number_format($shangpai['price'],2)}}" />
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>如您亲自上牌，可修改金额</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="prev-title">上临时牌照费</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value " value="" placeholder="{{number_format($linpai['price'],2)}}" />
                                    &nbsp;
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>如您需办理，请填写金额</td>
                        </tr>
                        <tr>
                            <td class="prev-title">交强险</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value empty" :class="{txt:!isQita}" value="{{number_format($jiaoqiangxian,2)}}" data-value="{{number_format($jiaoqiangxian,2)}}" />
                                    <!-- <span class="fixed-value">1,000.00</span> -->
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><span :class="{hide:isQita}">座位数：{{$seat_num}}</span><span class="hide" :class="{show:isQita}">请填写金额</span> </td>
                        </tr>
                        <tr>
                            <td class="prev-title">商业车险</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <input type="text" class="dym-value " value="" />
                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>请填写金额</td>
                        </tr>

                        <tr>
                            <td class="prev-title">售方其他杂费</td>
                            <td>
                                <p>
                                    <span>+￥</span>
                                    <span class="fixed-value">{{number_format($seller_other_price,2)}}</span>

                                </p>
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="prev-title">其他费用</td>
                            <td>
                                <p>
                                    <span class="">+￥</span>
                                    <input type="text" class="dym-value " value="" />
                                    &nbsp;
                                </p>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                            <td>&nbsp;</td>
                            <td>可填写您估算的其他花费金额</td>
                        </tr>

                        <tr>
                            <td class="prev-title">其他补贴</td>
                            <td>

                                &nbsp;
                            </td>
                            <td>
                                <p>
                                    <span class="">-￥</span>
                                    <input type="text" class="dym-subsidy " value="" />

                                </p>

                            </td>
                            <td>&nbsp;</td>
                            <td>可填写您可得各项补贴金额</td>
                        </tr>
                        <tr>
                            <td class="prev-title">买车担保金</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>
                                <p>
                                    <span>/￥</span>
                                    <span class="flat-value">{{number_format($doposit_price,2)}}</span>
                                </p>
                            </td>
                            <td>若无违约，此金额最终分文不少</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="prev-title"><b>各小项总计</b></td>
                            <td>
                                <p>
                                    <span><b>+</b>￥</span>
                                    <span class="add-total"></span>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <span>-￥</span>
                                    <span class="minus-total"></span>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <span>/￥</span>
                                    <span class="fixed-total"></span>
                                </p>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </tfoot>
                </table>

                <div class="info pos-rlt ">

                    <p>车辆落地估算花费总金额：<span class="fs20">￥<span class="calc-total"></span></span>（增项合计—减项合计，平项无违约全部返还故未计入 ）</p>
                    <p>总预备资金：<span class="fs18">￥<span class="calc-prev-total"></span></span>（增项合计+平项合计，减项可能延后故未扣除）</p>
                    <small>(此结果仅供参考，部分项目各地有差异，实际花费以发生项目实际缴费为准)</small>
                    <a href="落地价计算器.html" class="reset">重置</a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-calc", "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection
