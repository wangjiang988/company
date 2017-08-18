@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                @if($baojia['bj_status']==1)
					@if($baojia['bj_start_time']>time())
					<!-- //等待生效 start -->
                	<h2 class="title weighttitle">等待生效</h2>
                	<div class="content-wapper lh30">
	                	<div class="row">
	                		<div class="col-sm-6 col-xs-6"><span>报价编号：{{$baojia['bj_serial']}}</span></div>
	                		<div class="col-sm-6 col-xs-6">
		                		<span>操作：</span>   
		                		<a href="javascript:baojiaExcute('copy',{{$baojia['bj_id']}})" class="juhuang tdu">复制</a> 
                        		<a href="javascript:;" class="juhuang tdu ml10 stop" data-id="{{$baojia['bj_id']}}">暂停</a> 
                       			 <a href="javascript:;" class="juhuang tdu ml10 susp" data-id="{{$baojia['bj_id']}}">终止</a> 
	                		</div>
	                		<div class="col-sm-6 col-xs-6"><span>报价时间：{{$baojia['bj_create_time']}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>设置生效时间：{{date("Y-m-d H:i:s",$baojia['bj_start_time'])}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>报价人：{{session('user.seller_name')}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>生效倒计时：{{diffBetweenTwoDays(date("Y-m-d H:i:s",$baojia['bj_start_time']),4)}}</span></div>
	                		<div class="clear"></div>
	                	</div>
                	</div>
                	<!-- //等待生效 end -->
                	
                	@else
                	<!-- //正在报价 start-->
                	<h2 class="title weighttitle">正在报价</h2>
                	<div class="content-wapper lh30">
	                	<div class="row">
	                		<div class="col-sm-6 col-xs-6"><span>报价编号：{{$baojia['bj_serial']}}</span></div>
	                		<div class="col-sm-6 col-xs-6">
		                		<span>操作：</span>   
		                		<a href="javascript:baojiaExcute('copy',{{$baojia['bj_id']}})" class="juhuang tdu">复制</a> 
                        		<a href="javascript:;" class="juhuang tdu ml10 stop" data-id="{{$baojia['bj_id']}}">暂停</a> 
                       			 <a href="javascript:;" class="juhuang tdu ml10 susp" data-id="{{$baojia['bj_id']}}">终止</a> 
	                		</div>
	                		<div class="col-sm-6 col-xs-6"><span>报价时间：{{$baojia['bj_create_time']}} </span></div>
	                		<div class="col-sm-6 col-xs-6"><span>生效时间：{{date("Y-m-d H:i:s",$baojia['bj_start_time'])}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>报价人：{{session('user.seller_name')}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>结束设置时间：{{date("Y-m-d H:i:s",$baojia['bj_end_time'])}}</span></div>
	                		<div class="clear"></div>
	                	</div>
                	</div>
                	<!-- //正在报价 end-->
                	@endif
 				@elseif($baojia['bj_status']==2)
 				<!-- //暂时下架 start-->
 					<h2 class="title weighttitle">暂时下架</h2>
                	<div class="content-wapper lh30">
	                	<div class="row">
	                		<div class="col-sm-6 col-xs-6"><span>报价编号：{{$baojia['bj_serial']}}</span></div>
	                		<div class="col-sm-6 col-xs-6">
		                		<span>操作：</span>   
		                		 <a href="javascript:baojiaExcute('copy',{{$baojia['bj_id']}})" class="juhuang tdu">复制</a> 
                         <a href="javascript:;" class="juhuang tdu ml10 renew" data-id="{{$baojia['bj_id']}}">恢复</a> 
                       			 <a href="javascript:;" class="juhuang tdu ml10 susp" data-id="{{$baojia['bj_id']}}">终止</a> 
	                		</div>
	                		<div class="col-sm-6 col-xs-6"><span>报价时间：{{$baojia['bj_create_time']}} </span></div>
	                		<div class="col-sm-6 col-xs-6"><span>暂时下架时间：{{$baojia['bj_update_time']}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>报价人：{{session('user.seller_name')}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>下架原因：{{$baojia['bj_reason']}}</span></div>
	                		<div class="clear"></div>
	                	</div>
                	</div>
                <!-- //暂时下架 end-->
 				@else
 				<!-- //失效报价 start-->
 					<h2 class="title weighttitle">失效报价</h2>
                	<div class="content-wapper lh30">
	                	<div class="row">
	                		<div class="col-sm-6 col-xs-6"><span>报价编号：{{$baojia['bj_serial']}}</span></div>
	                		<div class="col-sm-6 col-xs-6">
		                		<span>操作：</span>   
		                		<a href="javascript:baojiaExcute('copy',{{$baojia['bj_id']}})" class="juhuang tdu">复制</a> 
	                		</div>
	                		<div class="col-sm-6 col-xs-6"><span>报价时间：{{$baojia['bj_create_time']}} </span></div>
	                		<div class="col-sm-6 col-xs-6"><span>失效时间：{{$baojia['bj_update_time']}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>报价人：{{session('user.seller_name')}}</span></div>
	                		<div class="col-sm-6 col-xs-6"><span>失效原因：{{$baojia['bj_reason']}}</span><a href="#" class="juhuang tdu"></a></div>
	                		<div class="clear"></div>
	                	</div>
                	</div>
 				<!-- //失效报价 start-->
 				@endif
 

                    <div class="custom-offer-step-tab">
                        <ul>
                          <li class="first"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/1">车型价格</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/2">车况说明</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/3">选装精品</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/4">首年保险</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/5">收费标准</a></li>
                          <li class="last cur"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/6">其他事项</a></li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="newOfferings">

                       <div class="content-wapper border">
                           <div class="box">
                               <p><span class="blue weight">一、补贴情况</span></p>

                               <div class="tbl-list-tool-panle valite-1">
                                  @if($expandInfo['bt_status']==0)
                                  @elseif($expandInfo['bt_status']==1)     
                                  <p class="ml20"><b>国家节能补贴：</b>
                                  <span class="ml10">提供</span>
                                  </p>
                                  @if($expandInfo['bt_work_day']>0)
                                  <div class="ml128">
                                      <p>
                                          <span>经销商代办上牌的，交车上牌时当场兑现；由客户本人上牌的，上牌资料齐全后，</span>
                                      </p>
                                      <p>
                                          <span>经销商垫付给客户，时限</span>
                                          <span>{{$expandInfo['bt_work_day']}}</span>
                                          <span>个工作日。</span>
                                      </p>
                                  </div>
                                  @endif
                                  @if($expandInfo['bt_work_month']>0)
                                  <div class="ml128">
                                      <p>
                                          <span>上牌资料齐全后，经销商将所有资料交给汽车厂商，厂商直接付给客户，或者（厂商付经销商再由）</span>
                                          <span>经销商垫付给客户，时限</span>
                                          <span>{{$expandInfo['bt_work_month']}}</span>
                                          <span>个月。</span>
                                      </p>
                                  </div>
                                  @endif
                                  
                               </div>
                               @endif
                                  <div class="tbl-list-tool-panle">
                                  @if($baojia['bj_zf_butie']>0)
                                  <p class="ml20"><b>地方政府置换补贴：</b>
                                      <span>可为客户提供协助</span>
                                  </p>  
                                   @endif        
                                   @if($baojia['bj_cj_butie']>0)                        
                                  <p class="ml20"><b>厂家或经销商置换补贴：</b>
                                      <span>有</span>
                                  </p>  
                                  @endif                               
                               		</div>
                               </div>
                           <div class="box">
                               <p><span class="blue weight">二、移交内容</span></p>
                               <div class="tbl-list-tool-panle">
                                  <p class="ml20"><b>随车工具：</b>{{implode('、',$suicheInfo['随车工具'])}}</p>
                                  <p class="ml20"><b>随车移交文件：</b>{{implode('、',$suicheInfo['文件资料'])}}</p>
                               </div>
                           </div>
                           <div class="box">
                               <p><span class="blue weight">三、生效设置</span></p>
                               
                               <label class="radio-label ml50 noweight">
                                
                                 <span>
                                 <?php 
                                 if(date("Y-m-d",$baojia['bj_end_time'])=='2099-12-31'){
                                 	echo '立即生效，始终有效';
                                 	$fulltime = 1;
                                 }else{
                                 	echo '按设置生效及有效时间';
                                 	$fulltime = 0;
                                 }
                                 ?>
                                   <span class="smip-gray">（设置工作时间之外、资金池可提现余额不足、主动下架操作、被客户购买后库存为
                                      <span class="">零，则报价信息将自动下架）</span>
                                   </span>
                                 </span>
                               </label>
                               @if($fulltime==0)
                               <div class="no-offer ml50">
                                   <p>开始时间： {{date("Y-m-d H:i",$baojia['bj_start_time'])}}</p>
                                   <p>结束时间： {{date("Y-m-d H:i",$baojia['bj_end_time'])}}</p>
                                   <div class="clear"></div>
                               </div>
                               @endif
                           </div>

                           <div class="box">
                               <div class="mt20">
                                  <span class="blue weight">四、台数设置</span>

                               </div>
                               <div class="tbl-list-tool-panle">
                                  <p class="ml50 mt10"><span>台数：{{$baojia['bj_num']}}</span></p>
                               </div>
                           </div>
                           
                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                    
                            
                       </div> 
             
                    </form>
                    <div class="m-t-200"></div>
                   <div class="m-t-200"></div>
                    <div id="DelWin" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <form action="SolutionDel">
                            <div class="popup-content">
                                 <div class="m-t-10"></div>
                                 <p class="fs14 pd msg" id="msg" style="text-align:center">确定将该报价{{$baojia['bj_serial']}}终止吗？</p>
                                 <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                            </div>
                        </form>
                    </div>
                  </div>
                  <div id="StopWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd msg" id="msg" style="text-align:center">确定将报价{{$baojia['bj_serial']}}暂时下架吗</p>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </form>
                      </div>
                   </div>
                  <div id="RenewWin" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <form action="SolutionDel">
                            <div class="popup-content">
                                 <div class="m-t-10"></div>
                                 <p class="fs14 pd msg" style="text-align:center">确定立即恢复报价{{$baojia['bj_serial']}}生效吗？</p>
                                 <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>               
                
@endsection
@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin",
                   "module/custom/custom.admin.common.jquery",
                   "module/custom/custom.admin.charge.jquery", 
                   "module/custom/custom.admin.offer-new-unfinished.jquery",
                    "module/common/common", "bt"]);
	</script>
@endsection