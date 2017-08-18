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
                                            <td  width="50%"><p><b>订单号：</b></p>{{$order_num}}</td>
                                            <td  width="50%">
                                                <div class="psr">
                                                  <b>订单时间：</b>{{ddate($created_at)}}
                                                  <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                                     <b>更多</b>
                                                  </span>
                                                  <p class="tm tm-long">
                                                
                                                    
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>订单类别：</b><?php if ($baojia['bj_producetime']): ?>
                                              现车订单
                                            <?php else: ?>
                                              远期订单
                                            <?php endif ?></p><hr class="dashed"></td>
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
                                            <td><p><b>车辆类别：</b>全新中规车</p></td>
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
                                            <td><p><b>付款方式：</b>{{payType($baojia['bj_pay_type'])}}</p></td>
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
                                    <div class="times times2 times-error" style="height:450px">
                                        <p class="status tac status2"><b class="fs14">订单状态：{{getStatusNotice($cart_sub_status)}}  </b></p>
                                        
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($buy_id)}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{ changeMobile($buyer['member_mobile'])}}</p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b>{{$shangpaiarea}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b>{{carUse($buytype)}} </p>
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
                    <h2 class="title">结算信息  </h2>
                    <div class="fl" style="width: 85%;margin:0 auto">
                        <table class="tbl">
                            <tr>
                                <td><p class="tac fs14"><b>项目</b></p></td>
                                <td><p class="tac fs14"><b>金额</b></p></td>
                                <td><p class="tac fs14"><b>进度</b></p></td>
                                <td><p class="tac fs14"><b>备注</b></p></td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">歉意金赔偿</p></td>
                                <td><p class="tac fs14">—人民币 499.00 元</p></td>
                                <td><p class="tac fs14">已执行</p></td>
                                <td>
                                    <p class="tal fs14">原因：反馈订单修改约定内容</p>
                                    <p class="tal fs14">确认时间：2015年12月18日  8：36：05</p>
                                    <p class="tal fs14">执行时间：2015年12月18日  8：36：05</p>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">歉意金赔偿2</p></td>
                                <td><p class="tac fs14">—人民币 499.00 元</p></td>
                                <td><p class="tac fs14">已执行</p></td>
                                <td>
                                    <p class="tal fs14">原因：反馈订单修改约定内容</p>
                                    <p class="tal fs14">确认时间：2015年12月18日  8：36：05</p>
                                    <p class="tal fs14">执行时间：2015年12月18日  8：36：05</p>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">客户买车担保金利息赔偿</p></td>
                                <td><p class="tac fs14">—人民币 499.00 元</p></td>
                                <td><p class="tac fs14">已执行</p></td>
                                <td>
                                    <p class="tal fs14">原因：反馈订单修改约定内容</p>
                                    <p class="tal fs14">确认时间：2015年12月18日  8：36：05</p>
                                    <p class="tal fs14">执行时间：2015年12月18日  8：36：05</p>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">华车平台损失赔偿</p></td>
                                <td><p class="tac fs14">—人民币 200.00 元</p></td>
                                <td><p class="tac fs14">已执行</p></td>
                                <td>
                                    <p class="tal fs14">原因：售方变更内容导致订单终止</p>
                                    <p class="tal fs14">确认时间：2015年12月18日  8：36：05</p>
                                    <p class="tal fs14">执行时间：2015年12月18日  8：36：05</p>
                                </td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">实际收入 </p></td>
                                <td><p class="tac fs14">—人民币 699.00 元</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td><p class="tac fs14"></p></td>
                            </tr>
                        </table>
                    </div>
                    <div class="clear"></div>
                    <p class="fs14"><b>加信宝冻结与解冻保证金进程：</b></p>
                    <div class="fl" style="width: 85%;margin:0 auto">
                        <table class="tbl">
                            <tr>
                                <td><p class="tac fs14"><b>项目</b></p></td>
                                <td><p class="tac fs14"><b>金额</b></p></td>
                                <td><p class="tac fs14"><b>冻结时间</b></p></td>
                                <td><p class="tac fs14"><b>解冻时间</b></p></td>
                                <td><p class="tac fs14"><b>去向</b></p></td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">歉意金 </p></td>
                                <td><p class="tac fs14">人民币 499.00 元</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">赔偿客户 </p></td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">歉意金2 </p></td>
                                <td><p class="tac fs14">人民币 499.00 元</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">赔偿客户 </p></td>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">客户买车担保金利息 </p></td>
                                <td><p class="tac fs14">人民币 499.00 元</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">赔偿客户 </p></td>
                            </tr>

                            <tr>
                                <td><p class="tac fs14">客户买车担保金利息2 </p></td>
                                <td><p class="tac fs14">人民币 499.00 元</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">2015年12月18日 15:18:08</p></td>
                                <td><p class="tac fs14">赔偿客户 </p></td>
                            </tr>
                        </table>
                    </div>
                    <div class="clear"></div>
                    <p class="fs14"><span class="xing">*</span>客户买车担保金：6,000.00元 ， 2015-12-06 ~ 2015-12-09（3天）</p>
                    <p class="fs14"><span class="ml10"></span>客户买车担保金2：6,000.00元 ， 2015-12-10 ~ 2016-1-2 （22天）</p>
                    <div class="clear"></div>

                    
               
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