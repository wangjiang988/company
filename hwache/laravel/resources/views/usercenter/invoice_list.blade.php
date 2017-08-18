@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
					          
                    <h2 class="title psr">
                        <span>我的发票</span>
                    </h2>
                    <div class="content-wapper">
                    @if(count($invoiceList)>0)
                        <table class="tbl">
                            <tr>
                                <th>订单号</th>
                                <th>可开发票金额</th>
                                <th>已开发票金额</th>
                                <th>发票抬头</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            @foreach($invoiceList as $K=>$v)
                            <?php  if($v['invoice_type'] == 1){ continue;} ?>
                            <tr>
                                <td>
                                    <p class="p fs14">{{$v['order_num']}}</p>
                                </td>
                                <?php 
                                if((intval(strtotime($v['updated_at']))+86400*90) > time()){
                                	$style= '';
                                }else{
                                	$style= 'color:#DDD';
                                }
                                ?>
                               
                                <td>
                                    <p class="p fs14" style="{{$style}}">￥2,000.00</p>
                                </td>
                                <td>
                                    <p class="p fs14"></p>
                                </td>
                                <td>
                                    <p class="p fs14">{{$v['inv_title'] or '/'}}</p>
                                </td>
                                <td>
                                <?php 
                                $status = intval($v['invoice_status']);
                                ?>
                                    <p class="p fs14">
                                    	@if((intval(strtotime($v['updated_at']))+86400*90) > time())
                                    		{{$invoiceStatus[$status] or '/'}}
                                		@else
                                			已超时
                                		@endif
                                    
                                    </p>
                                </td>
                                <td>
                                 <!--//大于等于6 标志订单已经进入结算状态-->
                                @if($v['calc_status']==1)
                                	@if(empty($v['invoice_status']))
                                		@if((intval(strtotime($v['updated_at']))+86400*90) > time() || $v['overtime_invoice_status'] == 1)
                                    		<a href="./memberInvoice/{{$v['order_num']}}"  class="btn btn-s-md btn-danger sure tbl-control">申请</a>
                                		@else
                                			<a href="./memberInvoice/{{$v['order_num']}}"  class="btn btn-s-md btn-danger sure tbl-control">查看</a>
                                		@endif
                                	@elseif($v['invoice_status'] == 4)
                                	<a href="./memberInvoice/{{$v['order_num']}}"  class="btn btn-s-md btn-danger sure tbl-control">查看</a>
                                    	@if($v['re_do_status']==1)
                                    		<a href="./memberInvoice/{{$v['order_num']}}?redo=y"  class="btn btn-s-md btn-danger sure tbl-control">申请重开</a>
                                		@endif
                                	@else
                                	 <a href="./memberInvoice/{{$v['order_num']}}"  class="btn btn-s-md btn-danger sure tbl-control">查看</a>
                                	@endif
                                @else
                                	尚未进入结算状态
                                @endif
                                </td>
                            </tr>
                            @endforeach
                            
                        </table>
                          <p class="fs14 mt10 clear">温馨提示：请在结算完成后三个月内申请开票，超时系统将自动关闭开票功能。</p>
						@else
                        <br><br><br><br>
                        <div class="tac">没有结算完成的订单，显示</div>
                        <br><br><br><br><br><br>

                        <br><br><br><br>
                        <div class="tac">您暂无可申请/已申请的发票记录，已结算的订单方可申请发票~</div>
                        <br><br><br><br><br><br>
						@endif
                        

                    </div>
@endsection                
@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection    