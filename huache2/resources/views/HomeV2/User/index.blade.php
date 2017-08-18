@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')

<div class="user-content">
        <div class="user-info-wrapper">
            <div class="user-img-wrapper">
                <div class="user-img-bg user-img-bg-empty">
                  @if(getUserPhotoDefault($user->img_url) == '/themes/images/user-upload-def.png')
                    <img src="{{ asset('webhtml/user/themes/images/user-empty.png') }}" alt="" class="user-img">
                  @else
                    <img src="{{getUserPhotoDefault($user->img_url)}}" alt="" class="user-img">
                  @endif
                    <p class="user-edit-wrapper"><a href="{{ route('user.info') }}">编辑头像</a></p>
                </div>
            </div>
            <div class="user-info">
                <p>欢迎您登上<span class="juhuang">
                {{ getMemberName(Auth::user()->id,Auth::user()->phone) }}</span>号华车！</p>
                <div class="user-control">
                    <a href="{{ url('/') }}" class="btn btn-s-md btn-danger btn-auto">>> 去买车</a>
                    <span class="ml150">可用余额：
                        @if(isset($account))
                            @if($account->avaliable_deposit < 0)
                            -￥{{ -($account->avaliable_deposit) }}
                            @else
                            ￥{{ $account->avaliable_deposit }}
                            @endif
                        @else
                           0
                        @endif
                    </span>
                    <a href="{{ route('pay.online') }}" class="btn btn-s-md btn-danger btn-auto ml50">>> 去充值</a>

                </div>
            </div>
            <div class="clear"></div>
        </div>

        <h2 class="blue-title pb0 psr">
            <span class="blue">我的订单</span>
            <a class="title-right psa juhuang" href="{{ route('my.order') }}">查看全部订单</a>
        </h2>
        <div class="content-wapper">
            @if(isset($orders))
            <table class="tbl">
                <tr>
                    <th width="152">订单号/时间</th>
                    <th width="250">车型规格</th>
                    <th width="86">华车车价</th>
                    <th width="87">买车担保金</th>
                    <th width="124">订单状态</th>
                    <th width="57">操作</th>
                </tr>

                @foreach($orders as $key => $item)
                <tr>
                    <td>
                        <div class="psr">
                            <p class="p">{{ $item->order_sn }}</p>
                            <p class="p psr">{{ $item->created_at }}</p>
                        </div>
                    </td>
                    <td>
                        <p class="p">{{ $item->gc_name }}</p>
                    </td>
                    <td>
                        <p class="p">￥{{ $item->hwache_price }}</p>
                    </td>
                    <td>
                        <p class="p">￥{{ number_format($item->sponsion_price,2) }}</p>
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
            @else 
                <div class="empty-car tac"><span>这里都是空空的，快去挑选您的心仪座驾吧~</span><a href="/" class="juhuang ml50">去看看</a></div>
            @endif
        </div>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-index", "/webhtml/user/js/module/common/common"],function(v,u,c){
            @if(isset($orders))
                @foreach($orders as $key => $item)
                u.init("{{'time_'.$key}}",'{{ \Carbon\Carbon::now() }}','{{ \Carbon\Carbon::parse($item->updated_at)->toDateTimeString() }}',function(){
                    $('{{'#check_'.$key}}').text('付款超时');
                    $('{{'#check_'.$key}}').attr('href','');
                    $('{{'#djs_'.$key}}').html('');
                    $('{{'#time_'.$key}}').html('');
                })
                @endforeach
            @endif
        })
    </script>
@endsection