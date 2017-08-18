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
    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li class="step-cur">预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content pdi">
                    <small>开始预约</small>
                    <i></i>
                    <small class="juhuang">反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi" ms-controller="item">
        <form action="{{url('cart/postyuyuenoconfirm')}}" method="post">
        <input type="hidden" name="order_num" value="{{ $order_num}}">
        <input type="hidden" name="id" value="{{ $id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="wapper has-min-step" style="overflow: visible;">
            <h1>尊敬的客户：</h1>
            <h1 class="ti">我们怀着急切的心情告诉您，提车事项得到经销商进一步回复，请您尽快选择最合适的方案。</h1>
            <p class="tac m-t-10"><a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang tdu ">查看订单总详情</a></p>
            <br>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：{{$order_num}}</td>
                    <td width="50%">
                        <div class="psr fs14">
                          	订单时间：{{ddate($order['created_at'])}}
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
            <div class="m-t-10 clear"></div>
            <p class="fs14 tdu"><b>1.您座驾的回程方式，经销商已提供了以下方案，请选择最合适的一种：</b></p>
            <div class="radio i-checks">
                <label class="fs14">
                    <input ms-on-click="songche(0)" checked="" type="radio" name="songche" value="自己开回">
                    <i></i>
                    自己开回（无送车服务费）
                </label>
            </div>
            @if(isset($pdiReply['daijia']['choose']))
            <div class="radio i-checks">
                <label class="fs14">
                    <input ms-on-click="songche(1)" type="radio" name="songche" value="代驾送车" >
                    <i></i>
                    代驾送车  
                </label>
            </div>
            @endif
            @if(isset($pdiReply['transport']['choose']))
            <div class="radio i-checks">
                <label class="fs14">
                    <input ms-on-click="songche(2)" type="radio" name="songche" value="板车运输送车" >
                    <i></i>
                    板车运输送车
                </label>
            </div>
            @endif
            <table class="tbl songche" style="display: none">
             @if(isset($pdiReply['daijia']['choose']))
                <tr>
                    <td width="50%">
                        <p class="fs14"><b>服务费：</b>人民币{{$pdiReply['daijia']['fee']}}元</p>
                    </td>
                    <td width="50%"><p class="fs14"><b>支付方式：</b>
                   @if( $pdiReply['daijia']['pay_type'] == "pay_first")
                   		在经销商处支付   （提车时先支付后送车；）
                   @else
                   		车送到目的地后当场支付
                   @endif
                    </p></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>送车大致地址：</b>{{$order_attr['deliver_addr']}}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>说明：</b>仅指代驾服务的费用，油费、过路费等实报实销。为保障您的权益，请在经销商处另行签订书面委托协议。</p>
                    </td>
                </tr>
              @endif
            </table>
            <table class="tbl songche" style="display: none">
            @if(isset($pdiReply['transport']['choose']))
                <tr>
                    <td width="50%">
                        <p class="fs14"><b>运费：</b>人民币{{$pdiReply['transport']['fee']}}元</p>
                    </td>
                    <td width="50%"><p class="fs14"><b>运输险保费：</b>人民币{{$pdiReply['transport']['bx_fee']}}元   </p></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>送车大致地址：</b>{{$order_attr['deliver_addr']}}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>支付方式：</b>
                        
                        @if( $pdiReply['transport']['pay_type'] == "pay_first")
                   			在经销商处支付   （提车时先支付后送车；）
	                   @else
	                   		车送到目的地后当场支付
	                   @endif
                   </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>说明：</b>运费中已含送车产生的油费、过路费。为保障您的权益，请在经销商处另行签订书面委托协议。如您的送车地点属于卡车、外地车限行区域，则只能将车送到最接近的可交车地点，由您与经销商直接协商确定。</p>
                    </td>
                </tr>
               @endif
            </table>
    <?php 
    $baoxianStr = array(
			    		1=>"自燃损失险",
			    		2=>"新增加设备损失险",
			    		3=>"发动机涉水损失险",
			    		4=>"修理期间费用补偿险",
			    		5=>"车上货物责任险",
			    		6=>"机动车损失险无法找到第三方特约险",
			    		7=>"指定修理厂险",
			    		8=>"精神损害抚慰金责任险",
			    		9=>"增加第三者责任险保额",
			    		10=>"增加车上人员责任险保额"
			    );
    $baoxian = $pdiReply['baoxian'];
    ?>
            
            <p class="fs14 tdu"><b>2.您感兴趣的其他车辆商业保险，经销商报价如下，请选择您需要的保险项目（可多选或不选）：</b></p>
           @if(count($baoxian)>0)
          	 	@foreach($baoxian as $k => $v)
	            <div class="radio i-checks">
	                <label class="fs14">              
	                    @if(isset($v['choose']) && $v['choose'] == 'Y')
		                    <input type="checkbox" name="baoxian[{{$k}}]" value="Y" >
		                    <i></i>
	                    	<b>{{$baoxianStr[$k]}}</b><span class="ml20">(首年保费报价：人民币{{$v['bf']}}元     说明：{{$v['bfsm']}})</span>
	                    @else
	                    	<input type="checkbox" name="baoxian[{{$k}}]" value="Y" disabled>
	                    	<b>{{$baoxianStr[$k]}}</b>(恕该保险公司无法提供)
	                    @endif
	                </label>
	            </div>
            	@endforeach
            @else
            <p>暂无其他险种</p>
            @endif
            
            <br>
            <p class="fs14 tdu"><b>3.您的特别要求：</b></p>
            <p><input style="width:100%" type="text" class="form-control address" placeholder="{{$order_attr['other']}}" disabled></p>
           
 

            <p class="fs14"><b>经销商回复如下，请选择您需要办理的项目（可多选或不选）：</b></p>
            @if(count($pdiReply['project_ok']>0))
            	@foreach($pdiReply['project_ok'] as $k => $v)
		            <div class="radio i-checks">
		                <label class="fs14">
		                    <input type="checkbox" name="project_ok[]" value="{{$v['name']}}">
		                    <i></i>
		                    办{{$v['name']}}： 可以办理 办理费用：人民币{{$v['money']}}元 需要时间{{$v['day']}}个自然日 交车时间<?=$v['effect']=="N"?"无":"有"?>影响
		                    @if($v['effect']=="Y" && isset($v['jc_date']) )
		                    	，交车时间延后到{{$v['jc_date']}}
		                    @endif
		                </label>
		            </div>
            	@endforeach
            @endif
            <!-- 不能办理项目 -->
            @if(count($pdiReply['project_not']>0))
            	@foreach($pdiReply['project_not'] as $k => $v)
		            <p class="psr fs14">
		                <i class="no"></i>
		               	 办{{$v}}： 恕无法办理  ， 华车网补充建议：请向当地车管部门咨询。
		                <em class="no psa">不可选哦</em>
		            </p>
		            <br>
            	@endforeach
            @endif
            
            
            <!-- 可以办理的服务或者赠品 -->
             @if(count($pdiReply['service_ok']>0))
            	@foreach($pdiReply['service_ok'] as $k => $v)
            		<p class="psr fs14">
            		 	<input type="checkbox" name="service_ok[]" value="{{$v}}">
		                {{$v}}：  可以满足  ， 华车网补充建议：特别喜欢的话就买一个吧。
		            </p>
            	@endforeach
            @endif
            <!-- 不可以办理的服务或者赠品 -->
             @if(count($pdiReply['service_not']>0))
            	@foreach($pdiReply['service_not'] as $k => $v)
            		<p class="psr fs14">
		                <i class="no"></i>
		                {{$v}}：  恕无法提供  ， 华车网补充建议：特别喜欢的话就买一个吧。
		                <em class="no psa">不可选哦</em>
		            </p>
		            <br>
            	@endforeach
            @endif
            <br>    
            <p class="fs14 tdu"><b>4.您提议的提车日期 :</b></p>
            <p class="fs14"><b>提议的提车时间为 :</b>{{$order_attr['pdi_date_client']}}</p>
            @if($pdiReply['act_date'] == 'Y')
            <p class="fs14"><span class="xing">*</span>经销商已同意上述交车日期，且无超期费</p>
            @else
            <p class="fs14"><span class="xing">*</span>经销商已与您协商，达成的交车日期：{{$pdiReply['jc_date']}}（达成的超期提车补偿金额：人民币{{intval($pdiReply['over_fee'])}}元在经销商处支付）</p>
            
            @endif
            <p class="fs14"><span class="xing">*</span>如有异议请<a href="javascript:;" ms-on-click="showCommite" class="tdu juhuang">点此</a>告知华车平台，由平台进行调解和裁判。</p>
            
            <!-- 争议单独页面处理 -->
            {{--
            <textarea style="width:500px;height:80px;border:1px solid #dcdddd;display: none;" placeholder="请输入内容"></textarea>
            <p class="tal">
                <a href="javascript:;" class="btn btn-s-md btn-danger sure bt-small ">提交异议</a>
                <a href="javascript:;" class="btn btn-s-md btn-danger sure bt-small ml20"> 返回 </a>
                <a href="javascript:;" class="btn btn-s-md btn-danger oksure ml20 bt-small">已提交，平台正在调解</a>
            </p>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <p class="fs14"><b>华车平台调解方案如下：</b></p>
            <p class="fs14"><span class="xing">*</span><b>提车时间定为：2015年10月6日  上午/下午</b></p>
            <p class="fs14"><span class="xing">*</span><b>超期费金额：人民币2,000.00元</b></p>
			--}}
            <hr class="dashed">
            
            <table class="tbl ">
                <tr>
                    <td width="50%">
                        <p class="fs14"><b>计划上牌车主名称：</b>{{$order['reg_name']}}</p>
                    </td>
                    <td width="50%"><p class="fs14"><b>提车人姓名：</b>{{$ticheren['username']}}</p></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>上牌车主名称与提车人姓名是否一致：</b>
                        <?php 
                        if(($order_attr['agreement'])==1){
                        	echo "一致";
                        }else{
                        	echo "不一致";
                        }
                        ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fs14"><b>提车人需要准备的文件资料：</b>{{$needfile}}</p>
                    </td>
                </tr>
              
            </table>

            <div class="fs14">
                <span class="fl"><b>温馨提示：</b>请在右侧时限内确认，超时平台将按默认结果自动提交。</span>
                <div class="time fl">
                    <div class="jishi jishi2 jishi3">
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
                <div class="clear"></div>
            </div>
            <div class="box">
                <div class="box-inner box-inner-def tbl">
                    <p class="center">
                        
                        <input type="submit" value="确认提交" class="btn btn-s-md btn-danger fs16">
                    </p>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-pdi-confirm", "module/common/common", "bt"]);
    </script>
@endsection