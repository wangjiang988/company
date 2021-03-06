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
  <div style="overflow: visible;" class="container content m-t-86 pos-rlt " ms-controller="custom">
       <div class="cus-step">
           <div class="line stp-2"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><span>3</span><i>3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul> 
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-2">
                    <small>等待响应</small>
                    <i></i>
                    <small class="juhuang">响应完成</small>
                </div>
            </div>
       </div>
   
        <form action="{{ url('dealer/pdisave') }}" method="post" name="item-form">
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
                                                  <b>订单时间：</b>{{ddate($created_at)}}
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
                                              <?php if ($baojia['bj_producetime']): ?>
                                              现车订单
                                            <?php else: ?>
                                              远期订单
                                            <?php endif ?>
                                            </p><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>品牌：</b>{{$brand[0]}}</p></td>
                                            <td><p><b>车系：</b>{{$brand[1]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车型规格：</b>{{$brand[2]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>座位数：</b>{{$carmodelInfo['seat_num']}}</p></td>
                                            <td><p><b>厂商指导价：</b>{{$carmodelInfo['zhidaojia']}} 元</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆类别：</b>全新中规车
                                              
                                            </p></td>
                                            <td><p><b>数量：</b>{{$cart_num}}</p></td>
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
                                            <td><p><b>归属地区：</b>{{$jxs['d_areainfo']}}</p></td>
                                            <td>
                                                <div class="psr">
                                                  <b>销售区域：</b>
                                                  <span class="">{{$shangpaiarea}}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>经销商裸车开票价格：</b>{{$baojia['bj_lckp_price']}} 元</p></td>
                                            <td><p><b>付款方式：</b>{{payType($baojia['bj_pay_type'])}}
                                              
                                            </p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>客户买车定金：</b>{{$baojia['bj_car_guarantee']}} 元</p></td>
                                            <td><p><b>我的服务费：</b>{{$baojia['bj_agent_service_price']}} 元</p></td>
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
                                        <p class="status tac status2"><b class="fs14">订单状态：{{getStatusNotice($cart_sub_status)}}    </b></p>
                                        
                                        <p class="tac fs14">离交车通知发出时限还剩<span class="juhuang"> {{$surplus_time_notice}} 天</span></p>
                                        <p class="tac fs14">离交车时限还剩<span class="juhuang"> {{$surplus_time}} 天</span></p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($buy_id)}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{ changeMobile($buyer['member_mobile'])}} </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b>{{$shangpaiarea}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b>
                                          {{carUse($buytype)}}
                                         </p>
                                        <p class="pl20 pt">
                                          <b>上牌客户身份类别： </b>
                                          <span class="fr" style="width: 165px;color:#8e8d8d;text-align: left;">{{$shenfen}}</span> 
                                          <span class="clear"></span>
                                        </p>
                                        <p class="clear"></p>
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：   </b>{{$guarantee}} 元 </p>
                                       
                                    </div>
                                </td>
                               
                            </tr> 
                          
                        </tbody>
                    </table>

                    <?php if ($baojia['bj_producetime']): ?>
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16">商品内容</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td width="33%"><p><b>基本配置：</b>同<a href="{{$barnd_info['official_url']}}" class="juhuang tdu" target="_blank">附件一</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{$carinfo['guobieTitle']}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$carinfo['paifang']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$carinfo['body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$carinfo['interior_color']}}</p></td>
                                            <td width="33%"><p><b>出厂年月：</b>{{$carinfo['chuchang']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{$carinfo['licheng']}} 公里</p></td>
                                            <td width="33%"><p><b>交车时限：  </b>{{date('Y-m-d',strtotime($jiaoche_time))}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{date('Y-m-d',strtotime($jiaoche_notice_time))}}</p></td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
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
                                                      $fee=0.00;
                                                      if(count($xzj)>0){
                                                      foreach ($xzj as $key =>$value) {  
                                                   ?> 
                                                   <tr>
                                                       <td class="tac"><?php echo $value['xzj_title']; ?></td>
                                                       <td class="tac"><?php echo $value['xzj_model']; ?></td>
                                                       <td class="tac"><?php echo $value['xzj_guide_price']; ?></td>
                                                       <td class="tac"><?php echo $value['num']; ?></td>
                                                       <td class="tac"><?php echo $value['xzj_guide_price']*$value['num']; ?></td>
                                                   </tr>
                                                   <?php  
                                                      $fee+=$value['fee'];
                                                      $count+=$value['xzj_guide_price']*$value['num'];
                                                     } 
													}
                                                      ?>
                                                     
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"><?php echo $count; ?> 元</span></h2>
                                            </td>
                                        </tr>
                                    </table>                                      
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                    <?php endif ?>
                    
                    <?php if ($baojia['bj_jc_period']): ?>
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16">商品内容</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td width="33%"><p><b>基本配置：</b>同<a href="{{$barnd_info['official_url']}}" class="juhuang tdu" target="_blank">附件一</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{$carinfo['guobieTitle']}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$carinfo['paifang']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$carinfo['body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$carinfo['interior_color']}}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{$carinfo['zhouqi']}}个月 </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>交车时限：  </b>{{date('Y年m月d日',strtotime($jiaoche_time))}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{date('Y年m月d日',strtotime($jiaoche_notice_time))}}</p></td>
                                            <td width="33%"><p><b></b></p></td>
                                        </tr>
                                      

                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                     <?php endif ?> 
                    <h2 class="title">选装精品  </h2>
                    <p class="fs14"><b>原厂选装精品折扣率：</b>{{$baojia['bj_xzj_zhekou']}} %</p>
                    <p class="fs14"><b>原厂选装精品的已定价标准和客户已选精品：(<span class="bl">{{$ycxzj_count}}</span>)<span class="sj sj2" ms-on-click="toggleList"></span></b></p>
                    <table class="tbl bltbl">
                        <tr>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号/说明</p></th>
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15 bl">客户已选件数</p></th>
                           <th><p class="fs15 ">客户选定时间</p></th>
                       </tr>
                       
                            <?php foreach ($userxzj as $key => $value): 
                                  if($value['is_yc']==0 || $value['xzj_front']==1) continue;
                            ?>
                              <tr>
                                   <td class="tac"><?php echo $value['xzj_name']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_model']; ?></td>
                                 
                                   <td class="tac"><?php echo $value['discount_price']; ?></td>
                                   
                                   <td class="tac"><?php echo $value['select_num']; ?></td>
                                   
                                   <td class="tac"><?php echo $value['createtime']; ?></td>
                                   
                               </tr>
                            <?php endforeach ?>
                            

                        
                    </table>

                    <p class="fs14"><b>非原厂选装精品的已定价标准和客户已选精品：(<span class="bl">{{$fycxzj_count}}</span>)<span class="sj sj2" ms-on-click="toggleList"></span></b></p>
                    <table class="tbl bltbl">
                        <tr>
                           <th><p class="fs15">品牌</p></th>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号/说明</p></th> 
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15 bl">客户已选件数</p></th>
                           <th><p class="fs15 ">客户选定时间</p></th>
                       </tr>
                       <?php foreach ($userxzj as $key => $value): ?>
                         <?php if($value['is_yc']==1 || $value['xzj_front']==1) continue; ?>
                         <tr>
                                  <td class="tac"><?php echo $value['xzj_brand']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_name']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_model']; ?></td>
                                 
                                   <td class="tac"><?php echo $value['discount_price']; ?></td>
                                   
                                   <td class="tac"><?php echo $value['select_num']; ?></td>
                                   
                                   <td class="tac"><?php echo $value['createtime']; ?></td>
                                   
                               </tr>
                            <?php endforeach ?>      
                    </table>

                    <h2 class="title">车辆首年商业保险</h2>

                     <?php if ($baojia['bj_baoxian'] && $baojia['bj_bx_select']): ?>
                          <?php if ($baoxianname['bx_is_quanguo'] || $islocal): ?>
                            <p class="fs14"><b>客户是否经销商处购买商业保险：</b>确定购买</p>
                            <p class="fs14"><b>提供商业保险的保险公司：</b>{{$baoxianname['bx_title']}}</p>
                          <?php endif ?>
                        
                      <?php elseif($baojia['bj_bx_select']): ?>
                        <?php if ($baoxianname['bx_is_quanguo'] || $islocal): ?>
                            <p class="fs14"><b>客户是否经销商处购买商业保险：</b>待定</p>
                          <?php else: ?>
                            <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                        <?php endif ?>
                        
                      <?php else: ?>
                        <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                    <?php endif ?>
                    
                    <?php if ($baojia['bj_bx_select']): ?>
                      <p class="fs14">商业保险的首年保费基准：</p>
                      <table class="tbl">
                        <tbody><tr>
                            <td width="50" valign="top">
                                <table width="175%" height="100%" style="margin-left: -10px">
                                    <tbody><tr>
                                        <td style="border:0;height: 30px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;"><b>类别</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;" valign="middle">
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
                                        <th class="" width="140">保费原价(元)</th>
                                        <th class="norightborder">折扣率{{$baojia['bj_xzj_zhekou']}}%折后价(元)</th>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14 normal">机动车损失险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class="tac">{{$chesun['price']}}</td>
                                        <td class="norightborder tac">{{$chesun['discount_price']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14">机动车盗抢险</label></td>
                                        <td class="">按保险公司规定执行</td>
                                        <td class="tac">{{$daoqiang['price']}}</td>
                                        <td class="norightborder tac">{{$daoqiang['discount_price']}}</td>
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
                                                        <p class="tac fs14">{{$value['price']}}</p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>

                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                
                                                <?php foreach ($sanzhe as $key => $value): ?>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">{{$value['discount_price']}}</p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>
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
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14"><?php echo $value['price']; ?></p>
                                                    </td>
                                                </tr>
                                              <?php endforeach ?>
                                                
                                                
                                            </table>
                                        </td>
                                        <td class="norightborder nobottomborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($renyuan as $key => $value): ?>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14"><?php echo $value['discount_price']; ?></p>
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
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14"><?php echo $value['price']; ?></p>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                                
                                                
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <?php foreach ($boli as $key => $value): ?>
                                              <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14"><?php echo $value['discount_price']; ?></p>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                            </table>
                                        </td>
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
                                                        <p class="tal fs14">赔付额度0.5万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tal fs14">赔付额度1万元</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
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
                                                        <p class="tac fs14"><?php echo $value['price']; ?></p>
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
                                                        <p class="tac fs14"><?php echo $value['discount_price']; ?></p>
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
                                                        <p class="tac fs14">{{$chesun['bjm_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">{{$daoqiang['bjm_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">费率 {{ $bjm_sanzhe_rate}} %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">费率 {{ $bjm_renyuan_rate}} %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tac fs14">费率 {{ $bjm_huahen_rate}} %计保费</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding">
                                            <table class="tbl2" width="100%">
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">{{$chesun['bjm_discount_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">{{$daoqiang['bjm_discount_price']}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">左侧保费 x {{$baojia['bj_xzj_zhekou']}}%</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">左侧保费 x {{$baojia['bj_xzj_zhekou']}}%</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tac fs14">左侧保费 x {{$baojia['bj_xzj_zhekou']}}%</p>
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
                    <?php endif ?>
                    <p>说明：具体投保险种与保额，由客户在交车通知反馈中选择。 </p>

                    <h2 class="title">上牌（车辆注册登记）</h2>

                    <?php if ($shangpai_area==$dealer['d_shi']): ?>
                      <?php if ($baojia['bj_shangpai']): ?>
                        <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                          <p class="fs14"><b>代办上牌服务费金额：</b><?php echo $baojia['bj_shangpai_price'] ?> 元</p>
                          <p class="fs14"><b>客户自己上牌违约金赔偿：</b><?php echo $baojia['bj_license_plate_break_contract'] ?> 元</p>
                      <?php else: ?>
                        <p class="fs14"><b>是否由经销商代办上牌：</b>待定</p>
                        <p class="fs14"><b>代办上牌服务费金额：</b><?php echo $baojia['bj_shangpai_price'] ?> 元</p>
                          <p class="fs14"><b>客户自己上牌违约金赔偿：</b><?php echo $baojia['bj_license_plate_break_contract'] ?> 元</p>
                      <?php endif ?>
                        

                        <?php if ($area_xianpai): ?>
                          <p class="fs14"><b>限牌城市（{{$area_xianpai}}）客户取得牌照指标的安排： </b>已取得牌照指标  或  订车后自行取得牌照指标</p>
                        <?php endif ?>
                        

                      <?php else: ?>
                        <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>
                    <?php endif ?>
                    
                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <?php 
                          switch ($baojia['bj_linpai']) {
                            case '0':
                              ?>
                                <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>不代办</p>
                              <?php
                              break;
                            
                            case '1':
                              ?>
                              <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>确认代办</p>
                              <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b><?php echo $baojia['bj_linpai_price'] ?>元</p>
                              <?php
                              break;
                            case '2':
                              ?>
                              <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>待定</p>
                              <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b><?php echo $baojia['bj_linpai_price'] ?> 元</p>
                              <?php
                              break;
                          }
                       ?>
                    
                    <h2 class="title">其他收费</h2>
                    <div style="width: 70%;margin:0 auto">
                        <table class="tbl">
                            <tbody><tr>
                                <th><label class="fs14 weight">费用名称</label></th>
                                <th><label class="fs14 weight">金额</label></th>
                            </tr>
                            @foreach($other_price as $key =>$value)
                            <?php if ($value<=0) continue; ?>
                              
                        <tr>
                            <td><p class="fs14 tac">{{ $key }}</p></td>
                            <td><p class="fs14 tac">{{ $value }} 元</p></td>
                        </tr>
                        @endforeach
                        </tbody></table>
                    </div>

                    <h2 class="title">交车有关事宜</h2>
                    <p class="fs14"><b>交车移交客户的文件资料：</b>
                    {{$wenjian}}
                    </p>
                    <p class="fs14"><b>交车移交客户的随车工具：</b>
                      {{$gongju}}
                    </p>
                    <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
                    <p class="fs14">信用卡: {{$more['ka']['xyk']}}</p>
                    <p class="fs14">借记卡: {{$more['ka']['jjk']}}</p>


                    <h2 class="title">补贴</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <?php if ($baojia['bj_butie']): ?>
                      <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b><?php echo $baojia['bj_butie'] ?> 元</p>
                        <p class="fs14"><b> 办理流程和时限：</b>

                          <?php 
                                        if (isset($more['butie'])) {
                                            if (is_array($more['butie'])) {
                                                echo implode($more['butie']);
                                            }else{
                                                echo $more['butie'];
                                            }
                                        }

                                     ?>
                        </p>
                      </div>
                    <?php endif ?>
                    <div class="clear"></div>
                    <?php if ($baojia['bj_zf_butie']): ?>
                      <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                    <?php endif ?>
                    <?php if ($baojia['bj_cj_butie']): ?>
                      <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                    <?php endif ?>
                    <div class="clear"></div>

                    <table class="tbl">
                      <tr>
                        <th class="tal juhuang">订单信息</th>
                      </tr>
                      <tr>
                        <td>
                            <div style="width: 55%;margin:0 auto">
                               <table class="tbl">
                                  <tbody>
                          
                                      <tr>
                                          <td><p class="tal fs14 weight">车辆识别代号（VIN码）</p></td>
                                          <td width="300">
                                              <span></span>
                                              <textarea style="width:240px;" name="vin" id="" cols="30" rows="1"></textarea>
                                              <span class="edit show" ms-on-click="edit"></span>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">发动机号</p></td>
                                          <td width="300">
                                              <span></span>
                                              <textarea  style="width:240px;" name="engine_no" id="" cols="30" rows="1"></textarea>
                                              <span class="edit show" ms-on-click="edit"></span>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">实车出厂年月</p></td>
                                          <td width="300">
                                              <span>{{$carinfo['chuchang']}}</span>
                                              <input onfocus="WdatePicker({dateFmt:'yyyy-MM-dd' });" style="width:240px;display: none;text-align: left;" name="production_date" id="" value="{{$carinfo['chuchang']}}" />
                                              <span class="edit show" ms-on-click="edit"></span>
                                          </td>
                                      </tr> 
                                      <tr>
                                          <td><p class="tal fs14 weight">实车行驶里程（公里）</p></td>
                                          <td width="300">
                                              <span></span>
                                              <textarea style="width:240px;" name="mileage" id="" cols="30" rows="1"></textarea>
                                              <span class="edit show" ms-on-click="edit"></span>
                                          </td>
                                      </tr> 
                                  </tbody>
                               </table>
                            </div>

                            <hr class="dashed">
                            <p class="m-t-10 " style="width: 96%;margin:0 auto;"><b>可交车时间</b>（请选择至少1个可交车日期，建议多提供几个供客户挑选）</p>
                            <ul class="pdi-time">
                            
                               <input type="hidden" name="pdi_date_dealer" id="pdi_date_dealer">
                               <?php foreach ($dates as $key => $value): ?>
                                 <li >
                                 <span ms-on-click="showSJD" class="txt-w">{{ddate($value,4)}}</span>
                                 <div class="split-white"></div>
                                 <dl class="sjd">
                                   <dd class="first" ms-on-click="hideSJD({{$key}},'{{$value}}')">
                                     <label>
                                        <input type="radio" ms-attr-name="pditime_<!--{{$key}}-->">
                                        <span class="txt-span">上午</span>
                                        <div class="clear"></div>
                                     </label>
                                   </dd>
                                   <dd ms-on-click="hideSJD({{$key}},'{{$value}}')">
                                     <label>
                                        <input type="radio" ms-attr-name="pditime_<!--{{$key}}-->">
                                        <span class="txt-span">下午</span>
                                        <div class="clear"></div>
                                     </label>
                                   </dd>
                                   <dd ms-on-click="hideSJD({{$key}},'{{$value}}')" class="last">
                                     <label>
                                        <input type="radio" ms-attr-name="pditime_<!--{{$key}}-->">
                                        <span class="txt-span">上午/下午</span>
                                        <div class="clear"></div>
                                     </label>
                                   </dd>
                                   <div class="clear"></div>
                                 </dl>
                               </li>
                               <?php endforeach ?>
                               
                               <div class="clear"></div>
                            </ul>

                            <dl class="hasselect">
                              <dt><span>已选日期：</span></dt>
                            </dl>
                            <div class="clear"></div>
                            <hr class="dashed">
                            <p class="ml20 m-t-10"><b>免费礼品或服务</b>  </p>
                            <div style="width: 75%;margin:0 auto">

                               <table class="tbl">
                                  <tbody> 
                     @if(count($zengpin)>0)     
                     @foreach($zengpin as $key =>$value)
                       <?php if ($value['num']<1) continue; ?>
                        <tr>
                            <td><p class="fs14 tac">{{ $value['title']}}</p></td>
                            <td><p class="fs14 tac">{{ $value['num']}}</p></td>
                            <td><p class="fs14 tac">
                            @if($value['is_install'])
                                已安装
                            @else
                                未安装
                            @endif
                            </p></td>
                            <td><p class="fs14 tac"><input type="text" name="zengpin[{{$value['id']}}]" value="{{ $value['beizhu']}}"></p></td>
                        </tr>
                       @endforeach
                      @endif
                       <input type="hidden" name="bj_id" value="{{$bj_id}}"> 
                                     
                                  </tbody>
                               </table>
                            </div>

                            <hr class="dashed">
                            <p class="ml20 m-t-10"><b>需要客户配合提供的文件资料   </b>  </p>

                            <div style="width: 75%;margin:0 auto">
                               <table class="tbl">
                                    <?php foreach ($files as $key => $value): ?>
                                        <tr>
                                        <td width="160"><p class="tac fs14 ">{{$value['catename']}}</p></td>
                                        <td colspan="3" class="nopadding">
                                           <table class="tbl2" width="100%">
                                                <?php 
                                                foreach ($value['filename'] as $k => $v): 
                                                  ?>
                                                 <tr>
                                                 <td width="200" class="bottomtborder ">{{$v['title']}}
                                                  <input type="hidden" name="file[{{$key}}][cate][{{$k}}]" value="{{$value['catename']}}">
                                                  <input type="hidden" name="file[{{$key}}][cate_id][{{$k}}]" value="{{$v['cate_id']}}">
                                                  <input type="hidden" name="file[{{$key}}][yj][{{$k}}]" value="{{$v['original']}}">
                                                  <input type="hidden" name="file[{{$key}}][isself][{{$k}}]" value="{{$v['isself']}}">
                                                  <input type="hidden" name="file[{{$key}}][file_id][{{$k}}]" value="{{$v['file_id']}}">
                                                 </td>
                                                 <td width="150" class="bottomtborder ">
                                                  <p class="tac fs14 ">
                                                  <?php if ($v['original']==1): ?>
                                                    √<input type="hidden" name="file[{{$key}}][sl][{{$k}}]" value="1">
                                                  <?php else: ?>
                                                    <input type="text" name="file[{{$key}}][sl][{{$k}}]" value="">
                                                  <?php endif ?>
                                                  </p>
                                                 </td>
                                                 <td class="cell tac norightborder"><input type="radio"  checked="" style="width: auto" name="file[{{$key}}][title][{{$k}}]" value="{{$v['title']}}"><span class="fn">需要</span> <input type="radio"  style="width: auto" name="file[{{$key}}][title][{{$k}}]" value=""><span class="fn">不需要</span></td>
                                               </tr>
                                               <?php endforeach ?>
                                                
                                            </table>
                                        </td>  
                                    </tr>
                                    <?php endforeach ?>
                                      
                               </table>
                               <p class="fs14">注：文件数量标记“√”表示文件为正本或原件，不同环节可通用。</p>
                            </div>


                        </td>
                      </tr>
                    </table>
                    
                     <table class="tbl2" width="73%" style="margin:0 auto">
                       <tr>
                         <td width="50%" class="tac">
                         <input type="button" value="发出交车邀请" class="btn btn-s-md btn-danger fs16" ms-on-click="send_invite">
                            {{--<a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger fs16">发出交车邀请</a>--}}
                            <p class="fs14 center "><span><input type="checkbox" name="" id=""></span><small class="fn">预备移交的车辆和文件已检查完毕，确认符合上述约定内容。</small></p>
                         </td>
                         
                         <td width="50%" class="tac">
                            <a href="javascript:;"  ms-on-click="modify" class="btn btn-s-md btn-danger fs16">修改交车车辆</a>
                            <p class="fs14 center "><span class="xing">*</span><small>点此按钮将导致客户获得歉意金等补偿！</small></p>

                         </td>
                       </tr>
                     </table>

                </div>
            </div>
        </div>
          <input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$id}}" name="id" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        <div class="wapper modifydiv hide">
        <form action="{{ url('dealer/pdisave') }}" method="post" name="item-form-edit">
        <input type="hidden" name="currentstatus" value="{{$cart_sub_status}}">
    		<input type="hidden" name="origin_body_color" value="{{$body_color}}">
    		<input type="hidden" name="origin_interior_color" value="{{$interior_color}}">
    		<input type="hidden" name="origin_bj_producetime" value="{{$baojia['bj_producetime']}}">
    		<input type="hidden" name="origin_licheng" value="{{$bj_licheng}}">
    		<input type="hidden" name="origin_bj_jc_period" value="{{$bj_jc_period}}">
    		<input type="hidden" name="origin_paifang" value="{{$paifangTitle}}">

                        <p class="fs14 m-t-10"><b>请选择：</b></p>
                        <table class="tbl2">
                          <tr>
                            <td valign="top" width="20" class="nopadding">
                                <input type="radio" class="jiaocheinput" name="jiaoche" id="" value="2">
                            </td>
                            <td class="nopadding"><p class="fs14">无法交车，终止订单。</p></td>
                          </tr>
                          <tr>
                            <td valign="top" width="20" class="nopadding">
                                <input class="jiaocheinput" checked type="radio" name="jiaoche" id="" value="1">
                            </td>
                            <td class="nopadding">

                                <div >
                                    <p class="fs14 ">部分内容修改后（如客户同意）可继续订单。</p>
                                    <p class="fs14">
                                      <span>如与客户沟通协商修改事宜，需客户联系方式：</span>
                                      <a ms-on-click="showPhone" href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger sure bt">点击查看</a>
                                      <span class="juhuang hide">13477990215 <i class="wanner"></i></span>
                                    </p>
                                    <p class="fs14">请提交修改内容：</p>
                                    <span class="fl spantitle">车身颜色：</span>
                                    <div class="btn-group m-r fl pdi-drop">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$body_color}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="{{$body_color}}" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="body_color_modify"  value="{{$body_color}}"/>
			                         @foreach($carmodelInfo['body_color'] as $key=>$v)
			                            <li ms-on-click="selectTimeWithVal('{{$v}}')"
			                 
			                                <?php if ($body_color==$v): ?>
			                                    class="active"
			                                <?php endif ?>
			                            ><a><span>{{ $v }}</span></a></li>
			                        </li>
			                        @endforeach
                                         
                                      </ul>
                                    </div>
                                    <div class="clear m-t-10"></div>
                                   
                                    <span class="fl spantitle">内饰颜色：</span>
                                    <div class="btn-group m-r fl pdi-drop">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$interior_color}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="{{$interior_color}}" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="interior_color_modify" value="{{$interior_color}}"/>
                                           @foreach($carmodelInfo['interior_color'] as $key=>$v)
					                            <li ms-on-click="selectTimeWithVal('{{$v}}')"
					                 
					                                <?php if ($interior_color==$v): ?>
					                                    class="active"
					                                <?php endif ?>
					                            ><a><span>{{ $v }}</span></a></li>
					                        </li>
					                        @endforeach
                                      </ul>
                                    </div>
                                    <div class="clear m-t-10"></div>
									<?php if(!empty($baojia['bj_producetime'])){
										$arr = explode("-",$baojia['bj_producetime']);
										$produceYear = $arr[0];
										$produceMonth =$arr[1];
										$currentYear = date("Y");
										$currentYearArray = array($currentYear-1,$currentYear,$currentYear+1);
									}
									?>
                                  <?php if ($baojia['bj_producetime']): ?>
                                    <span class="fl spantitle">出厂年月：</span>
                                    <div class="btn-group m-r fl ">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span><?php echo $produceYear;?></span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="<?php echo $produceYear;?>" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="bj_producetime_year_modify" value="{{$produceYear}}"/>
                                          @foreach($currentYearArray as $key=>$v)
					                            <li ms-on-click="selectTimeWithVal('{{$v}}')"
					                 
					                                <?php if ($produceYear==$v): ?>
					                                    class="active"
					                                <?php endif ?>
					                            ><a><span>{{ $v }}</span></a></li>
					                        </li>
					                        @endforeach
                                      </ul>
                                    </div>
                                    <div class="btn-group m-r fl ml10">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$produceMonth}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="{{$produceMonth}}" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="bi_producetime_month_modify" value="{{$produceMonth}}"/>
                                          <?php for($i=1;$i<=12;$i++){?>
                                          <li ms-on-click="selectTime" <?php echo $i==$produceMonth?" class='active' ":"" ?>><a><span>{{$i}}</span></a></li>
                                          <?php }?>
                                      </ul>
                                    </div>
                                    <div class="clear m-t-10"></div>
                                  <?php endif ?>
                                    <span class="fl spantitle">行驶里程：</span>
                                    <div class="btn-group m-r fl ">
                                    <span><input type="text" name="bj_licheng_modify" value="{{$bj_licheng}}"/></span>
                                     
                                    </div>
                                    <span class="fl spantitle ml10">公里</span>
                                    <div class="clear m-t-10"></div>
                                    <?php if ($baojia['bj_jc_period']): ?>
                                    <span class="fl spantitle">交车周期：</span>
                                    <div class="btn-group m-r fl ">
                                      <span><input type="text" name="bj_jc_period_modify" value="{{$bj_jc_period}}"/></span>
                                    </div>
                                    <span class="fl spantitle ml10">个月</span>
                                    <div class="clear m-t-10"></div>
                                    <?php endif ?>
                                    <span class="fl spantitle">排放标准： </span>
                                    <div class="btn-group m-r fl ">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$paifangTitle}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="{{$paifangTitle}}" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="paifang_modify" value="{{$paifangTitle}}"/>
                                          @foreach($att as $a=>$v)
				                            @if($v['key_type']=='biaozhun')
				                                <li  ms-on-click="selectTime"
				                                    <?php if ($paifangbianzhun==$v['key_value']): ?>
				                                        class="active"
				                                    <?php endif ?>
				                                ><a><span>{{ $v['key_name'] }}</span></a></li>
				                            @endif
				
				                        @endforeach
                                      </ul>
                                    </div> 
                                    <div class="clear m-t-10"></div>

                                </div> 

                            </td>
                          </tr>
                        </table>

                        <p class="fs14 ml20">已装原厂选装精品：</p>

                        <table class="tbl bgtbl ml20">
                            <tr>
                                <th>名称</th>
                                <th>型号/说明</th>
                                <th>厂商指导价</th>  
                                <th width="108">数量</th>
                                <th>附加价值</th>
                            </tr>
                            <?php if(count($xzj)>0):?>
                            <?php foreach($xzj as $kk=>$vv):?>
                                   <?php if($vv['num']<1) continue; ?>              
                            <tr data-id="3">
                                <td>
                                <input type="hidden" name="xzjModelTitle[{{$vv['xzj_id']}}]" value="{{$vv['xzj_title']}}">
                                <input type="hidden" name="xzjModelModel[{{$vv['xzj_id']}}]" value="{{$vv['xzj_model']}}">
                                <input type="hidden" name="xzjModelGuidePrice[{{$vv['xzj_id']}}]" value="{{$vv['xzj_guide_price']}}">
                                <input type="hidden" name="xzjModelFee[{{$vv['xzj_id']}}]" value="{{$vv['fee']}}">
                                <input type="hidden" name="xzjModelPrice[{{$vv['xzj_id']}}]" value=""> 
                                <input type="hidden" name="xzjModelBeizhu[{{$vv['xzj_id']}}]" value="{{isset($vv['beizhu'])?$vv['beizhu']:''}}">
                                {{$vv['xzj_title']}}</td>
                                <td class="tac">{{$vv['xzj_model']}}</td>
                                <td class="tac">{{$vv['xzj_guide_price']}}</td>
                                <td>
                                <input type="hidden"  value="{{$vv['num']}}" name="origin_ycxzj[{{$vv['xzj_id']}}]">
                                    <div class="xuan">
                                        <div class="x-w"> 
                                            <a ms-click="prev" class="prev">-</a>
                                            <input type="text" readonly value="{{$vv['num']}}" class="input" name="ycxzj[{{$vv['xzj_id']}}]">
                                            <a ms-click="next({{$vv['num']}})" class="next">+</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="tac">{{$vv['xzj_guide_price']*$vv['num']}}</td>
                            </tr>
                            <?php endforeach;?>
                           <?php endif;?>
                        </table>
                        <p class="ml20">
                            <small class="wp45 fr tar di mr150"><span>合计价值：<label></label></span></small>
                            <input type="hidden" name="price">
                        </p>

                        <p class="fs14 ml20">免费礼品和服务：</p>
                        <table class="tbl bgtbl ml20 bgtbl-mini">
                            <tr>
                                <th>名称</th>
                                <th>数量</th>
                                <th>状态</th>
                            </tr>
                            @if(count($zengpin)>0)
                            @foreach($zengpin as $key =>$value)
                            <?php if ($value['num']<1) continue; ?>
                            
                            <tr data-id="3">
                            <input type="hidden" name="zengpinModelTitle[{{$value['id']}}]" value="{{$value['title']}}">
                            <input type="hidden" name="zengpinModeInstall[{{$value['id']}}]" value="{{$value['is_install']}}">
                            <input type="hidden" name="zengpinModelbeizhu[{{$value['id']}}]" value="{{$value['beizhu']}}">
                                <td>{{ $value['title']}}</td>
                                <td align="center">
                                <input type="hidden"  value="{{ $value['num']}}"  name="origin_zengping[{{$value['id']}}]">
                                    <div class="xuan">
                                        <div class="x-w"> 
                                            <a ms-click="prev2" class="prev">-</a>
                                            <input type="text" readonly value="{{ $value['num']}}" class="input" name="zengping[{{$value['id']}}]">
                                            <a ms-click="next2({{ $value['num']}})" class="next">+</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="tac">
                                  <p class="fs14">{{getInstall($value['is_install'])}}
                                  
                                  </p>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            
                        </table>
                        <p class="tac">
                        <input type="button" value="提交修改" class="btn btn-s-md btn-danger fs16" ms-on-click="send_edit_before_invite">
                          
                        </p>
                        <p class="tac">
                          <input type="checkbox" name="" id=""><span class="fn fs14">同意支付歉意金和客户买车担保金利息赔偿。</span>
                        </p>
        	<input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$id}}" name="id" >
            <input type="hidden" value="1" name="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        </div>
        	<!-- //客户修改了选装件 -->
        	@if($xzj_status==1)
        	<?php $xzjFlag = true;?>
        <form name="rsync_xzj_form" method="post">
           <div id="dinggou-tip" class="popupbox" for="xuanfu-tip" >
            <div class="popup-title">确认修改选装精品</div>
            <div class="popup-wrapper">
                <div class="popup-content popup-content-large">
                    <p class="fs14 ">       
                      <b>客户对本订单的选装精品提议修改</b>
                    </p>
                    
                    <p class="fs14 ">     
                      原厂选装精品:  
                    </p>
                    <p class="fs14 ">     
                      原厂选装精品折扣率:  
                    </p>
                    <table class="tbl tblue"  style="width: 100%">
                        <tbody>
                            <tr>
                                <th><label class="fs14">名称</label></th>
                                <th><label class="fs14">型号/说明</label></th>
                                <th><label class="fs14">含安装费折<br>后总单价</label></th>
                                <th style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">单车可装件数</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tac fs14">可供件数</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </th>
                                <th style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">客户原选件数</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tac fs14">修改的新件数</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </th>
                                <th><label class="fs14">操作</label></th>
                            </tr>
                            @foreach($userXzjAllData as $k=>$v)
                            <?php if($v['is_yc'] ==0){continue;}?> 
                            <tr>
                                <td><p class="tac fs14">{{$v['xzj_name']}}</p></td>
                                <td><p class="tac fs14">{{$v['xzj_model']}}</p></td>
                                <td><p class="tac fs14">{{$v['discount_price']}}</p></td>
                                <td style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tal fs14">&nbsp;</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tal fs14">&nbsp;</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td  style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">{{$v['num']}}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tac fs14 ">
                                                         <span class="juhuang">
                                                         @if($v['xzj_modify']!=$v['num'])
                                                         {{$v['xzj_modify']}}
                                                         @else
                                                         	&nbsp;
                                                         @endif
                                                         
                                                         </span>
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </td>
                               
                                <td class="cell">
                                    <p class="tac fs14">
                                    	@if($v['xzj_modify']>=$v['num'])
                                    		{{$v['createtime']}}
                                    		<br>已选
                                    		<input name="xzj[{{$v['id']}}]" class="radio hide" type="radio" value='2' checked>
                                    	@else
                                    		<?php $xzjFlag = false;?>
                                    		<input name="xzj[{{$v['id']}}]" class="radio" type="radio" value='2' checked><span>同意</span>
                                        	<input name="xzj[{{$v['id']}}]" class="radio" type="radio" value='3'><span>不同意</span>
                                    	@endif
                                        
                                    </p>
                                </td>
                            </tr> 
							@endforeach
                           
                            

                           
                        </tbody>
                    </table>
                    <p class="fs14 ">     
                      原厂选装精品折扣率:  
                    </p>
                    <table class="tbl tblue">
                        <tbody>
                            <tr>
                                <th><label class="fs14">品牌</label></th>
                                <th><label class="fs14">名称</label></th>
                                <th><label class="fs14">型号/说明</label></th>
                                <th><label class="fs14">含安装费折<br>后总单价</label></th>
                                <th style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">单车可装件数</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tac fs14">可供件数</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </th>
                                <th style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">客户原选件数</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tac fs14">修改的新件数</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </th>
                                <th><label class="fs14">操作</label></th>
                            </tr> 
                            @foreach($userXzjAllData as $k=>$v)
                            <?php if($v['is_yc'] ==1){continue;}?>  
                            <tr>
                                <td><p class="tac fs14">{{$v['xzj_brand']}}</p></td>
                                <td><p class="tac fs14">{{$v['xzj_name']}}</p></td>
                                <td><p class="tac fs14">{{$v['xzj_model']}}</p></td>
                                <td><p class="tac fs14">{{$v['discount_price']}}</p></td>
                                <td style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tal fs14">&nbsp;</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tal fs14">&nbsp;</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td  style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">{{$v['num']}}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="tac fs14 ">
                                                        @if($v['xzj_modify']!=$v['num'])
                                                         	{{$v['xzj_modify']}}
                                                        @else
                                                        	&nbsp;
                                                        @endif
                                                        
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </td>
                               
                                <td class="cell">
                                    <p class="tac fs12">
                                        @if($v['xzj_modify']>=$v['num'])
                                    		{{$v['createtime']}}
                                    		<br>已选
                                    		<input name="xzj[{{$v['id']}}]" class="radio hide" type="radio" value='2' checked>
                                    	@else
                                    		<?php $xzjFlag = false;?>
                                    		<input name="xzj[{{$v['id']}}]" class="radio" type="radio" value='2' checked><span>同意</span>
                                        	<input name="xzj[{{$v['id']}}]" class="radio" type="radio" value='3'><span>不同意</span>
                                    	@endif
                                    </p>
                                </td>
                            </tr>
							@endforeach
                            

                            

                           
                        </tbody>
                    </table>
                    <div class="times times2 tal">
                        回复倒计时：
                        <p class="tac fs18 mt10" style="display:inline-block;">
                            <span class="juhuang countdown"><span>X</span>天<span>X</span>小时<span>X</span>分<span>X</span>秒</span>
                        </p>
                    </div>
                    <p class="fs12">
                      温馨提示：如不同意，请与客户电话沟通后确定提交。如超时未提交，平台默认同意修改。
                    </p>

                </div>
                <div class="popup-control">
                @if($xzjFlag === false)
                    <a ms-on-click="agreeOrder" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                @else
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure">知道了</a>
                @endif
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div class="dinggou-fix xuanfu-tip">
            <span>确认修改选装精品</span>
            <a href="javascript:;" class="btn btn-danger fs14 btn-recover">恢复</a>
        </div>
        	<input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$id}}" name="id" >
            <input type="hidden" value="1" name="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        @endif
    </div>

    <div ms-controller="uitest" ms-ui-$opts="testui" data-id="ddd"></div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"],function(a,b,c){
            
            //这个是选装件修改后的弹窗
            <!-- //客户修改了选装件 -->
            @if($xzj_status==1)
            	a.checkxuanzhuangjian()
            @endif
            //
            
            $(".countdown").CountDown({
              startTime:'2016-3-14 16:14:35',
              endTime :'2016-3-17 13:29:25',
              timekeeping:'countdown'
            })
        });

    </script>
@endsection