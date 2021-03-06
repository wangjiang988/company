@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
  <link href="{{asset('themes/item-fix-adv.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    <nav class="navbar navbar-inverse navbar-fixed-top" >
        <div class="container">
            <div id="navbar" class="collapse navbar-collapse">
                <div class="navbar-header pos-rlt">
                    <a class="navbar-brand logo" href="/">华车网</a>
                </div>
                <ul class="nav navbar-nav item-nav">
                    <li><a href="#baozhang">诚信保障</a></li>
                    <li><a href="#services">服务中心</a></li>
                </ul>
                <ul class="nav navbar-nav control">
                    <li class="loginout">
                        <label>欢迎您：<span>HC15206139216</span> </label>
                        <em>|</em>
                        <a href="javascript:;" @click="loginOut"><span>[</span>退出<span>]</span></a>
                        <div v-cloak v-show="isShowLoginOut" class="login-out-comfirm">
                            <p>退出登陆状态将重新回到<br>华车首页，是否继续？</p>
                            <div class="confirm">
                                <a @click="doLoginOut" href="javascript:;">确认退出</a>
                                <a @click="cancelLoginOut" href="javascript:;" class="cancel">取消</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <div class="container m-t-86 pos-rlt">
        <div class="step pos-rlt">
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
                    <small class="">卖方确认</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content item">
        <form action="" name="item-form" class="item-form">
            <div class="wapper has-min-step">
                <h1>客官大人：</h1>
                <h1>您即将通过华车平台预订汽车产品，请仔细阅读下方的订购说明，并按照逐个步骤的提示完成订购流程。</h1>
                <!--产品-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(0)">一、产品<i :class="{hidec:!item[0].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[0].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[0].isAgree">已同意</code>
                    </div>
                    <div v-cloak v-show="item[0].isToggle" class="box-inner box-inner-def">
                        <h2>您选择的汽车产品为：</h2>
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
                                @if($bj_is_xianche || (count($gitfs)>0 && $gitfs[0]['is_install']))
                                已加装部分
                                @if($bj_is_xianche)
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
                                    <a class="juhuang cp" href="{{env('UPLOAD_URL').'/'.$detail_img}}" target="_blank">查看基本配置</a>（注：本配置说明引自该品牌厂商权威官方网站资料 <a class="juhuang cp" href="{{$official_url}}" target="_blank">去官网查看配置）</a>
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
                                (不高于）<span>{{$bj_licheng}}公里</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">出厂年月</td>
                                <td>
                                @if($bj_is_xianche == 0)
                                (不早于）<span>{{date("Y年m月", time())}}</span>
                                @else
                                (不早于）<span>{{date("Y年m月",strtotime($bj_producetime))}}</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">排放标准</td>
                                <td>符合{{$paifang}}标准</td>
                            </tr>
                        </table>
                        @if($bj_is_xianche && $originals)
                        <h2>已装原厂选装精品：</h2>
                        <table class="tbl">
                            <tr>
                                <th width="250">名称</th>
                                <th width="250">型号/说明</th>
                                <th width="150">厂商指导价</th>
                                <th width="100">数量</th>
                                <th>附加价值</th>
                            </tr>
                            @foreach($originals['rpo'] as $original)
                            <tr>
                                <td width="250">{{$original['xzj_title']}}</td>
                                <td width="250">{{$original['xzj_model']}}</td>
                                <td width="150">￥{{number_format($original['xzj_guide_price'],2)}}</td>
                                <td width="100">{{$original['num']}}</td>
                                <td>￥{{number_format($original['xzj_guide_price']*$original['num'],2)}}</td>
                            </tr>
                            @endforeach
                        </table>
                        <h2 class="text-right pr150"><b>合计价值：</b><span class="juhuang">
                        ￥ {{number_format($originals['rpo_sum'],2)}}</span></h2>
                        @endif
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(0)" :disabled="item[0].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--车价与买车担保金-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(1)">二、车价与买车担保金<i :class="{hidec:!item[1].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[1].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[1].isAgree">已同意</code>
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
                                    <p>（您所见的车价为包含经销商车辆开票价和华车平台强力保障购车服务费金额的一口价。在交车前华车平台将</p>
                                    <p>告知您提车时在经销商处支付的车辆开票价，以及华车平台服务费的金额。华车平台承诺您实际支付的这两项</p>
                                    <p>金额之和不高于本约定购车价。）</p>

                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">支付方式</td>
                                <td>
                                    <p>全款</p>
                                    <p>（在授权经销商处当场直接支付<span class="juhuang">车辆开票价格之全部金额</span>。华车平台服务费是在本次交车完成后才与您结算，</p>
                                    <p>从买车担保金中扣除。）</p>

                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">买车担保金</td>
                                <td>
                                    <p>人民币{{number_format($client_sponsion_price,2)}}元</p>
                                    <p>（该金额将冻结在华车平台加信宝，订单执行完毕扣除华车平台服务费和其他违约赔偿（如产生）后，多余金额将</p>
                                    <p>原路返还给您。）</p>
                                </td>
                            </tr>
                        </table>
                        <p class="p-text fs14"><span class="xing">*</span>温馨提示：如您为他人或企业买车，买车担保金需先由您支付至华车平台并提供担保，实际的买车上牌车主在提车时向授权经销商支付开票车</p>
                        <p class="p-text ml12 fs14">款，获得发票抬头与付款人一致的机动车销售统一发票。</p>
                        <p class="fs16 blue weight text-center mt50">客户资金流程</p>
                        <p class="fs12 blue text-center">（示例）</p>
                        <p class="text-center"><img class="wp100" src="/themes/images/item/liucheng.png" alt=""></p>

                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(1)" :disabled="item[1].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
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
                        <code v-cloak v-show="item[2].isAgree">已同意</code>
                    </div>
                    <div v-cloak v-show="item[2].isToggle" class="box-inner box-inner-def">
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">交车时限</td>
                                 @if($bj_is_xianche == 0)
                                  <td class="fs16">买车担保金全额确认进入华车平台加信宝后的{{$bj_jc_period}}个月内</td>
                                 @else
                                  <td class="fs16">买车担保金全额确认进入华车平台加信宝后的15个自然日内</td>
                                 @endif
                            </tr>
                            <tr>
                                <td class="prev-title ">交车地点范围</td>
                                <td class="fs16">
                                    <p>该车将在 <span class="fs16 weight">{{$scope}}</span> 内的该品牌厂商授权经销商的合法正规营业场所内交付，具体地点在后续交车通知中另行告知。<span class="tdu">本范围中所示地区为直辖市、地级市、自治州级别的行政区划，</span><span class="tdu">包含了下辖的所有区、县级市、县或自治县</span>！</p>

                                </td>
                            </tr>
                        </table>
                        <h6 class="fs14"><span>*</span> 温馨提示：您须作好自行前往上述范围内指定地点提车的各项准备，建议您按最不便的提车方案考虑，慎重决定。 </h6>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(2)" :disabled="item[2].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--您的计划-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(3)">四、您的计划<i :class="{hidec:!item[3].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[3].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[3].isAgree">已同意</code>
                    </div>
                    <div v-cloak v-show="item[3].isToggle" class="box-inner box-inner-def">
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">承诺上牌地区</td>
                                <td>{{$area_city}}<span class="juhuang">
                                @if($area_xianpai)
                                （限牌城市）
                                @endif
                                </span></td>
                            </tr>
                        </table>
                        <h6 class="lh22 fs14"><span>*</span>特别提示：您所选择的上牌地区作为您所作的承诺，亦将正式称为订单约定内容的一部分。因各地上牌政策差异，您提车后如临时决定改变该车上牌地区，车辆及其配套上牌文件可能存在与新上牌地区之上牌政策不相符合的风险。并且，因某些厂家区域销售政策所限，上牌地区的擅自改变将可能给经销商带来某些损失，您必须由此作出违约赔偿，违约赔偿金有无及其金额详见下方第六条《上牌服务》。此外，擅自改变上牌地区还可能造成后续其他约定事项前提条件的改变，造成约定订单内容无法执行的后果，故请慎重决定尊驾将来的上牌地区。   </h6>
                        <table class="tbl"> 
                            <tr>
                                <td class="prev-title">车辆用途</td>
                                <td>
                                @if($buytype==1)
                                <p>非营业个人客车 （通俗称谓：公司自用车）。</p>
                                @else
                                    <p>非营业个人客车 （通俗称谓：私家车）。</p>  
                                 @endif
                                </td>
                            </tr>
                        </table>
                        <h6 class="lh22 fs14">
                            说明：因客观条件所限，请恕目前对营运用途车辆、机关单位用途车辆暂时无法提供服务。如有上述需求。建议走线下渠道直接向当地授权经
                            销商询洽。
                        </h6>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(3)" :disabled="item[3].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--车辆商业保险-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(4)">五、车辆商业保险<i :class="{hidec:!item[4].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[4].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[4].isAgree">已同意</code>
                    </div>

                 
                    <div v-cloak v-show="item[4].isToggle" class="box-inner box-inner-def">
                    @if($is_baoxian)
                    <p>
                            车辆首年商业保险约定：<b>指定投保</b> <small>（您须在经销商处投保）</small>
                        </p>
                        @else
                        <p>
                            车辆首年商业保险约定：<b>自由投保</b> <small>（您可以在经销商处投保，也可以在当地其他渠道投保） </small>
                        </p>
                        @endif
                     
                        <p class="fs14 lh22 nomargin ti">在经销商处投保车辆首年商业保险，参考投保项目和参考保费如下（
                        @if($buytype)
                        非营业企业客车
                        @else
                        非营业个人客车
                        @endif
                        ）：<span class="juhuang">（不高于）人民币{{number_format($baoxian_price,2)}}元 </span>  ，包括了下列商业保险项目组合（这些项目的组合再加上交强
                        险，可理解为某种常规概念上的全险）：机动车损失保险；机动车盗抢险；第三者责任保险：赔付额度 50万元；车上人员责任险（驾驶人1万元
                        限额+乘客4座各1万元限额）；玻璃单独破碎险（进口车为进口玻璃、国产车为国产玻璃）、车身划痕损失险 赔付额度2万元、不计免赔特约险（包含机动车损失保
                        险、机动车盗抢险、第三者责任保险、车上人员责任险、车身划痕损失险）。</p>
                        <p class="fs14 lh22 nomargin ti">如果您确认投保项目内容与上述项目内容完全一致，我们承诺您提车时在经销商处支付的保费不高于上述金额。您也可以在收到交车通知后根据需要选择投保项目和内容，我们亦承诺定价标准不变。</p>
                        <p class="m-t-10"> <b>支付方式</b>：<small>在经销商处购买的，在经销商处支付。</small></p>


                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(4)" :disabled="item[4].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--上牌服务-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(5)">六、上牌服务<i :class="{hidec:!item[5].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[5].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[5].isAgree">已同意</code>
                    </div>
                     
                    <div v-cloak v-show="item[5].isToggle" class="box-inner box-inner-def">
                        <p><img src="/themes/images/item/1.1_03.png" alt=""></p>
                        <p><b>说明：</b>指经销商为购车方代办机动车注册登记手续的服务，仅按车管所相关规则办理，不对牌号结果负责，也不含在某些限牌地区通过摇号、拍卖、转让等方式取得牌照资源指标的服务和费用。</p>
                        <table class="tbl">
                        @if($shangpai == 1)
                         <tr>
                                <td class="prev-title">上牌服务约定</td>
                                <td>
                                    <p class="fs14">本人上牌</p>
                                    <p><small>（您须亲自办理上牌手续，经销商不代办。为避免出现您提车后无法上牌的情况，请在付诚意金预订前，先向上牌地车管所详询上牌流程和必备文件资料，并结合下方经销商将向您移交的文件资料，决定是否在华车平台订购您的心仪座驾。）</small></p>
                                </td>
                            </tr>
                            @elseif($shangpai == 2)
                            <tr>
                                <td class="prev-title">上牌服务约定</td>
                                <td>
                                    <p class="fs14">指定上牌</p>
                                    <p><small>（您须委托经销商代办上牌手续，且向经销商支付下方标准的服务费。）</small></p>
                                </td>
                            </tr>
                           @elseif($shangpai == 3)
                            <tr>
                                <td class="prev-title">上牌服务约定</td>
                                <td>
                                    <p class="fs14">自选上牌</p>
                                    <p><small>（您在收到交车通知后选择：由您亲自办理上牌手续，或者委托经销商代办，并向其支付下方标准的服务费。）</small></p>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td class="prev-title">上牌服务约定</td>
                                <td>
                                    <p class="fs14">接受安排</p>
                                    <p><small>（因可能涉及异地上牌，我们将在预约交车前告知您上牌是由经销商代办还是由您亲自办理，您须完全接受我们所作的安排。如告知由经销商代办，则您将向其支付下方标准的服务费。也有可能告知由您亲自办理上牌手续，为避免出现您提车后无法上牌的情况，请在付诚意金预订前，先向上牌地车管所详询上牌流程和必备文件资料，并结合下方经销商将向您移交的文件资料，决定是否在华车平台订购您的心仪座驾。）</small></p>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="prev-title">服务费金额</td>
                                <td>
                                @if(intval($agent_numberplate_price)) 
                                （不高于）人民币{{number_format($agent_numberplate_price,2)}}元
                                @else
                                人民币{{number_format($agent_numberplate_price,2)}}元
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">支付方式</td>
                                <td>
                                    <p class="fs14">在经销商处办理的，在经销商处支付。</p>
                                </td>
                            </tr>
                        </table>
                        @if($buytype)
                        <p><span class="xing">*</span><b>上牌车主身份类别（请选择）：</b></p>
                        <p><input :disabled="item[5].isAgree" type="radio" name="chezhu" id="chezhu1" v-model="step6.isParentSelect" value="3"><label class="fn" for="chezhu1">上牌地本市注册企业（增值税一般纳税人）</label></p>

                        <p>
                        <input :disabled="item[5].isAgree" type="radio" name="chezhu" id="chezhu2" v-model="step6.isParentSelect" value="4"><label class="fn" for="chezhu2">上牌地本市注册企业（小规模纳税人）</label></p>

                        <!-- <div v-cloak class="red" v-show="step6.isAgree && step6.isParentSelect == 0">请选择上牌车主身份类别</div> -->
                        @else
                        <p><span class="xing">*</span><b>上牌人身份类别（请选择）：</b></p> 
                        <p><input :disabled="item[5].isAgree" v-model="step6.isParentSelect" type="radio" name="shangpai" id="shangpai" value="2" @click="setDefIdentity"><label class="fn" for="shangpai">上牌地本市户籍居民</label></p> 
                        <p><input :disabled="item[5].isAgree" v-model="step6.isParentSelect" type="radio" name="shangpai" id="qita" value="1"><label class="fn" for="qita">其他</label></p>

                        <p class="pl20"><input :disabled="item[5].isAgree" @click="selectParant(0)" type="radio" name="" value="1" v-model="step6.childVal"><label class="fn" for="">@{{step6.child[0].txt}}</label></p>
                        <div class="pl20">
                            <input :disabled="item[5].isAgree" @click="selectParant(1)" type="radio" name="" value="2" v-model="step6.childVal" class="fl" />
                            <!--国内其他限牌城市户籍居民-->
                            <label class="fn fl" for="">@{{step6.child[1].txt}}</label>
                            <ul class="city"> 
                                <!--国内其他限牌城市户籍居民 北京上海广州天津杭州贵阳深圳苏州-->
                                <li v-for="(item,index) in step6.child[1].list" @click="selectHouseholdRegistration(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i>@{{item.txt}}</li>
                                <!-- <li @click="selectHouseholdRegistration(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i> -->
                                
                                </li>
                                <li class="red" v-show="step6.isAgree && step6.childVal == 2  && step6.householdRegistration == '' ">请选择户籍所在城市</li>
                                <input type="hidden" name="huji" id="huji" :value="step6.householdRegistration" v-model="step6.householdRegistration">
                                <div class="clear"></div>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <p class="pl20">
                            <!--中国军人-->
                            <input :disabled="item[5].isAgree" @click="selectParant(3)" type="radio" name="" value="3" v-model="step6.childVal"><label class="fn" for="">@{{step6.child[2].txt}}</label>
                        </p>
                        <div class="pl20">
                            <!--非中国大陆人士-->
                            <input :disabled="item[5].isAgree" @click="selectParant(4)" type="radio" name="" value="4" v-model="step6.childVal" class="fl" />
                            <label class="fn fl" for="">@{{step6.child[3].txt}}</label>
                            <ul class="city">
                                <li v-for="(item,index) in step6.child[3].list" @click="selectForeign(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i>@{{item.txt}}</li>
                                <li class="red" v-show="step6.isAgree && step6.childVal == 4  && step6.foreign == '' ">请选择您的身份</li>
                                <input type="hidden" name="waiji" id="waiji" v-model="step6.foreign">
                                <div class="clear"></div>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        @endif
                        <!-- <div v-cloak class="red" v-show="step6.isAgree && (step6.isParentSelect == 1 || step6.isParentSelect == 0) && step6.childVal == ''">请选择上牌车主身份类别</div> -->

                        <p class="fs14">温馨提示：</p>
                        <p class="fs14 ti">根据政府的相关政策，成功上牌的前提需要您的户籍等条件达到上牌地区规定的资格。非当地户籍人士上牌的门槛可能包括（但不限于）：暂住证（且上网信息满一定时间）、非国内人士相关证件（原件与翻译文件）和临时住宿登记表（满一定时间），国内其他限牌城市户籍人士审批表... ...如有疑问请向当地车辆管理部门咨询。</p>
                        <p class="fs14 ti">如您委托经销商代办上牌手续，须向经销商提供符合上牌地区上牌政策的所有必需文件（包括身份证明、牌照指标等)、否则您将<span class="juhuang">单独承担</span>无法上牌的所有后果。</p>
                        <p class="fs14">
                        @if($area_xianpai)
                        <span class="red">*</span>
                        <b class="ml5">特别提醒：您的计划上牌地区<input type="text" class="baoxianinput juhuang " readonly="" value="{{$area_city}}">为限牌城市，需要您自行取得牌照指标方可上牌，请确认您取得牌照指标的安排：</b>
                        </p>
                        <p class="fs14">
                            <input :disabled="item[5].isAgree" type="radio" v-model="step6.licensingIndicators" value="1"><span>我已取得牌照指标</span>
                        </p>
                        <p class="fs14">
                            <input :disabled="item[5].isAgree" type="radio" v-model="step6.licensingIndicators" value="2"><span>我将在订车或提车后自行办理牌照指标，上路风险本人愿自行承担。</span>
                        </p>
                        <p class="fs14" ><span class="juhuang">
                        @if($tips['tips'])
                        特别提示：{{$tips['tips']}}
                        @endif
                        </span></p>
                        @endif
                        <hr class="dashed">
                        @if($shangpai!=2)
                        <table class="tbl">
                            <tr>
                                <td width="180"><p class="fs16"><b>上牌违约赔偿</b></p> </td>
                                <td><p class="fs14">因某些品牌厂商对经销商销售区域存在一定限制，如车主提车后在承诺上牌地区以外上牌，将使经销商遭受厂商处罚，车主须对此损失进行补偿。</p></td>
                            </tr>
                            <tr>
                                <td width="180"><p class="fs16"><b>上牌违约赔偿金额</b></p> </td>
                                <td><p class="fs14">人民币{{number_format($client_license_compensate,2)}}元</p></td>
                            </tr>
                            <tr>
                                <td><p class="fs16"><b>支付方式</b></p></td>
                                <td><p class="fs14">如发生此赔偿将在冻结的买车担保金中扣除。</p></td>
                            </tr>
                        </table>
                       @endif
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agreeRegistrationService" :disabled="item[5].isAgree" class="btn btn-s-md btn-danger fs16 fr">我同意</a> 
                            <span class="fs14 juhuang fr valite-error" v-show="step6.isAgree && (step6.isParentSelect == 1 || step6.isParentSelect == 0)">必选项目请选完整~</span>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--上临时牌照-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(6)">七、上临时牌照<i :class="{hidec:!item[6].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[6].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[6].isAgree">已同意</code>
                    </div>

                    
                    <div v-cloak v-show="item[6].isToggle" class="box-inner box-inner-def">
                        <p><img src="/themes/images/item/1.1_07.gif" alt=""></p>
                        <p><b>说明：</b>仅指办理车辆临时移动牌照，而不是机动车注册登记。根据《中华人民共和国交通安全法》，在没有取得正式牌照之前，必须按规定申领车辆临时移动牌照方能上路行驶。      </p>
                        <table class="tbl">
                        @if($bj_linpai)
                            <tr>
                                <td class="prev-title">上临时牌照约定</td>
                                <td>
                                    <p class="fs16">指定服务</p>
                                    <p><small>（由您本人亲自上牌的，则必须先委托经销商代办临时移动牌照，并向其支付下方标准的服务费。由经销商代办上牌的，您在预约交车步骤中可选择办理临时移动牌照以避免上路风险，但也必须委托经销商代办，并向其支付下方标准的服务费。）</small></p>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td class="prev-title">上临时牌照约定</td>
                                <td>
                                    <p class="fs16">自选服务</p>
                                    <p><small>（您在预约交车步骤中可选择是否委托经销商代办临时移动牌照，如委托则向其支付下方标准的服务费。）</small></p>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="prev-title">服务费金额（每次）</td>
                                <td>
                                    <p class="fs14">
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
                                    <p class="fs14">在经销商处办理的，在经销商处支付。</p>
                                </td>
                            </tr>
                        </table>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(6)" :disabled="item[6].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                        
                    </div>
                </div>
                <!--卖方其他收费-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(7)">八、售方其他收费<i :class="{hidec:!item[7].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[7].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[7].isAgree">已同意</code>
                    </div> 
                   
                    <div v-cloak v-show="item[7].isToggle" class="box-inner box-inner-def">
                        <p>
                            <b>说明：</b>交车时您须向经销商支付的其它费用（不含您与经销商后续达成的新项目）。
                        </p>
                        <table class="tbl">
                         @if(count($others)>0)
                            <tr>
                                <th>费用名称</th>
                                <th>金额</th>
                            </tr>
                            @foreach($others as $other)
                            <tr>
                                <td><p class="fs14 tac">{{$other['other_name']}}</p></td>
                                <td><p class="fs14 tac">（不高于）人民币{{number_format($other['sub_total'],2)}}元</p></td>
                            </tr>
                            @endforeach
                            @if(count($others)>0)
                            <tr>
                                <td  colspan="2">
                                    <p class="fs14 text-right">共计：（不高于）人民币 {{number_format($other_sum,2)}}元&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                </td>
                            </tr>
                            @endif
                             <tr class="trbg">
                                <td  colspan="2">
                                    <p class="fs14 zhifu"><b>支付方式</b>在经销商处支付。</p>
                                </td>
                            </tr>
                            @else
                            <p class="text-center fs16">没有其他已确定收费~</p>
                            @endif
                        </table>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(7)" :disabled="item[7].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div> 

                <!--免费礼品或服务-->
                <div class="box">
                    <div class="title">
                        <label id="liping" @click="toggleContent(8)">九、免费礼品或服务<i :class="{hidec:!item[8].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[8].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[8].isAgree">已同意</code>
                    </div>

                    
                    <div v-cloak v-show="item[8].isToggle" class="box-inner box-inner-def">
                        <table class="tbl">
                        @if(count($gitfs)>0)
                            <tr>
                                <th>名称</th>
                                <th>数量</th>
                                <th>状态</th>
                            </tr>
                             @foreach($gitfs as $gitf)
                            <tr>
                                <td><p class="fs14 tac">{{$gitf['title']}}</p></td>
                                <td><p class="fs14 tac">{{$gitf['num']}}</p></td>
                                <td><p class="fs14 tac">
                                @if($gitf['is_install'])
                                已安装
                                @else
                                /
                                @endif
                                </p></td>
                            </tr>
                            @endforeach
                            @else
                            <p class="text-center fs16">没有已确定的免费礼品或服务~</p>
                            @endif
                           
                        </table>
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(8)" :disabled="item[8].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                         
                    </div>
                </div>

                <!--原厂选装精品折扣率和报价-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(9)">十、选装精品<i :class="{hidec:!item[9].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[9].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[9].isAgree">已同意</code>
                    </div> 
                  
                    <div v-cloak v-show="item[9].isToggle" class="box-inner box-inner-def">
                        <p><b>说明：</b>如您需要在现有配置上加装其它的原厂选装精品，经销商在厂商指导价定价基础上给您的折扣优惠。比如定价100元，折扣率95%，则您享受的优惠价为95元。</p>
                    @if($bj_is_xianche)
                       @if(isset($originals['rear']))
                        <table class="tbl">
                            <tbody>
                                <tr>
                                    <td class="prev-title">原厂选装精品折扣率</td>
                                    <td><p class="fs14">{{$bj_xzj_zhekou}}%</p></td>
                                </tr>
                                <tr>
                                    <td class="prev-title">是否有安装费用</td>
                                    <td>
                                        <p class="fs14">
                                        @if($originals['rpo_sum']>0)
                                        有
                                        @else
                                        无 
                                        @endif 
                                        ( 部分选装精品需要额外的安装费用，在您挑选时有权选择购买或不买。） </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="prev-title">支付方式</td>
                                    <td>
                                        <p class="fs14">在经销商处支付。</p>
                                    </td>
                                </tr>
                                <tr class="trbg">
                                    <td colspan="2">
                                        <p class="tac fs16"><a class="juhuang tdu" href="#link">查看原厂选装精品的品种和厂商指导价</a></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p><small>注：您可在支付买车担保金后选择购买选装精品。</small></p>
                        @else
                        <p class="text-center fs16">本车型无可选原厂选装精品~</p>
                        @endif
                    @else
                       @if(isset($originals['rpo']))
                        <table class="tbl">
                            <tbody>
                                <tr>
                                    <td class="prev-title">原厂选装精品折扣率</td>
                                    <td>{{$bj_xzj_zhekou}}%</td>
                                </tr>
                                
                                <tr>
                                    <td class="prev-title">支付方式</td>
                                    <td>
                                        <p class="fs14">在经销商处支付。</p>
                                    </td>
                                </tr>
                                <tr class="trbg">
                                    <td colspan="2">
                                        <p class="tac fs16"><b>清单列表</b></p>
                                    </td>
                                </tr>
                                <tr >
                                    <td colspan="2" class="nopadding">
                                        <table class="tbl nomargin" >
                                            <tr>
                                                <th class="noleftborder notopborder">原厂选装件名称</th>
                                                <th class="notopborder">型号/说明</th>
                                                <th class="notopborder">厂商指导价</th>
                                                <th class="norightborder notopborder">折后价  </th>
                                            </tr>
                                            @foreach($originals['rpo'] as $original)
                                            <tr>
                                                <td class="noleftborder nobottomborder"><p class="fs14 tac">{{$original['xzj_title']}}</p></td>
                                                <td class="nobottomborder"><p class="fs14 tac">{{$original['xzj_model']}}</p></td>
                                                <td class="nobottomborder"><p class="fs14 tac">￥{{number_format($original['xzj_guide_price'],2)}}</p></td>
                                                <td class="nobottomborder norightborder"><p class="fs14 tac">￥{{bcmul($original['xzj_guide_price'],$bj_xzj_zhekou/100,2)}}</p></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p>注：您可在支付买车担保金后选择购买选装精品，到时将有更多精品供您挑选。</p>
                        @else
                        <p class="text-center fs16">本车型无可选原厂选装精品~</p>
                        @endif
                    @endif
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(9)" :disabled="item[9].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <!--交车有关事宜-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(10)">十一、交车有关事宜<i :class="{hidec:!item[10].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[10].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[10].isAgree">已同意</code>
                    </div>

                   
                    <div v-cloak v-show="item[10].isToggle" class="box-inner box-inner-def">
                        <p> 经销商将在交车时当场向您移交下列文件资料和随车工具。</p>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">文件资料</td>
                                <td><p>{{implode(',', $annexs['files'])}}</p></td>
                            </tr>
                            <tr>
                                <td class="prev-title">随车工具</td>
                                <td>
                                    <p>{{implode(',', $annexs['tools'])}}</p>
                                </td>
                            </tr>
                        </table>
                        <p class="fs16"><b>在经销商处单车刷卡付款收费标准：</b></p>
                        <table class="">
                            <tr>
                             
                                <td>
                                @if($xyk_status)
                                    <p>单车付款刷信用卡免费次数：{{$xyk_number}} 次，超出次数收费{{$xyk_yuan_num}}元：刷卡金额的{{$xyk_per_num}}%（百分之）；</p>
                                @else
                                <p>单车付款刷信用卡免费次数：不限</p>
                                @endif
                                @if($jjk_status)
                                    <p>单车付款刷借计卡免费次数：{{$jjk_number}}次，超出次数收费：刷卡金额的{{$jjk_per_num}}%（百分之），  每次{{$jjk_yuan_num}}元（封顶）；</p>
                                @else
                                <p>单车付款刷借计卡免费次数：不限</p>
                                @endif
                                </td>
                            </tr>
                          
                        </table>
                        @if($shangpai!=2)
                        <p class="fs14">温馨提示：上述文件资料为经销商交车时移交给您的常规文件，如您可能需要亲自办理上牌手续，请在支付诚意金前，向当地车辆管理部门咨询清楚是否有需要经销商配合提供的其他特殊文件，并在支付诚意金同时提交，由经销商在反馈中确认（此种情况下，如反馈无法办理该文件，或者您无法接受反馈的办理时间和办理费用终止订单，将只能退还诚意金，而无法获得歉意金等赔偿。）</p>
                        <p class="fs14">
                            <input :disabled="item[10].isAgree" type="radio" name="xx" v-model="step11.parent" value="0"><span class="ml5">无其他特殊文件要求</span>
                        </p>
                        <p class="fs14">
                            <input :disabled="item[10].isAgree" type="radio" name="xx" v-model="step11.parent" value="1"><span class="ml5">有其他特殊文件要求</span>
                        </p>
                        <div class="fs14 ml20" >
                        <?php $files = explode('|',$tips['special_file']) ?>
                        @if(array_filter($files))
                        @foreach($files as $file)
                            <input :disabled="item[10].isAgree" type="checkbox" class="fn" v-model="step11.child" value="{{$file}}"><span class="fn">{{$file}}</span>
                        @endforeach
                        @endif
                            <a href="#" class="juhuang tdu pl20">告诉华车 </a>
                            <span>（没有列出的其他新文件，请先提交华车平台审核）</span>
                            <div class="hide">
                                <input type="checkbox" class="fn" ms-on-click="moreFile" checked><span class="fn">其它</span>
                                <span class="fileinput fileinput-open">
                                    <input type="text" placeholder="输入"> 
                                    <span class="add-fileinput juhuang fn cp" ms-on-click="addFileInput">增加</span>
                                </span>
                            </div>
                        </div>
                        @endif
                        <br>
                        
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="relatedMatters" :disabled="item[10].isAgree" class="btn btn-s-md btn-danger fs16 fr">我同意</a> 
                            <span class="fs14 juhuang fr valite-error" v-show="!step11.isAgree">请选择是否有特殊文件要求~</span>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

              

                <!--补贴-->
                <div class="box">
                    <div class="title">
                        <label @click="toggleContent(11)">十二、补贴<i :class="{hidec:!item[11].isToggle}"></i></label>
                        <em></em>
                        <code v-cloak v-show="!item[11].isAgree" class="besure">待确定</code>
                        <code v-cloak v-show="item[11].isAgree">已同意</code>
                    </div>

                   
                    <div v-cloak v-show="item[11].isToggle" class="box-inner box-inner-def">
                    <p class="fs16 text-center">没有补贴项目~</p>
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">补贴名称</td>
                                <td><p><b>地方政府置换补贴  </b></p></td>
                            </tr>
                            <tr>
                                <td class="prev-title">补贴说明</td>
                                <td>
                                    <p class="fs14">该上牌地区的政府对旧车置换新车提供的补贴。</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">经销商是否提供协助</td>
                                <td>
                                    <p class="fs14">是</p>
                                </td>
                            </tr>
                            
                             
                        </table>
                        <p class="fs14">温馨提示：经销商并不负责办理该补贴，仅对您向有关部门申请该补贴提供力所能及的协助。如经销商属异地，所提供的协助可能存在不被上牌地区当地政府所认可的风险，具体申请政策和手续请向当地政府相关部门咨询。</p>
                        
                        <table class="tbl">
                            <tr>
                                <td class="prev-title">补贴名称</td>
                                <td><p><b>厂家或经销商置换补贴</b></p></td>
                            </tr>
                            <tr>
                                <td class="prev-title">补贴说明</td>
                                <td>
                                    <p class="fs14">该品牌的厂家或经销商为消费者旧车置换新车提供的补贴。</p>
                                </td>
                            </tr>
                        </table>
                        <p class="fs14">
                            温馨提示：由于可能涉及旧车估价等不确定因素，您需要在提车之前与经销商另行商定补贴金额。此处仅提供有无补贴的信息，并不具有合同强制效力。
                        </p>
                        
                        <div class="control-wapper mt20">
                            <a href="javascript:;" @click="agree(11)" :disabled="item[11].isAgree" class="btn btn-s-md btn-danger fs16">我同意</a> 
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <ul class="tiaokuan">
                    <li>
                        <p class="ifl fs14"><i class="yuan"></i></p>
                        <div class="ifl wp90">
                            <p>本次购车<a target="_blank" href="/car_calc/{{$bj_serial}}/{{$area_id}}/{{$buytype}}" class="juhuang tdu">落地价计算器 </a> </p>
                            <p class="fs14">温馨提示：全款购车，您需作好充分的资金准备，可能包括：您的华车车价金额、买车担保金（订单完成后返还余款）、购置税、车船税、
                            上牌费、上临时牌照费、车辆商业保险保费、交强险保费、选装精品费用、售方其他杂费、提车回程费用等。
                            </p>
                        </div>
                   
                        <div class="clear"></div>
                    </li>
                    <li>
                        <p class="ifl fs14"><i class="yuan"></i></p>
                        <div class="ifl wp90">
                            <p>上述特别邀约，与经销商店内其他优惠<b>不可兼得</b>。您接受邀约，视为同意放弃厂商或经销商提供的其他额外优惠。但由国家或地方政府提供的补贴、税收优惠等，您可正常享受。
                            </p>
                            
                        </div>
                        <div class="clear"></div>
                    </li>
                   
                    <li>
                        <p class="ifl fs14"><i class="yuan"></i></p>
                        <div class="ifl wp90">
                            <p>根据华车平台规则，经销商将在平台收到诚意金后的<span class="juhuang tdu">规定时间</span>内对订单内容再次进行确认，确认无误后您须立即开始支付买
                            车担保金余款<span class="tdu"><b>人民币{{number_format($client_sponsion_price-499,2)}}元</b></span>（买车担保金总金额减去已付诚意金后的余款）。目前我们提供<span class="juhuang tdu"><b>线上支付</b></span>和<span class="juhuang tdu"><b>银行转账</b></span>
                            两种支付方式供您选择。银行转账方式，您须在24小时内提交有效的银行汇款凭证。线上支付方式因第三方支付
                            的支付限额可能受限影响支付成功，可分笔支付。您在当日付完第一笔后，支付时限自动延长到第三个自然日的24点，
                            您可从容完成买车担保金余款支付。
                            </p>
                            
                        </div>
                        <div class="clear"></div>
                    </li>

                    <li>
                        <p class="ifl fs14"><i class="yuan"></i></p>
                        <div class="ifl wp90">
                            <p class="lh22">诚意金：<span class="tdu"><b class="tdu">人民币499.00元</b></span> （只能使用您在华车平台的可用余额支付，余额不足按流程先充值，充值款不买车可退还。）
                            <b>根据华车平台规则，诚意金的成功支付将表示您已不可撤销地接受上述所有条件，订单即时生效。若非对方原因终止订
                            单，诚意金是无法退还的。如您成功支付后遇到经销商违反以上邀约，华车平台核实后不仅诚意金可退，还将向您赔偿
                            歉意金人民币499.00元</b>（见<a href="#" class="juhuang tdu">平台保障政策</a>）。
                            </p>
                        </div>
                        <div class="clear"></div>
                        
                    </li>
                </ul>

                <div class="tac">
                    <input type="hidden" value="buy/bj_serial" name="" id="txturl">
                    <p><small><input v-model="isSelectAgree" value="agree" type="checkbox" /><span class="agree-txt fn">我同意华车平台<a href="#" class="juhuang">《服务协议》</a>. 并接受上述订单约定条款。</span></small></p>
                    <a type="bottom" @click="pay" class="btn btn-s-md btn-danger btn-zhifu">我要支付诚意金</a>
                    <p class="fs14 juhuang">@{{errorMsg}}</p>
                </div>
                
                
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

@section('login')
    @include('HomeV2._layout.login')
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/item/item.adv","module/common/head.vue.adv", "module/common/common", "bt"],function(a,b,c,d){
            @if($xianpai_citys)
            @foreach($xianpai_citys as $xianpai_city)
            b.init('{{mb_substr($xianpai_city,0,mb_strlen($xianpai_city)-1)}}')
            @endforeach
            @endif
            b.initShangPai({{$shangpai}})
        })
    </script>  
@endsection 


