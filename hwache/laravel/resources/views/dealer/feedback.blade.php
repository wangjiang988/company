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
           <div class="line stp-1"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i>2</i></li>
               <li class="third"><span>3</span><i>3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul> 
       </div>
       
   

        <div class="wapper has-min-step">
          <form action="{{ url('dealer/save_feedback') }}" method="post" name="form1">       
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
                                                  <b>订单时间：</b>{{ddate($created_at,1)}}
                                                  <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                                     <b>更多</b>
                                                  </span>
                                                  <p class="tm tm-long">
                                                  @if(count($cart_log)>0)     
						                          	@foreach($cart_log as $k =>$v )
												     <span>{{$v['msg_time']}}：{{$v['time']}}</span>
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
                                            <td><p><b>类别：</b>
                                            全新中规车
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
                                                <span class="">{{$shangpai_area}}</span>

                                              </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>经销商裸车开票价格：</b>{{$baojia['bj_lckp_price']}} 元</p></td>
                                            <td><p><b>付款方式：</b>
                                            <?php if ($baojia['bj_pay_type']==1): ?>
                                              全款
                                              <?php else: ?>
                                              贷款 
                                            <?php endif ?>
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
                                                  <p class="tm detail">歉意金：499,00元（2015-10-26   10：57 ：57）</p>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>                                    
                                </td>
                                <td>
                                    <div class="times">
                                        <p class="status tac"><b>订单状态：{{getStatusNotice($cart_sub_status)}}</b></p>
                                        <p class="tac fs15 m-t-10">等待反馈剩余时间<span class="juhuang"><span class="juhuang countdown"><span>0</span>小时<span>0</span>分<span>0</span>秒</span></span></p>
                                        <hr class="dashed mt69">
                                        <p class="pl20"><b>客户会员号： </b>{{formatNum($buy_id,1)}} </p>
                                        <p class="pl20"><b>客户车辆用途： </b>
                                            {{carUse($buytype)}}
                                         </p>
                                        <p class="pl20"><b>客户承诺上牌地区： </b>{{$shangpai_area}} </p>
                                        <p class="pl20 ">
                                          <b>上牌客户身份类别： </b>
                                          <span class="fr" style="width: 166px">{{$shenfen}}</span> 
                                          <label class="clear"></label>
                                        </p>
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
                                            <td width="33%"><p><b>基本配置：</b>同<a href="{{$barnd_info['official_url']}}" class="juhuang tdu">附件一</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>
                                              {{$guobieTitle}}
                                            </p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$paifangTitle}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$body_color}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$interior_color}}</p></td>
                                        <td width="33%"><p><b>出厂年月：</b>{{$barnd_info['chuchang_time']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{$baojia['bj_licheng']}} 公里</p></td>
                                            <td width="33%"><p><b></b></p></td>
                                            <td width="33%"><p><b></b></p></td>
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
                                                       <th><p class="fs15">型号</p></th>
                                                       <th><p class="fs15">厂家指导价</p></th>
                                                       <th><p class="fs15">数量</p></th>
                                                       <th><p class="fs15">附加价值</p></th>
                                                   </tr>
                                                   <?php 
                                                      $count=0.00;
                                                      $fee=0.00;
                                                      foreach ($xzj as $key =>$value) {
                                                          if(!$value['xzj_yc']) continue;
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
                                            <td width="33%"><p><b>基本配置：</b>同<a href="{{$barnd_info['official_url']}}" class="juhuang tdu">附件一</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{$guobieTitle}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$paifangTitle}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$body_color}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$interior_color}}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{$baojia['bj_jc_period']}}个月(交车时限：{{$new_date}})</p></td>
                                        </tr>
                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                    <?php endif ?>
                    

                    <h2 class="title">原厂选装精品  </h2>
                    <p class="fs14"><b>原厂选装精品折扣率：</b>{{$baojia['bj_xzj_zhekou']}} %</p>
                    <p class="fs14"><b>原厂选装精品的已定价标准：</b></p>
                    <table class="tbl">
                        <tr>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号</p></th>
                           <th><p class="fs15">厂家指导价</p></th>
                           <th><p class="fs15">折后单价</p></th>
                           <th><p class="fs15">安装费用</p></th>
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15">单车可装件数</p></th>
                           <th><p class="fs15">可供件数</p></th>
                       </tr>
                       <?php if ($baojia['bj_producetime']): ?>
                            <?php foreach ($xzj_daili as $key => $value): 
                                  if(!$value['xzj_yc']) continue;
                            ?>
                              <tr>
                                   <td class="tac"><?php echo $value['xzj_title']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_model']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_guide_price']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_guide_price']*$baojia['bj_xzj_zhekou']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_fee']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_fee']+$value['xzj_guide_price']*$baojia['bj_xzj_zhekou']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_max_num']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_has_num']; ?></td>
                               </tr>
                            <?php endforeach ?>
                            

                        <?php else: ?>
                              <?php foreach ($xzj as $key => $value): 
                                  if($value['xzj_yc']==0) continue;
                            ?>
                              <tr>
                                   <td class="tac"><?php echo $value['xzj_title']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_model']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_guide_price']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_guide_price']*$baojia['bj_xzj_zhekou']; ?></td>
                                   <td class="tac"><?php echo $value['fee']; ?></td>
                                   <td class="tac"><?php echo $value['fee']+$value['xzj_guide_price']*$baojia['bj_xzj_zhekou']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_max_num']; ?></td>
                                   <td class="tac"><?php echo $value['num']; ?></td>
                               </tr>
                            <?php endforeach ?>

                       <?php endif ?>
                       
                       
                    </table>

                    <h2 class="title">车辆首年商业保险</h2>
                    <?php if ($baojia['bj_baoxian'] && $baojia['bj_bx_select']): ?>
                        <p class="fs14"><b>客户是否经销商处购买商业保险：</b>确定购买</p>
                        <p class="fs14"><b>提供商业保险的保险公司：</b>{{$baoxian_name}}</p>
                      <?php elseif($baojia['bj_baoxian']): ?>
                        <p class="fs14"><b>客户是否经销商处购买商业保险：</b>待定</p>
                      <?php else: ?>
                        <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                    <?php endif ?>
                    <?php if ($baojia['bj_baoxian']): ?>
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
                    <p>说明：具体投保险种与保额，由客户在交车通知反馈中选择。 </p>
                    <?php endif ?>
                    

                    <h2 class="title">上牌（车辆注册登记）服务</h2>
                    <?php if ($shangpai_area==$dealer['d_shi']): ?>
                        <?php 
                          switch ($baojia['bj_shangpai']) {
                            case '1':
                              ?>
                          <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                          <p class="fs14"><b>代办上牌服务费金额：</b><?php echo $baojia['bj_shangpai_price'] ?></p>
                          <p class="fs14"><b>客户自己上牌违约赔偿金额：</b><?php echo $baojia['bj_license_plate_break_contract'] ?></p>
                              <?php
                              break;
                            case '0'
                              ?>
                              <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>

                              <?php
                              break;
                            case '2':
                              
                              ?>
                              <p class="fs14"><b>代办上牌服务费金额：</b><?php echo $baojia['bj_shangpai_price'] ?></p>
                          <p class="fs14"><b>客户自己上牌违约赔偿金额：</b><?php echo $baojia['bj_license_plate_break_contract'] ?></p>
                              
                              <?php
                              break;
                          }
                         ?>

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
                            <td><p class="fs14 tac">{{ $value['title']}}</p></td>
                            <td><p class="fs14 tac">{{ $value['num']}}</p></td>
                            <td><p class="fs14 tac">
                            
                            {{getInstall($value['is_install'])}}
                            </p></td>
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
                    
                    <?php if ($baojia['bj_butie']): ?>
                      <p class="ifl fs14"><i class="yuan"></i></p>
                      <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b><?php echo $baojia['bj_butie'] ?></p>
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
                  <?php if ($baojia['bj_shangpai']<>1): ?>
                         <h2 class="title">需要经销商为客户办理的其他上牌资料（请逐项回复）</h2>
                    <?php if ($ziliao): ?>
                        <?php foreach ($ziliao as $key => $value): ?>
                          <?php if(empty($value)) continue; ?>
                        <table class="tbl2">
                          <tr>
                              <td valign="top"><span class="tal fs14 nopadding"  >{{$value}}：</span></td>
                              <td class="cell"> 
                                  <div>
                                      <input type="hidden" name="ziliao[{{$key}}][title]" value="{{$value}}">
                                      <input name="ziliao[{{$key}}][ok]"  value="1" class="radio" type="radio" />
                                      <span>可以办理， 费用<input type="text" class="baoxianinput juhuang shot" name="ziliao[{{$key}}][fee] " value="">元，办理时间<input type="text" class="baoxianinput juhuang shot" name="ziliao[{{$key}}][days]" value="">个自燃日 </span>
                                  </div>
                                  <div>
                                      <input name="ziliao[{{$key}}][ok]" checked="checked"  value="0" class="radio" type="radio" />
                                      <span>请恕无法办理   </span>
                                  </div>
                              </td>
                          </tr>
                      </table>
                      <?php endforeach ?>
                    <?php endif ?>
                  <?php endif ?>     
                    
                    

                    <p class="center">
                    <input type="submit" value="提交" class="btn btn-s-md btn-danger">
                        {{--<a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger">提交</a>--}}
                        <a ms-on-click="modify" href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" style="width:80px">修改</a>
                        <p class="fs14 center ml326"><span class="xing">*</span>点此按钮将导致客户获得歉意金补偿！</p>
                    </p>
                    <div class="modifydiv hide">

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
                                <input class="jiaocheinput"  type="radio" name="jiaoche" id="" value="1">
                            </td>
                            <td class="nopadding">

                                <div >
                                    <p class="fs14 ">部分内容修改后（如客户同意）可继续订单。请提交修改内容：</p>
                                    
                                    <span class="fl spantitle">车身颜色：</span>
                                    <div class="btn-group m-r fl pdi-drop">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$body_color}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="{{$body_color}}" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="body_color" value="{{$body_color}}" />
                                          <?php foreach ($carmodelInfo['body_color'] as $key => $value): ?>
                                            <li ms-on-click="selectTime" 

                                            <?php if ($bjCarInfo['body_color']==$key): ?>
                                              class="active"
                                            <?php endif ?>
                                            ><a><span>{{$value}}</span></a></li>
                                          <?php endforeach ?>
                                          
                                          
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
                                          <input type="hidden" name="interior_color" value="{{$interior_color}}" />

                                          <?php foreach ($carmodelInfo['interior_color'] as $key => $value): ?>
                                            <li ms-on-click="selectTime" 
                                            <?php if ($bjCarInfo['interior_color']==$key): ?>
                                              class="active"
                                            <?php endif ?>
                                          
                                            ><a><span>{{$value}}</span></a></li>
                                          <?php endforeach ?>
                                          
                                      </ul>
                                    </div>
                                    <div class="clear m-t-10"></div>
                                    <?php if ($baojia['bj_producetime']): ?>
                                    <span class="fl spantitle">出厂年月：</span>
                                    <div class="btn-group m-r fl ">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$chuchang[0]}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="nian" value="{{$chuchang[0]}}" />
                                          
                                          <?php for ($i=1998; $i < 2016; $i++) { 
                                            echo '<li ms-on-click="selectTime"><a><span>'.$i.'</span></a></li>';
                                          } ?>
                                          

                                      </ul>年
                                    </div>
                                    <div class="btn-group m-r fl ml10">
                                      <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                          <span class="dropdown-label"><span>{{$chuchang[1]}}</span></span>
                                          <span class="caret"></span>
                                      </button>
                                      <ul data-def-value="" class="dropdown-menu dropdown-select2">
                                          <input type="hidden" name="yue" value="{{$chuchang[1]}}" />
                                          
                                          <?php for ($i=1; $i < 13; $i++) { 
                                            echo '<li ms-on-click="selectTime"><a><span>'.sprintf('%02d',$i).'</span></a></li>';
                                          } ?>

                                      </ul>月
                                    </div>
                                    <div class="clear m-t-10"></div>
                                    <?php endif ?>
                                    <span class="fl spantitle">行驶里程：</span>
                                    <div class="btn-group m-r fl ">
                                    <input type="text" name="licheng" value="{{$baojia['bj_licheng']}}" class="btn btn-sm btn-default dropdown-toggle">
                                      
                                      
                                    </div>
                                    <span class="fl spantitle ml10">公里</span>
                                    <div class="clear m-t-10"></div>
                                    <?php if ($baojia['bj_jc_period']): ?>
                                    <span class="fl spantitle">交车周期：</span>
                                    <div class="btn-group m-r fl ">
                                     <input type="text" name="zhouqi" value="{{$baojia['bj_jc_period']}}" class="btn btn-sm btn-default dropdown-toggle">
                                      
                                      
                                    </div>
                                    <span class="fl spantitle ml10">个月</span>
                                    <div class="clear m-t-10"></div>
                                    <?php endif ?>
                                    <span class="fl spantitle">排放标准： </span>
                                    <div class="btn-group m-r fl ">
                                    <select name="paifang" id="" class="btn btn-sm btn-default dropdown-toggle">
                                      <?php foreach ($paifang as $key => $value): ?>
                                        <option value="{{$value}}" 
                                        <?php if ($value==$paifangTitle): ?>
                                          class="active"
                                        <?php endif ?>
                                        >{{$value}}</option>
                                      <?php endforeach ?>
                                      
                                    </select>
                                      
                                     <input type="hidden" value="{{$guobieTitle}}" name="guobieTitle"> 
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
                            <?php foreach ($xzj as $key => $value): ?>
                              
                            <tr data-id="3">
                                <td class="tac"><?php echo $value['xzj_title']; ?>
                                <input type="hidden" name="xzj[{{$key}}][xzj_id]" value="{{$value['xzj_id']}}">
                                <input type="hidden" name="xzj[{{$key}}][xzj_title]" value="{{$value['xzj_title']}}">
                                <input type="hidden" name="xzj[{{$key}}][fee]" value="{{$value['fee']}}">
                                
                                <input type="hidden" name="xzj[{{$key}}][old_num]" value="{{$value['num']}}">
                                <input type="hidden" name="xzj[{{$key}}][xzj_yc]" value="1">
                                <input type="hidden" name="xzj[{{$key}}][xzj_front]" value="1">
                                <input type="hidden" name="xzj[{{$key}}][id]" value="{{$value['id']}}">
                                </td>
                                <td class="tac"><?php echo $value['xzj_model']; ?> /<?php echo $value['xzj_notice']; ?>
                                  <input type="hidden" name="xzj[{{$key}}][xzj_model]" value="{{$value['xzj_model']}}">
                                  <input type="hidden" name="xzj[{{$key}}][xzj_notice]" value="{{$value['xzj_notice']}}">
                                </td>
                                <td class="tac"><?php echo $value['xzj_guide_price']; ?><input type="hidden" name="xzj[{{$key}}][xzj_guide_price]" value="{{$value['xzj_guide_price']}}"></td>
                                <td class="tac">
                                    <div class="xuan">
                                        <div class="x-w"> 
                                            <a ms-click="prev" class="prev">-</a>
                                            <input type="text" name="xzj[{{$key}}][num]" readonly="" value="{{$value['num']}}" class="input">
                                            <a ms-click="next({{$value['num']}})" class="next">+</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="tac"></td>
                            </tr>
                           <?php endforeach ?> 
                        </table>
                        <p class="ml20">
                            <small class="wp45 fr tar di mr150"><span>合计价值：<label></label></span> 元</small>
                            <input type="hidden" name="price">
                        </p>

                        <p class="fs14 ml20">免费礼品和服务：</p>
                        <table class="tbl bgtbl ml20 bgtbl-mini">
                            <tr>
                                <th>名称</th>
                                <th>数量</th>
                                <th>状态</th>
                            </tr>
                            @foreach($zengpin as $key =>$value)
                            <tr data-id="3">
                                <td>
                                  <input type="hidden" name="zengpin[{{$key}}][id]" value="{{ $value['zengpin_id']}}">
                                  <input type="hidden" name="zengpin[{{$key}}][title]" value="{{ $value['title']}}">
                                  <input type="hidden" name="zengpin[{{$key}}][old_num]" value="{{ $value['num']}}">
                                  <input type="hidden" name="zengpin[{{$key}}][beizhu]" value="{{ $value['beizhu']}}">
                                {{ $value['title']}}</td>
                                <td align="center">
                                    <div class="xuan">
                                        <div class="x-w"> 
                                            <a ms-click="prev2" class="prev">-</a>
                                            <input type="text" name="zengpin[{{$key}}][num]"  readonly="" value="{{ $value['num']}}" class="input">
                                            <a ms-click="next2({{ $value['num']}})" class="next">+</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="tac">
                                <input type="hidden" name="zengpin[{{$key}}][is_install]" value="{{$value['is_install']}}">
                                  <p class="fs14">@if($value['is_install'])
                                已安装
                            @else
                                未安装
                            @endif</p>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        <p class="tac">
                        <input type="button" value="提交" class="btn btn-s-md btn-danger fs16" ms-on-click="send_modify_before_sure_earnest">
                          
                        </p>
                        <p class="tac">
                          <input type="checkbox" name="" id=""><span class="fn fs14">同意支付歉意金赔偿</span>
                        </p>

                    </div>
                </div>
               
            </div>
            <input type="hidden" value="{{$id}}" name="id" >
            <input type="hidden" value="{{$bj_id}}" name="bj_id" >
            <input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" name="timeout" value="{{$timeout}}"></input>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"],function(){
            $(".countdown").CountDown({
              startTime:'{{$starttime}}',
              endTime :'{{$endtime}}',
              timekeeping:'countdown',
              // callback:function(){
                // $("form[name='form1']").submit()
              // }
          })
        });
    </script>
@endsection