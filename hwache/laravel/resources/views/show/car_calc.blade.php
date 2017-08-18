@extends('_layout.base')
@section('css')
<link href="{{asset('themes/search.css')}}" rel="stylesheet" />
<link href="{{asset('themes/calc.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ route('user.ucenter') }}"><span>{{ $_SESSION['member_name'] }}</span></a> </label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
<div class="search-panel-box">
        <div class="container m-t-86 pos-rlt" >

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
                                <p>{{$city}} 
                                <span>
                                	@if($xianpai==1)
                                	（限牌城市的牌照指标须自备）
                                	@endif
                                </span></p>
                            </dt>
                        </dl>
                    </li>
                    <li class="ml">
                        <label>车辆用途：</label>
                        <dl>
                            <dt class="s-chexing">
             					<p> 
					             @if($buytype==0)                   
                                    非营业个人客车
					             @else
					             	非营业企业客车
					             @endif
                                </p>
                            </dt>
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

            <table cellspacing="0" cellpadding="0" class="tbl">
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
                        <td>华车车价</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <span class="fixed-value">{{number_format($car_hw_price,2)}}</span>
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>车辆开票金额+服务费</td>
                    </tr>
                    <tr>
                        <td>车辆购置税</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <span class="fixed-value">{{number_format($gouzhishui,2)}}</span>
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>购车款/(1+17%)×购置税率({{$gzsRate}}%) </td>
                    </tr>
                    <tr>
                        <td>车船使用税</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <span class="fixed-value">{{number_format($chechuanshui,2)}}</span>
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>排量：{{$pailiang}}L</td>
                    </tr>
                    @if($shangpai['type']==2 || $shangpai['type']==4)
                    <tr>
                        <td>上牌费</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <span class="fixed-value">{{number_format($shangpai['price'],2)}}</span>
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?=$shangpai['type']==2?'指定上牌':'接受安排';?></td>
                    </tr>
                    @elseif($shangpai['type']==3)
                    <tr>
                        <td>上牌费</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <input type="text" class="dym-value" value="{{number_format($shangpai['price'],2)}}" />
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>自选上牌</td>
                    </tr>
                    @elseif($shangpai['type']==1)
                    <tr>
                        <td>上牌费</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <input type="text" class="dym-value" value="0" />
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>本人上牌，请填写您估算的金额</td>
                    </tr>
                    @endif
                    
                    @if($linpai['type']==1 && $shangpai['type']==1)
                    <tr>
                        <td>上临时牌照费</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <span class="fixed-value">{{number_format($linpai['price'],2)}}</span>
                                &nbsp;
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>指定服务</td>
                    </tr>
                    @else
                    <tr>
                        <td>上临时牌照费</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <input type="text" class="dym-value " value="{{number_format($linpai['price'],2)}}" />
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?=$linpai['type']==1?'指定服务':'自选服务';?></td>
                    </tr>
                    @endif
                    <tr>
                        <td>交强险</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <span class="fixed-value">{{number_format($jiaoqiangxian,2)}}</span>
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>座位数：{{$seat_num}} </td>
                    </tr>
                    <tr>
                        <td>车辆首年商业保险</td>
                        <td>
                            <p>
                                <span>+￥</span>
                                <input type="text" class="dym-value" value="{{number_format($shangyebaoxian['price'],2)}}" />
                            </p>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?=$shangyebaoxian['type']==1?'指定投保，参考项目与保费见详情':'自由投保，参考项目与保费见详情';?></td>
                    </tr>
                    <tr>
                        <td>售方其他杂费</td>
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
                        <td>其他费用</td>
                        <td>
                            <p>
                                <span class="">+￥</span>
                                <input type="text" class="dym-value " value="0" />
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
                        <td>其他补贴</td>
                        <td>

                            &nbsp;
                        </td>
                        <td>
                            <p>
                                <span class="">-￥</span>
                                <input type="text" class="dym-subsidy " value="0" />

                            </p>

                        </td>
                        <td>&nbsp;</td>
                        <td>可填写您可能获得的其他补贴金额</td>
                    </tr>
                    <tr>
                        <td>买车担保金</td>
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
                        <td><b>各小项总计</b></td>
                        <td>
                            <p>
                                <span>+￥</span>
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
                <a href="javascript:window.location.reload()" class="reset">重置</a>
            </div>
        </div>
    </form>

@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/common/calc", "module/common/common", "bt"]);
    </script>
@endsection