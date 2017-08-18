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
                                                  <b>订单时间：</b>{{$order->created_at}}
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
                                            <td><p><b>厂商指导价：</b>{{$carmodelInfo['zhidaojia']}}  元</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆类别：</b>{{carUse($order['buytype'])}}</p></td>
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
                                                <span class="">{{$jxs['d_areainfo']}}</span>
                                                <span class="sj" ms-click="hideTm"  ms-mouseout="hideTm()"  ms-mouseover="displayTm()" >
                                                   <span class="fs12">更多</span>
                                                </span>
                                                <div class="tm loca-c page-loca" >
                                                  <input type="hidden" name="page-loca">
                                                  
                                                  <?php foreach ($area as $key => $value): ?>
                                                    <?php foreach ($value as $k=> $v): ?>
                                                      <a href="javascript:;">{{$v}}</a>
                                                    <?php endforeach ?>
                                                    
                                                  <?php endforeach ?>
                                                </div>
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
                                                  <p class="tm detail">
                                                    <label>歉意金：499,00元（2015-10-26   10：57 ：57）</label>
                                                    <label>歉意金（赔偿客户）：— 499,00元（2015-10-26   10：57 ：57）</label>
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>                                    
                                </td>
                                <td>
                                    <div class="times times-error">
                                        <p class="status tac  m-t-10"><b class="fs14">订单状态：{{getStatusNotice($order['cart_sub_status'])}}</b></p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>

                                        <hr class="dashed mt69">
                                        <p class="pl20 pt"><b>客户会员号： </b>{{formatNum($order['buy_id'],1)}} </p>
                                        <p class="pl20 pt"><b>客户车辆用途： </b>{{carUse($order['buytype'])}} </p>
                                        <p class="pl20 pt"><b>客户承诺上牌地区： </b> {{$shangpai_area}}</p>
                                        <p class="pl20 pt">
                                          <b>上牌车主身份类别： </b>
                                          <span class="fr" style="width: 166px">{{$order['shenfen']}}</span> 
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
                                            <td width="33%"><p><b>生产国别：</b>{{$editcarinfo['guobieTitle']}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{$editcarinfo['paifang']}}
                                            </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$editcarinfo['body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$editcarinfo['interior_color']}}</p></td>
                                            <td width="33%"><p><b>出厂年月：</b>{{$editcarinfo['chuchang']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b>{{$editcarinfo['licheng']}}</p></td>
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
                                                       <th><p class="fs15">型号/说明</p></th>
                                                       <th><p class="fs15">厂家指导价</p></th>
                                                       <th><p class="fs15">数量</p></th>
                                                       <th><p class="fs15">附加价值</p></th>
                                                   </tr>
                                                   
                                                   <?php 
                                                    $total=0.00;
                                                   foreach ($xzj as $key => $value): ?>
                                                    <tr>
                                                       <td>{{$value['xzj_title']}}</td>
                                                       <td>{{$value['xzj_model']}}</td>
                                                       <td>{{$value['xzj_guide_price']}}</td>
                                                       <td>{{$value['num']}}</td>
                                                       <td>{{$value['xzj_guide_price']*$value['num']}}</td>
                                                   </tr>
                                                   <?php $total+=$value['xzj_guide_price']*$value['num']; ?>
                                                   <?php endforeach ?> 
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang">{{$total}}</span></h2>
                                                

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
                                            <td width="33%"><p><b>排放标准：</b>{{$editcarinfo['paifang']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$editcarinfo['body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$editcarinfo['interior_color']}}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{$editcarinfo['zhouqi']}}个月</p></td>
                                        </tr>
                                        
                                      

                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
                        <?php endif ?>
                    <h2 class="title">结算信息  </h2>
                    <div class="fl" style="width: 70%;margin:0 auto">
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
                    <div class="fl" style="width: 70%;margin:0 auto">
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
                        </table>
                    </div>
                    <div class="clear"></div>




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