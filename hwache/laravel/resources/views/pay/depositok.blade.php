@extends('_layout.base')
@section('css')
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
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span></a> </label>
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
<div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li class="step-cur">付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content" style=" left: 187px;">
                    <small>正在支付</small>
                    <i></i>
                    <small class="juhuang" class="">查收确认</small>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
	
    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper"> 
         	<p>&nbsp;</p>
            <p>尊敬的客户：</p>
            <p>华车网平台已收到您在规定时间内支付的买车担保金人民币 {{$money}} 元，您的购车计划正顺利推进中！</p>
            <?php if ($baojia['bj_producetime']): ?>
                <p>【现车】</p>
            <p>根据平台规则，经销商应在7日内向您发出交车邀请，15日内可向您移交符合订单要求的车辆（具体提车日期尊重您的安排）。</p>
            <?php endif ?>
            <?php if ($baojia['bj_jc_period']): ?>
                <p>【非现车】</p>
            <p>根据平台规则，经销商应在约定交车日期（{{$new_date}}）前，至少提前7日，也就是{{$date_tiqian}}前向您发出交车邀请，在约定交车日期</p>
            <p>（{{$new_date}}）前向您移交符合订单要求的车辆（具体提车日期尊重您的安排）。</p>
            <?php endif ?>

            
            <p>如果经销商超时，您将有权取消订单，退还已付买车担保金，并获得华车网加信宝提供的额外补偿：</p>
            <p>歉意金人民币{{$jine['qianyijin']}}元和买车担保金人民币{{$guarantee}}元冻结期间的利息（日利率万分之二）。</p>
            <p>如您对随后经销商提出的延期交车修改方案满意，您也可在获得歉意金和前期买车担保金利息的基础上，选择不退还买车担保金，执行修改方案，</p>
            <p>华车网加信宝继续为您保驾护航！</p>
            <p>请您暂时按捺一下拥有心仪座驾的急迫性情，期待我们为您精心准备得妥妥哒！</p>
            <p class="tar">
                <a href="{{url('orderoverview')}}/{{$order_num}}" target="_blank" class="tdu juhuang">查看订单总详情</a>
            </p>
            <div class="split"></div>
            <p>花点时间，继续打造梦想座驾吧！</p>
            <p><b class="blue">打造更个性的座驾</b></p>
            <p class="fs14">原厂选装精品折扣率：{{ $baojia['bj_xzj_zhekou']}} %</p>
            <p class="fs14">原厂选装精品，欢迎选购：</p>
            
<?php 
if(count($userXzjData) >0){
	$flag_dg = "hide";//已结订购  隐藏
	$flag_dg_checkbox = "disabled";//已结订购  隐藏
	$input_css = "style=border:none;";
}else{
	$input_css = "";
	$flag_dg = "";
	$flag_dg_checkbox = '';
}
if (!empty($baojia['bj_producetime'])){
	$flag_xc = "";
}else{
	$flag_xc = "<span class='pl5 xing'>*</span>";
}
?>
			<form action="{{route('cart.xzjpost')}}" method="post" name='xzj_dinggou_form'>  
            <table class="tbl bgtbl">
                <tr>
                    <th width="50">选择</th>
                    <th>名称</th>
                    <th>型号/说明</th>
                    <th>厂商指导价</th>
                    <th>安装费</th>
                    <th>含安装费折后总单价</th>
                    <th width="108">选择购买件数</th>
                    <th>金额</th>
                </tr>
                 <?php 
                $ycTotal = 0;
                $checkCut = 'N';//检测选装件 是否有减  默认没有减
                ?>
               @foreach($xzj_daili as $k=>$v)
                <tr data-id="3">
                    <td class="tac"><input style="width:auto;" type="checkbox" name="" id="" {{$flag_dg_checkbox}} <?php if(isset($userXzjData[$v['id']])){echo 'checked';}?>></td>
                    <td>{{$flag_xc.$v['xzj_title']}}</td>
                    <td>{{$v['xzj_model']}}</td>
                    <td>{{$v['xzj_guide_price']}}</td>
                    <td class="tac">{{$v['xzj_fee']}}</td>
                    <td class="tac">{{$v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100+$v['xzj_fee']}}</td>
                    <td>
                        <div class="xuan">
                            <div class="x-w"> 
                                <a ms-click="prev" class="prev {{$flag_dg}}">-</a>
                                <input type="text" readonly class="input" {{$input_css}} name="ycxzj_num[{{$v['id']}}]" value="<?php if(isset($userXzjData[$v['id']])){echo $userXzjData[$v['id']];}else{echo '0';}?>">
                                <a ms-click="next({{min($v['xzj_has_num'],$v['xzj_max_num'])}})" class="next {{$flag_dg}}">+</a>
                                <input type="hidden"  value="{{$v['id']}}"  name="xzj_daili_id[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_title']}}"  name="ycxzj_title[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_model']}}"  name="ycxzj_model[{{$v['id']}}]">
                                <input type="hidden"  value=""  name="xzj_brand[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_guide_price']}}"  name="ycxzj_guide_price[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_fee']}}"  name="ycxzj_fee[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100+$v['xzj_fee']}}"  name="discount_price[{{$v['id']}}]">
                                <input type="hidden"  value="1"  name="ycxzj_is_yc[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_front']}}"  name="ycxzj_is_front[{{$v['id']}}]">
                            </div>
                        </div>
                    </td>
                    <td class="tac">
                    <?php 
                    if(isset($userXzjData[$v['id']])){
                    	$t= intval($userXzjData[$v['id']]*($v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100+$v['xzj_fee']));
                    	$ycTotal += $t;
                    	echo $t;
                    } 
                    if(isset($userXzjAllData[$v['id']]) && $userXzjAllData[$v['id']]['xzj_status'] == 1 && ($userXzjAllData[$v['id']]['xzj_modify'] < $userXzjAllData[$v['id']]['num'])){
                    	$checkCut = 'Y';
                    }
                    ?></td>
                </tr>
                @endforeach
            </table>
            <div>
                <small class="fl">温馨提示：加<span class="pl5 xing">*</span>的选装精品需在24小时内订购。</small>
                <div class="time fl m-t-10">
                    <div class="jishi jishi2 " id="countdown">
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

                <small class="wp45 fr tar di"><span>合计金额：<label>{{$ycTotal}}</label></span></small>
                <div class="clear"></div>

                <input type="hidden" name="price">
            </div>

            <p class="fs14 clear m-t-10">非原厂选装精品，欢迎选购：</p>
            <table class="tbl bgtbl">
                <tr>
                    <th width="50">选择</th>
                    <th>品牌</th>
                    <th>名称</th>
                    <th>型号/说明</th>
                    <th>含安装费折后总单价</th>
                    <th width="108">选择购买件数</th>
                    <th>金额</th>
                </tr>
                <?php 
                $fycTotal = 0;
                ?>
                @foreach ($fycxzj_daili as $k => $v)
                <tr data-id="{{$v['mid']}}">
                    <td class="tac"><input style="width:auto;" type="checkbox" name="" id="" {{$flag_dg_checkbox}} <?php if(isset($userXzjData[$v['mid']])){echo 'checked';}?>></td>
                    <td>{{$v['xzj_brand']}}</td>
                    <td>{{$v['xzj_title']}}</td>
                    <td>{{$v['xzj_model']}}/{{$v['xzj_notice']}}</td>
                    <td class="tac">{{$v['discount_price']}}</td>
                    <td>
                        <div class="xuan">
                            <div class="x-w"> 
                                <a ms-click="prev" class="prev {{$flag_dg}}">-</a>
                                <input type="text" readonly  {{$input_css}} value="<?php if(isset($userXzjData[$v['mid']])){echo $userXzjData[$v['mid']];}else{echo '0';}?>" class="input" name="ycxzj_num[{{$v['mid']}}]" class="input">
                                <a ms-click="next({{min($v['xzj_has_num'],$v['xzj_max_num'])}})" class="next {{$flag_dg}}">+</a>
                            	<input type="hidden"  value="{{$v['mid']}}"  name="xzj_daili_id[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_fee']}}"  name="ycxzj_fee[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_title']}}"  name="ycxzj_title[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_model']}}"  name="ycxzj_model[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_brand']}}"  name="xzj_brand[{{$v['mid']}}]">
                                <input type="hidden"  value=""  name="ycxzj_guide_price[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['discount_price']}}"  name="discount_price[{{$v['mid']}}]">
                                <input type="hidden"  value="0"  name="ycxzj_is_yc[{{$v['mid']}}]">
                                <input type="hidden"  value="0"  name="ycxzj_is_front[{{$v['mid']}}]">
                            </div>
                        </div>
                    </td>
                    <td class="tac">
                    <?php 
                    if(isset($userXzjData[$v['mid']])){
                    	$t1 = intval($userXzjData[$v['mid']]*$v['discount_price']);
                    	$fycTotal += $t1;
                    	echo $t1;
                    } 
                    if(isset($userXzjAllData[$v['mid']]) && $userXzjAllData[$v['mid']]['xzj_status'] == 1 && ($userXzjAllData[$v['mid']]['xzj_modify'] < $userXzjAllData[$v['mid']]['num'])){
                    	$checkCut = 'Y';
                    }
                    ?></td>
                </tr>
                @endforeach
                
            </table>
            <p>
                <small class="wp45 fl di">&nbsp;</small>
                <small class="wp45 fr tar di"><span>合计金额：<label>{{$fycTotal}}</label></span></small>
                <input type="hidden" name="price">
            </p>
            <div class="clear"></div>
            <p class="tac">
            @if($xzj_status==0)
            	@if(count($userXzjData) >0)
	            	<a href="javascript:;" class=" btn btn-s-md btn-danger btn-danger-hover">已订购</a>
	                <a ms-on-click="modifyDinggou"  href="javascript:;" class=" btn  end  fs16 btn-empty">修改订购</a>
	            @else
	            	<input type="button" ms-on-click="dinggou"  class="btn btn-s-md btn-danger" value="订购">
	            	
	            @endif
	            
	        @elseif($xzj_status==1)
	        	@if($checkCut == 'Y')
			        <div class="times times2 tac">
		                    <center>      
		                        <a href="javascript:;" class=" btn btn-s-md btn-danger btn-danger-hover fs18">正在确认</a>
		                    </center>
		                    倒计时：
		                    <p class="tac fs18 mt10" style="display:inline-block;">
		                        <span class="juhuang countdown"><span>X</span>天<span>X</span>小时<span>X</span>分<span>X</span>秒</span>
		                    </p>
		            </div>
            	@else
            		<a href="javascript:;" class=" btn btn-s-md btn-danger btn-danger-hover">已订购</a>
	                <a ms-on-click="modifyDinggou"  href="javascript:;" class=" btn  end  fs16 btn-empty">修改订购</a>
	            
            	@endif
            @else
            	<a href="javascript:;" class=" btn btn-s-md btn-danger btn-danger-hover">已订购</a>
            	<a ms-on-click="modifyDinggou"  href="javascript:;" class=" btn  end  fs16 btn-empty">修改订购</a>
            
	        
            @endif
            </p>
            <input type="hidden" value="ycxzj" name="tj_type" >
            <input type="hidden" value="{{$order_num}}" name="order_num" >
            <input type="hidden" value="{{$order['id']}}" name="id" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			</form>
            
			<form action="{{route('cart.xzjpost')}}" method="post" name="xzj_modify_form_sure">
            <div id="dinggou-modify"  for="xuanfu-modify" class="popupbox">
                <div class="popup-title">修改选装精品</div>
                <div class="popup-wrapper">
                
                    <div class="popup-content">
                        <div class="">  
 
                            <p class="fs14">原厂选装精品折扣率：</p>
                            <p class="fs14">原厂选装精品，欢迎选购：</p>

                            <table class="tbl bgtbl">
                                <tr>
                                    <th width="50">选择</th>
                                    <th>名称</th>
                                    <th>型号/说明</th>
                                    <th>厂商指导价</th>
                                    <th>安装费</th>
                                    <th>含安装费折后总单价</th>
                                    <th width="108">选择购买件数</th>
                                    <th>金额</th>
                                </tr>
                                
                                @foreach($xzj_daili as $k=>$v)
                <tr data-id="3">
                    <td class="tac"><input style="width:auto;" type="checkbox" name="" id="" <?php if(isset($userXzjData[$v['id']])){echo $userXzjData[$v['id']];}else{echo '0';}?>' <?php if(isset($userXzjData[$v['id']])){echo 'checked disabled';}?>></td>
                    <td>{{$flag_xc.$v['xzj_title']}}</td>
                    <td>{{$v['xzj_model']}}</td>
                    <td>{{$v['xzj_guide_price']}}</td>
                    <td class="tac">{{$v['xzj_fee']}}</td>
                    <td class="tac">{{$v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100+$v['xzj_fee']}}</td>
                    <td>
                        <div class="xuan">
                            <div class="x-w"> 
                                <a ms-click-1="prev" ms-click-2="prevFix" class="prev">-</a>
                                <input type="text" readonly class="input"  name="ycxzj_num[{{$v['id']}}]" data-def-value='<?php if(isset($userXzjData[$v['id']])){echo $userXzjData[$v['id']];}else{echo '0';}?>' value="<?php if(isset($userXzjData[$v['id']])){echo $userXzjData[$v['id']];}else{echo '0';}?>">
                                <a ms-click-1="next({{min($v['xzj_has_num'],$v['xzj_max_num'])}})"  ms-click-2="nextFix" class="next">+</a>
                            </div>
                                <div class="xztip psr">
                                    <div class="xztip-wrapper psa">
                                        <p>
                                                        ！售方可能已实质备货，修改请求需取得其同意方可生效
                                        </p>
                                    </div>
                                </div>
                                <input type="hidden"  value="{{$v['id']}}"  name="xzj_daili_id[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_title']}}"  name="ycxzj_title[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_model']}}"  name="ycxzj_model[{{$v['id']}}]">
                                <input type="hidden"  value=""  name="xzj_brand[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_guide_price']}}"  name="ycxzj_guide_price[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_fee']}}"  name="ycxzj_fee[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100+$v['xzj_fee']}}"  name="discount_price[{{$v['id']}}]">
                                <input type="hidden"  value="1"  name="ycxzj_is_yc[{{$v['id']}}]">
                                <input type="hidden"  value="{{$v['xzj_front']}}"  name="ycxzj_is_front[{{$v['id']}}]">
                        </div>
                    </td>
                    <td class="tac">
                    <?php 
                    if(isset($userXzjData[$v['id']])){
                    	$t= intval($userXzjData[$v['id']]*($v['xzj_guide_price']*$baojia['bj_xzj_zhekou']/100+$v['xzj_fee']));
                    	echo $t;
                    } 
                    ?></td>
                </tr>
                @endforeach
                
                                
                            </table>
                            <div>
                                <small class="fl">温馨提示：加<span class="pl5 xing">*</span>的选装精品需在24小时内订购。</small>
                                <div class="time fl m-t-10">
                                    <div class="jishi jishi2 " id="countdown2">
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

                                <small class="wp45 fr tar di"><span>合计金额：<label>{{$ycTotal}}</label></span></small>
                                <div class="clear"></div>

                                <input type="hidden" name="price">
                            </div>

                            <p class="fs14 clear m-t-10">非原厂选装精品，欢迎选购：</p>
                            <table class="tbl bgtbl fyc_table">
                                <tr>
                                    <th width="50">选择</th>
                                    <th>品牌</th>
                                    <th>名称</th>
                                    <th>型号/说明</th>
                                    <th>含安装费折后总单价</th>
                                    <th width="108">选择购买件数</th>
                                    <th>金额</th>
                                </tr>
                                 @foreach ($fycxzj_daili as $k => $v)
                <tr data-id="{{$v['mid']}}">
                    <td class="tac"><input style="width:auto;" type="checkbox" name="" id="" <?php if(isset($userXzjData[$v['mid']])){echo 'checked disabled';}?>></td>
                    <td>{{$v['xzj_brand']}}</td>
                    <td>{{$v['xzj_title']}}</td>
                    <td>{{$v['xzj_model']}}/{{$v['xzj_notice']}}</td>
                    <td class="tac">{{$v['discount_price']}}</td>
                    <td>
                        <div class="xuan">
                            <div class="x-w"> 
                                <a ms-click-1="prev" ms-click-2="prevFix" class="prev">-</a>
                                <input type="text" readonly data-def-value='<?php if(isset($userXzjData[$v['mid']])){echo $userXzjData[$v['mid']];}else{echo '0';}?>'  value="<?php if(isset($userXzjData[$v['mid']])){echo $userXzjData[$v['mid']];}else{echo '0';}?>" class="input" name="ycxzj_num[{{$v['mid']}}]" class="input">
                                <a ms-click="next({{min($v['xzj_has_num'],$v['xzj_max_num'])}})" ms-click-2="nextFix" class="next">+</a>
                            </div>
                            <div class="xztip psr">
                               <div class="xztip-wrapper psa">
                               <p>
                                                        ！售方可能已实质备货，修改请求需取得其同意方可生效
                                 </p>
                                </div>
                            </div>
                            	<input type="hidden"  value="{{$v['mid']}}"  name="xzj_daili_id[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_fee']}}"  name="ycxzj_fee[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_title']}}"  name="ycxzj_title[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_model']}}"  name="ycxzj_model[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['xzj_brand']}}"  name="xzj_brand[{{$v['mid']}}]">
                                <input type="hidden"  value=""  name="ycxzj_guide_price[{{$v['mid']}}]">
                                <input type="hidden"  value="{{$v['discount_price']}}"  name="discount_price[{{$v['mid']}}]">
                                <input type="hidden"  value="0"  name="ycxzj_is_yc[{{$v['mid']}}]">
                                <input type="hidden"  value="0"  name="ycxzj_is_front[{{$v['mid']}}]">
                        </div>
                    </td>
                    <td class="tac">
                    <?php 
                    if(isset($userXzjData[$v['mid']])){
                    	$t1 = intval($userXzjData[$v['mid']]*$v['discount_price']);
                    	echo $t1;
                    } 
                    ?></td>
                </tr>
                @endforeach
                                
                            </table>
                            <p>
                                <small class="wp45 fl di">&nbsp;</small>
                                <small class="wp45 fr tar di"><span>合计金额：<label>{{$fycTotal}}</label></span></small>
                                <input type="hidden" name="price">
                            </p>
                            <div class="clear"></div>
                        </div> 

                    </div>
                    <div class="popup-control">
                        <a ms-on-click="initorder" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                        <div class="clear"></div>
                    </div>
                    <input type="hidden" value="xzj_modify" name="tj_type" >
		            <input type="hidden" value="{{$order_num}}" name="order_num" >
		            <input type="hidden" value="{{$order['id']}}" name="id" >
		            <input type="hidden" name="_token" value="{{ csrf_token() }}">
		            
                </div>
            </div>
			</form>
            <div id="dinggou-order" for="xuanfu-sure" class="popupbox">
                <div class="popup-title">确认修改选装</div>
                <div class="popup-wrapper">
                
                    <div class="popup-content">
                        <p class="fs14">请核对您的选装精品修改提议：</p>
                        <table class="tbl bgtbl">
                            <tr>
                                <th>品牌</th>
                                <th>名称</th>
                                <th>型号/说明</th>
                                <th>原订购数量</th>
                                <th>提议修改数量</th>
                            </tr>
                            <tr ms-repeat-yc="ycjingping" ms-data-id="yc.id">
                                <td class="tac">原厂</td>
                                <td class="tac"><!--yc.name--></td>
                                <td class="tac"><!--yc.xinghao--></td>
                                <td class="tac"><!--yc.oldtotal--></td>
                                <td class="tac" ms-attr-class="yc.shuliang < yc.oldtotal ? 'tac juhuang':'tac'"><!--yc.shuliang--></td>
                            </tr>
                            <tr ms-repeat-fyc="fycjingping" ms-data-id="fyc.id">
                                <td class="tac"><!--fyc.pingpai--></td>
                                <td class="tac"><!--fyc.name--></td>
                                <td class="tac"><!--fyc.xinghao--></td>
                                <td class="tac"><!--fyc.oldtotal--></td>
                                <td class="tac" ms-attr-class="fyc.shuliang < fyc.oldtotal ? 'tac juhuang':'tac'"><!--fyc.shuliang--></td>
                            </tr>
                          
                        </table>
                    </div>
                    <div class="popup-control">
                        <a ms-on-click="sureorder" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                        <div class="clear"></div>
                    </div>
                   </form>
                </div>
            </div>
			
            <!-- //同意或者不同意时的展示 -->
            @if($xzj_status==3)
			<p class="fs14">因售方已完成备货，很遗憾一下修改提议将无法满足，敬请谅解，如有疑问请联系售方（{{$jxs['d_name']}} : {{$jxs['d_tel']}}）</p>
			
				
            
            <table class="tbl bgtbl">
                <tr>
                    <th>品牌</th>
                    <th>名称</th>
                    <th>型号/说明</th>
                    <th>原订购数量</th>
                    <th>提议修改数量</th>
                </tr>
                @foreach($userXzjAllData as $k=>$v)
                	@if($v['xzj_status']==3)
                <tr>
                    <td class="tac"><?=$v['is_yc']==1?'原厂':$v['xzj_brand']?></td>
                    <td class="tac">{{$v['xzj_name']}}</td>
                    <td class="tac">{{$v['xzj_model']}}</td>
                    <td class="tac">{{$v['num']}}</td>
                    <td class="tac juhuang">{{$v['xzj_modify']}}</td>
                </tr>
                	@endif
                @endforeach
               
              
            </table>
			@endif

            <div id="dinggou-tip" class="popupbox">
                <div class="popup-title">温馨提示</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <p class="fs14 pd ti">       
                        若您”确定订购“后再次提出减少订购数量的要求，
                        由于售方可能已进行实质备货，需要取得其同意方可完成
                        数量修改，故存在无法再减少数量的风险，请慎重决定是
                        否订购！
                        </p>
                    </div>
                    <div class="popup-control">
                        <a ms-on-click="xzorder" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定订购</a>
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            
			

            <div class="dinggou-fix xuanfu-modify">
                <span>修改选装精品</span>
                <a href="javascript:;" class="btn btn-danger fs14 btn-recover">恢复</a>
            </div>
            <div class="dinggou-fix xuanfu-sure">
                <span>确认修改选装精品</span>
                <a href="javascript:;" class="btn btn-danger fs14 btn-recover">恢复</a>
            </div>

        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
	seajs.use(["module/item/item-pay-2-sucess", "module/common/common","module/common/hc.popup.jquery", "bt"],function(a,b,c,d){
    a.init()
    $("#countdown,#countdown2").CountDown({
      startTime:'{{$order["created_at"]}}',
      endTime :'{{date("Y-m-d H:i:s",strtotime($order["created_at"])+24*60*60)}}',
      timekeeping:'countdown'
    })

    $(".countdown").CountDown({
      startTime:'2016-3-14 16:14:35',
      endTime :'2016-3-17 13:29:25',
      timekeeping:'countdown'
    })

})
    </script>
@endsection
