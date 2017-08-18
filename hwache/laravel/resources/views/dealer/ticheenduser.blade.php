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
           <div class="line stp-4"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><i class="cur-step cur-step-4">4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul> 
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-4">
             
                    <small>正在交车</small>
                    <i></i>
                    <small class="juhuang">核实信息</small>
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
                                             <td><p><b>车身颜色：</b>{{ $bj['body_color'] }}</p></td>
                                            <td><p><b>内饰颜色：</b>{{ $bj['interior_color'] }}</p></td>
                                        </tr>
                                       
                                      
                                    </table>                                  
                                </td>
                                <td>
                                    <div class="times times2" style="height:auto">
                                        <p class="status tac status2"><b>订单状态：{{getStatusNotice($order['cart_sub_status'])}}</b></p>
                                        
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu" target="_blank">查看订单总详情</a></p>
                                    </div>
                                </td>
                               
                            </tr> 
                            <tr>
                              <td colspan="2">
                                 <table class="tbl2" width="100%">
                                   <tr>
                                     <td width="50%"><p class="fs14"><b>经销商名称：</b>{{$jxs['d_name']}}</p></td>
                                     <td><p class="fs14"><b>销售区域：</b>{{$bj['area']}}</p></td>
                                   </tr>
                                   <tr>
                                     <td><p class="fs14"><b>交车地点：</b>{{$jxs['d_jc_place']}}</p></td>
                                     <td><p class="fs14"><b>约定交车时间：</b>{{$jc_date['date']}}</p></td>
                                   </tr>
                                 </table>

                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                 <table class="tbl2" width="100%">
                                   <tr>
                                     <td width="33.33%"><p class="fs14"><b>客户会员号：</b>{{formatNum($order['buy_id'],1)}} </p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户姓名：  </b>{{mb_substr($buyer['member_truename'],0,1)}}**</p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户称呼：  </b>{{ getSex($buyer['member_sex'])}}</p></td>
                                   </tr>
                                   <tr>
                                     <td width="33.33%"><p class="fs14"><b>客户承诺上牌地区：</b> {{$shangpai_area}} </p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户车辆用途：</b><?php if ($order['buytype']): ?>
个人用车
  <?php else: ?>
