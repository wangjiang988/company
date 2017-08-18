@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '客户订单总详情 - 华车网';?>
  <link href="{{asset('webhtml/user/themes/user.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt content">
        <div class="wapper has-min-step has-top-border">
            <div class="box-border p10">

                    <h2 class="title">
                        <a href="{{route('cart.order_detail',['id'=>$order->id])}}"><span class="juhuang fs18">订单总详情</span></a>
                    </h2>

                    <table class="fs14">
                      <tr>
                        <td width="635">

                           <p class="ml20">
                              <span class="inline-block w200 "><b>订单号：</b>{{$order->order_sn}}</span>
                              <span class="inline-block ml100"><b>订单时间：</b>{{$order->getCreateTime('created_at')}}</span>
                           </p>
                           <p class="ml20">
                              <span class="inline-block w200 "><b>订单类别：</b>
			                      @if($order->is_xianche)现车@else非现车@endif
                              </span>
			                        </span>
                              <span class="inline-block ml100"><b>查看时间：</b>{{date('Y-m-d H:i:s',time())}}</span>
                           </p>

                        </td>
                        <td class="tac">
                          <div class="status-wrapper">
                           @if(!is_null($order->orderStatus))
                          <?php $remarks = explode("-", $order->orderStatus->member_remark);?>
                          @if(count($remarks) > 1)
                           <p class="status-icon"><b style="margin-left:68px">{{$order->orderStatus->user_progress}}-{{$remarks[0]}}</b></p>
                            <p class="fs12">{{$remarks[1]}}</p>
                          @else
                           <p class="status-icon"><b style="margin-left:68px">{{$order->orderStatus->user_progress}}</b></p>
                            <p class="fs12">{{$remarks[0]}}</p>
                          @endif
                           <?php $list = [2011,2021,2022,2012,2031,2032];?>
                             @if(in_array($order->order_state, $list))
                             <p>
                              @if($order->orderAttr->show_status == 3)
                             确认售方反馈特别文件时限
                              @else
                             确认售方修改内容时限
                              @endif
                             ：<span class="juhuang">{{$order->rockon_time}}</span></p>
                             @endif
                          @endif
                          </div>
                        </td>
                      </tr>
                    </table>

                    <div class="box-border fs14 p10">
                      <p><b class="juhuang">商品信息</b></p>
                      <p><b>品牌车系车型规格：</b>{{$order->gc_name}}</p>
                      <table>
                        <tr>
                          <td width="575">
                              <p><b>整车型号： </b>{{$order->orderGoodsClass->vehicle_model}}</p>
                          </td>
                          <td>
                              <p><b>厂商指导价： </b>￥{{number_format(unserialize(($order->orderInfoprice['value'])),2)}}</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <p><b>基本配置： </b><a href="/img/{{base64_encode(env('UPLOAD_URL').'/'.$arr_baojia['detail_img'])}}"><span class="blue">查看</span></p>
                          </td>
                          <td>
                              <p><b>生产国别：</b>
			      @if($order->orderinfo->car_guobie)
                              国产
                              @else
                              进口
                              @endif
                              </p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <p><b>座位数： </b>{{$order->orderinfo->car_seating}}座</p>
                          </td>
                          <td>
                              <p><b>车辆类别：</b>全新中规车整车</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <p><b>排放标准： </b>{{$order->orderinfo->car_paifang}}</p>
                          </td>
                          <td>
                              <p><b>数量：</b>1台</p>
                          </td>
                        </tr>
                      </table>
                      <p><b>车身颜色：</b>{{$order->orderinfo->body_color}}</p>
                      <p><b>内饰颜色：</b>{{$order->orderinfo->interior_color}}</p>
                    </div>
                    <div class="m-t-10"></div>

                    <ul class="detail-step-wrapper fs14">

                     <?php $arr = [1,2,3,4,5,6,7];?>
                       @if(in_array(Request::get('tab'), $arr))
                       <li @if(Request::get('tab') == 1) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=1'}}">订单前序</a></li>

                       <li @if(Request::get('tab') == 2) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=2'}}">交易约定</a></li>

                       <li @if(Request::get('tab') == 3) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=3'}}">交易提示</a></li>

                       <li @if(Request::get('tab') == 4) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=4'}}">订单后记</a></li>

                       <li @if(Request::get('tab') == 5) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=5'}}">担保结算</a></li>

                       <li @if(Request::get('tab') == 6) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=6'}}">进程记录</a></li>

                       <li @if(Request::get('tab') == 7) class="cur-step" @endif><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=7'}}">特殊进程</a></li>
                       @else
                       <li class="cur-step"><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=1'}}">订单前序</a></li>
                       <li><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=2'}}">交易约定</a></li>
                       <li><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=3'}}">交易提示</a></li>
                       <li><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=4'}}">订单后记</a></li>
                       <li><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=5'}}">担保结算</a></li>
                       <li><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=6'}}">进程记录</a></li>
                       <li><a href="{{route('cart.order_detail',['id'=>$order->id]).'?tab=7'}}">特殊进程</a></li>
                       @endif
                       <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>

                    @if(Request::get('tab') == 2)
                    <div class="box-border fs14 detail-wrapper">

                       <table class="tbl tbl-gray tac mauto wp97 ">
                       @if($order->order_state >= 301 && $order->order_status >= 3)
                            <tr>
                                <td class="prev-title w200">车辆开票价</td>
                                <td class="fs14">￥{{number_format($order->orderPrice->car_price,2)}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">华车服务费</td>
                                <td class="fs14">￥{{number_format($order->orderPrice->agent_service_price,2)}}</td>
                            </tr>
                        @endif
                            <tr>
                                <td class="prev-title">付款方式</td>
                                <td class="fs14">全款</td>
                            </tr>
                        @if($order->order_state >= 301 && $order->order_status >= 3)
                            <tr>
                                <td class="prev-title">经销商</td>
                                <td class="fs14">{{$order->orderDealer->d_name}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">营业地点</td>
                                <td class="fs14">{{$order->orderDealer->d_yy_place}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">交车地点</td>
                                <td class="fs14">{{$order->orderDealer->d_jc_place}}</td>
                            </tr>
                        @endif
                            @if($order->order_state >=402 && $order->order_status >= 4)
                            <tr>
                            <?php $info_day = [1 => '全天',2 => '上午',3=>'下午'];?>
                                <td class="prev-title">约定交车时间</td>
                                <td class="fs14">{{date('Y年m月d日',strtotime($order->orderinfo->car_jiaoche_at)) . $info_day[$order->orderinfo->car_jiaoche_day]}}</td>
                            </tr>
                            @endif
                       </table>
                       <div class="m-t-10"></div>

                      @if($rpos['rpos'])
                       <p>已装原厂选装精品：</p>
                       <table class="tbl tbl-blue tac tbl-xzj">
                         <tr>
                           <th>名称</th>
                           <th>型号/说明</th>
                           <th>厂商指导价</th>
                           <th>数量</th>
                           <th>附加价值</th>
                         </tr>
                         @foreach($rpos['rpos'] as $rpo)
                         @if($rpo['num'] != 0)
                         <tr>
                           <td>{{$rpo['xzj_title']}}</td>
                           <td>{{$rpo['xzj_model']}}</td>
                           <td>￥{{$rpo['xzj_guide_price']}}</td>
                           <td>{{$rpo['num']}}</td>
                           <td>￥{{$rpo['xzj_guide_price'] * $rpo['num']}}</td>
                         </tr>
                         @endif
                         @endforeach
                       </table>
                       <p class="tar mt10">（不另收费）合计价值：<b></b></p>
                       @endif

                  @if($rpos['zp'])
                       <p>免费礼品或服务</p>
                       <table class="tbl tbl-blue tac wauto">
                         <tr>
                           <th width="245">名称</th>
                           <th width="150">数量</th>
                           <th width="200">状态</th>
                         </tr>
                         @foreach($rpos['zp'] as $zp)
                         @if($zp['num'] != 0)
                         <tr>
                           <td>{{$zp['zp_title']}}</td>
                           <td>{{$zp['num']}}</td>
                           @if(isset($zp['is_install']))
                           <td>{{($zp['is_install']) ? '已安装' : '/'}}</td>
                           @else
                           <td>{{($zp['zp_status']) ? '已安装' : '/'}}</td>
                           @endif
                          @endif
                         </tr>
                        @endforeach
                       </table>
                       <div class="m-t-10"></div>
                  @endif

                       @if($order->orderOtherPrice->count())
                      <p>其他收费：</p>
                       <table class="tbl tbl-blue tac wp50 nomargin tbl-xzj">
                         <tr>
                           <th>费用名称</th>
                           <th>金额</th>
                         </tr>
                         @foreach($order->orderOtherPrice as $other)
                         <tr>
                           <td>{{$other->other_name}}</td>
                           <td>￥{{$other->other_price}}</td>
                         </tr>
                         @endforeach

                       </table>
                       <p class="ml326 mt10">合计：<b>￥</b></p>
                       @endif


                    @if($order->orderXzj->where('xzj_type',1)->count())
                       <p>客户已订购原厂选装精品：</p>
                       <table class="tbl tbl-blue tac tbl-xzj">
                         <tr>
                           <th>名称</th>
                           <th>型号/说明</th>
                           <th>厂商指导价</th>
                           <th>安装费</th>
                           <th>含安装费<br>折后总单价</th>
                           <th>已订<br>件数</th>
                           <th>订购时间</th>
                           <th>金额</th>
                         </tr>
                         @foreach($order->orderXzj->where('xzj_type',1)->sortByDesc('created_at') as $xzj)
                         <tr>
                           <td>{{$xzj->xzj_title}}</td>
                           <td>{{$xzj->xzj_model}}</td>
                           <td>￥{{$xzj->xzj_guide_price}}</td>
                           <td>￥{{$xzj->xzj_fee}}</td>
                           <td>￥{{number_format($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee,2)}}</td>
                           <td>{{$xzj->xzj_num}}</td>
                           <td>{{$xzj->created_at}}</td>
                           <td>￥{{number_format(($xzj->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $xzj->xzj_fee)*$xzj->xzj_num,2)}}</td>
                         </tr>
                         @endforeach
                       </table>
                       <p class="tar mt10">合计：<b>￥</b></p>
                     @endif

                       @if($order->orderXzj->where('xzj_type',0)->count())
                       <p>客户已订购非原厂选装精品：</p>
                       <table class="tbl tbl-blue tac tbl-xzj">
                         <tr>
                           <th>品牌</th>
                           <th>名称</th>
                           <th>型号/说明</th>
                           <th>含安装费<br>折后总单价</th>
                           <th>已订<br>件数</th>
                           <th>订购时间</th>
                           <th>金额</th>
                         </tr>
                         @foreach($order->orderXzj->where('xzj_type',0)->sortByDesc('created_at') as $xzj)
                         <tr>
                           <td>{{$xzj->xzj_brand}}</td>
                           <td>{{$xzj->xzj_title}}</td>
                           <td>{{$xzj->xzj_model}}</td>
                           <td>￥{{number_format($xzj->xzj_guide_price + $xzj->xzj_fee,2)}}</td>
                           <td>{{$xzj->xzj_num}}</td>
                           <td>{{$xzj->created_at}}</td>
                           <td>￥{{number_format(($xzj->xzj_guide_price + $xzj->xzj_fee) * $xzj->xzj_num,2)}}</td>
                         </tr>
                         @endforeach
                       </table>
                       <p class="tar mt10">合计：<b>￥</b></p>
                       @endif
                    </div>
                    @elseif(Request::get('tab') == 3)
                    <div class="box-border fs14 detail-wrapper">
                       <p><span class="tip-title">客户有关</span></p>
                       <table class="tbl tbl-gray tac wp97">
                       @if(($order->order_state >= 401 && $order->order_state != 403) && $order->order_status >= 4)
                            <tr>
                                <td class="prev-title w300">提车人姓名</td>
                                <td class="fs14">{{$order->orderAppoint->extract_name}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">提车人电话</td>
                                <td class="fs14">{{$order->orderAppoint->extract_phone}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">计划上牌车主名称</td>
                                <td class="fs14">{{$order->orderAppoint->owner_name}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">上牌车主名称与提车人姓名是否一致</td>
                                <td class="fs14">@if($order->orderAppoint->car_purpose == 2 || ($order->orderAppoint->owner_name != $order->orderAppoint->extract_name)) 否
                                    @else
                                    是
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @if($order->area->area_xianpai)
                            <tr>
                                <td class="prev-title">上牌车主取得牌照指标的方式</td>
                                @if($order->orderAttr->license_tag)
                                <td>已取得牌照指标</td>
                                @else
                                <td>在订车或提车后自行办理牌照指标</td>
                                @endif
                            </tr>
                            @endif
                            @if(($order->order_state != 403 && $order->order_state >= 402) && $order->order_status>=4)
                            <tr>
                                <td class="prev-title">车主车辆用途</td>
                                <td class="fs14">@if($order->orderAppoint->car_purpose == 0)非营业个人客车(私家车) @elseif($order->orderAppoint->car_purpose == 1)非营业企业客车@elseif($order->orderAppoint->car_purpose == 2)无@endif</td>
                            </tr>
                        <tr>
                        <td class="prev-title">车主身份类别</td>
                        <td class="fs14">
                          @if($order->orderAppoint->car_purpose == 1)
                             <?php $arr = ['上牌地本地注册企业（增值税一般纳税人）','上牌地本地注册企业（小规模纳税人）'];?>
                             {{$arr[$order->orderAppoint->identity_type]}}</span>
                             @elseif($order->orderAppoint->car_purpose == 2)
                             无
                             @else
                             <?php $type_id = getIdentity_id($order->orderAppoint->identity_type,true);?>
                              <?php $arr1 = [2,3,4,5,6,7,8,15]; $arr2=[10,11,12,13];?>
                              @if(array_search($order->orderAppoint->identity_type,$arr1))
                              国内其他限牌城市户籍居民({{$type_id}})
                              @elseif(array_search($order->orderAppoint->identity_type,$arr2))
                              非中国大陆人士({{$type_id}})
                              @else
                              {{$type_id}}
                              @endif
                             @endif
                  				</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="prev-title">客户前往提车的方式</td>
                                <td class="fs14">本人安排</td>
                            </tr>
                            <tr>
                                <td class="prev-title">客户车辆的回程方式</td>
                                <td class="fs14">本人安排</td>
                            </tr>
                       </table>
                       <div class="m-t-10"></div>
                      @if($order->order_state >= 404 && $order->order_status >= 4)
                       <p class="ml15">客户配合提供的文件资料：</p>
                       <table class="tbl tbl-blue tac wp97">
                         <tr>
                           <th>使用场合</th>
                           <th>文件资料</th>
                           <th>数量</th>
                           <th>文件格式</th>
                         </tr>
                         <tr>
                           <td rowspan="2">提车人身份验证</td>
                           <td>身份证原件</td>
                           <td rowspan="2">2</td>
                           <td>&nbsp;</td>
                         </tr>
                         <tr>
                           <td>身份证复印件</td>
                           <td><img width="15" src="./themes/images/upload-img.png" alt=""><span class="ml5">额额法儿安抚.jpg</span></td>
                         </tr>
                         <tr>
                           <td>投保车辆首年商业保险（含投保交强险，车船税）</td>
                           <td>身份证复印件</td>
                           <td>2</td>
                           <td>&nbsp;</td>
                         </tr>
                         <tr>
                           <td>代办上牌手续（购置税）</td>
                           <td>上牌指标正本</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                         </tr>
                         <tr>
                           <td>代办车辆临时牌照手续</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                         </tr>
                         <tr>
                           <td>刷卡付款（刷卡人非银行卡卡主本人）</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                         </tr>
                       </table>
                       <div class="m-t-10"></div>


                       <p class="ml15">文件下载：</p>
                       <table class="tbl tbl-blue tac ml15 wp50 ">
                         <tr>
                           <th>文件名</th>
                           <th>文件下载</th>
                         </tr>
                         <tr>
                           <td>交车确认书</td>
                           <td><a href="#" class="tdu blue">下载</a></td>
                         </tr>
                         <tr>
                           <td>XX文件</td>
                           <td><a href="#" class="tdu blue">下载</a></td>
                         </tr>
                       </table>
                       <div class="m-t-10"></div>
                     @endif
                       <p><span class="tip-title">售方有关</span></p>
                       <table class="tbl tbl-gray tac wp97">
                          @if($order->order_state >= 402 && $order->order_status >= 4)
                            <tr>
                                <td class="prev-title">售方姓名</td>
                                <td class="fs14">{{$order->orderMember->member_truename}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">售方手机号</td>
                                <td class="fs14">{{$order->orderMember->member_mobile}}</td>
                            </tr>
                            @endif
                            @if($order->order_state >= 404 && $order->order_status >= 4)
                            <tr>
                                <td class="prev-title">服务专员姓名</td>
                                <td class="fs14">{{$order->orderAppoint->appoinWaiter->name or ''}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">服务专员手机号</td>
                                <td class="fs14">{{$order->orderAppoint->appoinWaiter->mobile or ''}}</td>
                            </tr>
                             @if($order->orderAppoint->appoinWaiter->tel)
                            <tr>
                                <td class="prev-title">服务专员备用电话</td>
                                <td class="fs14">{{$order->orderAppoint->appoinWaiter->tel}}</td>
                            </tr>
                              @endif
                            @endif
                            <tr>
                                <td class="prev-title">单车付款刷信用卡收费标准</td>
                                 @if($arr_baojia['xyk_status'])
                                <td class="fs14">
                                    免费次数：{{$arr_baojia['xyk_number']}} 次，超出次数收费{{$arr_baojia['xyk_yuan_num']}}元：刷卡金额的{{$arr_baojia['xyk_per_num']}}%（百分之）
                                </td>
                                @else
                                <td class="fs14">
                                    单车付款刷信用卡免费次数：不限</p>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td class="prev-title">单车付款刷借记卡收费标准</td>
                                 @if($arr_baojia['jjk_status'])
                                <td class="fs14">免费次数：{{$arr_baojia['jjk_number']}}次，超出次数收费：刷卡金额的{{$arr_baojia['jjk_per_num']}}%（百分之），  每次{{$arr_baojia['jjk_yuan_num']}}元（封顶）</td>
                                @else
                                <td class="fs14">
                                    单车付款刷借计卡免费次数：不限
                                </td>
                                @endif
                            </tr>
                            @if($order->order_status >= 3 && $order->order_state != 300)
                            <tr>
                                <td class="prev-title w300">交车邀请发出时限</td>
                                <td class="fs14">
                                  @if($order->is_xianche)
                                  {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-8*24*3600)}}24：00：00
                                  @else
                                  {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-7*24*3600)}}24：00：00
                                  @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">交车时限</td>
                                <td class="fs14">{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict))}}24：00：00</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="prev-title">向客户当场移交的文件资料</td>
                                <td class="tali fs14">
                                  @foreach($tools_files['files'] as $file)
                                  {{$file['title']}}
                                  @if(!$loop->last)
                                  、
                                  @endif
                                  @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">向客户当场移交的随车工具</td>
                                <td class="tali fs14">
                                  @foreach($tools_files['tools'] as $tools)
                                  {{$tools['title']}}
                                  @if(!$loop->last)
                                  、
                                  @endif
                                  @endforeach
                                </td>
                            </tr>

                       </table>
                       <div class="m-t-10"></div>
                    </div>
                    @elseif(Request::get('tab') == 4)
                    <div class="box-border fs14 detail-wrapper">
                    @if(!is_null($order->orderComment))
                       <p><span class="tip-title">评价</span></p>
                       <div class="ml100">
                          <table class="fs14">
                              <tr>
                                  <td valign="middle">
                                      华车服务：
                                  </td>
                                  <td>
                                      <div class="form-item" id="hs">
                                        <div class="formItemDiff formItemDiffFirst psr"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td valign="middle">
                                      <div class="m-t-10"></div>
                                      售方服务：
                                  </td>
                                  <td>
                                      <div class="m-t-10"></div>
                                      <div class="form-item" id="cs">
                                        <div class="formItemDiff formItemDiffFirst psr"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td valign="middle">
                                      <div class="m-t-10"></div>
                                      评价内容：
                                  </td>
                                  <td>
                                      <div class="m-t-10"></div>
                                      <p class="nomargin">{{$order->orderComment->evaluate or ''}}</p>
                                  </td>
                              </tr>

                          </table>
                       </div>
                       @endif
                       <div class="m-t-10"></div>
                       <p><span class="tip-title">发票</span></p>
                       <p class="ml100">已开票 </p>
                       <div class="m-t-10"></div>
                    </div>
                    @elseif(Request::get('tab') == 5)
                    <div class="box-border fs14 detail-wrapper">
                      <p><span class="tip-title">加信宝</span></p>
                      <p>买车担保金<b>￥1,900.00 </b>支付提交与入账进度</p>
                       <table class="tbl tbl-blue tac wp48 tbl-gray-border  fl">
                         <tr>
                           <th colspan="3"><span class="fs16">提交</span></th>
                         </tr>
                         <tr>
                           <td>时间</td>
                           <td>支付方式</td>
                           <td>金额</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>代金券（诚意金）</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>可用余额（诚意金）</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>代金券（买车担保金余款）</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>银行转账</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td colspan="3" class="tar gray-bg"><p class="tar nomargin">已提交支付金额：￥1,200.00</p></td>
                         </tr>
                         <tr>
                           <td colspan="3" class="tal gray-bg"><p class="tal nomargin">待付金额：￥700.00</p></td>
                         </tr>
                       </table>

                       <table class="tbl tbl-blue tac wp48 tbl-gray-border fl ml30">
                         <tr>
                           <th colspan="3"><span class="fs16">入账</span></th>
                         </tr>
                         <tr>
                           <td>时间</td>
                           <td>支付方式</td>
                           <td>金额</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>代金券（诚意金）</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>可用余额（诚意金）</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>2017-06-05 11:15:36</td>
                           <td>代金券（买车担保金余款）</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td colspan="3" class="tar gray-bg"><p class="tar nomargin">已入账金额：￥800.00</p></td>
                         </tr>
                         <tr>
                           <td colspan="3" class="tal gray-bg"><p class="tal nomargin">未入账金额：￥1,100.00</p></td>
                         </tr>
                       </table>
                       <div class="clear"></div>
                       <div class="m-t-10"></div>

                       <p>买车担保金解冻确认</p>
                       <table class="tbl tbl-blue tac wp48 tbl-gray-border">
                         <tr>
                           <th>支付方式</th>
                           <th>金额</th>
                         </tr>
                         <tr>
                           <td>转付华车服务费</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>应退还可用余额</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                         <tr>
                           <td>失效代金券</td>
                           <td class="tar">￥200.00</td>
                         </tr>
                       </table>

                       <div class="m-t-10"></div>

                       <p>买车担保金冻结与解冻详情</p>
                       <table class="tbl tbl-blue tac tbl-gray-border">
                         <tr>
                           <th>发生时间</th>
                           <th>冻结/解冻</th>
                           <th>冻结来源</th>
                           <th>解冻去向</th>
                           <th>冻结增减金额</th>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>冻结</td>
                           <td>代金券（诚意金）</td>
                           <td>&nbsp;</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>冻结</td>
                           <td>可用余额（诚意金）</td>
                           <td>&nbsp;</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>冻结</td>
                           <td>代金券（买车担保金余款）</td>
                           <td>&nbsp;</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>冻结</td>
                           <td>线上支付（支付宝）</td>
                           <td>&nbsp;</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>冻结</td>
                           <td>银行转账</td>
                           <td>&nbsp;</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>解冻</td>
                           <td>&nbsp;</td>
                           <td>转付华车服务费</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>2016-02-02 09:30:11</td>
                           <td>解冻</td>
                           <td>&nbsp;</td>
                           <td>已退还可用余额</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr"> ￥200.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td colspan="5" class="gray-bg"><p class="nomargin tar">冻结余额：￥543.00</p></td>
                         </tr>

                       </table>
                       <div class="m-t-10"></div>

                       <p><span class="tip-title">收支汇总</span></p>
                       <table class="tbl tbl-blue tac wp97">
                         <!--本订单相关的收支汇总，项目名称见下方，如客户无实际收支产生显示如下-->
                         <tr>
                           <th colspan="4">
                             <p class="nomargin tal"> 暂无实际收支记录</p>
                           </th>
                         </tr>
                         <!--反之-->
                         <tr>
                           <th colspan="4">
                             <p class="nomargin tar">总收支：￥2,000.98</p>
                           </th>
                         </tr>
                         <tr>
                           <td>项目</td>
                           <td><b>收支金额</b></td>
                           <td>说明</td>
                           <td><b>时间</b></td>
                         </tr>
                         <tr>
                           <td>转付华车服务费</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">  ￥1,500.00 </span>
                              <div class="clear"></div>
                           </td>
                           <td>结算</td>
                           <td>2017-06-06 16:31:26</td>
                         </tr>
                         <tr>
                           <td>诚意金赔偿</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">  ￥1,500.00</span>
                              <div class="clear"></div>
                           </td>
                           <td></td>
                           <td>2017-06-06 19:01:47</td>
                         </tr>
                         <tr>
                           <td>买车担保金赔偿</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">  ￥1,500.00</span>
                              <div class="clear"></div>
                           </td>
                           <td></td>
                           <td>2017-06-06 19:01:47</td>
                         </tr>
                         <tr>
                           <td>获得客户买车担保金利息补偿</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">  ￥23,000.00</span>
                              <div class="clear"></div>
                           </td>
                           <td>售方修改订单</td>
                           <td>2017-06-06 16:39:48</td>
                         </tr>
                         <tr>
                           <td>获得歉意金补偿</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">￥23,000.00</span>
                              <div class="clear"></div>
                           </td>
                           <td>售方终止订单</td>
                           <td>2017-06-06 16:39:48</td>
                         </tr>

                       </table>
                       <div class="m-t-10"></div>
                    </div>
                    @elseif(Request::get('tab') == 6)
                    <div class="box-border fs14 detail-wrapper">
                      <div class="m-t-10"></div>
                      <table class="tbl tbl-gray tac wp50 mauto">
                      @foreach($order->orderLog as $log)
                          <tr>
                              <td class="prev-title">{{$log->msg}}</td>
                              <td class="fs14">{{$log->created_at}}</td>
                          </tr>
                      @endforeach
                     </table>
                     <div class="m-t-10"></div>
                    </div>
                    @elseif(Request::get('tab') == 7)
                    <div class="box-border fs14 detail-wrapper">
                  @if($order->orderAttr->show_status == 3 && $files)
                    <p><span class="tip-title">特殊文件</span></p>
                       <p><b>特需内容：  </b>{{$order->orderAttr->file_comment}}</p>
                       <table>
                       @foreach($files as $file)
                         <tr>
                           <td valign="top"><b>反馈内容：</b></td>
                           <td width="220">{{$file['title']}}</td>
                           @if($file['ok'] == 'Y')
                           <td width="260">
                           <b>办理费用：</b>人民币{{$file['fee']}}元
                           </td>
                           <td>
                           <b>延后时间：</b>{{$file['day']}}个自然日
                           </td>
                           @else
                           <td colspan="2">
                             恕无法办理
                           </td>
                           @endif
                         </tr>
                         @endforeach
                       </table>
                       <p class="mt10"><b>确认结果：  </b>
                       @if($order->orderAttr->new_file_comment)
                       办理
                       @else
                       不办
                       @endif
                       </p>
                  @endif
                      @if(!is_null($order->orderAppoint))
                       <p><span class="tip-title">预约交车</span></p>
                       <table class="tbl tbl-gray tac wp97">
                       <?php $day = ['','全天','上午','下午'];?>
                       @if(intval($order->orderAppoint->system_data))
                           <tr>
                                <td class="prev-title w300">售方邀请内容</td>
                                <td id="comment" style="text-align: left!important;"></td>
                            </tr>
                            <tr>
                                <td class="prev-title">售方反馈内容</td>
                                <td>希望 {{$order->orderAppoint->seller_data}} {{$day[$order->orderAppoint->seller_day]}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title w300">售方回复反馈</td>
                                <td>{{$order->orderAppoint->system_data}} {{$day[$order->orderAppoint->system_day]}}，{{intval($order->orderAppoint->system_out_price)?'超期费 ￥'.$order->orderAppoint->system_out_price : ''}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">客户确认结果</td>
                                <td>同意（{{$order->orderAppoint->system_data}} {{$day[$order->orderAppoint->system_day]}}，{{intval($order->orderAppoint->system_out_price)?'超期费 ￥'.$order->orderAppoint->system_out_price : ''}}）</td>
                            </tr>
                        @else
                           @if(intval($order->orderAppoint->seller_data))
                           <tr>
                                <td class="prev-title w300">售方邀请内容</td>
                                <td id="comment" style="text-align: left!important;"></td>
                            </tr>
                            <tr>
                                <td class="prev-title">客户反馈内容</td>
                                <td>希望 {{$order->orderAppoint->member_data}} {{$day[$order->orderAppoint->member_day]}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title w300">售方回复反馈</td>
                                <td>{{$order->orderAppoint->seller_data}} {{$day[$order->orderAppoint->seller_day]}}，{{intval($order->orderAppoint->out_price)?'超期费 ￥'.$order->orderAppoint->out_price : ''}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">客户确认结果</td>
                                <td>同意（{{$order->orderAppoint->seller_data}} {{$day[$order->orderAppoint->seller_day]}}，{{intval($order->orderAppoint->out_price)?'超期费 ￥'.$order->orderAppoint->out_price : ''}}）</td>
                            </tr>
                            @elseif($order->orderAppoint->is_feeback)
                            <tr>
                                <td class="prev-title">售方邀请内容</td>
                                <td id="comment" style="text-align: left!important;"></td>
                            </tr>
                            <tr>
                                <td class="prev-title">售方反馈内容</td>
                                <td>希望{{$order->orderAppoint->member_data}} {{$day[$order->orderAppoint->member_day]}}</td>
                            </tr>
                            @else
                            <tr>
                                <td class="prev-title w300">售方邀请内容</td>
                                <td id="comment" style="text-align: left!important;"></td>
                            </tr>
                            <tr>
                                <td class="prev-title">客户反馈内容</td>
                                <td>希望（{{$order->orderAppoint->default_data}} {{$day[$order->orderAppoint->default_day]}}）</td>
                            </tr>
                            <tr>
                                <td class="prev-title">客户反馈内容</td>
                                <td>同意（{{$order->orderAppoint->default_data}} {{$day[$order->orderAppoint->default_day]}}）</td>
                            </tr>
                            @endif
                        @endif
                      </table>
                       <div class="m-t-10"></div>
                 @endif
                  @if(count($arr_diff))
                      <table class="tbl tbl-blue tac ml15 wp97 ">
                         <tr>
                           <th>提出时间</th>
                           <th>项目</th>
                           <th>订单原有约定</th>
                           <th>经销商提议修改为</th>
                           <th>客户确认结果</th>
                           <th>客户确认时间</th>
                         </tr>
                         @foreach($arr_diff as $diff)
                         <tr>
                           <td>{{$diff['updated_at']}}</td>
                           <td>{{$diff['title']}}</td>
                           <td>{{$diff['old_num']}}</td>
                           <td>{{$diff['num']}}</td>
                           @if(! in_array($order->order_state,$order->lists))
                           <td>同意</td>
                           @else
                           <td>不同意</td>
                           @endif
                           <td>{{$diff['created_at']}}</td>
                         </tr>
                         @endforeach
                       </table>
                       <div class="m-t-10"></div>
                      @endif

                     @if($order->orderXzjEdit->count())
                       <p><span class="tip-title">精品协商</span></p>
                       <table class="tbl tbl-blue tac wp97">

                         <tr>
                           <th>客户发起时间</th>
                           <th>品牌</th>
                           <th>名称</th>
                           <th>型号/说明</th>
                           <th>厂商编号</th>
                           <th>含安装费<br>折后总价</th>
                           <th>希望件数<br>减少为</th>
                           <th>协商结果</th>
                           <th>客户确认时间</th>
                         </tr>
                         @foreach($order->orderXzjEdit as $xzjedit)
                         <tr>
                           <td>{{$xzjedit->created_at}}</td>
                           <td>
                           @if($xzjedit->orderXzjs[0]->xzj_type)
                           原厂
                           @else
                           {{$xzjedit->orderXzjs[0]->xzj_brand}}
                           @endif
                           </td>
                           <td>{{$xzjedit->orderXzjs[0]->xzj_title}}</td>
                           <td>{{$xzjedit->orderXzjs[0]->xzj_model}}</td>
                           <td>5166165166</td>
                           <td>￥{{number_format($xzjedit->orderXzjs[0]->xzj_guide_price + $xzjedit->orderXzjs[0]->xzj_fee,2)}}</td>
                           <td>{{$xzjedit->edit_num}}</td>
                           <td>
                           @if($xzjedit->is_install)
                           同意
                           @else
                           拒绝
                           @endif
                           </td>
                           <td>{{$xzjedit->updated_at}}</td>
                         </tr>
                         @endforeach
                       </table>
                       <div class="m-t-10"></div>
                      @endif

                       <p><span class="tip-title">协商处理(待后续)</span></p>
                       <table class="tbl tbl-blue tac ml15 wp97 ">
                         <tr>
                           <th>编号</th>
                           <th>协商终止方案发出时间</th>
                           <th>售方方案内容</th>
                           <th>客户确认时间</th>
                           <th>售方确认时间</th>
                           <th>达成结果</th>
                         </tr>
                         <tr>
                           <td>12456464648861684648</td>
                           <td>2017-06-06 <br>15:56:26</td>
                           <td></td>
                           <td></td>
                           <td>2017-06-06 <br>15:56:26</td>
                           <td>未达成</td>
                         </tr>
                         <tr>
                           <td>12456464648861684648</td>
                           <td>2017-06-06 <br>15:56:26</td>
                           <td></td>
                           <td>2017-06-06 <br>15:56:26</td>
                           <td>2017-06-06 <br>15:56:26</td>
                           <td>已达成</td>
                         </tr>
                       </table>
                       <div class="m-t-10"></div>

                       <p><span class="tip-title">交易终止(待后续)</span></p>
                       <p><b>结束原因：</b>售方终止订单</p>
                       <table>
                         <tr>
                           <td width="70" valign="top"><b>当前执行：</b></td>
                           <td>
                                <p class="nomargin">1.歉意金2赔偿￥499.00</p>
                                <p class="nomargin">2.客户买车担保金2赔偿￥499.00</p>
                                <p class="nomargin">3.客户买车其他损失赔偿￥800.00</p>
                           </td>
                         </tr>
                       </table>



                      <div class="m-t-10"></div>
                    </div>
                    @else
                    <div class="box-border p10 fs14">

                      <table>
                        <tr>
                          <td width="575">
                            <p><b>华车车价:</b>￥{{$order->hwache_price}}</p>
                          </td>
                          <td>
                            <p><b>买车担保金约定：</b>￥{{number_format($order->sponsion_price,2)}}</p>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <p><b>交车地点范围约定：</b>
                                <?php $result =$order->orderScope->toArray();
                                $data = [
                                        $result['province1_name'] . $result['area1_name'],
                                        $result['province2_name'] . $result['area2_name'],
                                        $result['province3_name'] . $result['area3_name']
                                    ];
                                ?>
                             {{implode(' 或 ',$data)}}
                            </p>
                          </td>

                        </tr>
                        @if($order->shangpai_status != 1)
                        <tr>
                          <td>
                            <p><b>上牌服务约定：</b>自由办理</p>
                          </td>
                          <td>
                            <p><b>代办上牌服务费参考金额：</b>￥{{$order->orderPrice->agent_numberplate_price}}</p>
                          </td>
                        </tr>
                        @endif
                        <tr>
                          <td>
                            <p><b>上临牌约定：</b>客户自由办理</p>
                          </td>
                          <td>
                            <p><b>代办临牌（每次）服务费参考金额:</b>￥{{$order->orderPrice->agent_temp_numberplate_price}}</p>
                          </td>
                        </tr>
                        @if($order->is_xianche)
                        <tr>
                          <td>
                            <p><b>行驶里程：</b>不高于{{$order->orderinfo->mileage}}</p>
                          </td>
                          <td>
                           <p><b>出厂年月：</b>{{$order->orderinfo->year_month}}</p>
                          </td>
                        </tr>
                        @else
                          <tr>
                          <td>
                            <p><b>行驶里程：</b>不高于20公里</p>
                          </td>
                          <td>
                            <p><b>出厂年月：</b>不早于{{$order->created_at}}</p>
                          </td>
                        </tr>
                        @endif
                        <tr>
                        @if(! $order->is_xianche)
                          <td>
                            <p><b>交车周期：</b>{{$order->orderinfo->cycle}}</p>
                          </td>
                         @endif
                          <td>
                      <p><b>计划上牌地区：</b>{!! json_decode(getRegionIdToName($order->shangpai_area))[0] !!}</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p><b>商业车险投保约定：</b>客户自由办理</p>
                          </td>
                          <td>
                            <p><b>原厂选装精品折扣率：</b>{{$order->orderBaojia->bj_xzj_zhekou}}%</p>
                          </td>
                        </tr>

                      </table>
                    </div>
                    @endif


                    <div class="m-t-10"></div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base"],function(v,u,c){
            @if(!is_null($order->orderComment))
            $("#hs .formItemDiff").slice(0, {{$order->orderComment->seller_service}}).addClass('sele')
            $("#cs .formItemDiff").slice(0, {{$order->orderComment->hwache_service}}).addClass('sele')
            @endif

          var _tal = $(".tbl-xzj");
          $.each(_tal,function(index, el) {
            var sum_price = 0
            $(el).find('tr').slice(1).each(function(index, item) {
              var price = $(item).find('td').last().text().replace('￥','').replace(/,/g,'')
              sum_price += parseFloat(price)
            });
            $(el).next().find('b').text(u.format(sum_price.toFixed(2)))

          });

          @if(!is_null($order->orderDate))

          var _json = {!! json_encode($order->orderDate->jiaoche_times) !!}
          var _strarray = []
          _json.forEach(function(item,index){
            var _str =
                item.year + "-" +
                item.month + "-" +
                item.day + " "+
                (
                  item.select == 1
                  ? "上午/下午" :
                  (item.select == 2 ? "上午" : "下午")
                )
            _strarray.push(_str)
          })
          $("#comment").html(_strarray.join(","))

          @endif
        })
    </script>
@endsection
