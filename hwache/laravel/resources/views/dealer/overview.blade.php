@extends('_layout.base')
@section('css')
<link href="{{asset('themes/bootstrap.css')}}" rel="stylesheet" />
<link href="{{asset('themes/common.css')}}" rel="stylesheet" />
<link href="{{asset('themes/detial.css')}}" rel="stylesheet" />
<!--[if lt IE 9]>
  <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
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
                        <span>{{$order_num}}</span>
                    </li>
                    <li class="psr">
                        <label>订单时间：</label>
                        <span>{{ddate($order['created_at'])}}</span>
                        
                        <span class="sj sj2"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                            <b>更多</b>
                        </span>
                        <p class="tm tm-long">
                            @if(count($cart_log)>0)     
								@foreach($cart_log as $k =>$v )
								 <span>{{$v['msg_time']}}：{{$v['time']}}</span>
								 @endforeach
							 @endif

                        </p>
                       
                    </li>
                    <li>
                        <label>订单类别：</label>
                        <span>
                            
                            <?php if ($order['buytype']==1): ?>
                                公司用车
                                <?php else: ?>
                                个人用车
                            <?php endif ?>
                        </span>
                    </li>
                </ol>
            </li>
            <li class="col2">
                <label>订单状态：</label>
                <span>{{getStatusNotice($order['cart_sub_status'])}}</span>
            </li>
            <div class="clear"></div>
        </ul>

        <div class="detial psr">
            <h1 class="head-title">订单信息</h1>
            
            <!--售方信息-->
            <dl class="detial-item">
                <dt>
                    售方信息
                </dt>

                <dd>
                    经销商代理会员号：{{formatNum($seller['seller_id'],2)}}
                </dd>
                <dd>
                    经销商代理名称：{{$seller['seller_name']}}
                </dd>
                <dd>
                    经销商代理身份类别： {{$seller['identity']?'公司':'个人'}}
                </dd>

                <dd>
                    经销商代理联系人姓名：{{$seller['member_truename']}}
                </dd>
                <dd>
                    经销商代理电话：{{$seller['member_mobile']}}
                </dd>
                <dd>
                    经销商编号：{{formatNum($jxs['d_id'],3)}}
                </dd>

                <dd>
                    经销商名称：{{$jxs['d_name']}}
                </dd>
                <dd>
                    营业地点：{{$jxs['d_yy_place']}}
                </dd>
                <dd>
                    交车地点：{{$jxs['d_jc_place']}}
                </dd>
                
                <dd>
                    归属地区：{{$jxs['d_shi']}}
                </dd>
                <dd>
                    销售区域：{{$jxs['d_shi']}}
                </dd>
                <dd>
                    报价编号：（{{$bj['bj_serial']}}） 
                </dd>
                <?php if ($order['cart_status']>=5): ?>
                <dd>
                    服务专员姓名：       
                </dd>
                <dd>
                    服务专员电话：       
                </dd>
                <dd>
                    服务专员备用电话：
                </dd>
                <?php endif ?>
                <dd>
                    客户买车定金：{{$guarantee}} 元
                </dd>
                <dd>
                    经销商代理的服务费：{{$bj_agent_service_price}} 元
                </dd>
                <dd class="psr" style="overflow: visible;">
                    加信宝冻结的浮动保证金：ＸＸＸ元    
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>更多</b>
                    </span>
                    <p class="tm tm-long" style="left: -14px;">
                       <span>客户诚意金确认进入加信宝时间：{{$earnest_time}}</span>
                            <span>反馈订单时间：{{$feedback_time}}</span>
                            <?php if ($order['cart_sub_status']>=300): ?>
                                <span>客户买车担保金全额确认进入加信宝时间：{{$deposit_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cart_sub_status']>=400): ?>
                            <span>开始执行订单时间： {{$response_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cart_sub_status']>=401): ?>
                            <span>交车通知发出时间： {{$pdinotice_time}}</span>
                            <?php endif ?>
                    </p>
                </dd>
                <div class="clear"></div>

            </dl>
            <!--客户信息-->
            <dl class="detial-item">
                <dt>
                    客户信息
                </dt>
                <dd>
                    客户姓名：{{$buyer['member_truename']}}
                </dd>
                <dd>
                    客户称呼：{{ getSex($buyer['member_sex'])}}
                </dd>

                <dd>
                    客户电话：{{ $buyer['member_mobile']}}
                </dd>
                <dd>
                    诚意金：{{$sysprice['chengyijin']}}元
                </dd>
                <dd class="psr" style="overflow: visible;">
                    客户买车担保金：{{$guarantee}}元   
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>更多</b>
                    </span>
                    <p class="tm tm-long">
                        <span>诚意金：{{$sysprice['chengyijin']}}元  {{$earnest_time}}</span>
                        <span>买车担保金余额1：{{$guarantee-$sysprice['chengyijin']}}元  {{$deposit_time}}</span>
                        
                    </p>
                </dd>
                <dd>
                    客户承诺上牌地区：{{$shangpai_area}}
                </dd>
                <dd title="计划上牌(注册登记)车主名称：{{$order['reg_name']}}">
                    计划上牌(注册登记)车主名称：{{$order['reg_name']}}
                </dd>
                <dd title="上牌车主身份类别：计划上牌(注册登记)车主名称：苏州华车网络...">
                    上牌车主身份类别：{{$order['shenfen']}}
                </dd>
                
                <dd>
                    车主车辆用途：<?php if ($order['buytype']): ?>
个人用车
  <?php else: ?>
公司用车
<?php endif ?>
                </dd>
                <?php if ($order['cart_sub_status']>=402): ?>
                <dd>
                    上牌车主名称与提车人姓名是否一致：
                    <?php if ($order_attr->agreement): ?>
                        是
                    <?php else: ?>
                        否
                    <?php endif ?>
                       
                </dd>
                <dd>
                    提车人姓名：{{$ticheren['username']}}
                </dd>
                <dd>
                    提车人电话：  {{$ticheren['mobile']}}     
                </dd>
                <div class="clear"></div>
                <dd>
                    提车人需要准备的文件资料：       
                </dd>
            <?php endif ?>
                <div class="clear"></div>
                <?php if ($order['cart_sub_status']>=402): ?>
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
            <?php endif ?>
                <div class="clear"></div>
                <?php if ($order['cart_status']>=5): ?>
                <dd>
                    实际上牌地区：
                </dd>
                <dd>
                    实际上牌（注册登记）车主名称：
                </dd>
                <dd>
                    牌照号码：    
                </dd>
            <?php endif ?>
                <div class="clear"></div>

            </dl>

            <!--商品内容-->
            <dl class="detial-item detial-noborder">
                <dt>
                    商品内容
                </dt>

                <dd>
                    品牌：{{$bj['brand'][0]}}
                </dd>
                <dd>
                    车系：{{$bj['brand'][1]}}
                </dd>
                <dd>
                    车型规格：{{$bj['brand'][2]}} 
                </dd>

                <dd>
                    座位数：{{ $bj['seat_num'] }}
                </dd>
                <dd>
                    厂商指导价：{{ $bj['zhidaojia'] }} 元
                </dd>
                <dd>
                    车辆类别：全新中规车整车
                </dd>

                <dd>
                    数量：{{ $bj['bj_num']}}
                </dd>
                <dd>
                    基本配置：<a href="{{ $bj['barnd_info']['official_url'] }}" target="_blank">官方网址</a>
                </dd>
                <dd>
                    生产国别：{{ $bj['guobieTitle'] }}
                </dd>
                
                <dd>
                    排放标准：{{ $bj['paifangTitle'] }}
                </dd>
                <dd>
                    车身颜色：{{ $bj['body_color'] }}       
                </dd>
                <dd>
                    内饰颜色：{{ $bj['interior_color'] }} 
                </dd>
                <dd>
                    经销商裸车开票价格：{{ $bj['bj_lckp_price'] }} 元       
                </dd>
                <dd>
                    付款方式：{{ $bj['payTitle'] }}       
                </dd>
                <?php if ($bj['bj_producetime']): ?>
                    <dd>
                    出厂年月：{{ $bj['bj_producetime']}}
                    </dd>
                <?php endif ?>
                <dd>
                    行驶里程：{{ $bj['bj_licheng'] }} 公里
                </dd>
                <?php if ($bj['bj_jc_period']): ?>
                    <dd>
                    交车周期：{{ $bj['bj_jc_period'] }} 个月
                    </dd>
                <?php endif ?>
                <dd class="psr" style="overflow: visible;">
                    交车时限：    
                    <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                        <b>详细</b>
                    </span>
                    <p class="tm tm-long" style="left: -14px;">
                        <span>诚意金确认进入加信宝时间：{{$earnest_time}}</span>
                            <?php if ($order['cart_sub_status']>=202): ?>
                            <span>经销商反馈订单时间：{{$feedback_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cart_sub_status']>=303): ?>
                                <span>买车担保金全额确认进入加信宝时间：{{$deposit_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cart_sub_status']>=303): ?>
                            <span>经销商开始执行订单时间： {{$response_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cart_sub_status']>=401): ?>
                            <span>经销商交车通知发出时间： {{$pdinotice_time}}</span>
                            <?php endif ?>
                    </p>
                </dd>
                <?php if ($order['cart_status']>=4): ?>
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
            <?php endif ?>
                <dd>
                    已装原厂选装精品：
                </dd>

                <div class="clear"></div>

            </dl>
            <?php if ($bj['bj_producetime']): 
                        $fee=0.00;
                    ?>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th><p class="fs14">厂商指导价</p></th>
                    <th><p class="fs14">数量</p></th>
                    <th><p class="fs14">附加价值</p></th>
                </tr>
                <?php 
                    $count=0.00;
                    foreach ($xzj as $key =>$value) {
                        //if(!$value['xzj_yc'] || !$value['is_install']) continue;
                 ?>    
                <tr>
                    <td><p class="fs14 tac normal"><?php echo $value['xzj_title']; ?></p></td>
                    <td><p class="fs14 tac normal"><?php echo $value['xzj_model']; ?></p></td>
                    <td><p class="fs14 tac normal"><?php echo $value['xzj_guide_price']; ?></p></td>
                    <td><p class="fs14 tac normal"><?php echo $value['num']; ?></p></td>
                    <td><p class="fs14 tac normal"><?php echo $value['xzj_guide_price']*$value['num']; ?></p></td>
                </tr>
                <?php  
                    $fee+=$value['fee'];
                    $count+=$value['xzj_guide_price']*$value['num'];
                   } ?>
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b><?php echo $count; ?></p>
            <?php endif ?>
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
                @if(!empty($zengpin) && count($zengpin)>0)
                @foreach($zengpin as $key =>$value)
                <tr>
                    <td><p class="fs14 tac normal">{{ $value['title']}}</p></td>
                    <td><p class="fs14 tac normal">{{ $value['num']}}</p></td>
                    <td><p class="fs14 tac normal">@if($value['is_install'])
                                已安装
                            @else
                                未安装
                            @endif</p></td>
                    <td><p class="fs14 tac normal">&nbsp;</p></td>
                </tr>
                @endforeach
                @endif
            </table>
            <hr class="dashed" />
            
            <!--免费礼品或服务-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    选装精品
                </dt>
            </dl>
            <p class="fs14">原厂选装精品折扣率：{{ $bj['bj_xzj_zhekou']}} % </p>
            <p class="fs14">原厂选装精品的已定价标准和客户已选精品：</p>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th class="nopadding">
                        <p class="fs14">厂商编号</p>
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
                <?php 
                $yc_price=0.00;
                foreach ($userxzj as $key => $value): ?>
                    <?php if(!$value['is_yc']) continue;?>
                
                <tr>
                    <td width=140><p class="fs14 tac normal">{{$value['xzj_name']}}</p></td>
                    <td width=130><p class="fs14 tac normal">{{$value['xzj_model']}}</p></td>
                    <td class="nopadding">
                       <p class="fs14">{{$value['cs_serial']}}</p>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                       {{$value['xzj_max_num']}} 
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        {{$value['has_num']}} 
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
                                        {{$value['guide_price']}} 
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        {{$value['fee']}} 
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal">{{$value['discount_price']}} </p></td>
                    <td><p class="fs14 tac normal">{{$value['select_num']}}</p></td>
                    <td><p class="fs14 tac normal">{{$value['createtime']}}</p></td>
                    <td><p class="fs14 tac normal">{{$value['price']}}</p></td>
                </tr>
                <?php 
                    $yc_price+=$value['price'];
                endforeach 
                ?>
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b>{{$yc_price}}</p>

            <p class="fs14">非原厂选装精品的已定价标准和客户已选精品：</p>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">品牌</p></th>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th class="nopadding">
                        <p class="fs14">厂商编号</p>
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
                <?php 
                $fyc_price=0.00;
                foreach ($userxzj as $key => $value): ?>
                    <?php if($value['is_yc']) continue;?>
                    <tr>
                    <td><p class="fs14 tac normal">{{$value['xzj_brand']}}</p></td>
                    <td width=140><p class="fs14 tac normal">{{$value['xzj_name']}}</p></td>
                    <td width=130><p class="fs14 tac normal">{{$value['xzj_model']}}</p></td>
                    <td class="nopadding">
                        <p class="fs14">{{$value['cs_serial']}}</p>
                    </td>
                    <td class="nopadding">
                        <table class="tbl2" width="100%">
                            <tr>
                                <td valign="middle" class="notopborder noleftborder norightborder nopadding">
                                    <p class="fs14">
                                        {{$value['xzj_max_num']}}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="norightborder noleftborder nobottomtborder nopadding">
                                    <p class="fs14">
                                        {{$value['has_num']}}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><p class="fs14 tac normal">{{$value['discount_price']}}</p></td>
                    <td><p class="fs14 tac normal">{{$value['select_num']}}</p></td>
                    <td><p class="fs14 tac normal">{{$value['createtime']}}</p></td>
                    <td><p class="fs14 tac normal">{{$value['price']}}</p></td>
                </tr>
                <?php 
                $fyc_price+=$value['price'];
                endforeach ?>
                
               
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b>{{$fyc_price}}</p>
            <hr class="dashed" />

            <!--车辆首年商业保险-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    车辆首年商业保险
                </dt>
            </dl>
            <p class="fs14"><b>客户是否在经销商处办理保险：<?php if ($bj['bj_baoxian'] && $bj['bj_bx_select']){echo "是";}else{echo "否";} ?></b> </p>
            <p class="fs14"><b>经销商处提供保险的保险公司：{{$baoxianname->bx_title}}</b></p>
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
            <p class="fs14"><b>是否由经销商代办上牌：<?php if ($order['shangpai']==1): ?>
                是</p><p class="fs14"><b>代办上牌服务费金额：{{$bj['bj_linpai_price']}} 元</b> </p>  
            <?php else: ?>
                否</p><p class="fs14"><b>客户本人上牌违约赔偿金额：{{$bj['bj_license_plate_break_contract']}}</b> </p>   
            <?php endif ?>             
            <?php if ($bj['area_xianpai']): ?>    
            <p class="fs14"><b>限牌城市（ {{$bj['area_xianpai']}} ）客户取得牌照指标的安排：{{$order['zhibiao']}}</b> </p> 
                <?php endif ?> 

            <?php if ($order['cart_sub_status']>=402): ?>      
            <p class="fs14"><b>上牌需要客户配合提供的文件资料：</b> </p>       
                <?php endif ?>        

            <hr class="dashed" />

            <!--上临时牌照-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    上临时牌照（车辆临时移动牌照）     
                </dt>
            </dl>
            <?php if ($order['linpai']==1): ?>
                <p class="fs14"><b>是否由经销商代办车辆临时牌照：是  </b> </p>       
                <p class="fs14"><b>代办车辆临时牌照的（每次）服务费金额：{{$bj['bj_linpai_price']}} 元</b> </p> 
                <?php if ($order['cart_sub_status']>=402): ?>
                    <p class="fs14"><b>上临时牌照需要客户配合提供的文件资料： </b> </p>
                <?php endif ?>
                

            <?php else: ?>
                <p class="fs14"><b>是否由经销商代办车辆临时牌照：否  </b> </p>
            <?php endif ?>       

            <hr class="dashed" />

            <!--补贴-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    补贴
                </dt>
            </dl>
            <?php if ($jn_butie): ?>
                <div class="psr">
                <p class="fl"><i class="yuan"></i></p>
                <div class="fl">
                    <p class="fs14 "><b>国家节能补贴  </b></p>
                    <p class="fs14">补贴金额：{{ $bj['bj_butie'] }}</p>
                    <p class="fs14">办理流程和时限：
                        <?php 
                                        if (isset($bj['more']['butie'])) {
                                            if (is_array($bj['more']['butie'])) {
                                                echo implode($bj['more']['butie']);
                                            }else{
                                                echo $bj['more']['butie'];
                                            }
                                        }

                                     ?>
                    </p>
                    <?php if ($order['cart_sub_status']>=402): ?> 
                    <p class="fs14">国家节能补贴需要客户配合提供的文件资料：
                        
                    </p>
                    <?php endif ?>
                    <p class="fs14">发放补贴约定时间：{{$order['fafang_butie']}}</p>
                    <p class="fs14">客户收到补贴时间：{{$order['shoudao_butie']}}</p>

                </div>
                <div class="clear"></div>
            </div>
            <?php endif ?>
            <?php if ($zh_butie): ?>                   
            <div class="psr">
                <p class="fl"><i class="yuan"></i></p>
                <div class="fl">
                    <p class="fs14 "><b>地方政府置换补贴经销商提供协助 </b></p>
                </div>
                <div class="clear"></div>
            </div>
            <?php endif ?>
            <?php if ($cj_butie): ?>
            <div class="psr">
                <p class="fl"><i class="yuan"></i></p>
                <div class="fl">
                    <p class="fs14 "><b>厂家或经销商置换补贴： </b>有</p>
                </div>
                <div class="clear"></div>
            </div>
            <?php endif ?>

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
                <?php 
                    $total=0.00;
                foreach ($bj['other_price'] as $key => $value){ 
                    if ($value<=0) continue;
                        $total+=$value;
                    ?>
                    <tr>
                    <td><p class="fs14 tac">{{ $key }}</p></td>
                    <td><p class="fs14 tac">{{ $value }} 元</p></td>
                    </tr>
                <?php } ?>
            </table>
            <p class="tar pr250 fs14"><b>合计金额：</b>{{$total}}</p>
            <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
            <p class="fs14">信用卡:{{$bj['more']['ka']['xyk']}}</p>
            <p class="fs14">借记卡:{{$bj['more']['ka']['jjk']}}</p>
            <p class="fs14"><b>经销商交车当场向客户移交的文件资料：
                {{$wenjian}}
            </b></p>
            <p class="fs14"><b>经销商交车当场向客户移交的随车工具：
                {{$gongju}}
            </b></p>

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
            

            <!--结算信息-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    结算信息         
                </dt>  

                <dd>
                    交车审核完成时间：
                </dd>
                <div class="clear"></div>
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


        </div>

    </div> 
@endsection
@section('js')
<script src="{{asset('js/sea.js')}}"></script>
<script src="{{asset('js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["module/item/detial", "module/common/common", "bt"]);
    </script>
@endsection