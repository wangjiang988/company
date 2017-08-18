@extends('_layout.base_dealercenter')
<?php $title="执行中订单列表";?>
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                    <form action="">
                        <div class="search-panel-main mt10">
                            <span class="fl mt5">订单号：</span>
                            <input placeholder="" type="text" value="" name="order_sn" class="form-control custom-control ml5 fl">

                            <span class="fl mt5 ml10">客户手机号：</span>
                            <input  style="width:430px!important;"  placeholder="" type="text" value="" name="phone" class="form-control custom-control ml5 fl">
                            <span class="ml10 fl">&nbsp;</span>

                            <div class="clear"></div>
                        </div>
                        <div class="search-panel-opt ">
                            <table>
                                <tr>
                                    <td valign="top" width="62"><b class="blue"></b></td>
                                    <td>
                                        <div class="opt-wrapper">

                                           {{csrf_field()}}
                                           <input type="hidden" value="active" name="active">
                                            <div class="btn-group btn-jquery-event fl">
                                                <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>品牌不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select brand">
                                                    <input type="hidden" name="brand">
                                                    <li class="active"><a><span>品牌不限</span></a></li>
                                                    @if($brand->count()>0)
                                                    @foreach($brand as $car_brand)
                                                    <li data-type="2"><a><span>{{$car_brand->car_brand}}</span></a></li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>

                                            <div class="btn-group btn-jquery-event ml20 fl">
                                                <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>车系不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chexi">
                                                    <input type="hidden" name="series">
                                                    <li class="active"><a><span>车系不限</span></a></li>
                                                </ul>
                                            </div>

                                            <div class="btn-group btn-jquery-event ml20 fl" >
                                                <button style="height:33px;width: 360px;" data-toggle="dropdown" class="btn btn-select btn-select-long btn-default btn-380">
                                                    <span class="dropdown-label"><span>车型规格不限</span></span>
                                                    <span class="caret" style="top:15px;position: absolute;"></span>
                                                </button>
                                                <ul style="min-width: 360px;" class="dropdown-menu dropdown-select dropdown-380 guige">
                                                    <input type="hidden" name="guige">
                                                    <li class="active"><a><span>车型规格不限</span></a></li>
                                                </ul>
                                            </div>

                                            <div class="clear"></div>
                                            <br>
                                            <div class="btn-group btn-jquery-event fl order_state">
                                                  <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                                      <span class="dropdown-label"><span>订单状态不限</span></span>
                                                      <span class="caret"></span>
                                                  </button>
                                                  <ul class="dropdown-menu dropdown-company">
                                                      <input type="hidden" name="order_state">
                                                      <li data-state="" class="active"><a><span>订单状态不限</span></a></li>
                                                      <li data-state="feedback"><a><span>反馈订单</span></a></li>
                                                      <li data-state="ready"><a><span>准备订单</span></a></li>
                                                      <li data-state="reserva"><a><span>预约交车</span></a></li>
                                                      <li data-state="delivery"><a><span>交车执行</span></a></li>
                                                  </ul>
                                            </div>
                                            <div class="btn-group btn-jquery-event ml20 fl">
                                                <input placeholder="可输入详细状态关键词" name="keyword" type="text" style="width:216px!important;" class="form-control custom-control ml5">
                                            </div>
                                            <div class="btn-group btn-jquery-event ml20 fl rend">
                                                <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle  dropdown-company">
                                                    <span class="dropdown-label"><span>反馈不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select  dropdown-company">
                                                    <input type="hidden" name="feedback">
                                                    <li data-type="" class="active"><a><span>反馈不限</span></a></li>
                                                    <li data-type="rend"><a><span>待反馈</span></a></li>
                                                    <li data-type="norend"><a><span>无需反馈</span></a></li>
                                                </ul>
                                            </div>

                                            <div class="btn-group btn-custom-event mt20 fl">
                                                <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle  dropdown-company">
                                                    <span class="dropdown-label"><span>经销商不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select  dropdown-company">
                                                    <input type="hidden" name="dealer">
                                                    <li class="active"><a><span>经销商不限</span></a></li>
                                                    @if($dealers->count())
                                                    @foreach($dealers as $dealer)
                                                    <li><a><span>{{$dealer->d_name}}</span></a></li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>

                                            <span style="display: none;" class="fl ml20 tac span-guided mt20 zhidaojia">厂商指导价：</span>
                                            <a href="javascript:;" class="btn btn-danger fs16 btn-small submit-search ml10 fl" style="margin-top: 20px!important;">查找</a>
                                            <a href="javascript:window.location.reload()" class="btn btn-danger fs16 btn-small sure  ml10 fl" style="margin-top: 20px!important;">重置</a>
                                            <div class="clear"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <h4 class="title"><span>订单信息</span></h4>
                    @include('dealer.orders._layout.active_content')
                    </form>

@endsection

@section('js')
    <style>
        .pageinfo * {
            float: none;
            margin-right: 0px;
        }
    </style>
    <script src="/js/sea.js"></script>
    <script src="/js/config.js"></script>
    <script type="text/javascript">
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.order.jquery", "module/common/common", "bt"],function(a,b,c){

        });
    </script>
@endsection

