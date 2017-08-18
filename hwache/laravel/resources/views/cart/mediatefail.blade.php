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
                    <small class="juhuang">正在交车</small>
                    <i></i>
                    <small>核实信息</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi"  ms-controller="item">

        <div class="wapper has-min-step">
        @if($disputeType=="user")
            <div class="step-n step-n-4"></div>
        @else
            <div class="step-n step-n-4-c"></div>
        @endif
            <br>
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：</b></p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b></p></li>
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
                    <p class="fs14">路虎</p>
                </li>
                <li class="pdi-type"><p class="fs14">新一代揽胜</p></li>
                <li class="pdi-title"><p class="fs14">2015款 SDV6 Hybrid Vogue SE 混合动力创世加长版</p></li>
                <li class="pdi-color"><p class="fs14">月光石白色（个性金属漆）</p></li>
                <div class="clear"></div>
            </ul>     

            <p class="fs14 tac">
                <a href="#" class="juhuang tdu pl20">查看订单总详情</a>
            </p>


            <div class="box">
               
                <div class="box-inner  box-inner-def">
                    <h2 class="title">争议处理</h2>
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <td width="150"><p class="tal fs14"><b>争议内容</b></p></td>
                                <td><p class="tal fs14">{{$dispute['content']}}</p></td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>客户提交内容</b></p></td>
                                <td><p class="tal fs14">
                                @if(!empty($dispute['problem']))
                                {{implode(' , ',unserialize($dispute['problem']))}}
                                @endif
                                </p></td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>客户证据材料</b></p></td>
                                <td>
                                    <p class="tal fs14">
                                        <span class="file-prev ml10">
                                        @if(count($evidence)>0)
                                        	@foreach($evidence as $k=>$v)
                                        		<a href="/upload/evidence/{{$v['urls']}}" target="_bank">{{$v['urls']}}</a><br>
                                        	@endforeach
                                        @endif
                                        </span>
                                    </p>
                                </td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>售方提交内容</b></p></td>
                                <td><p class="tal fs14">{{$defend['content']}}</p></td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>售方证据材料</b></p></td>
                                <td>
                                    <p class="tal fs14">
                                        <span class="file-prev ml10">
                                        @if(count($evidence_defend)>0)
                                        	@foreach($evidence_defend as $k=>$v)
                                        		<a href="/upload/evidence/{{$v['urls']}}" target="_bank">{{$v['urls']}}</a><br>
                                        	@endforeach
                                        @endif
                                        </span>
                                    </p>
                                </td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>华车平台判定依据</b></p></td>
                                <td><p class="tal fs14">{{$mediate['breaker_content']}}</p></td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>华车平台判定结论</b></p></td>
                                <td><p class="tal fs14">
                                		<?php 
                                		if($mediate['breaker']==1){
                                			echo '售方违约';
                                		}elseif($mediate['breaker']==2){
                                			echo '客户违约';
                                		}elseif($mediate['breaker']==3){
                                			echo '不违约';
                                		}
                                			
                                		?>     <br>
                                		时间：{{$mediate['breaker_date']}}
                                		</p>
                                </td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>订单操作</b></p></td>
                                <td><p class="tal fs14">订单终止</p></td>
                            </tr> 
                        </tbody>
                    </table>
					<?php 
					$calc_money = 0;
					$calc_money+=$deposit;
					?>
                    <h2 class="title">结算信息</h2>
                    <table class="tbl w50 fl">
                        <tbody>
                            <tr>
                                <td width="150"><p class="tal fs14"><b>买车担保金</b></p></td>
                                <td><p class="tal fs14">人民币 {{$deposit}} 元</p></td>
                            </tr> 
                            <?php 
                            if(!empty($mediate['breaker_excute'])){
                            	$breaker_excute = unserialize($mediate['breaker_excute']);
                            	if($dispute['member_id'] == $order->buy_id){
                            		$excute = $breaker_excute['dispute'];
                            	}else{
                            		$excute = $breaker_excute['defend'];
                            	}
                            }else{
                            	$excute = array();
                            }
                            
                            if(count($excute)>0){
                            	foreach($excute as $k=>$v){	
                            		$calc_money+=$v['money'];
                            
                            ?>
                            
                            <tr>
                                <td width="150"><p class="tal fs14"><b>{{$v['title']}}</b></p></td>
                                <td><p class="tal fs14">人民币 {{$v['money']}} 元</p></td>
                            </tr> 
                            <?php 
                            	}
                            }
                            ?>
                            
                            <tr>
                                <td width="150"><p class="tal fs14"><b>应退还您的金额</b></p></td>
                                <td><p class="tal fs14">人民币{{$calc_money}} 元</p></td>
                            </tr> 
                        </tbody>
                    </table>
                    <a href="#" class="juhuang tdu fl" style="margin-top: 238px;margin-left: 10px;">报错</a>
                    <div class="clear"></div>
                    <p class="fs14">如客户买车担保金利息：2015 - 12 - 10 ~ 2015 - 12 - 20 , 10天，6000 X 10 X 0.02% = 12.00元</p>
                    <p class="fs14">很遗憾本次购车因售方原因未能顺利完成，华车平台已尽力为您提供了必要保障，并且挽回了您的一定损失。您可选择不退款，留在平台继续买车，也可选择退款，华车平台将尊重您的决定。</p>
                    <div class="clear"></div>
                    <p class="tac">
                        <a href="javascript:;" class="btn btn-s-md bt-small-2 btn-danger fs16">申请退款提现</a>
                        <a href="javascript:;" class="btn btn-s-md bt-small-2 btn-danger fs16 ml20">暂不退款，在华车平台继续买车</a>
                    </p>

                    <h2 class="title">结算信息</h2>
                    <table class="tbl w50 fl">
                        <tbody>
                            <tr>
                                <td width="150"><p class="tal fs14"><b>买车担保金</b></p></td>
                                <td><p class="tal fs14">人民币 10,000.00 元</p></td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>客户买车担保金赔偿</b></p></td>
                                <td><p class="tal fs14">— 人民币 8000.00 元</p></td>
                            </tr> 
                            <tr>
                                <td width="150"><p class="tal fs14"><b>应退还您的金额</b></p></td>
                                <td><p class="tal fs14">人民币 2000.00 元</p></td>
                            </tr> 
                        </tbody>
                    </table>
                    <a href="#" class="juhuang tdu fl" style="margin-top: 115px;margin-left: 10px;">报错</a>
                    <div class="clear"></div>
                    <p class="fs14">根据华车平台规则，很遗憾您将承担本次交车失利的责任，华车平台已从您的买车担保金中扣除相应的赔偿。您可继续留在平台买车，也可选择退还剩余金额。</p>
                    
                    <p class="tac">
                        <a href="javascript:;" class="btn btn-s-md bt-small-2 btn-danger fs16">申请退款提现</a>
                        <a href="javascript:;" class="btn btn-s-md bt-small-2 btn-danger fs16 ml20">暂不退款，在华车平台继续买车</a>
                    </p>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>
                    <div class="m-t-10"></div>

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
