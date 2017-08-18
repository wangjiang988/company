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
                          <li class="cur"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/3">选装精品</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/5">收费标准</a></li>
                          <li class="last"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/6">其他事项</a></li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    <form action="newOfferings">
                       <div class="content-wapper border">
                            <p>
                             <span>原厂选装精品折扣率：</span>     
                             <span>{{$baojia['bj_xzj_zhekou']}}</span>
                             <span>%</span>
                            </p>
                            <p>

                            @if($baojia['bj_is_xianche']==0)
                            <div class="m-t-10"></div>
                            <p>
                             <span><b>出厂前装</b></span>     
                            </p>
                            <table class="tbl tbl-blue wp100">
                              <tbody>
                                <tr>
                                  <th class="tac"><span>名称</span></th>
                                  <th class="tac"><span>型号/说明</span></th>
                                  <th class="tac"><span>厂商编号</span></th>
                                  <th class="tac"><span>厂商指导价</span></th>
                                  <th class="tac"><span>折后价</span></th>
                                </tr>
                                <?php $i=0;?>
                                @if(count($bj_xzj)>0)
                                @foreach($bj_xzj as $k=>$v)
                                <?php if($v['xzj_front']==0){ continue; }?>
                                <tr>
                                  <td class="tac"><span>{{$v['xzj_title']}}</span></td>
                                  <td class="tac"><span>{{$v['xzj_model']}}</span></td>
                                  <td class="tac"><span>{{$v['xzj_cs_serial']}}</span></td>
                                  <td class="tac"><span>￥{{number_format($v['xzj_guide_price'],2)}}</span></td>
                                  <td class="tac"><span>￥{{number_format($v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100,2)}}</span></td>
                                </tr>
                                <?php $i++;?>
                                @endforeach
                                @endif
                                
                                @if($i==0)
                                 <tr><td colspan=5 style="text-align: left; padding-left:20px;">暂无</td></tr>
                               @endif
                               
                              </tbody>
                            </table>
                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
							@endif

                           <span><b>后装</b></span>
                           </p>
                           <table class="tbl tbl-blue wp100">
                               <tbody>
                               <tr>
                                   <th class="tac"><span>名称</span></th>
                                   <th class="tac"><span>型号/说明</span></th>
                                   <th class="tac"><span>厂商编号</span></th>
                                   <th class="tac"><span>厂商指导价</span></th>
                                   <th class="tac"><span>安装费</span></th>
                                   <th class="tac">
                                       <div class="psr">
                                           <span>含安装费折</span><br><span>后总单价</span>
                                           <div class="th-tip">
                                               <i></i>
                                               计算方式：厂商指导价x折扣率+安装费
                                           </div>
                                       </div>
                                   </th>
                                   <th class="tac"><span>可供件数</span></th>
                               </tr>
                               <?php $i=0;?>
                               @if(count($bj_xzj)>0)
                                   @foreach($bj_xzj as $k=>$v)
                                       <?php if($v['xzj_front']==1){ continue; }?>
                                       <tr>
                                           <td class="tac"><span>{{$v['xzj_title']}}</span></td>
                                           <td class="tac"><span>{{$v['xzj_model']}}</span></td>
                                           <td class="tac"><span>{{$v['xzj_cs_serial']}}</span></td>
                                           <td class="tac"><span>￥{{number_format($v['xzj_guide_price'],2)}}</span></td>
                                           <td class="tac"><span>￥{{number_format($v['fee'],2)}}</span></td>
                                           <td class="tac"><span>￥{{number_format($baojia['bj_xzj_zhekou']*$v['xzj_guide_price']/100+$v['fee'],2)}}</span></td>
                                           <td class="tac"><span><?=($v['xzj_has_num']==0)?'不限':$v['xzj_has_num'];?></span></td>
                                       </tr>
                                       <?php $i++;?>
                                   @endforeach
                               @endif
                               @if($i==0)
                                   <tr><td colspan=7 style="text-align: left; padding-left:20px;">暂无</td></tr>
                               @endif

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
                   "module/custom/custom.admin.goods.jquery", 
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