@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
<div class="user-content">

    <h2 class="blue-title pb0 psr">
        <span class="blue">文件下载</span>
    </h2>
    <div class="content-wapper">
        <table class="custom-info-tbl noborder wp100">
            <tr>
                <td class="tar" width="90">订单号：</td>
                <td class="tal">
                    <input placeholder="请输入" type="text" value="" name="" class="form-control ml5 w300 inline-block">
                    <a href="javascript:;" class="btn btn-danger fs14 next ml20 btn-blue" style="margin-top:0;">查 询</a>
                </td>

            </tr>
        </table>
        <br>
        <table class="tbl">
            <tr>
                <th>文件名</th>
                <th>订单号</th>
                <th>说明</th>
                <th>操作</th>
            </tr>
            <tr>
                <td>
                    <p class="fs14 p">交车宝典</p>
                </td>
                <td>
                    <p class="fs14 p"></p>
                </td>
                <td>
                    <p class="fs14 p">交车的注意事项</p>
                </td>
                <td>
                    <a href="#"  class="juhuang tdu">下载</a>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fs14 p">授权书</p>
                </td>
                <td>
                    <p class="fs14 p">123213</p>
                </td>
                <td>
                    <p class="fs14 p"></p>
                </td>
                <td>
                    <a href="#"  class="juhuang tdu">下载</a>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fs14 p">交车确认书</p>
                </td>
                <td>
                    <p class="fs14 p">123213</p>
                </td>
                <td>
                    <p class="fs14 p"></p>
                </td>
                <td>
                    <a href="#"  class="juhuang tdu">下载</a>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fs14 p">提车委托书</p>
                </td>
                <td>
                    <p class="fs14 p">32132</p>
                </td>
                <td>
                    <p class="fs14 p">提车委托书</p>
                </td>
                <td>
                    <a href="#"  class="juhuang tdu">下载</a>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="fs14 p">代付说明</p>
                </td>
                <td>
                    <p class="fs14 p">31321</p>
                </td>
                <td>
                    <p class="fs14 p">用于支付银行卡非本人时</p>
                </td>
                <td>
                    <a href="#"  class="juhuang tdu">下载</a>
                </td>
            </tr>

        </table>

    </div>
    <div class="m-t-10" v-for="i in 15"></div>

</div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min", "/webhtml/user/js/module/common/common"],function(v,u,c){

        })
    </script>
@endsection