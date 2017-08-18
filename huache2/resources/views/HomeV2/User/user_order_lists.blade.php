@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')



    <div class="user-content">

        <div class="content-wapper" v-cloak>
            <p class="pre-fix"><span class="ml10 juhuang weight">全部订单</span></p>
            <div class="m-t-10"></div>
            @if(\Auth::user()->userOrder()->count())
            <form action="" method="get">
            <div class="order-search-wrapper">
                <span class="fl mt5">选择时间：</span>
                <drop-down ref="time" id="hftime" name="time" @receive-params="getTime" :list="timeList" def-value="@if(Request::get('time') == 1) 近三个月订单 @elseif(Request::get('time') == 2) 三个月之前 @else全部 @endif" class-name="btn-dropdown-default btn-dropdown-big"></drop-down>
                <span class="fl mt5 ml50">选择品牌：</span>
                <drop-down ref="brand" id="hfbrand" @receive-params="getBrand" :list="brandList" def-value="@if(Request::get('brand')) {{ Request::get('brand') }} @else 全部 @endif" class-name="btn-dropdown-default btn-dropdown-big"></drop-down>
                <input type="hidden" v-model="brand" name="brand" value="{{ Request::get('brand') }}" />
                <button type="button" onclick='submit();' class="btn btn-danger btn-auto fl ml20 nomargin">查找</button>
            </div>
            </form>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            @endif
            @if($list->count()>0 && (\Auth::user()->userOrder()->count()))
            <table class="tbl">
                <tr>
                    <th width="152">订单号/时间</th>
                    <th width="250">车型规格</th>
                    <th width="86">华车车价</th>
                    <th width="87">买车担保金</th>
                    <th width="124">订单状态</th>
                    <th width="57">操作</th>
                </tr>               
                @foreach($list as $item)
                <tr>
                    <td>
                        <div class="psr">
                            <p class="p">{{$item->order_sn}}</p>
                            <p class="p psr">{{$item->created_at}}</p>
                        </div>
                    </td>
                    <td>
                    <?php $gc_name = explode('&gt;', $item->gc_name);?>
                        <p class="p tal ml5">{{$gc_name[0].'>'.$gc_name[1].'>'.$gc_name[2]}} </p>
                    </td>
                    <td>
                        <p class="p tar">￥{{$item->hwache_price}}</p>
                    </td>
                    <td>
                        <p class="p tar">￥{{number_format($item->sponsion_price,2)}}</p>
                    </td>
                    <td>
                    <?php $status = explode('-',$item->orderStatus->member_remark);?>
                    @if(count($status)>1)
                        <p class="p">{{$item->orderStatus->user_progress}} > {{$status[0]}}</p>
                        @if($item->orderComment()->count())
                        <p class="p p-gray">完成评价</p>
                        @else
                        <p class="p p-gray">{{$status[1]}}</p>
                        @endif
                    @else
                       <p class="p">{{$item->orderStatus->user_progress}}</p>
                        <p class="p p-gray">{{$status[0]}}</p>
                    @endif
                    </td>
                    <td>
                        <a href="{{route('cart.editcar', ['id'=>$item->id])}}"  class="btn btn-s-md btn-danger sure tbl-control btn-70 btn-auto">查看</a>
                    </td>
                </tr>
                @endforeach
            </table>
             @elseif(\Auth::user()->userOrder()->count())
             <table class="tbl">
                <tr>
                    <th width="152">订单号/时间</th>
                    <th width="250">车型规格</th>
                    <th width="86">华车车价</th>
                    <th width="87">买车担保金</th>
                    <th width="124">订单状态</th>
                    <th width="57">操作</th>
                </tr>     
                <tr>
                    <td colspan="6">
                        <p class="tac mt10">客官，找不到您要搜索的订单，请更换查找条件试试哦~</p>
                    </td>
                </tr>
            </table>          
            @else
            <div style="background-position: 0 50%;" class="empty-car ml200">这里都是空空的，快去挑选您的心仪座驾吧~<a href="/" class="juhuang ml20">去看看</a></div>
            @endif
            <div class="tac">{{ $list->appends(['time' => Request::get('time'),'brand' => Request::get('brand')])->links() }}</div>
            <div v-for="i in 3" class="m-t-10"></div>


        </div>

    </div>




@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('js')

    <script src="{{asset('/webhtml/user/js/sea.js')}}"></script>
    <script src="{{asset('/webhtml/user/js/config.js')}}"></script>
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-order", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.initBrandList(
                {!!json_encode($brand) !!}
                )
            u.initDefValue('{{Request::get('time')}}','{{Request::get('brand')}}')
            $(".slide").height("757")
        })
    </script>
@endsection


