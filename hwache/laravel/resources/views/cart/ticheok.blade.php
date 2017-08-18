@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span> </a></label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
  <div class="container m-t-86 pos-rlt" >
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li class="step-cur">付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content payment">
                    <small>正在交车</small>
                    <i></i>
                    <small class="juhuang">核实信息</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi" style="overflow: visible;"  ms-controller="item">

        <div class="wapper has-min-step"  style="overflow: visible;"> 
            <h1>尊敬的客户：</h1>
            <h1 class="ti">感谢您向我们提交尊驾信息！核实之后将进入正式结算程序，请耐心等待！</h1>
            <br>
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：</b>{{$order_num}}</p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b>{{ddate($order['cartBase']['created_at'])}}</p></li>
                <li class="pdi-more">
                    <div class="psr fs14">
                      <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                         <b>更多</b>
                      </span>
                      <p class="tm tm-long" style="display: none;">
                      @if(count($cart_log)>0)
                      @foreach($cart_log as $k =>$v )
                      	<span>{{$v['msg_time']}}：{{$v['time']}}</span>
                      @endforeach
                      @endif
                      </p>
                    </div>


                </li>
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
            <ul class="pdi-order-ul border">
                <li class="pdi-name">
                    <p class="fs14">{{$bj['brand'][0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$bj['brand'][1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$bj['brand'][2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$bj['body_color']}}</p></li>
                <div class="clear"></div>
            </ul>     

            <p class="fs14 tac">
                <a href="{{url('orderoverview')}}/{{$order_num}}" class="tdu juhuang" target="_blank">查看订单总详情</a>
            </p>
            <hr class="dashed">               
            <div class="box">
               
                <div class="box-inner  box-inner-def">
                   
                    <?php if ($order['cartBase']['shangpai']): ?>
                        <div style="width: 55%;float:left;">
                         <table class="tbl">
                            <tbody>
                                <tr>
                                    <td><p class="tal fs14 weight">车辆识别代号（VIN码）</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_vin']}}</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">发动机号</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_engine_no']}}</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <th class="tal"><label class="fs14 tal">上牌地区</label></th>
                                    <th><p class="tal fs14 "><span class="nomarl">{{$jiaoche['user_shangpai_area']}}</span></p></th>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14 weight">车辆用途</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_useway']}}</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">上牌（注册登记）车主名称</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_regname']}}</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">牌照号码</p></td>
                                    <td width="300">
                                        @if(!empty($jiaoche['user_chepai']))
                                      <span>{{implode("",unserialize($jiaoche['user_chepai']))}}</span>
                                    @endif
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">国家节能补贴发放约定时间</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_butie_date']}}</span>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>

                       
                        <div class="clear"></div>
                       
                        <br>
                        <table class="tbl">
                            <tbody>
                                <tr>
                                    <th class="tal"><label class="fs14 tal">买车担保金</label><p class="fs14 tal">（冻结在华车网加信宝）</p></th>
                                    <th><p class="tal fs14 "><span class="nomarl fs14">人民币 {{$guarantee}} 元</span></p></th>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14 weight">华车服务费约定</p></td>
                                    <td width="300">
                                        <span class="fs14">人民币 {{$server_money['hwacheServicePrice']}} 元</span>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
			<!-- 补充证据start -->			
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
                	<form action="{{url('cart/ajax').'/'.$order_num.'/sub_jiaoche_ext'}}" method='post' enctype="multipart/form-data" name='item-form-ext-{{$v["id"]}}'>
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
                	<input type="hidden" value="{{$order_num}}" name="order_num" >
                    <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
            		<input type="hidden" name="_token" value="{{ csrf_token() }}">
                	</form>
                	@endif
                @endforeach
                
             @endif
             <!-- 补充证据end -->
                        <br>
                        
						@if($order['cartAttr']['butie']==1)
			                <div class="fs14 m-t-10 clear">
			                @if($jiaoche['user_butie_get_date']=='')
			                    <span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['user_butie_date']}}    </span>
			                    <a ms-on-click="pdibutie" href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">发放补贴</a>
			                	<span class="fl">（尚未收到不必点击）。</span>
			                @else
			                	<a href="javascript:;" class="btn btn-s-md btn-danger oksure bt" >已收到补贴</a>
			                @endif
			                    <div class="clear"></div>
			                </div>
		                @endif

                    </div>
                    <div class="jingdu">
                        <span class="fl fs14"><b>核实信息进度：</b></span>
                        <dl class="fl jd-dl">
                            <dd class="cur-jd"><span></span>客户已提交</dd>
                            <dt></dt>
                            <dd 
                            <?php if ($jiaoche['pdi_date_first']): ?>
                                class="cur-jd"
                            <?php endif ?>
                            ><span></span>经销商已提交</dd>
                            <dt></dt>
                            <dd><span></span>华车平台核实中</dd>
                        </dl>
                        <div class="clear"></div>

                    </div>
                    <div class="clear"></div>
                    <?php else: ?>
                            <div style="width: 55%;float:left;">
                         <table class="tbl">
                            <tbody>
                                <tr>
                                    <td><p class="tal fs14 weight">车辆识别代号（VIN码）</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_vin']}}</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">发动机号</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_engine_no']}}</span>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                        <br>
                        <table class="tbl">
                            <tbody>
                                <tr>
                                    <th class="tal"><label class="fs14 tal">买车担保金</label><p class="fs14 tal">（冻结在华车网加信宝）</p></th>
                                    <th><p class="tal fs14 "><span class="nomarl fs14">人民币 {{$guarantee}} 元</span></p></th>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14 weight">华车服务费约定</p></td>
                                    <td width="300">
                                        <span class="fs14">人民币 {{$server_money['hwacheServicePrice']}} 元</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">客户本人上牌违约金约定</p></td>
                                    <td width="300">
                                        <span class="fs14">人民币 {{$bj['bj_license_plate_break_contract']}} 元</span>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                        <p>
                    <form action="{{ url('cart/postotherticheinfo') }}" method="post" name="item" id="form_shangpai">  
                            <span class="fl mt5">您的预计上牌（注册登记）最晚日期：</span>
                            <div class="btn-group m-r fl">
                              <div class="form-group psr pdi-control">
                                <input name="shangpai_time" value="{{$jiaoche['user_shangpai_time']}}" style="" type="text" placeholder="" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                <i class="rili"></i>
                              </div>
                            </div>
                           <a href="javascript:void(0)" class="fl juhuang tdu mt5 ml5" id="modifyShangpaiButton">修改</a>
                        </p>
					<input type="hidden" value="{{$order_num}}" name="order_num" >
		            <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
		            <input type="hidden" value="1" name="edit">
		            <input type="hidden" name="_token" value="{{ csrf_token() }}">
					</form>
                    </div>

                    <div class="jingdu">
                        <span class="fl fs14"><b>核实信息进度：</b></span>
                        <dl class="fl jd-dl">
                            <dd class="cur-jd"><span></span>客户已提交</dd>
                            <dt></dt>
                            <dd
                            <?php if ($jiaoche['pdi_date_first']): ?>
                                class="cur-jd"
                            <?php endif ?>
                            ><span></span>经销商已提交</dd>
                            <dt></dt>
                            <dd><span></span>华车平台核实中</dd>
                            <dt></dt>
                            <dd><span></span>华车平台已核实</dd>
                        </dl>
                        <div class="clear"></div>

                    </div>
                    <div class="clear"></div>
                    <p class="fs14 clear"><small>温馨提示：如您本人上牌时间过长，可能因为国家补贴政策、厂商内部政策调整而导致原国家节能补贴发放条件发生变化。对交车（收到任一方交车信息日）后15个自
