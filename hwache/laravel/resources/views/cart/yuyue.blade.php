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
                    <small class="juhuang">等待预约</small>
                    <i></i>
                    <small>反馈确认</small>
                    <i></i>
                    <small class="">预约完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi" ms-controller="item">
        <form action="{{url('cart/yuyue')}}" method="post" name="item-form">
        <input type="hidden" name="order_num" value="{{ $order['cartBase']['order_num']}}">
        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti">我们怀着无比激动的心情第一时间告诉您，您的未来座驾已准备完毕，即将接受您的检阅！<a href="{{url('cart/xzjusergetlist')}}/{{$order_num}}" class="juhuang tdu">选装精品</a>最后优惠，错过可能很遗憾！</h1>

            <h1 class="ti">售方已向您发出交车邀请多日，请您立即回复。如超过交车时限未回复，根据平台规则您违约将<span class="juhuang">赔偿所有买车担保金</span>。</h1>

            <div class="fs14 tac ml260"  >
                  <span class="fs14 fl mt10">剩余时间：</span>
                  <div class="time fl mt15">
                      <div class="jishi jishi2 wp100" id="countdown">
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


            <!--//3.倒计时为0后，如平台客服进行过程保留订单操作，则显示如下-->
            <div class=""  >
            
                <span class="fl mt10 ti">您未答复交车邀请，已超过交车时限，请立即答复！已超时：</span>
                <div class="time fl mt15">
                      <div class="jishi jishi2 jishi-error wp100" id="timeout">
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
                                <p><b>经销商名称： {{$jxs['d_name']}}</b></p>
                                <p><b>交车地点：{{$jxs['d_jc_place']}}</b></p>
                                <p><b>您前往提车的方式：</b></p>
                                <table>
                                    <tr>
                                        <td align="left">
                                            <input type="hidden" name="tiCheMethod" id="tiCheMethod" value="自己安排直接到店">
                                            <ol>
                                                <li ms-click="tiCheMethod" class="select-prev cur-select"><span>自己安排直接到店</span><i ></i></li>
                                            </ol>
                                            
                                        </td>
                                    </tr>
                                </table>
                                <p><b>您座驾的回程方式：</b></p>
                                <table>
                                    <tr>
                                        <td align="left">
                                            <input type="hidden" name="huichengCheMethod" id="huichengCheMethod" value="自己开回">
                                            <ol>
                                                <li ms-click="tiCheMethod" class="cur-select"><span>自己开回</span><i></i></li>
                                                <li ms-click="tiCheMethod" class="select-next last-child"><span>了解送车服务报价<i></i></span></li>
                                            </ol>
                                            <input type="text" class="form-control address ml200 hide" name="deliver_addr" placeholder="填写送车大致地址" >
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
                        {{$jiaoche_time}}</p>
                    </div>
                    <div class="clear"></div>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    
                    <p><b>经销商邀请您在下列可提车日期中挑选尊驾的提车吉日：</b></p>
                    <div class="clear"></div>
                    <table>
                        <tr>
                            <td align="left">
                                <ol class="time">
                                    <input type="hidden" value="" name="jiaoche_date" id="SolicitationTime">

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

<li ms-click="SolicitationTime" class="{{$class}}"><i></i><span>{{$value}}</span><span>{{$timestr}}</span></li>
                                    <?php endforeach ?>
                                    
                                </ol>
                            </td>
                        </tr>
                    </table>
                    <p>温馨提示：交车和上牌过程中，可能遇到特殊情况当日无法完成所有手续，请您对其后的日程一并考虑。</p>

                    <p class="ifl fs14"><i class="yuan"></i></p>    
                    <div class="ifl">
                        <p class="fs14 "><b class="fl">如以上时间都不便安排，请告知您可安排的最接近时间：  </b>
                        <table>
                            <tr>
                                <td align="left" style="width:500px;padding-top: 10px;">
                                    <div class="btn-group m-r fl"  style="width:160px;">
                                        <div class="form-group psr">
                                          <input style="" type="text" placeholder="" class="form-control"  onfocus ="WdatePicker()" name="mydate">
                                          <i class="rili" ms-click=rili></i>
                                        </div>
                                    </div>
                                    <div class="btn-group m-r fl bts">
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span>上午/下午</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-select">
                                            <input type="hidden" name="d-s-r" />
                                            <li ms-on-click="selectTime" class="active"><a><span>上午/下午</span></a></li>
                                            <li ms-on-click="selectTime"><a><span>上午</span></a></li>
                                            <li ms-on-click="selectTime"><a><span>下午</span></a></li>
                                        </ul>
                                    </div>
                                    <div class="clear"></div>

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="clear"></div>

                    <p><span class="xing">*</span>温馨提示：因超期提车将增加经销商额外成本，须由您作相应补偿，补偿金额由经销商和您在下一步反馈中协商确定。</p>
                    <p><b>在尊重规则基础上，我们也倡导互谅互让，建议您向经销商说明提车延后的原因：</b></p>
                    <p><input type="text"  class="form-control w100p" name="yuanyin" placeholder="选填"></p>
                    <p>温馨提示：交车和上牌过程中，可能遇到特殊情况当日无法完成所有手续，请您对其后的日程一并考虑。</p>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl">
                        <p class="fs14 "><b class="fl">您座驾计划上牌（注册登记）的车主名称：    </b><div class="clear"></div></p>
                        <p class="fs14"><input type="text" class="form-control address " placeholder="" name="chezhu" value="{{$buyer['member_truename']}}"></p>
                        <p class="fs14">温馨提示：该名称将用于进一步核对订单信息，填写以后请不要再更改以免造成误会。</p>
                    </div>
                    <div class="clear"></div>

                    <p class="ifl fs14"><i class="yuan"></i></p>
                    <div class="ifl" >
                        <p class="fs14 tbl fl" style="margin-top:0;+text-align: left;width: 100%">
                            <b class="fl">您计划委托的提车人是否与上牌名称一致：</b>
                            <span class="cell fl">
                                <input type="radio" class="radio" name="agreement" checked="" value="1">
                                <label class="fn">是</label>
                            </span>
                            <span class="cell fl ml20">
                                <input type="radio" class="radio" name="agreement" value="0">
                                <label class="fn">否</label>
                            </span>
                            <div class="clear"></div>
                        </p>
                        <p class="fs14">
                            <b class="fl">姓名：  </b><input style="margin-top:-5px" type="text" class="form-control address fl" placeholder="" name="tiche[username]" value="{{$buyer['member_truename']}}">
                            <b class="fl" style="margin-left: 35px">电话：  </b><input style="margin-top:-5px" type="text" class="form-control address fl" placeholder="" name="tiche[mobile]" value="{{$buyer['member_mobile']}}">
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
                    
                    <?php if ($bj['bj_baoxian'] && $bj['bj_bx_select']): ?>
                        <?php if ($baoxianname['bx_is_quanguo'] || $islocal): ?>
                            <p><b>投保约定：</b>指定投保（您已同意车辆首年商业保险在经销商处办理，现在您可以选择需要投保的险种预约投保。）</p>
                            <p><b>为您提供保险服务的保险公司：</b>{{$baoxianname['bx_title']}}</p>
                            <p>各险种的报价基准、推荐投保组合的参考保费明细，详见下方说明，您可在“我的投保”栏内根据需要选择：</p>
                        <?php endif ?>
                        
                    <?php elseif($bj['bj_bx_select']): ?>
                        <?php if ($baoxianname['bx_is_quanguo'] || $islocal): ?>
                                <p><b>投保约定：</b>自由投保</p>
                                <p><b>请选择您的投保安排：</b></p>
                                <div class="radio i-checks">
                                    <label>
                                        <input type="radio" name="final_baoxian" value="option2" checked="">
                                        在当地自主投保
                                    </label>
                                </div>
                                <div class="radio i-checks">
                                    <label>
                                        <input type="radio" name="final_baoxian" value="option2" checked="">
                                        在经销商处投保
                                    </label>
                                </div>
                                <p><b>经销商推荐的保险公司为：</b>{{$baoxianname['bx_title']}}</p>
                                <p>推荐保险公司首年商业车险各险种的报价基准、推荐投保组合的参考保费明细，详见下方说明，您可在“我的投保”栏内根据需要选择：</p>
                        <?php else: ?>
                            <p><b>投保约定：</b>自由投保</p>
                            <p>您可联系当地保险公司直接投保。</p> 
                        <?php endif ?>

                    <?php else: ?>
                        <p><b>投保约定：</b>自由投保</p>
                        <p>您可联系当地保险公司直接投保。</p> 
                    <?php endif ?>
                    <?php if ($bj['bj_bx_select']): ?>
                   
                    <table ms-controller="baoxian" class="tbl baoxian bx-self">
                        <tr>
                            <td class="cell" colspan="2">
                                <b>
                                    报价方式：
                                </b>
                                <input disabled  name="baoxian"   class="radio" type="radio"
                                />
                                <span>
                                    自选保险
                                </span>
                                <input disabled checked name="baoxian" class="radio" type="radio"
                                />
                                <span>
                                    推荐组合
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="50" valign="top" style="padding:0">
                                <table width="100%" height="100%" style="">
                                    <tr>
                                        <td style="border:0;height: 40px;border-bottom: 1px solid #dcdddd;padding: 0;margin:0;text-align: center;">
                                            <b>
                                                类别
                                            </b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;">
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14 tac">
                                                <b>
                                                    主
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14">
                                                <b>
                                                    &nbsp;
                                                </b>
                                            </p>
                                            <p class="fs14 tac">
                                                <b>
                                                    险
                                                </b>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="nopadding">
                                <table width="100%">
                                    <tr>
                                        <th width="160" class="">
                                            险种
                                        </th>
                                        <th class="" width="400">
                                            赔付选项
                                        </th>
                                        <th class="" width="140">
                                            报价基准
                                        </th>
                                        <th class="norightborder">
                                            我的投保
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="cell"><input ms-on-click="bxself('{{$chesun['discount_price']}}')" type="checkbox" class="radio jdcssx" checked="checked" name="baoxian[chesun]" value="{{$chesun['discount_price']}}" id="cs1" /> <label class="fn">机动车损失险</label></td>
                                        <td class="">
                                            按保险公司规定执行
                                        </td>
                                        <td class="">
                                            {{$chesun['price']}} 
                                            
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$chesun['discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell"><input ms-on-click="bxself('{{$daoqiang['discount_price']}}')" checked="checked" type="checkbox" name="baoxian[daoqiang]" value="{{$daoqiang['discount_price']}}" id="dq1" class="radio dqx" /> <label class="fn">机动车盗抢险</label></td>
                                        <td class="">
                                            按保险公司规定执行
                                        </td>
                                        <td class="">
                                            {{$daoqiang['price']}}
                                            
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$daoqiang['discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <input ms-attr-checked="isdisan"  ms-on-change="disanevent()" ms-on-click="bxself()" type="checkbox" class="radio zrx" 
                                            name="baoxian[sanzhe]" id="sz1" value="1" />
                                            <label class="fn">
                                                第三者责任保险
                                            </label>
                                        </td>
                                        <td class="cell" width="320">
                                            <p>
                                                赔付额度：
                                            </p>
                                            <div class="hide hfdiv">
                                                <input type="hidden" name="baoxian[sanzhe][compensate]" value="{{$sanzhe_r['compensate']}}万">
                                            </div>
                                            <?php foreach ($sanzhe as $key => $value): ?>
                                                <input name="baoxian[sanzhe][price]" ms-on-click="selectBX" data-bind="{{$value['price']}}" class="radio" type="radio" value="{{$value['discount_price']}}" id="szd{{$value['compensate']}}" <?php if ($value['compensate']==$sanzhe_r['compensate']): ?>
                                                    checked
                                                <?php endif ?>/><span>{{$value['compensate']}}万</span>
                                            <?php endforeach ?>
                                        </td>
                                        <td class="">
                                            <p style="display: none;">
                                                选择左侧赔付额度
                                            </p>
                                            <span>
                                            {{$sanzhe_r['price']}}
                                            </span>
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$sanzhe_r['discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell nobottomborder"><input ms-on-click="bxself2()" type="checkbox" class="radio cszrx" name="baoxian[renyuan]" value="1" checked="" id="ry1" /> <label class="fn">车上人员责任险</label></td>
                                        <td class="cell nopadding nobottomborder" width="320">
                                            <div>
                                                <p class="first-p">
                                                    驾驶人每次事故责任限额：
                                                </p>
                                                <div class="r-box">
                                                    <div class="hide hfdiv">
                                                        <input type="hidden" name="baoxian[renyuan][sj][compensate]" value="{{$renyuan_r['compensate']}}万">
                                                    </div>
                                                    <?php foreach ($renyuan as $key => $value): ?>
                                                    <?php if ($value['staff']=='ck') continue; ?>
                                                        
                                                    <input ms-on-click="selectBX2" data-bind="{{$value['discount_price']}}" name="baoxian[renyuan][sj][price]" class="radio" type="radio" value="{{$value['discount_price']}}" id="rysj{{$value['compensate']}}" <?php if ($renyuan_r['compensate']==$value['compensate']): ?>
                                                        checked
                                                    <?php endif ?>/><span>{{$value['compensate']}}万</span>
                                                <?php endforeach ?>
                                                </div>
                                                <h5 class="fenge">
                                                    乘客每次事故每人责任限额：
                                                </h5>
                                                <div class="r-box">
                                                    <div class="hide hfdiv">
                                                        <input type="hidden" name="baoxian[renyuan][ck][compensate]" value="{{$renyuan_r_ck['compensate']}}万">
                                                    </div>
                                                    <?php foreach ($renyuan as $key => $value): ?>
                                                    <?php if ($value['staff']=='sj') continue; ?>
                                                        
                                                    <input ms-on-click="selectBXBoth" data-bind="{{$value['discount_price']}}" data-bind-type="1" name="baoxian[renyuan][ck][price]" class="radio" type="radio" value="{{$value['discount_price']}}" id="ryck{{$value['compensate']}}"
                                                        <?php if ($value['compensate']==$renyuan_r_ck['compensate']): ?>
                                                            checked
                                                        <?php endif ?>
                                                    /><span>{{$value['compensate']}}万</span>
                                                <?php endforeach ?>
                                                </div>
                                                <h5 class="fenge" style="border-top:0">
                                                    乘客座位数：
                                                </h5>
                                                <div class="r-box">
                                                    <div class="hide hfdiv">
                                                        <input type="hidden" name="" value="">
                                                    </div>
                                                    <?php for ($i=1; $i < $bj['seat_num'];$i++) { 
                                                   ?> 
                                                    <input ms-on-click="selectBXBoth" data-bind="{{$i}}" data-bind-type="2"  name="baoxian[renyuan][seat]" class="radio" type="radio" value="{{$i}}" id="seat{{$i}}" 
                                                        
                                                    <?php if (($bj['seat_num']-1)==$i): ?>
                                                        checked
                                                    <?php endif ?>
                                                    /><span>{{$i}}座</span>
                                                <?php
                                                } 

                                                ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nobottomborder nopadding" width="130" align="center">
                                            <table class="tbl2 " width="100%" style="height: 181px;">
                                                <tr style="height: 62px;">
                                                    <td class="norightborder">
                                                        <p style="display:none">
                                                            选择左侧限额
                                                        </p>
                                                        <span>
                                                        {{$renyuan_r['price']}}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="norightborder nobottomborder">
                                                        <p style="display:none">
                                                            选择左侧限额和座位数
                                                        </p>
                                                        <span>
                                                        {{$renyuan_r_ck['price']}}
                                                        </span>
                                                        <span>
                                                        *{{($bj['seat_num']-1)}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="norightborder nopadding nobottomborder">
                                            <table class="tbl2 " width="100%" style="height: 181px;">
                                                <tr style="height: 62px;">
                                                    <td class="norightborder cts tongji">
                                                        {{$renyuan_r['discount_price']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="norightborder nobottomborder cts tongji">
                                                        <span>{{$renyuan_r_ck['discount_price']*($bj['seat_num']-1)}}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="30" class="" align="center">
                                <p class="fs14">
                                    <b>
                                        附
                                    </b>
                                </p>
                                <p class="fs14">
                                    <b>
                                        加
                                    </b>
                                </p>
                                <p class="fs14">
                                    <b>
                                        险
                                    </b>
                                </p>
                            </td>
                            <td class="nopadding ">
                                <table width="100%">
                                    <tr>
                                        <td class="cell " width="160">
                                            <input ms-on-click="bxself()" type="checkbox" class="radio" checked=""
                                            name="baoxian[boli]" value="1" id="bl1" />
                                            <label class="fn">
                                                玻璃单独破碎险
                                            </label>
                                        </td>
                                        <td class="cell" width="400">
                                            <div class="hide hfdiv">
                                                <input type="hidden" name="baoxian[boli][state]" value="{{$boli_r['state']=='jk'?'进口玻璃':'国产玻璃'}}">
                                            </div>
                                            <?php foreach ($boli as $key => $value): ?>
                                                <input ms-on-click="selectBX" data-bind="{{$value['discount_price']}}" value="{{$value['discount_price']}}" class="radio" type="radio" name="baoxian[boli][price]" id="bld{{$value['state']}}" 
                                                <?php if ($value['state']==$boli_r['state']): ?>
                                                    checked
                                                <?php endif ?>
                                                /><span><?php if ($value['state']=='jk'): ?>
                                                    进口玻璃
                                                <?php else: ?>
                                                    国产玻璃
                                                <?php endif ?></span>
                                            <?php endforeach ?>
                                        </td>
                                        <td class="" width="140">
                                            <p style="display: none">
                                                选择左侧玻璃类型
                                            </p>
                                            <span>
                                           {{$boli_r['price']}}
                                            </span>
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$boli_r['discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <input ms-on-click="bxself()" type="checkbox" class="radio chx" checked=""
                                            name="baoxian[huahen]" id="hh1" value="1" />
                                            <label class="fn">
                                                车身划痕损失险
                                            </label>
                                        </td>
                                        <td class="cell" width="320">
                                            <div class="hide hfdiv">
                                                <input type="hidden" name="baoxian[huahen][compensate]" value="{{$huahen_r['compensate']}}">
                                            </div>
                                            <p>
                                                赔付额度：
                                            </p>
                                            <?php foreach ($huahen as $key => $value): ?>
                                                <input ms-on-click="selectBX" data-bind="{{$value['discount_price']}}" name="baoxian[huahen][price]" class="radio" type="radio" value="{{$value['discount_price']}}" id="hhd{{$value['compensate']}}" 
                                                <?php if ($value['compensate']==$huahen_r['compensate']): ?>
                                                    checked
                                                <?php endif ?>
                                                /><span>{{$value['compensate']}}</span>
                                            <?php endforeach ?>
                                        </td>
                                        <td class="">
                                            <p style="display: none;">
                                                选择左侧赔付额度
                                            </p>
                                            <span>
                                            {{$huahen_r['price']}}
                                            </span>
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$huahen_r['discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input for="jdcssx" ms-on-click="bxself('120.00')" type="checkbox" class="radio fjx" checked="" name="baoxian[bjm_chesun]" value="{{$chesun['bjm_discount_price']}}" id="bjmcs" /> 
                                                <label class="fn ">
                                                    <span>不计免赔特约险</span>
                                                    <span class="pdi-tip">
                                                        <span class="psr">请先选择机动车损失险赔付额度<i></i></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                机动车损失险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        <td class="">
                                            {{$chesun['bjm_price']}}
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$chesun['bjm_discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input for="dqx" ms-on-click="mianpei" type="checkbox" class="radio fjx"
                                                name="baoxian[bjm_daoqiang]" value="{{$daoqiang['bjm_discount_price']}}" checked="" id="bjmdq" />
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    <span class="pdi-tip">
                                                        <span class="psr">
                                                            请先选择机动车盗抢险赔付额度
                                                            <i>
                                                            </i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                机动车盗抢险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        <td class="">
                                            {{$daoqiang['bjm_price']}}
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$daoqiang['bjm_discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input for="zrx" ms-on-click="mianpei" type="checkbox" class="radio fjx"
                                                name="baoxian[bjm_sanzhe]" value="{{$sanzhe_r['bjm_discount_price']}}" id="bjmsz" checked="" />
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    <span class="pdi-tip">
                                                        <span class="psr">
                                                            请先选择第三者责任险赔付额度
                                                            <i>
                                                            </i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                第三者责任险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        <td class="">
                                            {{$sanzhe_r['bjm_price']}}
                                        </td>
                                        <td class="norightborder tongji">
                                            {{$sanzhe_r['bjm_discount_price']}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell">
                                            <div class="psr">
                                                <input for="cszrx" ms-on-click="mianpei" type="checkbox" class="radio fjx"
                                                name="baoxian[bjm_renyuan]" value="{{$renyuan_r['bjm_discount_price']}}" checked="" id="bjmry" />
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    <span class="pdi-tip pdi-cszrx">
                                                        <span class="psr">
                                                            请先选择车上人员责任险责任限额和座位数
                                                            <i>
                                                            </i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell" width="320">
                                            <span>
                                                车上人员责任险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        <td class="">
                                            {{$renyuan_r['bjm_price']}}
                                        </td>
                                        <td data=' <!--(zerenxian.z + zerenxian.x * zerenxian.y) == 0 ?"" :zerenxian.z + zerenxian.x * zerenxian.y  -->' class="norightborder tongji">
                                            <span class="tongji-ry" style="display:none">
                                            {{$renyuan_r['bjm_discount_price']}}
                                           </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cell nobottomborder">
                                            <div class="psr">
                                                <input for="chx" ms-on-click="mianpei" type="checkbox" class="radio fjx"
                                                name="baoxian[bjm_huahen]" value="{{$huahen_r['bjm_discount_price']}}" checked="" id="bjmhh" />
                                                <label class="fn ">
                                                    <span>
                                                        不计免赔特约险
                                                    </span>
                                                    <span class="pdi-tip pdi-chx">
                                                        <span class="psr">
                                                            请先选择车身划痕损失险赔付额度
                                                            <i>
                                                            </i>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="cell nobottomborder" width="320">
                                            <span>
                                                车身划痕损失险，按保险公司规定执行。
                                            </span>
                                        </td>
                                        <td class=" nobottomborder">
                                            {{$huahen_r['bjm_price']}}
                                        </td>
                                        <td class="norightborder nobottomborder tongji">
                                            {{$huahen_r['bjm_discount_price']}}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tfoot>
                            <tr>
                                <td class="" colspan="2" align="center" valign="middle">
                                    <h4>
                                        已选投保组合总价：人民币
                                        <span class="juhuang">
                                            <!--total-->
                                        </span>
                                        元
                                    </h4>
                                    <p>
                                        <a href="javascript:;" data-grounp="照推荐组合投保" class="btn btn-s-md btn-danger">
                                            确定
                                        </a>
                                        <a href="javascript:;" data-grounp="照推荐组合投保" class="btn btn-s-md btn-danger">
                                            撤销
                                        </a>
                                        <a href="javascript:;" data-grounp="照推荐组合投保" class="btn btn-s-md btn-danger sure">
                                            已确定
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php endif ?>
                   
                    <p><b>支付方式:</b>在经销商处支付</p>
                    <p><b>您需要配合提供的文件资料:{{$toubao_file}}</b></p>
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
                    
                    <?php if ($bj['bj_shangpai']): ?>
                        <table class="tbl">
                        <tr>
                            <td width="50%"><p class="fs14"><b>上牌服务约定：</b>指定上牌（接受安排) <input type="hidden" name="shangpai" value="1"></p></td>
                            <td width="50%"><p class="fs14"><b>是否由经销商代办上牌：</b>是</p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p class="fs14"><b>上牌服务费约定：</b>（不高于）{{$bj['bj_shangpai_price']}}人民币元</p></td>
                            <td width="50%"><p class="fs14"><b>上牌费金额：</b>人民币{{$bj['bj_paizhao_price']}}元</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <?php if ($order['cartBase']['buytype']==1): ?>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$shangpaifile}}</p></td>
                        </tr>
                    <?php endif ?>
                    </table>
                <?php else: ?>
                    <table class="tbl">
                        <tr>
                            <td width="50%"><p class="fs14"><b>上牌服务约定：</b>本人上牌（接受安排）</p></td>
                            <td width="50%"><p class="fs14"><b>是否由经销商代办上牌：</b>否</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>客户本人上牌违约赔偿金额：{{$bj['bj_license_plate_break_contract']}} 元</b></p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>根据本车注册登记信息核实，如您违约将从买车担保金中扣除此金额。</p></td>
                        </tr>
                    </table>
                    <p><small>再次温馨提示：因各地政策差异，请务必咨询当地车辆管理部门上牌所需的资料。在下方有经销商交车时将移交给您的详细文件资料，请仔细核对，如有不明请提出，避免出现提车后无法上牌的困境。</small></p>
                    
                    <p><b>上牌服务约定：</b>自选上牌</p>
                    <p><b>请选择是否由经销商代办上牌：</b></p>
                    <p>
                        <label class="fn"><input checked="" ms-on-click="shangpai" value="0" type="radio" name="shangpai" /><span class="fn">否，本人亲自办理</span></label>
                        <label class="fn"><input ms-on-click="shangpai" type="radio" name="shangpai" value="1" class="ml20" /><span class="fn">是，委托经销商代办</span></label>
                    </p>
                    <table class="tbl sp"> 
                        <tr>
                            <td><p class="fs14"><b>客户本人上牌违约赔偿金额：{{$bj['bj_license_plate_break_contract']}} 元</b></p></td>
                        </tr>
                        <tr>
                            <td><p class="fs14"><b>支付方式：</b>根据本车注册登记信息核实，如您违约将从买车担保金中扣除此金额。</p></td>
                        </tr>
                    </table>

                    <table class="tbl sp" style="display: none;">
                        <tr>
                            <td width="50%"><p class="fs14"><b>上牌服务费约定：</b>（不高于）人民币{{$bj['bj_shangpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"><b>上牌服务费金额：</b>人民币{{$bj['bj_shangpai_price']}}元</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <?php if ($order['cartBase']['buytype']==1): ?>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$shangpaifile}}</p></td>
                        </tr>
                    	<?php endif ?>
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
                    
                    
                    <?php if ($bj['bj_linpai']==0): ?>
                        <p><b>上临时牌照约定：</b>自选服务</p>
                    <p><b>请选择是否由经销商代办车辆临时牌照：</b></p>
                    <p>
                        <label class="fn"><input checked=""  type="radio" name="linpai" value="0" /><span class="fn">否，本人亲自办理临时牌照，上路风险本人承担</span></label>
                        <label class="fn"><input  type="radio" name="linpai" value="1" class="ml20" /><span class="fn">是，委托经销商代办</span></label>
                    </p>
                    <table class="tbl sp" >
                        <tr>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费约定：</b>（不高于）人民币{{$bj['bj_linpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费金额：</b>人民币{{$bj['bj_linpai_price']}}元</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$linpai_file}}</p></td>
                        </tr>
                    </table>
                        <?php else: ?>
                            <?php if ($bj['bj_shangpai']==0): ?>
                                    <table class="tbl sp" >
                        <tr>
                            <td colspan="2"><p class="fs14"><b>上临时牌照约定：</b>指定服务（由您本人亲自上牌的，则须先委托经销商代办临时移动牌照）<input type="hidden" name="linpai" value="1"></p></td>
                        </tr>
                        <tr>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费约定：</b>（不高于）人民币{{$bj['bj_linpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费金额：</b>人民币{{$bj['bj_linpai_price']}}元</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$linpai_file}}</p></td>
                        </tr>
                    </table>


                    <p><b>上临时牌照约定：</b>指定服务（上牌服务由经销商代办，您可选择再办理一张临时移动牌照以避免上路风险）</p> 
                                <?php else: ?>
                                    <p><b>请选择是否由经销商代办车辆临时牌照：</b></p>
                    <p>
                        <label class="fn"><input checked=""  type="radio" name="linpai" value="0" /><span class="fn">否，本人亲自办理临时牌照，上路风险本人承担</span></label>
                        <label class="fn"><input  type="radio" name="linpai" class="ml20" value="1" /><span class="fn">是，委托经销商代办</span></label>
                    </p>
                    <table class="tbl sp" >
                        <tr>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费约定：</b>（不高于）人民币{{$bj['bj_linpai_price']}}元</p></td>
                            <td width="50%"><p class="fs14"><b>上临时牌照（每次）服务费金额：</b>人民币{{$bj['bj_linpai_price']}}元</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>支付方式：</b>在经销商处支付</p></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p class="fs14"><b>您需要配合提供的文件资料：</b>{{$linpai_file}}</p></td>
                        </tr>
                    </table>
                            <?php endif ?>
                            
                    <?php endif ?>
                    

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
                    
                    <?php if ($bj['bj_butie']): ?>
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <td class="prev-title">补贴名称</td>
                                <td><p class="fs14"><b>国家节能补贴</b></p></td>
                            </tr>
                            <tr>
                                <td class="prev-title">补贴说明</td>
                                <td>
                                    <p class="fs14">在政策有效期内，国家对符合“节能产品惠民工程”节能汽车标准条件车型给予的一次性定额补贴。</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">  办理所需文件</td>
                                <td>
                                    <p class="fs14">{{$butiefile}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title"><p>该经销商办理国家</p><p>节能补贴流程与时限</p></td>
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
                            <input type="radio" name="butie" id="" value="1" checked=""><label class="fn" for="">我同意按此流程办理</label>
                            <input type="radio" name="butie" id="" class="ml20" style="margin-left:20px!important" value=""><label class="fn" for="">不办了</label>
                        </p>
                    </div>
                    <?php endif ?>
                    <?php if ($bj['bj_zf_butie']): ?>
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
                                 <input type="radio" name="zhihuan" id="" value="1" checked=""><label class="fn" for="">我需要协助</label>
                            <input type="radio" name="zhihuan" id="" class="ml20" style="margin-left:20px!important" value=""><label class="fn" for="">不办了</label>
                            </p>
                        </div> 
                    <?php endif ?>
                    <?php if ($bj['bj_cj_butie']): ?>
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
                           <input type="checkbox" name="cj_butie" id="" value="1" checked=""><label class="fn" for="">我知道了</label>
                        </p>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <!--您的特别要求-->
            <div class="box">
                <div class="title">
                    <label ms-on-click="toggleContent">六、您的特别要求</label>
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
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][1][chesun]" value="自燃损失险"></p>
                                <dl>
                                    <dt>1.自燃损失险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input checked="checked"  type="radio" name="other_baoxian[chesun][1][bjm]"  value="是"><span>是</span></label>
                                        <label><input type="radio" name="other_baoxian[chesun][1][bjm]"  value="否" ><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][2][chesun]" value="新增加设备损失险"></p>
                                <dl>
                                    <dt>2.新增加设备损失险</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][3][chesun]" value="发动机涉水损失险"></p>
                                <dl>
                                    <dt>3.发动机涉水损失险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input type="radio" checked="checked" name="other_baoxian[chesun][3][bjm]" value="是" ><span>是</span></label>
                                        <label><input type="radio" name="other_baoxian[chesun][3][bjm]" value="否" ><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][4][chesun]" value="修理期间费用补偿险"></p>
                                <dl>
                                    <dt>4.修理期间费用补偿险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input type="radio"  checked="checked" name="other_baoxian[chesun][4][bjm]" value="是" ><span>是</span></label>
                                        <label><input type="radio" name="other_baoxian[chesun][4][bjm]" value="否" ><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][5][chesun]" value="车上货物责任险"></p>
                                <dl>
                                    <dt>5.车上货物责任险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input type="radio"  checked="checked" name="other_baoxian[chesun][5][bjm]" value="是" ><span>是</span></label>
                                        <label><input type="radio" name="other_baoxian[chesun][5][bjm]" value="否" ><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>

                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][6][chesun]" value="机动车损失险无法找到第三方特约险"></p>
                                <dl>
                                    <dt>6.机动车损失险无法找到第三方特约险</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>

                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[chesun][7][chesun]" value="指定修理厂险"></p>
                                <dl>
                                    <dt>7.指定修理厂险</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[renyuan][8][renyuan]" value="精神损害抚慰金责任险"></p>
                                <dl>
                                    <dt>8.精神损害抚慰金责任险（是否加不计免赔：</dt>
                                    <dd>
                                        <label><input type="radio"  checked="checked" name="other_baoxian[renyuan][8][bjm]" value="是" ><span>是</span></label>
                                        <label><input type="radio" name="jingsheng" name="other_baoxian[renyuan][8][bjm]" value="否" ><span>否</span></label>
                                        <div class="clear"></div>
                                    </dd>
                                    <dt>)</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[sanzhe][9][sanzhe]" value="增加第三者责任险保额"></p>
                                <dl>
                                    <dt>9.增加第三者责任险保额</dt>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </li>
                            <li>
                                <p><input type="checkbox" class="radio" name="other_baoxian[sanzhe][10][sanzhe]" value="增加车上人员责任险保额"></p>
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
                    <input type="text" name="other" value="" class="form-control w100p yaoqiu ml20" placeholder="选填" />
                    <p class="ml20">温馨提示：经销商将在预约交车反馈中详细回答，尽量满足您的要求。您须同意 : <b>经销商的反馈结果不对本订单其他约定内容的执行产生影响</b>。</p>
                    <p class="center btn-fl btn-save">
                {{--<a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger">保存</a>--}}
                        <input type="button" value="保存" class="btn btn-s-md btn-danger" ms-on-click="send_yuyue">
                        <a href="javascript:;" data-grounp="照推荐组合投保" class="btn btn-s-md btn-danger btn-tj">提交</a>
                    </p>
                    <p class="cell center m-t-10"><input type="checkbox" class="radio" name="agree-check"><label class="fn">我已阅读并同意上述通知内容</label></p>
                    <input type="hidden" name="id" value="{{ $order['cartBase']['id']}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                 
                </div>
            </div>

        
        </div>
        </form>
    </div>
@endsection
@section('js')
   
    <script type="text/javascript">
        <?php if ($bj['bj_bx_select']): ?>
    var zuhe=[{!!$zuhe!!}];
    var zuhe2=[{!!$zuhe!!}];
        <?php endif ?>
        seajs.use(["module/item/item-pdi-fix", "module/item/item-baoxian", "module/common/common", "bt"],function(){
            $("#countdown").CountDown({
              startTime:'{{$starttime}}',
              endTime :'{{$endtime}}',
              timekeeping:'countdown'
            })
            $("#timeout").CountDown({
            startTime:'2016-3-14 16:14:35',
            endTime :'2016-3-12 16:29:25',
            timekeeping:'timeout'
          })
        });
    </script>
@endsection