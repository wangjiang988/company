@extends('HomeV2._layout.base2')
@section('css')
<?php $arr='查看协商记录';?>
<link href="{{asset('webhtml/order/themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
   @include('_layout.nav')
@endsection
@section('content')
  <div class="container m-t-86 psr">
        <div class="step pos-rlt">
             <p class="order-head-status">
                 <span class="ml5 blue fs18">协商记录</span>
             </p>

        </div>
    </div>

    <div class="container pos-rlt content">
         <div class="xs-hd wp90">
            <ul class="ml30">
                <li>1.发起协商</li>
                <li>2.协商中</li>
                <li class="cur">3.完成协商</li>
                <div class="clear"></div>
            </ul>
        </div>

        <div class="clear"></div>
        <div class="wapper has-min-step">
            <div class="clear m-t-10"></div>
            <form action="/user/modifyxzj">
                <table  class="tbl tbl-blue tbl-xzj">
                    <tr>
                        <th width="130"><b>品牌</b></th>
                        <th width="150"><b>名称</b></th>
                        <th width="120"><b>已订件数</b></th>
                        <th width="130"><b>希望件数减少为</b></th>
                        <th width="100"><b>协商结果</b></th>
                        <th width="150"><b>售方确认时间</b></th>
                    </tr>
                    @foreach($result->sortByDesc('xzj_type') as $data)
                    <tr>
                @if(!$data->xzj_brand)
                    <td><p class="tac">原厂</p></td>
                @else
                    <td><p class="tac">{{$data->xzj_brand}}</p></td>
                @endif
                        <td><p class="tac">{{$data->xzj_title}}</p></td>
                        <td><p class="tac">{{$data->old_num}}</p></td>
                        <td><p class="tac">{{$data->edit_num}}</p></td>
                        <td><p class="tac">
                        @if($data->is_install == 1)
                        <span class="green fs16">
                        已同意
                        </span>
                        @else
                        <span class="red">
                        未同意
                        </span>
                         @endif
                        </p></td>
                        <td><p class="tac">{{$data->created_at}}</p></td>
                    </tr>
                    @endforeach
                </table>
            </form>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <table>
                <tr>
                    <td width="80" valign="top"><span>温馨提示：</span></td>
                    <td>
                        <p class="fs14">1.售方已同意的项目，已按您的希望数量更新已订购数量。</p>
                        <p class="fs14">2.未尽事宜建议您与售方代表（{{$order->orderMember->member_truename}}：{{$order->orderMember->member_mobile}}）保持沟通。</p>
                    </td>
                </tr>
            </table>

            <div class=" tac psr center mt50" >
                <a href="javascript:history.go(-1)" class="btn btn-s-md btn-danger fs18 sure ml50">返回</a>
            </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection
@section('js')
<script src="{{asset('/webhtml/order/js/sea.js')}}"></script>
<script src="{{asset('/webhtml/order/js/config.js')}}"></script>
    <script type="text/javascript">
    seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/order/js/module/order/order-wait",  "/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection