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
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" target="_blank" class="juhuang tdu">查看订单总详情</a></p>
                                           
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
                
                <?php if ($is_defend): ?>
                  <div class="step-n step-n-3-c"></div>
                <?php else: ?>
                  <div class="step-n step-n-2-c"></div>
                <?php endif ?>   
                <div class="tbl m-t-10">
                    <p class="fs14  "><b>客户已向华车平台反映下列问题：</b></p>
                    <ul class="bxlist" style="margin-top: 10px">
                    <?php foreach ($problem as $key => $value): ?>
                      <li style="width: 30%">
                            <p><input checked type="checkbox" disabled="disabled" class="radio"></p>
                            <dl>
                                <dt>{{$value}}</dt>
                                <div class="clear"></div>
                            </dl>
                            <div class="clear"></div>
                        </li>
                    <?php endforeach ?>
                       
                        <div class="clear"></div>
                    </ul>
                   
                    <div class="clear"></div>
                    <textarea class="txtarea-s " readonly="readonly" placeholder="">{{$content}}</textarea>
                    
                    
                    <p class="fs14 tdu  m-t-10"><b>相关证据</b></p>
                    <div class="m-t-10">
                        <span class="blue fl ">
                        @foreach($evidence as $k =>$v)
                        <span class="file-prev ml10"><a href="../../upload/evidence/{{$v['urls']}}" target="_blank">{{$v['urls']}}</a></span>
                        @endforeach
                        </span>
                        
                        <div class="clear"></div>
                    </div>
                    
                    <br>
		            <div class="clear"></div>
		            @if($is_defend == 0)
		            <form action="" method="post" enctype="multipart/form-data">
                   <p class="fs14   m-t-10"><b>申辩内容：</b></p>                 
                    <textarea class="txtarea-s  m-t-10" placeholder="请输入" name="content" style="width:80%"></textarea>
                	<p class="fs14 tdu  m-t-10"><b>申辩证据材料：</b></p>
                    <div class="m-t-10">
                        <span class="blue fl "></span>
                        <span class="juhuang tdu cp fl ml10" ms-on-click="uploadForMuliteFileInput">上传</span>
                        
                        <input type="hidden" name="" id="hfFile">
                        <div class="clear"></div>
                    </div>
                    <input type="submit" name="submit" value="提交" class="btn btn-s-md btn-danger">
                	<input type="hidden" name="order_num" value="{{ $order_num}}">
                	<input type="hidden" name="dispute_id" value="{{ $dispute_id}}">
                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                	<input type="hidden" name="id"  value="{{$order['id']}}">
                	<input type="hidden" name="act"  value="defendfirst">
                	</form>
                	@else
                	<p class="fs14   m-t-10"><b>申辩内容：</b></p>                 
                    <textarea class="txtarea-s  m-t-10" placeholder="请输入" name="content" style="width:80%">{{$defend_content}}</textarea>
                	<p class="fs14 tdu  m-t-10"><b>申辩证据材料：</b></p>
                    <div class="m-t-10">
                        <span class="blue fl ">
                        @foreach($zhengju as $k =>$v)
                        <span class="file-prev ml10"><a href="../../upload/evidence/{{$v['urls']}}" target="_blank">{{$v['urls']}}</a></span>
                        @endforeach
                        </span>
                        
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                	<p class="m-t-10">
                	<a href="javascript:;" class="btn btn-s-md btn-danger fl  bt oksure ml20">已提交</a>
                	</p>
                	@endif
                <div class="clear"></div>
                <hr class="dashed">
		        <div class="m-t-10"></div>
                    
                </div>
                 <div class="clear"></div>
                <div class="m-t-10"></div>
                <div class="time tac m-t-10">
                    <div class="jishi jishi2">
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">:</span>
                        <span>0</span>
                        <span>0</span>
                    </div>
                </div>

                <div class="clear"></div>

                <div class="m-t-10"></div>
                @if(!empty($defend['resupply']))
                	@if(empty($defend['resupply_evidence']))
		                <h2 class="title">平台核实   </h2>
		                <form action="" method="post" enctype="multipart/form-data">
		                <p class="fs14 bl5"><b class="ml10">请补充材料清楚说明“<span class="juhuang">{{$defend['resupply']}}</span>”</b></p>
		                <p class="fs14 tdu  m-t-10"><b>证据材料：</b></p>
		                <div class="m-t-10">
		                        <span class="blue fl "></span>
		                        <span class="juhuang tdu cp fl ml10" ms-on-click="uploadForMuliteFileInput">上传</span>
		                        
		                        <input type="hidden" name="" id="hfFile">
		                        <div class="clear"></div>
		                </div>
		                <div class="clear"></div>              
		                <input type="submit" name="submit" value="提交" class="btn btn-s-md btn-danger">
		                  
		                <p class="fs14 clear m-t-10 fn">
		                    <input type="checkbox"><span class="fn">本人愿对补充材料的真实性承担一切责任！</span>
		                </p>
		                <input type="hidden" name="order_num" value="{{ $order_num}}">
		                <input type="hidden" name="defend_id" value="{{ $defend['id']}}">
		                <input type="hidden" name="_token" value="{{ csrf_token() }}">
		                <input type="hidden" name="id"  value="{{$order['id']}}">
		                <input type="hidden" name="act"  value="defendfirst_resupply">
						</form>
					@else
						<h2 class="title">平台核实   </h2>
						<div class="fs14 bl5">
                			<b class="ml10">提交补充说明: {{$defend['resupply']}}</b>
            			</div>
						<p class="fs14 tdu  m-t-10"><b>证据材料：</b></p>
		                <div class="m-t-10">
		                        <span class="blue fl ">
		                        <?php $defend['resupply_evidence'] = unserialize($defend['resupply_evidence']);?>
		                        @foreach($defend['resupply_evidence'] as $k =>$v)
		                        	<span class="file-prev ml10"><a href="../../upload/evidence/{{$v}}" target="_blank">{{$v}}</a></span>
		                        @endforeach
		                        </span>
		                        
		                        <div class="clear"></div>
		                </div>
		                <div class="clear"></div>
		                <p class="m-t-10">              
		                <a href="javascript:;" class="btn btn-s-md btn-danger fl  bt oksure ml20">已提交</a>
						</p>
						<div class="clear"></div>
					@endif
				@endif

                <hr class="dashed-2">
                @if(!empty($defend['resupply']) || !empty($mediate))
                <div class="fs14 bl5">
                    <b class="ml10">如与客户达成和解，请告知华车平台：</b>
                </div>
                
                <div>
                	@if(empty($defend['defend_hejie']))
                	<form action="{{url('dealer/defend')}}/{{$order_num}}" method="post">
	                <div>
	                    <textarea class="form-control pdi-control m-t-10 w70" cols="66" rows="3" name="content2"></textarea>
	
	                    <p class="m-t-10">
	                       <input type="submit" class="btn btn-s-md btn-danger fl bt" value="提交">
	                    </p>
	                    <div class="clear"></div>
	                </div>
					<input type="hidden" value="{{$order['order_num']}}" name="order_num">
	            	<input type="hidden" value="{{$defend['id']}}" name="defend_id" >
	            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	            	<input type="hidden" name="act"  value="hejie">
	            	<input type="hidden" name="id"  value="{{$order['id']}}">
					</form>
					
                    
					@else
					<textarea class="form-control pdi-control m-t-10 w70" cols="66" rows="3" name="content2"  readonly>{{$defend['defend_hejie']}}</textarea>
					<p class="m-t-10">
					<a href="javascript:;" class="btn btn-s-md btn-danger fl  bt oksure ml20">已提交</a>
					</p>
					@endif
   
                    <div class="clear"></div>
                </div>
		
				
                <hr class="dashed-2">
				@if(!empty($mediate['content']))
                <div class="fs14 bl5" style="height: 19px;">
                    <b class="ml10 fl">华车平台调解建议：</b>
                    <input placeholder="经销商更换车辆" style="width: 600px;margin-top: -3px;" type="text" placeholder="" class="form-control pdi-control fl" readonly value="{{$mediate['content']}}">
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <p class="fs14">
                    	等待客户确认中
                </p>
                @endif

                
				@endif
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>




            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection