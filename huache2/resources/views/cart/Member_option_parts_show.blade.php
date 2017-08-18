@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '订购选择精品';?>
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 psr">
        <div class="step pos-rlt">
             <p class="order-head-status"><span class="blue fs18">打造个性座驾</span></p>
             <div class="xzj-status-wrapper">
              @if($order->xzjp_steps == 1)
                 <a href="{{route('parts.negotia',['id'=>$order->id])}}" class="xzj-status-item">
                     <span class="xzj-icon xzj-negotiation"></span>
                     <span class="xzj-txt">
                         <span class="xzj-num">{{$order->orderXzjEdit->where('is_install',0)->count()}}</span>
                         <span class="xzj-status">协商中</span>
                     </span>
                 </a>
                 @endif
                 @if(!$order->orderXzj->isEmpty())
                 <a href="{{route('parts.list',['id'=>$order->id])}}" class="xzj-status-item ml20">
                     <span class="xzj-icon xzj-ordered"></span>
                     <span class="xzj-txt">
                         <span class="xzj-num">{{count($order->orderXzj)}}</span>
                         <span class="xzj-status">已订购</span>
                     </span>
                 </a>
                 @endif
             </div>
        </div>
    </div>

    <div class="container pos-rlt content">
        <p class="juhuang mt10 ml50"><span>想打造只属于您的梦想座驾吗？正是时候！个性化精品已到，助您梦想成真</span><img class="ml5" src="/themes/images/v.gif" alt=""></p>
        <div class="xzj-marquee-wrapper">
            <a @click="marqueeImg('left')"  href="javascript:;" class="xzj-control xzj-m-prev fl"></a>
            <a @click="marqueeImg('right')" href="javascript:;" class="xzj-control xzj-m-next fr" id="xzj-m-next"></a>
            <div class="xzj-m-box fl ml10">
                <div class="xzj-marquee" :style="{'margin-left':marginLeft+'px'}">
                    <img v-for="i in 10" :src="'/themes/images/xzj-'+i+'.png'" alt="">
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="wapper has-min-step">
            <div class="clear m-t-10"></div>

            <?php $gc_name = explode('&gt;', $order->gc_name);?>
            <ul class="pdi-order-ul border ">
               <p class="fs14 ml10">
                   <span>订单号：{{$order->order_sn}}</span>
                   <span class="ml20">{{$gc_name[0]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$gc_name[1]}}</span>
                   <span class="ml5">></span>
                   <span class="ml5">{{$gc_name[2]}}</span>
                   <span class="ml30">{{$order->orderAttr->cart_color}}</span>
               </p>
            </ul>
            <!--没有选装件的时候-->
            @if($originals->where('xzj_max_num','<>',0)->isEmpty() && $order->orderXzj->count() && $nonoriginals->where('xzj_max_num','<>',0)->isEmpty())
            <div class="box tac">
                <p class="wp45 mauto mt20">
                    <span class="icon-large icon-error-large fl"></span><span class="mt10 gray fl ml10">恭喜您已抢订成功！现在一件不剩啦～</span>
                </p>
                <div class="clear"></div>
                <a href="javascript:;" class="mt50 btn btn-s-md btn-danger fs18 sure">关闭</a>
            </div>
            @else
            <!--有选装件的时候-->
            <form id="main" action="{{route('buy.store',['id'=>$order->id])}}" method="post">
            {{csrf_field()}}
            @if(!$originals->isEmpty() && count($originals->where('xzj_max_num','<>',0)->where('xzj_has_num','<>',0)))
                <div class="box">
                    <p class="mt20 psr ml20">
                        <i class="css-arrow psa" @click="toggle(0)" :class="{'css-arrow-rotating':!toggleArray[0].display}"></i>
                        <b class="fs16 ml30 ">原厂</b>
                        <span class="fs14 ml5">选装精品</span>
                        <span class="ml20 p-gray fs12">了解更多？  >  </span>
                        <span class="blue ml10 fs12">上官网</span>
                    </p>
                    <table v-show="toggleArray[0].display" class="tbl tbl-blue tbl-xzj">
                        <tr>
                            <th width="148"><b>名称</b></th>
                            <th width="246"><b>型号/说明</b></th>
                            <th width="104"><b>厂商指导价</b></th>
                            <th width="85"><b>安装费</b></th>
                            <th width="112"><b>含安装费<br>折后总单价</b></th>
                            <th width="120"><b class="red">选择订购件数</b></th>
                            <th width="84"><b>金额</b></th>
                        </tr>
                        @foreach($originals as $key=>$original)
                        @if($original->xzj_has_num && $original->xzj_max_num)
                        <tr>
                            <td><p class="tac">{{$original->xzj_title}}</p></td>
                            <input type="hidden" name="xzj[{{$original->m_id}}][id]" value="{{$original->m_id}}">
                            <input type="hidden" name="xzj[{{$original->m_id}}][type]" value=1>
                            <td><p class="tac">{{$original->xzj_model}}</p></td>
                            <td><p class="tar">￥{{$original->xzj_guide_price}}</p></td>
                            <td><p class="tar">￥{{number_format($original->fee,2)}}</p></td>
                            <td><p class="tar">￥{{number_format(($original->xzj_guide_price*$order->orderBaojia->bj_xzj_zhekou/100 + $original->fee),2)}}</p></td>
                            <td>
                                <div class="x-w xuan @if($original->xzj_has_num <= $original->xzj_max_num) xuan-error @endif">
                                    <span class="red landscape mt-10">供应</span>
                                    <a @click="prev" class="prev">-</a>
                                    <input data-price="{{$original->xzj_guide_price + $original->fee}}" readonly  type="text" value="0" class="input" def-value="0" name="xzj[{{$original->m_id}}][num]">
                                  <?php $has_num = $original->xzj_max_num-$order->getXzjNum($original->m_id);?>
                                    <a @click="next($event,{{min($has_num,$original->xzj_has_num)}})" class="next">+</a>
                                    <span class="red landscape mt-10 ml5">紧张</span>
                                </div>
                            </td>
                            <td><p class="tac">￥0.00</p></td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                    <p class="ml20" v-show="toggleArray[0].display">
                        <small class="wp45 fr tar di mr150">
                            <span>
                            合计价值：<label></label>
                            </span>
                        </small>
                        <input type="hidden" name="price" value="">
                    </p>
                </div>
                 @endif
                <div class="clear"></div>
                @if(!$nonoriginals->isEmpty() && count($nonoriginals->where('xzj_max_num','<>',0)->where('xzj_has_num','<>',0)))
                <div class="box mt20">
                    <p class="mt20 psr ml20">
                        <i @click="toggle(1)" :class="{'css-arrow-rotating':!toggleArray[1].display}" class="css-arrow psa"></i>
                        <b class="fs16 ml30 ">非原厂</b>
                        <span class="fs14 ml5">选装精品</span>
                    </p>
                    <table v-if="toggleArray[1].display" class="tbl tbl-blue tbl-xzj">
                        <tr>
                            <th width="148"><b>品牌</b></th>
                            <th width="148"><b>名称</b></th>
                            <th width="246"><b>型号/说明</b></th>
                            <th width="112"><b>含安装费<br>折后总单价</b></th>
                            <th width="120"><b class="red">选择订购件数</b></th>
                            <th width="84"><b>金额</b></th>
                        </tr>
                        @foreach($nonoriginals as $key=>$nonoriginal)
                        @if($nonoriginal->xzj_has_num && $nonoriginal->xzj_max_num)
                        <tr>
                            <td><p class="tac">{{$nonoriginal->xzj_brand}}</p></td>
                            <td><p class="tac">{{$nonoriginal->xzj_title}}</p></td>
                            <td><p class="tac">{{$nonoriginal->xzj_model}}</p></td>
                            <td><p class="tar">￥{{number_format($nonoriginal->xzj_guide_price,2)}}</p></td>
                            <td>
                            <input type="hidden" name="xzj[{{$nonoriginal->xzj_daili_id}}][id]" value="{{$nonoriginal->xzj_daili_id}}">
                            <input type="hidden" name="xzj[{{$nonoriginal->xzj_daili_id}}][type]" value=0>
                                <div class="x-w xuan @if($nonoriginal->xzj_has_num <= $nonoriginal->xzj_max_num) xuan-error @endif">
                                    <span class="red landscape mt-10">供应</span>
                                    <a @click="prev" class="prev">-</a>
                                    <input data-price="{{$nonoriginal->xzj_guide_price}}" readonly  type="text" value="0" class="input" def-value="0" name="xzj[{{$nonoriginal->xzj_daili_id}}][num]">
                                  <?php $has_num = $nonoriginal->xzj_max_num-$order->getXzjNum($nonoriginal->xzj_daili_id);?>
                                    <a @click="next($event,{{min($has_num,$nonoriginal->xzj_has_num)}})" class="next">+</a>
                                    <span class="red landscape mt-10 ml5">紧张</span>
                                </div>
                            </td>
                            <td><p class="tar">￥0.00</p></td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                    <p class="ml20" v-if="toggleArray[1].display">
                        <small class="wp45 fr tar di mr150">
                            <span>
                            合计价值：<label></label>
                            </span>
                        </small>
                        <input type="hidden" name="price" value="">
                    </p>
                </div>
                @endif
                <div class="clear"></div>
                <p class="mt20">温馨提示：订购的精品将在交车时付款移交。</p>
                <p class="tac red hide" :class="{show:error}">没找到您要订购的精品，请选一下订购数量哦～</p>
                <p class="tac">
                    <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs18">我要订购</a>
                </p>
            </form>
            @endif

            <div id="sendWin" class="popupbox">
                <div class="popup-title">温馨提示</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <p class="fs14 tac">
                           <br>
                           <div class="tip-text fs14 text-left inline-block xzj-send-icon xzj-list-wrapper">
                               <p class="ti">若您确认订购精品，为保证交车时一同向您移交，售方将立即按您的要求完成备货。若此后您提出减少订购数量的变更要求，需取得售方同意方可完成数量修改。因此，一旦订购成功即存在<b>无法再减少</b>订购数量的风险，请慎重决定是否订购下列选装精品：</p>
                               <table class="tbl tbl-blue tac">
                                   <tr>
                                       <th width="120"><b>品牌</b></th>
                                       <th width="180"><b>名称</b></th>
                                       <th width="122"><b>本次订购件数</b></th>
                                       <th width="100"><b>金额</b></th>
                                   </tr>
                                   <tr v-for="order in orderList">
                                       <td>
                                           <p>@{{order.brand}}</p>
                                       </td>
                                       <td>
                                           <p>@{{order.name}}</p>
                                       </td>
                                       <td>
                                           <p>@{{order.count}}</p>
                                       </td>
                                       <td class="tar">
                                           <p>@{{formatMoney(order.price,2,"￥")}}</p>
                                       </td>
                                   </tr>
                               </table>
                               <div class="clear"></div>
                               <div class="xzj-code-send-wrapper mt20">
                                   <span class="fl">手 机 号： {{changeMobile(Auth::user()->phone)}}</span>
                                   <span class="ml20 fl">验 证 码：</span>
                                   <phone-code class="inline-block fl nopadding hasnomargin" phone="{{Auth::user()->phone}}" @valite-code="getCode" sendtype="78525075" sendurl="/parts/getcode"></phone-code>
                               </div>
                               <div class="clear"></div>
                               <p class="tac mt10">
                                   <span v-show="codeStatus == 0" class="red">请输入验证码</span>
                                   <span v-show="codeStatus == 2" class="red">验证码有误，请重新输入~</span>
                                   <span v-show="codeStatus == 3" class="red">验证码已失效，请重新获取~</span>
                               </p>
                           </div>
                           <div class="clear"></div>

                           <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs18 do  ">确认订购</a>
                        <a href="javascript:;" @click="chance" class="btn btn-s-md btn-danger fs18 sure  ml50 inline-block ">取消订购</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>

            <div id="successWin" class="popupbox">
                <div class="popup-title">订购成功</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14 pd tac succeed constraint">
                           <span class="tip-tag bp0"></span>
                           <span class="tip-text mt10">恭喜您离梦想座驾又近一步！如有新的idea，欢迎再来逛逛哦～</span>
                           <div class="clear"></div>
                           <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="subForm" class="btn btn-s-md btn-danger fs14 do w100 sure">确认</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>

            <div id="errorWin" class="popupbox">
                <div class="popup-title">订购未成功</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <p class="fs14 pd tac succeed constraint">
                           <span class="tip-tag bp0"></span>
                           <span class="tip-text mt10">很抱歉，您要订购的部分精品刚被别人抢订，有劳您重选一下。看准了出手一定要快哦～</span>
                           <div class="clear"></div>
                           <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="reload" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">确认</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>



            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-option-choice",  "/js/module/common/common"],function(v,u,c){
            setInterval(function(){
              document.getElementById('xzj-m-next').click()
            },5000)
        })
    </script>
@endsection

