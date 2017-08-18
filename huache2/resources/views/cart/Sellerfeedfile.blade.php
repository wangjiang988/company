@extends('HomeV2._layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('themes/bootstrap.css') }}"/>
  <link href="{{asset('/webhtml/common/css/common.css')}}" rel="stylesheet" />
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('HomeV2._layout.header2')
@endsection
@section('content')

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
                    <small>选择产品</small>
                    <i></i>
                    <small>付诚意金</small>
                    <i></i>
                    <small class="juhuang" class="">售方确认</small>
                </div>
            </div>
        </div>
    </div>
    <div class="container pos-rlt content">
         @include('cart.branch.shellfragment')

            <hr class="dashed">
            <div class="wait-order-status" ms-controller="orderStatus">
            <form action="{{route('cart.accept')}}" method="post" id="step-c3">
            {!!csrf_field()!!}
                <input type="hidden" name="id" value="{{$order->id}}">
                <p class="fs14"><b>您所关心的特别文件办理事项，经销商在{{$order['created_at']}}作了如下回复，请选择您要办理的内容：</b></p>
                @if(isset($result['car']['ziliao']))
                @foreach($result['car']['ziliao'] as $key=>$value)
                <div class="zm-select-wrapper">
                    <div class="checkbox-wrapper psr fs14 inline-block fs14">
                        <label class="mt">
                        <input   @if($value['ok'] == 'N')
                         disabled=""
                         @endif type="checkbox" checked="" name="ziliao[{{$key}}][title]" id="" value="{{$value['title'].'|'.$value['day'].'|'.$value['fee']}}">

                         </label>
                    </div>
                    <span class="fs14 ml5">{{$value['title']}}证明：
                    @if($value['ok'] == 'Y')
                        可以办理，办理费用：人民币{{$value['fee']}}元，
                        办理将延后交车时限：{{$value['day']}}个自然日
                     @else
                         恕无法办理
                     @endif
                        </span>
                </div>
                 @endforeach
                 @endif
                </form>
                <form action="{{route('cart.end')}}" method="post" id="stopOrder">
                {{csrf_field()}}
                 <input type="hidden" name="id" value="{{$order['id']}}">
                </form>
                <p class="fs14">
                    <span>温馨提示：请在24小时内确认，超过时间未确认将默认为终止订单、退诚意金（无歉意金补偿）操作。</span>
                    <p class="tac red hide" id="showhide">请选择是否继续订单！</p>
                    <br><br>
                    <div class="time">
                        <div class="jishi jishi-long countdown">
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
                </p>
                <br>
                <p class="center psr">
                    <a @click="send" href="javascript:;" class="btn btn-danger fs16 btn-auto">就办这些，继续订单</a>
                    <a @click="stopOrder" href="javascript:;" class="btn btn-danger fs16 btn-auto btn-white btn-float">不买了，退诚意金</a>

                </p>

                <div id="stopWin" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac succeed error constraint">
                               <span class="tip-tag bp0"></span>
                               <span class="tip-text mt10">确定放弃订单吗？</span>
                               <div class="clear"></div>
                               <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" @click="doStopOrder" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
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
                               <span class="tip-text fs14 text-left inline-block">确定要按您的已选内容办理并继续订单吗？</span>
                               <div class="clear"></div>
                               <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","module/item/item-wait","module/item/item-step-c3", "module/common/common", "bt"],function(u,a,b,c){
            a.init('{{date('Y-m-d H:i:s',time())}}','{{$order->rockon_time}}',function(){
                //设置回调
            })
        })
    </script>
@endsection

