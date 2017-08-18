@extends('_layout.orders.base_order')
@section('title', '等待售方反馈 - 用户管理 - 华车网')
@section('content')

    <div class="container content m-t-86 pos-rlt " ms-controller="custom">
       <div class="cus-step">
           <div class="line stp-1"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i>2</i></li>
               <li class="third"><span>3</span><i>3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>
       </div>

        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class=" fs16 weight">订单信息</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td  width="50%"><p><b>订单号：</b>{{$order['order_sn']}}</p></td>
                                            <td  width="50%">
                                                <b>订单时间：</b>{{$order['created_at']}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>订单类别：</b>
											  @if($baojia['bj_is_xianche'])
                                            现车订单
                                            @else
                                            非现车订单
                                            @endif
                                            </p><hr class="dashed"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                              <p>
                                                 <b>车型：</b>
                                                 <span>{{$order['gc_name']}}</span>
                                              </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p><b>整车型号： </b>{{$baojia['vehicle_model']}}</p></td>
                                            <td><p><b>厂商指导价：</b>￥{{number_format($car_info['zhidaojia'],2)}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车辆类别：</b>全新中规车整车</p></td>
                                        </tr>
                                        <tr>
                                            <td><p><b>数量：</b>1台</p></td>
                                            <td>
                                            @if($baojia['bj_dealer_internal_id'])
                                            <p><b>内部车辆编号：</b>{{$baojia['bj_dealer_internal_id']}}</p>
                                            @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <?php $dealer = $order->orderDealer ?>
                                            <td colspan="2"><p><b>经销商：</b>{{$dealer['d_name']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>营业地点：</b>{{$dealer['d_yy_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>交车地点：</b>{{$dealer['d_jc_place']}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>归属地区：</b>{{$dealer['d_areainfo']}}</p></td>

                                        </tr>
                                        <tr>
                                            <td colspan="2"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>车辆开票价格：</b>{{number_format($baojia['car_price'],2)}}</p></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><p><b>客户买车定金：</b>{{number_format($baojia['client_hand_price'],2)}}</p></td>
                                        </tr>

                                         <tr>
                                            <td><p><b>我的服务费：</b>￥{{number_format($baojia['agent_service_price'],2)}}</p></td>
                                            <td><p><b>加信宝当前冻结：</b>￥499.00</p></td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <div class="times">
                                    @if($order->order_state == 2012)
                                    <p class="status tac  m-t-10"><b class="fs14">订单状态：等待售方反馈特别文件办理</b></p>
                                    @else
                                    <p class="status tac  m-t-10"><b class="fs14">订单状态：等待售方反馈</b></p>
                                    @endif
                                        <p class="tac fs14">反馈时限仅剩
                                            <span class="jishi countdown inline-block juhuang">
                                            @if($order->orderAttr->show_status === 3)
                                                <span>0</span>
                                                <span>0</span>
                                                <span class="fuhao">时</span>
                                            @endif
                                                <span>0</span>
                                                <span>0</span>
                                                <span class="fuhao">分</span>
                                                <span>0</span>
                                                <span>0</span>
                                            </span>
                                             <span class="fuhao">秒</span>
                                        </p>

                                        <p class="tac m-t-10"><a href="{{route('dealer.order.detail',['order_id'=>$order['id']])}}" class="juhuang tdu">查看订单总详情</a></p>

                                        <hr class="dashed mt69">
                                        <p class="pl20 pt"><b>客户承诺上牌地区： </b>
                                        <?php $shangpai = getAreaName($order['shangpai_area'])?>
                                        @if($shangpai['parent_name'] === $shangpai['name'])
                                        {{$shangpai['name']}} </p>
                                        @else
                                        {{$shangpai['parent_name'].$shangpai['name']}} </p>
                                        @endif
                                        </p>
                                    </div>
                                </td>

                            </tr>

                        </tbody>
                    </table>


                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th colspan="2" class="tal juhuang"><label class="weight fs16">商品说明</label></th>
                            </tr>
                            <tr>
                                <td width="660">
                                    <table class="tbl2" width="100%">
                                        <tr>
                                            <td width="33%"><p><b>基本配置：</b><a href="/img/{{base64_encode(env('UPLOAD_URL').'/'.$baojia['detail_img'])}}" class="blue tdu">查看</a></p></td>
                                            <td width="33%"><p><b>生产国别：</b>
                                             @if(!$car_info['guobie'])
                                            进口
                                            @else
                                            国产
                                            @endif
                                            </p></td>
                                            <td width="33%"><p><b>座位数：</b>5</p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>车身颜色：</b>{{$baojia['bj_body_color']}}</p></td>
                                            <td width="33%"><p><b>内饰颜色：</b>{{$car_info['nside_color']}}</p></td>
                                            <td width="33%"><p><b>排放标准：</b>
                                            @if(!$car_info['paifang'])
                                            国五
                                            @else
                                            国四
                                            @endif
                                            </p></td>
                                        </tr>
                                        <tr>
                                            <td width="33%"><p><b>出厂年月：</b>
                                        @if($baojia['bj_is_xianche'])
                                        (不早于)<span>{{date("Y年m月",strtotime($baojia['bj_producetime']))}}</span>
                                        @else
                                        (不早于)<span>{{date("Y年m月", time())}}</span>
                                        @endif
                                        </p></td>
                                                    <td width="33%"><p><b>行驶里程：</b>
                                        @if($baojia['bj_is_xianche'])
                                        (不高于）<span>{{$baojia['bj_licheng']}}公里</span>
                                        @else
                                        (不高于)<span>20公里</span>
                                        @endif
                                            </p></td>
                                            @if(!$baojia['bj_is_xianche'])
                                            <td width="33%"><p><b>交车周期：</b>{{$baojia['bj_jc_period']}}个月</p></td>
                                            @endif
                                        </tr>

                                        <tr>
                                            <td colspan="3"><hr class="dashed nomargin"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                        @if($baojia['bj_is_xianche'] && isset($originals['rpo']))
                                                <p>已装原厂选装精品：</p>
                                                <table class="tbl tbl3">
                                                    <tr>
                                                       <th width="137"><p class="fs15">名称</p></th>
                                                       <th width="520"><p class="fs15">型号/说明</p></th>
                                                       <th><p class="fs15">厂家指导价</p></th>
                                                       <th><p class="fs15">数量</p></th>
                                                       <th><p class="fs15">附加价值</p></th>
                                                   </tr>
                                                   @foreach($originals['rpo'] as $rpo)
                                                   <tr>
                                                       <td class="tac">{{$rpo['xzj_title']}}</td>
                                                       <td class="tal">{{$rpo['xzj_model']}}</td>
                                                       <td class="tac">￥{{$rpo['xzj_guide_price']}}</td>
                                                       <td class="tac">{{$rpo['num']}}</td>
                                                       <td class="tac">￥{{number_format($rpo['xzj_guide_price']*$rpo['num'],2)}}</td>
                                                   </tr>
                                                   @endforeach
                                                </table>
                                                <h2 class="text-right pr150 fs15"><b>合计价值：</b><span class="juhuang">{{number_format($originals['rpo_sum'],2)}}</span></h2>

                                            </td>
                                            @endif
                                        </tr>
                                    </table>
                                    <hr>
                                     @if(count($order->orderServer))
                                    <p>免费礼品或服务</p>
                                    <div class="fl wp70">
                                        <table class="tbl">
                                            <tr>
                                                <td><p class="tac fs14"><b>名称</b></p></td>
                                                <td><p class="tac fs14"><b>数量</b></p></td>
                                                <td><p class="tac fs14"><b>状态</b></p></td>
                                            </tr>
                                            @foreach($order->orderServer as $orderserve)
                                            <tr>
                                                <td><p class="tac fs14">{{$orderserve->zp_title}}</p></td>
                                                <td><p class="tac fs14">{{$orderserve->num}}</p></td>
                                                <td><p class="tac fs14">
                                                @if($orderserve->is_install)
                                                已安装
                                                @else
                                                /
                                                @endif
                                                </p></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                        @endif
                                    </div>
                                    <div class="clear"></div>

                                    <hr>
                                    <p><b>客户投保：</b>
                                    @if($baojia['bj_baoxian'])
                                    是
                                    @else
                                    待定
                                    @endif
                                    </p>  <br>
                                    <p><b>代办上牌：</b>
                            <?php $shangpai_status=$order->orderAttr['shangpai_status']?>
                                    @if($shangpai_status == 2)
                                    是（￥{{number_format($order['shangpai_price'],2)}}）
                                    @elseif($shangpai_status == 1)
                                    否
                                    @elseif($shangpai_status == 3)
                                    待定
                                    @endif
                                    </p>  <br>
                                    <p><b>代办临牌：</b>
                                    @if($baojia['bj_linpai'] == 1)
                                    是（￥{{number_format($baojia['agent_temp_numberplate_price'],2)}}）
                                    @else
                                    待定
                                    @endif
                                  </p>
                                  @if($shangpai_status == 1)
                                  <br>
                                    <p><b>客户本人上牌违约赔偿（约定）：</b>￥{{number_format($baojia['client_license_compensate'],2)}}</p><br>
                                   @endif
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <form action="{{route('dealer.orderstore',['id'=>$order['id']])}}/" id="user-form-step-1" method="post">
                    <input type="hidden" name="status" value="Y">
                    {!! csrf_field() !!}
                    @if($order->order_state == config('orders.order_earnest_not_confirm_file'))

                   <h2 class="title"><span class="red">*</span><span class="ml5">需要经销商为客户办理的其他上牌资料（请逐项回复）</span></h2>
                    <table class="tbl2 tbl-file">
                    <?php $files = explode('、', $order->orderAttr['file_comment']);?>
                    @foreach($files as $key=>$file)
                        <tr>
                            <td valign="top"><span class="tal fs14 nopadding">{{$file}}：</span></td>
                            <td class="cell">
                                <div>
                                <input type="hidden" name="ziliao[{{$key}}][title]" value="{{$file}}">
                                    <input name="ziliao[{{$key}}][ok]" class="radio" type="radio" value="Y" />
                                    <span>可以办理，费用
                                    <input ms-on-focus="selectParent(this)" ms-on-blur="checkDecimal(this)" type="text" class="baoxianinput2 juhuang shot" name="ziliao[{{$key}}][fee]" value="">元，办理时间
                                    <input ms-on-focus="selectParent(this)" ms-on-blur="checkNumber(this)" type="text" class="baoxianinput2 juhuang shot" name="ziliao[{{$key}}][day]}}" value="">个自然日 </span>
                                    <span class="red error hide">格式有误！</span>
                                </div>
                                <div>
                                    <input ms-on-click="clearInput(this)" name="ziliao[{{$key}}][ok]" checked="" class="radio" type="radio" value="N" />
                                    <span>请恕无法办理   </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <p>温馨提示：20分钟内未提交，平台将默认内容无误自动提交。订单完整内容，参见订单总详情。</p>
                    @endif

                    <p class="center" id="btn-control-wapper">
                        <a href="javascript:;" ms-on-click="send" class="btn btn-s-md btn-danger btn-auto">确认订单内容无误，提交反馈</a>
                        <a ms-on-click="modify" href="javascript:;" class="btn btn-s-md btn-danger btn-auto ml20 btn-white">修改或终止</a>
                    </p>
                    <p class="center red hide" id="question-error">还有未答问题！</p>

                    <div class="modifydiv hide">

                          <p class="fs14 m-t-10"><b>请选择：</b></p>
                          <table class="tbl2">
                            <tr>
                              <td valign="top" width="20" class="nopadding">
                                  <input ms-duplex-boolean="modifyOrStop" value="false" type="radio" class="jiaocheinput" name="state" id="">
                              </td>
                              <td class="nopadding"><p class="fs14">无法交车，终止订单。</p></td>
                            </tr>
                            <tr>
                              <td valign="top" width="20" class="nopadding">
                                  <input ms-duplex-boolean="modifyOrStop" value="true" class="jiaocheinput" checked type="radio" name="state" id="">
                              </td>
                              <td class="nopadding">
                                  <div >
                                      <p class="fs14 ">部分内容修改后（如客户同意）可继续订单。请提交修改内容：</p>
                                      <span class="fl spantitle">车身颜色：</span>
                                      <div class="btn-group m-r fl pdi-drop">
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span> {{$baojia['bj_body_color']}}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul data-def-value="{{$baojia['bj_body_color']}}" class="dropdown-menu dropdown-select2 dropdown-auto">
                                        <input type="hidden" name="body_color" />
                                        <?php $body_colors =$order->orderColors->keyBy('name');?>
                                        <?php $bodys = unserialize($body_colors['body_color']['value']); ?>
                                        <?php $interiors = unserialize($body_colors['interior_color']['value']); ?>
                                        @foreach($bodys as $key=>$value)
                                        <li ms-on-click="selectTime('{{$value}}')"><a><span>{{$value}}</span></a></li>
                                        @endforeach
                                        </ul>
                                      </div>
                                      <div class="clear m-t-10"></div>
                                      <span class="fl spantitle">内饰颜色：</span>
                                      <div class="btn-group m-r fl pdi-drop">
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span>{{$car_info['nside_color']}}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                       <ul data-def-value="{{$car_info['nside_color']}}" class="dropdown-menu dropdown-select2 dropdown-auto">
                                       <input type="hidden" name="interior_color" />
                                            @foreach($interiors as $key=>$value)
                                            <li ms-on-click="selectTime('{{$value}}')"><a><span>{{$value}}</span></a></li>
                                            @endforeach
                                        </ul>
                                      </div>
                                      @if($baojia['bj_baoxian'])
                                      <div class="clear m-t-10"></div>
                                      <span class="fl spantitle">出厂年月：</span>
                                      <div class="btn-group m-r fl ">
                                        <input type="hidden" name="year_month" ms-duplex="yearAndMonth" />
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span>{{substr($baojia['bj_producetime'],0,4)}}年</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul data-def-value="{{substr($baojia['bj_producetime'],0,4)}}年" class="dropdown-menu dropdown-select2 dropdown-auto">
                                            <input type="hidden" name="" />
                                           <li ms-repeat-year="yearList" ms-click-1="selectTime('year年')" ms-click-2="pushMonthList(year,'{{substr($baojia['bj_producetime'],5,3)}}')"><a><span><!--year-->年</span></a></li>
                                        </ul>
                                      </div>
                                      <div class="btn-group m-r fl ml10">
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span>
                                            {{substr($baojia['bj_producetime'],5,3)}}月</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul data-def-value="{{substr($baojia['bj_producetime'],5,3)}}月" class="dropdown-menu dropdown-select2 dropdown-auto">
                                            <input type="hidden" name="" />
                                            <li ms-repeat-month="monthList" ms-click-1="selectTime('month月')"  ms-click-2="selectMonth(month)"><a><span><!--month-->月</span></a></li>
                                        </ul>
                                      </div>
                                      <div class="clear m-t-10"></div>

                                      <span class="fl spantitle">行驶里程：</span>
                                      <div class="btn-group m-r fl ">
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span>{{$baojia['bj_licheng']}}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul data-def-value="{{$baojia['bj_licheng']}}" class="dropdown-menu dropdown-select2 dropdown-auto">
                                            <input type="hidden" name="mileage" />
                                            <li ms-repeat-km="kmList" ms-on-click="selectTime(km)"><a><span><!--km--></span></a></li>
                                        </ul>
                                      </div>
                                      <span class="fl spantitle ml10">公里</span>
                                      @endif
                                      @if(!$baojia['bj_is_xianche'])
                                      <div class="clear m-t-10"></div>
                                      <span class="fl spantitle">交车周期：</span>
                                      <div class="btn-group m-r fl ">
                                        <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                            <span class="dropdown-label"><span>{{$baojia['bj_jc_period']}}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul data-def-value="{{$baojia['bj_jc_period']}}" class="dropdown-menu dropdown-select2 dropdown-auto">
                                            <input type="hidden" name="cycle" />
                                            <li ms-repeat-car="carTimeList" ms-on-click="selectTime(car)"><a><span><!--car--></span></a></li>
                                        </ul>
                                      </div>
                                      <span class="fl spantitle ml10">个月</span>
                                      <div class="clear m-t-10"></div>
                                  </div>
                                  @endif

                              </td>
                            </tr>
                          </table>
                          @if($baojia['bj_is_xianche'] && isset($originals['rpo']))
                          <p class="fs14 ml20">已装原厂选装精品：</p>
                          <table class="tbl bgtbl ml20">
                              <tr>
                                  <th>名称</th>
                                  <th>型号/说明</th>
                                  <th>厂商指导价</th>
                                  <th width="108">数量</th>
                                  <th>附加价值</th>
                              </tr>
                              @foreach($originals['rpo'] as $key=>$rpo)
                              <tr>
                                  <td>{{$rpo['xzj_title']}}</td>
                                  <input type="hidden" name="xzj[{{$key}}][xzj_id]" value="{{$rpo['xzj_id']}}">
                                  <td class="tal">{{$rpo['xzj_model']}}</td>
                                  <input type="hidden" name="xzj[{{$key}}][xzj_model]" value="{{$rpo['xzj_model']}}">
                                  <input type="hidden" name="xzj[{{$key}}][xzj_title]" value="{{$rpo['xzj_title']}}">
                                  <td class="tac">{{$rpo['xzj_guide_price']}}</td>
                                  <input type="hidden" name="xzj[{{$key}}][xzj_guide_price]" value="{{$rpo['xzj_guide_price']}}">
                                  <input type="hidden" name="xzj[{{$key}}][old_num]" value="{{$rpo['num']}}">
                                  <td>
                                      <div class="xuan">
                                          <div class="x-w">
                                              <a ms-click="prev" class="prev">-</a>
                                    <input data-price="{{$rpo['xzj_guide_price']}}" type="text" name="xzj[{{$key}}][num]" readonly="readonly" value="{{$rpo['num']}}" class="input" def-value="{{$rpo['num']}}">
                                              <a ms-click="next({{$rpo['num']}})" class="next">+</a>
                                          </div>
                                      </div>
                                  </td>
                                  <td class="tac">
                                      {{$rpo['num'] * $rpo['xzj_guide_price']}}
                                  </td>
                              </tr>
                          @endforeach
                          </table>
                          <p class="ml20">
                              <small class="wp45 fr tar di mr150"><span>合计价值：<label>{{number_format($originals['rpo_sum'],2)}}</label></span></small>
                              <input type="hidden" name="price">
                          </p>
                          @endif
                          @if(count($order->orderServer))
                          <p class="fs14 ml20">免费礼品和服务：</p>
                          <table class="tbl bgtbl ml20 bgtbl-mini">
                              <tr>
                                  <th>名称</th>
                                  <th>数量</th>
                                  <th>状态</th>
                              </tr>
                              @foreach($order->orderServer as $key=>$orderserve)
                              <tr data-id="3">
                                  <td>{{$orderserve->zp_title}}</td>
                                  <input type="hidden" name="zhengpin[{{$key}}][zp_title]" value="{{$orderserve->zp_title}}">
                                  <input type="hidden" name="zhengpin[{{$key}}][zp_status]" value="{{$orderserve->is_instal}}">
                                  <input type="hidden" name="zhengpin[{{$key}}][old_num]" value="{{$orderserve->num}}">
                                  <td align="center">
                                      <div class="xuan">
                                          <div class="x-w">
                                              <a ms-click="prev2" class="prev">-</a>
                                              <input type="text" name=zhengpin[{{$key}}][num] readonly value="{{$orderserve->num}}" def-value="{{$orderserve->num}}" class="input">
                                              <a ms-click="next2({{$orderserve->num}})" class="next">+</a>
                                          </div>
                                      </div>
                                  </td>
                                  <td class="tac">
                                  <p class="fs14">
                                  @if($orderserve->is_install)
                                    已安装
                                  @else
                                    /
                                  @endif
                                     </p>
                                  </td>
                              </tr>
                              @endforeach
                          </table>
                          @endif
                          <br><br>
                          <div class="tac">
                            <input type="checkbox" ms-duplex-string="agreeList" value="1" checked="" name="" id=""><span class="fn fs14">同意支付歉意金赔偿</span>
                          </div>
                          <div class="center">
                            <a href="javascript:;" ms-on-click="sureModify" class="btn btn-s-md btn-danger fs16 btn-white">确认变更订单</a>
                            <a href="javascript:;" ms-on-click="noModify" class="btn btn-s-md btn-danger fs16 ml20 btn-empty">放弃修改，返回</a>
                          </div>
                          <p class="tac red m-t-10" ms-if="!agree && isSend">
                            变更订单须同意支付歉意金赔偿！
                          </p>
                          <p class="tac red m-t-10" ms-if="agree && isSend && noChange">
                            无变更内容，请返回！
                          </p>

                    </div>
                    </form>

                    <form action="{{route('dealer.endoreder',['id'=>$order['id']])}}/" id="user-form-step-1-2" method="post">
                    {!! csrf_field() !!}
                    </form>

                    <form action="{{route('dealer.orderstore',['id'=>$order['id']])}}/" id="user-form-step-1-1" method="post">
                    {!! csrf_field() !!}
                    </form>

            </div>

        </div>

        <div id="modifyWin" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac succeed error constraint">
                       <span class="tip-tag bp0"></span>
                       <span class="tip-text">确定修改订单，并按华车平台规则支付赔偿吗？</span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
               </div>
                <div class="popup-control">
                    <a href="javascript:;" ms-on-click="doModify" class="btn btn-s-md btn-danger fs14  w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>

                </div>
            </div>
        </div>

        <div id="stopWin" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac succeed error constraint">
                       <span class="tip-tag bp0"></span>
                       <span class="tip-text">确定终止订单，并按华车平台规则支付赔偿吗？</span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
               </div>
                <div class="popup-control">
                    <a href="javascript:;" ms-on-click="stopOrder" class="btn btn-s-md btn-danger fs14 w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div id="sendWin" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac">
                       <br>
                       <span class="tip-text fs14 text-left inline-block">确定提交反馈吗？</span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" ms-on-click="doSend({{$order->id}})" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>



    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/custom/custom.order.step1","module/common/common"],function(a,b,c){
          a.init('{{date('Y-m-d H:i:s',time())}}','{{$order->rockon_time}}',function(){
            window.location.reload()
          })
          a.initPushKmList({{$baojia['bj_licheng']}})
          a.initPushCarTimeList({{$baojia['bj_jc_period']}})
            @if($baojia['bj_producetime'])
          a.initYearAndMonth({{substr($baojia['bj_producetime'],0,4)}},{{substr($baojia['bj_producetime'],5,3)}})
            @endif
        })
    </script>
@endsection
