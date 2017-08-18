@extends('HomeV2._layout.user_base2')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="container m-t-86 pos-rlt content ">
        <div class="wapper has-min-step content-wapper">
            <p class="blue">提现路线 > 维护开户行信息</p>
            <div class="box box-border top-border p10">
                {!! Form::open(['url'=>route('Withdrawal.Bank',$find->id),'role'=>'form','id'=>'form']) !!}
                    <table class="noborder-tbl wp100">
                        <tr>
                            <td width="150" align="right" valign="middle">
                                <span class="ml5 fr mt5 fs14 weight">开户行：</span>
                                <span class="ml5 fr mt5 red">*</span>
                                <div class="clear"></div>
                            </td>
                            <td width="600" class="bank-area">
                                <province-city ref="pc" v-on:receive-params="getArea" def-value="请选择·开户地区" is-select-province="false"></province-city>
                                <div class="form-group psr pdi-control fs14  m-t-10 ml10">
                                    <input @focus="initBankInfo" @blur="checkBankInfo" v-model="bankInfo" type="text" name="bank_address" placeholder="开户行请填完整，如：招商银行苏州分行干将路支行" class="form-control form-control-large" :class="{'w125':true,'error-bg':isBankInfo}">
                                    <span class="edit es-edit"></span>
                                    <p class="hide inputerror juhuang m-t-10 ml20">请正确输入开户行</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="fs14">
                                <p><b>账  号：</b></p>
                            </td>
                            <td>
                                <p class="fs14">
                                    {!! Form::hidden('bank_code',$find->bank_code) !!}
                                    <span v-cloak>{{splitBrank('<?=$find->bank_code?>')}}</span>

                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="fs14">
                                <p><b>户  名：</b></p>
                            </td>
                            <td>
                                <p class="fs14">{{ $find->bank_register_name }}</p>
                            </td>
                        </tr>
                    </table>
                    {!! Form::hidden('id',$find->bank_id) !!}
                {!! Form::close() !!}

                <p class="tac red hide" :class="{show:isError}">开户行信息务必完整哦！</p>
                <p class="mt20 tac">
                    <a @click="send" href="javascript:;" class="btn btn-danger">确定</a>
                    <a href="javascript:history.go(-1);" class="btn btn-danger sure ml50">返 回</a>
                </p>

                <div class="m-t-10"></div>
                <div class="m-t-10"></div>
                <div class="m-t-10"></div>

            </div>
        </div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-maintain-bank", "/webhtml/user/js/module/common/common"],function(v,u,c){
            u.initData('{{$find->province}}','{{$find->city}}','{{$find->bank_address}}')
        })
    </script>
@endsection