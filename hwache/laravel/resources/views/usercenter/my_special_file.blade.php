@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
			<h2 class="title psr">
                        <span>提车所需文件格式下载</span>
                    </h2>
                    <div class="content-wapper">
                       
                        <table class="tbl">
                            <tr>
                                <th>文件名称</th>
                                <th>说明</th>
                                <th>操作</th>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">提车委托书</p>
                                </td>
                                <td>
                                    <p class="p fs14">订单号：123432</p>
                                </td>
                                <td>
                                    <a href="#"  class="juhuang tdu">下载</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">授权书</p>
                                </td>
                                <td>
                                    <p class="p fs14">订单号：123432</p>
                                </td>
                                <td>
                                    <a href="#"  class="juhuang tdu">下载</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">交车确认书</p>
                                </td>
                                <td>
                                    <p class="p fs14">订单号：123432</p>
                                </td>
                                <td>
                                    <a href="#"  class="juhuang tdu">下载</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">交车宝典</p>
                                </td>
                                <td>
                                    <p class="p fs14">交车的注意事项</p>
                                </td>
                                <td>
                                    <a href="#"  class="juhuang tdu">下载</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="p fs14">代付说明</p>
                                </td>
                                <td>
                                    <p class="p fs14">订单号：123432，用于支付银行卡非本人时</p>
                                </td>
                                <td>
                                    <a href="#"  class="juhuang tdu">下载</a>
                                </td>
                            </tr>

                            
                        </table>

                        <br><br><br><br>
                      

                        

                    </div> 
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection