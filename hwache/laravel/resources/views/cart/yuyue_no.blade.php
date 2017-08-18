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

        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti">我们怀着无比激动的心情第一时间告诉您，您的未来座驾已准备完毕，即将接受您的检阅！</h1>
            <table class="nobordertbl" width="100%">
                <tr>
                    <td width="50%" class=" fs14">订单号：{{ $order['cartBase']['order_num']}}</td>
                    <td width="50%">
                        <div class="psr fs14">
                          订单时间：{{ ddate($order['cartBase']['created_at'])}}
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
                    <td class="" width="50%">品牌：<span class="fn">{{$bj['brand'][0]}}</span></td>
                    <td width="50%">车系：<span class="fn">{{$bj['brand'][1]}}</span></td>
                </tr>
                <tr>
                    <td class="">车型规格:<span class="fn">{{$bj['brand'][2]}}</span></td>
                    <td>类别:<span class="fn">全新中规车整车
                    <?php if ($bj['bj_producetime'] && $bj['yc_xzj']): ?>
                    （已加装精品）
                    <?php endif ?>
                    </span></td>
                </tr>
                <tr>
                    <td class="">数量：<span class="fn">{{ $bj['bj_num']}}</span></td>
                    <td>生产国别：<span class="fn">{{ $bj['guobieTitle'] }}</span></td>
                </tr>
                <tr>
                    <td class="">座位数：<span class="fn">{{ $bj['seat_num'] }}</span></td>
                    <td class="">排放标准：<span class="fn">{{ $bj['paifangTitle'] }}</span></td>
                    
                </tr>
                <tr>
                    <td class="">车身颜色：<span class="fn">{{ $bj['body_color'] }}</span></td>
                    <td>
                        内饰颜色：<span class="fn">{{ $bj['interior_color'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td class="">行驶里程：<span class="fn">{{ $bj['bj_licheng'] }} 公里</span></td>
                    <td>出厂年月：<span class="fn">{{ $bj['bj_producetime']?$bj['bj_producetime']:''}}</span></td>
                </tr>
                <tr>
                    <td>车辆用途：<span class="fn">
                        
                        <?php if ($order['cartBase']['buytype']==1): ?>
						个人用车
						  <?php else: ?>
						公司用车
						<?php endif ?>
                    </span></td>
                    <td>上牌地区：<span class="fn">{{$shangpai_area}}</span></td>
                </tr>
                <tr>
                    <td class="" >基本配置：<span class="fn"><a href="{{ $bj['barnd_info']['official_url'] }}" target="_blank">官方参数</a></span></td>
                    <td>裸车开票价格：<span class="fn">{{ $bj['bj_lckp_price']}} 元</span></td>
                </tr>
               
            </table>
            
            <p class="tac"><a href="{{url('orderoverview')}}/{{$order_num}}" class="tdu juhuang" target="_blank">查看订单总详情</a></p>
            

            <!--交车-->
            <div class="box">
                <div class="title">
                    <label ms-click="toggleContent">一、交车</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def">
                    <table width="100%">
                        <tr>
                            <td valign="top"> 
                                <p><b>经销商名称： </b>{{$jxs['d_name']}}</p>
                                <p><b>交车地点：</b>{{$jxs['d_jc_place']}}</p>
                                <p><b>您前往提车的方式：</b></p>
                                <input type="hidden" name="tiCheMethod" id="tiCheMethod">
                                <table>
                                    <tr>
                                        <td align="left">
                                            <ol>
                                                <li class="cur-select"><span>自己安排直接到店</span><i ></i></li>
                                            </ol>
                                        </td>
                                    </tr>
                                </table>
                                <p><b>您座驾的回程方式：</b></p>
                                <table>
                                    <tr>
                                        <td align="left">
                                        	<?php 
                                        	if(!empty($order['cartAttr']['trip_way'])){
                                        		$trip_way = unserialize($order['cartAttr']['trip_way']);
                                        		$fangshi=$trip_way['fangshi'];
                                        	}else{
                                        		$fangshi = "自己开回";
                                        	}
                                        	$fangshi = "了解送车服务报价";
                                        		?>
                                            <ol>
                                                <li class="<?php if($fangshi == "自己开回"){echo 'cur-select';}?>"><span>自己开回</span><i></i></li>
                                                <li class="<?php if($fangshi == "了解送车服务报价"){echo 'cur-select';}?>"><span>了解送车服务报价<i></i></span></li>
                                            </ol>
                                            <input type="text" class="form-control address ml200 <?php if($fangshi != "了解送车服务报价"){echo 'hide';}?>" placeholder="<?php echo $order['cartAttr']['deliver_addr'];?>" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td align="center" width="270">
                                <p class="tal"><b class="tal">交车地点图示</b></p>
                                <img  width="270" src="themes/images/item/map.gif" />
                            </td>
                        </tr>
                    </table>
                      
                    <div class="split"></div>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14 "><b class="fl">交车时限：  </b>  
                        2016年4月15日24：00：00</p>
                    </div>
                    <div class="clear"></div>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    
                    <p><b>经销商邀请您在下列可提车日期中挑选尊驾的提车吉日：</b></p>
                    <div class="clear"></div>
                    <table>
                        <tr>
                            <td align="left">
                                <ol class="time">
                                    <input type="hidden" value="" name="SolicitationTime" id="SolicitationTime">       
                                    <?php foreach ($dates as $key => $value): ?>

                                        <?php 
                                                $class='disable';
                                                $timestr='';
                                                $thistime=strtotime(date('Y').$value);

                                        foreach ($jiaoche_date as $k){
                                             if (strpos($k, "$value")!==false){
                                             		if($thistime<time()){
                                             			$class='disable2';
                                             		}else{
                                             			$class='sure';
                                             		}
                                                    
                                                    $timestr=substr($k, 10);
                                                    continue;
                                                }
                                            }
                                        ?>

									<li  class="{{$class}}"><i></i><span>{{$value}}</span><span>{{$timestr}}</span></li>
                                    <?php endforeach ?>
                                </ol>
                            </td>
                        </tr>
                    </table>
                    <p>温馨提示：交车和上牌过程中，可能遇到特殊情况当日无法完成所有手续，请您对其后的日程一并考虑。</p>
					<dl class="hasselect">
                              <dt><span class="p5">首次推荐日期：</span></dt>
                              <?php foreach ($pdi_date_dealer as $key => $value): ?>
                                <?php if(empty($value)) continue;?>
                                <dd><span class="p5">{{$value}}</span></dd>
                              <?php endforeach ?>
                              
                            </dl>
                            <div class="clear"></div>
                            <dl class="hasselect">
                              <dt><span class="p5">客户希望日期：</span></dt>
                              <?php foreach ($pdi_date_client as $key => $value): ?>
                                <?php if(empty($value)) continue;?>
                                <dd><span class="p5">{{$value}}</span></dd>
                              <?php endforeach ?>
                              
                              <dd style="border:0;cursor: default;"><span></span></dd>
                            </dl>
                            
                    <div class="clear"></div>

                    <p><span class="xing">*</span>温馨提示：因超期提车将增加经销商额外成本，须由您作相应补偿，补偿金额由经销商和您在下一步反馈中协商确定。</p>
                    <p><b>在尊重规则基础上，我们也倡导互谅互让，建议您向经销商说明提车延后的原因：</b></p>
                    <p><input type="text" class="form-control w100p" placeholder="<?php echo $order['cartAttr']['take_cause']?>" disabled="" ></p>
                    <p>温馨提示：交车和上牌过程中，可能遇到特殊情况当日无法完成所有手续，请您对其后的日程一并考虑。</p>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14 "><b class="fl">您座驾计划上牌（注册登记）的车主名称：    </b><div class="clear"></div></p>
                        <p class="fs14"><input disabled="" type="text" class="form-control address " placeholder="{{$buyer['member_truename']}}"></p>
                        <p class="fs14">温馨提示：该名称将用于进一步核对订单信息，填写以后请不要再更改以免造成误会。</p>
                    </div>
                    <div class="clear"></div>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl" >
                        <p class="fs14 tbl fl" style="margin-top:0;+text-align: left;width: 100%">
                            <b class="fl">您计划委托的提款人是否与上牌名称一致：</b>
                            <span class="cell fl">
                                <input disabled="" type="checkbox" disabled="" class="radio" <?php if($order['cartAttr']['agreement']==1){echo "checked";}?>>
                                <label class="fn">是</label>
                            </span>
                            <span class="cell fl ml20">
                                <input disabled=""  type="checkbox" disabled="" class="radio" <?php if($order['cartAttr']['agreement']!=1){echo "checked";}?>>
                                <label class="fn">否</label>
                            </span>
                            <div class="clear"></div>
                        </p>
                        <p class="fs14">
                            <b class="fl">姓名：  </b><input disabled=""  style="margin-top:-5px" type="text" class="form-control address fl" placeholder="{{$ticheren['username']}}">
                            <b class="fl" style="margin-left: 35px">电话：  </b><input disabled=""  style="margin-top:-5px" type="text" class="form-control address fl" placeholder="{{$ticheren['mobile']}}">
                            <div class="clear"></div>

                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

          
            <!--车辆首年商业保险-->
            <div class="box">
                <div class="title">
                    <label ms-on-click="toggleContent">二、车辆首年商业保险</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                    <?php if ($order['cartBase']['final_baoxian']): ?>
                        <p><b>投保约定：</b>指定投保（您已同意车辆首年商业保险在经销商处办理）</p>
                    <p><b>为您提供保险服务的保险公司：</b>平安财产保险股份有限公司</p>
                <?php else: ?>
                    <p><b>投保约定：</b>自由投保</p>
                    <?php endif ?>

                    <table class="tbl baoxian">
                        <tr>
                            <td class="cell" colspan="2">
                                <b>报价方式：</b>
                                <input name="baoxian3" disabled="" class="radio" type="radio" /><span>自选保险</span>
                                <input name="baoxian4" checked="" disabled="" class="radio" type="radio" /><span>推荐组合</span>
                            </td>
                        </tr> 
                        <tr>
                            <td width="50" valign="top" style="padding:0" >
                            <?php $baoxian_count=0.00; ?>
                                <table width="100%" height="100%" >
                                    <tr>
                                        <td style="border:0;height: 40px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;"><b>类别</b></td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;">
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14 tac"><b>主</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14"><b>&nbsp;</b></p>
                                            <p class="fs14 tac"><b>险</b></p>
                                        </td>
                                    </tr>
                                </table>
                                
                            </td>
                            <td class="nopadding">
                                <table width="100%">
                                    <tr>
                                        <th width="160" class="">险种</th>
                                        <th class="" width="400">赔付选项</th>
                                        <th class="norightborder">我的投保</th>
                                    </tr>
                                    <?php if (!empty($baoxian['chesun'])): ?>
                                           <tr>
                                                <td class="cell"><input type="checkbox" 
                                                checked="" disabled="" class="radio" /> <label class="fn">机动车损失险</label></td>
                                                <td class="">按保险公司规定执行</td>
                                                <td class="norightborder">{{$baoxian['chesun']}} 元</td>
                                            </tr> 
                                            <?php $baoxian_count+=$baoxian['chesun']; ?>
                                    <?php endif ?>
                                    

                                    <?php if (!empty($baoxian['daoqiang'])): ?>
                                           <tr>
                                                <td class="cell"><input type="checkbox"checked="" disabled="" class="radio" /> <label class="fn">机动车盗抢险</label></td>
                                                <td class="">按保险公司规定执行</td>
                                                <td class="norightborder">{{$baoxian['daoqiang']}} 元</td>
                                            </tr>
                                            <?php $baoxian_count+=$baoxian['daoqiang']; ?> 
                                        <?php endif ?>
                                    

                                    <?php if (!empty($baoxian['sanzhe'])): ?>
                                          <tr>
                                            <td class="cell"><input type="checkbox"checked="" disabled="" class="radio" /> <label class="fn">第三者责任保险</label></td>
                                            <td class="cell" width="320">
                                                <p>赔付额度：</p>
                                                {{$baoxian['sanzhe']['compensate']}}                                        </td>
                                            <td class="norightborder">{{$baoxian['sanzhe']['price']}} 元</td>
                                        </tr>
                                        <?php $baoxian_count+=$baoxian['sanzhe']['price']; ?>   
                                    <?php endif ?>
                                    

                                    <?php if (!empty($baoxian['renyuan'])): ?>
                                         <tr>
                                        <td class="cell nobottomborder"><input checked="" type="checkbox" disabled="" class="radio" /> <label class="fn">车上人员责任险</label></td>
                                        <td class="cell nopadding nobottomborder" width="320">
                                            <div>
                                                <p class="first-p">驾驶人每次事故责任限额：</p>
                                                <div class="r-box">{{$baoxian['renyuan']['sj']['compensate']}}
                                                     </div>
                                                <h5 class="fenge">乘客每次事故每人责任限额：</h5>
                                                <div class="r-box">{{$baoxian['renyuan']['ck']['compensate']}}
                                                   </div>
                                                <h5 class="fenge" style="border-top:0">乘客座位数：</h5>
                                                <div class="r-box">
                                                    {{$baoxian['renyuan']['seat']}}</div>
                                            </div>                                        </td>
                                        <td class="norightborder nopadding nobottomborder">
                                            <table class="tbl2 " width="100%" style="height: 181px;">
                                                <tr style="height: 62px;">
                                                    <td class="norightborder">{{$baoxian['renyuan']['sj']['price']}} 元</td>
                                                </tr>
                                                <tr>
                                                    <td class="norightborder nobottomborder">{{$baoxian['renyuan']['ck']['price']}} 元</td>
                                                </tr>
                                            </table>                                        </td>
                                    </tr>   
                                    <?php $baoxian_count+=$baoxian['renyuan']['ck']['price'];
                                        $baoxian_count+=$baoxian['renyuan']['sj']['price'];

                                     ?>
                                    <?php endif ?>
                                    

                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="30" class="" align="center">
                                <p class="fs14"><b>附</b></p>
                                <p class="fs14"><b>加</b></p>
                                <p class="fs14"><b>险</b></p>
                            </td>
                            <td class="nopadding " >
                                <table width="100%">
                                <?php if (!empty($baoxian['boli'])): ?>
                                         <tr>
                                            <td class="cell "  width="160"><input checked="" type="checkbox" disabled="" class="radio" /> <label  class="fn">玻璃单独破碎险</label></td>
                                            <td class="cell" width="400">
                                                <span>{{$baoxian['boli']['state']}}</span>
                                                       </td>
                                            <td class="norightborder">{{$baoxian['boli']['price']}} 元</td>
                                        </tr> 
                                        <?php 
                                        $baoxian_count+=$baoxian['boli']['price'];

                                     ?>  
                                <?php endif ?>
                                    

                                    <?php if (!empty($baoxian['huahen'])): ?>
                                            <tr>
                                                <td class="cell"><input checked="" type="checkbox" disabled="" class="radio " /> <label class="fn">车身划痕损失险</label></td>
                                                <td class="cell" width="320">
                                                    <p>赔付额度：</p>{{$baoxian['huahen']['compensate']}}
                                                    </td>
                                                <td class="norightborder">{{$baoxian['huahen']['price']}} 元</td>
                                            </tr>
                                            <?php 
                                        $baoxian_count+=$baoxian['huahen']['price'];

                                     ?>  
                                        <?php endif ?>
                                    

                                    <?php if (!empty($baoxian['bjm_chesun'])): ?>
                                          <tr>
                                            <td class="cell"><input checked="" type="checkbox" disabled="" class="radio" /> <label class="fn psr">不计免赔特约险</label></td>
                                            <td class="cell" width="320">
                                                <span>机动车损失险，按保险公司规定执行。</span>                                        </td>
                                            <td class="norightborder">{{$baoxian['bjm_chesun']}} 元</td>
                                        </tr>
                                        <?php 
                                        $baoxian_count+=$baoxian['bjm_chesun'];
                                     ?>  
                                        <?php endif ?>
                                    

                                    <?php if (!empty($baoxian['bjm_daoqiang'])): ?>
                                            <tr>
                                                <td class="cell"><input checked="" type="checkbox" disabled="" class="radio" /> <label class="fn">不计免赔特约险</label></td>
                                                <td class="cell" width="320">
                                                    <span>机动车盗抢险，按保险公司规定执行。</span>                                        </td>
                                                <td class="norightborder">{{$baoxian['bjm_daoqiang']}} 元</td>
                                            </tr>
                                            <?php 
                                                $baoxian_count+=$baoxian['bjm_daoqiang'];
                                             ?> 
                                        <?php endif ?>
                                     

                                    <?php if (!empty($baoxian['bjm_sanzhe'])): ?>
                                          <tr>
                                            <td class="cell"><input checked="" type="checkbox" disabled="" class="radio" /> <label class="fn">不计免赔特约险</label></td>
                                            <td class="cell" width="320">
                                                <span>第三者责任险，按保险公司规定执行。</span>                                        </td>
                                            <td class="norightborder">{{$baoxian['bjm_sanzhe']}} 元</td>
                                        </tr> 
                                        <?php 
                                                $baoxian_count+=$baoxian['bjm_sanzhe'];
                                             ?> 
                                        <?php endif ?>
                                     

                                    <?php if (!empty($baoxian['bjm_renyuan'])): ?>
                                            <tr>
                                                <td class="cell"><input checked="" type="checkbox" disabled="" class="radio" /> <label class="fn">不计免赔特约险</label></td>
                                                <td class="cell" width="320">
                                                    <span>车上人员责任险，按保险公司规定执行。</span>                                        </td>
                                                <td class="norightborder">{{$baoxian['bjm_renyuan']}} 元</td>
                                            </tr>
                                            <?php 
                                                $baoxian_count+=$baoxian['bjm_renyuan'];
                                             ?>
                                        <?php endif ?>
                                     

                                    <?php if (!empty($baoxian['bjm_huahen'])): ?>
                                        <tr>
                                        <td class="cell nobottomborder"><input checked="" type="checkbox" disabled="" class="radio" /> <label class="fn">不计免赔特约险</label></td>
                                        <td class="cell nobottomborder" width="320">
                                            <span>车身划痕损失险，按保险公司规定执行。</span>                                        </td>
                                        <td class="norightborder nobottomborder">{{$baoxian['bjm_huahen']}} 元</td>
                                    </tr>
                                    <?php 
                                                $baoxian_count+=$baoxian['bjm_huahen'];
                                             ?>
                                    <?php endif ?>
                                    
                                </table>
                            </td>
                        </tr>
                        <tfoot>
                            <tr>
                                <td class="" colspan="2" align="center" valign="middle">
                                    <h4>
                                        已选投保组合总价：人民币<span class="juhuang">{{$baoxian_count}}</span>元 
                                    </h4>
                                    <p>
                                        <a href="javascript:;"  disabled="" class="btn btn-s-md btn-danger btn-danger-hover">已确定</a>
                                    </p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>


                   
                    <p><b>支付方式:</b>在经销商处支付</p>
                    <p><b>您需要配合提供的文件资料:</b>{{$toubao_file}}</p>
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
                    <p>
                        说明：指经销商为购车方代办机动车注册登记手续的服务，仅按车管所相关规则办理，不对牌号结果负责，也不含在某些限牌地区通过摇号、拍卖、转让等方式取得牌照资源指标的服务和费用。
                    </p>
                    <p><b>上牌人身份类别：</b>{{$order['cartBase']['shenfen']}}</p>
                    <?php if ($bj['area_xianpai']): ?>
                        <p><b>限牌城市（{{$bj['area_xianpai']}}）客户取得牌照指标的安排：{{$order['cartBase']['zhibiao']}} </b></p>
                    
                    <?php endif ?>
                    <?php if ($order['cartBase']['shangpai']==1): ?>
                            <table class="tbl">
                                    <tr>
                                        <td width="50%"><p class="fs14"><b>上牌服务约定：</b>指定上牌</p></td>
                                        <td width="50%"><p class="fs14"><b>是否由经销商代办上牌：</b>是</p></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><p class="fs14"><b>上牌服务费约定：</b>（不高于）人民币{{$bj['bj_shangpai_price']}}元</p></td>
                                        <td width="50%"><p class="fs14"><b>上牌费金额：</b>人民币{{$bj['bj_paizhao_price']}}元</p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$shangpai_file}}</p></td>
                                    </tr>
                                </table>
                        <?php else: ?>
                            <table class="tbl">
                               <tr>
                                   <td width="50%"><p class="fs14"><b>上牌服务约定：</b>本人上牌</p></td>
                                   <td width="50%"><p class="fs14"><b>是否由经销商代办上牌：</b>否</p></td>
                               </tr>
                               <tr>
                                   <td colspan="2"><p class="fs14"><b>客户本人上牌违约金约定：</b>{{$bj['bj_license_plate_break_contract']}} 元</p></td>
                               </tr>
                                <tr>
                                   <td colspan="2"><p class="fs14"><b>支付方式：</b>根据本车注册登记信息核实，如您违约将从买车担保金中扣除此金额。</p></td>
                                </tr>     
                             </table>
                    <?php endif ?>
               
                    
                </div>
            </div>

            <!--上临时牌照-->
            <div class="box">
                <div class="title">
                    <label ms-on-click="toggleContent">四、上临时牌照</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                    <p class="fs14">说明：仅指办理车辆临时移动牌照，而非机动车注册登记。根据《中华人民共和国交通安全法》，在没有取得正式牌照之前，必须按规定申领车辆临时移动牌照方能上路行驶。为避免上路风险，建议依法办理。</p>
                    @if($order['cartBase']['linpai']==1)
                    	@if($bj['bj_shangpai']==0)
                    <table class="tbl sp" >
                        <tr>
                            <td colspan="2"><p class="fs14"><b>上临时牌照约定：</b>指定服务（由您本人亲自上牌的，则须先委托经销商代办临时移动牌照）</p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费约定：</b>（不高于）人民币{{$bj['bj_linpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$linpai_file}}</p></td>
                        </tr>
                    </table>
                    	@else
				    <p><b>上临时牌照约定：</b>指定服务（上牌服务由经销商代办，您可选择再办理一张临时移动牌照以避免上路风险）</p> 
                    <p><b>请选择是否由经销商代办车辆临时牌照：</b></p>
                    <p>
                        <label class="fn"><input disabled=""  type="radio" name="shanglspai" /><span class="fn">否，本人亲自办理临时牌照，上路风险本人承担</span></label>
                        <label class="fn"><input disabled=""  checked=""  type="radio" name="shanglspai" class="ml20" /><span class="fn">是，委托经销商代办</span></label>
                    </p>
                    <table class="tbl sp" >
                        <tr>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费约定：</b>（不高于）人民币{{$bj['bj_linpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$linpai_file}}</p></td>
                        </tr>
                    </table>
						@endif
					@else
						<p><b>上临时牌照约定：</b>自选服务</p>
                    <p><b>请选择是否由经销商代办车辆临时牌照：</b></p>
                    <p>
                        <label class="fn"><input  disabled=""  checked=""  type="radio" name="shanglspai2" /><span class="fn">否，本人亲自办理临时牌照，上路风险本人承担</span></label>
                        <label class="fn"><input  disabled=""  type="radio" name="shanglspai2" class="ml20" /><span class="fn">是，委托经销商代办</span></label>
                    </p>
                    <table class="tbl sp" >
                        <tr>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费约定：</b>（不高于）人民币{{$bj['bj_linpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$linpai_file}}</p></td>
                        </tr>
                    </table>
                    
					@endif
                    
                </div>
            </div>

           
            <!--补贴-->
            <div class="box">
                <div class="title">
                    <label ms-on-click="toggleContent">五、补贴</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                    @if($bj['bj_butie'])
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <td class="prev-title " style="width: 190px;" ><p class="fs14">补贴名称</p></td>
                                <td><p class="fs14"><b>国家节能补贴</b></p></td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">补贴说明</p></td>
                                <td>
                                    <p class="fs14">在政策有效期内，国家对符合“节能产品惠民工程”节能汽车标准条件车型给予的一次性定额补贴。</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">金额</p> </td>
                                <td>
                                    <p class="fs14">{{$bj['bj_butie']}} 元</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">您需要配合提供的文件资料</p> </td>
                                <td>
                                    <p class="fs14">{{$butiefile}}  </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">该经销商办理国家</p><p class="fs14">节能补贴流程与时限</p></td>
                                <td>
                                    <?php 
                                        if (isset($bj['more']['butie'])) {
                                            if (is_array($bj['more']['butie'])) {
                                                echo implode($bj['more']['butie']);
                                            }else{
                                                echo $bj['more']['butie'];
                                            }
                                        }

                                     ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="tar">
                        <p>
                            <input disabled="" <?php if ($guojiabutie==1): ?>
                                checked='checked'
                            <?php endif ?> type="checkbox" name="" id=""><label class="fn" for="">我同意按此流程办理</label>
                            <input disabled="" type="checkbox" name="" id="" class="ml20" <?php if ($guojiabutie==0): ?>
                                checked='checked'
                            <?php endif ?> style="margin-left:20px!important"><label class="fn" for="">不办了</label>
                        </p>
                    </div>
                    @endif
                    @if($bj['bj_zf_butie'])
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <td class="prev-title"><p class="fs14">补贴名称</p></td>
                                <td><p class="fs14"><b>地方政府置换补贴  </b></p></td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">补贴说明</p></td>
                                <td>
                                    <p class="fs14">该上牌地区的政府对旧车置换新车提供的补贴。</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">经销商是否提供协助</p></td>
                                <td>
                                    <p class="fs14">是</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>温馨提示：经销商并不负责办理该补贴，仅对您向有关部门申请该补贴提供力所能及的协助。如经销商属异地，所提供的协助可能存在不被上牌</p>
                    <p>地区当地政府所认可的风险，具体申请政策和手续请向当地政府相关部门咨询。</p>
                    <div class="tar">
                        <p>
                            <input disabled="" type="checkbox" name="" id=""><label class="fn" for="">我需要协助</label>
                            <input disabled="" type="checkbox" name="" id="" class="ml20" style="margin-left:20px!important"><label class="fn" for="">不办了</label>
                        </p>
                    </div>
                    @endif
                    @if($bj['bj_cj_butie'])
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <td class="prev-title"><p class="fs14">补贴名称</p></td>
                                <td><p class="fs14"><b>厂家或经销商置换补贴    </b></p></td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p class="fs14">补贴说明</p></td>
                                <td>
                                    <p class="fs14">该品牌的厂家或经销商为消费者旧车置换新车提供的补贴。。</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>温馨提示：由于可能涉及旧车估价等不确定因素，您需要在提车之前与经销商另行商定补贴金额。此处仅提供有无补贴的信息，并不具有合同强</p>
                    <p>制效力。</p>
                    <div class="tar">
                        <p>
                            <input disabled="" type="checkbox" name="" id=""><label class="fn" for="">我知道了</label>
                        </p>
                    </div>
					@endif
                </div>
            </div>

      

            <!--您的特别要求-->
            <div class="box">
                <div class="title">
                    <label >六、您的特别要求</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def tbl" style="line-height: 30px;+line-height: normal;">

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl wp90" >
                        <p class="fs14 tbl fl ">
                            <b>给您和尊驾更全面的保护，以下车辆商业保险附加项目可向经销商询价（可多选）</b>
                        </p>
                        <div class="clear"></div>

                        <ul class="bxlist">
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][1]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>1.自燃损失险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input disabled="" type="radio" name="isziran" <?php if($other_baoxian['chesun'][1]['bjm']=="是"){echo "checked";} ?>><span>是</span></label>
                                        <label><input disabled="" type="radio" name="isziran" <?php if($other_baoxian['chesun'][1]['bjm']=="否"){echo "checked";} ?>><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][2]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>2.新增加设备损失险</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][3]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>3.发动机涉水损失险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input type="radio" disabled="" name="sheshui" <?php if($other_baoxian['chesun'][3]['bjm']=="是"){echo "checked";} ?>><span>是</span></label>
                                        <label><input type="radio" disabled="" name="sheshui" <?php if($other_baoxian['chesun'][3]['bjm']=="否"){echo "checked";} ?>><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][4]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>4.修理期间费用补偿险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input disabled="" type="radio" name="buchang" <?php if($other_baoxian['chesun'][4]['bjm']=="是"){echo "checked";} ?>><span>是</span></label>
                                        <label><input disabled="" type="radio" name="buchang" <?php if($other_baoxian['chesun'][5]['bjm']=="否"){echo "checked";} ?>><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][5]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>5.车上货物责任险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input disabled="" type="radio" name="cheshangxian" <?php if($other_baoxian['chesun'][5]['bjm']=="是"){echo "checked";} ?>><span>是</span></label>
                                        <label><input disabled="" type="radio" name="cheshangxian" <?php if($other_baoxian['chesun'][5]['bjm']=="否"){echo "checked";} ?><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>

                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][6]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>6.机动车损失险无法找到第三方特约险</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>

                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['chesun'][7]['chesun'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>7.指定修理厂险</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['renyuan'][8]['renyuan'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>8.精神损害抚慰金责任险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input disabled="" type="radio" name="jingsheng" <?php if($other_baoxian['renyuan'][8]['bjm']=="是"){echo "checked";} ?>><span>是</span></label>
                                        <label><input disabled="" type="radio" name="jingsheng" <?php if($other_baoxian['renyuan'][8]['bjm']=="否"){echo "checked";} ?>><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['sanzhe'][9]['sanzhe'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>9.增加第三者责任险保额</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" disabled="" class="radio" <?php if(isset($other_baoxian['sanzhe'][10]['sanzhe'])){echo "checked";} ?>></p>
                                <dl>
                                    <dt>10.增加车上人员责任险保额</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>


                            <div class="clear"></div>
                        </ul> 
                        
                    </div>
                    <div class="clear"></div>
                    <p class="ml20">说明：附加险1、2、3、4、5、6、7的投保前提为已投机动车损失险；8、9、10的投保前提是已投第三者责任险或车上人员责任险。</p>
                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl wp90" >
                        <p><b>您的其他要求</b>（可输入不超过100个字）</p>
                    </div>
                    <div class="clear"></div>
                    <input type="text" disabled="" class="form-control w100p yaoqiu ml20" placeholder="<?php if(!empty($order['cartAttr']['other'])){echo ($order['cartAttr']['other']);}?>" />
                    <p class="ml20">温馨提示：经销商将在预约交车反馈中详细回答，尽量满足您的要求。您须同意 : <b>经销商的反馈结果不对本订单其他约定内容的执行产生影响</b>。</p>
                    <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger btn-danger-hover">已提交反馈</a>
                    </p>
                 
                </div>
            </div>

        
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-pdi-confirm", "module/common/common", "bt"]);
    </script>
@endsection