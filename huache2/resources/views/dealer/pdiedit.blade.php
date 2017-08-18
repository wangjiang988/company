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
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ url('/member')}}"><span>{{ $_SESSION['member_name'] }}</span> </a></label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ env('WWW_URL') }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
    <div style="overflow: visible;" class="container content m-t-86 pos-rlt " ms-controller="custom">
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
                                            <td colspan="2"><p><b>订单类别：</b>现车订单</p><hr class="dashed"></td>
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
                                            <td><p><b>车辆类别：</b></p></td>
                                            <td><p><b>数量：</b>{{$baojia['bj_num']}}</p></td>
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
                                                  <span class="">{{$jxs['d_shi']}}</span>
                                                  
                                                  
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
                                                  <p class="tm detail" style="width:470px">
                                                    <label>歉意金：499,00元（2015-10-26   10：57 ：57）</label>
                                                    <label>歉意金（赔偿客户）：— 499.00元（2015-10-25   10：57：57）</label>
                                                    <label>歉意金2：499.00元（2015-10-25  10：57：57）</label>
                                                    <label>客户买车担保金利息金额：723.00元（2015-10-25 ~ 2015-11-03）</label>
                                                    <label>歉意金2（赔偿客户）：— 499.00元（2015-10-25   10：57：57）</label>
                                                    <label>客户买车担保金利息赔偿：— 723.00元（2015-10-25   10：57：57）</label>
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>                                  
                                </td>
                                <td>
                                    <div class="times times2" style="height:450px">
                                        <p class="status tac status2"><b class="fs14">订单状态：{{getStatusNotice($cart_sub_status)}}   </b></p>
                                        
                                        <p class="tac fs14">离交车通知发出时限还剩<span class="juhuang">{{$surplus_time_notice}} 天</span></p>
                                        <p class="tac fs14">离交车时限还剩<span class="juhuang">{{$surplus_time}} 天</span></p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($buy_id)}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{changeMobile($buyer['member_mobile'])}} </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：</b>{{$shangpaiarea}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：</b>{{carUse($buytype)}} </p>
                                        <p class="pl20 pt">
                                          <b>上牌客户身份类别： </b>
                                          <span class="fr" style="width: 165px;color:#8e8d8d;text-align: left;">{{$shenfen}}</span> 
                                          <span class="clear"></span>
                                        </p>
                                        <p class="clear"></p>
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：</b>{{$guarantee}} 元</p>
                                       
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
                                            <td width="33%"><p><b>基本配置：</b><a href="{{$barnd_info['official_url']}}" class="juhuang tdu" target="_blank">参考官网参数</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{$guobieTitle}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$paifangTitle}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$body_color}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$interior_color}}</p></td>
                                            <td width="33%"><p><b>出厂年月：</b>{{$barnd_info['chuchang_time']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{$baojia['bj_licheng']}} 公里</p></td>
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
                                            <td width="33%"><p><b>基本配置：</b>同<a href="{{$barnd_info['official_url']}}" class="juhuang tdu" target="_blank">附件一</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{guobieTitle}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$paifangTitle}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$body_color}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$paifangTitle}}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{$bj_jc_period}}个月 </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>交车时限：  </b>{{date('Y-m-d',strtotime($jiaoche_time))}}</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>{{date('Y-m-d',strtotime($jiaoche_notice_time))}}</p></td>
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
                       @if(!empty($userxzj) && count($userxzj)>0)
                        <?php foreach ($userxzj as $key => $value): 
                                  if($value['is_yc']==0) continue;
                            ?>
                              <tr>
                                   <td class="tac"><?php echo $value['xzj_name']; ?></td>
                                   <td class="tac"><?php echo $value['xzj_model']; ?></td>
                                 
                                   <td class="tac"><?php echo $value['discount_price']; ?></td>
                                 
                                   <td class="tac"><?php echo $value['select_num']; ?></td>
                                   
                                   <td class="tac"><?php echo $value['createtime']; ?></td>
                                   
                               
                                   
                               </tr>
                            <?php endforeach ?>
                       @endif
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
                         <?php if($value['is_yc']==1) continue; ?>
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
                    <p class="fs14">商业保险的首年保费基准：{{$baojia['bj_baoxian_discount']}}%<span class="sj sj2" ms-on-click="toggleList"></span></p>


                     <?php if ($baojia['bj_bx_select']): ?>
                      <p class="fs14">商业保险的首年保费基准：<span class="sj sj2" ms-on-click="toggleList"></span></p>
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
                                                        <p class="tac fs14">费率 {{ round($bjm_sanzhe_rate,2)}} %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bottomtborder norightborder">
                                                        <p class="tac fs14">费率 {{ round($bjm_renyuan_rate,2)}} %计保费</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="nobottomborder">
                                                        <p class="tac fs14">费率 {{ round($bjm_huahen_rate,2)}} %计保费</p>
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
                          <p class="fs14"><b>限牌城市（{{$$baojia['area_xianpai']}}）客户取得牌照指标的安排： </b>已取得牌照指标  或  订车后自行取得牌照指标</p>
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
                              <p class="fs14"><b>代办临时牌照（每次）服务费金额：</b><?php echo $baojia['bj_linpai_price'] ?> 元</p>
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
                    <p class="fs14"><b>交车当场移交客户的文件资料：</b><span>{{$wenjian}}</span></p>
                    <p class="fs14"><b>交车当场移交客户的随车工具：</b>{{$gongju}}</p>
                    <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>

                    <p class="fs14">信用卡: {{$baojia['more']['ka']['xyk']}}</p>
                    <p class="fs14">借记卡: {{$baojia['more']['ka']['jjk']}}</p>

                    <h2 class="title">补贴</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b>{{$baojia['bj_butie']}} 元</p>
                        <p class="fs14"><b> 办理流程和时限：</b>
                        <?php 
                                        if (isset($baojia['more']['butie'])) {
                                            if (is_array($baojia['more']['butie'])) {
                                                echo implode($baojia['more']['butie']);
                                            }else{
                                                echo $baojia['more']['butie'];
                                            }
                                        }

                                     ?>
                        </p>
                    </div>
                    <div class="clear"></div>
                     <?php if ($baojia['bj_zf_butie']): ?>
                      <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                    <?php endif ?>
                    <?php if ($baojia['bj_cj_butie']): ?>
                      <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                    <?php endif ?>
                    <div class="clear"></div>


                    <h2 class="title">以下提议修改内容等待客户确认</h2>
                    <p class="fs14">车身颜色：{{$editcarinfo['body_color']}}</p>
                    <p class="fs14">内饰颜色：{{$editcarinfo['interior_color']}}</p>
                    <p class="fs14">出厂年月：{{$editcarinfo['chuchang']}}</p>
                    <p class="fs14">行驶里程：{{$editcarinfo['licheng']}}公里</p>
                    <p class="fs14">交车时限：{{$bj_jc_period}}</p>
                    <p class="fs14">排放标准：{{$editcarinfo['paifang']}} </p>

                    <p class="fs14 ">已装原厂选装精品：</p>

                        <table class="tbl bgtbl " style="width: 100%">
                            <tr>
                                <th>名称</th>
                                <th>型号/说明</th>
                                <th>厂商指导价</th>
                                <th>数量</th>
                                <th>附加价值</th>
                            </tr>
                            <?php $cc=0.00; ?>
                            @if(!empty($editxzj) && count($editxzj)>0)
	                            @foreach($editxzj as $k => $v)
                              <?php if ($v['num']<1) continue; 
                              $cc+=$v['xzj_guide_price']*$v['num'];

                              ?>
	                            <tr data-id="3">
	                                <td>{{$v['xzj_title']}}</td>
	                                <td>{{$v['xzj_model']}}</td>
	                                <td class="tac">{{$v['xzj_guide_price']}} 元</td>
	                                <td class="tac">{{$v['num']}}</td>
	                                <td class="tac">{{$v['xzj_guide_price']*$v['num']}}</td>
	                            </tr>
	                            @endforeach
                           
                            @endif
                            
                        </table>
                        <p class="fs14">
                            <small class="wp45 fr tar di mr150"><span>合计价值：<label>{{$cc}} 元</label></span></small>
                            <input type="hidden" name="price">
                        </p>

                        <p class="fs14 ">免费礼品和服务：</p>
                        <table class="tbl bgtbl bgtbl-mini fl">
                            <tr>
                                <th>名称</th>
                                <th>数量</th>
                                <th>状态</th>
                            </tr>
                            @if(!empty($editzengpin) && count($editzengpin)>0)
                            	@foreach ($editzengpin as $key => $value)
                              <?php if ($value['num']<1) continue; ?>
                                          <tr data-id="3">
			                                <td>{{$value['title']}}</td>
			                                <td align="center">
			                                    <div class="xuan">
			                                        <div class="x-w"> 
			                                            <p class="fs14 tac">{{$value['num']}}</p>
			                                        </div>
			                                    </div>
			                                </td>
			                                <td class="tac">
			                                  <p class="fs14">{{$value['is_install']==1?"已安装":"未安装"}}</p>
			                                </td>
			                            </tr>
                                  @endforeach
                            
                             @endif
                                       
                           
                            
                        </table>
                        <div class="clear"></div>

                    
               
                </div>
            </div>
        </div>
    </div>
    <div ms-controller="uitest" ms-ui-$opts="testui" data-id="ddd"></div>
@endsection
@section('js')
   
    <script type="text/javascript">
        seajs.use(["module/item/item-wait","module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection