@extends('_layout.base')
@section('css')
  <link href="{{asset('themes/search.css')}}" rel="stylesheet"/>
@endsection
@section('nav')@include('_layout.nav')@endsection

@section('content')
  <form class="SearchPageForm" ms-controller="search" name="SearchPageForm">
    <div class="search-panel-box">
      <div class="container m-t-86 pos-rlt">

        <div class="search-def-option">
          <ul>
            <li>
              <label>新车上牌地区</label>
              <dl class="slidedownfix">
                <dt class="s-area">
                  <i ms-on-click="dropdown"></i>
                <p>{{ $city }}<span>
                    @if($xianpai==1)
                      (限牌)
                    @endif
                  </span></p>
                </dt>
                <dd>
                  <ol>
                    <li ms-on-click="SelectCardArea(1)">苏州</li>
                    <li ms-on-click="SelectCardArea(2)">常熟</li>
                    <li ms-on-click="SelectCardArea(3)">太仓(限牌)</li>
                    <li ms-on-click="SelectCardArea(4)">无锡</li>
                  </ol>
                  <input type="hidden"  name="area"  value="{{Input::get('area')}}"/>
                </dd>
              </dl>
            </li>

            <li>
              <label>品牌</label>
              <dl class="slidedownfix">
                <dt class="s-area">
                  <i ms-on-click="dropdown"></i>
                <p>{{ $pinpai  }}</p>
                </dt>
                <dd>
                  <ol>
                    @foreach($brand as $b=>$v)
                      <li ms-on-click="SelectBrand({{ $v->gc_id }})">{{ $v->gc_name }}</li>
                    @endforeach
                  </ol>
                  <input type="hidden" data-duplex-changed="textchange" name="carbrand" value=""/>
                </dd>
              </dl>
            </li>

            <li>
              <label>车系</label>
              <dl class="slidedownfix">
                <dt class="s-area">
                  <i ms-on-click="dropdown"></i>
                <p>{{ $chexi }}</p>
                </dt>
                <dd>
                  <ol>
                    <li ms-repeat-chexi="chexi" ms-click="SelectChexi(chexi.gc_id)"><!--chexi.gc_name--></li>
                  </ol>
                  <input type="hidden" data-duplex-changed="textchange" name="chexi" value=""/>
                </dd>
              </dl>
            </li>

            <li>
              <label>车型规格</label>
              <dl class="slidedownfix">
                <dt class="s-chexing">
                  <i ms-on-click="dropdown"></i>
                <p>{{ $chexing }}</p>
                </dt>
                <dd>
                  <ol>
                    <li ms-repeat-chexing="chexing" ms-click="SelectChexing(chexing.gc_id)"><!--chexing.gc_name--></li>
                  </ol>
                  <input type="hidden" data-duplex-changed="textchange" name="car" ms-duplex-string="car" value=""/>
                </dd>
              </dl>
            </li>

            <li>
              <label>厂商指导价</label>
              <dl>
                <dt class="s-chexing">
                <p>￥{{ $carmodelInfo['zhidaojia'] }}</p>
                </dt>
                <dd>
                  <input type="hidden" data-duplex-changed="textchange" name="price" value=""/>
                </dd>
              </dl>
            </li>
            <li class="clear"></li>
            <li>
              <label>座位数</label>
              <dl>
                <dt class="s-chexing">
                <p>{{ $carmodelInfo['seat_num'] }}</p>
                </dt>
              </dl>
            </li>
            <li>
              <label>生产国别</label>
              <dl>
                <dt class="s-chexing">
                @if($carmodelInfo['guobie']==1)
                  <p>进口</p>
                @else
                  <p>国产</p>
                @endif
                </dt>
              </dl>
            </li>
            <li>
              <label>基本配置</label>
              <dl>
                <dt class="s-chexing">
                <p>权威参数来自官网 <a href="{{ $barnd_info['official_url'] }}" target="_blank"><span>查看</span></a></p>
                </dt>
              </dl>
            </li>
            <li class="clear"></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container  pos-rlt search-select-option">
      <dl>
        <dt>
          <label>车源距离：</label>
          <label class="checkbox-inline i-checks-me">
            <i class="<?php if(!$juli){echo 'checks-me';}?>"></i>
            <em><a
                href="?car={{ $car }}&area={{ $area }}&juli=&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}">全部</a></em>
            <input name="juli" type="hidden" data-duplex-changed="textchange" ms-duplex-string="juli" value="" class="">
          </label>
        </dt>
        <dd>
          <ul>
            @foreach($att as $a=>$v)
              @if($v['key_type']=='juli')
                <li><em
                    <?php if ($juli == $v['key_value']): ?>
                    class="selected"
                  <?php endif ?>
                  ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $v['key_value'] }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}"> {{ $v['key_name'] }}</a></em>
                </li>
              @endif

            @endforeach
          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl>
        <dt>
          <label>车身颜色：</label>
          <label class="checkbox-inline i-checks-me">
            <i class="<?php if(!$body_color){echo 'checks-me';}?>"></i>
            <em><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color=&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}">全部</a></em>
            <input name="body_color" ms-duplex-string="body_color" type="hidden" data-duplex-changed="textchange"
                   value="" class="">
          </label>

        <div class="select-control">
          <span ms-on-click="showCheckbox" class="start">多选</span>
          <span ms-on-click="filterOption" class="sure">确定</span>
          <span ms-on-click="hideCheckbox" class="chance">取消</span>
        </div>

        </dt>
        <dd>
          <ul>
            @foreach($carmodelInfo['body_color'] as $key=>$v)
              <li>
                <label class="checkbox-inline i-checks-me hide">
                  <i class="checks-me"></i>
                </label>
                <em <?php if ($body_color == $v): ?>
                  class="selected"
                <?php endif ?>
                ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $v }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}"> {{ $v }}</a></em>
              </li>
            @endforeach

          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl>
        <dt>
          <label>内饰颜色：</label>
          <label class="checkbox-inline i-checks-me">
            <i class="<?php if(!$interior_color){echo 'checks-me';}?>"></i>
            <em><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color=&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}">全部</a></em>
            <input name="interior_color" ms-duplex-string="interior_color" type="hidden"
                   data-duplex-changed="textchange" value="" class="">
          </label>

        <div class="select-control">
          <span ms-on-click="showCheckbox" class="start">多选</span>
          <span ms-on-click="filterOption" class="sure">确定</span>
          <span ms-on-click="hideCheckbox" class="chance">取消</span>
        </div>
        </dt>
        <dd>
          <ul>
            @foreach($carmodelInfo['interior_color'] as $key=>$v)
              <li>
                <label class="checkbox-inline i-checks-me hide">
                  <i class="checks-me"></i>
                </label>
                <em
                  <?php if ($interior_color == $v): ?>
                  class="selected"
                <?php endif ?>
                ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $v }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}"> {{ $v }}</a></em>
              </li>
            @endforeach

          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl>
        <dt>
          <label>行驶里程：</label>
          <label class="checkbox-inline i-checks-me">
            <i class="<?php if(!$licheng){echo 'checks-me';}?>"></i>
            <em><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng=&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}">全部</a></em>
            <input name="licheng" type="hidden" data-duplex-changed="textchange" ms-duplex="licheng" value=""/>
          </label>
        </dt>
        <dd>
          <ul>
            @foreach($att as $a=>$v)
              @if($v['key_type']=='licheng')
                <li><em
                    <?php if ($licheng == $v['key_value']): ?>
                    class="selected"
                  <?php endif ?>
                  ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $v['key_value'] }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}"> {{ $v['key_name'] }}</a></em>
                </li>
              @endif
            @endforeach

          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl>
        <dt>
          <label>出厂年月：</label>
          <label class="checkbox-inline i-checks-me">
            <i class="<?php if(empty($chuchang)){echo 'checks-me';}?>"></i>
            <em><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang=&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}">全部</a></em>
            <input name="chuchang" type="hidden" data-duplex-changed="textchange" ms-duplex-string="chuchang" value="" class="">
          </label>
        </dt>
        <dd>
          <ul>
            @foreach($att as $a=>$v)
              @if($v['key_type']=='chuchang')
                <li><em
                    <?php if ($chuchang == $v['key_value']): ?>
                    class="selected"
                  <?php endif ?>
                  ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $v['key_value'] }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun}}"> {{ $v['key_name'] }}</a></em>
                </li>
              @endif
            @endforeach
          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl>
        <dt>
          <label>排放标准：</label>
          <label class="checkbox-inline i-checks-me">
            <i class="<?php if(!$biaozhun){echo 'checks-me';}?>"></i>
            <em><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun=">全部</a></em>
            <input name="biaozhun" ms-duplex-string="biaozhun" type="hidden" data-duplex-changed="textchange" value="" class="">
          </label>
        </dt>
        <dd>
          <ul>
            @foreach($att as $a=>$v)
              @if($v['key_type']=='biaozhun')
                <li><em
                  <?php if ($biaozhun == $v['key_value']): ?>
                    class="selected"
                  <?php endif ?>
                  ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $buytype }}&biaozhun={{ $v['key_value'] }}"> {{ $v['key_name'] }}</a></em>
                </li>
              @endif

            @endforeach
          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl>
        <dt>
          <label>付款方式：</label>
        </dt>
        <dd>
          <ul>
            @foreach($att as $a=>$v)
              @if($v['key_type']=='fukuan' && $v['key_value']==1)
                <li><em
                    <?php if ($fukuan == $v['key_value']): ?>
                    class="selected"
                  <?php endif ?>
                  ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $v['key_value'] }}&buytype={{ $buytype }}&biaozhun={{ $biaozhun }}"> {{ $v['key_name'] }}</a></em>
                </li>
              @endif

            @endforeach

          </ul>
          <a data-slide-data="1" data-s="收起筛选" data-more="更多选项" ms-on-click="slideOption" class="toggle-slide hover">收起筛选<i></i></a>
        </dd>
        <div class="clear"></div>
      </dl>
      <dl class="nobd">
        <dt>
          <label>上牌用途：</label>
          
        </dt>
        <dd>
          <ul>
            @foreach($att as $a=>$v)
              @if($v['key_type']=='buytype')
                <li><em
                    <?php if ($buytype == $v['key_value']): ?>
                    class="selected"
                  <?php endif ?>
                  ><a href="?car={{ $car }}&area={{ $area }}&juli={{ $juli }}&body_color={{ $body_color }}&interior_color={{ $interior_color }}&licheng={{ $licheng }}&chuchang={{ $chuchang }}&fukuan={{ $fukuan }}&buytype={{ $v['key_value'] }}&biaozhun={{ $biaozhun }}"> {{ $v['key_name'] }}</a></em>
                </li>
              @endif

            @endforeach
          </ul>
        </dd>
        <div class="clear"></div>
      </dl>
    </div>
    <div class="container  pos-rlt car-list">
      <table cellpadding="0" cellspacing="0" width="100%">
        <!--表格头-->
        <tr>
          <td>
            <label class="label bg-primary m-l-xs" ms-on-click="compare_car({{$area}},{{$car}},{{$buytype}})">对比</label>
          </td>
          <td class="th">
            <table cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <th width="50">
                  <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                      <td style="border:0" class="">
                        <label data-toggle="tooltip" data-placement="right" title="交车将在该范围内该品牌厂商授权经销商的合法、正规营业场所内进行，具体地点在后续交车通知中另行告知。">
                          <p class="m0">可售</p>
                          <p class="m0">车源</p>
                          <p class="m0">位置</p>
                        </label>
                      </td>
                      <td style="border:0">
                        <i class="cyi cyi-fix">i</i>
                      </td>
                    </tr>
                  </table>
                </th>
                <th width="103">
                  <table cellpadding="0" cellspacing="0" width="100%" height="100%">
                    <tr>
                      <td style="border:0;text-align:right;padding-right:10px;">
                        <label data-toggle="tooltip" data-placement="top" title="此车价为包含经销商裸车开票金额和华车网平台强力保障购车服务费金额的一口价，在交车通知中将告知您两项分别的具体金额。">
                          车价
                        </label>
                      </td>
                      <td style="border:0">
                        <span class="psr">
                            <i class="cyi psa cji">i</i>
                          </span>
                          <span class="th-sort">
                            <i class="" ms-on-click="sort"></i>
                          </span>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="border:0">(含服务费)</td>
                    </tr>
                  </table>
                </th>
                <th width="123">
                  <h6>车身颜色</h6>
                  <h5>
                    <label data-toggle="tooltip" data-placement="right" title="指首年商业保险是否必须在经销商处购买，若是则显示“指定购买”，如不是显示“自由购买“。">
                      <b> 商业保险限定</b>
                    </label>
                    <br/>
                    <label data-toggle="tooltip" data-placement="bottom"
                           title="为便于比较，首年商业保险参考保费报价， 统一按照投保下列项目计算：机动车损失保险； 机动车盗抢险；第三者责任保险：赔付额度 50万元；车上人员责任险（驾驶人1万元限额 + 乘客座位每座各1万元限额  ） ； 玻璃单独破碎险（进口车为进口玻璃、国产车为国产玻璃  ）、自燃损失险、车身划痕损失险赔付额度2万元、不计免赔特约险（包含机动车损失保险、机动车盗抢险、第三者责任保险、车上人员责任险、车身划痕损失险）。您在收到交车通知后，可再正式确认投保项目，我们承诺项目不变则价格不变；如您到时需要更改投保项目，我们亦承诺原报价的定价标准不变。">
                      <b> 和参考保费</b>
                    </label>
                    <span class="psr">
                      <i class="cyi psa xdi">i</i>
                      <i class="cyi psa xdi cbi">i</i>
                    </span>
                  </h5>

                </th>
                <th width="123">
                  <h6>内饰颜色</h6>
                  <h5>
                    <label data-toggle="tooltip" data-placement="right"
                           title="上牌服务：指经销商为购车方代办机动车注册登记手续的服务，仅按车管所相关规则办理，不对牌号结果负责，也不含在某些限牌地区通过摇号、拍卖、转让等方式取得牌照资源指标的服务和费用。购车方上牌服务必须委托经销商办理，则显示“指定上牌”；如显示“自助上牌”，下方的报价为您可选择该经销商代办上牌的参考价格，当然，您也可选择不代办而亲自去办理；如显示“接受安排”，指华车网平台来安排上牌手续由经销商代办或者您亲自办理，您须完全接受我们所作的安排，如交车通知中告知经销商代办的，其服务费用按下方标准收取。">
                      <b> 上牌服务限定</b>
                    </label>
                    <br/>和参考保费
                    <span class="psr">
                      <i class="cyi psa xdi">i</i>
                    </span>
                  </h5>
                </th>
                <th width="123">
                  <h6>行驶里程</h6>
                  <h5>
                    <label data-toggle="tooltip" data-placement="bottom" title="上临时牌：指办理车辆临时移动牌照。必须由经销商统一办理的，显示“指定上牌”；如非必须，则显示“自助上牌”，下方有报价的为您可选择该经销商代办上牌的参考价格。">
                      <b>上临时牌限定</b>
                    </label>
                    <br/>和参考保费
                    <span class="psr">
                      <i class="cyi psa xdi">i</i>
                    </span>
                  </h5>
                </th>
                <th width="148">
                  <h6>出厂年月或交车周期</h6>
                  <h5>其他收费项目<br/>和金额</h5>
                </th>

                <th width="83">
                  <h6>排放标准</h6>
                  <h5>补贴种类<br/>和金额</h5>
                </th>
                <th width="123">
                  <h6>已装原厂选装精品</h6>
                  <h5>可选原厂选装<br/>参考折扣</h5>
                </th>
                <th width="106">
                  <h6>免费礼品或服务</h6>
                  <h5>买车担保金</h5>
                </th>
                <th width="70">
                  <h6>诚意金<br/>￥499</h6>
                </th>
              </tr>
            </table>
          </td>
        </tr>
        @foreach($baojialist as $baojia=>$bj)

          <?php
          if ($body_color) {
            if (strpos($body_color, $bj['body_color']) === false) {
              continue;
            }
          }
          ?>

          <?php
          if ($interior_color) {
            if (strpos($interior_color, $bj['interior_color']) === false) {
              continue;
            }

          }
          ?>
		<?php
          if (!empty($biaozhun)) {
            if ($biaozhun != $bj['paifang']) {
              continue;
            }
          }
          ?>
          <?php
          $xzj_count = 0.00;
          $xzj_title = '已装原厂选装精品：';
          foreach ($bj['xzj'] as $key => $value) {

            $xzj_title .= $value['xzj_title'] . '、';
            $xzj_count += $value['xzj_guide_price'];


          }
          if ($xzj_count > 0) {
            $xzj_title = trim($xzj_title, '、') . "，合计总价值$xzj_count(按厂商指导价计)。";
          }

          $zengpin_title = ' 免费礼品或服务：';
          foreach ($bj['zengpin'] as $key => $value) {
            $zengpin_title .= $value['title'] . '、';
          }
          if (count($bj['zengpin']) > 0) {
            $zengpin_title = trim($zengpin_title, '、') . '。';
          } else {
            $zengpin_title .= ' 暂无赠品';
          }


          $other_count = 0.00;
          $other_title = '其他收费项目和金额：';
          foreach ($bj['otherprice'] as $key => $value) {
            $other_title .= $key . $value . '元、';
            $other_count += $value;
          }
          $other_title = trim($other_title, '、') . "，共计" . $other_count . "元。";
          if ($other_count > 0) {
            $other_title = $other_title;
          } else {
            $other_title = '';
          }


          $butie_title = "补贴种类和金额：";
          if ($bj['bj_butie'] > 0) {
            $butie_title .= "国家节能补贴：" . $bj['bj_butie'] . "元。";
          } else {
            $butie_title .= "暂无补贴";
          }
          ?>
          <tr>
            <td>
              <label ms-data-id="{{ $bj['bj_id'] }}" class="checkbox-inline i-checks-me" ms-on-click="duibicheck">
                <i class="" name="compare_queue[{{ $bj['bj_id'] }}]"></i>
              </label>
            </td>
            <td class="td ">
              <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td width="53" style="border-right:0">
                    <h6>{{ $bj['show_distance'] }}公里</h6>
                  </td>
                  <td class="nobd">
                    <table cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td width="112" class="first">
                          <h6>￥{{ $bj['bj_price'] }}</h6>

                        </td>
                        <td width="133">
                          <h6>{{ $bj['body_color'] }}</h6>
                          <h5>
                            @if($bj['bj_baoxian']==1)
                              指定购买
                            @else
                              自由购买
                            @endif<br/>￥{{ $bj['bxprice']['count'] }}

                            <br/>
                            <label class="hastopborder"><span class="gray">≤</span>￥{{ $bj['bxprice']['count'] }}
                            </label>

                          </h5>

                        </td>
                        <td width="133">
                          <h6>{{ $bj['interior_color'] }}</h6>
                          <h5>
                            @if($bj['bj_shangpai']==1)
                              指定上牌
                            @else
                              自由上牌
                            @endif<br/>
                            <br/>
                            <label class="hastopborder"><span class="gray">≤</span>￥{{ $bj['bj_shangpai_price'] }}
                            </label>
                          </h5>

                        </td>
                        <td width="133">
                          <h6>{{ $bj['bj_licheng'] }}</h6>
                          <h5>
                            @if($bj['bj_linpai']==1)
                              指定上牌
                            @else
                              自由上牌
                            @endif
                            <br/>
                            <label class="hastopborder"><span class="gray">≤</span>￥{{ $bj['bj_linpai_price'] }}</label>
                          </h5>
                        </td>
                        <td width="160">
                          <h6><span class="gray">不早于</span>{{ $bj['timerange'] }}</h6>
                          <h5 ms-on-click="showDetail" data-title="{{$other_title}}">
                            <table class="inner-c" cellpadding="0" cellspacing="0" width="100%" height="100%">
                              <tr>
                                <td><span class="gray">≤</span>{{ $bj['bj_other_price'] }}</td>
                              </tr>
                            </table>
                          </h5>
                        </td>
                        <td width="90">
                          <h6>@if($carmodelInfo['paifang']==0)
                              国5标准
                            @elseif($carmodelInfo['paifang']==1)
                              国4标准
                            @elseif($carmodelInfo['paifang']==2)
                              新能源
                            @endif</h6>
                          <h5 ms-on-click="showDetail" data-title="{{$butie_title}}">
                            <label>国家补贴</label>
                            <br/>
                            <label class="hastopborder">-￥{{ $bj['bj_butie'] }}</label>
                          </h5>

                        </td>
                        <td width="133">
                          <h6 ms-on-click="showDetail" data-title="{{$xzj_title}}">@foreach($bj['xzj'] as $key=>$value)
                              {{ $value['xzj_title'] }}
                            @endforeach&nbsp;</h6>
                          <h5 ms-on-click="showDetail" data-title="{{$xzj_title}}">
                            <table class="inner-c" cellpadding="0" cellspacing="0" width="100%" height="100%">
                              <tr>
                                <td>{{ $bj['bj_xzj_zhekou'] }} %</td>
                              </tr>
                            </table>
                          </h5>
                        </td>
                        <td width="115">
                          <h6 ms-on-click="showDetail"
                              data-title="{{$zengpin_title}}">@foreach($bj['zengpin'] as $key=>$value)
                              {{ $value['title'] }}
                            @endforeach &nbsp;</h6>
                          <h5 ms-on-click="showDetail" data-title="￥{{ $bj['bj_car_guarantee'] }}">
                            <table class="inner-c" cellpadding="0" cellspacing="0" width="100%" height="100%">
                              <tr>
                                <td>￥{{ $bj['bj_car_guarantee'] }}</td>
                              </tr>
                            </table>
                          </h5>
                        </td>

                        <td class="last">
                          <h6><a href="{{ url('show/'.$bj['bj_serial']).'/'.$bj['show_distance'].'/'.$buytype }}"
                                 class="btn btn-s-md btn-danger  qg">抢订</a></h6>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="10" class="info-c">


                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>

          </tr>
        @endforeach

      </table>

      <div class="toolbar" style="">

        {!! $page !!}
      </div>
    </div>
  </form>

@endsection
@section('js')
  <script type="text/javascript">
    seajs.use(["module/search/search", "module/common/common", "bt"]);
  </script>
@endsection