公司用车
<?php endif ?></p></td>
                                     <td width="33.33%"><p class="fs14"><b class="fl">上牌车主身份类别：</b><span class="fl" style="width: 140px;">{{$order['shenfen']}}</span><div class="clear"></div></p></td>
                                   </tr>
                                   
                                 </table>

                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                 <table class="tbl2" width="100%">
                                   <tr>
                                     <td width="33.33%"><p class="fs14"><b>经销商裸车开票价格：</b>{{ $bj['bj_lckp_price'] }} 元 </p></td>
                                     <td width="33.33%"><p class="fs14"><b>我的服务费：  </b>{{$bj_agent_service_price}} 元</p></td>
                                     <td width="33.33%"><p class="fs14"><b>客户买车定金：  </b>{{$guarantee}} 元</p></td>
                                   </tr>
                                   <tr>
                                     <td colspan="3">
                                     <div class="psr">
                                        <b>加信宝已冻结浮动保证金：</b>XX 元
                                        <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                           <b>详细</b>
                                        </span>
                                        <p class="tm detail" style="width:470px">
                                          <label>冻结的第一笔歉意金：499,00元（2015-10-26   10：57 ：57）</label>
                                          <label>冻结的客户买车担保金利息损失补偿金：723.09元（2015-10-25 ~ 2015-11-03）</label>
                                        </p>
                                      </div>
                                   
                                   </tr>
                                   
                                 </table>

                              </td>
                            </tr>
                          
                        </tbody>
                    </table>


                   
                  <h2 class="title jh">交车信息</h2>
                     


                <div style="width: 500px;float:left;">
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
                                  <td><p class="tal fs14 weight">客户本人上牌违约金约定</p></td>
                                  <td width="300">
                                      <span>{{$bj['bj_license_plate_break_contract']}} 元</span>
                                  </td>
                              </tr> 
                              <tr>
                                  <td><p class="tal fs14 weight">客户预计上牌最晚日期</p></td>
                                  <td width="300">
                                      <span>{{$jiaoche['user_shangpai_time']}}</span>
                                  </td>
                              </tr>  
                              
                          </tbody>
                    </table> 
                </div>
                <div class="jingdu" >
                    <span class="fl fs14"><b>核实信息进度：</b></span>
                    <dl class="fl jd-dl" style="width: 150px;">
                        <dd class="cur-jd"><span></span>售方已提交</dd>
                        <dt></dt>
                        <dd
						<?php if ($jiaoche['user_date_first']): ?>
							 class="cur-jd"
						<?php endif ?>
                        ><span></span>客户已提交</dd>
                        <dt></dt>
                        <dd><span></span>华车平台核实中</dd>
                        <dt></dt>
                        <dd><span></span>华车平台已核实</dd>
                    </dl>
                    <div class="clear"></div>

                </div>
                <div class="clear"></div>
                <p class="tac"><a href="javascript:;" class="btn btn-s-md btn-danger sure">车辆服务费结算申请</a></p>


              

                <hr class="dashed" />
                <div style="width: 800px;float:left;">
                    <p class="fs14 m-t-10">本次已交车辆服务的收支明细：</p>
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
                <div class="jingdu" style="width: 20%;padding: 50px 0 0 0;position: relative;height: 600px" >
                    <span class="fl fs14 pl20"><b>核实信息进度：</b></span>
                    <dl class="fl jd-dl m-t-10" style="width: 150px;">
                        <dd class="cur-jd fs14"><span></span>核对金额</dd>
                        <dt></dt>
                        <dd class="fs14"><span></span>办理手续</dd>
                        <dt></dt>
                        <dd class="fs14"><span></span>结算完成</dd>
                    </dl>
                    <div class="clear"></div>
                    <a href="#" class="juhuang tdu pl20" style="position:absolute;bottom: 0;">报错</a>

                </div>
                <div class="clear"></div>
                <p class="fs14" style="margin:15px 0 0 0">加信宝冻结与解冻保证金进程：</p>
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
                <p class="tac">
                	@if($order['pdi_calc_status']==0)
                  <a href="javascript:;" class="btn btn-s-md btn-danger" ms-on-click="pdi_agree_calc('{{$order_num}}','{{csrf_token()}}')">申请结算</a>
                	@elseif($order['pdi_calc_status']==1)
                <a href="javascript:;" class="btn btn-s-md btn-danger" >已申请结算</a>
                	@elseif($order['pdi_calc_status']==2)
                <a href="javascript:;" class="btn btn-s-md btn-danger" >已结算</a>
                	@endif
                </p>
                @if($order['pdi_calc_status']==0)
                <p class="tac fs14"><span class="xing">*</span><span>点击确定后，上方内容将先行结算。</span></p>
				@endif
				@if($calc_file>0)
                <p class="fs14"><b>结算文件使用数：</b>{{$calc_file_used}}；<b>剩余可用数：</b>{{$calc_file}}</p>
                @else
                <p class="fs14"><b>结算文件使用数：</b>{{$calc_file_used}}；<b>剩余可用数：</b>0，<span class="juhuang">请提交结算文件！</span></p>
                @endif
                <p class="fs14"><b>本次结算金额：</b>人民币3,000.00元</p>

                <p class="fs14"><b>本次结算金额：</b>人民币3,200.00元，已进入可提现资金池</p>
                <p class="fs14"><b>最新资金池可提现余额：</b><span class="juhuang">人民币12,000.00元</span></p>
                <hr class="dashed">
				<?php if (!empty($jiaoche['user_date_first'])): ?>
					<p class="fs14 m-t-10">客户补充的上牌信息请核实，如需修改请点击内容：</p>
				<form action="{{ url('dealer/pdisavecarattrinfo') }}" method="post">
                   <div style="width: 650px;float:left;">
                        <table class="tbl2">
                            <tbody>
                                <tr>
                                    <th class="tar p10"><label class="fs14"><b>上牌地区：</b></label></th>
                                    <th class="p10">
                                        <div class="btn-group m-r fl bts fn pdi-drop pdi-drop-warp">
                                          <button class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>{{$jiaoche['user_shangpai_area']}}</span>&nbsp;</span>
                                              <span class="caret"></span>
                                          </button>
                                          <div class="dropdown-menu dropdown-select area-tab-div">
                                          <?php 
                                          	$ss=explode(' ', $jiaoche['user_shangpai_area']);
                                          	if(count($ss) !=2 ){
                                          		$ss = array(0=>"",1=>"");
                                          	}
                                           ?>
                                              <input type="hidden" name="sheng" value="{{$ss[0]}}" />
                                              <input type="hidden" name="shi" value="{{$ss[1]}}"/>
                                              <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                              <dl class="dl">
                                                 <?php foreach ($sheng as $key => $value): ?>
                                                      <dd ms-on-click="selectProvince({{$value['area_id']}})">{{$value['area_name']}}</dd>
                                                    <?php endforeach ?>
                                                <div class="clear"></div>
                                              </dl>
                                              <dl class="dl" style="display: none;">
                                                <dd ms-repeat-city="citylist" ms-on-click="selectCity"><!--city.name--></dd> 
                                                <div class="clear"></div>
                                              </dl>
                                          </div>
                                        </div>
                                    </th>
                                </tr>
                          <tr>
                              <td class="p10 tar"><label class="fs14"><b>车辆用途：</b></label></td>
                              <td width="400" class="p10">
                                  <div class="btn-group m-r fl bts fn pdi-drop">
                                    <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                        <span class="dropdown-label"><span>{{$jiaoche['user_useway']}}</span>&nbsp;</span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-select">
                                        <input type="hidden" name="yongtu" value="{{$jiaoche['user_useway']}}" />
                                        <li ms-on-click="selectTime" class="active"><a><span>非营业个人客车</span></a></li>
                                        <li ms-on-click="selectTime"><a><span>非营业公司客车</span></a></li>
                                    </ul>
                                  </div>
                              </td>
                          </tr> 
                          
                          <tr>
                              <td class="p10 tar"><label class="fs14"><b>上牌（注册登记）车主名称：</b></label></td>
                              <td width="400" class="p10">
                                  <div class="btn-group m-r time-sl">
                                    <div class="form-group psr pdi-control">
                                      <input style="" name="reg_name" value="{{$jiaoche['user_regname']}}" type="text" placeholder="" class="form-control pdi-control">
                                      <span class="edit"></span>
                                    </div>
                                  </div>
                              </td>
                          </tr> 
                          <tr>
                              <td class="p10 tar"><label class="fs14"><b>牌照号码：</b></label></td>
                              <td width="400" class="p10">
                                              <!--苏-->
                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>{{$chepai[0]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[0]}}" />
                                                    <li  ms-repeat-item="areaSn" ms-on-click="selectTime"  ms-class="<!--item == '{{$chepai[0]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>
                                              <!--E-->
                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>{{$chepai[1]}}</span></span>
                                                    <span class="caret"></span>
                                                </button> 
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[1]}}"/>
                                                    <li ms-repeat-item="en" ms-on-click="selectTime" ms-class="<!--item == '{{$chepai[1]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul> 
                                              </div>
                                              
                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[2]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[2]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[2]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[3]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[3]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[3]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[4]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[4]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[4]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[5]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[5]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[5]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[6]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[6]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[6]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                          </td>
                          </tr> 
                          
                          
                      </tbody>
                  </table>

                   
              </div>
              <div class="jingdu" style="width:33%;padding-left:0px;">
                  <span class="fl fs14"><b>核实补充信息进度：</b></span>
                  <dl class="fl jd-dl">
                      <dd class="cur-jd"><span></span>客户已提交</dd>
                      <dt></dt>
                      <dd class="cur-jd"><span></span>售方已提交</dd>
                      <dt></dt>
                      <dd><span></span>华车平台核实中</dd>
                     
                  </dl>
                  <div class="clear"></div>

              </div>
              <div class="clear"></div>

              

              <div class="clear"></div>
              <div class="tac m-t-10 ">
                   <input type="submit" value="提交" class="btn btn-s-md btn-danger bt">
                  <a href="javascript:;" class="btn btn-s-md btn-danger oksure bt">已提交</a>
              </div>
          <?php else: ?>
          <!-- //如果客户车牌信息没有填写，且超时了，显示下面 -->
          @if($chaoshi < date('Y-m-d'))
			<div class="tac m-t-10 ">
                  <a href="javascript:;" class="btn btn-s-md btn-danger oksure btn-long">客户超时，售方反向提交</a>
              </div>

			<form action="{{ url('dealer/pdisavecarattrinfo') }}" method="post" name='item-form'>
              <hr class="dashed">

                <p class="fs14 m-t-10"></p>

                   <div style="width: 650px;float:left;">
                        <table class="tbl2">
                            <tbody>
                                <tr>
                                    <th class="tar p10"><label class="fs14"><b>上牌地区：</b></label></th>
                                    <th class="p10">
                                        <div class="btn-group m-r fl bts fn pdi-drop pdi-drop-warp">
                                          <button class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>{{$jiaoche['pdi_shangpai_area']}}</span>&nbsp;</span>
                                              <span class="caret"></span>
                                          </button>
                                          <div class="dropdown-menu dropdown-select area-tab-div">
                                          <?php 
                                          if(!empty($jiaoche['pdi_shangpai_area'])){
                                          	$ss=explode(' ', $jiaoche['pdi_shangpai_area']);
                                          }else{
                                          	$ss = array(0=>'',1=>'');
                                          }
                                          	
                                           ?>
                                              <input type="hidden" name="sheng" value="{{$ss['0']}}"/>
                                              <input type="hidden" name="shi" value="{{$ss['1']}}"/>
                                              <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                              <dl class="dl">
                                                <?php foreach ($sheng as $key => $value): ?>
                                                      <dd ms-on-click="selectProvince({{$value['area_id']}})">{{$value['area_name']}}</dd>
                                                    <?php endforeach ?>
                                                <div class="clear"></div>
                                              </dl>
                                              <dl class="dl" style="display: none;">
                                                <dd ms-repeat-city="citylist" ms-on-click="selectCity"><!--city.name--></dd> 
                                                <div class="clear"></div>
                                              </dl>
                                          </div>
                                        </div>
                                    </th>
                                </tr>
                          <tr>
                              <td class="p10 tar"><label class="fs14"><b>车辆用途：</b></label></td>
                              <td width="400" class="p10">
                                  <div class="btn-group m-r fl bts fn pdi-drop">
                                    <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                        <span class="dropdown-label"><span>非营业个人客车</span></span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-select">
                                        <input type="hidden" name="yongtu" value="{{$car['yongtu'] or '非营业个人客车'}}" />
                                        <li ms-on-click="selectTime" class="active"><a><span>非营业个人客车</span></a></li>
                                       
                                        <li ms-on-click="selectTime"><a><span>非营业公司客车</span></a></li>
                                    </ul>
                                  </div>
                              </td>
                          </tr> 
                          
                          <tr>
                              <td class="p10 tar"><label class="fs14"><b>上牌（注册登记）车主名称：</b></label></td>
                              <td width="400" class="p10">
                                  <div class="btn-group m-r time-sl">
                                    <div class="form-group psr pdi-control">
                                      <input style="" type="text" name="reg_name"  placeholder="" class="form-control pdi-control" value="{{$jiaoche['pdi_regname']}}">
                                      <span class="edit"></span>
                                    </div>
                                  </div>
                              </td>
                          </tr> 
                          <tr>
                              <td class="p10 tar"><label class="fs14"><b>牌照号码：</b></label></td>
                              <td width="400" class="p10">
                                              <!--苏-->
                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>{{$chepai[0]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[0]}}" />
                                                    <li  ms-repeat-item="areaSn" ms-on-click="selectTime"  ms-class="<!--item == '{{$chepai[0]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>
                                              <!--E-->
                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>{{$chepai[1]}}</span></span>
                                                    <span class="caret"></span>
                                                </button> 
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[1]}}"/>
                                                    <li ms-repeat-item="en" ms-on-click="selectTime" ms-class="<!--item == '{{$chepai[1]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul> 
                                              </div>
                                              
                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[2]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[2]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[2]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[3]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[3]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[3]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[4]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[4]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[4]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[5]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[5]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[5]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                              <div class="btn-group m-r fl bts fn">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                                    <span class="dropdown-label"><span>{{$chepai[6]}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chepai">
                                                    <input type="hidden" name="chepai[]" value="{{$chepai[6]}}"/>
                                                    <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[6]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                                </ul>
                                              </div>

                                          </td>
                          </tr> 
                          
                    
                 
                          
                      </tbody>
                  </table>

                   
              </div>
              <div class="jingdu" style="width:33%;padding-left:0px;">
                  <span class="fl fs14"><b>核实补充信息进度：</b></span>
                  <dl class="fl jd-dl">
                      <dd class="cur-jd"><span></span>客户已提交</dd>
                      <dt></dt>
                      <dd class="cur-jd"><span></span>售方已提交</dd>
                      <dt></dt>
                      <dd><span></span>华车平台核实中</dd>
                     
                  </dl>
                  <div class="clear"></div>

              </div>
              <div class="clear"></div>
			{{--
              <p class="fs14">
                  <span class="fl">因上述车辆信息与经销商存在差异，请提交行驶证等照片供平台核实：</span>
                  <span class="blue fl "></span>
                  <span class="juhuang tdu cp fl ml10" ms-on-click="upload">上传</span>
                  <input type="file" name="" ms-on-change="change" id="hfUpload" class="hide" value="">
                  <input type="hidden" name="" id="hfFile">
              </p>
			--}}
              <div class="clear"></div>
              <div class="tac m-t-10 ">
              @if($order['cart_sub_status'] == 502 || $order['cart_sub_status'] == 505)
              	<input type="button" ms-on-click='submit_tiche_end' value="提交" class="btn btn-s-md btn-danger bt">
              @else
              	<a href="javascript:;" class="btn btn-s-md btn-danger oksure bt">已提交</a>
              @endif
               
                  
              </div>
              @endif
		<?php endif ?>
		
		<input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$order['id']}}" name="id" >
            <input type="hidden" value="1" name="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="member_type" value="jxs">
         </form>       

              
              <div class="tac m-t-10 "></div>
              <!-- 国家节能补贴start -->
				@if($order_attr['butie']==1)
	                <div class="fs14 m-t-10 clear">
	                @if($jiaoche['pdi_butie_fafang']=='')
	                    <span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['pdi_butie_date']}}    </span>
	                    <a ms-on-click="pdibutie" href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">发放补贴</a>
	                @else
	                	<span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['pdi_butie_date']}}    </span>
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
      				<!-- 国家节能补贴end -->

                    
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection