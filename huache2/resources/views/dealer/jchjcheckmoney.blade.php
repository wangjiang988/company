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
           <div class="line stp-5"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><i class="cur-step cur-step-4">4</i></li>
               <li class="last end"><i class="cur-step cur-step-5">5</i></li>
           </ul> 
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-5">
                    <small class="juhuang">核对金额</small>
                    <i></i>
                    <small>办理手续</small>
                    <i></i>
                    <small>结算完毕</small>
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
                                                  <b>订单时间：</b>{{$order['created_at']}}
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
                                            <td colspan="2"><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>品牌：</b>{{$bj['brand'][0]}}</p></td>
                                            <td><p><b>车系：</b>{{$bj['brand'][1]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车型规格：</b>{{$bj['brand'][2]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车身颜色：</b>{{$body_color}}</p></td>
                                            <td><p><b>内饰颜色：</b>{{$interior_color}}</p></td>
                                        </tr>
                                       
                                      
                                    </table>                                  
                                </td>
                                <td>
                                    <div class="times times2" style="height:auto">
                                        <p class="status tac status2"><b>订单状态：交车信息已核实，</b></p>
                                        <p class="tac"><b>等待核对收支金额</b></p>
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>
                                    </div>
                                </td>
                               
                            </tr> 
                            <tr>
                              <td colspan="2">
                                 <table class="tbl2" width="100%">
                                   <tr>
                                     <td width="50%"><p class="fs14"><b>经销商名称：</b>{{$jxs['d_name']}}</p></td>
                                     <td><p class="fs14"><b>交车地点：</b>{{$jxs['d_jc_place']}}</p></td>
                                   </tr>
                                
                                 </table>

                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                 <table class="tbl2" width="100%">
                                   <tr>
                                     <td width="33.33%"><p class="fs14"><b>客户会员号：</b>{{formatNum($order['buy_id'],1)}} </p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户姓名：  </b>{{$buyer['member_truename']}}</p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户称呼：  </b>{{ getSex($buyer['member_sex'])}}</p></td>
                                   </tr>
                                 </table>

                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                 <table class="tbl2" width="100%">
                                   <tr>
                                     <td width="33.33%"><p class="fs14"><b>经销商裸车开票价格：</b> {{$bj['bj_lckp_price']}}</p></td>
                                     <td width="33.33%"><p class="fs14"><b>我的服务费：  </b>{{$guarantee}}</p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户买车定金：  </b>{{$bj_agent_service_price}}</p></td>
                                   </tr>
                                   <tr>
                                     <td colspan="3">
                                        <p class="fs14"><b>客户本人上牌违约金约定：</b>{{$bj['bj_license_plate_break_contract']}}</p>
                                   </tr>
                                   
                                 </table>

                              </td>
                            </tr>
                          
                        </tbody>
                    </table>


                   
                  <h2 class="title">交车信息</h2>
                     


                <div style="width: 500px;">
                    <table class="tbl">
                          <tbody>
                              <tr>
                                  <td><p class="tal fs14 weight">车辆识别代号（VIN码）</p></td>
                                  <td width="300">
                                      <span>{{$jiaoche['pdi_vin']}}</span>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">发动机号</p></td>
                                  <td width="300">
                                      <span>{{$jiaoche['pdi_engine_no']}}</span>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">上牌地区</p></td>
                                  <td width="300">
                                      <span>{{$jiaoche['pdi_shangpai_area']}}</span>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">车辆用途</p></td>
                                  <td width="300">
                                      <span>{{$jiaoche['pdi_useway']}}</span>
                                  </td>
                              </tr>  
                              <tr>
                                  <td><p class="tal fs14 weight">上牌（注册登记）车主名称</p></td>
                                  <td width="300">
                                      <span>{{$jiaoche['pdi_regname']}}</span>
                                  </td>
                              </tr>  
                              <tr>
                                  <td><p class="tal fs14 weight">牌照号码</p></td>
                                  <td width="300">
                                  	@if(!empty($jiaoche['pdi_chepai']))
                                      <span>{{implode("",unserialize($jiaoche['pdi_chepai']))}}</span>
                                    @endif
                                  </td>
                              </tr>  
                              
                          </tbody>
                    </table> 
                </div>
                


                <h2 class="title">结算信息</h2>
                <div style="width: 800px;">
                    <table class="tbl">
                          <tbody>
                              <tr>
                                  <th><span class="fs14">项目</span></th>
                                  <th>
                                      <span class="fs14">金额</span>
                                  </th>
                                  <th>
                                      <span class="fs14">进度</span>
                                  </th>
                                  <th>
                                      <span class="fs14">备注</span>
                                  </th>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">我的服务费实得</p></td>
                                  <td width="">
                                      <span>人民币 3,000.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span>已执行</span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">获得诚意金补偿</p></td>
                                  <td width="">
                                      <span>人民币 499.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span>已确认</span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：客户未按时支付买车担保金</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：</p>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">获得客户买车定金补偿</p></td>
                                  <td width="">
                                      <span>人民币 5,000.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span></span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：客户未在约定上牌地区上牌</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">获得客户本人上牌违约金补偿</p></td>
                                  <td width="">
                                      <span>人民币 2,199.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span></span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：客户未在约定上牌地区上牌</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">歉意金赔偿</p></td>
                                  <td width="">
                                      <span>—人民币 499.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span></span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：反馈订单修改约定内容</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">客户买车担保金利息赔偿</p></td>
                                  <td width="">
                                      <span>—人民币 4,000.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span></span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：交车通知发出时限内未发交车通知</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">实际收入</p></td>
                                  <td width="">
                                      <span>人民币 3,000.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span></span>
                                  </td>
                                  <td width="">
                                      <p class="fs14"></p>
                                  </td>
                              </tr> 
                             
                          </tbody>
                    </table> 
                </div>
               
                <p class="fs14" style="margin:15px 0 0 0"><b>加信宝冻结与解冻保证金进程：</b></p>
                <div class="psr" style="height:175px;">
                  <table class="tbl" style="width:90%;float: left;">
                    <tr>
                      <th><span class="fs14">项目</span></th>
                      <th><span class="fs14">金额</span></th>
                      <th><span class="fs14">状态</span></th>
                      <th><span class="fs14">冻结时间</span></th>
                      <th><span class="fs14">解冻时间</span></th>
                      <th><span class="fs14">去向</span></th>
                    </tr>
                    <tr>
                      <td>歉意金 2</td>
                      <td>人民币 499.00 元</td>
                      <td>已解冻</td>
                      <td>2015年12月18日 15:18:08</td>
                      <td>2015年12月18日 15:18:08</td>
                      <td>浮动保证金</td>
                    </tr>
                    <tr>
                      <td>客户买车担保金利息</td>
                      <td>人民币 302.00 元</td>
                      <td>已解冻</td>
                      <td>2015年12月18日 15:18:08</td>
                      <td>2015年12月18日 15:18:08</td>
                      <td>赔偿客户</td>
                    </tr>
                    <tr>
                      <td>国家节能补贴发放保证金</td>
                      <td>人民币 2,199.00 元</td>
                      <td>已冻结</td>
                      <td>2015年12月18日 15:18:08</td>
                      <td></td>
                      <td></td>
                    </tr>
                  </table>
                   <a href="#" class="juhuang tdu pl20" style="position:absolute;bottom: 0;">报错</a>

                </div>

                <div class="clear"></div>

                <p class="fs14"><span class="xing weight">*</span>客户买车担保金：6,000.00 元，2015 - 12 - 01~2016 - 1 - 15（4天）</p>
                <p class="fs14 pl12">客户买车担保金 2：6,000.00 元，2016 - 1 - 16~2016 - 1 - 20（4天）</p>
                <p class="fs14"><b>结算文件可用数：</b>{{$calc_file}}</p>
                <p class="tal">
               	 @if($order['pdi_calc_status']==0)
                  <a href="javascript:;" class="btn btn-s-md btn-danger" ms-on-click="pdi_agree_calc('{{$order_num}}','{{csrf_token()}}')">申请结算</a>
                	@else
                <a href="javascript:;" class="btn btn-s-md btn-danger" >已申请结算</a>
                	@endif
                 
                </p>

              
                   
              </div>
              @if($order_attr['butie']==1)
	                <div class="fs14 m-t-10 clear">
	                @if($jiaoche['pdi_butie_fafang']=='')
	                    <span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['user_butie_date']}}    </span>
	                    <a ms-on-click="pdibutie" href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">发放补贴</a>
	                @else
	                	<span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['user_butie_date']}}    </span>
	                    <a  href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">补贴已发放</a>
	                @endif
	                    <div class="clear"></div>
	                </div>
                @endif
                <div class="clear"></div>
				<div id="pdi-tip" class="popupbox">
                      <div class="popup-title">提示</div>
                      <div class="popup-wrapper">
                      <form action='{{ url('dealer/ajax').'/'.$order_num.'/surebutie' }}' name='surebutieform' method='post' enctype="multipart/form-data">
                          <div class="popup-content">
                              
                              <div class="fs14 pd ">       
                                <span class="fl">上传发放补贴证明资料：</span>
                                <span class="blue fl "></span>
                                <span class="juhuang tdu cp fl ml10" ms-on-click="upload">上传</span>
                                <input type="file" name="pdi_butie_file" ms-on-change="changesingle" id="hfUpload" class="hide" value="">
                                <input type="hidden" name="" id="hfFile">
                                <div class="clear"></div>
                              </div>
                          </div>
                          <div class="popup-control">
                              <a ms-on-click="surebutie" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                              <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">取消</a>
                              <div class="clear"></div>
                          </div>
                        <input type="hidden" value="{{$order_num}}" name="order_num" >
                        <input type="hidden" value="{{$order['id']}}" name="id" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
                      </div>
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