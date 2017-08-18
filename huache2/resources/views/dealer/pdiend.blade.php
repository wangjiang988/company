@extends('_layout.base')
@section('css')
<link href="{{asset('themes/custom.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#guize">平台规则</a></li>
                <li><a href="#my">我的账户</a></li>
            </ul>
            <ul class="nav navbar-nav control">
                <li class="loginout">
                <?php if (isset($_SESSION['member_name'])): ?>
                    <label>欢迎您：<span>{{ $_SESSION['member_name'] }}</span> </label>
                    <em>|</em>
                    <a href="http://user.123.com/index.php?act=seller_logout&op=logout"><span>[</span>退出<span>]</span></a>
                <?php endif ?>
                    
                    
                </li>
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
 <div class="container content m-t-86 pos-rlt " ms-controller="custom">
       <div class="cus-step">
           <div class="line stp-3"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul> 
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-3">
                    <small>发出通知</small>
                    <i></i>
                    <small>确认反馈</small>
                    <i></i>
                    <small class="juhuang">预约完毕</small>
                </div>
            </div>
       </div>
   

        <div class="wapper has-min-step">
                      
            <div class="box">
               
                <div class="box-inner  box-inner-def">
                   
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16">订单信息</label></th>
                               
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td  width="50%"><p><b>订单编号：</b>{{$order_num}}</p></td>
                                            <td  width="50%">
                                                <div class="psr">
                                                  <b>订单时间：</b>{{ddate($order['created_at'])}}
                                                  <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                                     <b>更多</b>
                                                  </span>
                                                  <p class="tm tm-long">
                                                    @if(count($cart_log)>0)     
															@foreach($cart_log as $k =>$v )
															<label>{{$v['msg_time']}}：{{$v['time']}}</label>
															@endforeach
													@endif
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>订单类别：</b><?php if ($bj['bj_producetime']): ?>
                                              现车订单
                                            <?php else: ?>
                                              远期订单
                                            <?php endif ?></p><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>品牌：</b>{{$bj['brand'][0]}}</p></td>
                                            <td><p><b>车系：</b>{{$bj['brand'][1]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车型规格：</b>{{$bj['brand'][2]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>座位数：</b>{{ $bj['seat_num'] }}</p></td>
                                            <td><p><b>厂商指导价：</b>{{ $bj['zhidaojia'] }} 元</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>类别：</b>全新中规车整车</p></td>
                                            <td><p><b>数量：</b>{{ $bj['bj_num']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>经销商名称：</b>{{$jxs['d_name']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>营业地点：</b>{{$jxs['d_yy_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>交车地点：</b>{{$jxs['d_jc_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>归属地区：</b>{{$jxs['d_shi']}}</p></td>
                                            <td>
                                                <div class="psr">
                                                  <b>销售区域：</b>
                                                  <span class="">{{$shangpai_area}}</span>
                                                  
                                                  
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>经销商裸车开票价格：</b>{{ $bj['bj_lckp_price'] }} 元</p></td>
                                            <td><p><b>付款方式：</b>{{ $bj['payTitle'] }}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>客户买车定金：</b>{{$guarantee}} 元</p></td>
                                            <td><p><b>我的服务费：</b>{{$bj_agent_service_price}} 元</p></td>
                                        </tr>
                                         
                                        <tr>
                                            <td colspan="2">
                                                <div class="psr">
                                                  <b>加信宝已冻结浮动保证金：</b>xxxxx
                                                  <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                                     <b>详细</b>
                                                  </span>
                                                  <p class="tm detail" style="width:470px">
                                                    <label>冻结的第一笔歉意金：499,00元（2015-10-26   10：57 ：57）</label>
                                                    <label>冻结的客户买车担保金利息损失补偿金：723.09元（2015-10-25 ~ 2015-11-03）</label>
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>                                  
                                </td>
                                <td>
                                    <div class="times times2" style="height:450px">
                                        <p class="status tac status2"><b>订单状态：{{getStatusNotice($order['cart_sub_status'])}} </b></p>
                                        
                                        <p class="tac">约定交车时间：{{$jc_date['date']}}</p>
                                        <p class="tac">离交车仅剩 <span class="juhuang ">{{$sy_time}}</span> 天</p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" target="_blank" class="juhuang tdu">查看订单总详情</a></p>
                                           
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($order['buy_id'],1)}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{ changeMobile($buyer['member_mobile'])}} </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b>{{$shangpai_area}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b><?php if ($order['buytype']): ?>
个人用车
  <?php else: ?>
公司用车
<?php endif ?> </p>
                                        <p class="pl20 pt">
                                          <b>上牌客户身份类别： </b>
                                          <span class="fr" style="width: 165px;color:#8e8d8d;text-align: left;">{{$order['shenfen']}}</span> 
                                          <span class="clear"></span>
                                        </p>
                                        <p class="clear"></p>
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：   </b>{{$guarantee}} 元 </p>
                                               
                                           
                                       
                                    </div>
                                </td>
                               
                            </tr> 
                          
                        </tbody>
                    </table>

                    <?php if ($bj['bj_producetime']): ?> 
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16">商品内容</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td width="33%"><p><b>基本配置：</b><a href="{{ $bj['barnd_info']['official_url'] }}" target="_blank">官网参数</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{ $bj['guobieTitle'] }}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{ $bj['paifangTitle'] }}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{ $bj['body_color'] }}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{ $bj['interior_color'] }}</p></td>
                                            <td width="33%"><p><b>出厂年月：</b>
                                          {{ $bj['bj_producetime']?$bj['bj_producetime']:''}}
                                            </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{ $bj['bj_licheng'] }} 公里</p></td>
                                            <td width="33%"><p><b>交车时限：  </b>{{ddate($order['jiaoche_time'])}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{ddate($order['jiaoche_notice_time'])}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆识别代码（VIN码）：</b>{{$order_attr['vin']}} </p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>发动机号：  </b>{{$order_attr['engine_no']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>实车出厂年月：</b>{{$order_attr['production_date']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>实车行驶里程：  </b>{{$order_attr['mileage']}} 公里</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <p>已装原厂选装精品：</p>
                                                <?php if ($bj['bj_producetime']): 
                                                    $fee=0.00;
                                                ?>
                                                <table class="tbl tbl3">
                                                    <tr>
                                                       <th><p class="fs15">名称</p></th>
                                                       <th><p class="fs15">型号</p></th>
                                                       <th><p class="fs15">厂家指导价</p></th>
                                                       <th><p class="fs15">数量</p></th>
                                                       <th><p class="fs15">附加价值</p></th>
                                                   </tr>
                                                   <?php 
                                                      $count=0.00;
                                                      foreach ($xzj as $key =>$value) {
                                                          if(!$value['xzj_yc'] || !$value['xzj_front']) continue;
                                                   ?> 
                                                   <tr>
                                                       <td><?php echo $value['xzj_title']; ?></td>
                                                       <td><?php echo $value['xzj_model'].'/'.$value['beizhu'] ; ?></td>
                                                       <td><?php echo $value['xzj_guide_price']; ?></td>
                                                       <td><?php echo $value['num']; ?></td>
                                                       <td><?php echo $value['xzj_guide_price']*$value['num']; ?></td>
                                                   </tr>
                                                   <?php  
                                                      $fee+=$value['fee'];
                                                      $count+=$value['xzj_guide_price']*$value['num'];
                                                     } ?>  
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"><?php echo $count; ?> 元</span></h2>
                                                
                                                <?php endif ?>  
                                            </td>
                                        </tr>
                                    </table>                                      
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                     <?php endif ?>   
                    
                    <?php if ($bj['bj_jc_period']): ?>
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16">商品内容</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td width="33%"><p><b>基本配置：</b><a href="{{ $bj['barnd_info']['official_url'] }}" target="_blank">官网参数</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{ $bj['guobieTitle'] }}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{ $bj['paifangTitle'] }}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{ $bj['body_color'] }}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{ $bj['interior_color'] }}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{ $bj['bj_jc_period'] }}个月 </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>交车时限：  </b>{{ddate($order['jiaoche_time'])}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{ddate($order['jiaoche_notice_time'])}}</p></td>
                                            <td width="33%"><p><b></b></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆识别代码（VIN码）：</b>{{$order_attr['vin']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>发动机号：  </b>{{$order_attr['engine_no']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>实车出厂年月：</b>{{$order_attr['production_date']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>实车行驶里程：  </b>{{$order_attr['mileage']}}</p></td>
                                        </tr>
                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                    <?php endif ?>
                    <h2 class="title">选装精品 (原厂) </h2>
                    <table class="tbl bltbl" style="display: table;">
                        <tr>
                           <th><p class="fs15">序号</p></th>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号</p></th>
                           <th><p class="fs15">厂商货号</p></th>
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15">可供件数</p></th>
                           <th><p class="fs15">客户已选件数</p></th>
                           <th><p class="fs15 ">金额</p></th>
                       </tr>
                      <?php 
                        $yc_total=0.00;
                        foreach ($userxzj as $key => $value){ 

                    ?>
                        <?php if (!$value['is_yc']) continue; ?>
                       <tr>
                       		<td></td>
                           <td>{{ $value['xzj_name']}}</td>
                           <td>{{$value['xzj_model']}}</td>
                           <td></td>
                           <td>{{$value['price']}}</td>
                           <td>{{$value['has_num']}}</td>
                           
                           <td>{{$value['select_num']}}</td>
                           <td>{{$value['select_num']*$value['price']}}</td>
                       </tr>
                       <?php 
                          $yc_total+= $value['price']; 
                        } 
                    ?> 
                    </table>

                    <p class="fs14 tar pr80"><b>合计：</b>{{$yc_total}} 元</p>
                
                    <h2 class="title">车辆首年商业保险</h2>
                    <p class="fs14"><b>客户确定在经销商处购买</b></p>
                    <p class="fs14"><b>客户不在经销商处购买</b></p>
                    <p class="fs14"><b>提供商业保险的保险公司：</b></p>
                    <p class="fs14">客户选定投保内容：<span class="sj sj2" ms-on-click="toggleList"></span></p>


                    <table class="tbl baoxian bx-self" style="width: 75%">
                        
                        <tr>
                            <td width="50" valign="top" style="padding:0">
                                <table width="100%" height="100%" style="">
                                    <tbody><tr>
                                        <td style="border:0;height: 40px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;">
                                            <b>
                                                类别
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;">
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14 tac">
                                                <b>
                                                    主
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14 tac">
                                                <b>
                                                    险
                                                </b>
                                            </p>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                            <td class="nopadding">
                                <table width="100%">
                                    <tbody><tr>
                                        <th width="160" class="">
                                            险种
                                        </th>
                                        <th class="" width="400">
                                            赔付选项
                                        </th>
                                       
                                        <th class="norightborder">
                                            保费金额
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <input disabled type="checkbox" class="radio jdcssx" checked="" name="baoxian[chesun]" value="3169.92" id="cs1">
                                            <label class="fn">
                                                机动车损失险
                                            </label>
                                        </td>
                                        <td class="">
                                            按保险公司规定执行
                                        </td>
                                       
                                        <td class="norightborder tongji tac">
                                            3169.92
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <input disabled checked="checked" type="checkbox" name="baoxian[daoqiang]" value="3890.7" id="dq1" class="radio dqx">
                                            <label class="fn">
                                                机动车盗抢险
                                            </label>
                                        </td>
                                        <td class="">
                                            按保险公司规定执行
                                        </td>
                                        
                                        <td class="norightborder tongji tac tac">
                                            3890.7
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <input disabled type="checkbox" class="radio zrx" checked="" name="baoxian[sanzhe]" id="sz1" value="1">
                                            <label class="fn">
                                                第三者责任保险
                                            </label>
                                        </td>
                                        <td class="cell" width="320">
                                            <p style="padding-left: 0">
                                                赔付额度：
                                            </p>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="4.50" class="radio" type="radio" value="5" id="szd5">
                                            <span>
                                                5万
                                            </span>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="5.40" class="radio" type="radio" value="10" id="szd10">
                                            <span>
                                                10万
                                            </span>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="6.30" class="radio" type="radio" value="15" id="szd15">
                                            <span>
                                                15万
                                            </span>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="7.20" class="radio" type="radio" value="20" id="szd20">
                                            <span>
                                                20万
                                            </span>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="8.10" class="radio" type="radio" value="30" id="szd30">
                                            <span>
                                                30万
                                            </span>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="0.90" class="radio" type="radio" value="50" id="szd50" checked="">
                                            <span>
                                                50万
                                            </span>
                                            <input disabled name="baoxian[sanzhe][compensate]" data-bind="1.80" class="radio" type="radio" value="100" id="szd100">
                                            <span>
                                                100万
                                            </span>
                                        </td>
                                        
                                        <td class="norightborder tongji tac">
                                            0.9
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell nobottomborder">
                                            <input disabled type="checkbox" class="radio cszrx" name="baoxian[renyuan]" value="1" checked="" id="ry1">
                                            <label class="fn">
                                                车上人员责任险
                                            </label>
                                        </td>
                                        <td class="cell nopadding nobottomborder" width="320">
                                            <div>
                                                <p class="first-p pl10">
                                                    驾驶人每次事故责任限额：
                                                </p>
                                                <div class="r-box pl10" >
                                                    <input disabled data-bind="270.00" name="baoxian[renyuan][sj][compensate]" class="radio" type="radio" value="270.00" id="rysj1" checked="">
                                                    <span>
                                                        1万
                                                    </span>
                                                    <input disabled data-bind="720.00" name="baoxian[renyuan][sj][compensate]" class="radio" type="radio" value="720.00" id="rysj2">
                                                    <span>
                                                        2万
                                                    </span>
                                                    <input disabled data-bind="1350.00" name="baoxian[renyuan][sj][compensate]" class="radio" type="radio" value="1350.00" id="rysj3">
                                                    <span>
                                                        3万
                                                    </span>
                                                    <input disabled data-bind="2160.00" name="baoxian[renyuan][sj][compensate]" class="radio" type="radio" value="2160.00" id="rysj4">
                                                    <span>
                                                        4万
                                                    </span>
                                                    <input disabled data-bind="3150.00" name="baoxian[renyuan][sj][compensate]" class="radio" type="radio" value="3150.00" id="rysj5">
                                                    <span>
                                                        5万
                                                    </span>
                                                </div>
                                                <h5 class="fenge pl10">
                                                    乘客每次事故每人责任限额：
                                                </h5>
                                                <div class="r-box pl10" >
                                                    <input disabled checked="" data-bind-type="1" data-bind="720.00" name="baoxian[renyuan][ck][compensate]" class="radio" type="radio" value="720.00" id="ryck1">
                                                    <span>
                                                        1万
                                                    </span>
                                                    <input disabled data-bind-type="1" data-bind="1620.00" name="baoxian[renyuan][ck][compensate]" class="radio" type="radio" value="1620.00" id="ryck2">
                                                    <span>
                                                        2万
                                                    </span>
                                                    <input disabled data-bind-type="1" data-bind="270.00" name="baoxian[renyuan][ck][compensate]" class="radio" type="radio" value="270.00" id="ryck3">
                                                    <span>
                                                        3万
                                                    </span>
                                                    <input disabled data-bind-type="1" data-bind="720.00" name="baoxian[renyuan][ck][compensate]" class="radio" type="radio" value="720.00" id="ryck4">
                                                    <span>
                                                        4万
                                                    </span>
                                                    <input disabled data-bind-type="1" data-bind="1350.00" name="baoxian[renyuan][ck][compensate]" class="radio" type="radio" value="1350.00" id="ryck5">
                                                    <span>
                                                        5万
                                                    </span>
                                                </div>
                                                <h5 class="fenge pl10" style="border-top:0">
                                                    乘客座位数：
                                                </h5>
                                                <div class="r-box pl10" >
                                                    <input disabled data-bind-type="2" data-bind="1" name="baoxian[renyuan][seat]" class="radio" type="radio" value="1" id="seat1">
                                                    <span>
                                                        1座
                                                    </span>
                                                    <input disabled data-bind-type="2" data-bind="2" name="baoxian[renyuan][seat]" class="radio" type="radio" value="2" id="seat2">
                                                    <span>
                                                        2座
                                                    </span>
                                                    <input disabled data-bind-type="2" data-bind="3" name="baoxian[renyuan][seat]" class="radio" type="radio" value="3" id="seat3">
                                                    <span>
                                                        3座
                                                    </span>
                                                    <input disabled checked="" data-bind-type="2" data-bind="4" name="baoxian[renyuan][seat]" class="radio" type="radio" value="4" id="seat4">
                                                    <span>
                                                        4座
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="norightborder nopadding nobottomborder">
                                            <table class="tbl2 " width="100%" style="height: 181px;">
                                                <tbody><tr style="height: 62px;">
                                                    <td class="norightborder cts tongji tac">
                                                        270
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="norightborder nobottomborder cts tongji  tac">
                                                        <span>2880</span>
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                        <tr>
                            <td width="30" class="" align="center">
                                <p class="fs14">
                                    <b>
                                        附
                                    </b>
                                </p>
                                <p class="fs14">
                                    <b>
                                        加
                                    </b>
                                </p>
                                <p class="fs14">
                                    <b>
                                        险
                                    </b>
                                </p>
                            </td>
                            <td class="nopadding ">
                                <table width="100%">
                                 
                                    <tr>
                                        <td class="cell " width="160">
                                            <input disabled type="checkbox" class="radio" checked="" name="baoxian[boli]" value="1" id="bl1">
                                            <label class="fn">
                                                玻璃单独破碎险
                                            </label>
                                        </td>
                                        <td class="cell" width="400">
                                            <input disabled value="3888.00" data-bind="3888.00" class="radio" type="radio" name="baoxian[boli][$value['state']]" id="bldjk" checked="">
                                            <span>
                                                进口玻璃
                                            </span>
                                            <input disabled value="4860.00" data-bind="4860.00" class="radio" type="radio" name="baoxian[boli][$value['state']]" id="bldgc">
                                            <span>
                                                国产玻璃
                                            </span>
                                        </td>
                                        
                                        <td class="norightborder tongji tac">
                                            3888
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <input disabled type="checkbox" class="radio chx" checked="" name="baoxian[huahen]" id="hh1" value="1">
                                            <label class="fn">
                                                车身划痕损失险
                                            </label>
                                        </td>
                                        <td class="cell" width="320">
                                            <p>
                                                赔付额度：
                                            </p>
                                            <input disabled data-bind="2.70" name="baoxian[huahen][compensate]" class="radio" type="radio" value="2000" id="hhd2000">
                                            <span>
                                                2000
                                            </span>
                                            <input disabled data-bind="6.30" name="baoxian[huahen][compensate]" class="radio" type="radio" value="5000" id="hhd5000">
                                            <span>
                                                5000
                                            </span>
                                            <input disabled data-bind="9.90" name="baoxian[huahen][compensate]" class="radio" type="radio" value="10000" id="hhd10000">
                                            <span>
                                                10000
                                            </span>
                                            <input disabled data-bind="13.50" name="baoxian[huahen][compensate]" class="radio" type="radio" value="20000" id="hhd20000" checked="">
                                            <span>
                                                20000
                                            </span>
                                        </td>
                                       
                                        <td class="norightborder tongji tac">
                                            13.5
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input disabled for="jdcssx" type="checkbox" class="radio fjx" checked="" name="baoxian[bjm_chesun]" value="95.09" id="bjmcs">
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                机动车损失险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        
                                        <td class="norightborder tongji tac">
                                            95.09
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input disabled for="dqx" type="checkbox" class="radio fjx" name="baoxian[bjm_daoqiang]" value="77.81" checked="" id="bjmdq">
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                机动车盗抢险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        
                                        <td class="norightborder tongji tac">
                                            77.81
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input disabled for="zrx" type="checkbox" class="radio fjx" name="baoxian[bjm_sanzhe]" value="" id="bjmsz" checked="">
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                第三者责任险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        
                                        <td class="norightborder tongji tac">
                                            0
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input disabled for="cszrx" type="checkbox" class="radio fjx" name="baoxian[bjm_renyuan]" value="" checked="" id="bjmry">
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                车上人员责任险，按保险公司规定执行。
                                            </span>
                                        </td>
                                       
                                        <td class="norightborder tongji tac">
                                            <span class="tongji-ry" style="">3150</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell nobottomborder">
                                            <div class="psr">
                                                <input disabled for="chx" type="checkbox" class="radio fjx" name="baoxian[bjm_huahen]" value="" checked="" id="bjmhh">
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell nobottomborder" width="320">
                                            <span>
                                                车身划痕损失险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        
                                        <td class="norightborder nobottomborder tongji tac">
                                            0.68
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                          <td>
                            <p class="fs14">
                                <b>
                                    其
                                </b>
                            </p>
                            <p class="fs14">
                                <b>
                                    它
                                </b>
                            </p>
                          </td>
                          <td class="nopadding">
                              <table width="100%">
                                <tr>
                                  <td width="160">涉水险</td>
                                  <td width="400"></td>
                                  <td class="tac norightborder">120.00  元</td>
                                </tr>
                                <tr>
                                  <td>涉水险</td>
                                  <td></td>
                                  <td class="tac norightborder">120.00  元</td>
                                </tr>
                              </table>

                          </td>
                          
                        </tr>
                        
                    </table>

                    <p class="fs14 tar pr260"><b>合计：</b></p>


                    

                    <h2 class="title">上牌（车辆注册登记）</h2>
                    <?php if ($order['shangpai']): ?>
                  <p class="fs14"><b>客户确定由经销商代办上牌手续。 </b></p>
                    <?php if ($bj['area_xianpai']): ?>
                          <p class="fs14"><b>限牌城市（{{$bj['area_xianpai']}}）客户取得牌照指标的安排： </b>已取得牌照指标  或  订车后自行取得牌照指标</p>
                        <?php endif ?>
                      <?php else: ?>
                        <p class="fs14"><b>客户自己办理上牌手续。</b></p>
                        <p class="fs14"><b>客户本人上牌违约赔偿金额：</b>{{$bj['bj_license_plate_break_contract']}} 元</p>
                    <?php endif ?>
                    
                    
                    
                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <?php if ($order['linpai']): ?>
                      <p class="fs14"><b>客户确定由经销商代办车辆临时牌照。</b></p>
                      <?php else: ?>
                        <p class="fs14"><b>客户自己办理</b></p>
                    <?php endif ?>
                    <?php if ($order_attr['butie']): ?>
                      <h2 class="title">补贴</h2>
                      <p class="ifl fs14"><i class="yuan"></i></p>
                      <div class="ifl">
                          <p class="fs14"><b>国家节能补贴</b></p>
                          <p class="fs14"><b>补贴金额： </b>{{$bj['bj_butie']}} 元</p>
                          <p class="fs14"><b> 办理流程和时限：</b>

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
                      </div>
                    <?php endif ?>
                    
                    <div class="clear"></div>
                    <?php if ($order_attr['zhihuan']): ?>
                      <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>

                    <?php endif ?>
                    <?php if ($order_attr['cj_butie']): ?>
                    <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                    <?php endif ?>
                    <div class="clear"></div>

                    <h2 class="title">交车有关事宜</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户计划上牌（注册登记）的详细名称：</b>{{$order['reg_name']}}</p>
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户计划委托的提车人姓名：  </b>{{$ticheren['username']}}<span class="ml20">电话：{{$ticheren['mobile']}}</span></p>
                        <?php if ($order_attr['agreement']): ?>
                          <p class="fs14">上牌名称与提车人姓名一致。</p>
                        <?php else: ?>
                          <p class="fs14">上牌名称与提车人姓名不一致。</p>
                        <?php endif ?>
                        
                        
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户前来提车方式：</b>{{$order_attr['take_way']}}</p>
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户提车后车辆回程方式：  </b><span class="">{{$trip_way['fangshi']}}</span></p>
                        <p class="fs14"><b>送车大致地址：</b>{{$order_attr['deliver_addr']}} </p>
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>在经销商处单车付款刷卡收费标准：  </b></p>
                        <p class="fs14">信用卡:{{$bj['more']['ka']['xyk']}}</p>
                        <p class="fs14">借记卡:{{$bj['more']['ka']['jjk']}}</p>
                    </div>
                    <div class="clear"></div>

                    <h2 class="title">免费礼品或服务</h2>
                    <div style="width: 70%;margin:0 auto">
                        <table class="tbl">
                            <tbody><tr>
                                <th><label class="fs14 weight">序号</label></th>
                                <th><label class="fs14 weight">名称</label></th>
                                <th><label class="fs14 weight">数量</label></th>
                                <th><label class="fs14 weight">状态</label></th>
                                <th><label class="fs14 weight">说明</label></th>

                            </tr>
                            @if(!empty($zengpin) && count($zengpin)>=1)
                            <?php 
                            $i=1;
                            foreach ($zengpin as $key => $value): ?>
                                         <tr>
                                         <td><p class="tac fs14 weight">{{$i}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['title']}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['num']}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['is_install']}}</p></td>
                                          <td width="300">
                                              <span>{{$value['beizhu']}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
                                          </td>
                                      </tr> 
                                      {{$i++}}
                                       <?php endforeach ?>
                            @endif
                        </tbody></table>
                    </div>


                    <h2 class="title">各项费用金额与支付方式</h2>
                    <div style="width: 70%;margin:0 auto">
                        <table class="tbl">
                            <tr>
                                <th><label class="fs14 weight">名称</label></th>
                                <th><label class="fs14 weight">金额</label></th>
                                <th><label class="fs14 weight">支付方式</label></th>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">裸车开票价格</p></td>
                                <td><p class="tal fs14">{{ $bj['bj_lckp_price'] }}</p></td>
                                <td><p class="tal fs14">先验车后支付！</p></td>
                            </tr>
                            <?php if ($yc_total>0): ?>
                              <tr>
                                <td><p class="tal fs14">选装精品合计</p></td>
                                <td><p class="tal fs14">{{$yc_total}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <?php endif ?>
                            
                            <?php if ($order['final_baoxian']): ?>
                            <tr>
                                <td><p class="tal fs14">首年车辆商业保险保费合计</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">投保时支付</p></td>
                            </tr>
                            <?php endif ?>
                            <?php if ($order['shangpai']): ?>
                              <tr>
                                <td><p class="tal fs14">上牌服务费</p></td>
                                <td><p class="tal fs14">{{$bj['bj_shangpai_price']}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <?php endif ?>
                            
                            <?php if ($order['linpai']): ?>
                              <tr>
                                <td><p class="tal fs14">上临时牌照服务费</p></td>
                                <td><p class="tal fs14">{{$bj['bj_linpai_price']}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <?php endif ?>
                            
                            
                            
                            <tr>
                                <td><p class="tal fs14">其他费用</p></td>
                                <td><p class="tal fs14">{{$total_op}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <?php if (strpos($trip_way['fangshi'],'代驾')!==false): ?>
                              <tr>
                                    <td><p class="tal fs14">代驾送车服务费</p></td>
                                    <td><p class="tal fs14">{{$trip_way['price']}}</p></td>
                                    <td><p class="tal fs14">{{$trip_way['pay']}}</p></td>
                                </tr>
                            <?php endif ?>
                            <?php if (strpos($trip_way['fangshi'],'板车')!==false): ?>
                            <tr>
                                <td><p class="tal fs14">板车运输送车运费</p></td>
                                <td><p class="tal fs14">{{$trip_way['price']}}</p></td>
                                <td><p class="tal fs14">{{$trip_way['pay']}}</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">板车运输送车运输险保费</p></td>
                                <td><p class="tal fs14">{{$trip_way['baoxian']}}</p></td>
                                <td><p class="tal fs14">{{$trip_way['pay']}}</p></td>
                            </tr>
                          <?php endif ?>
                          <?php if ($fl_price>0): ?>
                          	<tr>
                                <td><p class="tal fs14">特别约定之证明办理费用</p></td>
                                <td><p class="tal fs14">{{$fl_price}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                          <?php endif ?>
                            <?php if ($order['overtime']): ?>
                            	<tr>
	                                <td><p class="tal fs14">超期提车补偿金额</p></td>
	                                <td><p class="tal fs14">{{$jc_date['fee']}}</p></td>
	                                <td><p class="tal fs14">在经销商处当场支付</p></td>
	                            </tr>
                            <?php endif ?>
                            <?php if ($order_attr['butie']): ?>
                            	<tr>
	                                <td><p class="tal fs14">国家节能补贴</p></td>
	                                <td><p class="tal fs14">-{{$bj['bj_butie']}} </p></td>
	                                <td><p class="tal fs14">补贴</p></td>
	                            </tr>
                            <?php endif ?>
                            

                        </table>
                    </div>
                    

                    <h2 class="title">双方互交资料物品</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl" style="width:700px;">
                        <p class="fs14">向客户移交：</p>
                        <div style="margin:0 auto;width: 100%">
                            <table class="tbl">
                              <tr>
                                  <td width="160"><p class="tac fs14 weight">类别</p></td>
                                  <td width="198"><p class="tac fs14 weight">名称</p></td>
                                  <td width="150"><p class="tac fs14 weight">数量</p></td>
                                  <td width="150"><p class="tac fs14 weight">备注</p></td>
                              </tr> 
                              <tr>
                                  <td width="160"><p class="tac fs14 ">文件资料</p></td>
                                  <td colspan="3" class="nopadding ">
                                     <table class="tbl2" width="100%">
                                     @if(!empty($wenjian))
                                         <?php foreach ($wenjian as $key => $value): ?>
                                         	<tr>
                                              <td width="197" class="bottomtborder ">
                                                  <p class="tac fs14">{{$value->title}}</p>
                                              </td>
                                              <td width="150" class="bottomtborder ">
                                                  <p class="tac fs14">{{$value->num}}</p>
                                              </td>
                                              <td width="150" class="bottomtborder norightborder">
                                                  <p class="tac fs14">{{$value->notice}}</p>
                                              </td>
                                          </tr>
                                         <?php endforeach ?>
                                      @endif
                                      </table>
                                  </td>
                                  
                              </tr> 
                              <tr>
                                  <td width="160"><p class="tac fs14 ">随车工具</p></td>
                                  <td colspan="3" class="nopadding ">
                                     <table class="tbl2" width="100%">
                                     @if(!empty($gongju))
                                     	<?php foreach ($gongju as $key => $value): ?>
                                     		<tr>
                                              <td width="197" class="bottomtborder ">
                                                  <p class="tac fs14">{{$value->title}}</p>
                                              </td>
                                              <td width="150" class="bottomtborder ">
                                                  <p class="tac fs14">{{$value->num}}</p>
                                              </td>
                                              <td width="150" class="bottomtborder norightborder">
                                                  <p class="tac fs14">{{$value->notice}}</p>
                                              </td>
                                              
                                          </tr>
                                     	<?php endforeach ?> 
                                     	@endif
                                      </table>
                                  </td>
                                  
                              </tr> 
                          </table>
                        </div>
                    </div>
                    <div class="clear"></div>
                    

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl" style="width:700px;">
                        <p class="fs14">客户提供：</p>
                        <div style="margin:0 auto;width: 100%">
                            <table class="tbl" ms-if="!view">
                              <tr>
                                  <td class="cell" colspan="3">
                                      <b>
                                          查看方式：
                                      </b>
                                      <input ms-attr-checked="!view" name="baoxian" ms-on-click="viewMethod(1)"  class="radio" type="radio"><span>按使用场合查看</span>
                                      <input name="baoxian" ms-on-click="viewMethod(1)"  class="radio" type="radio"><span>按名称查看</span>
                                  </td>
                              </tr>
                              <tr>
                                  <td width="160"><p class="tac fs14 weight">使用场合</p></td>
                                  <td width="198"><p class="tac fs14 weight">文件资料</p></td>
                                  <td width="150"><p class="tac fs14 weight">数量</p></td>
                              </tr> 
                              <?php foreach ($files1 as $key => $value): ?>
                                <tr>
                                  <td width="160"><p class="tac fs14 ">{{$key}}</p></td>
                                  <td colspan="2" class="nopadding ">
                                     <table class="tbl2" width="100%">
                                      <?php foreach ($value as $k => $v): 
                                        
                                      ?>
                                        <tr>
                                              <td width="197" class="bottomtborder ">
                                                  <p class="tac fs14">{{$v['title']}}</p>
                                              </td>
                                              <td width="150" class="bottomtborder norightborder">
                                                  <p class="tac fs14">{{$v['num']}}</p>
                                              </td>
                                              
                                          </tr>
                                      <?php endforeach ?>
                                          
                                          
                                      </table>
                                  </td>
                              </tr>
                              <?php endforeach ?>
                               

                            </table>

                            <table class="tbl" ms-if="view">
                              <tr>
                                  <td class="cell" colspan="3">
                                      <b>
                                          查看方式：
                                      </b>
                                      <input name="baoxian2" ms-on-click="viewMethod(2)"  class="radio" type="radio"><span>按使用场合查看</span>
                                      <input name="baoxian2" ms-attr-checked="view" ms-on-click="viewMethod(2)" checked="" class="radio" type="radio"><span>按名称查看</span>
                                  </td>
                              </tr>
                              <tr>
                                  <td width="160"><p class="tac fs14 weight">文件资料</p></td>
                                  <td width="198"><p class="tac fs14 weight">数量</p></td>
                                  <td width="150"><p class="tac fs14 weight">使用场合</p></td>
                              </tr> 
                                <?php foreach ($files2 as $key => $value): ?>
                                  <tr>
                                    <td width="160"><p class="tac fs14 ">{{$value['title']}}</p></td>
                                    <td class="">
                                       <p class="tac fs14">{{$value['num']}}</p>
                                    </td>
                                    <td class="">
                                       <p class="tac fs14">{{$value['cate']}}</p>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                               

                           
                            </table>
                        </div>
                    </div>
                    <div class="clear"></div>
                      <h2 class="title">服务专员安排</h2>
                      <form action="{{ url('dealer/savezhuanyuan') }}" method="post" name="item-form">
                           <div style="width: 55%;margin:0 auto">
                         <table class="tbl">
                            <tr>
                              <td width="45%"><p class="fs14"><b>服务专员姓名</b></p></td>
                              <td><p class="fs14" id="fwzyName">{{$orignZhuanyuan['name']}}
                              </p></td>
                            </tr>
                            <tr>
                              <td><p class="fs14"><b>电话</b></p></td>
                              <td><p class="fs14" id="fwzyMobile">{{$orignZhuanyuan['mobile']}}

                            </tr>
                            <tr>
                              <td><p class="fs14"><b>备用电话</b></p></td>
                              <td><p class="fs14" id="fwzyTel">{{$orignZhuanyuan['tel']}}
                              </p></td>
                            </tr>
                         </table>
                          @if(!empty($zhuanyuan) && count($zhuanyuan)>=1)
                          <input type="hidden" name="originZhuanyuan" value="{{$orignZhuanyuan['name']}}">
		                    <select name="fuwuyuan" id="fuwuyuan" class="bltbl" ms-on-change="modifyFwzyChange">
		                    @foreach($zhuanyuan as $k => $v)
		                    <option value="{{$v['name']}}_{{$v['mobile']}}_{{$v['tel']}}" <?=$v['name']==$orignZhuanyuan['name']?"selected":""?>>{{$v['name']}}</option>
		                    @endforeach
		                    </select>
		                    @else
		                    	请后台添加服务专员，以供交车时客户联系
		                    @endif
                         
                         <p class="fs14">
                            <span ms-on-click="modifyFwzy">修改</span>
                            （注：选择或修改服务专员）
                         </p>
                         <p class="tac">
                         <input type="submit" value="交车" class="btn btn-s-md btn-danger">
                            
                            <p class="tac">
                              <a href="#" class="tdu fs14 juhuang">下载交车确认书</a>
                              <a href="{{url('dealer/zhengyi')}}/{{$order_num}}" class="tdu fs14 juhuang pl20" target="_blank">遇到问题</a>
                            </p>
                         </p>
                      </div>
                      <input type="hidden" value="{{$order_num}}" name="order_num" >
                      <input type="hidden" value="{{$order['id']}}" name="id" >
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      </form>    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection