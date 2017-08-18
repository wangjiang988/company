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
                <li>付款提车<i></i></li>
                <li class="step-cur">退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content back">
                    <small class="juhuang">核对金额</small>
                    <i></i>
                    <small>办理退款</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi"  ms-controller="item">

        <div class="wapper has-min-step">
                              
            <h1>尊敬的客户：</h1>
            <h1 class="ti">感谢您的耐心等待！您提交的车辆信息已通过审核，请核对本次服务结算明细。    </h1>
            <br>
            <p class="fs14"><b>订单： </b>{{$order_num}}</p>    
            
             <ul class="pdi-order-ul border">
                <li class="pdi-name">
                    <p class="fs14">{{$bj['brand'][0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$bj['brand'][1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$bj['brand'][2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$bj['body_color']}}</p></li>
                <div class="clear"></div>
            </ul>
                       
            <div class="box">
               
                <div class="box-inner  box-inner-def">
                   

                    <div style="width: 55%;" class="psr">

                        <table class="tbl">
                            <tbody>
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
                                    <td><p class="tal fs14 weight">注册登记名称</p></td>
                                    <td width="300">
                                        <span>{{$jiaoche['user_regname']}}</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">牌照号码</p></td>
                                    <td width="300">
                                        <span>
                                        @if(!empty($jiaoche['user_chepai']))
                                        {{implode("",unserialize($jiaoche['user_chepai']))}}
                                        @endif
                                        </span>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                        <form action="{{ url('cart/heshi') }}" method="post" name="item-form">
                        
                        <table class="tbl">
                            <tbody>
                                <tr>
                                    <th class="tal"><label class="fs14 tal">买车担保金</label><p class="fs14 tal">（冻结在华车网加信宝）</p></th>
                                    <th><p class="tal fs14 "><span class="nomarl fs14">人民币<input type="text" class="baoxianinput juhuang shot" name="money['买车担保金']" value="+{{$allPrice['bj_doposit_price']}}" readonly>元</span></p></th>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14 weight">华车网服务费约定</p></td>
                                    <td width="300">
                                        <span class="fs14">人民币<input type="text" class="baoxianinput juhuang shot" name="money['华车网服务费约定']" value="-{{$allPrice['bj_agent_service_price']}}" readonly>元</span>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14 weight">上牌违约赔偿金额约定</p></td>
                                    <td width="300">
                                        <span>人民币<input type="text" class="baoxianinput juhuang shot" name="money['上牌违约赔偿金额约定']" value="+{{$allPrice['bj_license_plate_break_contract']}}" readonly>元</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14 weight">应退还给您的金额</p></td>
                                    <td width="300">
                                    <?php 
                                    	$totalMoney = $allPrice['bj_doposit_price']-$allPrice['bj_agent_service_price']+$allPrice['bj_license_plate_break_contract'];
                                    ?>
                                        <span>人民币<input type="text" class="baoxianinput juhuang shot" name="totalmoney" value="{{$totalMoney}}" readonly>元</span>
                                    </td>
                                </tr>  
                                
                            </tbody>
                        </table>

                        <a href="#" class="juhuang tdu back-error">报错</a>

                    </div>
                    
                    
                    <div class="fs14">
                        <span class="fl"><b>温馨提示：</b>请在右侧时限内确认或报错，超时平台将自动提交确认。</span>
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
                    
                	<input type="hidden" value="{{$order_num}}" name="order_num" >
        			<input type="hidden" value="{{$order['cartBase']['id']}}" name="id" >
        			<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <p class="center">
                    	@if($order['cartBase']['calc_status']==0)
                        <input type="submit" value="确认" class="btn btn-s-md btn-danger fl">
                    	@else
                    	<input type="button" value="已申请结算" class="btn btn-s-md btn-danger fl">
                    	@endif
                    </p>
                    </form>
                </div>
               
            </div>
        
        </div>
    </div>
@endsection
@section('js')    
    <script type="text/javascript">
        seajs.use(["module/item/item-back-guarantees", "module/common/common", "bt"]);
    </script>
@endsection