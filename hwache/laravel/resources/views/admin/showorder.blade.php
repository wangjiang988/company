@extends('_layout.base')
@section('css')
<link href="{{asset('themes/detial.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
           
        </div>

    </div>
</nav>
@endsection
@section('content')
	<div class="container pos-rlt content m-t-86"  ms-controller="detial">
        <ul class="detial-common">
            <li class="col1">
                <ol>
                    <li>
                        <label>订单号：</label>
                        <span>HD2252162452</span>
                    </li>
                    <li class="psr">
                        <label>订单时间：</label>
                        <span>2015年10月28日</span>
                        
                        <span class="sj sj2"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                            <b>更多</b>
                        </span>
                        <p class="tm tm-long">
                            <span>诚意金确认进入加信宝时间：2015-10-28  10：45：04</span>
                            <span>经销商反馈订单时间：2015-10-28  10：45：04</span>
                            <span>买车担保金全额确认进入加信宝时间：2015-10-28  10：45：04</span>
                            <span>经销商开始执行订单时间： 2015-10-28  10：45：04</span>
                            <span>经销商交车通知发出时间： 2015-10-28  10：45：04</span>
                        </p>
                       
                    </li>
                    <li>
                        <label>订单类别：</label>
                        <span></span>
                    </li>
                </ol>
            </li>
            <li class="col2">
                <label>订单状态：</label>
                <span>收到客户诚意金，等待反馈</span>
            </li>
            <div class="clear"></div>
        </ul>

        <div class="detial psr">
            <h1 class="head-title">订单信息</h1>
            <!--客户信息-->
            <dl class="detial-item">
                <dt>
                    客户信息
                </dt>

                <dd>
                    客户会员号：HC12598621263
                </dd>
                <dd>
                    客户姓名：张三
                </dd>
                <dd>
                    客户称呼：先生/女士/小姐
                </dd>

                <dd>
                    客户电话：13477990215
                </dd>
                <dd>
                    诚意金：499.00元
                </dd>
                <dd>
                    客户买车担保金：10000.00元
                </dd>

                <dd>
                    客户承诺上牌地区：
                </dd>
                <dd title="计划上牌(注册登记)车主名称：苏州华车网络科技有限公司">
                    计划上牌(注册登记)车主名称：苏州华车网络...
                </dd>
                <dd title="上牌车主身份类别：计划上牌(注册登记)车主名称：苏州华车网络...">
                    上牌车主身份类别：计划上牌(注册登记)车主名称：苏州华车网络...
                </dd>
                
                <dd>
                    车主车辆用途：
                </dd>
                <dd>
                    上牌车主名称与提车人姓名是否一致：       
                </dd>
                <div class="clear"></div>
                <dd>
                    提车人姓名：
                </dd>
                <dd>
                    提车人电话：       
                </dd>
                <div class="clear"></div>
                <dd>
                    提车人需要准备的文件资料：       
                </dd>
                <div class="clear"></div>
                <dd>
                    客户前来提车方式：
                </dd>
                <dd>
                    车辆回程方式：
                </dd>
                <dd>
                    车辆回程费用：    
                </dd>
                <div class="clear"></div>
                <dd>
                    送车大致地址：    
                </dd>
                <div class="clear"></div>
                <dd>
                    实际上牌地区：
                </dd>
                <dd>
                    实际上牌（注册登记）车主名称：
                </dd>
                <dd>
                    牌照号码：    
                </dd>
                <div class="clear"></div>

            </dl>
            <!--售方信息-->
            <dl class="detial-item">
                <dt>
                    售方信息
                </dt>

                <dd>
                    经销商代理会员号：
                </dd>
                <dd>
                    经销商代理名称：
                </dd>
                <dd>
                    经销商代理身份类别： 
                </dd>

                <dd>
                    经销商代理联系人姓名：
                </dd>
                <dd>
                    经销商代理电话：
                </dd>
                <dd>
                    经销商编号：
                </dd>

                <dd>
                    经销商名称：
                </dd>
                <dd>
                    营业地点：
                </dd>
                <dd>
                    交车地点：
                </dd>
                
                <dd>
                    归属地区：
                </dd>
                <dd>
                    销售区域：       
                </dd>
                <dd>
                    报价编号：（查看） 
                </dd>
                <dd>
                    服务专员姓名：       
                </dd>
                <dd>
                    服务专员电话：       
                </dd>
                <dd>
                    服务专员备用电话：
                </dd>
                <dd>
                    客户买车定金：
                </dd>
                <dd>
                    经销商代理的服务费：    
                </dd>
                <dd class="psr" style="overflow: visible;">
                    加信宝冻结的浮动保证金：ＸＸＸ元    
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>更多</b>
                    </span>
                    <p class="tm tm-long" style="left: -14px;">
                        <span>诚意金确认进入加信宝时间：2015-10-28  10：45：04</span>
                        <span>经销商反馈订单时间：2015-10-28  10：45：04</span>
                        <span>买车担保金全额确认进入加信宝时间：2015-10-28  10：45：04</span>
                        <span>经销商开始执行订单时间： 2015-10-28  10：45：04</span>
                        <span>经销商交车通知发出时间： 2015-10-28  10：45：04</span>
                    </p>
                </dd>
                <div class="clear"></div>

            </dl>

            <!--商品内容-->
            <dl class="detial-item detial-noborder">
                <dt>
                    商品内容
                </dt>

                <dd>
                    品牌：
                </dd>
                <dd>
                    车系：
                </dd>
                <dd>
                    车型规格： 
                </dd>

                <dd>
                    座位数：
                </dd>
                <dd>
                    厂商指导价：
                </dd>
                <dd>
                    车辆类别：
                </dd>

                <dd>
                    数量：
                </dd>
                <dd>
                    基本配置：
                </dd>
                <dd>
                    生产国别：
                </dd>
                
                <dd>
                    排放标准：
                </dd>
                <dd>
                    车身颜色：       
                </dd>
                <dd>
                    内饰颜色： 
                </dd>
                <dd>
                    经销商裸车开票价格：       
                </dd>
                <dd>
                    付款方式：       
                </dd>
                <dd>
                    出厂年月：
                </dd>
                <dd>
                    行驶里程：
                </dd>
                <dd>
                    交车周期：    
                </dd>
                <dd class="psr" style="overflow: visible;">
                    交车时限：    
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: -14px;">
                        <span>诚意金确认进入加信宝时间：2015-10-28  10：45：04</span>
                        <span>经销商反馈订单时间：2015-10-28  10：45：04</span>
                        <span>买车担保金全额确认进入加信宝时间：2015-10-28  10：45：04</span>
                        <span>经销商开始执行订单时间： 2015-10-28  10：45：04</span>
                        <span>经销商交车通知发出时间： 2015-10-28  10：45：04</span>
                    </p>
                </dd>
                <dd>
                    约定交车时间：       
                </dd>
                <dd>
                    交车完成时间：
                </dd>
                <dd>
                    车辆识别代号（VIN码）：
                </dd>
                <dd>
                    发动机号：       
                </dd>
                <dd>
                    实车出厂年月：
                </dd>
                <dd>
                    实车行驶里程：
                </dd>
                <dd>
                    已装原厂选装精品：
                </dd>

                <div class="clear"></div>

            </dl>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th><p class="fs14">华车内部编号</p></th>
                    <th><p class="fs14">厂商编号</p></th>
                    <th><p class="fs14">厂商指导价</p></th>
                    <th><p class="fs14">数量</p></th>
                    <th><p class="fs14">附加价值</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac normal">&nbsp;</p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b>xxx</p>
            <hr class="dashed" />

            <!--免费礼品或服务-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    免费礼品或服务
                </dt>
            </dl>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">数量</p></th>
                    <th><p class="fs14">状态</p></th>
                    <th><p class="fs14">说明</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac normal">&nbsp;</p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
            </table>
            <hr class="dashed" />
            
            <!--免费礼品或服务-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    选装精品
                </dt>
            </dl>
            <p class="fs14">原厂选装精品折扣率： </p>
            <p class="fs14">原厂选装精品的已定价标准和客户已选精品：</p>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        华车内部编号
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        厂商编号
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </th>
                    <th class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        单车可装件数
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        可供件数
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </th>
                    <th class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        厂商指导价
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        安装费
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </th>
                    <th><p class="fs14">含安装费折<br>后总单价</p></th>
                    <th><p class="fs14">客户已选<br>件数</p></th>
                    <th><p class="fs14">客户选定<br>时间</p></th>
                    <th><p class="fs14">金额</p></th>
                </tr>
                <tr>
                    <td width=140><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=130><p class="fs14 tac normal"></p></td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
                <tr>
                    <td width=140><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=130><p class="fs14 tac normal"></p></td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
                <tr>
                    <td width=140><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=130><p class="fs14 tac normal"></p></td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b>xxx</p>

            <p class="fs14">非原厂选装精品的已定价标准和客户已选精品：</p>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">品牌</p></th>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        华车内部编号
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        厂商编号
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </th>
                    <th class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        单车可装件数
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        可供件数
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </th>
                    <th><p class="fs14">含安装费折<br>后总单价</p></th>
                    <th><p class="fs14">客户已选<br>件数</p></th>
                    <th><p class="fs14">客户选定<br>时间</p></th>
                    <th><p class="fs14">金额</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=140><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=130><p class="fs14 tac normal"></p></td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=140><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=130><p class="fs14 tac normal"></p></td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=140><p class="fs14 tac normal">&nbsp;</p></td>
                    <td width=130><p class="fs14 tac normal"></p></td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                    <td><p class="fs14 tac normal"></p></td>
                </tr>
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b>xxx</p>
            <hr class="dashed" />

            <!--车辆首年商业保险-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    车辆首年商业保险
                </dt>
            </dl>
            <p class="fs14"><b>客户是否在经销商处办理保险：</b> </p>
            <p class="fs14"><b>经销商处提供保险的保险公司：</b></p>
            <p class="fs14">商业保险的首年保费基准和客户选定投保内容：</p>

            <table class="tbl baoxian">
                <tr>
                    <td width="50" valign="top" style="padding:0">
                        <table width="100%" height="100%">
                            <tbody><tr>
                                <td style="border:0;height: 40px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;"><b>类别</b></td>
                            </tr>
                            <tr>
                                <td style="border:0;">
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14 tac"><b>主</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14"><b>&nbsp;</b></p>
                                    <p class="fs14 tac"><b>险</b></p>
                                </td>
                            </tr>
                        </tbody></table>
                        
                    </td>
                    <td class="nopadding">
                        <table width="100%" class="nobgtbl">
                            <tr>
                                <th width="160" class="fs14 rightborder">险种</th>
                                <th class="fs14 rightborder" width="370">赔付选项</th>
                                <th class="fs14 rightborder" width="113">保费原价</th>
                                <th class="fs14 rightborder" width="130">折后价</th>
                                <th class="norightborder fs14" width="133">客户已选投保保费</th>
                            </tr>
                            <tr>
                                <td class="cell" width=""> <label class="fn">机动车损失险</label></td>
                                <td class="">按保险公司规定执行</td>
                                <td class=""></td>
                                <td class=""></td>
                                <td class="norightborder">1325.08 元</td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn">机动车盗抢险</label></td>
                                <td class="">按保险公司规定执行</td>
                                <td class=""></td>
                                <td class=""></td>
                                <td class="norightborder">2098.99 元</td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn">第三者责任保险</label></td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    赔付额度5万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度5万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度5万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度5万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度5万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度5万元
                                                </p>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td> 
                            </tr>
                            <tr>
                                <td class="cell nobottomborder"> <label class="fn">车上人员责任险</label></td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    驾驶人每次事故责任限额1万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    驾驶人每次事故责任限额1万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    驾驶人每次事故责任限额1万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    驾驶人每次事故责任限额1万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    驾驶人每次事故责任限额1万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    驾驶人每次事故责任限额1万元
                                                </p>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="cell nopadding " width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="30" class="" align="center">
                        <p class="fs14"><b>附</b></p>
                        <p class="fs14"><b>加</b></p>
                        <p class="fs14"><b>险</b></p>
                    </td>
                    <td class="nopadding ">
                        <table width="100%">
                            <tr>
                                <td class="cell notopborder" width="160"> <label class="fn">玻璃单独破碎险</label></td>
                                <td class="cell notopborder nopadding" width="">

                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    进口玻璃
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    国产玻璃
                                                </p>
                                            </td>
                                        </tr>

                                    </table>
                                  
                                </td>
                                <td class="notopborder  nopadding" width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                                <td class=" notopborder nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="norightborder notopborder nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell "> <label class="fn">车身划痕损失险</label></td>
                                <td class="cell nopadding" width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                    赔付额度0.2万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度0.2万元
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                    赔付额度0.1万元
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class=" nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="norightborder nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn psr">不计免赔特约险</label></td>
                                <td class="cell nopadding" width="">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                机动车损失险不计免赔
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                机动车盗抢险不计免赔
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                第三者责任险不计免赔，按赔付额度保费x费率
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                车上人员责任险不计免赔, 按限额和人数保费x费率
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                车上人员责任险不计免赔, 按限额和人数保费x费率
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                费率    %计保费
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                费率    %计保费
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                费率    %计保费
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="nopadding">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                保费原价 x 98%
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                保费原价 x 98%
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                保费原价 x 98%
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="norightborder nopadding" width="133">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td  valign="middle" class="notopborder noleftborder norightborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">

                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder noleftborder nobottomtborder nopadding">
                                                <p class="fs14">
                                                 
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        
                        </table>
                    </td>
                </tr>
              
            </table>

            <p class="tar pr150 fs14"><b>合计保费金额：</b>xxx</p>
            <p class="fs14"><b>投保需要客户配合提供的文件资料：</b> </p>
            <hr class="dashed" />

            <!--上牌-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    上牌（车辆注册登记）
                </dt>
            </dl>
            <p class="fs14"><b>是否由经销商代办上牌：</b> </p>       
            <p class="fs14"><b>代办上牌服务费金额：</b> </p>       
            <p class="fs14"><b>客户本人上牌违约赔偿金额（客户）：</b> </p>       
            <p class="fs14"><b>客户本人上牌违约赔偿金额（经销商代理）：</b> </p>       
            <p class="fs14"><b>限牌城市（  ）客户取得牌照指标的安排：</b> </p>       
            <p class="fs14"><b>上牌需要客户配合提供的文件资料：</b> </p>       

            <hr class="dashed" />

            <!--上临时牌照-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    上临时牌照（车辆临时移动牌照）     
                </dt>
            </dl>
            <p class="fs14"><b>是否由经销商代办车辆临时牌照：  </b> </p>       
            <p class="fs14"><b>代办车辆临时牌照的（每次）服务费金额：          </b> </p>       
            <p class="fs14"><b>上临时牌照需要客户配合提供的文件资料：          </b> </p>       

            <hr class="dashed" />

            <!--补贴-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    补贴
                </dt>
            </dl>
            <div class="psr">
                <p class="fl"><i class="yuan"></i></p>
                <div class="fl">
                    <p class="fs14 "><b>国家节能补贴  </b></p>
                    <p class="fs14">补贴金额：</p>
                    <p class="fs14">办理流程和时限：</p>
                    <p class="fs14">国家节能补贴需要客户配合提供的文件资料：</p>
                    <p class="fs14">发放补贴约定时间：</p>
                    <p class="fs14">客户收到补贴时间：</p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="psr">
                <p class="fl"><i class="yuan"></i></p>
                <div class="fl">
                    <p class="fs14 "><b>地方政府置换补贴经销商提供协助       </b></p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="psr">
                <p class="fl"><i class="yuan"></i></p>
                <div class="fl">
                    <p class="fs14 "><b>厂家或经销商置换补贴： </b>有</p>
                </div>
                <div class="clear"></div>
            </div>

            <hr class="dashed" />
            <!--其他收费-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    其他收费（经销商向客户约定收取的杂费）         
                </dt>
            </dl>
            <table class="tbl" style="width:60%;">
                <tr>
                    <th><p class="fs14">费用名称</p></th>
                    <th><p class="fs14">金额</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac">XX证明</p></td>
                    <td><p class="fs14 tac">1000元</p></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac">&nbsp;</p></td>
                    <td><p class="fs14 tac"></p></td>
                </tr>
            </table>
            <p class="tar pr250 fs14"><b>合计金额：</b>xxx</p>
            <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
            <p class="fs14">信用卡免费  次，超出次数另收刷卡金的  %或者每次  元；</p>
            <p class="fs14">信用卡免费  次，超出次数另收刷卡金的  %或者每次  元；</p>
            <p class="fs14"><b>经销商交车当场向客户移交的文件资料：</b></p>
            <p class="fs14"><b>经销商交车当场向客户移交的随车工具：</b></p>

            <hr class="dashed" />

            <!--争议处理-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    争议处理         
                </dt>
            </dl>

            <table class="tbl" style="width:80%">
                <tr>
                    <td width="160"><p class="fs14"><b>争议内容</b></p></td>
                    <td></td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>客户提交内容</b></p></td>
                    <td></td>
                </tr>
                 <tr>
                    <td><p class="fs14"><b>客户证据材料</b></p></td>
                    <td><p class="fs14"><img src="themes/images/item/img.gif" alt=""><span>证据图片.jpg</span></p></td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>售方提交内容</b></p></td>
                    <td></td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>售方证据材料</b></p></td>
                    <td></td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>华车平台判定依据</b></p></td>
                    <td></td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>华车平台判定结论</b></p></td>
                    <td>客户赔偿    时间：2015年11月13日    10：23：06</td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>执行（客户）</b></p></td>
                    <td>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">诚意金赔偿：1000.00元     时间：2015年11月13日    10：23：06 </p> 
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" > 
                                <p class="fs14 ">客户买车担保金赔偿：500.00元     时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">获得歉意金补偿：500.00元     时间：2015年11月13日    10：23：06</p> 
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">获得客户买车担保金利息补偿（2015-10-25~2015-11-03）：101.00元  时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>执行（售方）</b></p></td>
                    <td>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">歉意金赔偿：1000.00元     时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">客户买车担保金利息赔偿（2015-10-25~2015-11-03）：151.00元  时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">获得诚意金补偿：500.00元     时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">获得客户买车定金补偿：4000.00元     时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="psr">
                            <p class="fl"><i class="yuan"></i></p>
                            <div class="fl wp90" >
                                <p class="fs14 ">获得客户本人上牌违约赔偿金补偿：2000.00元     时间：2015年11月13日    10：23：06</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><p class="fs14"><b>订单操作</b></p></td>
                    <td><p class="fs14">继续执行</p></td>
                </tr>
            </table>

            <hr class="dashed" />
            <!--交车反馈-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    交车反馈         
                </dt>
            </dl>
            <p class="fs14">客户反馈不相符内容：</p>

            <table class="tbl" style="width:80%;">
                <tr>
                    <th><p class="fs14">项目</p></th>
                    <th><p class="fs14">约定</p></th>
                    <th><p class="fs14">反馈内容</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac">生产国别</p></td>
                    <td><p class="fs14 tac"></p></td>
                    <td><p class="fs14 tac"></p></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac">基本配置</p></td>
                    <td><p class="fs14 tac"><a href="#" class="juhuang tdu">见附件一</a></p></td>
                    <td><p class="fs14 tac"></p></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac">经销商名称</p></td>
                    <td><p class="fs14 tac"></p></td>
                    <td><p class="fs14 tac"></p></td>
                </tr>
            </table>
            <hr class="dashed" />

            <!--结算信息-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    结算信息         
                </dt>  

                <dd>
                    交车审核完成时间：
                </dd>
                <dd>
                    华车服务费：
                </dd>
                <dd>
                    客户请求开票时间： 
                </dd>

                <dd>
                    发票抬头：
                </dd>
                <dd>
                    发票编号：
                </dd>
                <dd>
                    发票金额：
                </dd>

                <dd>
                    发票寄送地址：
                </dd>
                <dd>
                    发票寄送时间：
                </dd>
                <dd>
                    快递名称：
                </dd>
                
                <dd>
                    运单号：
                </dd>
                <dd>
                    发票管理员：       
                </dd>
                <dd class="psr" style="overflow: visible;">
                    退还客户金额：1400.00元   
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: 114px;">
                        <span>客户买车担保金10000.00元 — 华车服务费3000.00元 —</span>
                        <span>客户本人上牌违约赔偿金（客户）5000.00元 + 歉意金补</span>
                        <span>偿499.00元+客户买车担保金利息补偿101.00元=1400元</span>
                    </p>
                </dd>
                
                <dd class="psr" style="overflow: visible;">
                    退款路线：客户本人银行账户尾号1234  
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: 114px;">
                        <span>开户行：</span>
                        <span>账号：</span>
                        <span>户名：</span>
                    </p>
                </dd>
                <dd>
                    退款完成时间：<a class="juhuang" href="themes/images/item/map.gif" target="_blank">（查看）</a>       
                </dd>
                <dd>
                    退款财务：
                </dd>
                <dd class="psr" style="overflow: visible;">
                    经销商代理解冻保证金：709.00元   
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: 114px;">
                        <span>第一次解冻：499.00元    时间：</span>
                        <span>第二次解冻：499.00元    时间：</span>
                        <span>剩余解冻：202.00元    时间：</span>
                    </p>
                </dd>
                <div class="clear"></div>
                <dd class="psr" style="overflow: visible;">
                    经销商代理本次应得收入：3008.00元    
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: 170px;">
                        <span>经销商代理的服务费：1005.00元：</span>
                        <span>诚意金补偿：499.00元</span>
                        <span>客户买车定金补偿：800.00元</span>
                        <span>客户本人上牌违约赔偿金补偿：600.00元</span>
                    </p>
                </dd>
                <dd class="psr" style="overflow: visible;">
                    已结算金额：2000.00元    
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: 114px;">
                        <span>1000.00元    时间：</span>
                        <span>1000.00元    时间：</span>
                        <span>未完成财务手续金额：1008.00元</span>
                    </p>
                </dd>
                <dd>
                    结算财务：
                </dd>
                
                <div class="clear"></div>

            </dl>

            <p class="fs14">客户点评：</p>
            <table class="tbl" style="width:80%;">
                <tr>
                    <th width="140"><p class="fs14">点评项目</p></th>
                    <th width="200"><p class="fs14">评价</p></th>
                    <th width="420"><p class="fs14">评论</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac">华车网</p></td>
                    <td>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </td>
                    <td class="nobottomtborder"></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac">经销商</p></td>
                    <td>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </td>
                    <td class="notopborder"></td>
                </tr>
              
            </table>
            <hr class="dashed" />

            <!--平台备注-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    平台备注         
                </dt>
            </dl>
            <table class="tbl" style="width:80%;">
                <tr>
                    <th width=""><p class="fs14">序号</p></th>
                    <th width=""><p class="fs14">备注人</p></th>
                    <th width=""><p class="fs14">时间</p></th>
                    <th width=""><p class="fs14">备注内容</p></th>
                </tr>
                <tr>
                    <td><p class="fs14 tac">&nbsp;</p></td>
                    <td>
                        
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td><p class="fs14 tac">&nbsp;</p></td>
                    <td>
                        
                    </td>
                    <td></td>
                    <td></td>
                </tr>
              
            </table>
            <hr class="dashed" />

            <!--平台财务-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    平台财务         
                </dt>
            </dl>
            <table class="tbl" style="width:80%;">
                <tr>
                    <th width="50%"><p class="fs14">收入</p></th>
                    <th width="50%"><p class="fs14">支出</p></th>
                </tr>
                <tr>
                    <td class="nopadding"  width="50%">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td width="50%" valign="middle" class="notopborder noleftborder  nobottomtborder tac nopadding">
                                    <p class="fs14">
                                        <b>名称</b>
                                    </p>
                                </td>
                          
                                <td width="50%" class="norightborder noleftborder notopborder nobottomtborder norightborder tac nopadding">
                                    <p class="fs14">
                                        <b>金额</b>
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td> 
                    <td class="nopadding" width="50%">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td width="50%" valign="middle" class="notopborder noleftborder  nobottomtborder tac nopadding">
                                    <p class="fs14">
                                        <b>名称</b>
                                    </p>
                                </td>
                          
                                <td width="50%" class="norightborder noleftborder notopborder nobottomtborder norightborder tac nopadding">
                                    <p class="fs14">
                                        <b>金额</b>
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td> 
                </tr>
                <tr>
                    <td class="nopadding" >
                        <table class="tbl2" width="100%">
                            <tr>
                                <td width="50%"  valign="middle" class="notopborder noleftborder  nobottomtborder tac nopadding">
                                    <p class="fs14">
                                        &nbsp;
                                    </p>
                                </td>
                          
                                <td width="50%" class="norightborder noleftborder notopborder nobottomtborder norightborder tac nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td> 
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td width="50%" valign="middle" class="notopborder noleftborder  nobottomtborder tac nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                          
                                <td width="50%" class="norightborder noleftborder notopborder nobottomtborder norightborder tac nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td> 
                </tr>
                <tr>
                    <td class="nopadding" >
                        <table class="tbl2" width="100%">
                            <tr>
                                <td width="50%"  valign="middle" class="notopborder noleftborder  nobottomtborder tac nopadding">
                                    <p class="fs14">
                                        &nbsp;
                                    </p>
                                </td>
                          
                                <td width="50%" class="norightborder noleftborder notopborder nobottomtborder norightborder tac nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td> 
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td width="50%" valign="middle" class="notopborder noleftborder  nobottomtborder tac nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                          
                                <td width="50%" class="norightborder noleftborder notopborder nobottomtborder norightborder tac nopadding">
                                    <p class="fs14">
                                        
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </td> 
                </tr>
                <tr>
                    <td width="50%"><p class="fs14 pl20">合计：</p></td>
                    <td width="50%"><p class="fs14 pl20">合计：</p></td>
                </tr>
              
            </table>

        </div>

    </div> 
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/detial", "module/common/common", "bt"]);
    </script>
@endsection