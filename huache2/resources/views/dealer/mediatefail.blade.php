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
                    <small class="juhuang">正在交车</small>
                    <i></i>
                    <small>核实信息</small>
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
                                                  </p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>订单类别：</b>
                                            <?php if ($bj['bj_producetime']): ?>
                                             		 现车订单
                                            <?php else: ?>
                                              		远期订单
                                            <?php endif ?>
                                            </p><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>品牌：</b>{{$bj['brand'][0]}}</p></td>
                                            <td><p><b>车系：</b>{{$bj['brand'][1]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车型规格：</b>{{$bj['brand'][2]}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>座位数：</b>{{ $bj['seat_num'] }}</p></td>
                                            <td><p><b>厂商指导价：</b>{{ $bj['zhidaojia'] }} 元</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆类别：</b></p></td>
                                            <td><p><b>数量：</b>{{ $bj['bj_num']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>经销商名称：</b>{{$jxs['d_name']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>营业地点：</b>{{$jxs['d_jc_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>交车地点：</b>{{$jxs['d_jc_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>归属地区：</b>{{$jxs['d_shi']}}</p></td>
                                            <td>
                                                <div class="psr">
                                                  <b>销售区域：</b>
                                                  <span class="">{{$bj['area']}}</span>
                                                  <span class="sj" ms-click="hideTm"  ms-mouseout="hideTm()"  ms-mouseover="displayTm()" >
                                                     <span class="fs12">更多</span>
                                                  </span>
                                                  <div class="tm loca-c page-loca" >
                                                    <input type="hidden" name="page-loca">
                                                    {{$bj['area']}}
                                                   
                                                  </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>经销商裸车开票价格：</b>{{ $bj['bj_lckp_price'] }} 元</p></td>
                                            <td><p><b>付款方式：</b>{{ $bj['payTitle'] }}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>客户买车定金：</b>{{$guarantee}} 元</p></td>
                                            <td><p><b>我的服务费：</b>{{$bj_agent_service_price}} 元</p></td>
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
                                        <div class="times times2" style="height:auto">
                                        <p class="status tac status2"><b>订单状态：{{getStatusNotice($order['cart_sub_status'])}}</b></p>
                                        <p class="tac">（客户已提交交车信息）</p>
                                        <p class="tac">约定交车时间：{{$jc_date['date']}}</p>
                                    </div>
                                        <p class="tac m-t-10"><a href="#" class="juhuang tdu">查看订单总详情</a></p>
                                           
                                        <hr class="dashed mt20">
                                        <p class="pl20 lh25"><b>客户会员号： </b>{{formatNum($order['buy_id'],1)}} </p>
                                        <p class="pl20 lh25"><b>客户姓名：   </b>{{mb_substr($buyer['member_truename'],0,1)}}** </p>
                                        <p class="pl20 lh25"><b>客户称呼：   </b>{{ getSex($buyer['member_sex'])}} </p>
                                        <p class="pl20 lh25"><b>客户电话：   </b>{{ changeMobile($buyer['member_mobile'])}} </p>
                                        <p class="pl20 lh25"><b>客户承诺上牌地区：   </b>{{$shangpai_area}}</p>
                                        <p class="pl20 lh25"><b>客户车辆用途：   </b>
                                        <?php if ($order['buytype']): ?>
											个人用车
											  <?php else: ?>
											公司用车
											<?php endif ?>
                                         </p>
                                        <p class="pl20 pt">
                                          <b>上牌车主身份类别： </b>
                                          <span class="fr" style="width: 165px;color:#8e8d8d;text-align: left;">{{$order['shenfen']}}</span> 
                                          <span class="clear"></span>
                                        </p>
                                        <p class="clear"></p>
                                        <p class="pl20 lh25"><b>客户买车担保金（已存加信宝）：   </b> </p>
                                               
                                           
                                       
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
                                            <td width="33%"><p><b>基本配置：</b><a href="{{ $bj['barnd_info']['official_url'] }}" target="_blank">官网参数</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{ $bj['guobieTitle'] }}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{ $bj['paifangTitle'] }}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{ $bj['body_color'] }}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{ $bj['interior_color'] }}</p></td>
                                            <td width="33%"><p><b>出厂年月：</b>{{ $bj['bj_producetime']?$bj['bj_producetime']:''}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><p><b>行驶里程：</b>{{ $bj['bj_licheng'] }}</p></td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆识别代码（VIN码）：</b>{{$order_attr['vin']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>发动机号：  </b>{{$order_attr['engine_no']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>实车出厂年月：</b>{{$order_attr['production_date']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>实车行驶里程：  </b>{{$order_attr['mileage']}} 公里</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <p>已装原厂选装精品：</p>
                                                <?php if ($bj['bj_producetime']): 
                                                    $fee=0.00;
                                                ?>
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
                                                      foreach ($xzj as $key =>$value) {
                                                          if(!$value['xzj_yc'] || !$value['is_install']) continue;
                                                   ?> 
                                                   <tr>
                                                       <td><?php echo $value['xzj_title']; ?></td>
                                                       <td><?php echo $value['xzj_model'].'/'.$value['beizhu'] ; ?></td>
                                                       <td><?php echo $value['xzj_guide_price']; ?></td>
                                                       <td><?php echo $value['num']; ?></td>
                                                       <td><?php echo $value['xzj_guide_price']*$value['num']; ?></td>
                                                   </tr>
                                                   <?php  
                                                      $fee+=$value['fee'];
                                                      $count+=$value['xzj_guide_price']*$value['num'];
                                                     } ?> 
                                                      
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang"><?php echo $count; ?> 元</span></h2>
                                                <?php endif ?>

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
                                            <td width="33%"><p><b>基本配置：</b><a href="{{ $bj['barnd_info']['official_url'] }}" target="_blank">官网参数</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>{{ $bj['guobieTitle'] }}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>{{ $bj['paifangTitle'] }}</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{ $bj['body_color'] }}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{ $bj['interior_color'] }}</p></td>
                                            <td width="33%"><p><b>交车周期：    </b>{{ $bj['bj_jc_period'] }}个月 </p></td>
                                        </tr>
                                       
                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>车辆识别代码（VIN码）：</b>{{$order_attr['vin']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>发动机号：  </b>{{$order_attr['engine_no']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>实车出厂年月：</b>{{$order_attr['production_date']}}</p></td>
                                            <td width="50%" colspan="2"><p class="ml150"><b>实车行驶里程：  </b>{{$order_attr['mileage']}} 公里</p></td>
                                        </tr>
                                    </table>                                    
                                </td>
                            </tr> 
                          
                        </tbody>
                    </table>
					<?php endif ?>
                    
                </div>
                @if($disputeType=="user")        
                <div class="step-n step-n-4-d-2"></div>
                @else
                <div class="step-n step-n-4-d-1"></div>
                @endif
               

                <div class="m-t-10"></div>
                <h2 class="title">争议处理   </h2>
                <table class="tbl w70 fl">
                    <tbody>
                        <tr>
                            <td width="150"><p class="tal fs14"><b>争议内容</b></p></td>
                            <td><p class="tal fs14">{{$dispute['content']}}</p></td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>客户提交内容</b></p></td>
                            <td><p class="tal fs14">
                              @if(!empty($dispute['problem']))
                                {{implode(' , ',unserialize($dispute['problem']))}}
                              @endif
                            </p></td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>客户证据材料</b></p></td>
                            <td>
                                <p class="tal fs14">
                                    <span class="file-prev ml10">
                                     @if(count($evidence)>0)
                                        	@foreach($evidence as $k=>$v)
                                        		<a href="/upload/evidence/{{$v['urls']}}" target="_bank">{{$v['urls']}}</a><br>
                                        	@endforeach
                                        @endif
                                    
                                    </span>
                                </p>
                            </td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>售方提交内容</b></p></td>
                            <td><p class="tal fs14">{{$defend['content']}}</p></td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>售方证据材料</b></p></td>
                            <td>
                                <p class="tal fs14">
                                    <span class="file-prev ml10">
                                     	@if(count($evidence_defend)>0)
                                        	@foreach($evidence_defend as $k=>$v)
                                        		<a href="/upload/evidence/{{$v['urls']}}" target="_bank">{{$v['urls']}}</a><br>
                                        	@endforeach
                                        @endif
                                    
                                    </span>
                                </p>
                            </td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>华车平台判定依据</b></p></td>
                            <td><p class="tal fs14">{{$mediate['breaker_content']}}</p></td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>华车平台判定结论</b></p></td>
                            <td><p class="tal fs14">
                            		<?php 
                                		if($mediate['breaker']==1){
                                			echo '售方违约';
                                		}elseif($mediate['breaker']==2){
                                			echo '客户违约';
                                		}elseif($mediate['breaker']==3){
                                			echo '不违约';
                                		}
                                			
                                	?><br>     
                                	 时间：{{$mediate['breaker_date']}}</p></td>
                        </tr> 
                        <tr>
                            <td width="150"><p class="tal fs14"><b>订单操作</b></p></td>
                            <td><p class="tal fs14">订单终止</p></td>
                        </tr> 
                    </tbody>
                </table>
                <div class="clear"></div>
				<?php 
					$calc_money = 0;
				?>
                <h2 class="title">结算信息   </h2>
                <div style="width: 800px;float:left;">
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
                            <?php 
                            if(!empty($mediate['breaker_excute'])){
                            	$breaker_excute = unserialize($mediate['breaker_excute']);
                            	if($dispute['member_id'] == $order['buy_id']){
                            		$excute = $breaker_excute['defend'];
                            	}else{
                            		$excute = $breaker_excute['dispute'];
                            	}
                            }else{
                            	$excute = array();
                            }
                            
                            if(count($excute)>0){
                            	foreach($excute as $k=>$v){	
                            		$calc_money+=$v['money'];
                            
                            ?>
                            <tr>
                                  <td><p class="tal fs14 weight">{{$v['title']}}</p></td>
                                  <td width="">
                                      <span>人民币{{$v['money']}} 元</span>
                                  </td>
                                  <td class="tac">
                                      <span>已执行</span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：客户未在约定上牌地区上牌</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr> 
                            <?php 
                            	}
                            
                            }
                            ?>
                              {{--
                              <tr>
                                  <td><p class="tal fs14 weight">华车平台损失赔偿</p></td>
                                  <td width="">
                                      <span>—人民币 4,000.00 元</span>
                                  </td>
                                  <td class="tac">
                                      <span>已确认</span>
                                  </td>
                                  <td width="">
                                      <p class="fs14">原因：售方原因导致订单终止</p>
                                      <p class="fs14">确认时间：2015年12月19日  8：36：05</p>
                                      <p class="fs14">执行时间：2015年12月19日  8：36：05</p>
                                  </td>
                              </tr>
                              --}} 
                              <tr>
                                  <td><p class="tal fs14 weight">实际收入</p></td>
                                  <td width="">
                                      <span>人民币 {{$calc_money}} 元</span>
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
                    <p class="fs14">注：华车平台损失 = 华车服务费人民币5,000.00元 — 经销商代理的服务费人民币2,000.00元</p>
                    <p class="fs14"><b>加信宝冻结与解冻保证金进程：</b></p>

                    <table class="tbl">
                      <tr>
                          <th><span class="fs14">项目</span></th>
                          <th>
                              <span class="fs14">金额</span>
                          </th>
                          <th>
                              <span class="fs14">冻结时间</span>
                          </th>
                          <th>
                              <span class="fs14">解冻时间</span>
                          </th>
                          <th>
                              <span class="fs14">去向</span>
                          </th>
                      </tr> 
                      <tr>
                          <td><span class="fs14">歉意金 </span></td>
                          <td>
                              <span class="fs14">人民币 499.00 元</span>
                          </td>
                          <td>
                              <span class="fs14">2015年12月18日 15:18:08</span>
                          </td>
                          <td>
                              <span class="fs14">2015年12月18日 15:18:08</span>
                          </td>
                          <td>
                              <span class="fs14">赔偿客户</span>
                          </td>
                      </tr> 
                      <tr>
                          <td><span class="fs14">客户买车担保金利息 </span></td>
                          <td>
                              <span class="fs14">人民币 499.00 元</span>
                          </td>
                          <td>
                              <span class="fs14">2015年12月18日 15:18:08</span>
                          </td>
                          <td>
                              <span class="fs14">2015年12月18日 15:18:08</span>
                          </td>
                          <td>
                              <span class="fs14">赔偿客户</span>
                          </td>
                      </tr> 
                    </table>
                    <p class="fs14"><span class="xing">*</span>客户买车担保金：6，000.00元，2015年12月18日 ~ 2015年12月28日（10天）</p>
                    <p class="fs14">
                        <b class="fl">结算文件可用数：</b>
                        <span class="fl">{{$calc_file}}份</span>
                    @if($order['pdi_calc_status']==0)
                  <a href="javascript:;" class="btn btn-s-md btn-danger" ms-on-click="pdi_agree_calc('{{$order_num}}','{{csrf_token()}}')">申请结算</a>
                	@elseif($order['pdi_calc_status']==1)
                <a href="javascript:;" class="btn btn-s-md btn-danger" >已申请结算</a>
                	@elseif($order['pdi_calc_status']==2)
                <a href="javascript:;" class="btn btn-s-md btn-danger" >已结算</a>
                	@endif
                        <a href="#" class="fl tdu juhuang">报错</a>
                        <div class="clear"></div>
                    </p>
                    <p class="fs14"><b>本次结算金额：</b>人民币3,000.00元，正在办理提现手续</p>
                    <p class="fs14"><b>本次结算金额：</b>人民币3,000.00元，已进入可提现资金池</p>
                    <p class="fs14"><b>最新资金池可提现余额：</b><span class="juhuang">人民币12,000.00元</span></p>

                    <div class="clear"></div>

                </div>
                <div class="clear"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>

                <div class="zm-all">
                    <div class="dialog dialog-access">
                        <div class="dialog-title"><span>提示</span><i ms-on-click="closeDialog" class="dialog-close"></i></div>
                        <div class="dialog-c">
                            <p class="fs16">结算文件不足，请补充！</p>
                            <a href="javascript:;" ms-on-click="accesssure" class="btn btn-s-md btn-danger bt sure">确定</a>
                        </div>
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