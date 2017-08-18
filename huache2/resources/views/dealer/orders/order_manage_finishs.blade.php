@extends('_layout.base_dealercenter')
<?php $title="订单终止列表";?>
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
                            <input placeholder="" type="text" value="" name="phone" class="form-control custom-control ml5 fl" style="width: 150px!important;">
                            <span class="fl mt5 ml10">交易结束原因：</span>
                            <input placeholder="可输入关键词" type="text" value="" name="keyword" class="form-control custom-control ml5 fl">
                            <div class="clear"></div>
                        </div>
                        <div class="search-panel-opt mt20">
                            <table>
                                <tr>
                                    <td valign="top" width="45"><b class="blue">筛选：</b></td>
                                    <td>
                                        <div class="opt-wrapper">

                                            <span class="fl ml20 mt5">结束时间范围：</span>
                                            <div class="form-group psr fl">
                                                <input style="width:160px" type="text" name="str_time" id="realInp" placeholder="" class="form-control " onfocus="WdatePicker({minDate:'2017-01-2 00:00:00',startDate:'2017-02-19 00:00:00' });">
                                                <i class="rili"></i>
                                            </div>
                                            <span class="fl ml10 mt5">~</span>
                                            <div class="form-group psr fl ml10">
                                                <input  style="width:160px" type="text" id="realInp2" name="end_time" placeholder="" class="form-control " onfocus="WdatePicker({minDate:'2017-07-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                                <i class="rili"></i>
                                            </div>
                                            <div class="btn-group btn-custom-event ml20 fl">
                                                <button data-toggle="dropdown" style="width:230px;" class="btn btn-select btn-select-normal btn-default">
                                                    <span class="dropdown-label"><span>经销商不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select" style="min-width:230px;">
                                                    <input type="hidden" name="dealer">
                                                    <li class="active"><a><span>经销商不限</span></a></li>
                                                    @if($dealers->count())
                                                    @foreach($dealers as $dealer)
                                                    <li><a><span>{{$dealer->d_name}}</span></a></li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                            </div>


                                            <div class="clear"></div>

                                            {{csrf_field()}}
                                            <input type="hidden" name="active" value="finishs">
                                            <div class="btn-group btn-jquery-event fl" style="margin-left:-45px;">
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

                                            <div class="btn-group btn-jquery-event  ml20 fl">
                                                <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                    <span class="dropdown-label"><span>车系不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select chexi">
                                                    <input type="hidden" name="series">
                                                    <li class="active"><a><span>车系不限</span></a></li>
                                                </ul>
                                            </div>

                                            <div class="btn-group btn-jquery-event  ml10 fl">
                                                <button data-toggle="dropdown" style="width:246px;" class="btn btn-select btn-default">
                                                    <span class="dropdown-label"><span>车型规格不限</span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select guige" style="min-width:246px;">
                                                    <input type="hidden" name="guige">
                                                    <li class="active"><a><span>车型规格不限</span></a></li>
                                                </ul>
                                            </div>

                                            <span class="ml10 fl">&nbsp;</span>
                                            <a style="margin-top:0px;width:77px!important;" href="javascript:;" class="btn btn-danger fs16 fl submit-search">查找</a>
                                            <a style="margin-top:0px;margin-left:15px;width:77px!important;" href="javascript:window.location.reload()" class="btn btn-danger fs16 btn-small sure btn-auto">重置</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <h4 class="title"><span>订单信息</span></h4>
                       @include('dealer.orders._layout.finishs_content')
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
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.order.jquery", "module/common/common", "vendor/DatePicker/WdatePicker.js","bt"],function(a,b,c){

        });
    </script>
@endsection

