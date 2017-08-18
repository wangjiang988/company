@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
    <form action="" method="get" name='order_form'>
        <div class="order-content">
            <span>全部订单</span>
        </div>
        <div class="order-wapper">
            <span>选择时间：</span>
            <div class="btn-group m-r pdi-drop pdi-drop-warp">
                <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn order-select">
                    <span class="dropdown-label">
                    <span>
                    <?php 
                    	if(!isset($_GET['date']) || $_GET['date']=='all'){
                    		$_GET['date']='all';
                    		echo '全部';
                    	}elseif($_GET['date']=='in_threemonth'){
                    		echo '近三个月订单';
                    	}elseif($_GET['date']=='over_threemonth'){
                    		echo '三个月之前';
                    	}
                    	
                    ?>
                    
                    </span>
                    </span>
                    <span class="caret"></span>
                    <span class="split" style="position: absolute;"></span>
                </div>
                <div class="dropdown-menu dropdown-select area-tab-div w140" style="display: none;">
                    <p class="area-tab"></p>
                    <dl class="dl" style="display: none;">
                        <dd ms-on-click="selectOrderTimeChoose('all')" <?php if($_GET['date']=='all'){ echo 'class="select-order-time"';}?>>全部</dd>
                        <dd ms-on-click="selectOrderTimeChoose('in_threemonth')" <?php if($_GET['date']=='in_threemonth'){ echo 'class="select-order-time"';}?>>近三个月订单</dd>
                        <dd ms-on-click="selectOrderTimeChoose('over_threemonth')" <?php if($_GET['date']=='over_threemonth'){ echo 'class="select-order-time"';}?>>三个月之前</dd>
                        <div class="clear"></div>
                    </dl>
                </div>
                <input type="hidden" name="date" value="">
            </div>
        </div>

        <div class="content-wapper">
            @if(!empty($orderList))
                <table class="tbl">
                    <tr>
                        <th width="160px">订单号/时间</th>
                        <th width="200px">车型规格</th>
                        <th width="100px">华车车价<br>（含服务费）</th>
                        <th width="90px">买车担保金</th>
                        <th width="95px">订单状态</th>
                        <th width="105px">操作</th>
                    </tr>
                    @foreach ($orderList as $k => $v)
                        <tr>
                            <td>{{$v->order_num}}
                                <p class="p psr">{{$v->created_at}}</p>
                            </td>
                            <td>
                                <?php
                                $tmpBrandArr = explode(" &gt;",$v->car_name);
                                echo '<p class="p">'.$tmpBrandArr[0].'</p>';
                                echo '<p class="p">'.$tmpBrandArr[1].'</p>';
                                echo '<p class="p">'.$tmpBrandArr[2].'</p>';
                                ?>
                            </td>
                            <td>{{$v->bj_price}}<br>（含服务费）</td>
                            <td>
                                <p class="p">￥{{ $price[$v->id]['doposit']  }}</p>
                                <p class="p">已付：<span class="juhuang">￥{{ $price[$v->id]['paidMoney']  }}</span></p>

                            </td>
                            <td>
                            @if($v->cart_status<=5 || $v->cart_status==1000)
                            	{{$lang[$v->cart_sub_status]['notice']}}
                            @else
                            	@if($v->end_user_status>=600)
                            		{{$lang[$v->end_user_status]['notice']}}
                            	@endif
                            @endif
                            </td>
                            <td>
                                <a href="/getmyorder/{{$v->order_num}}" target="_blank" class="btn btn-s-md btn-danger sure tbl-control">查看订单</a>
                                <p class="p mt5"><b>倒计时：</b></p>
                                <p class="p">23：12：11</p>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div class="pageinfo ml150">
                    {!! $orderList->appends(['date'=>$_GET['date']])->render() !!}
                </div>
            @else
                <div class="empty-car">你最近没有订单，这里都是空空的，快去挑选您中意的车吧！去看看</div>
            @endif

        </div>

    </form>
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
    </script>
@endsection