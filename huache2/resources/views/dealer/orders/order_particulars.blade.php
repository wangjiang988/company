@extends('_layout.orders.base_order')
@section('title', '订单总详情')
@section('content')
   <div class="container content m-t-86 psr">
        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def box-border">

                    <h2 class="title">
                        <a href="{{route('dealer.order.detail',['order_id'=>$order->id])}}"><span class="juhuang fs18">订单总详情</span></a>
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
                              <span class="inline-block ml100"><b>查看时间：</b>{{date('Y-m-d H:i:s',time())}}</span>
                           </p>

                        </td>
                        <td class="tac">
                          <div class="status-wrapper">
                          @if(!is_null($order->orderStatus))
                          <?php $remarks = explode("-", $order->orderStatus->seller_remark);?>
                          @if(count($remarks) > 1)
                           <p class="status-icon"><b style="margin-left:68px">{{$order->orderStatus->seller_progress}}-{{$remarks[0]}}</b></p>
                            <p class="fs12">{{$remarks[1]}}</p>
                          @else
                           <p class="status-icon"><b style="margin-left:68px">{{$order->orderStatus->seller_progress}}</b></p>
                            <p class="fs12">{{$remarks[0]}}</p>
                          @endif
                             @if(in_array($order->order_state,[2011,2012,2031,2032]))
                            <p>
                            @if($order->orderAttr->show_status == 3)
                            交车时限：
                            @else
                            反馈订单时限：
                            @endif
                            <span class="juhuang">{{$order->rockon_time}}</span></p>
                            @elseif(in_array($order->order_state,[301,303,302]))
                              <p>交车邀请发出时限：<span class="juhuang">
                            @if($order->is_xianche)
                            {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-8*24*3600)}}24：00：00
                            @else
                            {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-7*24*3600)}}24：00：00
                            @endif
                              </span></p>
                             @endif
                          @endif
                          </div>
                        </td>
                      </tr>
                    </table>

                    <div class="box-border fs14">
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
                              <p><b>基本配置： </b><a href="/img/{{base64_encode(env('UPLOAD_URL').'/'.$arr_baojia['detail_img'])}}"><span class="blue">查看</span></a></p>
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
                       <?php $arr = [1,2,3,4,5,6];?>
                       @if(in_array(Request::get('tab'), $arr))
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=1'}}"><li @if(Request::get('tab') == 1) class="cur-step" @endif>订单前序</li></a>

                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=2'}}"><li @if(Request::get('tab') == 2) class="cur-step" @endif>交易约定</li></a>

                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=3'}}"><li @if(Request::get('tab') == 3) class="cur-step" @endif>交易提示</li></a>

                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=4'}}"><li @if(Request::get('tab') == 4) class="cur-step" @endif>担保结算</li></a>

                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=5'}}"><li @if(Request::get('tab') == 5) class="cur-step" @endif>进程记录</li></a>

                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=6'}}"><li @if(Request::get('tab') == 6) class="cur-step" @endif>特殊进程</li></a>
                       @else
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=1'}}"><li class="cur-step">订单前序</li></a>
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=2'}}"><li>交易约定</li></a>
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=3'}}"><li>交易提示</li></a>
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=4'}}"><li>担保结算</li></a>
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=5'}}"><li>进程记录</li></a>
                       <a href="{{route('dealer.order.detail',['id'=>$order->id]).'?tab=6'}}"><li>特殊进程</li></a>
                       @endif
                       <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>
                    <div class="box-border fs14 detail-wrapper">
                    @if(Request::get('tab') == 2)
                       <table class="tbl tbl-gray tac wp97">
                            <tr>
                                <td class="prev-title">车辆开票价</td>
                                <td>￥{{number_format($order->orderPrice->car_price,2)}}</td>
                            </tr>
                            @if($order->order_state >=301 && $order->order_status >= 3)
                            <tr>
                                <td class="prev-title">华车服务费</td>
                                <td>￥{{$order->orderPrice->agent_service_price}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="prev-title">付款方式</td>
                                <td>全款</td>
                            </tr>
                            <tr>
                                <td class="prev-title">经销商</td>
                                <td>{{$order->orderDealer->d_name}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">营业地点</td>
                                <td>{{$order->orderDealer->d_yy_place}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">交车地点</td>
                                <td>{{$order->orderDealer->d_jc_place}}</td>
                            </tr>
                            @if($order->order_state >= 402 && $order->order_state != 403 && $order->order_status >= 4)
                            <tr>
                            <?php $info_day = [1 => '全天',2 => '上午',3=>'下午'];?>
                                <td class="prev-title">约定交车时间</td>
                                <td>{{date('Y年m月d日',strtotime($order->orderinfo->car_jiaoche_at)) . $info_day[$order->orderinfo->car_jiaoche_day]}}</td>
                            </tr>
                            @endif
                       </table>
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
                         @foreach($rpos['rpos'] as $xzj)
                         @if($xzj['num'] != 0)
                         <tr>
                           <td>{{$xzj['xzj_title']}}</td>
                           <td>{{$xzj['xzj_model']}}</td>
                           <td>￥{{$xzj['xzj_guide_price']}}</td>
                           <td>{{$xzj['num']}}</td>
                           <td>￥{{$xzj['xzj_guide_price'] * $xzj['num']}}</td>
                         </tr>
                         @endif
                         @endforeach
                       </table>
                       <p class="tar mt10">合计：<b>￥</b></p>
                       @endif


                       @if($rpos['zp'])
                       <p>免费礼品或服务</p>
                       <table class="tbl tbl-blue tac">
                         <tr>
                           <th>名称</th>
                           <th>数量</th>
                           <th>状态</th>
                         </tr>
                         @foreach($rpos['zp'] as $zengpin)
                         @if($zengpin['num'] != 0)
                         <tr>
                           <td>{{$zengpin['zp_title']}}</td>
                           <td>{{$zengpin['num']}}</td>
                            @if(isset($zengpin['is_install']))
                           <td>{{($zengpin['is_install']) ? '已安装' : '/'}}</td>
                           @else
                           <td>{{($zengpin['zp_status']) ? '已安装' : '/'}}</td>
                           @endif
                         </tr>
                         @endif
                         @endforeach
                       </table>
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

                    @elseif(Request::get('tab') == 3)
                         <table class="tbl tbl-gray tac wp97">
                         @if($order->order_status >= 3 && $order->order_state != 300)
                           @if($order->isVerify())
                            <tr>
                                <td class="prev-title">客户姓名</td>
                                @if($order->order_state >= 301 && $order->order_status >= 3)
                                <td>{{$order->orderuserextion->last_name.'***'}}
                                </td>
                                @endif
                            </tr>
                            @endif
                            @if($order->order_state >= 301 && $order->order_status >= 3)
                            <tr>
                                <td class="prev-title">客户称呼</td>
                                <td>{{$order->orderuserextion->call}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="prev-title">客户手机号</td>
                                @if($order->order_state == 301 && $order->orderAttr->new_file_days || (($order->order_state >= 401 && $order->order_state != 403) || $order->order_status >= 4) || $order->orderXzjEdit->where('is_install',0)->count())
                                <td>{{$order->orderUsers->phone}}</td>
                                @else
                                <td>{{changeMobile($order->orderUsers->phone)}}</td>
                                @endif
                            </tr>
                            @endif
                            @if(($order->order_state >= 401 && $order->order_state != 403) && $order->order_status >= 4)
                            <tr>
                                <td class="prev-title">提车人姓名</td>
                                <td>{{$order->orderAppoint->extract_name}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">提车人电话</td>
                                <td>{{$order->orderAppoint->extract_phone}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">计划上牌车主名称</td>
                                <td>{{$order->orderAppoint->owner_name}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title w300">上牌车主名称与提车人姓名是否一致</td>
                                <td>
                                  @if($order->orderAppoint->car_purpose == 2 || $order->orderAppoint->owner_name != $order->orderAppoint->extract_name)
                                     否
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
                            @if($order->order_state >= 400 && !is_null($order->orderAppoint) && $order->order_status >= 4)
                            <tr>
                                <td class="prev-title">车主车辆用途</td>
                                <td>@if($order->orderAppoint->car_purpose == 0)非营业个人客车(私家车) @elseif($order->orderAppoint->car_purpose == 1)非营业企业客车@elseif($order->orderAppoint->car_purpose == 2)无@endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">车主身份类别</td>
                                <td>
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
                            @if($order->order_state >= 300 && $order->order_status >= 3)
                            <tr>
                                <td class="prev-title">买车担保金</td>
                                <td>{{number_format($order->sponsion_price,2)}}</td>
                            </tr>
                            @endif

                       </table>
                       <div class="m-t-10"></div>

               @if($order->order_state >=404 && $order->order_status >= 4)
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
                            <tr>
                                <td class="prev-title">经销商类别</td>
                                <td>授权经销商</td>
                            </tr>
                        @if($order->order_state >= 404 && $order->order_status >= 4)
                          @if(!is_null($order->orderAppoint) && $order->orderAppoint->appoinWaiter)
                            <tr>
                                <td class="prev-title">服务专员姓名</td>
                                <td>
           {{$order->orderAppoint->appoinWaiter->name or ''}}</td>
                            </tr>
                            <tr>
                                <td class="prev-title">服务专员手机号</td>
                                <td>{{$order->orderAppoint->appoinWaiter->mobile or ''}}</td>
                            </tr>

                                @if($order->orderAppoint->appoinWaiter->tel)
                              <tr>
                                <td class="prev-title">服务专员备用电话</td>
                                <td>{{$order->orderAppoint->appoinWaiter->tel}}</td>
                              </tr>
                                @endif
                              @endif
                            @endif
                            <tr>
                                <td class="prev-title">单车付款刷信用卡收费标准</td>
                                @if($arr_baojia['xyk_status'])
                                <td>
                                    免费次数：{{$arr_baojia['xyk_number']}} 次，超出次数收费{{$arr_baojia['xyk_yuan_num']}}元：刷卡金额的{{$arr_baojia['xyk_per_num']}}%（百分之）
                                </td>
                                @else
                                <td>
                                    单车付款刷信用卡免费次数：不限</p>
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td class="prev-title">单车付款刷借记卡收费标准</td>
                                @if($arr_baojia['jjk_status'])
                                <td>免费次数：{{$arr_baojia['jjk_number']}}次，超出次数收费：刷卡金额的{{$arr_baojia['jjk_per_num']}}%（百分之），  每次{{$arr_baojia['jjk_yuan_num']}}元（封顶）</td>
                                @else
                                <td>
                                    单车付款刷借计卡免费次数：不限
                                </td>
                                @endif
                            </tr>
                            @if($order->order_status >= 3 && $order->order_state != 300)
                            <tr>
                                <td class="prev-title w300">交车邀请发出时限</td>
                                <td>
                                 @if($order->is_xianche)
                                {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-8*24*3600)}}24：00：00
                                @else
                                {{date('Y年m月d日',strtotime($order->orderinfo->car_astrict)-7*24*3600)}}24：00：00
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="prev-title">交车时限</td>
                                <td>{{date('Y年m月d日',strtotime($order->orderinfo->car_astrict))}}24：00：00</td></td>
                            </tr>
                            @endif
                             @if($order->order_state >= 400 && !is_null($order->orderAppoint) && $order->order_status >= 4)
                            <tr>
                                <td class="prev-title">车主车辆用途</td>
                                <td>@if($order->orderAppoint->car_purpose == 0)非营业个人客车(私家车) @elseif($order->orderAppoint->car_purpose == 1)非营业企业客车@elseif($order->orderAppoint->car_purpose == 2)无@endif
                                </td>
                            </tr>
                             @endif
                            <tr>
                                <td class="prev-title">向客户当场移交的文件资料</td>
                                <td class="tal">
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
                                <td class="tal">
                                @foreach($tools_files['tools'] as $tool)
                                {{$tool['title']}}
                                @if(!$loop->last)
                                、
                                @endif
                                @endforeach
                                </td>
                            </tr>

                       </table>
                    @elseif(Request::get('tab') == 4)
                                           <table class="tbl tbl-blue tac wp97">
                         <tr>
                           <th>冻结/解冻</th>
                           <th>项目</th>
                           <th>说明</th>
                           <th>冻结增减金额</th>
                         </tr>
                         <tr>
                           <td>冻结</td>
                           <td>歉意金</td>
                           <td>2017-04-16 11:22:55</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥499.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>冻结</td>
                           <td>客户买车担保金利息</td>
                           <td>2017-04-16～2017-04-17，2天</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥8.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                          <td>解冻</td>
                           <td>歉意金赔偿</td>
                           <td>2017-04-17 09:17:22</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">￥499.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                          <td>解冻</td>
                           <td>客户买车担保金利息赔偿</td>
                           <td>2017-04-17 09:17:22</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr"> ￥8.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                          <td>冻结</td>
                           <td>歉意金2</td>
                           <td>2017-04-17 15:30:51</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr">￥499.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                          <td>冻结</td>
                           <td>客户买车担保金利息2</td>
                           <td>2017-04-17～2017-04-27，11天</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr">￥44.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                       </table>
                       <p class="tar"><b>冻结余额： ￥543.00</b>&nbsp;&nbsp;&nbsp;&nbsp;</p>
                       <div class="m-t-10"></div>

                       <p><span class="tip-title">汇总</span></p>
                       <table class="tbl tbl-blue tac ml15 wp50 ">
                         <tr>
                           <th colspan="2" class="tal">预计结算金额：￥1,300.00</th>
                         </tr>
                         <tr>
                           <td>项目</td>
                           <td>金额</td>
                         </tr>
                         <tr>
                           <td>售方服务费实得</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr"> ￥8.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                         <tr>
                           <td>返还售方已得</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr"> ￥8.00</span>
                              <div class="clear"></div>
                           </td>
                         </tr>
                       </table>
                       <div class="m-t-10"></div>
                       <table class="tbl tbl-blue tac wp97">
                         <tr>
                           <th colspan="4">
                             <span class="fl">本次结算金额：￥20,000.00</span>
                             <span class="fr">总收益：-￥2,000.98 &nbsp;&nbsp;&nbsp;&nbsp;</span>
                           </th>

                         </tr>
                         <tr>
                           <td>项目</td>
                           <td><b>收支金额</b></td>
                           <td>说明</td>
                           <td><b>时间</b></td>
                         </tr>
                         <tr>
                           <td>歉意金赔偿</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">  ￥1,500.00 </span>
                              <div class="clear"></div>
                           </td>
                           <td>结算</td>
                           <td>2017-06-06 16:31:26</td>
                         </tr>
                         <tr>
                           <td>客户买车担保金利息赔偿</td>
                           <td>
                              <span class="fl">-</span>
                              <span class="fr">  ￥1,500.00</span>
                              <div class="clear"></div>
                           </td>
                           <td></td>
                           <td>2017-06-06 19:01:47</td>
                         </tr>
                         <tr>
                           <td>获得赔偿返还</td>
                           <td>
                              <span class="fl">+</span>
                              <span class="fr">  ￥23,000.00</span>
                              <div class="clear"></div>
                           </td>
                           <td></td>
                           <td>2017-06-06 16:39:48</td>
                         </tr>

                       </table>
                    @elseif(Request::get('tab') == 5)
                      <table class="tbl tbl-gray tac wp50 ml15">
                        @foreach($order->orderLog as $log)
                          <tr>
                              <td class="prev-title">{{$log->msg}}</td>
                              <td class="fs14">{{$log->created_at}}</td>
                          </tr>
                      @endforeach
                       </table>
                       <div class="m-t-10"></div>
                    @elseif(Request::get('tab') == 6)
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

                       <p><span class="tip-title">交易终止(待后续)</span></p>
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

                       <p><span class="tip-title">协商处理(待后续)</span></p>
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

                    @else
                    <table>
                        <tr>
                          <td width="575">
                            <p><b>经销商所属地区：</b>{{$order->orderDealer->d_areainfo}}</p>
                          </td>
                          <td>
                            <p><b>报价编号：</b>{{$order->orderBaojia->bj_serial}}</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p><b>客户买车定金：</b>{{$order->orderPrice->client_hand_price}}</p>
                          </td>
                          <td>
                            <p><b>售方服务费：</b>{{$order->orderPrice->agent_service_price}}</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p><b>报价上牌条件：客户自由办理</b>

                            </p>
                          </td>
                          <td>
                            <p><b>代办上牌服务费：</b>{{$order->orderPrice->agent_numberplate_price}}</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p><b>报价临牌条件：</b>客户自由办理</p>
                          </td>
                          <td>
                            <p><b>代办临牌（每次）服务费：</b>{{$order->orderPrice->agent_temp_numberplate_price}}</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <p><b>计划上牌地区：</b>{{getProvinceCityNames($order->shangpai_area)}}</p>
                          </td>
                          <td>
                            <p><b>商业车险投保约定：</b>客户自由投保</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                          @if($order->is_xianche)
                            <p><b>行驶里程：</b>不高于 {{$order->orderinfo->mileage}}</p>
                          @else
                          <p><b>行驶里程：</b>不高于20公里</p>
                          @endif
                          </td>
                          <td>
                            @if($order->is_xianche)
                            <p><b>出厂年月：</b>{{$order->orderinfo->year_month}}</p>
                            @else
                            <p><b>出厂年月：</b>不早于{{$order->created_at}}</p>
                            @endif
                          </td>
                        </tr>
                      @if($order->is_xianche || $order->orderBaojia->bj_dealer_internal_id)
                        <tr>
                        @if(! $order->is_xianche)
                          <td>
                            <p><b>交车周期：</b>{{$order->orderBaojia->cycle}}</p>
                          </td>
                        @endif
                        @if($order->orderBaojia->bj_dealer_internal_id)
                          <td>
                            <p><b>内部车辆编号：</b>{{$order->orderBaojia->bj_dealer_internal_id}}</p>
                          </td>
                          @endif
                        </tr>
                      @endif
                        <tr>
                          <td colspan="2">
                            <p><b>原厂选装精品折扣率：</b>{{$order->orderBaojia->bj_xzj_zhekou}}%</p>
                          </td>
                        </tr>
                      </table>
                    @endif
                    </div>
                    <div class="m-t-10"></div>
            </div>

        </div>

    </div>
    </div>

@endsection
@section('js')
    <style>
    .detail-wrapper .tip-title {
    width: 115px;}
    </style>
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-order-base", "module/common/common"],function(v,u,c){

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
    <script>
    </script>
@endsection

