@extends('_layout.base_dealer_v2')
@section('css','')

@section('content')
    <div class="custom-content custom-content-with-margin nomargin  psr " v-cloak>
        <p class="mt20">
            <img src="/webhtml/common/images/jxb.gif" class="fl" />
            <span class="ml10 fl mt-2">冻结中的浮动保证金：￥{{ number_format($freezeTotal,2) }}</span>
        </p>
        <div class="clear"></div>
        <hr class="dashed" />


        {!! Form::open(['url'=>route('dealer.funds','pay'),'role'=>'form','method'=>'get']) !!}
        <div class="mt20 psr" >
            <span class="ml50 fl mt5">订单号：</span>
            <input name="sn" value="{{ $search['sn'] }}" @focus="displaySnList" type="text" placeholder="" class="form-control w179 fl" >

            <span class="ml20 fl mt5">冻结金额：</span>
            <drop-down name="status" id="priceStatus" @receive-params="getStatus" :list="priceStatusList" def-value="{{ $search['status'] }}" class-name="btn-dropdown-normal"></drop-down>
            <button type="submit" class="btn btn-s-md btn-danger bt ml50">查找</button>
            <a href="{{ route('dealer.funds','pay') }}" class="btn btn-s-md btn-danger sure bt ml20">重置</a>
        </div>
        {!! Form::close() !!}
        <div class="clear"></div>
        <div class="mauto tac">
            <br><br>

            <table class="tbl tbl-time tbl-gray wp70">
                <tr>
                    <th width="124" class="fs16">订单号</th>
                    <th width="185" class="fs16">订单时间</th>
                    <th width="110" class="fs16">冻结余额</th>
                    <th width="97" class="fs16">操作</th>
                </tr>
                @if($list->total() >0)
                    @foreach($list->items() as $key => $item)
                    <tr>
                        <td>
                            <p class="p fs14">{{ $item->order_sn }}</p>
                        </td>
                        <td>
                            <p class="p fs14">{{ $item->created_at }}</p>
                        </td>
                        <td>
                            <p class="p fs14 tar">￥{{ number_format($item->money,2) }}</p>
                        </td>
                        <td>
                            <p class="p fs14"><a @click="view({{$item->order_id}},{{ $item->money }})" href="javascript:;" class="blue tdu">查看</a></p>
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="4">
                        <div class="mt20"></div>
                        <p class="p fs14 tac">   您还没有订单记录，加油！祝您早日开张！~</p>
                        <div class="mt20"></div>
                    </td>
                </tr>
                @endif
            </table>
            <br>

        </div>


        <div class="pageinfo ml200">
            {{ $list->appends($pageData)->links() }}
        </div>
        <div class="clear "></div>
        <div class="mt20"></div>
        <div class="clear"></div>


        <div class="m-t-10" v-for="i in 5"></div>

        <div id="viewWin" class="popupbox">
            <div class="popup-title">查看冻结余额明细</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <p class="fs14 tac">
                        <br>
                        <div class="tip-text fs14 text-left inline-block">
                    <p class="fs14">订单号：@{{sn}}</p>
                    <table class="tbl tac tbl-time tbl-gray ">
                        <tr>
                            <th width="128" class="fs16">冻结/解冻</th>
                            <th width="185" class="fs16">项目</th>
                            <th width="240" class="fs16">说明</th>
                            <th width="125" class="fs16">冻结增减金额 </th>
                        </tr>
                        <tr v-for="view in viewList">
                            <td>
                                <p class="p fs14">@{{view.status}}</p>
                            </td>
                            <td>
                                <p class="p fs14">@{{view.project}}</p>
                            </td>
                            <td>
                                <p class="p fs14">@{{view.info}}</p>
                            </td>
                            <td>
                                <p class="p fs14">
                                    <span class="fl" v-if="view.isNegative">-</span>
                                    <span class="fl" v-if="!view.isNegative">+</span>
                                    <span class="fr">@{{formatMoney(view.price,2,"￥")}}</span>
                                </p>
                                <div class="clear"></div>
                            </td>
                        </tr>
                        <tr v-show="viewList.length==0">
                            <td colspan="4">
                                <div class="mt20"></div>
                                <p class="p fs14 tac">   您还没有订单记录，加油！祝您早日开张！~</p>
                                <div class="mt20"></div>
                            </td>
                        </tr>
                    </table>
                    <p class="tar fs14">
                        冻结余额：@{{formatMoney(money,2,"￥")}}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </p>
                </div>
                <div class="clear"></div>
                <br>
                </p>
                <div class="m-t-10"></div>
            </div>
            <div class="popup-control">
                <a href="javascript:;" @click="closeView" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                <div class="clear"></div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js') }}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-treasure", "/webhtml/custom/js/module/common/common"],function(v,u,c){
            u.initUrl("{{ route('dealer.funds','freeze_detail') }}");
        })
    </script>
@endsection