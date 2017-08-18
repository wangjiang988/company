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
                                            <td  width="50%"><p><b>订单号：</b></p></td>
                                            <td  width="50%">
                                                <div class="psr">
                                                  <b>订单时间：</b>2015年10月20日
                                                  <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                                     <b>更多</b>
                                                  </span>

                                                  <p class="tm tm-long">
                                                
                                                    <label>客户诚意金确认进入加信宝时间  2015-10-20   10：57：57</label>
                                                    <label>售方反馈特需要求并提议修改订单时间  2015-10-20   10：57：57</label>
                                                   
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
                                                  <p class="tm detail">
                                                    <label>歉意金：499,00元（2015-10-26   10：57 ：57）</label>
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>                                    
                                </td>
                                <td>
                                    <div class="times">
                                    
                                        <p class="status tac  m-t-10"><b class="fs14">订单状态：已反馈特需要求，</b></p>
                                        <p class="fs14 tac"><b>等待客户确认</b></p>
                                        <p class="tac m-t-10"><a href="#" class="juhuang tdu">查看订单总详情</a></p>

                                        <hr class="dashed mt69">
                                        <p class="pl20 pt"><b>客户会员号： </b>HC13809643 </p>
                                        <p class="pl20 pt"><b>客户车辆用途： </b>非营业个人客车 </p>
                                        <p class="pl20 pt"><b>客户承诺上牌地区： </b> </p>
                                        <p class="pl20 pt">
                                          <b>上牌车主身份类别： </b>
                                          <span class="fr" style="width: 166px">国内其他限牌城市户籍居民（北京）</span> 
                                          <label class="clear"></label>
                                        </p>
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
                                            <td width="33%"><p><b>基本配置：</b>同<a href="#" class="juhuang tdu">附件一</a></p></td>
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
                                            <td width="33%"><p><b>交车周期：    </b>6个月</p></td>
                                        </tr>
                                        
                                      

                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>

                    <h2 class="title">选装精品  </h2>
                    <p class="fs14"><b>原厂选装精品折扣率：</b></p>
                    <p class="fs14"><b>原厂选装精品的已定价标准：</b></p>
                    <table class="tbl">
                        <tr>
                           <th><p class="fs15">名称</p></th>
                           <th><p class="fs15">型号/说明</p></th>
                           <th><p class="fs15">厂家指导价</p></th>
                           <th><p class="fs15">安装费</p></th>
                           <th><p class="fs15">含安装费折后总单价</p></th>
                           <th><p class="fs15">单车可装件数</p></th>
                           <th><p class="fs15">可供件数</p></th>
                       </tr>
                       <tr>
                           <td>&nbsp;</td>
                           <td></td>
                           <td></td>
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
                           <td></td>
                           <td></td>
                       </tr>   
                    </table>

                    <h2 class="title">车辆首年商业保险</h2>
                    <p class="fs14"><b>客户是否经销商处购买商业保险：</b>确定购买</p>
                    <p class="fs14"><b>客户是否经销商处购买商业保险：</b>待定</p>
                    <p class="fs14"><b>客户是否经销商处购买商业保险：</b>不购买</p>
                    <p class="fs14"><b>提供商业保险的保险公司：</b></p>
                    <p class="fs14">商业保险的首年保费基准：</p>


                    <table class="tbl">
                       
                        <tbody><tr>
                            <td width="50" valign="top">
                                <table width="175%" height="100%" style="margin-left: -10px">
                                    <tbody><tr>
                                        <td style="border:0;height: 30px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;"  valign="middle"><b>类别</b></td>
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
                                        <th class="" width="140">保费原价</th>
                                        <th class="norightborder">折后价</th>
                                    </tr>
                                    <tr>
                                        <td class="cell"><label class="fs14 normal">机动车损失险</label></td>
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
                    <p>说明：具体投保险种与保额，由客户在交车通知反馈中选择。 </p>

                    <h2 class="title">上牌（车辆注册登记）</h2>
                    <p class="fs14"><b>是否由经销商代办上牌： </b>确定代办上牌</p>
                    <p class="fs14"><b>是否由经销商代办上牌：</b>待定</p>
                    <p class="fs14"><b>是否由经销商代办上牌：</b>不代办</p>
                    <p class="fs14"><b>代办上牌服务费金额：</b></p>
                    <p class="fs14"><b>客户本人上牌违约金约定：</b></p>
                    <p class="fs14"><b>限牌城市（北京）客户取得牌照指标的安排： </b>已取得牌照指标  或  订车后自行取得牌照指标</p>
                    
                    <h2 class="title">上临时牌照（车辆临时移动牌照）</h2>
                    <p class="fs14"><b>是否由经销商代办车辆临时牌照：</b>确认代办</p>
                    <p class="fs14"><b>是否由经销商代办车辆临时牌照：</b>待定</p>
                    <p class="fs14"><b>是否由经销商代办车辆临时牌照：</b>不代办</p>
                    <p class="fs14"><b>代办车辆临时牌照（每次）服务费金额：</b></p>
                    
                    <h2 class="title">其他收费</h2>
                    <div style="width: 70%;margin:0 auto">
                        <table class="tbl">
                            <tbody><tr>
                                <th><label class="fs14 weight">费用名称</label></th>
                                <th><label class="fs14 weight">金额</label></th>
                            </tr>
                            <tr>
                                <td><p class="tac fs14"></p></td>
                                <td><p class="tac fs14"></p></td>
                            </tr>
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
                            <tr>
                                <td><p class="tac fs14"></p></td>
                                <td><p class="tac fs14"></p></td>
                                <td><p class="tac fs14"></p></td>
                            </tr>
                        </tbody></table>
                    </div>

                    <h2 class="title">交车有关事宜</h2>
                    <p class="fs14"><b>交车当场移交客户的文件资料：</b></p>
                    <p class="fs14"><b>交车当场移交客户的随车工具：</b></p>
                    <p class="fs14"><b>在经销商处单车付款刷卡收费标准：</b></p>
                    
                    <p class="fs14">信用卡：免费不限次</p>
                    <p class="fs14">信用卡：免费XX次；超出次数收费：刷卡金额的<input type="text" class="baoxianinput juhuang shot" name="money" value="">%（百分之），每次<input type="text" class="baoxianinput juhuang shot" name="money" value="">元（封顶）。</p>
                    <p class="fs14">信用卡：免费XX次；超出次数收费：刷卡金额的<input type="text" class="baoxianinput juhuang shot" name="money" value="">%（百分之）。</p>
                    <p class="fs14">信用卡：免费XX次；超出次数收费：每次<input type="text" class="baoxianinput juhuang shot" name="money" value="">元（封顶）。</p>

                    <p class="fs14">借记卡：免费不限次</p>
                    <p class="fs14">借记卡：免费XX次；超出次数收费：刷卡金额的<input type="text" class="baoxianinput juhuang shot" name="money" value="">%（百分之），每次<input type="text" class="baoxianinput juhuang shot" name="money" value="">元（封顶）。</p>
                    <p class="fs14">借记卡：免费XX次；超出次数收费：刷卡金额的<input type="text" class="baoxianinput juhuang shot" name="money" value="">%（百分之）。</p>
                    <p class="fs14">借记卡：免费XX次；超出次数收费：每次<input type="text" class="baoxianinput juhuang shot" name="money" value="">元（封顶）。</p>
       
       

                    <h2 class="title">补贴</h2>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14"><b>国家节能补贴</b></p>
                        <p class="fs14"><b>补贴金额： </b></p>
                        <p class="fs14"><b> 办理流程和时限：</b></p>
                    </div>
                    <div class="clear"></div>
                    <p class="fs14"><i class="yuan"></i><b>地方政府置换补贴：</b>经销商可提供必要协助  </p>
                    <p class="fs14"><i class="yuan"></i><b>厂家或经销商置换补贴：</b>有 </p>
                    <div class="clear"></div>

                    <h2 class="title">可为客户办理的其他上牌资料</h2>
                    <p class="fs14">XX证明：费用人民币1,500.00元，办理时间5个自然日 </p>                    
                    <p class="fs14">YY证明：费用人民币1,500.00元，办理时间5个自然日 </p>                    
                    <p class="fs14">ZZ证明：费用人民币1,500.00元，办理时间5个自然日 </p>                    


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