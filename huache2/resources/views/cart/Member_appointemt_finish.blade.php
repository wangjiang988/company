@extends('HomeV2._layout.base2')
@section('css')
  <?php $title = '等待售方反馈希望交车时间 - 华车网';?>
  <link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt">
        <div class="step psr">
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
                    <small>反馈确认</small>
                    <i></i>
                    <small class="juhuang" class="">预约完毕</small>
                </div>
            </div>

        </div>
    </div>

    <div class="container pos-rlt content r-pdi">

        <div class="wapper has-min-step">
         @include('cart._layout.appointemt_content')

            <!--您的资金准备-->
            <div class="box mt20">
                <div class="title">
                    <label ms-click="toggleContent">您的资金准备</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def tac">
                    <div class="clear m-t-10"></div>
                    <table class="tbl text-center tbl-blue fs14 wp80 mauto">
                        <tr>
                            <th width="200"><b>项目名称</b></th>
                            <th><b>确定金额</b></th>
                            <th><b>待定金额</b></th>
                            <th><b>备注</b></th>
                        </tr>
                        <tr>
                            <td>车辆开票价格</td>
                            <td class="tar">￥523,000.00</td>
                            <td></td>
                            <td>经销商处支付</td>
                        </tr>
                        <tr>
                            <td>选装精品合计</td>
                            <td class="tar">￥3,520.00</td>
                            <td></td>
                            <td>经销商处支付</td>
                        </tr>
                        <tr>
                            <td>PDI检测费</td>
                            <td class="tar">￥1,000.00</td>
                            <td></td>
                            <td>经销商处支付</td>
                        </tr>
                        <tr>
                            <td>办理XX证明</td>
                            <td class="tar">￥660.00</td>
                            <td></td>
                            <td>经销商处支付</td>
                        </tr>
                        <tr>
                            <td>超期费</td>
                            <td class="tar">￥150.00</td>
                            <td></td>
                            <td>经销商处支付</td>
                        </tr>
                        <tr>
                            <td>交强险</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" v-model="insurance" :value="insurance" class="price-input" type="text" value="1,000.00">
                            </td>
                            <td>投保时支付为准</td>
                        </tr>
                        <tr>
                            <td>车船使用税</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" v-model="transportRoyalities" :value="transportRoyalities" class="price-input" type="text" value="360.00">
                            </td>
                            <td>保险公司代收或办税服务厅缴纳为准</td>
                        </tr>
                        <tr>
                            <td>车辆购置税</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" v-model="vehiclePurchaseTax" :value="vehiclePurchaseTax" class="price-input" type="text" value="44,700.86">
                            </td>
                            <td>上牌地购置税办税服务厅实缴为准</td>
                        </tr>
                        <tr>
                            <td>上牌费</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" v-model="registration" :value="registration" class="price-input" type="text" value="1,000.00">
                            </td>
                            <td>按实际发生为准</td>
                        </tr>
                        <tr>
                            <td>商业保险</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" v-model="commercialInsurance" :value="commercialInsurance" class="price-input" type="text" value="1,000.00">
                            </td>
                            <td>投保时支付为准</td>
                        </tr>
                        <tr>
                            <td>上临时牌照费</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" v-model="temporaryLicenceFee" :value="temporaryLicenceFee" class="price-input" type="text" value="1,000.00">
                            </td>
                            <td>按实际发生为准</td>
                        </tr>
                        <tr>
                            <td>其他费用</td>
                            <td class="tar"></td>
                            <td>
                                <span>￥</span>
                                <input v-model="otherFees" @blur.stop-1="subPrice" @focus="initPrice" @blur.stop-2="setPrice" :value="otherFees" class="price-input" type="text" value="1,000.00">
                            </td>
                            <td>估算所有的其他花费</td>
                        </tr>

                    </table>
                    <div class="clear m-t-10"></div>
                    <p class="fs14">
                        <span>共计：</span>
                        <span class="ml50">@{{fixedPrice}}</span>
                        <span class="ml50">+</span>
                        <span class="ml50">@{{price}}</span>
                        <span class="ml50">=</span>
                        <span class="ml50 red">@{{totalPrice}}</span>
                        <a href="javascript:;" @click="savePrice" class="btn btn-s-md btn-danger fs14  w100 sure ml50">保存</a>
                    </p>
                    <div class="clear m-t-10"></div>
                    <p class="fs14 tal">
                        温馨提示：加信宝中另有您的买车担保金￥20,000.00，订单顺利完成后转付华车服务费￥1,000.00，多余金额￥19,000.00 <br>
                        <span class="ml70">将退还给您。</span>
                    </p>


                </div>
            </div>

            <!--您的文件资料-->
            <div class="box mt20">
                <div class="title">
                    <label ms-click="toggleContent">您的文件资料</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def tac">
                    <div class="clear m-t-10"></div>
                    <table class="tbl text-center tbl-blue fs14 wp80 mauto">
                        <tr>
                            <th width="200"><b>使用场合</b></th>
                            <th width="175"><b>是否确定经销商处使用</b></th>
                            <th width="390"><b>名称</b></th>
                            <th width="70"><b>数量</b></th>
                            <th width="70"><b>操作</b></th>
                        </tr>
                        <tr>
                            <td>交车前与售方签署</td>
                            <td><span class="juhuang">确定</span></td>
                            <td>交车确认书</td>
                            <td>√</td>
                            <td><a href="#" class="juhuang tdu">下载</a></td>
                        </tr>
                        <tr>
                            <td>交车时检验车辆</td>
                            <td><span class="juhuang">确定</span></td>
                            <td>交车宝典</td>
                            <td>√</td>
                            <td><a href="#" class="juhuang tdu">下载</a></td>
                        </tr>

            @if(!(($order->orderAppoint->owner_name == $order->orderAppoint->extract_name) && ($order->orderAppoint->car_purpose == 0)))
                        <tr>
                        @if($file_lists->where('cate_id',1)->isEmpty())
                            <td>提车人身份验证</td>
                            <td><span class="juhuang">确定</span></td>
                            <td>无</td>
                            <td></td>
                            <td><a href="#" class="juhuang tdu"></a></td>
                        @elseif($file_lists->where('cate_id',1)->count() == 1)
                            <td>提车人身份验证</td>
                            <?php $file = $file_lists->where('cate_id',1)->first();?>
                            <td><span class="juhuang">确定</span></td>
                            <td>{{$file->title}}</td>
                            <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                            <td><a href="{{$file->file_url}}" class="juhuang tdu">下载</a></td>
                        @else
                         <td rowspan="{{$file_lists->where('cate_id',1)->count()}}">
                                提车人身份验证<br>
                            </td>
                            <td rowspan="{{$file_lists->where('cate_id',1)->count()}}">
                            <span class="juhuang">确定</span>
                            </td>
                            @foreach($file_lists->where('cate_id',1) as $files)
                            <td>{{$files->title}}</td>
                            <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                            <td><a href="{{$files->file_url}}" class="juhuang tdu">下载</a></td>
                            </tr><tr>
                            @endforeach
                         @endif
                        </tr>
                    @endif
                    @if($order->orderAppoint->car_purpose == 2)
                         <tr>
                            <td>投保车辆首年商业保险</td>
                            <td><span>待定</span></td>
                            <td><span class="juhuang">请与售方直接确认</span></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>上牌</td>
                            <td><span>待定</span></td>
                            <td><span class="juhuang">请与售方直接确认</span></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>上临时牌照</td>
                            <td><span>待定</span></td>
                            <td><span class="juhuang">请与售方直接确认</span></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @else
                       <tr>
                        @if($file_lists->where('cate_id',2)->isEmpty() || !$pay_status)
                            <td>投保车辆首年商业保险</td>
                            <td><span>待定</span></td>
                            <td>无</td>
                            <td></td>
                            <td><a href="#" class="juhuang tdu"></a></td>
                        @elseif($file_lists->where('cate_id',2)->count() == 1)
                            <td>投保车辆首年商业保险</td>
                            <?php $file = $file_lists->where('cate_id',2)->first();?>
                            <td><span>待定</span></td>
                            <td>{{$file->title}}</td>
                            <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                            <td><a href="{{$file->file_url}}" class="blue">下载</a></td>
                        @else
                         <td rowspan="{{$file_lists->where('cate_id',2)->count()}}">
                                投保车辆首年商业保险<br>
                            </td>
                            <td rowspan="{{$file_lists->where('cate_id',2)->count()}}">
                            <span>待定</span>
                            </td>
                            @foreach($file_lists->where('cate_id',2) as $files)
                            <td>{{$files->title}}</td>
                            <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                            <td><a href="{{$files->file_url}}" class="blue">下载</a></td>
                            </tr><tr>
                            @endforeach
                         @endif
                        </tr>

                @if(!($order->orderAppoint->car_purpose ==0 && $order->orderAppoint->identity_type>0))
                      <tr>
                        @if($file_lists->where('cate_id',3)->isEmpty() || !$pay_status)
                            <td>代办上牌手续（含缴纳车辆购置税）<br></td>
                            <td><span>待定</span></td>
                            <td>无</td>
                            <td></td>
                            <td><a href="#" class="juhuang tdu"></a></td>
                        @elseif($file_lists->where('cate_id',3)->count() == 1)
                            <td>代办上牌手续<br>（含缴纳车辆购置税）<br></td>
                            <?php $file = $file_lists->where('cate_id',3)->first();?>
                            <td><span>待定</span></td>
                            <td>{{$file->title}}</td>
                            <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                            <td><a href="{{$file->file_url}}" class="blue">下载</a></td>
                        @else
                         <td rowspan="{{$file_lists->where('cate_id',3)->count()}}">
                                代办上牌手续<br>（含缴纳车辆购置税）<br>
                            </td>
                            <td rowspan="{{$file_lists->where('cate_id',3)->count()}}">
                            <span>待定</span>
                            </td>
                            @foreach($file_lists->where('cate_id',3) as $files)
                            <td>{{$files->title}}</td>
                            <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                            <td><a href="{{$files->file_url}}" class="blue">下载</a></td>
                            </tr><tr>
                            @endforeach
                         @endif
                        </tr>
                    @endif


                        <tr>
                        @if($file_lists->where('cate_id',4)->isEmpty() || !$pay_status)
                            <td>代办车辆临时牌照手续</td>
                            <td><span>待定</span></td>
                            <td>无</td>
                            <td></td>
                            <td><a href="#" class="juhuang tdu"></a></td>
                        @elseif($file_lists->where('cate_id',4)->count() == 1)
                            <td>代办车辆临时牌照手续</td>
                            <?php $file = $file_lists->where('cate_id',4)->first();?>
                            <td><span>待定</span></td>
                            <td>{{$file->title}}</td>
                            <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                            <td><a href="{{$file->file_url}}" class="blue">下载</a></td>
                        @else
                         <td rowspan="{{$file_lists->where('cate_id',4)->count()}}">
                                代办车辆临时牌照手续<br>
                            </td>
                            <td rowspan="{{$file_lists->where('cate_id',4)->count()}}">
                            <span>待定</span>
                            </td>
                            @foreach($file_lists->where('cate_id',4) as $files)
                            <td>{{$files->title}}</td>
                            <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                            <td><a href="{{$files->file_url}}" class="blue">下载</a></td>
                            </tr><tr>
                            @endforeach
                         @endif
                        </tr>
                    @endif

                @if($pay_status)
                    <tr>
                        @if($file_lists->where('cate_id',5)->isEmpty() || !$pay_status)
                            <td>非卡主本人刷卡</td>
                            <td><span>待定</span></td>
                            <td>无</td>
                            <td></td>
                            <td><a href="#" class="juhuang tdu"></a></td>
                        @elseif($file_lists->where('cate_id',5)->count() == 1)
                            <td>非卡主本人刷卡</td>
                            <?php $file = $file_lists->where('cate_id',5)->first();?>
                            <td><span>待定</span></td>
                            <td>{{$file->title}}</td>
                            <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                            <td><a href="{{$file->file_url}}" class="blue">下载</a></td>
                        @else
                         <td rowspan="{{$file_lists->where('cate_id',5)->count()}}">
                                非卡主本人刷卡<br>
                            </td>
                            <td rowspan="{{$file_lists->where('cate_id',5)->count()}}">
                            <span>待定</span>
                            </td>
                            @foreach($file_lists->where('cate_id',5) as $files)
                            <td>{{$files->title}}</td>
                            <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                            <td><a href="{{$files->file_url}}" class="blue">下载</a></td>
                            </tr><tr>
                            @endforeach
                         @endif
                        </tr>
                    @endif
                    </table>
                    <div class="clear m-t-10"></div>

                    <p class="fs14 tal">
                        温馨提示：1.“确定”经销商处使用的文件资料，前往提车请务必携带；<br>
                        <span class="ml70">2.“待定”的文件资料，您可根据是否需要在经销商处办理来决定提车时是否携带，如您尚未定夺，建议一并携带有备无患。</span><br>
                        @if(!$pay_status)
                        <span class="ml70">3.  该经销商处不支持非银行卡卡主本人的刷卡付款。</span>
                        @endif
                    </p>
                    <div class="clear m-t-10"></div>
                    <div class="title"></div>
                    <div class="clear m-t-10"></div>
                    <div class="ul-prev tal">
                        <span class="fs14 fl mt2">您的华车专属客服此次保驾护航，出发前请加华车客服QQ或微信，提车中遇到困扰可以随时咨询哦～</span>
                        <a target="_blank" href="tencent://message/?uin=574589608&Site=苏州华车网络科技有限公司&Menu=yes" class="ml20 fl mt-5"><img src="/webhtml/order/themes/images/qq.png" alt=""></a>
                        <a href="#" class="ml20 fl mt-5 psr" @mouseover="showQcode" @mouseleave="showQcode">
                            <img src="/webhtml/order/themes/images/weixin.png" alt="">
                            <img class="psa hide" :class="{show:isQcode}" src="/webhtml/common/images/qcode.gif" alt="">
                        </a>
                        <div class="clear"></div>
                    </div>

                    <p class="ul-prev tal"><span class="fs14">您收车后勿忘点一点“我已收车”告诉我们核实，还有买车担保金要退呢！</span></p>
                    <div class="clear m-t-10"></div>
                    <p class="tac mt20">
                        <a @click="collect" href="javascript:;" class="btn btn-s-md btn-danger fs18">我   已  收  车</a>
                    </p>

                    <div id="collectWin" class="popupbox">
                    <div class="popup-title"><span>确认收车</span></div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac" >
                               <span class="tip-text mt10">您将向华车反馈本次交车情况，确定吗？</span>
                               <div class="clear"></div>
                               <br>
                            <div class="m-t-10"></div>
                        </div>
                        {{csrf_field()}}
                        <div class="popup-control">
                            <a @click="doSend('{{route('store.deal',['id'=>$order->id])}}')" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                            <div class="clear"></div>

                            <div class="m-t-10"></div>
                        </div>
                    </div>
                </div>




                </div>
            </div>


            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>
            <div class="clear m-t-10"></div>

        </div>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-customers-waiting", "/js/module/common/common"],function(v,u,c){
            u.initFixedPrice([523000,3520,1000,660,150])
            u.initPrice(1000,360,44700.86,1000,200,200,100)
        });
    </script>
@endsection
