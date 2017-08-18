@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
			<h2 class="title psr">
                        <span>我的提现</span>
                    </h2>
                    <div class="content-wapper">
                        <br><br><br><br>
                        <div class="tac">您暂时还未进行任何的退款提现操作~</div>
                        <br><br><br><br><br><br>

                        <table class="tbl">
                            <tr>
                                <th>退款提现发起时间</th>
                                <th>提现金额</th>
                                <th>退款金额</th>
                                <th>退款方式</th>
                                <th>状态</th>
                                <th>退款时间</th>
                                <th>操作</th>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-12-1</p>
                                    <p class="p fs14">10：23：12</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥400.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥388.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">线上原路退回</p>
                                </td>
                                <td>
                                    <p class="p fs14">正在办理</p>
                                </td>
                                <td>
                                    <p class="p fs14">/</p>
                                </td>
                                <td>
                                    <a href="#"  class="btn btn-s-md btn-danger sure tbl-control">申请</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">2016-12-1</p>
                                    <p class="p fs14">10：23：12</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥400.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">￥388.00</p>
                                </td>
                                <td>
                                    <p class="p fs14">线上原路退回</p>
                                </td>
                                <td>
                                    <p class="p fs14">正在办理</p>
                                </td>
                                <td>
                                    <p class="p fs14">2015-10-15</p>
                                </td>
                                <td>
                                    <a href="#"  class="btn btn-s-md btn-danger sure tbl-control">申请</a>
                                </td>
                            </tr>

                        </table>


                    </div>
@endsection
@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection