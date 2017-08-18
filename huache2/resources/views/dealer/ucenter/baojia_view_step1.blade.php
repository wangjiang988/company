@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                @if($baojia['bj_status'] == 6)
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
                                <div class="col-sm-6 col-xs-6">

                                    <div class="time">
                                        <span class="fuhao fl">生效倒计时：</span>
                                        <div class="jishi countdown fl">
                                            <span>0</span>
                                            <span>0</span>
                                            <span class="fuhao">天</span>
                                            <span>0</span>
                                            <span>0</span>
                                            <span class="fuhao">小时</span>
                                            <span>0</span>
                                            <span>0</span>
                                            <span class="fuhao">分钟</span>
                                            <span>0</span>
                                            <span>0</span>
                                            <span class="fuhao">秒</span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <!-- //等待生效 end -->
				@elseif($baojia['bj_status']==1)

                	
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
 				@elseif($baojia['bj_status']==2)
 				<!-- //暂时下架 start-->
 					<h2 class="title weighttitle">暂时下架</h2>
                	<div class="content-wapper lh30">
	                	<div class="row">
	                		<div class="col-sm-6 col-xs-6"><span>报价编号：{{$baojia['bj_serial']}}</span></div>
	                		<div class="col-sm-6 col-xs-6">
		                		<span>操作：</span>   
		                		 <a href="javascript:baojiaExcute('copy',{{$baojia['bj_id']}})" class="juhuang tdu">复制</a>
                                @if(!in_array($baojia['bj_status_change_code'],get_system_suspect_code()))
                            <a href="javascript:;" class="juhuang tdu ml10 renew" data-id="{{$baojia['bj_id']}}">恢复</a>
                                @endif
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
                          <li class="first cur"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/1">车型价格</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/2">车况说明</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/3">选装精品</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/5">收费标准</a></li>
                          <li class="last"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/6">其他事项</a></li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="newOfferings">
                        <div class="content-wapper border">
                           
                           <table class=" custom-info-tbl noborder wp100">
                             <tbody>
                               <tr>
                                   <td class="tar" width="115"><span class="blue weight">一、选择经销商：</span></td>
                                   <td class="tal" width="280">
                                      <span>{{$dealerName}}</span>
                                   </td>
                                   <td width="80"><span>归属地区：</span></td>
                                   <td width="180" ><span>{{$dealerAddress}}</span></td>
                               </tr>
                             </tbody>
                           </table> 

                           <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tal" width="135"><span class="blue weight">二、车型：</span></td>
                                   <td class="tal"></td>
                                   <td><span></span></td>
                                   <td></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">品牌：</span></td>
                                   <td class="tal">
                                      <span>{{$brandArr[0]}}</span>
                                   </td>
                                   <td class="tar" width="200"><span>车系：</span></td>
                                   <td class="tal">
                                     <span>{{$brandArr[1]}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">车型规格：</span></td>
                                   <td class="tal" colspan="3">
                                       <span>{{$brandArr[2]}}</span>
                                   </td>
                                   
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">整车型号：</span></td>
                                   <td class="tal" colspan="3">
                                       <span>{{$car_info['vehicle_model']}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">座位数：</span></td>
                                   <td class="tal">
                                        <span>{{$car_info['seat_num']}}</span>
                                   </td>
                                   <td class="tar"><span>基本配置：</span></td>
                                   <td class="tal">
                                      <a href="{{$car_info['official_url']}}" target="_blank" class="juhuang tdu">查看</a>
                                   </td>
                               </tr>
                             </tbody>
                           </table> 

                           <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tal" width="135"><span class="blue weight">三、是否现车：</span></td>
                                   <td class="tal" colspan="3">
                                        @if($baojia['bj_is_xianche']==1)
                                        <span class="noweight">现车</span>
                                        @if($baojia['bj_dealer_internal_id']!="")
                                        <span class="ml20">经销商内部车辆编号（选填）</span>
                                        <span>{{$baojia['bj_dealer_internal_id']}}</span>
                                        @endif
                                        @else 
                                        <span class="noweight">非现车</span>
                                        @endif 
                                   </td>
                               </tr>
                               
                               <tr>
                                   <td class="tal" valign="top" width="135"><span class="blue weight">四、销售区域：</span></td>
                                   <td class="tal" colspan="3">
                                       <span>{{$myAreaStr}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" valign="top" width="135"><span class="blue weight">五、排放标准：</span></td>
                                   <td class="tal" colspan="3">
                                    @if($car_info['paifang']==0)
                                    <span class="noweight">国五标准</span>
                                    @elseif($car_info['paifang']==1)
                                    <span class="noweight">国四标准</span>
                                    @else
                                    <!-- <span class="noweight">未知标准</span> -->
                                    @endif 
                                   <!-- @foreach($paifangList as $k=>$v)
                                   <label class="ml20"><input  disabled="" type="radio" name="paifang" value="{{$k}}" <?php if($car_info['paifang']==$k){echo 'checked';}?>><span class="noweight ml5">{{$v}}</span></label>
                                   @endforeach -->
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" valign="top" width="135"><span class="blue weight">六、价格设置：</span></td>
                                   <td class="tal" colspan="3">
                                   </td>
                               </tr>
                             </tbody>
                           </table> 

                           <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tar" width="115">厂商指导价：</td>
                                   <td class="tal">￥<span class="format-money">{{$car_info['zhidaojia']}}</span></td>
                                   <td>人民币 <span></span></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="115">车辆开票价格： </td>
                                   <td class="tal"><span>￥</span><span class="format-money">{{$baojia['bj_lckp_price']}}</span></td>
                                   <td>人民币 <span></span></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="115">我的服务费： </td>
                                   <td class="tal"><span>￥</span><span class="format-money">{{$baojia['bj_agent_service_price']}}</span></td>
                                   <td>人民币 <span></span></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="115">客户买车定金： </td>
                                   <td class="tal"><span>￥</span><span class="format-money">{{$baojia['bj_doposit_price']}}</span></td>
                                   <td>人民币 <span></span></td>
                               </tr>

                             </tbody>
                           </table> 
                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                            
                            
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
                                     <div class="fs14 pd msg" id="msg" style="text-align:center">
                                     确定将报价{{$baojia['bj_serial']}}暂时下架吗？</div>
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

                <div id="notice_pop" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd msg" style="text-align:center"></p>
                                <div class="m-t-10"></div>
                            </div>
                        <p class="fs14 pd" style="text-align:center"><span class="juhuang second">5</span>秒后自动刷新页面</p>
                            <div class="popup-control">
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 reset-form w100 ml20">刷新</a>
                                <div class="clear"></div>
                            </div>
                    </div>
                </div>
                                
                
@endsection
@section('js')
 
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin",
                   "module/custom/custom.admin.common.jquery",
                   "module/custom/custom.admin.car.prices.jquery", 
                   "module/custom/custom.admin.offer-new-unfinished.jquery",
                   "module/common/common","/webhtml/common/js/vendor/time.jquery", "bt"],function(a,b,c,d,e){
                        $(".countdown").CountDown({
                              startTime:"{{date('Y-m-d H:i:s',time())}}",
                              endTime :"{{date('Y-m-d H:i:s',$baojia['bj_start_time'])}}",
                              timekeeping:'countdown',
                              callback:function(){
                                  
                              }
                        }) 
                    }); 
	</script>
@endsection