@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin marign-top-tab psr">
        @include('HomeV2._layout.myfile_nav')
        <div class="mt10"></div>

        <div class="content-wapper">

            <br><br>
            <h1 class="success-title"><span class="icon-large icon-success-large"></span><span class="inline-block ml20">审核通过</span></h1>
            <br>
            <table class="noborder-tbl wp100 ml100">
                <tr>
                    <td width="150" align="right" valign="middle">
                        <p class="mt10">开户行：</p>
                    </td>
                    <td width="600" class="bank-area">
                        （{{ $bank->province.$bank->city.$bank->district }}）{{ $bank->bank_address }}
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="middle">
                        <p class="mt10">账 号：</p>
                    </td>
                    <td width="600" class="bank-area">
                        {{ chanageStr($bank->bank_code,4,-4) }}
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="middle">
                        <p class="mt10">户 名：</p>
                    </td>
                    <td width="600" class="bank-area">
                        {{ $user->last_name.$user->first_name}}
                    </td>
                </tr>
                <tr>
                    <td class="tal" colspan="2">

                        <p class="tal ml200 mt20"><a href="{{ route('bank.update',['id'=>$bank->id]) }}" class="btn btn-s-md btn-danger fs16 btn-s w150 sure">更   换</a></p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="m-t-10" v-for="i in 5"></div>
    </div>

@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common"],function(v,u,c){


        })
    </script>
@endsection