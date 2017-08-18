@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
       <div class="content-wapper ">
            <div id="vue">
                <h2 class="title weighttitle">车源锁定</h2>
                <div class="m-t-10"></div> 
                <div class="row">
                    <div class="col-sm-7">
                        {{$carmodel->parent->gc_name}} <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>
                        {{$carmodel->gc_name}}  <i class="glyphicon glyphicon-menu-right" aria-hidden="true"></i>
                        {{$carmodel->staple_name}}
                    </div>
                    <div class="col-sm-2">
                         ￥ {{$carmodel->value}}
                    </div>
                     <div class="col-sm-3">
                       正在锁定车源 : <span class='juhuang'>{{count($list)}}</span>组
                    </div>
                </div>
                <div class="m-t-10"></div> 
                <table class="tbl tbl-time tbl-gray">
                    <tr>
                        <th width="50" class="fs16">组号</th>
                        <th width="150" class="fs16">内部车辆编号</th>
                        <th width="100" class="fs16">经销商</th>
                        <th width="200" class="fs16">车身颜色/内饰颜色</th>
                        <th width="100" class="fs16">报价编号</th>
                        <th width="100" class="fs16">报价状态</th>
                    </tr>
                        <?php $i=1;?>
                        @foreach($list as $key=>$group)
                            @foreach($group as $k=>$item)
                           
                            <tr class="p-gray">
                                @if( $k === 0)
                                <td rowspan="{{$group->count()}}"> <p class="p fs14 tac">{{$i}}</p></td>
                                <td rowspan="{{$group->count()}}">  <p class="p fs14 tac">{{$item->bj_dealer_internal_id}}</p></td>
                                @endif
                                <td>  <p class="p fs14 tac">{{$item->dealer->d_name}}</p></td>
                               
                                <td>  <p class="p fs14 tac">{{$item->bj_body_color}}/ 
                                @if(is_string($item->interior_color))
                                    {{ $item->interior_color }}
                                @endif    
                                </p></td>
                                <td>  <p class="p fs14 tac"><a href="{{route('dealer.baojia',['type'=>'view','id'=>$item->bj_id,'step'=>1])}}" class="juhuang tdu">{{$item->bj_serial}}</a></p></td>
                                <td>  <p class="p fs14 tac">{{show_baojia_status($item)}}</p></td>
                            </tr>
                            @endforeach
                             <?php $i++;?>
                        @endforeach
                </table>
                <br>
                <div class="pageinfo ml200">
                </div>
                <div class="clear "></div>

            </div>
        </div>
@endsection
@section('js')
  <script type="text/javascript">
    seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-withdrawal", "/webhtml/custom/js/module/common/common"],function(v,u,c){
        });
  </script>
@endsection


