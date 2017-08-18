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
                    <small class="juhuang">确认反馈</small>
                    <i></i>
                    <small>预约完毕</small>
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
                                            <td colspan="2"><p><b>车型规格：</b>{{$bj['brand'][1]}}</p></td>
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
                                                  <span class="">{{$bj['area']}}</span>
                                                 
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
                                            <td><p><b>客户买车定金：</b>{{$bj['bj_doposit_price']}} 元</p></td>
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
                                        <p class="status tac status2"><b>订单状态：{{getStatusNotice($order['cart_sub_status'])}}  </b></p>
                                        <p class="tac fs15">再确认回复仅剩<span class="juhuang">X天X小时X分钟X秒</span></p>
                                        <p class="tac ">离交车时限仅剩<span class="juhuang">{{diffBetweenTwoDays($order['jiaoche_time'])}}天</span></p>
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
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：{{$guarantee}}   </b> </p>

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
                                            <td width="33%"><p><b>出厂年月：</b>{{ $bj['bj_producetime']?$bj['bj_producetime']:''}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{ $bj['bj_licheng'] }}</p></td>
                                            <td width="33%"><p><b>交车时限：  </b>{{ddate($order['jiaoche_time'])}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{ddate($order['jiaoche_notice_time'])}}</p></td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                            <?php if ($bj['bj_producetime']): 
                                                    $fee=0.00;
                                                ?>
                                                <p>已装原厂选装精品：</p>
                                                <table class="tbl tbl3">
                                                    <tr>
                                                       <th><p class="fs15">名称</p></th>
                                                       <th><p class="fs15">型号</p></th>
                                                       <th><p class="fs15">厂家指导价</p></th>
                                                       <th><p class="fs15">数量</p></th>
                                                       <th><p class="fs15">附加价值</p></th>
                                                   </tr>
                                                   <?php $count=0.00;?>
                                                   @if(count($xzj)>0)
                                                   <?php 
                                                      
                                                      foreach ($xzj as $key =>$value) {
                                                          
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
                                                  @endif
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"><?php echo $count; ?></span></h2>
                                                
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
                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                    <?php endif ?>
                    <h2 class="title">选装精品  </h2>
                    <p class="fs14"><b>原厂选装精品折扣率：</b>{{ $bj['bj_xzj_zhekou']}} %</p>
                    <p class="fs14"><b>原厂选装精品的已定价标准和客户已选精品：(<span class="bl">{{$ycxzj_count}}</span>)<span class="sj sj2" ms-on-click="toggleList"></span></b></p>
                    <table class="tbl bltbl">
                        <tr>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号</p></th>
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15">单车可装件数</p></th>
                           <th><p class="fs15">可供件数</p></th>
                           <th><p class="fs15 bl">客户已选件数</p></th>
                           <th><p class="fs15 ">客户选定时间</p></th>
                       </tr>
                       <?php 
                        $yc_total=0.00;
                        foreach ($userxzj as $key => $value){ 
                    ?>
                        <?php if (!$value['is_yc']) continue; ?>
                       <tr>
                           <td>{{ $value['xzj_name']}}</td>
                           <td>{{$value['xzj_model']}}</td>
                           <td>{{$value['discount_price']}}</td>
                           <td>{{$value['xzj_max_num']}}</td>
                           <td>{{$value['has_num']}}</td>
                           <td>{{$value['select_num']}}</td>
                           <td>{{$value['createtime']}}</td>
                       </tr>
                       <?php 
                          $yc_total+= $value['price']; 
                        } 
                    ?>  
                    </table>

                    <p class="fs14"><b>非原厂选装精品的已定价标准和客户已选精品：(<span class="bl">{{$fycxzj_count}}</span>)<span class="sj sj2" ms-on-click="toggleList"></span></b></p>
                    <table class="tbl bltbl">
                        <tr>
                           <th><p class="fs15">品牌</p></th>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号</p></th> 
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15">单车可装件数</p></th>
                           <th><p class="fs15">可供件数</p></th>
                           <th><p class="fs15 bl">客户已选件数</p></th>
                           <th><p class="fs15 ">客户选定时间</p></th>
                       </tr>
                       <?php 
                        foreach ($userxzj as $key => $value){ 
                    ?>
                        <?php if ($value['is_yc']==1) continue; ?>
                       <tr>
                           <td>{{$value['xzj_brand']}}</td>
                           <td>{{ $value['xzj_name']}}</td>
                           <td>{{$value['xzj_model']}}</td>
                           <td>{{$value['discount_price']}}</td>
                           <td>{{$value['xzj_max_num']}}</td>
                           <td>{{$value['has_num']}}</td>
                           <td>{{$value['select_num']}}</td>
                           <td>{{$value['createtime']}}</td>
                       </tr>
                         <?php 
                        
                    } 
                    ?> 
                    </table>

                    <h2 class="title">车辆首年商业保险</h2>
                    <?php if ($bj['bj_baoxian'] && $bj['bj_bx_select']): ?>
                          <?php if ($baoxianname['bx_is_quanguo'] || $islocal): ?>
                            <p class="fs14"><b>客户是否经销商处购买商业保险：</b>确定购买</p>
                            <p class="fs14"><b>提供商业保险的保险公司：</b>{{$baoxianname['bx_title']}}</p>
                          <?php endif ?>
                        
                      <?php elseif($bj['bj_bx_select']): ?>
                        <?php if ($baoxianname['bx_is_quanguo'] || $islocal): ?>
                            <p class="fs14"><b>客户是否经销商处购买商业保险：</b>待定</p>
                          <?php else: ?>
                            <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                        <?php endif ?>
                        
                      <?php else: ?>
                        <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                    <?php endif ?>
                    <?php if ($bj['bj_bx_select']): ?>
                      <p class="fs14">商业保险的首年保费基准：{{$bj['bj_baoxian_discount']}}%</p>
                      <?php $baoxian_count=0.00; ?>
                      <table class="tbl">
                       
                        <tbody><tr>
                            <td width="50" valign="top">
                                <table width="175%" height="100%" style="margin-left: -10px">
                                    <tbody><tr>
                                        <td style="border:0;height: 30px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;" valign="middle"><b>类别</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;">
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
                                        <th width="180" class="">险种</th>
                                        <th class="" width="400">赔付选项</th>
                                        <th class="norightborder">折后价</th>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">机动车损失险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class="norightborder tac">{{$baoxian['chesun']}}</td>
                                        <?php $baoxian_count+=$baoxian['chesun']; ?>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">机动车盗抢险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class="norightborder tac">{{$baoxian['daoqiang']}}</td>
                                        <?php $baoxian_count+=$baoxian['daoqiang']; ?>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">第三者责任保险</label></td>
                                        <td class="cell nopadding" width="320" >
                                           <p class="tal fs14">赔付额度{{$baoxian['sanzhe']['compensate']}}元</p>  
                                      </td>
                                        <td class="norightborder nopadding">
                                         <p class="tac fs14">{{$baoxian['sanzhe']['price']}}</p>    </td>
                                         <?php $baoxian_count+=$baoxian['sanzhe']['price']; ?>
                                    </tr>
                                    <tr>
                                        <td class="cell nobottomborder"><label class="fs14">车上人员责任险</label></td>
                                        <td class="cell nopadding nobottomborder" width="320">
                                             <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额{{$baoxian['renyuan']['sj']['compensate']}}元</p>                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">乘客每次事故责任限额{{$baoxian['renyuan']['ck']['compensate']}}元</p>                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">座位数 </p>                                                    </td>
                                                </tr>
                                            </table>                                        </td>
                                        <td class="norightborder nobottomborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">{{$baoxian['renyuan']['sj']['price']}}</p>                         </td>
                                                        <?php $baoxian_count+=$baoxian['renyuan']['sj']['price']; ?>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tac fs14">{{$baoxian['renyuan']['ck']['price']}}</p>                                                    </td>
                                                        <?php $baoxian_count+=$baoxian['renyuan']['ck']['price']; ?>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tac fs14">{{$baoxian['renyuan']['seat']}}</p>                                                    </td>
                                                </tr>
                                            </table>                                        </td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                        <tr>
                            <td width="30" class="">
                                <p class="fs14"><b>附</b></p>
                                <p class="fs14"><b>加</b></p>
                                <p class="fs14"><b>险</b></p>
                            </td>
                            <td class="nopadding ">
                                <table width="100%">
                                    <tbody><tr>
                                        <td class="cell " width="180"><label class="fs14">玻璃单独破碎险</label></td>
                                        <td class="cell nopadding" width="400">
                                          <p class="fs14">{{$baoxian['boli']['state']}}</p>                                        </td>
                                        <td colspan="2" class="nopadding">
                                            <p class="tac fs14">{{$baoxian['boli']['price']}}</p>                                            </td>
                                            <?php $baoxian_count+=$baoxian['boli']['price']; ?>
                                        </tr>
                                   
                                    <tr>
                                        <td class="cell"><label class="fs14">车身划痕损失险</label></td>
                                        <td class="cell nopadding" width="400">
                                            <p class="tal fs14">赔付额度{{$baoxian['huahen']['compensate']}}元</p>                                        </td>
                                        <td colspan="2" class="nopadding">
                                         <p class="tac fs14">{{$baoxian['huahen']['price']}}</p>                                         </td>
                                         <?php $baoxian_count+=$baoxian['huahen']['price']; ?>
                                      </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">不计免赔特约险</label></td>
                                        <td colspan="2" class="cell nopadding"><table class="tbl2" width="100%">
                                        <?php if (!empty($baoxian['bjm_chesun'])): ?>
                                          <tr>
                                            <td class="bottomtborder " width="399"><p class="tal fs14">机动车损失险不计免赔</p></td>
                                            <td  class="bottomtborder norightborder"><p class="tac fs14">{{$baoxian['bjm_chesun']}}</p></td>
                                            <?php $baoxian_count+=$baoxian['bjm_chesun']; ?>
                                          </tr>
                                        <?php endif ?>
                                          <?php if (!empty($baoxian['bjm_daoqiang'])): ?>
                                            <tr>
                                              <td class="bottomtborder " width="399"><p class="tal fs14">机动车盗抢险不计免赔</p></td>
                                              <td class="bottomtborder norightborder"><p class="tac fs14">{{$baoxian['bjm_daoqiang']}}</p></td>
                                              <?php $baoxian_count+=$baoxian['bjm_daoqiang']; ?>
                                            </tr>
                                          <?php endif ?>
                                          <?php if (!empty($baoxian['bjm_sanzhe'])): ?>
                                            <tr>
                                            <td class="bottomtborder " width="398"><p class="tal fs14">第三者责任险不计免赔，按赔付额度保费x费率</p></td>
                                            <td class="bottomtborder norightborder" align="center"><p class="tac fs14">{{$baoxian['bjm_sanzhe']}}</p></td>
                                            <?php $baoxian_count+=$baoxian['bjm_sanzhe']; ?>
                                          </tr>
                                          <?php endif ?>
                                          <?php if (!empty($baoxian['bjm_renyuan'])): ?>
                                            <tr>
                                              <td class="bottomtborder "><p class="tal fs14" width="399">车上人员责任险不计免赔，按赔付额度保费x费率</p></td>
                                              <td class="bottomtborder norightborder"><p class="tac fs14">{{$baoxian['bjm_renyuan']}}</p>
                                              </td>
                                              <?php $baoxian_count+=$baoxian['bjm_renyuan']; ?>
                                            </tr>
                                          <?php endif ?>
                                          <?php if (!empty($baoxian['bjm_huahen'])): ?>
                                            <tr>
                                              <td class="nobottomborder"><p class="tal fs14" width="399">车身划痕损失险不计免赔，按赔付额度保费x费率</p></td>
                                              <td class="nobottomborder"><p class="tac fs14">{{$baoxian['bjm_huahen']}}</p></td>
                                              <?php $baoxian_count+=$baoxian['bjm_huahen']; ?>
                                            </tr>
                                          <?php endif ?>
                                          
                                        </table></td>
                                      </tr>
                                </tbody></table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="tac">客户已选投保组合总价：<span class="juhuang">{{$baoxian_count}}</span>元</p>
                    <?php endif ?>

                    <h2 class="title">上牌（车辆注册登记）</h2>
                    <?php if ($order['shangpai']): ?>
                      <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                      <p class="fs14"><b>代办上牌服务费金额：</b>{{$bj['bj_shangpai_price']}} 元</p>
                    <?php else: ?>
                      <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>
                      <p class="fs14"><b>客户本人上牌违约赔偿金额：</b>{{$bj['bj_shangpai_price']}} 元</p>
                    <?php endif ?>

                    <?php if ($bj['area_xianpai']): ?>
                        <p><b>限牌城市（{{$bj['area_xianpai']}}）客户取得牌照指标的安排：{{$order['cartBase']['zhibiao']}} </b></p>
                    <?php endif ?>
                    
                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <?php if ($order['linpai']): ?>
                      <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>确认代办</p>
                      <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b>{{$bj['bj_shangpai_price']}} 元</p>
                    <?php else: ?>
                      <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>不代办</p>
                    <?php endif ?>
                    <h2 class="title">其他收费</h2>
                    <div style="width: 70%;margin:0 auto">
                        <table class="tbl">
                            <tbody><tr>
                                <th><label class="fs14 weight">费用名称</label></th>
                                <th><label class="fs14 weight">金额</label></th>
                            </tr>
                            <?php 
                            foreach ($bj['other_price'] as $key => $value){ 
                    if ($value<=0) continue;
                       
                    ?>
                    <tr>
                    <td><p class="fs14 tac">{{ $key }}</p></td>
                    <td><p class="fs14 tac">{{ $value }} 元</p></td>
                    </tr>
                <?php } ?>
                        </tbody></table>
                    </div>

                    

                    <h2 class="title">交车有关事宜</h2>
                    <p class="fs14"><b>交车当场移交客户的文件资料：</b><span>{{$wenjian}}</span></span></p>
                    <p class="fs14"><b>交车当场移交客户的随车工具：</b>
                    {{$gongju}}
                    </p>
                    <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
                    <p class="fs14">信用卡: {{$bj['more']['ka']['xyk']}} </p>
                    <p class="fs14">借记卡: {{$bj['more']['ka']['jjk']}} </p>
                    <h2 class="title">补贴</h2>
                    
                    <?php if ($bj['bj_butie']): ?>
                      <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b><?php echo $bj['bj_butie'] ?> 元</p>
                        <p class="fs14"><b> 办理流程和时限：</b><?php 
                                        if (isset($bj['more']['butie'])) {
                                            if (is_array($bj['more']['butie'])) {
                                                echo implode($bj['more']['butie']);
                                            }else{
                                                echo $bj['more']['butie'];
                                            }
                                        }

                                     ?></p>
                    </div>
                    <?php endif ?>
                    <div class="clear"></div>

                    <?php if ($bj['bj_zf_butie']): ?>
                    <div class="clear"></div>
                    <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                  <?php endif ?>
                    <?php if ($bj['bj_cj_butie']): ?>
                  <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                <?php endif ?>
                    <div class="clear"></div>

                    <table class="tbl">
                      <tr>
                        <th class="tal juhuang">交车说明</th>
                      </tr>
                      <tr>
                        <td>
                            <div style="width: 55%;margin:0 auto">
                               <table class="tbl">
                                  <tbody>
                          
                                      <tr>
                                          <td><p class="tal fs14 weight">车辆识别代号（VIN码）</p></td>
                                          <td width="300">
                                              <span>{{$order_attr['vin']}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1">{{$order_attr['vin']}}</textarea>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">发动机号</p></td>
                                          <td width="300">
                                              <span>{{$order_attr['engine_no']}}</span>
                                              <textarea  style="width:240px;" name="" id="" cols="30" rows="1">{{$order_attr['engine_no']}}</textarea>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">实车出厂年月</p></td>
                                          <td width="300">
                                              <span>{{$order_attr['production_date']}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1">{{$order_attr['production_date']}}</textarea>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">实车行驶里程（公里）</p></td>
                                          <td width="300">
                                              <span>{{$order_attr['mileage']}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1">{{$order_attr['mileage']}}</textarea>
                                          </td>
                                      </tr> 
                                  </tbody>
                               </table>
                            </div>
                            <div class="clear"></div>
                            <hr class="dashed">
                            <p class="ml20 m-t-10"><b>免费礼品或服务</b>  </p>
                            <div style="width: 75%;margin:0 auto">
                               <table class="tbl">
                                  <tbody>
                          
                                      <tr>
                                          <td><p class="tac fs14 weight">名称</p></td>
                                          <td><p class="tac fs14 weight">数量</p></td>
                                          <td><p class="tac fs14 weight">状态</p></td>
                                          <td><p class="tac fs14 weight">说明</p></td>
                                      </tr> 
                                      @if(count($zengpin)>0)
                                      <?php foreach ($zengpin as $key => $value): ?>
                                        <tr>
                                          <td><p class="tac fs14 weight">{{$value['title']}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['num']}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['is_install']}}</p></td>
                                          <td width="300">
                                              <span class="tac fs14 weight">{{$value['beizhu']}}</span>
                                              
                                          </td>
                                      </tr> 
                                      <?php endforeach ?>
                                      @endif
                                      
                                     
                                  </tbody>
                               </table>
                            </div>

                            <hr class="dashed">
                            <p class="ml20 m-t-10"><b>需要客户配合提供的文件资料   </b>  </p>

                            <div style="width: 75%;margin:0 auto">
                               <table class="tbl">
                                    <tr>
                                        <td width="160"><p class="tac fs14 weight">文件资料使用场合</p></td>
                                        <td width="200"><p class="tac fs14 weight">文件资料名称</p></td>
                                        <td width="150"><p class="tac fs14 weight">数量</p></td>
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">投保(含交强险）</p></td>
                                        <td colspan="3" class="nopadding ">
                                           <table class="tbl2" width="100%">
                                                <tbody>
                                                <?php foreach ($toubaofile_self as $key => $value): ?>
                                                 
                                                    <tr>
                                                      <td width="199" class="bottomtborder ">
                                                          <p class="tac fs14">{{$value['title']}}</p>
                                                      </td>
                                                      <td width="150" class="bottomtborder norightborder">
                                                          <p class="tac fs14">
                                                          <?php if ($value['yj']): ?>
                                                            √
                                                          <?php else: ?>
                                                            {{$value['num']}}
                                                          <?php endif ?>

                                                          </p>
                                                      </td>
                                                      
                                                    </tr>
                                                <?php endforeach ?>
                                                
                                                
                                                
                                            </tbody></table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">上牌(含缴购置税）</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="tbl2" width="100%">
                                                <tbody>
                                                <?php foreach ($shangpaifile_self as $key => $value): ?>
                                                  
                                                  <tr>
                                                    <td width="199" class="bottomtborder">
                                                        <p class="tac fs14">{{$value['title']}}</p>
                                                    </td>
                                                    <td width="150" class="bottomtborder">
                                                        <p class="tac fs14"><?php if ($value['yj']): ?>
                                                            √
                                                          <?php else: ?>
                                                            {{$value['num']}}
                                                          <?php endif ?></p>
                                                    </td>
                                                    
                                                </tr>
                                                <?php endforeach ?>
                                                
                                                
                                            </tbody></table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">上临时牌照</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="" width="100%">
                                                <tbody>
                                                <?php foreach ($linpaifile_self as $key => $value): ?>
                                                  
                                                  <tr>
                                                    <td width="199" class="bottomtborder">
                                                        <p class="tac fs14">{{$value['title']}}</p>
                                                    </td>
                                                    <td width="150" class="bottomtborder">
                                                        <p class="tac fs14">
                                                        
                                                          <?php if ($value['yj']): ?>
                                                            √
                                                          <?php else: ?>
                                                            {{$value['num']}}
                                                          <?php endif ?>
                                                        </p>
                                                    </td>
                                                    
                                                </tr>
                                                <?php endforeach ?>
                                                
                                                
                                            </tbody></table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">国家节能补贴</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="tbl2" width="100%">
                                                <tbody>
                                                <?php foreach ($butiefile_self as $key => $value): ?>
                                                  
                                                  <tr>
                                                    <td width="199" class="bottomtborder">
                                                        <p class="tac fs14">{{$value['title']}}</p>
                                                    </td>
                                                    <td width="150" class="bottomtborder">
                                                        <p class="tac fs14"><?php if ($value['yj']): ?>
                                                            √
                                                          <?php else: ?>
                                                            {{$value['num']}}
                                                          <?php endif ?></p>
                                                    </td>
                                                    
                                                </tr>
                                                <?php endforeach ?>
                                                
                                                
                                            </tbody></table>
                                        </td>
                                        
                                    </tr> 
                               </table>
                            </div>
                            
                            <hr class="dashed">
                            <p class="m-t-10 " style="width: 96%;margin:0 auto;"><b class="blue tdu">交车时间</b></p>
                            <ul class="pdi-time">
                            
                               <?php foreach ($dates as $key => $value): ?>
                                <li>
                                 <span class="txt-w">{{$value}}</span>
                                 <div class="split-white"></div>
                                 
                                </li>
                              <?php endforeach ?>
                               <div class="clear"></div>
                            </ul>

                            <dl class="hasselect">
                              <dt><span class="p5">首次推荐日期：</span></dt>
                              <?php foreach ($pdi_date_dealer as $key => $value): ?>
                                <?php if(empty($value)) continue;?>
                                <dd><span class="p5">{{$value}}</span></dd>
                              <?php endforeach ?>
                            </dl>
                            <div class="clear"></div>
                            <dl class="hasselect">
                              <dt><span class="p5">客户希望日期：</span></dt>
                              <?php foreach ($pdi_date_client as $key => $value): ?>
                                <?php if(empty($value)) continue;?>
                                <dd><span class="p5">{{$value}}</span></dd>
                              <?php endforeach ?>
                              <dd style="border:0;cursor: default;"><span>（免收超期费日期）</span></dd>
                            </dl>
                            <div class="clear"></div>
                            <dl class="hasselect">
                              <dt><span class="p5">客户透露原因：</span></dt>
                              <dd style="border:0;cursor: default;">
                                 <input class="reson" type="text"  value="{{$order_attr['take_cause']}}" disabled>
                              </dd>
                            </dl>
                            <div class="clear"></div>
      
                            <p class="ifl fs14 pl20"><i class="yuan"></i></p>
                            <div class="ifl">
                                <p class="fs14"><b>与客户达成一致的交车日期：</b></p>
                                <p class="fs14"><input class="radios" type="radio" name="act_date" value="Y" disabled <?php if(isset($pdiReply['act_date']) && $pdiReply['act_date'] =='Y'){ echo 'checked';} ?>><span class="fn">接受客户希望日期（<?php foreach ($pdi_date_client as $key => $value): ?>
                                <?php if(empty($value)) continue;?>
                                {{$value}} 
                              <?php endforeach ?>），免收超期费</span></p>
                                <p class="fs14 m-t-10" style="width: 800px;">
                                  <input class="radios fl" type="radio" name="act_date" value="N" disabled <?php if(isset($pdiReply['act_date']) && $pdiReply['act_date'] =='N'){ echo 'checked';} ?>>
                                  <span class="fn fl">与客户协商后定为</span>

                                  <div class="btn-group m-r fl"  style="width:160px;margin-top: -4px;">
                                      <div class="form-group psr">
                                        <input style="" type="text" name='jc_date' placeholder="<?php if(isset($pdiReply['jc_date'])){echo $pdiReply['jc_date'];}?>" 
                                        class="form-control "  
                                        onfocus ="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });" 
                                        ms-on-click="initDP('2015-12-2 00:00:00','2015-12-19 00:00:00','2015-12-26 00:00:00')"
                                        disabled >
                                        <i class="rili" ></i>
                                      </div>
                                  </div>
                                  <div class="btn-group m-r fl bts fn" style="margin-top: -4px;">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" disabled>
                                          <span class="dropdown-label"><span>上午/下午</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-select">
                                          <input type="hidden" name="d-s-r" value='上午/下午'  disabled/>
                                          <li ms-on-click="selectTime" class="active"><a><span>上午/下午</span></a></li>
                                          <li ms-on-click="selectTime"><a><span>上午</span></a></li>
                                          <li ms-on-click="selectTime"><a><span>下午</span></a></li>
                                      </ul>
                                  </div>
                                  <span class="fn fl cq">超期费金额：</span>
                                  <input class="cqprice fl" type="text" name="over_fee" value="<?php if(isset($pdiReply['over_fee'])){echo $pdiReply['over_fee'];}?>" disabled>
                                  <span class="fn fl">元</span>
                                  <div class="clear"></div>

                                </p>
                            </div>
                            <div class="clear"></div>
                            <hr class="dashed">
                            <?php 
                            if(!empty($order_attr['trip_way'])){
                            	$trip_way = unserialize($order_attr['trip_way']);
                            	if($trip_way['fangshi'] == "自己开回"){
                            ?>
                            <div style="width: 96%;margin:0 auto;">
                                <p class="m-t-10 " ><b class="blue tdu">迎送服务</b></p>
                                <p><b>客户前来提车方式：</b>自己安排直接到店 自己开回</p>
                            </div>
                            <?php }else{ ?>
                            <div style="width: 96%;margin:0 auto;">
                                <p class="m-t-10 " ><b class="blue tdu">迎送服务</b></p>
                            </div>
                            <p class="ifl fs14 pl20"><i class="yuan"></i></p>
                            <div class="ifl">
                                <p><b>客户提车后车辆回程方式：</b>希望提供送车服务报价参考</p>
                                <p><b>送车大致地址：</b><input class="address fn" type="text"  value="{{$order_attr['deliver_addr']}}" disabled></p>
                                <p class="m-t-10">
                                    <input type="checkbox" class="fl radios" name="daijia[choose]" value='Y' disabled <?php if(isset($pdiReply['daijia']['choose'])&&$pdiReply['daijia']['choose']=='Y'){echo 'checked';}?>>
                                    <b class="fl fn">代驾送车</b>
                                    <span class="fl">服务费：</span>
                                    <input class="cqprice fl" type="text" name="daijia[fee]"  value="<?php if(isset($pdiReply['daijia']['fee'])){echo $pdiReply['daijia']['fee'];}?>" disabled>
                                    <span class="fl fn">元</span>
                                    <div class="clear"></div>
                                </p>
                                <p class="m-t-10">
                                    <b class="fl fn pl20">支付方式：</b>
                                    <input class="radios fl" type="radio" name="daijia[pay_type]" value='pay_first' disabled <?php if(isset($pdiReply['daijia']['pay_type'])&&$pdiReply['daijia']['pay_type']=='pay_first'){echo 'checked';}?>>
                                    <span class="fn fl">提车时先支付后送车</span>
                                    <input class="radios fl" style="margin-left: 10px;" type="radio" name="daijia[pay_type]"  value='pay_wait_ok' disabled <?php if(isset($pdiReply['daijia']['pay_type'])&&$pdiReply['daijia']['pay_type']=='pay_wait_ok'){echo 'checked';}?>>
                                    <span class="fn fl">车送到目的地后当场支付</span>
                                    <div class="clear"></div>

                                </p>

                                <p class="m-t-10">
                                    <input type="checkbox" class="fl radios" name="transport[choose]" value="Y" <?php if(isset($pdiReply['transport']['choose'])&&$pdiReply['transport']['choose']=='Y'){echo 'checked';}?> disabled>
                                    <b class="fl fn">板车运输送车</b>
                                    <span class="fl">服务费：</span>
                                    <input class="cqprice fl" type="text" name="transport[fee]"  value="<?php if(isset($pdiReply['transport']['fee'])){echo $pdiReply['transport']['fee'];}?>" disabled>
                                    <span class="fl fn">元</span>
                                    <span class="fl">运输险保费：</span>
                                    <input class="cqprice fl" type="text" name="transport[bx_fee]"  value="<?php if(isset($pdiReply['transport']['bx_fee'])){echo $pdiReply['transport']['bx_fee'];}?>" disabled>
                                    <span class="fl fn">元</span>
                                    <div class="clear"></div>
                                </p>
                                <p class="m-t-10">
                                    <b class="fl fn pl20">支付方式：</b>
                                    <input class="radios fl" type="radio" name="transport[pay_type]" value="pay_first" disabled <?php if(isset($pdiReply['transport']['pay_type'])&&$pdiReply['transport']['pay_type']=='pay_first'){echo 'checked';}?>>
                                    <span class="fn fl">提车时先支付后送车</span>
                                    <input class="radios fl" style="margin-left: 10px;" type="radio" name="transport[pay_type]" value="pay_wait_ok" disabled <?php if(isset($pdiReply['transport']['pay_type'])&&$pdiReply['transport']['pay_type']=='pay_wait_ok'){echo 'checked';}?>>
                                    <span class="fn fl">车送到目的地后当场支付</span>
                                    <div class="clear"></div>
                                </p>
                            </div>
                            <?php
                            	}
                            }
                            ?>
                            
                            <div class="clear"></div>
                            <hr class="dashed">
                            <div style="width: 96%;margin:0 auto;">
                                <p class="m-t-10 " ><b class="blue tdu">商业保险追加</b></p>
                                <p>客户对以下车辆商业保险感兴趣，请报价：</p>
                                <?php 
                                	if(!empty($order_attr['other_baoxian'])){
                                		$other_baoxian = unserialize($order_attr['other_baoxian']);
                                		$chensun = $other_baoxian['chesun'];
                                		$renyuan = isset($other_baoxian['renyuan'])?$other_baoxian['renyuan']:array();
                                		$sanzhe = isset($other_baoxian['sanzhe'])?$other_baoxian['sanzhe']:array();;
                                	}
                                	$baoxianStr = array(
			                                			1=>"自燃损失险",
			                                			2=>"新增加设备损失险",
			                                			3=>"发动机涉水损失险",
			                                			4=>"修理期间费用补偿险",
			                                			5=>"车上货物责任险",
			                                			6=>"机动车损失险无法找到第三方特约险",
			                                			7=>"指定修理厂险",
			                                			8=>"精神损害抚慰金责任险",
			                                			9=>"增加第三者责任险保额",
			                                			10=>"增加车上人员责任险保额"
                                					);
                                	$i=1;
                                	//<!--展示车损险其他险种-->
                                	foreach($chensun as $k => $v){
                                		if(isset($v['chesun'])){
                                ?>
                                
                                <p><b>{{$i}}.{{$baoxianStr[$k]}}：</b></p>
                                <p class="m-t-10">
                                    <input type="radio" class="fl radios" name="bx[{{$k}}][choose]" value='Y' <?php if(isset($pdiReply['baoxian'][$k]['choose']) && $pdiReply['baoxian'][$k]['choose']=='Y'){echo 'checked';}?> disabled>
                                    <b class="fl fn">首年保费
                                    <?php
                                     if(in_array($k,array(1,3,4,5,8))){
                                     	if($v['bjm']=="是") {
                                     		echo "(<b>追加不计免赔</b>)";
                                     	}else{
                                     		echo "(<b>不追加不计免赔</b>)";
                                     	}
                                     }
                                     ?>
                                    	：</b>
                                    <input class="cqprice fl" type="text" name="bx[{{$k}}][bf]"  value="<?php if(isset($pdiReply['baoxian'][$k]['bf'])){echo $pdiReply['baoxian'][$k]['bf'];}?>" disabled>
                                    <span class="fl fn">元</span>
                                    <span class="fl ml20">说明：</span>
                                    <input class="cqprice sm fl" type="text" name="bx[{{$k}}][bfsm]"  value="<?php if(isset($pdiReply['baoxian'][$k]['bfsm'])){echo $pdiReply['baoxian'][$k]['bfsm'];}?>" disabled>
                                    <div class="clear"></div>
                                </p>
                                <p class="m-t-10">
                                    <input type="radio" class="fl radios" name="bx[{{$k}}][choose]" value='N' <?php if(isset($pdiReply['baoxian'][$k]['choose']) && $pdiReply['baoxian'][$k]['choose']=='N'){echo 'checked';}?> disabled>
                                    <b class="fl fn">恕该保险公司无法提供   </b>
                                    <div class="clear"></div>
                                </p>
                                <br>
                                
                                <?php 
                                		$i++;
                                		}
									}
									//<!--展示人员险其他险种-->
                                    foreach($renyuan as $k => $v){
                                		if(isset($v['renyuan'])){
                                ?>
                                
                                <p><b>{{$i}}.{{$baoxianStr[$k]}}：</b></p>
                                <p class="m-t-10">
                                    <input type="radio" class="fl radios" name="bx[{{$k}}][choose]" value='Y' <?php if(isset($pdiReply['baoxian'][$k]['choose']) && $pdiReply['baoxian'][$k]['choose']=='Y'){echo 'checked';}?> disabled>
                                    <b class="fl fn">首年保费
                                    <?php
                                     if(in_array($k,array(1,3,4,5,8))){
                                     	if($v['bjm']=="是") {
                                     		echo "(<b>追加不计免赔</b>)";
                                     	}else{
                                     		echo "(<b>不追加不计免赔</b>)";
                                     	}
                                     }
                                     ?>
                                    	：</b>
                                    <input class="cqprice fl" type="text" name="bx[{{$k}}][bf]"  value="<?php if(isset($pdiReply['baoxian'][$k]['bf'])){echo $pdiReply['baoxian'][$k]['bf'];}?>" disabled>
                                    <span class="fl fn">元</span>
                                    <span class="fl ml20">说明：</span>
                                    <input class="cqprice sm fl" type="text" name="bx[{{$k}}][bfsm]"  value="<?php if(isset($pdiReply['baoxian'][$k]['bfsm'])){echo $pdiReply['baoxian'][$k]['bfsm'];}?>" disabled>
                                    <div class="clear"></div>
                                </p>
                                <p class="m-t-10">
                                    <input type="radio" class="fl radios" name="bx[{{$k}}][choose]" value='N' <?php if(isset($pdiReply['baoxian'][$k]['choose']) && $pdiReply['baoxian'][$k]['choose']=='N'){echo 'checked';}?> disabled>
                                    <b class="fl fn">恕该保险公司无法提供   </b>
                                    <div class="clear"></div>
                                </p>
                                <br>
                                
                                <?php 
                                		$i++;
                                		}
									}
									//<!--展示三者其他险种-->
                                	foreach($sanzhe as $k => $v){
                                		if(isset($v['sanzhe'])){
                                ?>
                                
                                <p><b>{{$i}}.{{$baoxianStr[$k]}}：</b></p>
                                <p class="m-t-10">
                                    <input type="radio" class="fl radios" name="bx[{{$k}}][choose]" value='Y' <?php if(isset($pdiReply['baoxian'][$k]['choose']) && $pdiReply['baoxian'][$k]['choose']=='Y'){echo 'checked';}?> disabled>
                                    <b class="fl fn">首年保费 ：</b>
                                    <input class="cqprice fl" type="text" name="bx[{{$k}}][bf]"  value="<?php if(isset($pdiReply['baoxian'][$k]['bf'])){echo $pdiReply['baoxian'][$k]['bf'];}?>" disabled>
                                    <span class="fl fn">元</span>
                                    <span class="fl ml20">说明：</span>
                                    <input class="cqprice sm fl" type="text" name="bx[{{$k}}][bfsm]" id="" value="<?php if(isset($pdiReply['baoxian'][$k]['bfsm'])){echo $pdiReply['baoxian'][$k]['bfsm'];}?>" disabled>
                                    <div class="clear"></div>
                                </p>
                                <p class="m-t-10">
                                    <input type="radio" class="fl radios" name="bx[{{$k}}][choose]" value='N' <?php if(isset($pdiReply['baoxian'][$k]['choose']) && $pdiReply['baoxian'][$k]['choose']=='N'){echo 'checked';}?> disabled>
                                    <b class="fl fn">恕该保险公司无法提供   </b>
                                    <div class="clear"></div>
                                </p>
                                <br>
                                
                                <?php 
                                		$i++;
                                		}
									}
                                
                                ?>
                            </div>
                            <hr class="dashed">

                            <div style="width: 96%;margin:0 auto;">
                                <p class="m-t-10 " ><b class="blue tdu">提车委托</b></p>
                                <p><b>客户计划上牌（注册登记）的详细名称：</b>{{$order['reg_name']}}</p>
                                <p>
                                  <b>客户计划委托的提车人：</b>
                                  <span>{{$ticheren['username']}}</span>
                                  <b class="ml20">电话：</b>
                                  <span>{{$ticheren['mobile']}}</span>
                                </p>
								@if($order_attr['agreement']==1)
                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl">
                                   <p><b>上牌名称与提车人姓名一致，需要客户提车人配合提供的文件资料：</b></p>
                                </div>
                                <div class="clear"></div>
								@else
                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl">
                                   <p><b>上牌名称与提车人姓名不一致，需要客户提车人配合提供的文件资料：</b></p>
                                </div>
                                <div class="clear"></div>
								@endif
                                <div style="width: 75%;margin:0 auto">
                                   <table class="tbl">
                                        <tr>
                                            <td width="160"><p class="tac fs14 weight">文件资料使用场合</p></td>
                                            <td width="200"><p class="tac fs14 weight">文件资料名称</p></td>
                                            <td width="150"><p class="tac fs14 weight">数量</p></td>
                                        </tr> 
                                        <tr>
                                            <td width="160"><p class="tac fs14 ">投保(含交强险）</p></td>
                                            <td colspan="3" class="nopadding ">
                                               <table class="tbl2" width="100%">
                                                    <tbody>

                                                    <?php foreach (array_filter($confirmfile['toubao']) as $key => $value): 
                                                        $vv=explode(',', $value)
                                                    ?>
                                                      <tr>
                                                          <td width="199" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[0]}}</p>
                                                          </td>
                                                          <td width="150" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[1]}}</p>
                                                          </td>
                                                          
                                                      </tr>
                                                    <?php endforeach ?>
                                                    
                                                </tbody></table>
                                            </td>
                                            
                                        </tr> 
                                        <tr>
                                            <td width="160"><p class="tac fs14 ">上牌(含缴购置税）</p></td>
                                            <td colspan="3" class="nopadding">
                                               <table class="tbl2" width="100%">
                                                    <tbody>

                                                    <?php foreach (array_filter($confirmfile['shangpai']) as $key => $value): 
                                                        $vv=explode(',', $value)
                                                    ?>
                                                      <tr>
                                                          <td width="199" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[0]}}</p>
                                                          </td>
                                                          <td width="150" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[1]}}</p>
                                                          </td>
                                                          
                                                      </tr>
                                                    <?php endforeach ?>
                                                    
                                                </tbody></table>
                                            </td>
                                            
                                        </tr> 
                                        <tr>
                                            <td width="160"><p class="tac fs14 ">上临时牌照</p></td>
                                            <td colspan="3" class="nopadding">
                                               <table class="tbl2" width="100%">
                                                    <tbody>

                                                    <?php foreach (array_filter($confirmfile['linpai']) as $key => $value): 
                                                        $vv=explode(',', $value)
                                                    ?>
                                                      <tr>
                                                          <td width="199" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[0]}}</p>
                                                          </td>
                                                          <td width="150" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[1]}}</p>
                                                          </td>
                                                          
                                                      </tr>
                                                    <?php endforeach ?>
                                                    
                                                </tbody></table>
                                            </td>
                                            
                                        </tr> 
                                        <tr>
                                            <td width="160"><p class="tac fs14 ">国家节能补贴</p></td>
                                            <td colspan="3" class="nopadding">
                                               <table class="tbl2" width="100%">
                                                    <tbody>
													@if(isset($confirmfile['butie']))
                                                    <?php foreach (array_filter($confirmfile['butie']) as $key => $value): 
                                                        $vv=explode(',', $value)
                                                    ?>
                                                      <tr>
                                                          <td width="199" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[0]}}</p>
                                                          </td>
                                                          <td width="150" class="nobottomborder">
                                                              <p class="tac fs14">{{$vv[1]}}</p>
                                                          </td>
                                                          
                                                      </tr>
                                                    <?php endforeach ?>
                                                    @endif
                                                </tbody></table>
                                            </td>
                                            
                                        </tr> 
                                   </table>
                                </div>
                                
                            </div>
                            <hr class="dashed">

                            <div style="width: 96%;margin:0 auto;">
                                <p class="m-t-10 " >
                                  <b class="blue tdu">客户的特别要求：</b>
                                  <input class="cqprice  address fn" type="text"  value="{{$order_attr['other']}}" disabled>
                                </p>
                                <p><b>文件证明类</b></p>
                                <p class="m-t-10"></p>  

                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl" id="file" style="width:95%">
                                   <p><b>可以办理</b></p>
                                   @if(isset($pdiReply['project_ok']) && count($pdiReply['project_ok'])>0)
	                                   @foreach($pdiReply['project_ok'] as $K=>$v)
	                                   <div class="fl wap">
	                                     <p class="m-t-10">
	                                        <span class="fl">项目<span class="num">1</span>：</span>
	                                        <input class="cqprice sm fl" type="text" name="project[0][name]"  value="{{$v['name']}}" disabled>
	                                        <b class="fl fn ml20">办理费用： </b>
	                                        <input class="cqprice fl" type="text" name="project[0][money]"  value="{{$v['money']}}" disabled>
	                                        <span class="fl fn">元</span>
	                                        <span class="fl fn ml20">需要时间：</span>
	                                        <input class="cqprice fl day" type="text" name="project[0][day]"  value="{{$v['day']}}" disabled>
	                                        <span class="fl fn">天</span>
	                                        <div class="clear"></div>
	                                    </p>
	                                    <p class="m-t-10">
	                                        <span class="fl">交车时间：</span>
	                                        <input class="fl radios" type="radio" name="project[0][effect]"  value="N" <?php if($v['effect']=='N'){echo 'checked';} ?> disabled>
	                                        <b class="fl fn">无影响 </b>
	                                        <input class="radios fl ml20" type="radio"  name="project[0][effect]"  value="Y" <?php if($v['effect']=='Y'){echo 'checked';} ?> disabled>
	                                        <span class="fl fn">延后到：</span>
	                                        <div class="btn-group m-r fl" style="width:160px;margin-top: -4px;">
	                                            <div class="form-group psr">
	                                              <input style="" type="text" name="project[0][jc_date]" placeholder="<?php if(isset($v['jc_date'])){echo $v['jc_date'];}?>" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });" disabled>
	                                              <i class="rili"></i>
	                                            </div>
	                                        </div>
	
	                                        <a class="juhuang fl  ml20">删除</a>
	                                        <a class="juhuang fl  ml20">继续添加</a>
	                                        <div class="clear"></div>
	                                    </p>
	                                  </div>
	                                  @endforeach
                                  @else
                                 	 暂无可以办理项目
                                  @endif
                                </div>
                                <div class="clear"></div>

                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl" id="banli" style="width:95%">
                                   <p><b>无法办理</b></p>
                                    @if(isset($pdiReply['project_not']) && count($pdiReply['project_not'])>0)
	                                   @foreach($pdiReply['project_not'] as $K=>$v)
	                                   <div class="wap">
	                                     <p class="m-t-10">
	                                        <span class="fl">项目<span class="num">{{$k}}</span>：</span>
	                                        <input class="cqprice sm fl" type="text" name="project_not[0]"  value="{{$v}}" disabled>
	                                        <a class="juhuang fl  ml20">删除</a>
	                                        <a class="juhuang fl  ml20">继续添加</a>
	                                        <div class="clear"></div>
	                                    </p>
	                                  </div>
	                                   @endforeach
	                                @else
	                                	暂未提交此项目
	                                @endif
                                   
                                   
                                </div>

                                <div class="clear"></div>

                                <p class="m-t-10"><b>赠送礼品或服务类</b></p>
                                <p class="m-t-10"></p>  
                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl" id="manzu" style="width:95%">
                                   <p><b>可以满足</b></p>
                                   @if(isset($pdiReply['service_ok']) && count($pdiReply['service_ok'])>0)
	                                   @foreach($pdiReply['service_ok'] as $K=>$v)
	                                   <div class="wap">
	                                     <p class="m-t-10">
	                                        <span class="fl">项目<span class="num">{{$k}}</span>：</span>
	                                        <input class="cqprice sm fl" type="text" name="service_ok[0]"  value="{{$v}}" disabled>
	                                        <a class="juhuang fl  ml20">删除</a>
	                                        <a class="juhuang fl  ml20">继续添加</a>
	                                        <div class="clear"></div>
	                                    </p>
	                                  </div>
	                                   @endforeach
	                                @else
	                                	暂未提交此项目
	                                @endif
                                </div>
                                <div class="clear"></div>

                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl" id="bumanzu" style="width:95%">
                                   <p><b>无法满足</b></p>
                                   @if(isset($pdiReply['service_not']) && count($pdiReply['service_not'])>0)
	                                   @foreach($pdiReply['service_not'] as $K=>$v)
	                                   <div class="wap">
	                                     <p class="m-t-10">
	                                        <span class="fl">项目<span class="num">{{$k}}</span>：</span>
	                                        <input class="cqprice sm fl" type="text" name="service_ok[0]"  value="{{$v}}" disabled>
	                                        <a class="juhuang fl  ml20">删除</a>
	                                        <a class="juhuang fl  ml20">继续添加</a>
	                                        <div class="clear"></div>
	                                    </p>
	                                  </div>
	                                   @endforeach
	                                @else
	                                	暂未提交此项目
	                                @endif
                                   
                                </div>
                                <div class="clear"></div>

                            </div>

                        </td>
                      </tr>
                    </table>

                     <p class="center">
                          <input type="button" value="已提交" class="btn btn-s-md btn-danger" disabled>
                     </p>

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