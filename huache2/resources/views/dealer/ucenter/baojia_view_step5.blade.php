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
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/3">选装精品</a></li>
                          <li class="cur"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/5">收费标准</a></li>
                          <li class="last"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/6">其他事项</a></li>
                          <div class="clear"></div>
                        </ul>
                    </div>


                    <form action="newOfferings">

                       <div class="content-wapper border">

                           <div class="tbl-list-tool-panle valite-1">
                              <p><span class="blue weight">一、上牌（车辆注册登记）</span></p>
                              <p class="ml26"><span class="ml5">本地客户自由办理，异地客户本人办理</span></p>
                              <p class="ml26"><span class="ml5"><b>代办上牌服务费金额：  </b>￥ {{$baojia['bj_shangpai_price']}}</span>
                              </p>
                           </div>

                           <div class="tbl-list-tool-panle  valite-1">
                              <p class="blue"><b>二、上临时牌照（临时移动牌照）</b></p>
                              <p class="ml26"><span class="ml5">本地客户自由办理</span></p>
                              <p class="ml26 mt10">
                                <b class="ml5">代办车辆临时牌照（每次）服务费金额：</b>  ￥ {{$baojia['bj_linpai_price']}}
                              </p>
                          </div>

                          <p class="blue"><b>三、其他收费（常规）</b></p>
                          <div class="ml26">
                            <table style="width:60%" class="tbl custom-info-tbl tbl-blue">
                                 <tr>
                                     <th class="tac" width="50%">费用名称</th>
                                     <th class="tac" width="50%">金额</th>
                                 </tr>
                                 @if(count($otherPrice)>0)
                                 @foreach($otherPrice as $k=>$v)
                                 <tr class="def-temp">
                                   <td class="tac" width="260" valign="middle">
                                       <span>{{$v['other_name']}}</span>
                                   </td>
                                   <td class="tac" width="200" valign="middle">
                                       <span>￥{{$v['other_price']}}</span>
                                   </td>
                                   @endforeach

                                 </tr>
                                 @else
                                 <tr>
                                   <td colspan="2">
                                     <div class="m-t-10"></div>
                                     <p class="tac">暂无其他收费项目~</p>
                                   </td>
                                 </tr>

                                 @endif
                            </table>
                          </div>

                          <div class="m-t-10"></div>

                          <p class="blue"><b>四、刷卡标准</b></p>
                          <div class="tbl-list-tool-panle">
                             @if($expandInfo['xyk_status'])
                              单车付款刷信用卡免费次数  {{$expandInfo['xyk_number']}}  次；超出次数收费：刷卡金额的  {{$expandInfo['xyk_per_num']}}% （百分之），每次  {{$expandInfo['xyk_yuan_num']}} 元（封顶）。
                              @else
                              单车付款刷信用卡免费次数: 不限
                              @endif
                          </div>
                          <div class="tbl-list-tool-panle">
                            @if($expandInfo['jjk_status'])
                             单车付款刷借记卡免费次数  {{$expandInfo['jjk_number']}}  次；超出次数收费：刷卡金额的  {{$expandInfo['jjk_per_num']}}% （百分之），每次  {{$expandInfo['jjk_yuan_num']}}  元（封顶）。
                             @else
                             单车付款刷借记卡免费次数: 不限
                             @endif
                          </div>
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
                   "module/custom/custom.admin.charge.jquery",
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