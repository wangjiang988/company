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
                          <li class="cur"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/4">首年保险</a></li>
                          <li><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/5">收费标准</a></li>
                          <li class="last"><a href="/dealer/baojia/view/{{$baojia['bj_id']}}/6">其他事项</a></li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="newOfferings">
                       <div class="content-wapper border">
                           
                            <p class="ml5"><b>车辆首年商业保险：</b>
                              @if($baojia['bj_baoxian']==1)
                              <span class="ml5">客户必须在经销商处投保（客户上牌地必须在保险公司理赔范围内）</span>
                              @elseif($baojia['bj_baoxian']==0)
                              <span class="ml5">客户自由投保</span>
                              @endif
                            </p>
                            <table class="custom-info-tbl noborder">
                              <tbody>
                                <tr>
                                   <td class="tar">
                                      <label>保险公司：</label>
                                   </td>
                                   <td class="tal">
                                      <span>
                                      @if($baojia['bj_bx_select']>0)
                                          {{@$baoxianList[$baojia['bj_bx_select']]['bx_title']}}
                                      @endif
                                      </span>
                                   </td>
                                </tr>
                                <tr>
                                   <td class="tar">
                                      <label>理赔范围：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="checkbox-wrapper psr">
                                          @if(@$baoxianList[$baojia['bj_bx_select']]['bx_is_quanguo']==1)
                                          <span>本地、异地</span>
                                          @else
                                          <span>本地</span>
                                          @endif
                                      </div>
                                   </td>
                                </tr> 
                              </tbody>
                            </table>
                        
                            <table class="tbl w615 tbl-insurance">
                              <tbody>
                                 <tr>
                                   <td rowspan="2" width="30"><b class="fs14">类别</b></td>
                                   <td rowspan="2" width="118"><b class="fs14">险种</b></td>
                                   <td rowspan="2" width="248"><b class="fs14">赔付选项</b></td>
                                   <td colspan="2"><b class="fs14">保费折后价</b></td> 
                                 </tr>
                                 <tr>
                                   <td width="132"><b class="fs14">非营业个人客车</b></td>
                                   <td width="132"><b class="fs14">非营业企业客车</b></td>
                                 </tr>
                                 
                                 <tr>
                                   <td rowspan="18" valign="middle">
                                      <b>主<br><br><br><br>险</b>
                                   </td>
                                   <td>机动车损失险</td>
                                   <td class="tal"><span class="ml10">按保险公司规定执行</span></td>
                                   <td>
                                      <span>{{number_format($bx['chesun'][0]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['chesun'][1]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 <tr>
                                   <td>机动车盗抢险</td>
                                   <td class="tal"><span class="ml10">按保险公司规定执行</span></td>
                                   <td>
                                      <span>{{number_format($bx['daoqiang'][0]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['daoqiang'][1]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 <tr>
                                   <td rowspan="6">第三者责任险</td>
                                   <td class="tal"><span class="ml10">赔付额度5万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][0][5]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][1][5]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度10万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][0][10]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][1][10]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度15万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][0][15]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][1][15]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度20万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][0][20]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][1][20]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度50万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][0][50]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][1][50]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度100万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][0][100]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['sanzhe'][1][100]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 
                                 <tr>
                                   <td rowspan="10">车上人员责任险</td>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额1万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['sj'][1]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['sj'][1]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额2万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['sj'][2]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['sj'][2]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额3万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['sj'][3]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['sj'][3]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额4万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['sj'][4]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['sj'][4]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额5万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['sj'][5]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['sj'][5]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额1万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['ck'][1]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['ck'][1]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额2万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['ck'][2]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['ck'][2]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额3万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['ck'][3]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['ck'][3]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额4万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['ck'][4]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['ck'][4]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额5万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][0]['ck'][5]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['renyuan'][1]['ck'][5]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 <tr>
                                   <td rowspan="11"><b>附<br><br><br><br>加<br><br><br><br>险</b></td>
                                   <td rowspan="2">玻璃单独破碎险</td>
                                   <td class="tal"><span class="ml10">进口玻璃</span></td>
                                   <td>
                                      <span>{{number_format($bx['boli'][0]['jk']['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['boli'][1]['jk']['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">国产玻璃</span></td>
                                   <td>
                                      <span>{{number_format($bx['boli'][0]['gc']['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['boli'][1]['gc']['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr> 
                                 
                                 <tr>
                                   <td rowspan="4">车身划痕损失险</td>
                                   <td class="tal"><span class="ml10">赔付额度0.2万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][0][2000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][1][2000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度0.5万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][0][5000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][1][5000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度1万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][0][10000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][1][10000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度2万元</span></td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][0][20000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['huahen'][1][20000]['price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 
                                 <tr>
                                   <td rowspan="5">不计免赔特约险</td>
                                   <td class="tal"><span class="ml10">机动车损失险不计免赔</span></td>
                                   <td>
                                      <span>{{number_format($bx['chesun'][0]['bjm_price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['chesun'][1]['bjm_price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">机动车盗抢险不计免赔</span></td>
                                   <td>
                                      <span>{{number_format($bx['daoqiang'][0]['bjm_price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <span>{{number_format($bx['daoqiang'][1]['bjm_price'],2)}}</span>
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10 block">第三者责任险不计免赔，按赔付额度保费X费率</span></td>
                                   <td>
                                      <span>费率</span>      
                                      <span>{{$bx['sanzhe'][0][5]['bjm_percent']}}</span>
                                      <span>%计保费</span>
                                   </td>
                                   <td>
                                      <span>费率</span>      
                                      <span>{{$bx['sanzhe'][1][5]['bjm_percent']}}</span>
                                      <span>%计保费</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10 block">车上人员责任险不计免赔，按限额和人数保费X费率</span></td>
                                   <td>
                                      <span>费率</span>      
                                      <span>{{$bx['renyuan'][0]['sj'][1]['bjm_percent']}}</span>
                                      <span>%计保费</span>
                                   </td>
                                   <td>
                                      <span>费率</span>      
                                      <span>{{$bx['renyuan'][1]['sj'][1]['bjm_percent']}}</span>
                                      <span>%计保费</span>
                                   </td>
                                 </tr>
                                  <tr>
                                   <td class="tal"><span class="ml10 block">车身划痕损失险不计免赔，按赔付额度保费X费率  </span></td>
                                   <td>
                                      <span>费率</span>      
                                      <span>{{$bx['huahen'][0][2000]['bjm_percent']}}</span>
                                      <span>%计保费</span>
                                   </td>
                                   <td>
                                      <span>费率</span>      
                                      <span>{{$bx['huahen'][1][2000]['bjm_percent']}}</span>
                                      <span>%计保费</span>
                                   </td>
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
                   "module/custom/custom.admin.insurance.jquery", 
                   "module/custom/custom.admin.offer-new-unfinished.jquery",
                   "module/common/common", "bt"]);
	</script>
@endsection