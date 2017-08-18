@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p><img src="{{ asset('webhtml/common/images/jxb.gif') }}" /></p>
            <div class="box box-border top-border p10">
                <div class=" fs14 mt10">
                    <span>订单号：</span>
                    <drop-down @receive-params="getOrderSn" :list="dropList" def-value="全部" id="orderSn" name="orderSn" class="nofl" class-name="btn-auto dropdown-method"></drop-down>
                    <span class="ml50">冻结中的金额：￥{{ number_format($payTotal,2) }}</span>
                </div>
                <div class="m-t-10"></div>
                <p class="blue nomargin">冻结解冻详情</p>
                <div class="split-blue"></div>
                <div class="clear"></div>

                <table class="tbl mt20 tbl-gray-border">
                    <tr>
                        <th><span class="noweight">发生时间</span></th>
                        <th><span class="noweight">订单号</span></th>
                        <th><span class="noweight">冻结/解冻</span></th>
                        <th><span class="noweight">冻结来源</span></th>
                        <th><span class="noweight">解冻去向</span></th>
                        <th><span class="noweight">冻结增减金额</span></th>
                        <th><span class="noweight">冻结余额</span></th>
                    </tr>
                    <tbody class="fs14" v-cloak>
                    @if($pays->total() >0)
                        @foreach($pays as $key => $item)
                    <tr>
                        <td>
                            @if($key ==0)
                            <div class="time-tag psr">
                                <span></span>
                                <p class="p fs14">{{ $item->created_at }}</p>
                            </div>
                             @else
                                <p class="p fs14">{{ $item->created_at }}</p>
                             @endif
                        </td>
                        <td>
                            <p class="p fs14">@if($item->order_id>0) {{ $item->order_id }} @endif</p>
                        </td>
                        <td>
                            <p class="p fs14">@if($item->type==10)冻结@else解冻@endif</p>
                        </td>
                        <td>
                            <p class="p fs14">
                                @if($item->type==10)
                                    {{ $item->item }}
                                @endif
                            </p>
                        </td>
                        <td>
                            <p class="p fs14">
                                @if($item->is_freeze==20)
                                    {{ $item->item }}
                                @endif
                            </p>
                        </td>
                        <td>
                            <p class="p fs14">{{ $item->type==10?'-':'+' }} ￥{{ number_format($item->money,2) }}</p>
                        </td>
                        <td>
                            <p class="p fs14">￥{{ number_format($item->freeze_avaiable,2) }}</p>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7">
                            <p class="tac mt10 fs14">客官，加信宝中暂无记录～</p>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
                <div class="wp100 mauto tac">
                    <div class="pageinfo  wauto inline-block">
                        {{ $pays->render() }}
                    </div>
                    <div class="clear"></div>
                </div>


                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <p class="mt20 tac">
                    <a href="javascript:history.go(-1);" class="btn btn-danger sure ml50">返回</a>
                </p>

                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>



            </div>
        </div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-view-jxb", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //初始化订单号列表
            u.initDropList([{id:1,name:'全部'},{id:2,name:'9872322'},{id:3,name:'98723227824'}])

        })
    </script>
@endsection