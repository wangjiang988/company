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
                          <li class="cur"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/2">车况说明</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/3">选装精品</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/4">首年保险</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/5">收费标准</a></li>
                          <li class="last"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/6">其他事项</a></li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="newOfferings">
                       <div class="content-wapper border">
                       		<!-- 非现车 -->
                           @if($baojia['bj_is_xianche']==1)
                            <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tar pl0">
                                      <label>车身颜色：</label>
                                   </td>
                                   <td class="tal">
                                      <span>{{@$carinfoArray['body_color'][$carAttr['body_color']]}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar">
                                      <label>内饰颜色：</label>
                                   </td>
                                   <td class="tal">
                                      <span>{{@$carinfoArray['interior_color'][$carAttr['interior_color']]}}</span>
                                   </td>
                               </tr>
                               
                               <tr>
                                   <td class="tar">
                                      <label>出厂年月：</label>
                                   </td>
                                   <td class="tal">
                                     <span>{{@$bj_chuchang[0]}} 年 </span>
                                     <span>{{@$bj_chuchang[1]}}月 </span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar">
                                      <label>行驶里程：</label>
                                   </td>
                                   <td class="tal">
                                      <span>{{$baojia['bj_licheng']}}</span>
                                      <span>公里</span>
                                   </td>
                               </tr>
                             </tbody>
                            </table> 
                            <p><b>已装原厂选装精品：</b></p>
                            <table class="tbl tbl-blue wp100">
                              <tbody>
                                <tr class="title-tag">
                                  <th class="tac none select"><span>选择</span></th>
                                  <th class="tac"><span>名称</span></th>
                                  <th class="tac"><span>型号/说明</span></th>
                                  <th class="tac"><span>厂商编号</span></th>
                                  <th class="tac"><span>厂商指导价</span></th>
                                  <th class="tac"><span>数量</span></th>
                                  <th class="tac"><span>附加价值</span></th>
                                </tr>
                                <?php $i=0;?>
                                @if(count($bj_xzj)>0)
                                @foreach($bj_xzj as $k=>$v)
                                <?php if($v['is_install']==0){ continue; }?>
                                <tr>
                                  <td class="tac"><span>{{@$v['xzj_title']}}</span></td>
                                  <td class="tac"><span>{{@$v['xzj_model']}}</span></td>
                                  <td class="tac"><span>{{@$v['xzj_cs_serial']}}</span></td>
                                  <td class="tac"><span>{{@$v['xzj_guide_price']}}</span></td>
                                  <td class="tac"><span>{{@$v['num']}}</span></td>
                                  <td class="tac"><span>{{@$v['xzj_guide_price']*$v['num']}}</span></td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                @endif
                                @if($i==0)
                                <tr><td colspan=6 style="text-align: left; padding-left:20px;">暂无</td></tr>
                                @endif
                                
                              </tbody>
                            </table>

                            <div class="m-t-10"></div>
                            <p class="tac none goods-control">
                              <a href="javascript:;" class="btn btn-danger bt fs18 goods-save-link">保存</a>
                              <a href="javascript:;" class="btn btn-danger bt sure fs18 ml20  goods-back-link">返回</a>
                            </p> 

                            <div class="m-t-10"></div>
                            <p><b>免费礼品和服务：</b></p>
                            <table class="tbl tbl-blue "  style="width: 60%">
                              <tbody>
                                <tr>
                                  <th class="tac"><span>名称</span></th>
                                  <th class="tac"><span>数量</span></th>
                                  <th class="tac"><span>状态</span></th>
                                </tr>
                                @if(count($bj_zengpin)>0)
                                @foreach($bj_zengpin as $v)
                                <tr>
                                  <td class="tac"><span>{{$v['title']}}</span></td>
                                  <td class="tac"><span>{{$v['num']}}</span></td>
                                  <td class="tac"><span>{{$v['is_install']==1?"已安装":"/"}}</span></td>
                                </tr>
                                @endforeach
                                @endif
                               
                              </tbody>
                            </table>
 
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                          
                            
                            
                            @else
							<!-- 非现车 -->
                            <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tar">
                                      <label>车身颜色：</label>
                                   </td>
                                   <td class="tal">
                                      <span>{{@$carinfoArray['body_color'][$carAttr['body_color']]}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar">
                                      <label>内饰颜色：</label>
                                   </td>
                                   <td class="tal">
                                      <span>{{$interColor}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar">
                                      <label>交车周期：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="btn-group btn-jquery-event">
                                          <span>{{$baojia['bj_jc_period']}}个月</span>
                                      </div>
                                      
                                   </td>
                               </tr>
                               
                             </tbody>
                            </table> 
                            
                            <p class="tac none goods-control">
                              <a href="javascript:;" class="btn btn-danger bt fs18 goods-save-link">保存</a>
                              <a href="javascript:;" class="btn btn-danger bt sure fs18 ml20  goods-back-link">返回</a>
                            </p> 

                            <div class="m-t-10"></div>
                            <p><b>免费礼品和服务：</b></p>
                            <table class="tbl tbl-blue " style="width: 40%">
                              <tbody>
                                <tr>
                                  <th class="tac"><span>名称</span></th>
                                  <th class="tac"><span>数量</span></th>
                                </tr>
                                @if(count($baojiaZengpinList)>0)
                                @foreach($baojiaZengpinList as $v)
                                <tr>
                                  <td class="tac"><span>{{$v['zp_title']}}</span></td>
                                  <td class="tac"><span>{{$v['num']}}</span></td>
                                </tr>
                                @endforeach
                                @else
                                <tr><td colspan=2 style="text-align: left; padding-left:20px;">暂无</td></tr>
                                @endif
                              </tbody>
                            </table>
 
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            @endif
                        </div>
                        
                    </form>
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
                                 <p class="fs14 pd msg" id="msg" style="text-align:center">确定将报价{{$baojia['bj_serial']}}暂时下架吗?</p>
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
                   "module/custom/custom.admin.offer-new-unfinished.jquery",
                    "module/common/common",
                     "bt"]);
	</script>
@endsection