然日内提交正确上牌信息，且符合国家政策规定的客户，华车平台提供国家节能补贴发放担保。除此以外的客户，请自行与经销商协商约定发放和领取该补贴事宜。</small></p>
<hr class="dashed">
<?php if (date('Y-m-d')<$jiaoche['user_shangpai_time']): ?>
<div style="width: 600px;float:left;">
<form action="{{ url('cart/postotherticheinfo') }}" method="post" name="item1">
                        <table class="tbl2">
                            <tbody>  
                            <?php 
                                          if(!empty($jiaoche['user_shangpai_area'])){
                                          	$ss=explode(' ', $jiaoche['user_shangpai_area']);
                                          }else{
                                          	$ss = array(0=>'',1=>'');
                                          }
                                          	?>
                                <tr>
                                          <th class="tar p10"><label class="fs14">上牌地区：</label></th>
                                          <th class="p10">
                                              <div class="btn-group m-r fl bts fn pdi-drop pdi-drop-warp">
                                                <button class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                                    <span class="dropdown-label"><span>{{$jiaoche['user_shangpai_area']}}</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <div class="dropdown-menu dropdown-select area-tab-div">
                                                    <input type="hidden" name="sheng" value="{{$ss[0]}}"/>
                                                    <input type="hidden" name="shi" value="{{$ss[1]}}" />
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
                                    <td class="p10 tar"><label class="fs14">车辆用途：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r fl bts fn pdi-drop">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>非营业个人客车</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select">
                                              <input type="hidden" name="yongtu" value="非营业个人客车" />
                                              <li ms-on-click="selectTime" class="active"><a><span>非营业个人客车</span></a></li>
                                              
                                              <li ms-on-click="selectTime"><a><span>非营业公司客车</span></a></li>
                                          </ul>
                                        </div>
                                    </td>
                                </tr> 
                                
                                <tr>
                                    <td class="p10 tar"><label class="fs14">上牌（注册登记）车主名称：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" name="reg_name" type="text" placeholder="" class="form-control pdi-control" value="{{$jiaoche['user_regname']}}">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="p10 tar"><label class="fs14">牌照号码：</label></td>
                                    <td width="400" class="p10">
                                        <!--苏-->
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$chepai[0]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[0]}}"/>
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
                                <tr>
                                    <td class="p10 tar"><label class="fs14">国家节能补贴发放约定时间：</label></td>
                                    <td width="300" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input name="fafang_butie" style="" type="text" placeholder="{{date('Y-m-d')}}" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });" value="{{$jiaoche['user_butie_date']}}">
                                            <i class="rili"></i>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                
                            </tbody>
                        </table>

                         
                    </div>
                    <div class="jingdu" style="width:33%;padding-left:0px;">
                        <span class="fl fs14"><b>核实信息进度：</b></span>
                        <dl class="fl jd-dl">
                            <dd class="cur-jd"><span></span>客户已提交</dd>
                            <dt></dt>
                            <dd class="cur-jd"><span></span>经销商已提交</dd>
                            <dt></dt>
                            <dd><span></span>华车平台核实中</dd>
                           
                        </dl>
                        <div class="clear"></div>

                    </div>
                    <div class="clear"></div>
                    <p class="tac">
                        <input type="submit" class="btn btn-s-md btn-danger" value="发送补充信息">
                        <a href="javascript:;" class="btn btn-s-md btn-danger oksure">已发送</a>
                    </p>
                    @if($order['cartAttr']['butie']==1)
			                <div class="fs14 m-t-10 clear">
			                @if($jiaoche['user_butie_get_date']=='')
			                    <span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['user_butie_date']}}    </span>
			                    <a ms-on-click="pdibutie" href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">发放补贴</a>
			                	<span class="fl">（尚未收到不必点击）。</span>
			                @else
			                	<a href="javascript:;" class="btn btn-s-md btn-danger oksure bt" >已收到补贴</a>
			                @endif
			                    <div class="clear"></div>
			                </div>
		            @endif
            <input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
            <input type="hidden" value="2" name="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

                     <hr class="dashed clear m-t-10">
    <?php else: ?>
<p class="fs14">因您未在规定期限内提交上牌补充信息，则由经销商反馈相关信息请您确认，如需修改请点击内容：</p>
<form action="{{ url('cart/postotherticheinfo') }}" method="post" name="item2">
                    <div style="width: 600px;float:left;">
                    
                        <table class="tbl2">
                            <tbody>
                                <tr>
                                          <th class="tar p10"><label class="fs14">上牌地区：</label></th>
                                          <th class="p10">
                                          <?php 
                                          if(!empty($jiaoche['pdi_shangpai_area'])){
                                          	$ss=explode(' ', $jiaoche['pdi_shangpai_area']);
                                          }else{
                                          	$ss = array(0=>'',1=>'');
                                          }
                                          	
                                           ?>
                                              <div class="btn-group m-r fl bts fn pdi-drop pdi-drop-warp">
                                                <button class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                                    <span class="dropdown-label"><span>{{$jiaoche['pdi_shangpai_area']}}&nbsp;</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <div class="dropdown-menu dropdown-select area-tab-div">
                                                    <input type="hidden" name="sheng" value="{{$ss[0]}}"/>
                                                    <input type="hidden" name="shi"  value="{{$ss[1]}}"/>
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
                                    <td class="p10 tar"><label class="fs14">车辆用途：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r fl bts fn pdi-drop">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$jiaoche['pdi_useway']}}&nbsp;</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select">
                                              <input type="hidden" name="yongtu" value="{{$jiaoche['pdi_useway']}}" />
                                              <li ms-on-click="selectTime" class="active"><a><span>非营业个人客车</span></a></li>
                                              
                                              <li ms-on-click="selectTime"><a><span>非营业公司客车</span></a></li>
                                          </ul>
                                        </div>
                                    </td>
                                </tr> 
                                
                                <tr>
                                    <td class="p10 tar"><label class="fs14">上牌（注册登记）车主名称：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" name="reg_name" value="{{$jiaoche['pdi_regname']}}" type="text" placeholder="" class="form-control pdi-control">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                @if(!empty($chepai_pdi) && count($chepai_pdi)>0)
                                <tr>
                                    <td class="p10 tar"><label class="fs14">牌照号码：</label></td>
                                    <td width="400" class="p10">
                                        <!--苏-->
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[0]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[0]}}" />
                                              <li  ms-repeat-item="areaSn" ms-on-click="selectTime"  ms-class="<!--item == '{{$chepai_pdi[0]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>
                                        <!--E-->
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[1]}}</span></span>
                                              <span class="caret"></span>
                                          </button> 
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[1]}}"/>
                                              <li ms-repeat-item="en" ms-on-click="selectTime" ms-class="<!--item == '{{$chepai_pdi[1]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul> 
                                        </div>
                                        
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[2]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[2]}}"/>
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai_pdi[2]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[3]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[3]}}"/>
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai_pdi[3]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[4]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[4]}}"/>
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai_pdi[4]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[5]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[5]}}"/>
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai_pdi[5]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai_pdi[6]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai_pdi[6]}}" />
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai_pdi[6]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                    </td>
                                </tr> 
                                @endif
                                <tr>
                                    <td class="p10 tar"><label class="fs14">国家节能补贴发放约定时间：</label></td>
                                    <td width="300" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" value="{{$jiaoche['pdi_butie_date']}}" name="fafang_butie" type="text" placeholder="" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                            <i class="rili"></i>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                
                            </tbody>
                        </table> 

                    </div>
                    <div class="jingdu" style="width:33%;padding-left:0px;">
                        <span class="fl fs14"><b>核实补充信息进度：</b></span>
                        <dl class="fl jd-dl" style="width: 150px;">
                            <dd class="cur-jd"><span></span>客户已提交</dd>
                            <dt></dt>
                            <dd><span></span>经销商已提交</dd>
                            <dt></dt>
                            <dd><span></span>华车平台核实中</dd>
                           
                        </dl>
                        <div class="clear"></div>

                    </div>
                    <div class="clear"></div>
                    
              
                    
                    
                    <p class="fs14 mt20">
                        您的有效确认剩余时间<span class="juhuang">{{diffBetweenTwoDays(date('Y-m-d',$bc_time),3)}}</span>，超时未确认平台将默认相符自动提交！
                    </p>
                    <p class="tac">
                    @if($order['cartBase']['cart_sub_status'] == 503 || $order['cartBase']['cart_sub_status'] == 504 || $order['cartBase']['cart_sub_status'] == 505)
                    	<input type="button" ms-on-click='submit_tiche_end' value="提交" class="btn btn-s-md btn-danger">
                    @else	
                        <a href="javascript:;" class="btn btn-s-md btn-danger oksure">已提交</a>
                    @endif
                    </p>
                    @if($order['cartAttr']['butie']==1)
			                <div class="fs14 m-t-10 clear">
			                @if($jiaoche['user_butie_get_date']=='')
			                    <span class="fl"><b>国家节能补贴发放客户约定时间：</b>{{$jiaoche['user_butie_date']}}    </span>
			                    <a ms-on-click="pdibutie" href="javascript:;"  class="btn btn-s-md btn-danger sure fl bt">发放补贴</a>
			                	<span class="fl">（尚未收到不必点击）。</span>
			                @else
			                	<a href="javascript:;" class="btn btn-s-md btn-danger oksure bt" >已收到补贴</a>
			                @endif
			                    <div class="clear"></div>
			                </div>
		            @endif
            <input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
            <input type="hidden" value="3" name="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>
<?php endif ?>
                      <!-- 补充证据start -->
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
                	<form action="{{url('cart/ajax').'/'.$order_num.'/sub_jiaoche_ext'}}" method='post' enctype="multipart/form-data" name='item-form-ext-{{$v["id"]}}'>
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
                	<input type="hidden" value="{{$order_num}}" name="order_num" >
                    <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
            		<input type="hidden" name="_token" value="{{ csrf_token() }}">
                	</form>
                	@endif
                @endforeach
              @endif
              <!-- 补充证据end -->
                       
                    <?php endif ?>
                    


                    

                    
                   

                </div>
                
              
              
               
            </div>
            
        			<div id="pdi-tip" class="popupbox">
        			 <form action='{{ url('cart/ajax').'/'.$order_num.'/surebutie' }}' name='surebutieform' method='post' enctype="multipart/form-data">
                        <div class="popup-title">提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs14 pd ti">       
                                确定已收到约定的全部国家节能补贴吗？
                                </p>
                            </div>
                            <div class="popup-control">
                                <a ms-on-click="surebutie" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <input type="hidden" value="{{$order_num}}" name="order_num" >
                        <input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      </form>
                    </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-payment-while", "module/common/common", "bt"]);
    </script>
@endsection
