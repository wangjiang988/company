@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('HomeV2._layout.not_login') @endsection
@section('content')

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">找回密码</div>
                <ul>
                    <li>1.填写账号</li>
                    <li class="cur">2.验证身份</li>
                    <li>3.重置密码</li>
                    <li>4.完成</li>
                    <div class="clear"></div>
                </ul>
                {!! Form::open(['url'=>route('answer.mobile_verify'),'id'=>'password-step-3','role'=>'form']) !!}
                    {!! Form::hidden('type','phone') !!}
                    {!! Form::hidden('name',$name) !!}
                    <div class="form tac" v-show="errorCount!=10">
                        <br>
                        <table v-cloak class="reg-form-tbl wauto">
                            @if($isOrder >0)
                            <tr class="text-gray">
                                <td align="right" valign="top" width="">
                                     <span class="red">*</span><span class="ml5">您最近购买过的车型</span>
                                </td>
                                <td width="">
                                   <div class="psr">
                                     <select :class="{select:true, ml10:true,'error-bg':brandId==0 && isSendLoading}"  v-model="brandId" >
                                        <option value="0" class="c999" >请选择品牌</option>
                                        <option v-for="brand in brandList" :value="brand.gc_id">@{{brand.gc_name}}</option>
                                     </select>
                                     <select :class="{select:true, ml10:true,'error-bg':carSeriesId==0 && isSendLoading}" v-model="carSeriesId">
                                        <option value="0" class="c999">请选择车系</option>
                                        <option v-for="series in seriesList" :value="series.gc_id">@{{series.gc_name}}</option>
                                     </select>
                                     <span class="ml20 red psa wp100 buy-cx-error" v-show="isSelectModel" v-cloak>请选择您购买过的一个车型~</span>
                                     <span class="ml20 gray psa wp100 buy-cx-error" v-show="!isSelectModel && modelId" v-cloak>厂商指导价：@{{price}}</span>
                                     <div class="clear"></div>
                                     <select @change="getPrice" :class="{'mt5':true,'select-long':true, ml10:true,'error-bg':modelId==0 && isSendLoading}" name="brand_id" v-model="modelId">
                                        <option value="0" class="c999">请选择车型</option>
                                        <option v-for="model in modelsList" :value="model.gc_id">@{{model.gc_name}}</option>
                                     </select>

                                   </div>

                                </td>
                            </tr>
                            @endif
                            <tr class="text-gray">
                                <td align="right" valign="top">
                                     <span class="red">*</span><span class="ml5">您身份证号最后四位</span>
                                </td>
                                <td>
                                   <div class="psr">
                                   <input @focus="resetInputError" maxlength="4" v-model="cardNum" :value="cardNum" type="text" name="id_cart" :class="{'card-last':true, ml10:true,'error-bg':cardNum=='' && isSendLoading}"  placeholder="4位数字或字母，字母请大写~" />
                                   <span class="ml20 red psa buy-num-error" v-show="isInput" v-cloak>请输入您身份证号最后4位~</span>
                                   </div>
                                </td>
                            </tr>
                            <tr class="text-gray">
                              <td  colspan="2">
                                 <p class="fs12 tip-wx">温馨提示：为了您的账户安全，验证账户问题须全部答对，方能重置密码哦～</p>
                              </td>
                            </tr>
                        </table>
                        <div class="text-center " >
                            <div class="countdown  hide" :class="{'inline-block':isShow}">
                                <div class="time-wrapper" v-cloak>
                                    <span>@{{time.minites[0]}}</span>
                                    <span>@{{time.minites[1]}}</span>
                                    <span class="symbol"><span>:</span></span>
                                    <span>@{{time.seconds[0]}}</span>
                                    <span>@{{time.seconds[1]}}</span>
                                    <div class="clear"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                            <button :disabled="isLoading" @click="send" type="button" class="btn btn-s-md btn-danger inline-block btn-next">下一步</button>
                            <div class="clear"></div>
                            <br>
                            <p class="red" v-show="answerError && errorCount <=6" v-cloak>验证问题未全部答对，对不起，请重新回答～</p>
                            <p class="red" v-show="answerError && errorCount >6" v-cloak>验证问题未全部答对，您还有@{{totalCount-errorCount}}次回答机会～</p>
                        </div>

                    </div>
                    <div class="form"v-show="errorCount==10">
                       <table v-show="errorCount == 10"  v-cloak class="reg-form-tbl w620">
                            <tr>
                                <td width="100" align="right" valign="middle">
                                    <span class="tag-info"></span>
                                </td>
                                <td class="text-gray"><br>
                                     <p>因输错次数过多，手机号{{ changeMobile($name) }}找回密码功能已被保护，</p>
                                     <p>请半小时后再试～</p>
                                </td>
                            </tr>
                            <tr>
                               <td colspan="2" class="text-center">
                                  <a href="{{ route('pwd.showResetForm') }}" class="btn btn-s-md btn-danger inline-block btn-next">确 定</a>
                               </td>
                             </tr>
                        </table>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        <br><br><br>


    </div>
@endsection
@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')


@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/pwd/password-step-2-2","bt"],function(a,b,c){
            b.init('{{$start_date}}','{{$end_date}}','{{ route('time_out.freeze')}}');
            b.initValiteOption({{$isOrder}});
            b.initUrl("{{ route('pwd.getReset') }}","{{ route('pwd.pwdOver') }}");
        })
    </script>
@endsection