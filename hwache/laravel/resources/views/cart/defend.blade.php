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
                <li>预约交车<i></i></li>
                <li class="step-cur">付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content payment">
                    <small class="juhuang">正在交车</small>
                    <i></i>
                    <small>核实信息</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content" ms-controller="item">
        <div class="wapper has-min-step">
        @if(empty($mediate))
            <div class="step-n step-n-2"></div>
        @else
             <div class="step-n step-n-3"></div>
        @endif
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：</b></p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b>2015年10月28日</p></li>
                <li class="pdi-more">
                    <div class="psr fs14">
                      <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                         <b>更多</b>
                      </span>
                      <p class="tm tm-long" style="display: none;width: 400px;">
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
                    <p class="fs14">{{$brand[0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$brand[1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$brand[2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$body_color}}（{{$interior_color}}）</p></li>
                <div class="clear"></div>
            </ul>
    
            <p class="tac m-t-10"><a href="{{url('orderoverview')}}/{{$order_num}}"  target="_blank" class="juhuang tdu">查看订单总详情</a></p>
            <hr class="dashed">
            <p class="fs14"><b>经销商已向华车平台提请争议处理的内容如下：</b></p>
            <div class="tbl">
                <ul class="bxlist">
                     @foreach($question as $k => $v)
                        <li style="width: 30%">
                            <p><input checked type="checkbox" class="radio" disabled></p>
                            <dl>
                                <dt>{{$v}}</dt>
                                <div class="clear"></div>
                            </dl>
                            <div class="clear"></div>
                        </li>
                      @endforeach
                    <div class="clear"></div>
                </ul>
            </div>
            <div class="clear"></div>
            <textarea class="txtarea" placeholder="" disabled>{{$dispute['content']}}</textarea>
            <p class="fs14 tdu  m-t-10"><b>3.相关证据</b></p>
                    <div class="m-t-10">
                        <span class="blue fl ">
                        @foreach($evidence as $k =>$v)
                        <span class="file-prev ml10"><a href="../../upload/evidence/{{$v['urls']}}" target="_blank">{{$v['urls']}}</a></span>
                        @endforeach
                        </span>
                        
                        <div class="clear"></div>
                    </div>
                    <br>
            <div class="clear"></div>
            <hr class="dashed">
            <div class="m-t-10"></div>
            
            @if(count($defend)==0)
            <form action="{{url('cart/defend')}}/{{$order_num}}" method="post" enctype="multipart/form-data">
            <p class="fs14"><b>按平台规则，您拥有进行申辩的权利，请向华车平台作出申辩：</b></p>
            <textarea class="txtarea" placeholder="请输入申辩内容" name='content'></textarea>

            <p class="fs14"><b>请上传证据材料：</b></p>
            <div>
                <span class="blue fl "></span>
                <span class="juhuang tdu cp fl ml10" ms-on-click="uploadForMuliteFileInput">上传</span>
                
                        <input type="hidden" name="" id="hfFile">
                        <input type="hidden" name="id"  value="{{$order['id']}}">
                        <input type="hidden" value="{{$order['order_num']}}" name="order_num" >
            			<input type="hidden" name="_token" value="{{ csrf_token() }}">
            			<input type="hidden" name="dispute_id"  value="{{$dispute['id']}}">
            			<input type="hidden" name="act"  value="defendfirst">
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="time tac m-t-10">
                <div class="jishi jishi2">
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
            <div class="m-t-10 tac">
               <input type="submit" class="btn btn-s-md btn-danger  " value="提交">
            </div>
            <p class="fs14 clear m-t-10 fn tac">
                <input type="checkbox"><span class="fn">本人愿对申辩材料的真实性承担一切责任！</span>
            </p>
			</form>
			@else
			
			<p class="fs14"><b>按平台规则，您已经进行申辩了。申辩内容如下：</b></p>
            <textarea class="txtarea" placeholder="请输入申辩内容" disabled>{{$defend['content']}}</textarea>

            <p class="fs14"><b>已经上传以下证据材料：</b></p>
            <div>
                <span class="blue fl " id="hfile_show">
                @foreach($defend_evidence as $k =>$v)
                        <span class="file-prev ml10"><a href="../../upload/evidence/{{$v['urls']}}" target="_blank">{{$v['urls']}}</a></span>
                @endforeach
                </span>
                
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="m-t-10"></div>
          
            <div class="clear"></div>
            <div class="m-t-10 tac">
               <a href="javascript:;" class="btn btn-s-md btn-danger    oksure ml20">已提交</a>
            </div>
          
			
			@endif
			
			
				
				
			
			@if(!empty($defend['resupply']))
					<h2 class="title">平台核实   </h2>
                	@if(empty($defend['resupply_evidence']))
		                
		                <form action="" method="post" enctype="multipart/form-data">
		                <p class="fs14 bl5"><b class="ml10">请补充材料清楚说明“<span class="juhuang">{{$defend['resupply']}}</span>”</b></p>
		                <p class="fs14 tdu  m-t-10"><b>证据材料：</b></p>
		                <div class="m-t-10">
		                        <span class="blue fl "></span>
		                        <span class="juhuang tdu cp fl ml10" ms-on-click="uploadForMuliteFileInput">上传</span>
		                        
		                        <input type="hidden" name="" id="hfFile">
		                        <div class="clear"></div>
		                </div>
		                <div class="clear"></div>              
		                <input type="submit" name="submit" value="提交" class="btn btn-s-md btn-danger">
		                  
		                <p class="fs14 clear m-t-10 fn">
		                    <input type="checkbox"><span class="fn">本人愿对补充材料的真实性承担一切责任！</span>
		                </p>
		                <input type="hidden" name="order_num" value="{{ $order_num}}">
		                <input type="hidden" name="defend_id" value="{{ $defend['id']}}">
		                <input type="hidden" name="_token" value="{{ csrf_token() }}">
		                <input type="hidden" name="id"  value="{{$order['id']}}">
		                <input type="hidden" name="act"  value="defendfirst_resupply">
						</form>
					@else
						<div class="fs14 bl5">
                			<b class="ml10">提交补充说明: {{$defend['resupply']}}</b>
            			</div>
						<p class="fs14 tdu  m-t-10"><b>证据材料：</b></p>
		                <div class="m-t-10">
		                        <span class="blue fl ">
		                        <?php $defend['resupply_evidence'] = unserialize($defend['resupply_evidence']);?>
		                        @foreach($defend['resupply_evidence'] as $k =>$v)
		                        	<span class="file-prev ml10"><a href="../../upload/evidence/{{$v}}" target="_blank">{{$v}}</a></span>
		                        @endforeach
		                        </span>
		                        
		                        <div class="clear"></div>
		                </div>
		                <div class="clear"></div>
		                <p class="m-t-10">              
		                <a href="javascript:;" class="btn btn-s-md btn-danger fl  bt oksure ml20">已提交</a>
						</p>
						<div class="clear"></div>
					@endif
				@endif
				
            <hr class="dashed">
            <div class="fs14 bl5">
                <b class="ml10">如与经销商达成和解，请告知华车平台：</b>
            </div>
            
            <div>
            @if(empty($defend['defend_hejie']))
	        <form action="" method="post">
            <div>
                <textarea class="form-control pdi-control m-t-10 w70" cols="66" rows="3" name="content2"></textarea>

                <p class="m-t-10">
                   <input type="submit" class="btn btn-s-md btn-danger fl bt" value="提交">
                </p>
                <div class="clear"></div>
            </div>
					<input type="hidden" value="{{$order['order_num']}}" name="order_num">
	            	<input type="hidden" value="{{$defend['id']}}" name="defend_id" >
	            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	            	<input type="hidden" name="act"  value="hejie">
	            	<input type="hidden" name="id"  value="{{$order['id']}}">
			</form>
			@else
					<textarea class="form-control pdi-control m-t-10 w70" cols="66" rows="3" readonly>{{$defend['defend_hejie']}}</textarea>
				
			@endif
                
                <div class="clear"></div>
            </div>
          
            <hr class="dashed">
            @if(!empty($mediate))
            <form action="" method="post" name="tiaojie_from" id="tiaojie_from">
            <div class="fs14 bl5" style="height: 19px;">
                <b class="ml10 fl">华车平台调解建议：</b>
                <input placeholder="" style="width: 600px;margin-top: -3px;" type="text" placeholder="" readonly="readonly" class="form-control pdi-control fl" value="{{$mediate['content']}}">
                <div class="clear"></div>
            </div>
           

            <div class="clear"></div>
            <p class="m-t-10">
            	@if($mediate['status']==0)
               <a ms-on-click="access" href="javascript:;" class="btn btn-s-md btn-danger fl sure">接受建议，继续订单</a>
               <a ms-on-click="noaccess" href="javascript:;" class="btn btn-s-md btn-danger fl sure ml20 wauto">不接受，按平台规则判定执行</a>
               <a href="javascript:;" class="btn btn-s-md btn-danger fl oksure ml20 wauto hide">不接受调解建议，等待平台按规则判定执行</a>
               @elseif($mediate['status']==1)
               <a  href="javascript:;" class="btn btn-s-md btn-danger fl sure ml20">不接受调解建议，等待平台按规则判定执行</a>
               @elseif($mediate['status']==2)
               <a  href="javascript:;" class="btn btn-s-md btn-danger fl sure">已完成</a>
               @endif
               <div class="clear"></div>
            </p>
			<input type="hidden" value="{{$order['order_num']}}" name="order_num" >
            <input type="hidden" value="{{$mediate['itemid']}}" name="itemid" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="result" value="0">
            <input type="hidden" name="act"  value="tiaojie">
            <input type="hidden" name="id"  value="{{$order['id']}}">
			</form>
			@endif
           

            <div class="clear"></div>
           
			
			
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>

        <div class="zm-all">
            <div class="dialog dialog-access">
                <div class="dialog-title"><span>提示</span><i ms-on-click="closeDialog" class="dialog-close"></i></div>
                <div class="dialog-c">
                    <p class="fs16">您接受上述调解建议，同意继续执行订单，确定吗？</p>
                    <a href="javascript:;" ms-on-click="accessandredirect" class="btn btn-s-md btn-danger bt sure">确定</a>
                </div>
            </div>

            <div class="dialog dialog-no-access">
                <div class="dialog-title"><span>提示</span><i ms-on-click="closeDialog" class="dialog-close"></i></div>
                <div class="dialog-c">
                    <p class="fs16">您不接受上述调解建议，同意按平台规则判定执行，确定吗？</p>
                    <a href="javascript:;" ms-on-click="noaccessanddosame" class="btn btn-s-md btn-danger bt sure">确定</a>
                </div>
            </div>
		
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-payment-while", "module/common/common", "bt"]);
    </script>
@endsection
