@extends('HomeV2._layout.base2')
@section('css')
<link href="{{asset('/webhtml/user/themes/user.css')}}" rel="stylesheet" />
@endsection
@section('content')
    <div class="container m-t-86 pos-rlt content">
        <div class="wapper has-min-step">
            <div class="box box-border top-border">
                <div class="goods-list-wrapper">
                    <h1 class="text-center goods-list-title"> 原厂选装精品列表</h1>
                    <p>{{$gc_name[0]}}<span class="ml10">></span>{{$gc_name[1]}}<span class="ml10">></span>{{$gc_name[2]}}</p>
                    <table class="tbl bgtbl wp100">
                        <tr>
                            <th width="180">名称</th>
                            <th>型号/说明</th>
                            <th width="130">厂商指导价</th>
                        </tr>
                        @foreach($xzj['rear'] as $x)
                        <tr>
                            <td>{{$x['xzj_title']}}</td>
                            <td>{{$x['xzj_model']}}</td>
                            <td class="tac">{{formatMoney(<?=$x['xzj_guide_price'];?>,2,"￥")}}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>

            </div>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common"],function(v,u,c){
        })
    </script>
@endsection 


