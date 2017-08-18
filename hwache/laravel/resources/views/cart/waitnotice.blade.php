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
    <div class="container  m-t-86 pos-rlt ">
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
        <div class="wapper has-min-step">  
            <p>尊敬的客户：</p>
            <p class="ti">您与经销商就订单部分修改达成共识，感谢您的理解与支持！</p>
            
            <p class="fs14"><b>经销商交车通知发出时限：</b>2015年12月11日  24：00：00</p>
            <p class="fs14"><b>倒计时剩余时间：</b>XX天XX小时XX分XX秒</p>
            <p class="fs14">如果经销商超时，您将有权取消订单，退还已付买车担保金，并获得华车网加信宝提供的额外补偿：</p>
            <p class="fs14">歉意金人民币499.00元和买车担保金人民币12,345.00元冻结期间的利息（日利率万分之二）。</p>
            <p class="fs14">如您对随后经销商提出的延期交车修改方案满意，您也可在获得歉意金和前期买车担保金利息的基础上，选择不退还买车担保金，执行修改方案，</p>
            <p class="fs14">华车网加信宝继续为您保驾护航！</p>
            <p class="fs14">请您暂时按捺一下拥有心仪座驾的急迫性情，期待我们为您精心准备得妥妥哒！</p>
            <p class="tac">
                <a href="{{url('orderoverview')}}/{{$order_num}}" class="tdu juhuang">查看订单总详情</a>
            </p>



            <div class="wapper has-min-step">

            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：</td>
                    <td width="50%">
                        <div class="psr fs14">
                          订单时间：2015年10月28日
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
                    </td>
                </tr>
            </table>
           
            <table class="tbl c-tbl">
                <tr>
                    <td class="" width="50%">品牌：<span class="fn">{{$brand[0]}}</span></td>
                    <td width="50%">车系：<span class="fn">{{$brand[1]}}</span></td>
                </tr>
                <tr>
                    <td class="">车型规格:<span class="fn">{{$brand[2]}}</span></td>
                    <td>类别:<span class="fn">
                    
                    </span></td>
                </tr>
                <tr>
                    <td class="">数量：<span class="fn">{{$cart_num}}</span></td>
                    <td>生产国别：<span class="fn">{{$guobieTitle}}</span></td>
                </tr>
                <tr>
                    <td class="">座位数：<span class="fn">{{$carmodelInfo['seat_num']}}</span></td>
                    <td class="">排放标准：<span class="fn">{{$paifangTitle}}</span></td>
                    
                </tr>
                <tr>
                    <td class="">车身颜色：<span class="fn">{{$body_color}}</span></td>
                    <td>
                        内饰颜色：<span class="fn">{{$interior_color}}</span>
                    </td>
                </tr>
                <tr>
                    <td class="">行驶里程：<span class="fn">{{$bj_licheng}}</span></td>
                    <td>出厂年月：<span class="fn">{{$baojia['bj_producetime']}}</span></td>
                </tr>
                <tr>
                    <td>车辆用途：<span class="fn">
                    <?php if ($buytype==0): ?>
                             	个人用车
                    <?php else: ?>
                         	公司用车
                    <?php endif ?>
                    </span></td>
                    <td>上牌地区：<span class="fn"></span></td>
                </tr>
                <tr>
                    <td class="" colspan="2">基本配置：<span class="fn"></span></td>
                </tr>
               
            </table>
            
            <br>
            <!--车价与买车担保金-->
            <div class="box">
                <div class="title">
                    <label ms-click="toggleContent">一、车价</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                    <h2><b class="fs14">厂商指导价：</b>{{$carmodelInfo['zhidaojia']}}</h2>
                    <p class="fs14"><b class="fs14">您的华车车价：</b>人民币<span class="juhuang fs14">{{$baojia['bj_lckp_price']+$baojia['bj_agent_service_price']}}</span>元 （伍拾贰万元整）</p>
                    <table>
                        <tr>

                            <td valign="top fs14"><span class="fs14">其中包括：</span></td>
                            <td>
                                <p class="fs14">1.裸车开票价格（您须在经销商处全额支付）：人民币<span class="juhuang">{{$baojia['bj_lckp_price']}}</span>元（伍拾万元整）</p>
                                <p class="fs14">2.华车服务费（订单完成后从买车担保金中扣除）：人民币<span class="juhuang">{{$baojia['bj_agent_service_price']}}</span>元（贰万元整）</p>
                            </td>
                        </tr>
                    </table>
                    <p><a href="#" class="tdu juhuang fs14">查看客户资金流程图</a></p>
                </div>
            </div>

            <!--交车时限与地点-->
            <div class="box">
                <div class="title">
                    <label ms-on-click="toggleContent">二、交车时限与地点</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def">
                   
                    <table class="tbl">
                        <tr>
                            <td width="50%"><p class="fs14"><b>交车时限：</b>2015年12月10日24：00：00</p></td>
                            <td width="50%"><p class="fs14"><b>交车地点范围约定：</b>苏州、上海</p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p class="fs14"><b>交车地点：</b>江苏省苏州市竹园路209号</p></td>
                            <td width="50%"><p class="fs14"><b>经销商名称：</b></p></td>
                        </tr>
                    </table>
                    <p class="tal"><b class="tal">交车地点图示</b></p>
                    <img width="380" src="themes/images/item/map.gif">
                   
                    <div class="clear"></div>
                </div>
            </div>

            <!--上牌-->
            <div class="box">
                <div class="title">
                    <label ms-on-click="toggleContent">三、上牌</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                  
                    <table class="tbl">
                    @if($baojia['bj_shangpai'] ==0)
                        <tr>
                            <td width="30%" align="center"><p class="fs16"><b>上牌服务约定</b></p></td>
                            <td>
                                <p><b>接受安排</b>（指定上牌）</p>
                                <p><small>您须委托经销商代办上牌手续，且向经销商支付下方标准的服务费。</small></p>
                            </td>
                        </tr>
                        @elseif($baojia['bj_shangpai'] ==1)
                        <tr>
                            <td width="30%" align="center"><p class="fs16"><b>上牌服务约定</b></p></td>
                            <td>
                                <p><b>接受安排</b>（本人上牌）</p>
                                <p><small>您须亲自办理上牌手续，经销商不代办。</small></p>
                            </td>
                        </tr>
                        @elseif($baojia['bj_shangpai'] ==2)
                        <tr>
                            <td width="30%" align="center"><p class="fs16"><b>上牌服务约定</b></p></td>
                            <td>
                                <p><b>接受安排</b>（自选上牌）</p>
                                <p><small>您在收到交车通知后选择：由您亲自办理上牌手续，或者委托经销商代办，并向其支付下方标准的服务费。</small></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" align="center"><p class="fs16"><b>  上牌服务费约定</b></p></td>
                            <td>
                                <p>人民币{{$baojia['bj_shangpai_price']}}元</p>
                            </td>
                        </tr>
                        @endif
                    </table>
                   
                </div>
            </div>
            
        
        
        </div>




            <div class="split"></div>
            <p>花点时间，继续打造梦想座驾吧！</p>
            <p><b class="blue">打造更个性的座驾</b></p>
            <p class="fs14">原厂选装精品折扣率：{{$baojia['bj_xzj_zhekou']}}</p>
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
                    if(isset($userXzjAllData[$v['mid']]) && $userXzjAllData[$v['mid']]['xzj_status'] == 1 && $userXzjAllData[$v['mid']]['xzj_modify'] < $userXzjAllData[$v['mid']]['num']){
                    	$checkCut = 'Y';
                    }
                    ?>
                    </td>
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
            <input type="hidden" value="xzj_3" name="tj_type" >
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