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
   
          <form action="{{ url('dealer/saveconfirm') }}" method="post" name="item-form">
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
                                            <td  width="50%"><p><b>订单号：</b>{{$order_num}}</p></td>
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
                                        <p class="status tac status2"><b>订单状态： {{getStatusNotice($order['cart_sub_status'])}}</b></p>
                                        
                                        <p class="tac fs15">补充预约信息仅剩<span class="juhuang"> {{$sy_time}}</span></p>
                                        <p class="tac ">约定交车时间<span class="juhuang"> {{$jiaoche_date['date']}}</span></p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu">查看订单总详情</a></p>
                                           
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($order['buy_id'],1)}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{ changeMobile($buyer['member_mobile'])}} </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b>{{$shangpai_area}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b>

                                        <?php if ($order['buytype']): ?>
                                          个人用车
                                            <?php else: ?>
                                          公司用车
                                          <?php endif ?> </p>
                                        <p class="pl20 pt">
                                          <b>上牌车主身份类别： </b>
                                          <span class="fr" style="width: 165px;color:#8e8d8d;text-align: left;">国内其他限牌城市户籍居民（北京）</span> 
                                          <span class="clear"></span>
                                        </p>
                                        <p class="clear"></p>
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：  {{$guarantee}} 元 </b> </p>

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
                                                       <th><p class="fs15">型号/说明</p></th>
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
                                            <td width="33%"><p><b>交车周期：    </b>{{ $bj['bj_jc_period'] }} 个月 </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>交车时限：  </b>年    {{ddate($order['jiaoche_time'])}}</p></td>
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
                           <th><p class="fs15">型号/说明</p></th>
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
                           <th><p class="fs15">型号/说明</p></th> 
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
                    <?php if ($order['final_baoxian']): ?>
                      <p class="fs14"><b>客户是否经销商处购买商业保险：</b>确定购买</p>
                            <p class="fs14"><b>提供商业保险的保险公司：</b>{{$baoxianname['bx_title']}}</p>
                      <?php else: ?>
                        <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                    <?php endif ?>
                    
                        
                    <p class="fs14">商业保险的首年保费基准：<span class="sj sj2" ms-on-click="toggleList"></span></p>

                    <?php if ($order['final_baoxian']): ?>
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
                                        <td class="tac">元</td>
                                        <td class="norightborder tac">元</td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">机动车盗抢险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class=""></td>
                                        <td class="norightborder"></td>
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
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
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
                                                        <p class="tal fs14">驾驶人每次事故责任限额6万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额7万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额8万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额9万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">驾驶人每次事故责任限额5万元</p>
                                                    </td>
                                                </tr>
                                               
                                            </table>
                                        </td>
                                        <td class="nobottomborder nopadding" width="130" align="center">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nobottomborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
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
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">自燃损失险</label></td>
                                        <td class="cell" width="320">按保险公司规定执行</td>
                                        <td class="">&nbsp;</td>
                                        <td class="norightborder">&nbsp;</td>
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
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度1万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度2万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">赔付额度0.5万元</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
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
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">费率    %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">费率    %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">费率    %计保费</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">&nbsp;</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">左侧保费 x 98%</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">左侧保费 x 98%</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tal fs14">左侧保费 x 98%</p>
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
                    <p class="tac">客户已选投保组合总价：<span class="juhuang">3，000.00</span>元</p>
                    <?php endif ?>
                    <h2 class="title">上牌（车辆注册登记）</h2>
                    <?php if ($order['shangpai']): ?>
                      <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                      <p class="fs14"><b>代办上牌服务费金额：</b>{{$bj['bj_shangpai_price']}} 元</p>
                      <?php if ($bj['area_xianpai']): ?>
                        <p class="fs14"><b>限牌城市（北京）客户取得牌照指标的安排： </b>{{$bj['area_xianpai']}}</p>
                      <?php endif ?>
                      

                    <?php else: ?>
                    <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>
                    <p class="fs14"><b>客户本人上牌违约金补偿：</b>{{$bj['bj_license_plate_break_contract']}}</p>
                    <?php endif ?>

                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <?php if ($order['linpai']): ?>
                    <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>确认代办</p>
                    <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b>{{$bj['bj_linpai_price']}} </p>
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
                            @foreach($bj['other_price'] as $key =>$value)
                            <?php if ($value<=0) continue; ?>
                              
                        <tr>
                            <td><p class="fs14 tac">{{ $key }}</p></td>
                            <td><p class="fs14 tac">{{ $value }} 元</p></td>
                        </tr>
                        @endforeach
                        </tbody></table>
                    </div>

                    

                    <h2 class="title">交车有关事宜</h2>
                    <p class="fs14"><b>交车当场移交客户的文件资料：</b><span>{{$wenjian}}</span></p>
                    <p class="fs14"><b>交车当场移交客户的随车工具：</b>{{$gongju}}</p>
                    <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
                    <p class="fs14">信用卡: {{$bj['more']['ka']['xyk']}}</p>
                    <p class="fs14">借记卡: {{$bj['more']['ka']['jjk']}}</p>


                    <h2 class="title">补贴</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <?php if ($order_attr['butie']): ?>
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
                    <?php if ($order_attr->zhihuan): ?>
                      <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                    <?php endif ?>
                    <?php if ($order_attr->cj_butie): ?>
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
                                              <span>{{$order_attr->vin}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">发动机号</p></td>
                                          <td width="300">
                                              <span>{{$order_attr->engine_no}}</span>
                                              <textarea  style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">实车出厂年月</p></td>
                                          <td width="300">
                                              <span>{{$order_attr->production_date}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">实车行驶里程（公里）</p></td>
                                          <td width="300">
                                              <span>{{$order_attr->mileage}}</span>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
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
                                      @if(!empty($zengpin) && count($zengpin)>=1)
                                        <?php foreach ($zengpin as $key => $value): ?>
                                         <tr>
                                          <td><p class="tac fs14 weight">{{$value['title']}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['num']}}</p></td>
                                          <td><p class="tac fs14 weight">{{$value['install']}}</p></td>
                                          <td width="300">
                                          <p class="tac fs14 weight">{{$value['notice']}}</p>
                                              
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
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
                                                
                                                <?php foreach ($orderfiles as $key => $value): ?>
                                                  <?php if($value['cate']!='投保') continue; ?>
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
                                            </table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">上牌(含缴购置税）</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="tbl2" width="100%">
                                                
                                                <?php foreach ($orderfiles as $key => $value): ?>
                                                  <?php if($value['cate']!='上牌照') continue; ?>
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
                                                
                                            </table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">上临时牌照</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="tbl2" width="100%">
                                                
                                                <?php foreach ($orderfiles as $key => $value): ?>
                                                  <?php if($value['cate']!='上临时移动牌') continue; ?>
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
                                                
                                            </table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                        <td width="160"><p class="tac fs14 ">国家节能补贴</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="tbl2" width="100%">
                                                
                                                <?php foreach ($orderfiles as $key => $value): ?>
                                                  <?php if($value['cate']!='领取国家节能补贴') continue; ?>
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
                                                
                                            </table>
                                        </td>
                                        
                                    </tr> 
                                    <tr>
                                      <td colspan="4" style="border:0">
                                          <p class="fs14">注：文件数量标记“√”表示文件为正本或原件，不同环节可通用。</p>
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
                              
                              <dd style="border:0;cursor: default;"><span></span></dd>
                            </dl>
                            <div class="clear"></div>
                            
      
                            <hr class="dashed">
                            <div style="width: 96%;margin:0 auto;">
                                <p class="m-t-10 " ><b class="blue tdu">迎送服务</b></p>
                                <p><b>客户前来提车方式：</b>{{$order_attr->take_way}} </p>
                                <p><b>客户车辆回程方式：</b>{{$trip_way['fangshi']}}</p>
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

                                
                                <?php if ($order_attr->agreement): ?>
                                  <p class="ifl fs14 "><i class="yuan"></i></p>
                                  <div class="ifl">
                                   <p><b>上牌名称与提车人姓名一致</b></p>
                                </div>
                              <?php else: ?>
                                <p class="ifl fs14 "><i class="yuan"></i></p>
                                <div class="ifl">
                                   <p><b>上牌名称与提车人姓名不一致，需要客户提车人配合提供的文件资料：</b></p>
                                </div>
                                <?php endif ?>
                                
                                <div class="clear"></div>
                                
                                
                                <div class="clear"></div>
                                  <?php if (!$order_attr->agreement): ?>
                                        <div style="width: 75%;margin:0 auto">
                                   <table class="tbl">
                                        <tr>
                                            <td width="160"><p class="tac fs14 weight">文件资料使用场合</p></td>
                                            <td width="178"><p class="tac fs14 weight">文件资料名称</p></td>
                                            <td width="140"><p class="tac fs14 weight">数量</p></td>
                                            <td width="180"><p class="tac fs14 weight">选择</p></td>
                                        </tr> 
                                        <tr>
                                            <td width="160"><p class="tac fs14 ">投保(含交强险）</p></td>
                                            <td colspan="3" class="nopadding ">
                                               <table class="tbl2" width="100%">
                                                    <tbody>
                                                    <?php foreach ($toubao_file as $key => $value): ?>
                                                      <?php if($value['cate']=="投保") continue; ?>
                                                      <tr>
                                                        <td width="189" class="bottomtborder ">
                                                            <p class="tac fs14">{{$value['title']}}</p>
                                                            <input type="hidden" name="baoxian[yj][{{$key}}]" value="{{$value['original']}}">
                                                        </td>
                                                        <td width="150" class="bottomtborder ">
                                                            <p class="tac fs14">
                                                            <?php if ($value['original']): ?>
                                                              √
                                                              <input type="hidden" name="baoxian[sl][{{$key}}]" value="1">
                                                            <?php else: ?>
                                                              
                                                              <input type="hidden" name="baoxian[sl][{{$key}}]" value="">
                                                            <?php endif ?>
                                                            

                                                            </p>
                                                        </td>
                                                        <td class="cell tac norightborder ">
                                                           <label><input type="radio" style="width: auto" name="baoxian[k][{{$key}}]" value="{{$value['title']}}" checked=""><span class="fn">需要</span></label>
                                                           <label><input type="radio" style="width: auto" name="baoxian[k][{{$key}}]" value=""><span class="fn">不需要</span></label>
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
													
                                                     <?php foreach ($shangpai_file as $key => $value): ?>
                                                      <?php if($value['isself']) continue; ?>
                                                      <tr>
                                                        <td width="189" class="bottomtborder ">
                                                            <p class="tac fs14">{{$value['title']}}</p>
                                                            <input type="hidden" name="shangpai[yj][{{$key}}]" value="{{$value['original']}}">
                                                        </td>
                                                        <td width="150" class="bottomtborder ">
                                                            <p class="tac fs14">
                                                            <?php if ($value['original']): ?>
                                                              √
                                                              <input type="hidden" name="shangpai[sl][{{$key}}]" value="1">
                                                            <?php else: ?>
                                                              
                                                              <input type="hidden" name="shangpai[sl][{{$key}}]" value="">
                                                            <?php endif ?>
                                                            

                                                            </p>
                                                        </td>
                                                        <td class="cell tac norightborder ">
                                                           <label><input type="radio" style="width: auto" name="shangpai[k][{{$key}}]" value="{{$value['title']}}" checked><span class="fn">需要</span></label>
                                                           <label><input type="radio" style="width: auto" name="shangpai[k][{{$key}}]" value=""><span class="fn">不需要</span></label>
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
                                                    <?php foreach ($linpai_file as $key => $value): ?>
                                                      <?php if($value['isself']) continue; ?>
                                                      <tr>
                                                        <td width="189" class="bottomtborder ">
                                                            <p class="tac fs14">{{$value['title']}}</p>
                                                            <input type="hidden" name="linpai[yj][{{$key}}]" value="{{$value['original']}}">
                                                        </td>
                                                        <td width="150" class="bottomtborder ">
                                                            <p class="tac fs14">
                                                            <?php if ($value['original']): ?>
                                                              √
                                                              <input type="hidden" name="linpai[sl][{{$key}}]" value="1">
                                                            <?php else: ?>
                                                              
                                                              <input type="hidden" name="linpai[sl][{{$key}}]" value="">
                                                            <?php endif ?>
                                                            

                                                            </p>
                                                        </td>
                                                        <td class="cell tac norightborder ">
                                                           <label><input type="radio" style="width: auto" name="linpai[k][{{$key}}]" value="{{$value['title']}}" checked><span class="fn">需要</span></label>
                                                           <label><input type="radio" style="width: auto" name="linpai[k][{{$key}}]" value=""><span class="fn">不需要</span></label>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </tbody></table>
                                            </td>
                                            
                                        </tr> 
                                        <tr>
                                            <td width="160"><p class="tac fs14 ">国家节能补贴</p></td>
                                            <td colspan="3" class="">
                                               <table class="tbl2" width="100%">
                                                    <tbody>


                                                    <?php foreach ($butie_file as $key => $value): ?>
                                                      <?php if($value['isself']) continue; ?>
                                                      <tr>
                                                        <td width="189" class="bottomtborder ">
                                                            <p class="tac fs14">{{$value['title']}}</p>
                                                            <input type="hidden" name="butie[yj][{{$key}}]" value="{{$value['original']}}">
                                                        </td>
                                                        <td width="150" class="bottomtborder ">
                                                            <p class="tac fs14">
                                                            <?php if ($value['original']): ?>
                                                              √
                                                              <input type="hidden" name="butie[sl][{{$key}}]" value="1">
                                                            <?php else: ?>
                                                             
                                                              <input type="hidden" name="butie[sl][{{$key}}]" value="">
                                                            <?php endif ?>
                                                            

                                                            </p>
                                                        </td>
                                                        <td class="cell tac norightborder ">
                                                           <label><input type="radio" style="width: auto" name="butie[k][{{$key}}]" value="{{$value['title']}}" checked><span class="fn">需要</span></label>
                                                           <label><input type="radio" style="width: auto" name="butie[k][{{$key}}]" value=""><span class="fn">不需要</span></label>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                    
                                                </tbody></table>
                                            </td>
                                            
                                        </tr> 
                                        <tr>
                                          <td colspan="4" style="border:0">
                                              <p class="fs14">注：文件数量标记“√”表示文件为正本或原件，不同环节可通用。</p>
                                          </td>
                                        </tr>
                                   </table>
                                </div>
                                  <?php endif ?>
                                
                                
                            </div>

                        </td>
                      </tr>
                    </table>

                    <h2 class="title">服务专员安排</h2>
                    @if(!empty($zhuanyuan) && count($zhuanyuan)>=1)
                    <select name="fuwuyuan">
                    @foreach($zhuanyuan as $k => $v)
                    <option value="{{$v['name']}}_{{$v['mobile']}}_{{$v['tel']}}">{{$v['name']}}</option>
                    @endforeach
                    </select>
                    <p class="center">
                        
                        <input type="submit" value="提交补充信息" class="btn btn-s-md btn-danger">
                     </p>
                    @else
                    	请后台添加服务专员，以供交车时客户联系，否则订单无法继续下去！
                    @endif
                   
                    </div>

                     

                </div>
            </div>
        </div>
        <input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$order['id']}}" name="id" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
        </form>
    </div>
@endsection
@section('js') 
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection