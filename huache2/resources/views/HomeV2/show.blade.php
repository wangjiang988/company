@extends('HomeV2._layout.base2')
{{$title = '诚意预约'}}
@section('css')
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('_layout.nav')
@endsection
@section('content')

    <div class="container m-t-86 psr">
        <div class="step psr">
            <ul>
                <li class="step-cur">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content">
                    <small class="juhuang">选择产品</small>
                    <i></i>
                    <small>付诚意金</small>
                    <i></i>
                    <small class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container psr content item">
        <form action="{{route('cartOne',['data'=>$en_data])}}" id="item-form" class="item-form" method="post">
            {{csrf_field()}}
            <input type="hidden" name="area" value="{{$area_id}}">
            <input type="hidden" name="shi" value="{{$d_shi}}">
            @if($shangpai == 4)
            <input type="hidden" name="shangpai_status" value="{{$shangpai_status}}">
            @endif
            <div class="wapper has-min-step">
                <h1>客官大人：</h1>
                <h1 class="ti">欢迎使用华车订购您的心仪座驾！以下内容全部确认同意并支付诚意金，即可轻松完成订购。</h1>
                <!--产品-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(0)">一、产品<i :class="{hidec:!item[0].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[0].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[0].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>
                    <div v-cloak v-show="item[0].isToggle" class="box-inner box-inner-def">
                        <h2 class="simp-title">车辆简况</h2>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">品牌</td>
                                <td>{{$brand}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">车系</td>
                                <td>{{$gc_series}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">车型规格</td>
                                <td>{{$gc_name}}(整车型号:{{$vehicle_model}})</td>
                            </tr>
                            <tr>
                                <td class="prev-title">车辆类别</td>
                                <td>全新中规车整车(
                                @if(($bj_is_xianche || (count($gitfs)>0 && $gitfs[0]['is_install'])) && isset($originals['rpo']))
                                已加装部分
                                @if($bj_is_xianche && isset($originals['rpo']))
                                <span class="juhuang cp" @click="srollTopShow(1)">原厂</span>
                                @endif
                                @if($bj_is_xianche && count($gitfs)>0 && $gitfs[0]['is_install'])
                                和
                                @endif
                                @if(count($gitfs)>0)
                                @if($gitfs[0]['is_install'])
                                <span class="juhuang cp" @click="srollTopShow(2)">非原厂</span>
                                @endif
                                @endif
                                选装精品，见下方详细说明
                                @else
                                暂未加装选装精品
                                @endif
								                                ）
																  </td>
                            </tr>
                            <tr>
                                <td class="prev-title">数量</td>
                                <td>1台</td>
                            </tr>
                            <tr>
                                <td class="prev-title">基本配置</td>
                                <td>
                                    <a class="juhuang cp tdu" href="/img/{{base64_encode(env('UPLOAD_URL').'/'.$detail_img)}}" target="_blank">查看基本配置</a>（注：本配置说明引自该品牌厂商权威官方网站资料 <a class="juhuang cp tdu" target="_blank" @if($official_url)href="{{$official_url}}"@endif>去官网查看配置</a>）
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">生产国别</td>
                                <td>{{$country}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">座位数</td>
                                <td>{{$seat_num}}座</td>
                            </tr>
                            <tr>
                                <td class="prev-title">车身颜色</td>
                                <td>{{$bj_body_color}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">内饰颜色</td>
                                <td>{{$interior_color}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">行驶里程</td>
                                <td>
                                @if($bj_is_xianche == 0)
                                (不高于）<span>20公里</span>
                                @else
                                (不高于)<span>{{$bj_licheng}}公里</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">出厂年月</td>
                                <td>
                                @if($bj_is_xianche == 0)
                                (不早于)<span>{{date("Y年m月", time())}}</span>
                                @else
                                (不早于)<span>{{date("Y年m月",strtotime($bj_producetime))}}</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">排放标准</td>
                                <td>符合{{$paifang}}标准</td>
                            </tr>
                        </table>
                       @if($bj_is_xianche && isset($originals['rpo']))
                        <h2 class="simp-title" id="yuanchang">已装原厂选装精品</h2>
                        <table class="tbl tac">
                            <tr>
                                <th width="150" class="prev-title">名称</th>
                                <th width="350" class="prev-title">型号/说明</th>
                                <th width="150" class="prev-title">厂商指导价</th>
                                <th width="100" class="prev-title">数量</th>
                                <th class="prev-title">附加价值</th>
                            </tr>
							 @foreach($originals['rpo'] as $key=>$original)
                             <tr @if(($key+1)%2==0) class="td-bg" @endif>
                                <td width="250">{{$original['xzj_title']}}</td>
                                <td width="250">{{$original['xzj_model']}}</td>
                                <td width="150" class="tar">￥{{number_format($original['xzj_guide_price'],2)}}</td>
                                <td width="100">{{$original['num']}}</td>
                                <td class="tar">￥{{number_format($original['xzj_guide_price']*$original['num'],2)}}</td>
                            </tr>
                            @endforeach
                        </table>
                        <h2 class="text-right "><b>合计价值：￥{{number_format($originals['rpo_sum'],2)}}</b></h2>
						@endif
                        @if(count($gitfs)>0)
                        <h2 class="simp-title" id="feiyuanchang">免费礼品或服务</h2>
                        <table class="tbl tac">
                                <tr>
                                <th width="" class="prev-title">名称</th>
                                <th width="" class="prev-title">数量</th>
                                <th class="prev-title">状态</th>
                            </tr>
                        @foreach($gitfs as $key=>$gitf)
                            <tr  @if(($key+1)%2==0) class="td-bg" @endif>
                                <td width="">{{$gitf['title']}}</td>
                                <td width="">{{$gitf['num']}}</td>
                                <td>@if($gitf['is_install'])已安装@else /@endif</td>
                            </tr>
                            @endforeach
                        </table>
                       @endif
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(0)" v-if="!item[0].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--车价-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(1)">二、车价<i :class="{hidec:!item[1].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[1].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[1].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>
                    <div v-cloak v-show="item[1].isToggle" class="box-inner box-inner-def">
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">厂商指导价</td>
                                <td>人民币{{number_format($zhidaojia,2)}} 元</td>
                            </tr>
                            <tr>
                                <td class="prev-title">您的华车车价</td>
                                <td>
                                    <p>人民币{{number_format($hwache_price,2)}}元({{num_to_rmb($hwache_price)}})</p>
                                    <p>（您所见的车价为包含经销商车辆开票价和华车服务费的一口价，且华车服务费在华车车价中所占比例低于5%。后续预约交车步骤前，将告知两项的具体金额。顺利完成的订单，华车服务费从买车担保金中扣减。已加装的所有原厂和非原厂选装精品，价值已包含在车辆开票价内，不另收费，但发票是否单独开具按经销商店内提示执行。）</p>

                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">支付方式</td>
                                <td>
                                    <p>全款</p>
                                    <p>（在授权经销商处当场直接支付<span class="juhuang">车辆开票价之全部金额</span>。华车服务费是在您如愿接收尊驾、本次订单完成后才与您结算，从买车担保金中扣除。</p>

                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">买车担保金</td>
                                <td>
                                    <p>人民币{{ number_format($client_sponsion_price,2) }}元</p>
                                    <p>（该金额将冻结在华车平台加信宝，订单执行完毕扣减华车服务费和其他违约赔偿<如产生>后，多余金额将解冻退还至您华车账户的可用余额，不用可申请提现。）</p>
                                </td>
                            </tr>
                        </table>
                        <table class="wp100">
                            <tr>
                                <td width="80" valign="top">温馨提示：</td>
                                <td>
                                    <p class="fs14">如您为他人或企业买车，买车担保金需先由您支付至加信宝进行担保，实际的买车上牌车主在提车时向授权经销商支付开票车款，获得发票抬头与付款车主一致的机动车销售统一发票。您实付的华车服务费，将可要求华车开具发票，发票抬头为上牌车主名称、或您实名认证后的真实姓名。</p>
                                </td>
                            </tr>
                        </table>
                        <p class="fs16 blue weight text-center mt50">客户资金流程</p>
                        <p class="fs12 blue text-center weight">（示例）</p>
                        <p class="text-center"><img class="wauto ml-15" src="/webhtml/order/themes/images/liucheng-1.png" alt=""></p>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(1)" v-if="!item[1].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--交车-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(2)">三、交车<i :class="{hidec:!item[2].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[2].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[2].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>
                    <div v-cloak v-show="item[2].isToggle" class="box-inner box-inner-def">
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">交车时限</td>
                                 @if($bj_is_xianche == 0)
                                 <td><p class="fs14 ti">买车担保金全额确认进入华车平台加信宝后的{{$bj_jc_period}}个月内</p></td>
                                 @else
                                 <td><p class="fs14 ti">买车担保金全额确认进入华车平台加信宝后的15个自然日内</p></td>
                                 @endif
                            </tr>
                            <tr>
                                <td class="prev-title">交车地点范围</td>
                                <td>
                                    <p class="fs14 ti">该车将在 <span class="fs16 weight">{{$scope}}</span> 内的该品牌厂商授权经销商的合法正规营业场所内交付，
                                    具体地点在后续交车通知中另行告知。<span class="tdu">本范围中所示地区为直辖市、地级市、自治州级别的行政区划，</span>
                                    <span class="tdu">包含了下辖的所有区、县级市、县或自治县！</span></p>

                                </td>
                            </tr>
                        </table>
                        <h6 class="fs14">温馨提示：您须作好自行前往上述范围内指定地点提车的各项准备，建议您按最不便的提车方案考虑，慎重决定。 </h6>
                        <div class="m-t-10"></div>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">文件资料</td>
                                <td class="ti">
                                  @foreach($annexs['tools'] as $tool)
                                  {{$tool['title']}}
                                  @if (!$loop->last)
                                   、
                                   @endif
                                  @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">随车工具</td>
                                <td>
                                    <p class="fs14 ti">
                                  @foreach($annexs['files'] as $file)
                                  {{$file['title']}}
                                  @if (!$loop->last)
                                   、
                                   @endif
                                  @endforeach
                                    </p>

                                </td>
                            </tr>
                        </table>
                        <h6 class="fs14">温馨提示：经销商将在交车时当场向您移交。 </h6>

                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(2)" v-if="!item[2].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--收费-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(3)">四、收费<i :class="{hidec:!item[3].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[3].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[3].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>

                    <div v-cloak v-show="item[3].isToggle" class="box-inner box-inner-def">

                       @if(count($others)>0)
                        <div class="price-wrapper">
                            <p><b>说明：</b>交车时您须向经销商支付的其它费用（不含您与经销商后续达成的新项目）。 </p>
                            <table class="tbl">
                                <tr>
                                    <th class="prev-title">费用名称</th>
                                    <th class="prev-title">金额</th>
                                </tr>
                           @foreach($others as $other)
                                <tr>
                                    <td><p class="fs16 tac">{{$other['other_name']}}</p></td>
                                    <td><p class="fs16 tac">（不高于）人民币{{number_format($other['sub_total'],2)}}元</p></td>
                                </tr>
                         @endforeach
                            </table>
                            <p class="tac fs16">共计：（不高于）人民币{{number_format($other_sum,2)}}元</p>
                        </div>
                        @endif
                        <h2 class="simp-title">在经销商处单车刷卡付款收费标准</h2>
                        <table class="tbl">

                            <tr>
                                <td class="prev-title">信用卡</td>
                                @if($xyk_status)
                                <td>
                                    <p class="fs16 tal">免费次数：{{$xyk_number}} 次，超出次数收费：刷卡金额的{{$xyk_per_num}}%（百分之）@if($xyk_yuan_num > 0)，每次{{$xyk_yuan_num}}元（封顶）@endif</p>
                                </td>
                                @else
                                <td>
                                    <p class="fs16 tal">单车付款刷信用卡免费次数：不限</p>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td class="prev-title">借记卡</td>
                                @if($jjk_status)
                                <td><p class="fs16 tal">免费次数：{{$jjk_number}} 次，超出次数收费：刷卡金额的{{$jjk_per_num}}%（百分之）@if($jjk_yuan_num > 0)，每次{{$jjk_yuan_num}}元（封顶）@endif</p></td>
                                @else
                                <td>
                                    <p class="fs16 tal">单车付款刷借计卡免费次数：不限</p>
                                </td>
                                @endif
                            </tr>
                        </table>


                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(3)" v-if="!item[3].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                 <!--上牌-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(4)">五、上牌<i :class="{hidec:!item[4].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[4].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[4].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>

                    <div v-cloak v-show="item[4].isToggle" class="box-inner box-inner-def">
                        <p class="tac">
                            <img src="/webhtml/order/themes/images/1.1_03.png" alt="">
                            <img src="/webhtml/order/themes/images/1.1_05.png" alt="" class="ml20">
                            <img src="/webhtml/order/themes/images/1.1_10.png" alt="" class="ml20">
                            <img src="/webhtml/order/themes/images/1.1_07.png" alt="" class="ml20">
                        </p>
                        <p class="fs16 weight">上牌，指机动车注册登记，取得机动车登记证书、号牌、行驶证、检验合格标志。</p>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">计划上牌地区</td>
                                <td>
                                    <p class="fs16">{{$area_city}}
                                    @if($area_xianpai)
                                    <span class="juhuang">（限牌城市）
                                    @endif
                                    </span></p>
                                </td>
                            </tr>
                        </table>
                        <table class="wp100">
                            <tr>
                                <td width="85" valign="top" class="fs16">温馨提示：</td>
                                <td>
                                    <p class="fs16 m0">1.机动车所有人应当向住所地的车辆管理所申请注册登记。</p>
                                    <p class="fs16 m0">2.因各地上牌政策差异，您提车后如临时决定改变尊驾的上牌地区，车辆及其配套上牌文件可能存在与新上牌地区之上牌政策不相符合的风险，故请慎重决定尊驾将来的上牌地区。</p>

                                    <p class="fs16 m0 juhuang">
                                    @if($tips->tips)
                                   3.{{$tips->tips}}
                                   @endif</p>
                                </td>
                            </tr>
                        </table>

                        <div class="xp-wrapper">
			           @if($area_xianpai)
                            <p class="fs16 mt10" id="special-notice-1"><b class="juhuang fs16">＊特别提醒：</b>当前计划上牌地区为限牌城市，需要您自行取得牌照指标方可上牌，请确认您取得牌照指标的安排：</p>
                            <p class="fs16">
                                <input :disabled="item[4].isAgree" type="radio" name="license_tag" v-model="step5.limitSelect" value="1"><span class="ml5">我已取得牌照指标</span>
                            </p>
                            <p class="fs16">
                                <input :disabled="item[4].isAgree" type="radio" name="license_tag" v-model="step5.limitSelect" value="0"><span class="ml5">我将在订车或提车后自行办理牌照指标，上路风险本人愿自行承担。</span>
                            </p>
                          @endif
                            <table class="tbl">
                             @if($shangpai == 1)
                                <tr>
                                    <td class="prev-title">上牌服务约定</td>
                                    <td>
                                        <p class="fs16">本人上牌</p>
                                        <p><small>（您须亲自办理上牌手续，经销商不代办。为避免出现您提车后无法上牌的情况，请在付诚意金预订前，先向上牌地车管所详询上牌流程和必备文件资料，并结合下方经销商将向您移交的文件资料，决定是否在华车平台订购您的心仪座驾。）</small></p>
                                    </td>
                                </tr>
                                 @elseif($shangpai == 3)
                                <tr>
                                    <td class="prev-title">上牌服务约定</td>
                                    <td>
                                        <p class="fs16">自选上牌</p>
                                        <p><small>（您在收到交车通知后选择：由您亲自办理上牌手续，或者委托经销商代办，并向其支付下方标准的服务费。）</small></p>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="prev-title">上牌服务约定</td>
                                    <td>
                                        <p class="fs16">接受安排</p>
                                        <p><small class="fs14">（因可能涉及异地上牌，我们将在预约交车前告知您上牌是由经销商代办还是由您亲自办理，您须完全接受我们所作的安排。如告知由经销商代办，则您将向其支付下方标准的服务费。也有可能告知由您亲自办理上牌手续，为避免出现您提车后无法上牌的情况，请在付诚意金预订前，先向上牌地车管所详询上牌流程和必备文件资料，并结合下方经销商将向您移交的文件资料，决定是否在华车平台订购您的心仪座驾。）</small></p>
                                    </td>
                                </tr>
                               @endif
                                <tr>
                                    <td class="prev-title">服务费金额</td>
                                    <td><p class="fs16">
				               @if(intval($agent_numberplate_price))
                                （不高于）人民币{{number_format($agent_numberplate_price,2)}}元
                                @else
                                人民币{{number_format($agent_numberplate_price,2)}}元
                                @endif</p></td>
                                </tr>
                                <tr>
                                    <td class="prev-title">支付方式</td>
                                    <td>
                                        <p class="fs16">在经销商处办理的，在经销商处支付。</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @if($shangpai != 1)
                        <h2 class="simp-title">经销商代办上牌服务</h2>
                        <p class="fs16 ti">指经销商为购车方代办机动车注册登记手续的服务，仅按车管所相关规则办理，不对牌号结果负责，也不含在某些限牌地区通过摇号、拍卖、转让等方式取得牌照资源指标的服务和费用。</p>
                        <table class="wp100">
                            <tr>
                                <td width="85" valign="top" class="fs16">温馨提示：</td>
                                <td>
                                    <p class="fs16 m0">1.根据政府的相关政策，成功上牌的前提需要您的户籍等条件达到上牌地区规定的资格。非当地户籍人士上牌的门槛可能包括（但不限于）：暂住证（且上网信息满一定时间）、非国内人士相关证件（原件与翻译文件）和临时住宿登记表（满一定时间），国内其他限牌城市户籍人士审批表... ...如有疑问请向当地车管所咨询。</p>
                                    <p class="fs16 m0">2.如您委托经销商代办上牌手续，须向经销商提供符合上牌地区上牌政策的所有必需文件（包括身份证明、牌照指标等)，否则您将<span class="juhuang">单独承担</span>无法上牌的所有后果。</p>
                                </td>
                            </tr>

                        </table>
                        @endif
                        <div class="m-t-10"></div>
                        <p class="fs16 m0" id="special-notice-2"><span class="juhuang weight">＊特别提醒：</span>请确认您在当地上牌是否需要经销商配合提供其他特殊文件。如需要，请从下方选项中勾选，在支付诚意金后，由经销商在24小时内反馈（此种情况下，如您无法接受反馈内容而终止订单，将<span class="tdu">只能退还诚意金，而无法获得歉意金赔偿</span>。）</p>
                        <div class="m-t-10"></div>
                        <p class="fs16">
                            <input :disabled="item[4].isAgree" type="radio" name="special_file" v-model="step5.parent" value="0"><span class="ml5">无其他特殊文件要求</span>
                        </p>
                        <p class="fs16">
                            <input :disabled="item[4].isAgree" type="radio" name="special_file" v-model="step5.parent" value="1"><span class="ml5">有其他特殊文件要求</span>
                        </p>
                        <div class="fs16 ml20" >
                        <?php $files = explode('|',$tips->special_file) ?>
                        @if(array_filter($files))
                        @foreach($files as $file)
                            <input :disabled="item[4].isAgree" type="checkbox" class="fn" v-model="step5.child" value="{{$file}}" name="file[{{$file}}]"><span class="fn">{{$file}}</span>
                       @endforeach
                        @endif
                            <a href="/member/special/add" target="_blank" class="juhuang tdu pl20">告诉华车 </a>
                            <span>（有未列出的其他新文件？先请告诉华车吧～）</span>
                        </div>
                        <br>

                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="relatedMatters" v-if="!item[4].isAgree" class="btn btn-s-md btn-danger fs16 fr ml10">我同意</a>
                            <span class="fs14 fr valite-error red " style="margin-top: 18px" v-show="!step5.isAgree">特别提醒的内容没选好哦～</span>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>



                <!--可选-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(5)">六、可选<i :class="{hidec:!item[5].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[5].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[5].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>


                    <div v-cloak v-show="item[5].isToggle" class="box-inner box-inner-def">
                        <h2 class="simp-title">商业车险</h2>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">商业车险投保约定</td>
                                <td>
                                    <p class="fs16">自由投保<br>（您可以在经销商处投保，当然也可以选择其他渠道投保。）</p>
                                </td>
                            </tr>
                        </table>
                        <hr class="dashed">
                        <h2 class="simp-title">代办临时牌照</h2>
                        <p class="wp50 fs16 ti fl">
                            仅指经销商为购车方代办车辆临时移动牌照，而不是机动车注册登记。根据《中华人民共和国交通安全法》，在没有取得正式牌照之前，必须按规定申领车辆临时移动牌照方能上路行驶。
                        </p>
                        <img src="/webhtml/order/themes/images/1.1_07.gif" alt="" style="margin-top: -20px;" width="320" class="fr">
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">代办临时牌照约定</td>
                                <td>
                                    <p class="fs16">自选服务<br>（您可以本人亲自办理车辆临时移动牌照，也可以在提车时委托经销商代办，向其支付下方标准的每次代办服务费。）</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">服务费金额（每次）</td>
                                <td><p class="fs16">
                                   @if(intval($agent_temp_numberplate_price))
                                    （不高于）人民币{{number_format($agent_temp_numberplate_price,2)}}元
                                    @else
                                    人民币{{number_format($agent_temp_numberplate_price,2)}}元
                                    @endif
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">支付方式</td>
                                <td>
                                    <p class="fs16">在经销商处办理的，在经销商处支付。</p>
                                </td>
                            </tr>
                        </table>

             @if(isset($originals['rear']) || isset($originals['rpo']))
                    @if($bj_is_xianche)
                        @if(isset($originals['rear']))
                        <hr class="dashed">
                        <h2 class="simp-title">原厂选装精品折扣</h2>
                        <p class="fs16 ti">如您需要在现有配置上加装其它的原厂选装精品，经销商在厂商指导价定价基础上给您的折扣优惠。比如定价100元，折扣率95%，则您享受的折后价为95元。                        </p>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">原厂选装精品折扣率</td>
                                <td colspan="3">
                                    <p class="fs16"> {{$bj_xzj_zhekou}}%</p>
                                </td>
                            </tr>
                            <!-- 现车才出现 -->
                            <tr>
                                <td class="prev-title">是否有安装费用</td>
                                <td colspan="3">
                                @if($originals['rpo_sum']>0)
                                    <p class="fs16">有 （部分选装精品需要额外的安装费用，在您挑选时有权选择购买或不买。）</p>
                                @else
                                    <p class="fs16">无</p>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">支付方式</td>
                                <td colspan="3">
                                    <p class="fs16">在经销商处支付</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="blue-bg">
                                    <p class="fs16 weight tac juhuang"><a href="/jingpinList/{{$bj_id}}" class="juhuang tdu" target="_blank">查看原厂选装精品的品种和厂商指导价</a></p>
                                </td>
                            </tr>
                        </table>
                        @else
                        {{--<p class="text-center fs16">本车型无可选原厂选装精品~</p>--}}
                        @endif
                    @else
                      @if(isset($originals['rpo']))
                                    <hr class="dashed">
                                    <h2 class="simp-title">原厂选装精品折扣</h2>
                                    <p class="fs16 ti">如您需要在现有配置上加装其它的原厂选装精品，经销商在厂商指导价定价基础上给您的折扣优惠。比如定价100元，折扣率95%，则您享受的折后价为95元。                        </p>
                         <table class="tbl">
                            <tr>
                                <td class="prev-title">原厂选装精品折扣率</td>
                                <td colspan="3">
                                    <p class="fs16"> {{$bj_xzj_zhekou}}% </p>
                                </td>
                            </tr>

                            <tr>
                                <td class="prev-title">支付方式</td>
                                <td colspan="3">
                                    <p class="fs16">在经销商处支付</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="blue-bg">
                                    <p class="fs18 weight tac">原厂选装精品</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">名称</td>
                                <td class="prev-title">型号/说明</td>
                                <td class="prev-title">厂商指导价</td>
                                <td class="prev-title">折后价</td>
                            </tr>
                            @foreach($originals['rpo'] as $original)
                            <tr>
                                <td class=""><p class="fs16">{{$original['xzj_title']}}</p></td>
                                <td class=""><p class="fs16">{{$original['xzj_model']}}</p></td>
                                <td class=""><p class="fs16 tar">￥{{number_format($original['xzj_guide_price'],2)}}</p></td>
                                <td class=""><p class="fs16 tar">￥{{bcmul($original['xzj_guide_price'],$bj_xzj_zhekou/100,2)}}</p></td>
                            </tr>
                            @endforeach
                        </table>
                        <p class="fs16">注：支付买车担保金后您可从容挑选选装精品。</p>
                        @else
                        {{--<p class="text-center fs16">本车型无可选原厂选装精品~</p>--}}
                        @endif
                    @endif
                @endif
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(5)" v-if="!item[5].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>


                <!--规则-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(6)">七、规则<i :class="{hidec:!item[6].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[6].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[6].isAgree" class="ok-code"><img width="38" src="/webhtml/order/themes/images/ok_03.png" alt=""></code>
                    </div>


                    <div v-cloak v-show="item[6].isToggle" :style="{opacity: item[6].isToggle ? 1 : 0}" class="box-inner box-inner-def">
                        <table>
                            <tr>
                                <td width="100" align="center" valign="top">
                                <img src="/webhtml/order/themes/images/1.1_17.png" alt="">
                                <td>
                                    <p class="fs16 ml10">是华车为购车客户和经销商共同打造的双向担保系统，用于独立、强力保障购车客户不打折扣地享受经销商承诺的各项服务。如有单方违约行为，通过加信宝赔偿解决，快速高效。</p>
                                </td>
                            </tr>
                        </table>
                        <h2 class="simp-title">购车中各方违约行为及后果</h2>

                        <table class="tbl tac">
                            <tr>
                                <td style="width: 104px" class="weight prev-title">订单阶段</td>
                                <td style="width: 173px" class="weight prev-title">加信宝冻结您的资金</td>
                                <td style="width: 145px" class="weight prev-title">您的违约行为</td>
                                <td style="width: 153px" class="weight prev-title">您的后果</td>
                                <td style="width: 148px" class="weight prev-title">售方违约行为</td>
                                <td style="width: 156px" class="weight prev-title">售方后果</td>
                            </tr>
                            <tr>
                                <td>诚意预约</td>
                                <td>诚意金￥499.00</td>
                                <td></td>
                                <td></td>
                                <td>修改、终止订单</td>
                                <td><span class="juhuang">赔偿歉意金￥499.00给您</span></td>
                            </tr>
                            <tr>
                                <td rowspan="2">付担保金</td>
                                <td class="red-bg">诚意金￥499.00</td>
                                <td class="red-bg">未按时足额支付买车担保金</td>
                                <td class="red-bg">赔偿诚意金￥499.00给售方</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>买车担保金</td>
                                <td></td>
                                <td></td>
                                <td>修改、终止订单、未按时发给您交车邀请</td>
                                <td><span class="juhuang">赔偿歉意金￥499.00、买车担保金利息给您</span></td>
                            </tr>
                            <tr>
                                <td rowspan="2">预约交车</td>
                                <td class="red-bg">买车担保金</td>
                                <td class="red-bg">非不可抗力原因拒绝预约</td>
                                <td class="red-bg">赔偿买车担保金给售方</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>买车担保金</td>
                                <td></td>
                                <td></td>
                                <td>非不可抗力原因拒绝预约</td>
                                <td><span class="juhuang">赔偿歉意金￥499.00、买车担保金利息给您</span></td>
                            </tr>
                            <tr>
                                <td rowspan="2">付款提车</td>
                                <td class="red-bg">买车担保金</td>
                                <td class="red-bg">非不可抗力原因拒绝提车</td>
                                <td class="red-bg">赔偿买车担保金给售方</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>买车担保金</td>
                                <td></td>
                                <td></td>
                                <td>非不可抗力原因终止履约、履约内容与约定不符</td>
                                <td><span class="juhuang">赔偿歉意金￥499.00、买车担保金利息、您的其他合理损失给您</span></td>
                            </tr>


                        </table>
                        <p class="fs16">注：买车担保金利息按每日0.02%计算。不可抗力、您的其他合理损失的界定范围，详见服务协议。</p>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(6)" v-if="!item[6].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a>
                            <div class="clear"></div>
                        </div>
                        <hr class="dashed hide" :class="{show:allAgree}">
                    </div>
                </div>

                <div class=" box hide-info-wrapper hide" :class="{show:allAgree}">

                    <h2 class="title noborder simp-title simp-title-large">落地价计算器</h2>
                    <div class="ml20">
                        <p class="fs16 ml5">想估算购车总花费？ <img src="/webhtml/order/themes/images/jt_03.png" alt=""> <a target="_blank" href="/car_calc/{{$bj_serial}}/{{$area_id}}/0" class="juhuang">落地价计算器</a></p>
                        <p class="fs16 ml5">温馨提示：全款购车，您需备好充裕资金，用途可能包括：您的华车车价、买车担保金（订单完成后返还余款）、车辆购置税、车船使用税、 上牌费、上临时牌照费、车辆商业保险保费、交强险保费、选装精品费用、售方其他杂费、提车回程费用等。</p>
                    </div>
                    <h2 class="simp-title simp-title-large">优惠说明</h2>
                    <div class="ml20">
                        <p class="fs16 ml5 ti">上述特别邀约，与经销商店内其他优惠不可兼得。您接受邀约，视为同意放弃厂商或经销商提供的其他额外优惠。但由国家或地方政府提供的补贴、税收优惠等，您可正常享受。</p>
                    </div>

                    <h2 class="simp-title simp-title-large">诚意金</h2>
                    <div class="ml20">
                        <p class="fs16 ml5">诚意金：<b class="tdu">人民币499.00元</b></p>
                        <p class="fs16 ml5">（须使用您在华车账户的可用余额支付，余额不足按流程先充值，充值款不买车可退还。）</p>
                        <p class="fs16 ml5 weight">特别提醒：诚意金成功付入加信宝，代表您已不可撤销地接受上述所有条件，订单即时生效。若非上牌特殊文件办理无法达成原因或售方违约原因造成订单终止，已付的诚意金是无法退还的！</p>
                    </div>
                    <h2 class="simp-title simp-title-large">买车担保金余款</h2>
                    <div class="ml20">
                        <p class="fs16 ml5 ti">根据华车规则，售方将在平台加信宝收到诚意金后的<span class="juhuang" v-show="step5.parent == '0' || step5.parent == ''">20分钟内</span><span class="juhuang" v-show="step5.parent == '1'">24小时</span>对订单内容再次进行确认，确认无误后您须立即开始支付买车担保金余款人民币{{number_format($client_sponsion_price-499,2)}}元（买车担保金总金额减去已付诚意金后的余款）。</p>
                        <p class="fs16 ml5 ti">目前有<a href="#" class="juhuang tdu">线上支付</a>和<a href="#" class="juhuang tdu">银行转账</a>两大类支付方式供您选择。</p>
                        <p class="fs16 ml5 ti">首笔余款须在24小时内提交支付，
                        <span v-show="step5.parent == '0' || step5.parent == ''">
                        尾款最迟须在{{date('Y年m月d日',strtotime("+3 day"))}}24点前完成全部支付
                        </span>
                        <span v-show="step5.parent == '1'">尾款最迟须在您确认接受日后的第三个自然日24点前完成全部支付</span>
                        （采用银行转账方式的，以有效银行汇款凭证的提交时间为准）。
                        </p>
                    </div>
                </div>

                <div class="tac mt50">
                    <div class="wauto mauto">
                        <input type="hidden" value="buy/bj_serial" name="" id="txturl">
                        <p class="hide" :class="{show:allAgree}">
                            <small>
                                <input v-if="!isSelectAgree.length" v-model="isSelectAgree" value="agree" type="checkbox" />
                                <img @click="noagree" style="margin-top:-5px;" width="13" v-if="isSelectAgree.length" src="/webhtml/common/images/agree.png" alt="">
                                <span class="agree-txt fn">我同意华车平台<a href="javascript:;" @click="service" class="juhuang">《服务协议》</a>. 并接受上述订单约定条款。</span>
                            </small>
                        </p>
                        <button :disabled="!allAgree" type="button" @click="pay" class="btn btn-s-md btn-danger btn-zhifu btn-disabled-zhifu">我要支付诚意金</button>
                        <p v-show="isClickPay && isSelectAgree.length == 0" class="fs14 text-center red m-t-10">接受服务协议我们才可为您服务哦～</p>
                        <p class="fs14 juhuang">@{{errorMsg}}</p>
                    </div>

                </div>

                <popup @update:status="getStatus" ref="popup">
                    <header slot="title">加信宝服务协议</header>
                    <h1 slot="header">加信宝服务协议</h1>
                    <div class="service-main" slot="main">
                        <p class="tar">版本生效时间：2017年07月</p>
                        <p>本协议是华车平台网站（网址：www.hWache.com）注册用户（以下简称为“用户”或“您”）与加信宝购车服务方（苏州华车电子商务有限公司，以下简称为“华车”），就购车交易服务等相关事宜所订立的契约，请您仔细阅读本服务协议，您勾选“我同意华车《服务协议》”、并成功支付诚意金后，本协议即构成对双方有约束力的法律文件。</p>
                        <p class="weight fs16">第1条 本协议的确认</p>
                        <p>1.1本协议有助于您了解华车为您提供的服务内容及您使用服务的权利和义务，请您仔细阅读（特别是以<b>粗体</b>标注的内容）。如果您对本协议的条款有疑问，您可通过华车平台网站的在线客服或客服电话进行咨询。</p>
                        <p>1.2如本协议发生变更，华车将通过华车平台网站以公示形式提前予以公告，变更后的协议在公告期届满起生效。若您在协议生效后继续使用本服务，表示您接受变更后的协议，也将遵循变更后的协议使用本服务。</p>
                        <p>1.3您应是中华人民共和国法律规定的具备完全民事权利能力和完全民事行为能力、能够独立承担民事责任的自然人。如您为无民事行为能力人或为限制民事行为能力人，则您及您的监护人应依照法律规定承担因此而导致的一切后果。</p>
                        <p>1.4您确认，使用您的华车平台网站账户向华车发出的所有指令，均是您本人在自愿状态下真实意愿的表达，若非法律规定或本协议约定之例外情形，您发出的指令是不可撤销的。</p>
                        <p>1.5您同意，在中华人民共和国大陆地区施行之法律允许的范围内，本协议是处理双方服务过程中权利义务的契约。</p>

                        <p class="weight fs16">第2条 服务说明</p>
                        <p>2.1您在华车平台网站上浏览到授权经销商发布的车辆信息，是符合以下标准和特征的汽车产品：</p>
                        <p>（1）质量达到《中华人民共和国产品质量法》、《中华人民共和国消费者权益保护法》、《汽车销售管理办法》及其他法规、部门规章和国家及地方强制性标准的规定，符合国家及地方汽车产品标准或行业标准，并符合出厂检验标准，符合安全驾驶和说明书载明的基本使用要求、尾气排放标准。</p>
                        <p>（2）是经国家有关部门公布、备案的汽车产品目录上的产品或合法进口的产品。</p>
                        <p>（3）未曾在中华人民共和国境内进行过车辆登记、未在厂商及经销商销售系统中出现过任何销售登记信息、未在厂商及经销商维修信息系统中出现过任何维修和保养记录。</p>
                        <p>2.2您在经销商处提车，<b>享有先验车后付款的权利</b>。验车的内容，至少包括对汽车产品外观、内饰、主要部件等可现场直接查验实物质量状况的检验，和对经销商已备妥完整车辆文件、随车工具、礼品赠品的检视。经销商不得在订单约定内容以外，强迫您代办上牌、代办临牌、投保车险、购买其他商品或服务。</p>
                        <p>2.3您在选中心仪的购买对象后，按照加信宝规则向售方发起订购，华车提供全程监督执行服务，保障您的应有权益。<b>该服务在交易完成后将向您收取一定的服务费用。如您购车最终未能如愿，不仅免收华车服务费，还由华车按照加信宝规则补偿您的损失。</b></p>
                        <p>2.4您通过华车订购车辆，应按照华车平台网站相关网页中展示的提示、说明、规定或政策等履行相关义务，也享有相关的权利。<b>该类提示、说明、规定或政策等与本服务协议共同构成您和华车的整体协议，您必须严格遵守。</b></p>

                        <p class="weight fs16">第3条 相关定义</p>
                        <p>3.1车辆开票价：是经销商给购车客户开具发票的总金额，在经销商店内支付。如果车辆在该车型基本配置上已加装部分选装精品，其价值已包含在车辆开票价内，不另收费，但精品发票是否单独开具按经销商店内提示执行。</p>
                        <p>3.2华车服务费：是华车为您购车提供保障和服务、付出有价值劳动的合理报酬。您成功购车、完成交易后才向您收取，<b>在退还买车担保金时由您向华车支付</b>。</p>
                        <p>3.3华车车价：是您在华车购车的总车价，包含车辆开票价和华车服务费两部分。订单生效后，根据订单执行的难易，华车与售方确定两者的具体金额（其中华车服务费在华车车价中所占比例低于5%），您须按照后续提示结果分别支付。</p>
                        <p>3.4买车担保金：是您就达成的购车约定向售方提供的履约担保，该金额冻结在加信宝，交易结束时解冻退还给您。<b>若您违约，将向售方赔偿与买车担保金相同金额的违约金</b>。</p>
                        <p>3.5诚意金：是您确定购车后付入加信宝担保您履行买车相关义务的保证金，加信宝收到冻结后订单生效。在您支付买车担保金余款时，已付的诚意金将作为到账的第一笔买车担保金。若非售方违约或上牌所需特殊文件无法达成一致的原因，而是<b>由您的原因违约，将向售方赔偿与诚意金相同金额的违约金</b>。</p>
                        <p>3.6歉意金：是售方违约时从加信宝补偿您的金额。</p>
                        <p>3.7买车担保金利息：您的买车担保金冻结在华车是不计息的，但售方违约时从加信宝向您额外补偿买车担保金冻结期间的利息，以弥补您的潜在损失，计息标准为每日0.02%（万分之二）。</p>
                        <p>3.8可售车源范围：亦称“交车地点范围”，是售方承诺将来向您交车地点的所在区域。因涉及车辆统筹调配，具体交车地点只能由售方安排，<b>在该范围内该品牌厂商任意一家授权经销商营业地点即可，请恕无法由您指定交车地点</b>。</p>

                        <p class="weight fs16">第4条 加信宝规则</p>
                        <p>4.1加信宝是华车为购车客户和售方共同打造的第三方双向担保系统。客户和售方通过加信宝增加互信、降低交易成本、提高交易效率，保障双方享有各自应有的权益。</p>
                        <p class="weight">4.2您的义务：支付诚意金、按时足额支付买车担保金余款、合理预约提车、按时付款提车。只要您履行上述义务，您的买车担保金最终分文不少。如果无不可抗力原因而您未能履行义务造成违约，您须承担的后果分别为：</p>
                        <p class="weight">（1）未按时足额支付买车担保金余款，则向售方赔偿与诚意金相同金额的违约金；</p>
                        <p class="weight">（2）拒绝预约提车，则向售方赔偿与买车担保金相同金额的违约金；</p>
                        <p class="weight">（3）拒绝付款提车，则向售方赔偿与买车担保金相同金额的违约金；</p>
                        <p class="weight">4.3您的保障：如售方无不可抗力原因而提出修改或终止订单、未及时发给您交车邀请、到交车时间不交车、实际车辆或移交内容与订单约定不符，均属于售方违约，由加信宝额外补偿您各阶段的损失：</p>
                        <p class="weight">（1）您的买车担保金足额到账前售方违约，则向您补偿歉意金；</p>
                        <p class="weight">（2）买车担保金足额到账后、您前往提车前售方违约，则向您补偿歉意金和买车担保金利息；</p>
                        <p class="weight">（3）您前往提车后、交车完成前售方违约，则向您补偿歉意金、买车担保金利息、您的其他合理损失；</p>
                        <p>4.4如售方修改订单违约，您获得相应补偿后，仍可选择接受修改条件继续订单，后续售方如再次违约您将再次获得补偿。</p>
                        <p>4.5您同意，对上述各阶段售方违约损失的补偿要求仅限于上述约定类别和范围，放弃通过华车或其他途径向售方要求任何额外、间接、偶然、派生、惩罚性损失赔偿的权利。</p>
                        <p>4.6不可抗力原因：本协议各方公认且无异议的不可抗力原因仅限于：自然灾害、罢工、暴乱、恐怖袭击、战争、政府行为、司法行政命令。其他不可抗力原因，须由提出方及时提交证据供华车审核。<b>您同意，授权华车判断并对华车不可抗力原因的判断结果不持异议</b>。</p>
                        <p>4.7您的其他合理损失：是您的提车行为直接关联的合理支出，包括：交通费、餐饮费、住宿费等。您使用的住宿和餐饮补偿仅按发生地经济型标准计算（事先您与售方达成正式约定的除外），且您须提供实际发生的必要凭证供华车审核。<b>您同意，授权华车判断并对华车最终认可的损失补偿不持异议</b>。</p>
                        <p>4.8华车收到各方异议申请后将尽力斡旋，推动订单继续完成。<b>如协商不成，您同意，授权华车对您或售方提出的异议赔付申请进行审核、裁判，不论裁判结果对您是否有利都不持异议</b>。</p>
                        <p>4.9售方违约或者您违约造成订单交易结束，未成功购车您无须再支付华车服务费。</p>
                        <p>4.10华车对加信宝规则拥有解释权。如果您对加信宝规则有疑问，您可通过华车平台网站的在线客服或客服电话进行咨询。</p>

                        <p class="weight fs16">第5条 资金操作授权</p>
                        <p class="weight">5.1您同意，授权华车可按照加信宝的冻结或解冻需要，向您在华车平台网站资金账户，发出查询、转入到加信宝、接收加信宝转出资金的相关指令。</p>
                        <p class="weight">5.2您同意，授权华车根据您的履约情况，将冻结在加信宝的买车担保金进行相应处置，包括但不限于：转付华车服务费、诚意金赔偿、买车担保金赔偿、退还您的资金账户。</p>
                        <p class="weight">5.3加信宝服务有赖于系统的准确运行及操作。若出现系统差错、故障或其他原因引发的展示错误、您或华车不当获利等情形，您同意华车可以采取更正差错的补救措施，包括但不限于：向您的资金账户发出扣划款项指令、接收补偿款项指令。</p>
                        <p class="weight">5.4加信宝的异议处理判断有赖于相关方提交证据的效用。若异议处理后出现新证据而须修正原有处理结果，您亦同意华车可以采用调账的补救措施，包括但不限于：向您的资金账户发出扣划款项指令、接收补偿款项指令。</p>

                        <p class="weight fs16">第6条 责任限定</p>
                        <p>6.1因下列原因导致华车无法正常为您提供服务，华车不承担责任：</p>
                        <p>（1）华车平台网站停机维护或升级；</p>
                        <p>（2）自然灾害、罢工、暴乱、恐怖袭击、战争、政府行为、司法行政命令等不可抗力；</p>
                        <p>（3）您操作不当或电脑软硬件和通信线路、供电线路出现故障；</p>
                        <p>（4）病毒、木马、恶意程序攻击、网络拥堵、系统不稳定、系统或设备故障、通讯故障、电力故障、支付机构等第三方中断服务等。</p>
                        <p>6.2邀约报价信息由经销商发布，信息的真实性亦由发布方承担责任，华车并不承担发布信息审核责任。但对达成订单的售方执行结果，华车会尽监督保障之责任。</p>
                        <p>6.3鉴于邀约报价信息会随库存情况实时更新，如因您付款延误造成库存不足报价失效而订购失败，华车不承担任何责任。您理解并同意，您在支付过程中，因网络中断、支付机构反馈信息错误等非华车原因造成的付款延误或失败，责任由您自行承担，华车不承担任何责任。</p>
                        <p class="weight">6.4如因厂家生产临时调整车俩配置，造成实际车辆与您订购时的参考配置存在差异，您理解并同意，不视其为售方所交车辆不符的违约行为，华车也不承担任何责任。</p>
                        <p>6.5由于购车是涉及众多因素的复杂行为，华车配套提供的落地价计算器服务，仅用于估算您购车的大致总花费，供您决策参考，华车不作任何最终花费金额的保证。</p>
                        <p class="weight">6.6交车时发现售方存在违约行为，您应及时向华车反映情况并提供必要证据。如您反映不及时或提交证据不充分造成华车无法有效判断，华车并不承担相关责任。</p>
                        <p class="weight">6.7您须确保上牌车主身份符合车辆上牌（注册登记）地区的有关政策要求，部分限牌地区需要的上牌指标由您本人准备。如发生此类问题影响上牌，华车不承担任何责任。</p>
                        <p class="weight">6.8您应按照华车提示的交车步骤、注意事项在经销商处仔细验收车辆、支付车款和约定费用。如由于您验收不仔细或放弃按流程操作，而给您带来风险和可能损失，华车对此无需承担任何责任。</p>
                        <p>6.9您理解，在异议调解服务中，华车客服并非专业人士，仅能以普通人的认知对提交凭证进行判断。因此您同意，除存在故意或重大过失外，对华车作出的异议调解和裁判决定免责。</p>
                        <p class="weight">6.10华车加信宝对您购车交易保障责任，从您成功支付诚意金起始，以您确认交车顺利完成的时间为限而自动终止。您与经销商后续达成的其他协议不在本协议保障范围内，此类协议包括但不限于：代办上牌协议、代办临牌协议、商业车险投保协议、车辆委托运输协议等，华车对此类协议无须承担任何责任。</p>
                        <p>6.11根据《家用汽车产品修理、更换、退货责任规定》（简称：汽车三包新规），汽车产品的质量责任由生产厂家和向您开具购车发票的经销商承担。<b>同时鉴于汽车产品的专业性，您理解并同意，交车之后如发现或发生车辆质量、实际车况有关的争议和纠纷，由您与经销商、生产厂家直接交涉处理，免除华车承担连带责任</b>。</p>

                        <p class="weight fs16">第7条 个人信息保护</p>
                        <p>7.1华车将严格保守您的个人信息，承诺未经过您的同意不对您的个人信息任意披露。</p>
                        <p>7.2但在以下情形下，华车可将您的信息与第三方分享，这些情形包括但不限于：</p>
                        <p>（1）事先获得您的同意或授权；</p>
                        <p>（2）根据法律法规的规定，或行政、司法机构的要求；</p>
                        <p>（3）为持续改进我们的服务，向华车的关联公司（包括但不限于华车的附属公司、控股公司、联营公司等）分享您的个人信息；</p>
                        <p class="weight">（4）为了您的交易顺利完成，向售方提供只与订单内容有关的您的个人信息，以便售方及时与您取得联系、提供服务。</p>

                        <p class="weight fs16">第8条 法律适用与管辖</p>
                        <p>8.1本协议的订立、生效、执行、修订、补充、解释、终止及争议解决均适用中华人民共和国大陆地区之有效法律（但不包括其冲突法规则）。如发生本协议与适用之法律相抵触，则这些条款将完全按法律规定重新解释，而其他有效条款继续有效。 </p>
                        <p>8.2如缔约方就本协议内容或其执行发生任何争议，双方应尽力友好协商解决；协商不成时，任何一方均可向华车所在地人民法院提起诉讼。</p>
                    </div>
                </popup>

            </div>


            <div class="floattip">
                <ul>
                    <li @click="hitTop"><span class="top"></span><a><label>回到</label><label>顶部</label></a></li>
                    <li><span class="ques"></span><a href="help.html"><label>问题</label><label>帮助</label></a></li>
                    <li><span class="kf"></span><a href="#"><label>在线</label><label>客服</label></a></li>
                </ul>
            </div>

        </form>


    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-detail", "/js/module/common/common"],function(v,u,c){
            //是否是限牌
            @if($area_xianpai)
            u.initIsLimit(true)
            @else
            u.initIsLimit(false)
            @endif
        })
    </script>
@endsection

