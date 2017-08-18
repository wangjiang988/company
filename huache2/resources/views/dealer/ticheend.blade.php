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
                                        
                                        <p class="tac m-t-10"><a href="{{url('dealer/overview')}}/{{$order_num}}" class="juhuang tdu">查看订单总详情</a></p>
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
										<?php endif ?></p>
									</td>
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
                                     <td width="33.33%"><p class="fs14"><b>我的服务费：  </b></p>{{$bj_agent_service_price}} 元</td>
                                     <td width="33.33%"><p class="fs14"><b>客户买车定金：  </b> {{$guarantee}} 元</p></td>
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
                                      <span>{{implode('',unserialize($jiaoche['pdi_chepai']))}}</span>
									  @endif
                                  </td>
                              </tr> 
                          </tbody>
                    </table> 
                </div>
                <div class="jingdu" >
                    <span class="fl fs14"><b>核实信息进度：</b></span>
                    <dl class="fl jd-dl" style="width: 150px;">
                        <dd class="cur-jd"><span></span>客户已提交</dd>
                        <dt></dt>
                        <dd class="cur-jd"><span></span>经销商已提交</dd>
                        <dt></dt>
                        <dd><span></span>华车平台核实中</dd>
                       
                    </dl>
                    <div class="clear"></div>

                </div>
                <div class="clear"></div>
              @if(count($jiaocheExt)>0)
                @foreach($jiaocheExt as $k => $v)
                	@if(!empty($v['resupply_date']))
                	 <!--//发送过的显示样式-->
	                <div class="clear upload-wrapper">
	                    <p class="fs14">
	                        <span class="fl">提交<span class="juhuang">“{{$v['content']}}”</span>相关材料供平台核实：</span>
	                    </p>
	                    <div class="clear"></div>
	                    <p class="fs14">
	                        <span class="blue fl ">
	                        	<?php 
	                        	if(!empty($v['resupply_file'])){
	                        		$files = unserialize($v['resupply_file']);
	                        		foreach($files as $file){
	                        	?>
	                        		<span class="file-prev"><a href="/upload/{{$file}}" target='_bank'>{{ltrim($file,"file/")}}</a></span>	
	                        	<?php 
	                        		}
	                        	}
	                        	?>
	                            
	                        </span>
	                    </p>
	                    <div class="clear"></div>
	                    <p class="fs14">
	                       	 发送时间：{{$v['resupply_date']}}
	                    </p>
	                </div>
                	@else
                	<!--//第一次或者再次发送的显示样式 和上面的不冲突-->
                	<form action="{{url('dealer/ajax').'/'.$order_num.'/sub_jiaoche_ext'}}" method='post' enctype="multipart/form-data" name='item-form-ext-{{$v["id"]}}'>
	                <div class="clear upload-wrapper">
	                    <p class="fs14">
	                        <span class="fl">请提交<span class="juhuang">“{{$v['content']}}”</span>相关材料供平台核实：</span>
	                    </p>
	                    <div class="clear"></div>
	                    <p class="fs14">
	                        <span class="blue fl "></span>
	                        <span class="juhuang tdu cp fl ml10" ms-on-click="uploadForMuliteFileInput">上传</span>
	                        <input type="hidden" name="" id="hfFile">
	                    </p>
	                </div>
                	<p class="tal clear">
                   		<a href="javascript:;" ms-on-click='sub_jiaoche_ext("item-form-ext-{{$v["id"]}}")' class="btn btn-s-md btn-danger">发送</a>
                	</p>
                	<input type='hidden' name='ext_id' value='{{$v["id"]}}'>
                	<input type="hidden" value="{{$order['order_num']}}" name="order_num" >
                    <input type="hidden" value="{{$order['id']}}" name="id" >
            		<input type="hidden" name="_token" value="{{ csrf_token() }}">
                	</form>
                	@endif
                @endforeach
                
             @endif
                
                
                
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

                    
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom_order", "module/common/common", "bt"]);
    </script>
@endsection