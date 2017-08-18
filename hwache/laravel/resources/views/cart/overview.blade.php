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
                        <span>{{$order_num}}</span>
                    </li>
                    <li class="psr">
                        <label>订单时间：</label>
                        <span>{{ddate($order['cartBase']['created_at'])}}</span>
                        
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
                            <?php if ($order['cartBase']['buytype']==1): ?>
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
                <span>{{getStatusNotice($cart_sub_status)}}</span>
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
                    客户会员号：{{formatNum($buyer['member_id'],1)}}
                </dd>
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
                    诚意金：{{$order['cartPrice']['cp_earnest']}} 元
                </dd>
                <dd>
                    客户买车担保金：{{$guarantee}}元
                </dd>

                <dd>
                    客户承诺上牌地区：{{$shangpai_area}}
                </dd>
                <dd title="计划上牌(注册登记)车主名称">
                    计划上牌(注册登记)车主名称：{{$order['cartBase']['reg_name']}}
                </dd>
                <dd title="上牌车主身份类别">
                    上牌车主身份类别：{{$order['cartBase']['shenfen']}}
                </dd>
                
                <dd>
                    车主车辆用途：<?php if ($order['cartBase']['buytype']): ?>
个人用车
  <?php else: ?>
公司用车
<?php endif ?>
                </dd>
                <?php if ($order['cartBase']['cart_status']>=5): ?>
                <dd>
                    上牌车主名称与提车人姓名是否一致：    <?php if($order['cartBase']['reg_name'] ==$ticheren['username'] ){echo "一致";}else{echo "不一致";}?>   
                </dd>
                <div class="clear"></div>
                <dd>
                    提车人姓名：{{$ticheren['username']}}
                </dd>
                <dd>
                    提车人电话： {{$ticheren['mobile']}}      
                </dd>
                <div class="clear"></div>
                <dd>
                    提车人需要准备的文件资料：       
                    <?php
                    foreach($files as $k=>$v){
                    	if($v['num']==0){ continue;}
                    	echo $v['title']."(".$v['num'].") ;";
                    }
                    ?>
                </dd>
            <?php endif ?>
                <div class="clear"></div>
                <?php if ($order['cartBase']['cart_sub_status']>=402): ?>
                <dd>
                    客户前来提车方式：{{$take_way}}
                </dd>
             <?php 
             $trip_way = !empty($trip_way)?unserialize($trip_way):array();
             ?>
                <dd>
                    车辆回程方式：<?php if(isset($trip_way['fangshi'])){echo $trip_way['fangshi'];}?>
                    
                </dd>
                <dd>
                    车辆回程费用：  
                 <?php 
                 if($trip_way['fangshi'] == '了解送车服务报价' ){
                 	echo '';
                 }else{
                 	if(isset($trip_way['price'])){
                 		echo $trip_way['price'];
                 	}
                 }?>
                	元  
                 
                </dd>
                <div class="clear"></div>
       			<?php if($trip_way['fangshi'] !='自己开回'){?>
                <dd>
                    	送车大致地址：    {{$deliver_addr}}
              
                </dd>
               <?php } ?>
            <?php endif ?>
                <div class="clear"></div>
                <?php if ($order['cartBase']['cart_status']>=5): ?>
                <?php $carInfo = unserialize($order['cartAttr']['user_carinfo']);?>
                <dd>
                    实际上牌地区：{{$carInfo['shangpai_area']}}
                </dd>
                <dd>
                    实际上牌（注册登记）车主名称：{{$order['cartBase']['reg_name']}}
                </dd>
                <dd>
                    牌照号码：    <?php if(is_array($carInfo['chepai'])){echo implode('',$carInfo['chepai']);}?>
                </dd>
            <?php endif ?>
                <div class="clear"></div>

            </dl>
            <!--售方信息-->
            <dl class="detial-item">
                <dt>
                    售方信息
                </dt>

                <dd>
                    经销商名称：{{$jxs['d_name']}}
                </dd>
                <dd>
                    营业地点：{{$jxs['d_yy_place']}}
                </dd>
                <dd>
                    交车地点：{{$jxs['d_jc_place']}}
                </dd>
                <?php if ($order['cartBase']['cart_status']>=5): ?>
                <?php $waiter = unserialize($order['cartAttr']['waiter']);?>
                <dd>
                    服务专员姓名：       {{$waiter['name']}}
                </dd>
                <dd>
                    服务专员电话：       {{$waiter['mobile']}}
                </dd>
                <dd>
                    服务专员备用电话：{{$waiter['tel']}}
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
                    厂商指导价：{{ $bj['zhidaojia'] }}
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
                    经销商裸车开票价格：{{ $bj['bj_lckp_price'] }} 
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
                            <?php if ($order['cartBase']['cart_sub_status']>=202): ?>
                            <span>经销商反馈订单时间：{{$feedback_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cartBase']['cart_sub_status']>=303): ?>
                                <span>买车担保金全额确认进入加信宝时间：{{$deposit_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cartBase']['cart_sub_status']>=303): ?>
                            <span>经销商开始执行订单时间： {{$response_time}}</span>
                            <?php endif ?>
                            <?php if ($order['cartBase']['cart_sub_status']>=401): ?>
                            <span>经销商交车通知发出时间： {{$pdinotice_time}}</span>
                            <?php endif ?>
                    </p>
                </dd>
                <?php if ($order['cartBase']['cart_status']>=4): ?>
                 <?php $carInfo = unserialize($order['cartAttr']['user_carinfo']);?>
                <dd>
                    约定交车时间：       {{$order['cartAttr']['pdi_date_client']}}
                </dd>
                <dd>
                    交车完成时间： 
                </dd>
                <dd>
                    车辆识别代号（VIN码）：{{$carInfo['vin']}}
                </dd>
                <dd>
                    发动机号：       {{$carInfo['engine_no']}}
                </dd>
                <?php endif ?>
                <div class="clear"></div>
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
                    if(is_array($xzj) && count($xzj)>0){
                    foreach ($xzj as $key =>$value) {
                        if(!$value['xzj_yc'] || !$value['is_install']) continue;
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
                   		} 
                    }
                    ?>
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
                @if(is_array($zengpin) && count($zengpin)>=1)
                 @foreach($zengpin as $key =>$value)
                <tr>
                    <td><p class="fs14 tac normal">{{ $value['title']}}</p></td>
                    <td><p class="fs14 tac normal">{{ $value['num']}}</p></td>
                    <td><p class="fs14 tac normal">
                    @if($value['is_install'])
                               	 已安装
                            @else
                               	 未安装
                            @endif</p></td>
                    <td><p class="fs14 tac normal">{{ $value['beizhu']}}</p></td>
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
            <?php if ($order['cartBase']['cart_sub_status']>=303): ?>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th><p class="fs14">厂商指导价</p></th>
                    <th><p class="fs14">安装费</p></th>
                    <th><p class="fs14">含安装费折<br>后总单价</p></th>
                    <th><p class="fs14">客户已选<br>件数</p></th>
                    <th><p class="fs14">客户选定<br>时间</p></th>
                    <th><p class="fs14">金额</p></th>
                </tr>
                               
                    <?php 
                        $yc_total=0.00;
                       	if(is_array($userxzj) && count($userxzj)>=1){
                        	foreach ($userxzj as $key => $value){ 

                    ?>
                        <?php if (!$value['is_yc']) continue; ?>
                        <tr>
                            <td width=140><p class="fs14 tac normal">{{ $value['xzj_name']}}</p></td>
                            <td width=130><p class="fs14 tac normal">{{$value['xzj_model']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['guide_price']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['fee']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['discount_price']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['num']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['createtime']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['price']}}</p></td>
                        </tr>
                    <?php 
	                          $yc_total+= $value['price']; 
	                        } 
                       	}
                    ?>
                
            </table>
           
            <p class="tar pr150 fs14"><b>合计金额：</b>{{$yc_total}}</p>
            <p class="fs14">非原厂选装精品的已定价标准和客户已选精品：</p>
            <table class="tbl">
                <tr>
                    <th><p class="fs14">品牌</p></th>
                    <th><p class="fs14">名称</p></th>
                    <th><p class="fs14">型号</p></th>
                    <th><p class="fs14">含安装费折<br>后总单价</p></th>
                    <th><p class="fs14">客户已选<br>件数</p></th>
                    <th><p class="fs14">客户选定<br>时间</p></th>
                    <th><p class="fs14">金额</p></th>
                </tr>
                    <?php 
                        $fyc_total=0.00;
                        if(is_array($userxzj) && count($userxzj)>=1){
                   		 	foreach ($userxzj as $key => $value): ?>
                        <?php if ($value['is_yc']==1) continue; ?>
                            
                        <tr>
                            <td><p class="fs14 tac normal">{{$value['xzj_brand']}}</p></td>
                            <td width=140><p class="fs14 tac normal">{{ $value['xzj_name']}}</p></td>
                            <td width=130><p class="fs14 tac normal">{{$value['xzj_model']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['discount_price']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['num']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['createtime']}}</p></td>
                            <td><p class="fs14 tac normal">{{$value['price']}}</p></td>
                        </tr>
                    <?php 
                        	$fyc_total+=$value['price'];
                    	endforeach
                    ?>
                <?php }?>
                
            </table>
            <p class="tar pr150 fs14"><b>合计金额：</b>{{$fyc_total}}</p>
        <?php endif ?>
            <hr class="dashed" />

            <!--车辆首年商业保险-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    车辆首年商业保险
                </dt>
            </dl>
            <p class="fs14"><b>客户是否在经销商处办理保险：</b><?php if ($bj['bj_baoxian'] && $bj['bj_bx_select']){echo "是";}else{echo "否";} ?> </p>
            <p class="fs14"><b>经销商处提供保险的保险公司：{{$baoxianname->bx_title}}</b></p>
            <?php if ($order['cartBase']['cart_sub_status']>=402): ?>
            <p class="fs14">客户已选投保内容：</p>

            <table class="tbl baoxian nobgtbl" >
                   
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
                        <table width="100%">
                            <tbody><tr>
                                <th width="160" class="">险种</th>
                                <th class="" width="400">赔付选项</th>
                                <th class="" width="140">报价基准</th>
                                <th class="norightborder">我的投保</th>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn">机动车损失险</label></td>
                                <td class="">按保险公司规定执行</td>
                                <td class="">  元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn">机动车盗抢险</label></td>
                                <td class="">按保险公司规定执行</td>
                                <td class="">  元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn">第三者责任保险</label></td>
                                <td class="cell" width="320">
                                    <p>赔付额度：100万</p>
                                    
                                </td>
                                <td class=""> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell nobottomborder"> <label class="fn">车上人员责任险</label></td>
                                <td class="cell nopadding nobottomborder" width="320">
                                    <div>
                                        <p class="first-p">驾驶人每次事故责任限额：5万</p>
                                        
                                        <h5 class="fenge">乘客每次事故每人责任限额：5万</h5>
                                        
                                        <h5 class="fenge" style="border-top:0">乘客座位数：7座</h5>
                                        
                                    </div>
                                </td>
                                <td class="nobottomborder nopadding" width="130" align="center">
                                    <table class="tbl2 " width="100%" style="height: 181px;">
                                        <tbody><tr style="height: 62px;">
                                            <td class="norightborder notopborder"> 元</td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder nobottomborder"> 元</td>
                                        </tr>
                                    </tbody></table> 
                                </td>
                                <td class="norightborder nopadding nobottomborder">
                                    <table class="tbl2 " width="100%" style="height: 181px;">
                                        <tbody><tr style="height: 62px;">
                                            <td class="norightborder notopborder"> 元</td>
                                        </tr>
                                        <tr>
                                            <td class="norightborder nobottomborder"> 元</td>
                                        </tr>
                                    </tbody></table> 
                                </td>
                            </tr>
                            
                        </tbody></table>
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
                            <tbody><tr>
                                <td class="cell notopborder" width="160"> <label class="fn">玻璃单独破碎险</label></td>
                                <td class="cell notopborder" width="400">

                                    <span>国产玻璃</span>
                                </td>
                                <td class=" notopborder" width="140"> 元</td>
                                <td class="norightborder notopborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn">车身划痕损失险</label></td>
                                <td class="cell" width="320">
                                    <p>赔付额度：0.2万</p>
                                    
                                </td>
                                <td class=""> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell"> <label class="fn psr">不计免赔特约险</label></td>
                                <td class="cell" width="320">
                                    <span>机动车损失险，按保险公司规定执行。</span>
                                </td>
                                <td class=""> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                             <tr>
                                <td class="cell"> <label class="fn">不计免赔特约险</label></td>
                                <td class="cell" width="320">
                                    <span>机动车盗抢险，按保险公司规定执行。</span>
                                </td>
                                <td class=""> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                             <tr>
                                <td class="cell"> <label class="fn">不计免赔特约险</label></td>
                                <td class="cell" width="320">
                                    <span>机动车盗抢险，按保险公司规定执行。</span>
                                </td>
                                <td class=""> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                             <tr>
                                <td class="cell"> <label class="fn">不计免赔特约险</label></td>
                                <td class="cell" width="320">
                                    <span>机动车盗抢险，按保险公司规定执行。</span>
                                </td>
                                <td class=""> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell nobottomborder"> <label class="fn">不计免赔特约险</label></td>
                                <td class="cell nobottomborder" width="320">
                                    <span>车身划痕损失险，按保险公司规定执行。</span>
                                </td>
                                <td class=" nobottomborder"> 元</td>
                                <td class="norightborder nobottomborder"> 元</td>
                            </tr>
                            
                        </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="30" class="" align="center">
                        <p class="fs14"><b>其</b></p>
                        <p class="fs14"><b>他</b></p>
                    </td>
                    <td class="nopadding ">
                        <table width="100%">
                            <tr>
                                <td class="cell notopborder" width="160"> <label class="fn">涉水险</label></td>
                                <td class="cell notopborder" width="400">
                                </td>
                                <td class="notopborder" width="140"> 元</td>
                                <td class="norightborder notopborder"> 元</td>
                            </tr>
                            <tr>
                                <td class="cell " width="160"> <label class="fn">BBBB险</label></td>
                                <td class="cell" width="400">
                                </td>
                                <td class="" width="140"> 元</td>
                                <td class="norightborder"> 元</td>
                            </tr>
                        </table>
                    </td>
                </tr>
             
            </table>


            <p class="tar pr150 fs14"><b>合计保费金额：</b>xxx</p>
            <p class="fs14"><b>投保需要客户配合提供的文件资料：</b> </p>
            <?php endif ?>
            <hr class="dashed" />

            <!--上牌-->
            <dl class="detial-item detial-noborder detial-nopadding">
                <dt>
                    上牌（车辆注册登记）
                </dt>
            </dl>

            <p class="fs14"><b>是否由经销商代办上牌：</b><?php if ($order['shangpai']==1): ?>
                是</p><p class="fs14"><b>代办上牌服务费金额：{{$bj['bj_linpai_price']}}</b> </p>  
            <?php else: ?>
                否</p><p class="fs14"><b>客户本人上牌违约赔偿金额：</b> </p>   
            <?php endif ?>        
            
            <?php if ($bj['area_xianpai']): ?>    
            <p class="fs14"><b>限牌城市（ {{$bj['area_xianpai']}} ）客户取得牌照指标的安排：{{$order['cartBase']['zhibiao']}}</b> </p> 
                <?php endif ?> 

            <?php if ($order['cartBase']['cart_sub_status']>=402): ?>      
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
                <p class="fs14"><b>代办车辆临时牌照的（每次）服务费金额： </b> </p> 
                <?php if ($order['cartBase']['cart_sub_status']>=402): ?>
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
                    <?php if ($order['cartBase']['cart_sub_status']>=402): ?> 
                    <p class="fs14">国家节能补贴需要客户配合提供的文件资料：
                        {{$butie_file}}
                    </p>
                    <?php endif ?>
                    <p class="fs14">发放补贴约定时间：</p>
                    <p class="fs14">客户收到补贴时间：</p>

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
            <p class="fs14">借记卡: {{$bj['more']['ka']['jjk']}}</p>
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
           

           

        </div>

    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/detial", "module/common/common", "bt"]);
    </script>
@endsection