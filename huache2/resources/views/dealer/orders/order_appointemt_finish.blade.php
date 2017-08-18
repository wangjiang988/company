@extends('_layout.orders.base_order')
@section('title', '售方等待交车-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 psr">
       <div class="cus-step">
           <div class="line stp-3"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>

       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-3">
                    <small>发出通知</small>
                    <i></i>
                    <small>确认反馈</small>
                    <i></i>
                    <small class="juhuang">预约完毕</small>
                </div>
            </div>
       </div>


        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                 @include('dealer.orders._layout.content')
                    <p>温馨提示：订单完整内容，参见订单总详情。</p>


                    <div class="m-t-10"></div>
                    <h2 class="title">
                        <span class="blue ml5 weight">交车提示</span>
                    </h2>

                    <div class="time-wrapper fs14 box-inner-def box-inner-fix box-border" style="width: 100%">
                         @if($order->orderAppoint->appoinWaiter)
                          <p>服务专员：{{$order->orderAppoint->appoinWaiter->name}}（T: {{$order->orderAppoint->appoinWaiter->mobile}}
                          @if($order->orderAppoint->appoinWaiter->tel)
                          / {{$order->orderAppoint->appoinWaiter->tel}}
                          @endif
                          ）
                          @endif
                          <a href="#" class="ml100 juhuang">交车确认书</a></p>
                          <div class="m-t-10"></div>
                          @if($annx['files'])
                          <p>交车时当场向客户移交的文件资料：{{implode(',', $annx['files'])}}</p>
                          @endif
                          <div class="m-t-10"></div>
                          @if($annx['tools'])
                          <p>随车工具：{{implode(',',$annx['tools'])}}</p>
                          @endif
                          <div class="m-t-10"></div>
                          @if(!$xzj_lists->isEmpty())
                          <p>客户订购的选装精品：</p>
                          <table class="tbl tbl-blue tac">
                            <tr>
                              <th width="100"><b>品牌</b></th>
                              <th><b>名称</b></th>
                              <th><b>型号/说明</b></th>
                              <th width="100"><b>厂商编号</b></th>
                              <th width="160"><b>含安装费<br>折后总单价</b></th>
                              <th width="100"><b>已订件数</b></th>
                              <th><b>金额</b></th>
                            </tr>
                            @foreach($xzj_lists as $xzj)
                            <tr>
                              @if($xzj->xzj_type)
                              <td>原厂</td>
                              @else
                              <td>{{$xzj->xzj_brand}}</td>
                              @endif
                              <td>{{$xzj->xzj_title}}</td>
                              <td>{{$xzj->xzj_model}}</td>
                              <td>{{$xzj->xzj_cs_serial}}</td>
                              <td class="tar">￥{{$xzj->xzj_guide_price + $xzj->xzj_fee}}</td>
                              <td>{{$xzj->same_sun}}</td>
                              <td class="tar">￥{{($xzj->xzj_guide_price + $xzj->xzj_fee) * $xzj->same_sun}}</td>
                            </tr>
                            @endforeach
                          </table>
                          @endif
                          <div class="m-t-10"></div>
                          <p>温馨提示：订单完整内容，参见订单总详情。</p>
                    </div>

                    <div class="clear m-t-10"></div>
                    <div class="clear m-t-10"></div>
                    @if($order->order_state == config('orders.order_jiaoche_confirm') || $order->order_state == config('orders.order_jiaoche_member'))
                    <p class="center">
                        <a href="javascript:;" @click="send" class="btn btn-s-md btn-danger btn-auto">已交车，提交信息</a>
                    </p>
                    <div id="tipWin" class="popupbox">
                    <div class="popup-title"><span>交车确认</span></div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <div class="m-t-10"></div>
                                <p class="fs14 pd tac" >
                                   <span class="tip-text mt10">确定马上提交交车信息吗？</span>
                                   <div class="clear"></div>
                                   <br>
                                <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                            {{csrf_field()}}
                                <a @click="doSend('{{route('dealer.deal.store',['id'=>$order->id])}}')" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                                <div class="clear"></div>

                                <div class="m-t-10"></div>
                            </div>
                        </div>
                    </div>
                   @endif

            </div>

        </div>

    </div>
    </div>

@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-seller-waiting-delivery", "module/common/common"],function(v,u,c){


        })
    </script>
@endsection