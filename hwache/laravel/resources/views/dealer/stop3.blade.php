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
                                            <td  width="50%"><p><b>订单号：</b></p></td>
                                            <td  width="50%">
                                                <div class="psr">
                                                  <b>订单时间：</b>2015年10月20日
                                                  <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                                     <b>更多</b>
                                                  </span>
                                                  <p class="tm tm-long">
                                                    <label>客户诚意金确认进入加信宝时间  2015-10-20   10：57：57</label>
                                                    <label>根据订单走过的路径显示</label>
                                                    <!--//类似这样的 <label>反馈订单时间  2015-10-20   10：57：57  </label>-->
                                                    <!--//第一种方式-->
                                                    <label>售方响应订单时间   2015-10-20   10：57：57</label>
                                                    <label>售方提议修改订单时间   2015-10-20   10：57：57</label>
                                                    <!--//第二种方式-->
                                                    <label>售方响应订单时间   2015-10-20   10：57：57</label>
                                                    <label>售方交车通知超时自动终止订单时间   2015-10-20   10：57：57</label>
                                                    <!--//第三种方式-->
                                                    <label>售方提议修改订单时间   2015-10-20   10：57：57</label>
                                                    <label>客户不接受修改而终止订单时间   2015-10-20   10：57：57</label>
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>订单类别：</b>现车订单</p><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>品牌：</b></p></td>
                                            <td><p><b>车系：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车型规格：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>座位数：</b></p></td>
                                            <td><p><b>厂商指导价：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆类别：</b></p></td>
                                            <td><p><b>数量：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>经销商名称：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>营业地点：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>交车地点：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>归属地区：</b></p></td>
                                            <td>
                                                <div class="psr">
                                                  <b>销售区域：</b>
                                                  <span class="">苏州</span>
                                                  <span class="sj" ms-click="hideTm"  ms-mouseout="hideTm()"  ms-mouseover="displayTm()" >
                                                     <span class="fs12">更多</span>
                                                  </span>
                                                  <div class="tm loca-c page-loca" >
                                                    <input type="hidden" name="page-loca">
                                                    <a href="javascript:;">南京</a>
                                                    <a href="javascript:;">苏州</a>
                                                    <a href="javascript:;">无锡</a>
                                                    <a href="javascript:;">常州</a>
                                                  </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>经销商裸车开票价格：</b></p></td>
                                            <td><p><b>付款方式：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>客户买车定金：</b></p></td>
                                            <td><p><b>我的服务费：</b></p></td>
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
                                        <p class="status tac status2"><b class="fs14">订单状态：已终止  </b></p>
                                        
                                        <p class="tac m-t-10"><a href="#" class="juhuang tdu">查看订单总详情</a></p>
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>HC13809643 </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>王** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>先生/女士/小姐 </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>134****2598 </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b></p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b>非营业个人客车 </p>
                                        <p class="pl20 pt">
                                          <b>上牌客户身份类别： </b>
                                          <span class="fr" style="width: 165px;color:#8e8d8d;text-align: left;">国内其他限牌城市户籍居民（北京）</span> 
                                          <span class="clear"></span>
                                        </p>
                                        <p class="clear"></p>
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：   </b> </p>
                                       
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
                                            <td width="33%"><p><b>基本配置：</b></p></td>
                                            <td width="33%"><p><b>生产国别：</b></p></td>
                                            <td width="33%"><p><b>排放标准：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b></p></td>
                                            <td width="33%"><p><b>内饰颜色：</b></p></td>
                                            <td width="33%"><p><b>出厂年月：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>行驶里程：</b></p></td>
                                            <td width="33%"><p><b>交车时限：  </b>年    月    日</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>年   月    日</p></td>
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
                                                   <tr>
                                                       <td>&nbsp;</td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                   </tr>
                                                   <tr>
                                                       <td>&nbsp;</td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                   </tr>   
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"></span></h2>
                                                

                                            </td>
                                        </tr>
                                    </table>                                      
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
                                            <td width="33%"><p><b>基本配置：</b></p></td>
                                            <td width="33%"><p><b>生产国别：</b></p></td>
                                            <td width="33%"><p><b>排放标准：</b></p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b></p></td>
                                            <td width="33%"><p><b>内饰颜色：</b></p></td>
                                            <td width="33%"><p><b>交车周期：    </b>6个月 </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>交车时限：  </b>年    月    日</p></td>
                                            <td width="33%"><p><b>交车通知发出时限：</b>年   月    日</p></td>
                                            <td width="33%"><p><b></b></p></td>
                                        </tr>
                                      

                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>

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