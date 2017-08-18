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
                    <small class="juhuang">发出通知</small>
                    <i></i>
                    <small>确认反馈</small>
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
                                            <td colspan="2"><p><b>订单类别：</b>
                                              <?php if ($bj['bj_producetime']): ?>
                                              现车订单
                                            <?php else: ?>
                                              远期订单
                                            <?php endif ?>
                                            </p><hr class="dashed"></td>
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
                                            <td><p><b>类别：</b>车辆类别：全新中规车整车</p></td>
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
                                        <p class="status tac status2"><b>订单状态：{{getStatusNotice($order['cart_sub_status'])}}    </b></p>
                                        <p class="tac fs15 m-t-10">离交车时限还剩<span class="juhuang">{{diffBetweenTwoDays($order['jiaoche_time'])}}天</span></p>
                                      
                                           
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
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：   </b>{{$guarantee}} </p>
                                               
                                           
                                       
                                    </div>
                                </td>
                               
                            </tr> 
                          
                        </tbody>
                    </table>


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
                                                   <?php 
                                                      $count=0.00;
                                                      foreach ($xzj as $key =>$value) {
                                                          if(!$value['xzj_yc'] || !$value['is_install']) continue;
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
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"><?php echo $count; ?></span></h2>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                    </table>                                      
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
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
                    <h2 class="title">原厂选装精品  </h2>
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
                           <td>{{$value['price']}}</td>
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

                    <?php if ($bj['bj_baoxian'] && $bj['bj_bx_select']): ?>
                    <p class="fs14"><b>客户是否经销商处购买商业保险：</b>确定购买</p>
                    <p class="fs14"><b>提供商业保险的保险公司：</b>{{$baoxianname->bx_title}}</p>
                    <?php elseif ($bj['bj_bx_select']) : ?>
                      <p class="fs14"><b>客户是否经销商处购买商业保险：</b>待定</p>
                      <p class="fs14"><b>提供商业保险的保险公司：</b>{{$baoxianname->bx_title}}</p>
                    <?php else: ?>
                      <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                    <?php endif ?>
                    <?php if ($bj['bj_baoxian'] || $bj['bj_bx_select']): ?>
                    <p class="fs14">商业保险的首年保费基准：<span class="sj sj2" ms-on-click="toggleList"></span></p>
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
                                        <th class="" width="140">保费原价</th>
                                        <th class="norightborder">折后价</th>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">机动车损失险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class="tac">{{$chesun['price']}}元</td>
                                        <td class="norightborder tac">{{$chesun['discount_price']}}元</td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">机动车盗抢险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class="">{{$daoqiang['price']}}元</td>
                                        <td class="norightborder">{{$daoqiang['discount_price']}}元</td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">第三者责任保险</label></td>
                                        <td class="cell nopadding" width="320" >
                                             
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度5万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">赔付额度10万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">赔付额度15万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">赔付额度20万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">赔付额度30万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">赔付额度50万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">赔付额度100万元</p>
                                                    </td>
                                                </tr>
                                            </table>
                                    
                                        </td>
                                        <td class="nopadding">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($sanzhe as $key => $value): ?>
                                              <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$value['price']}} 元</p>
                                                    </td>
                                                </tr
                                            ><?php endforeach ?>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                              <?php foreach ($sanzhe as $key => $value): ?>
                                              <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$value['discount_price']}} 元</p>
                                                    </td>
                                                </tr
                                            ><?php endforeach ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell nobottomborder"><label class="fs14">车上人员责任险</label></td>
                                        <td class="cell nopadding nobottomborder" width="320">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额1万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额2万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额3万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额4万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额5万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">乘客每次事故责任限额1万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">乘客每次事故责任限额2万元</p>
                                                  </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">乘客每次事故责任限额3万元</p>
                                                  </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">乘客每次事故责任限额4万元</p>
                                                  </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">乘客每次事故责任限额5万元</p>
                                                  </td>
                                                </tr>
                                               
                                            </table>
                                        </td>
                                        <td class="nobottomborder nopadding" width="130" align="center">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($renyuan as $key => $value): ?>
                                                <tr>
                                                    <td class="class="bottomtborder norightborder"">
                                                        <p class="tal fs14">{{$value['price']}} 元</p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>
                                            </table>
                                        </td>
                                        <td class="norightborder nobottomborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($renyuan as $key => $value): ?>
                                                <tr>
                                                    <td class="class="bottomtborder norightborder"">
                                                        <p class="tal fs14">{{$value['discount_price']}} 元</p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>
                                            </table>
                                        </td>
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
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">进口玻璃</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">国产玻璃</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="nopadding" width="140">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($boli as $key => $value): ?>
                                                <tr>
                                                    <td class="class="bottomtborder norightborder"">
                                                        <p class="tal fs14">{{$value['price']}} 元</p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($boli as $key => $value): ?>
                                                <tr>
                                                    <td class="class="bottomtborder norightborder"">
                                                        <p class="tal fs14">{{$value['discount_price']}} 元</p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">自燃损失险</label></td>
                                        <td class="cell" width="320">按保险公司规定执行</td>
                                        <td class="">{{$ziran['price']}}</td>
                                        <td class="norightborder">{{$ziran['discount_price']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">车身划痕损失险</label></td>
                                        <td class="cell nopadding" width="320">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度0.2万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">赔付额度0.5万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度1万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度2万元</p>
                                                    </td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                        <td class="nopadding">
                                            <table class="tbl2" width="100%">
                                            <?php foreach ($huahen as $key => $value): ?>
                                              <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$value['price']}}</p>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                               
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($huahen as $key => $value): ?>
                                              <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$value['discount_price']}}</p>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">不计免赔特约险</label></td>
                                        <td class="cell nopadding" width="320">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">机动车损失险不计免赔</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">机动车盗抢险不计免赔</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">第三者责任险不计免赔，按赔付额度保费x费率</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">车上人员责任险不计免赔，按赔付额度保费x费率</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">车身划痕损失险不计免赔，按赔付额度保费x费率</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$chesun['bjm_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$daoqiang['bjm_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">费率 {{intval($bjm_sanzhe_rate)}}   %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">费率 {{intval($bjm_renyuan_rate)}} %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">费率 {{intval($bjm_huahen_rate)}} %计保费</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$chesun['bjm_discount_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">{{$daoqiang['bjm_discount_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">左侧保费 x {{$bj['bj_baoxian_discount']}}%</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">左侧保费 x {{$bj['bj_baoxian_discount']}}%</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">左侧保费 x {{$bj['bj_baoxian_discount']}}%</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                     
                                    
                                </tbody></table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p>说明：具体投保险种与保额，由客户在交车通知反馈中选择。 </p>
                    <?php endif ?>
                    <?php if ($order['shangpai_area']==$jxs['d_shi'] && $bj['bj_shangpai']): ?>
                      <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                      <p class="fs14"><b>代办上牌服务费金额：</b>{{$bj['bj_shangpai_price']}} 元</p>
                    <?php endif ?>

                    <?php if ($order['shangpai_area']==$jxs['d_shi'] && $bj['bj_shangpai']==0): ?>
                    <p class="fs14"><b>是否由经销商代办上牌：</b>待定</p>
                    <p class="fs14"><b>代办上牌服务费金额：</b>{{$bj['bj_shangpai_price']}} 元</p>
                   <?php endif ?> 
                    <?php if ($order['shangpai_area']!=$jxs['d_shi']): ?>
                      <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>
                    <?php if ($bj['bj_license_plate_break_contract']): ?>
                      <p class="fs14"><b>客户本人上牌违约赔偿金额：</b>{{$bj['bj_license_plate_break_contract']}} 元</p>
                    <?php endif ?>
                      

                    <?php endif ?>
                    <?php if ($bj['area_xianpai']): ?>
                    <p class="fs14"><b>限牌城市（北京）客户取得牌照指标的安排： </b>{{$order['zhibiao']}}</p>
                    <?php  endif ?>
                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <?php if ($bj['bj_linpai']): ?>
                      <p class="fs14"><b>是否由经销商代办车辆临时牌照：</b>确认代办</p>
                    <?php else: ?>
                      <p class="fs14"><b>是否由经销商代办车辆临时牌照：</b>待定</p>
                    <?php endif ?>

                    <p class="fs14"><b>代办车辆临时牌照（每次）服务费金额：</b>{{$bj['bj_linpai_price']}}</p>
                    
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

                    <h2 class="title">免费礼品或服务</h2>
                    <div style="width: 70%;margin:0 auto">
                        <table class="tbl">
                            <tbody><tr>
                                <th><label class="fs14 weight" >名称</label></th>
                                <th><label class="fs14 weight">数量</label></th>
                                <th><label class="fs14 weight">状态</label></th>
                            </tr>
                            @foreach($zengpin as $key =>$value) 
                            <tr>
                                <td><p class="tac fs14">{{ $value['title']}}</p></td>
                                <td><p class="tac fs14">{{ $value['num']}}</p></td>
                                <td><p class="tac fs14">@if($value['is_install'])
                                                已安装
                                            @else
                                                未安装
                                            @endif</p></td>
                            </tr>
                            @endforeach
                        </tbody></table>
                    </div>

                    <h2 class="title">交车有关事宜</h2>
                    <p class="fs14"><b>交车当场移交客户的文件资料：</b><span>{{$wenjian}}</span>（客户电话：{{$buyer['member_mobile']}}）</span></p>
                    <p class="fs14"><b>交车当场移交客户的随车工具：</b>{{$gongju}}</p>
                    <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
                    <p class="fs14">信用卡:{{$bj['more']['ka']['xyk']}}</p>
                    <p class="fs14">借记卡:{{$bj['more']['ka']['jjk']}}</p>


                    <h2 class="title">补贴</h2>
                    <?php if ($bj['bj_butie']): ?>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b>$bj['bj_butie']</p>
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
                    <?php if ($bj['bj_zf_butie']): ?>
                    <div class="clear"></div>
                    <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                  <?php endif ?>
                    <?php if ($bj['bj_cj_butie']): ?>
                  <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                <?php endif ?>
                    <div class="clear"></div>

                    

                     <p class="center">
                        <a href="{{url('dealer/pdinotice')}}/{{$order_num}}" data-grounp="" class="btn btn-s-md btn-danger">发出邀请</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" style="width:80px">修改</a>
                     </p>
                     <p class="fs14 center ml326"><span class="xing">*</span>点此按钮将导致客户获得歉意金等补偿！</p>
                    

                </div>
            </div>
        </div>
    </div>
    <div ms-controller="uitest" ms-ui-$opts="testui" data-id="ddd"></div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection