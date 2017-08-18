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

   
        <form action="{{url('dealer/postpdiok')}}" method="post" name="item_pdiok">
        <input type="hidden" name="order_num" value="{{ $order_num}}">
        <input type="hidden" name="id" value="{{ $order['id']}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                                       
                                            <td><p><b>座位数：</b>{{$bj['seat_num']}}</p></td>
                                            <td><p><b>厂商指导价：</b>{{$bj['zhidaojia']}} 元</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>类别：</b>
                                            {{carUse($order['buytype'])}}</p></td>
                                            <td><p><b>数量：</b>{{$order['cart_num']}}</p></td>
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
                                            <td><p><b>经销商裸车开票价格：</b>{{$bj['bj_lckp_price']}} 元</p></td>
                                            <td><p><b>付款方式：</b>
                                              <?php if ($bj['bj_pay_type']==1): ?>
                                              全款
                                              <?php else: ?>
                                              贷款 
                                            <?php endif ?>
                                            </p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>客户买车定金：</b>{{$bj['bj_car_guarantee']}} 元</p></td>
                                            <td><p><b>我的服务费：</b>{{$bj['bj_agent_service_price']}} 元</p></td>
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
                                        <p class="tac fs15 m-t-10">离交车时限仅剩<span class="juhuang">{{$surplus_time}} 天</span></p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>
                                      
                                           
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($order['buy_id'])}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{ changeMobile($buyer['member_mobile'])}} </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b>{{$shangpaiarea}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b>{{carUse($order['buytype'])}}
                                          
                                         </p>
                                        <p class="pl20 pt">
                                          <b>上牌车主身份类别： </b>
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
                                            <td width="33%"><p><b>基本配置：</b><a href="{{$bj['barnd_info']['official_url']}}" class="juhuang tdu" target="_blank">参考官网参数</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{$bj['guobieTitle']}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$bj['paifangTitle']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$bj['body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$bj['interior_color']}}</p></td>
                                            <td width="33%"><p><b>出厂年月：</b>{{$bj['barnd_info']['chuchang_time']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{$bj['bj_licheng']}} 公里</p></td>
                                            <td width="33%"><p><b>交车时限：  </b>{{date('Y-m-d',strtotime($order['jiaoche_time']))}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{date('Y-m-d',strtotime($order['jiaoche_notice_time']))}}</p></td>
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
                                                   @if(!empty($xzj))
                                                   <?php 
                                                      $count=0.00;
                                                      $fee=0.00;
                                                      foreach ($xzj as $key =>$value) {
                                                          if($value['num']<1) continue;
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
                                                     } ?>
                                                    
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"><?php echo $count; ?> 元</span></h2>
                                                @endif

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
                                            <td width="33%"><p><b>基本配置：</b>同<a href="{{$barnd_info['official_url']}}" class="juhuang tdu" target="_blank">附件一</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{$bj['guobieTitle']}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$bj['paifangTitle']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$bj['body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$bj['paifangTitle']}}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{$bj['bj_jc_period']}}个月 </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>交车时限：  </b>{{date('Y-m-d',strtotime($order['jiaoche_time']))}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{date('Y-m-d',strtotime($order['jiaoche_notice_time']))}}</p></td>
                                            <td width="33%"><p><b></b></p></td>
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
                    <p class="fs14 tar pr80"><b>总计：</b>{{$yc_total}}</p>
                  

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
                    <?php if ($order['shangpai_area']==$dealer['d_shi']): ?>
                      <?php if ($bj['bj_shangpai']): ?>
                        <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                          <p class="fs14"><b>代办上牌服务费金额：</b><?php echo $bj['bj_shangpai_price'] ?> 元</p>
                          <p class="fs14"><b>客户自己上牌违约金赔偿：</b><?php echo $bj['bj_license_plate_break_contract'] ?> 元</p>
                      <?php else: ?>
                        <p class="fs14"><b>是否由经销商代办上牌：</b>待定</p>
                        <p class="fs14"><b>代办上牌服务费金额：</b><?php echo $bj['bj_shangpai_price'] ?> 元</p>
                          <p class="fs14"><b>客户自己上牌违约金赔偿：</b><?php echo $bj['bj_license_plate_break_contract'] ?> 元</p>
                      <?php endif ?>
                        

                        <?php if ($bj['area_xianpai']): ?>
                          <p class="fs14"><b>限牌城市（{{$bj['area_xianpai']}}）客户取得牌照指标的安排： </b>已取得牌照指标  或  订车后自行取得牌照指标</p>
                        <?php endif ?>
                        

                      <?php else: ?>
                        <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>
                    <?php endif ?>
                    
                    
                    
                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <?php 
                          switch ($bj['bj_linpai']) {
                            case '0':
                              ?>
                                <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>不代办</p>
                              <?php
                              break;
                            
                            case '1':
                              ?>
                              <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>确认代办</p>
                              <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b><?php echo $bj['bj_linpai_price'] ?>元</p>
                              <?php
                              break;
                            case '2':
                              ?>
                              <p class="fs14"><b>是否由经销商代办车辆临时移动牌照：</b>待定</p>
                              <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b><?php echo $bj['bj_linpai_price'] ?> 元</p>
                              <?php
                              break;
                          }
                       ?>

                    <h2 class="title">补贴</h2>
                    <?php if ($bj['bj_butie']): ?>
                      <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b><?php echo $bj['bj_butie'] ?> 元</p>
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
                    <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                    <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                    <div class="clear"></div>

                    <h2 class="title">交车有关事宜</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户计划上牌（注册登记）的详细名称：</b>{{$order['reg_name']}}</p>
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户计划委托的提车人姓名：  </b>{{$ticheren['username']}}<span class="ml20">电话：</span>{{$ticheren['mobile']}}</p>
                        @if($order_attr['agreement']==1)
                        <p class="fs14">上牌名称与提车人姓名一致。</p>
                        @else
                        <p class="fs14">上牌名称与提车人姓名不一致。</p>
                        @endif
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户前来提车方式：</b>{{$order_attr['take_way']}}</p>
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>客户提车后车辆回程方式：  </b><span class="">{{$user_choose['songche']}}</span></p>
                        <p class="fs14"><b>送车大致地址：</b>{{$order_attr['deliver_addr']}}</p>
                    </div>
                    <div class="clear"></div>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
                    	<p class="fs14">信用卡: {{$bj['more']['ka']['xyk']}}</p>
                    	<p class="fs14">借记卡: {{$bj['more']['ka']['jjk']}}</p>

                    </div>
                    <div class="clear"></div>
                    
					<h2 class="title">免费礼品或服务</h2>
                            <div style="width: 70%;margin:0 auto">
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
                                          <td><p class="tac fs14 weight"></p></td>
                                          <td width="300">
                                              <p class="tac fs14 weight"></p>
                                              <textarea style="width:240px;" name="" id="" cols="30" rows="1"></textarea>
                                          </td>
                                      </tr> 
                                       <?php endforeach ?>
                                       @endif
                                      
                                     
                                  </tbody>
                               </table>
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
                            <?php if (isset($order_attr['user_choose']['songche']) && $order_attr['user_choose']['songche']=="代驾送车"): ?>
                              <tr>
                                    <td><p class="tal fs14">代驾送车服务费</p></td>
                                    <td><p class="tal fs14">
                                    <?php 
                                    	if(isset($pdiReply['daijia']['fee'])){
                                    		echo $pdiReply['daijia']['fee'];
                                    		if($pdiReply['daijia']['pay_type']=='pay_first'){
                                    			$pay_type = "经销商处先付款";
                                    		}elseif($pdiReply['daijia']['pay_type']=='pay_wait_ok'){
                                    			$pay_type = "送车到目的时再付款";
                                    		}else{
                                    			$pay_type = '';
                                    		}
                                    	}
                                    	
                                    ?>
                                    
                                    </p></td>
                                    <td><p class="tal fs14">{{$pay_type}}</p></td>
                                </tr>
                            <?php endif ?>
                            <?php if (isset($order_attr['user_choose']['songche']) && $order_attr['user_choose']['songche']=="板车运输送车"): ?>
                            <tr>
                                <td><p class="tal fs14">板车运输送车运费</p></td>
                                <td><p class="tal fs14">
                                <?php 
                                    	if(isset($pdiReply['transport']['fee'])){
                                    		echo $pdiReply['transport']['fee'];
                                    		if($pdiReply['transport']['pay_type']=='pay_first'){
                                    			$pay_type = "经销商处先付款";
                                    		}elseif($pdiReply['transport']['pay_type']=='pay_wait_ok'){
                                    			$pay_type = "送车到目的时再付款";
                                    		}else{
                                    			$pay_type = '';
                                    		}
                                    	}
                                    	
                                    ?>
                             	</p></td>
                                <td><p class="tal fs14">{{$pay_type}}</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">板车运输送车运输险保费</p></td>
                                <td><p class="tal fs14">
                                <?php 
                                if(isset($pdiReply['transport']['bx_fee'])){
                                    		echo $pdiReply['transport']['bx_fee'];
                                }
                                ?>
                                </p></td>
                                <td><p class="tal fs14">{{$pay_type}}</p></td>
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
                                  <td colspan="3" >
                                     <table class="tbl2" width="100%">
                                     @if(count($wenjian)>0 && is_array($wenjian))
                                        <?php foreach ($wenjian as $key => $value): ?>
                                          <tr>
                                              <td width="197" >
                                                  <p class="tac fs14">{{$value->title}}</p>
                                              </td>
                                              <td width="150" >
                                                  <p class="tac fs14">{{$value->num}}</p>
                                              </td>
                                              <td width="150" class=" norightborder">
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
                                  <td colspan="3" >
                                     <table class="tbl2" width="100%">
                                     @if(count($gongju)>0 && is_array($gongju))
                                        <?php foreach ($gongju as $key => $value): ?>
                                          <tr>
                                              <td width="197" class=" ">
                                                  <p class="tac fs14">{{$value->title}}</p>
                                              </td>
                                              <td width="150" class=" ">
                                                  <p class="tac fs14">{{$value->num}}</p>
                                              </td>
                                              <td width="150" class=" norightborder">
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
                           <div style="width: 55%;margin:0 auto">
                            @if(!empty($zhuanyuan) && count($zhuanyuan)>=1)
		                    <select name="fuwuyuan" id="fuwuyuan"  ms-on-change="modifyFwzyChange">
		                    <option value="none" >请选择一个服务专员</option>
		                    @foreach($zhuanyuan as $k => $v)
		                    <option value="{{$v['name']}}_{{$v['mobile']}}_{{$v['tel']}}" >{{$v['name']}}</option>
		                    @endforeach
		                    </select>
		                    @else
		                    	请后台添加服务专员，以供交车时客户联系
		                    @endif
                         <table class="tbl">
                            <tr>
                              <td width="45%"><p class="fs14"><b>服务专员姓名</b></p></td>
                              <td><p class="fs14" id="fwzyName">
                              </p></td>
                            </tr>
                            <tr>
                              <td><p class="fs14"><b>电话</b></p></td>
                              <td><p class="fs14" id="fwzyMobile">

                            </tr>
                            <tr>
                              <td><p class="fs14"><b>备用电话</b></p></td>
                              <td><p class="fs14" id="fwzyTel">
                              </p></td>
                            </tr>
                         </table>
                         
                         
                         

                       <p class="fs16 tac">
                            
                            <input type="button" value="提交补充信息" class="btn btn-s-md btn-danger fs16" ms-on-click='send_other_info_pdiok'>
                        </p>

                      
                     
                           
                        
                    
            </div>
        </div>
        </form>
    </div>
@endsection
@section('js') 
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection