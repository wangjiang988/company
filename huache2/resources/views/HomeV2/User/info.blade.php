@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h2 class="blue-title pb0">个人信息</h2>
        <div class="content-wapper">
            <br>
            @if($user->last_name == null)
            <p class="nomargin"><b>我的姓名：</b><span class="ml20">从此账户只为您服务，服务您一生一世！ </span><span class="ml20">></span>
                <a href="{{ route('auth.addShowIdCart') }}"><span class="ml20 juhuang">去实名认证</span></a>
            </p>
            @elseif($user->is_id_verify ==1)
            <p class="nomargin"><b>我的姓名：</b><span class="ml20">{{ $user->last_name.$user->first_name }}</span>
                <span class="ml50">已实名认证，不可修改</span></p>
            @elseif($user->is_id_verify ==0)
            <p class="nomargin"><b>我的姓名：</b><span class="ml20">{{ $user->last_name.$user->first_name }}</span>
                <span class="ml50">实名认证审核中</span>
                <a href="{{ route('auth.showIdCart') }}"><span class="ml20 juhuang">查看</span></a>
            </p>
            @else
            <p class="nomargin"><b>我的姓名：</b><span class="ml20">{{ $user->last_name.$user->first_name }}</span>
                <span class="ml50">实名认证未通过   </span><span class="ml20">></span>
                <a href="{{ route('auth.addShowIdCart') }}" class="ml20 juhuang">再次认证</a>
            </p>
            @endif
            <br><br>
            <p class="nomargin"><b>注册手机：</b><span class="ml20">{{ changeMobile($user->phone) }}</span>
                <span class="ml50">已验证</span>
                <a href="{{ route('upmobile.seep1') }}"><span class="ml20 juhuang">修改</span></a>
            </p>
            <br><br>
            @if($user->email == null)
            <p class="nomargin"><b>绑定邮箱：</b><span class="ml20">万一手机出状况，邮箱也可挑大梁。绑定一下，有备无患！  </span><span class="ml20">></span>
                <a href="{{ route('email.add') }}"><span class="ml20 juhuang">去绑定</span></a>
            </p>
            @else
            <p class="nomargin"><b>绑定邮箱：</b><span class="ml20">{{ changeEmail($user->email) }}</span>
                <span class="ml50">已绑定，可用于登录</span>
                <a href="{{ route('upemail.seep2') }}"><span class="ml20 juhuang">修改</span></a>
            </p>
            @endif
        </div>
        <br><br>
        <h2 class="blue-title pb0">补充信息</h2>
        <div class="content-wapper psr">
            <br>
            <p class="nomargin" v-cloak v-show="!isSetNick"><b>喜欢别人对我称呼：</b>
                <span class="ml20">听到熟悉的称呼，距离不是问题了 </span><span class="ml20">></span>
                <a @click="setNick" href="javascript:;"class="ml20 juhuang">我要设置</a>
            </p>
            <p class="nomargin" v-cloak v-show="isSetNick"><b>喜欢别人对我称呼：</b><span class="ml20">@{{nickName}}</span><a @click="setNick" href="javascript:;"class="ml50 juhuang">修改</a></p>
            <br><br>
            <p class="nomargin" v-cloak v-show="!isSetAddress"><b>我的地址：</b><span class="ml20">以后可能会收到惊喜哦～ </span><span class="ml20">></span><a @click="setAddress" href="javascript:;"class="ml20 juhuang">我要设置</a></p>
            <p class="nomargin" v-cloak v-show="isSetAddress">
                <b class="inline-block fl">我的地址：</b>
                <span class="inline-block ml20 fl">
                    <span class="">@{{province}}@{{city}}</span><br>
                    <span class="">@{{address}}</span><br>
                    <a @click="setAddress" href="javascript:;"class="juhuang">修改</a>
                </span>
            </p>
            <br><br>

            <div class="clear"></div>
            @if($user->is_id_verify == null  || $user->is_id_verify!=1)
                <p>
                    <b>我的银行账户：</b>
                    <span class="ml20">实名认证身份后，方可绑定本人卡。</span>
                    <span class="ml20">></span>
                    <a @click="authentication" href="javascript:;" class="ml20 juhuang">去绑定</a>
                </p>
            @else

                @if($brank != null)
                    <?php
                        $verifyMsg = ['认证审核中','已成功绑定，可用于提现','审核未通过','已成功绑定，可用于提现','已成功绑定，可用于提现'];
                        $viewMsg = ['查看','更换','重新绑定','更换审核中','更换未成功'];
                        $url = ($brank->is_verify ==1) ? route('bank.update',['id'=>$brank->id]) : route('user.bank');
                    ?>
                    <p><b>我的银行账户：</b><span class="ml20">{{ chanageStr($brank->bank_code,4,-4) }} </span>
                        <span class="ml20">{{ $verifyMsg[$brank->is_verify] }}</span>
                        <a href="{{ $url }}"class="ml20 juhuang">{{ $viewMsg[$brank->is_verify] }}</a>
                    </p>
                @else
                    <p>
                        <b>我的银行账户：</b>
                        <span class="ml20">实名认证身份后，方可绑定本人卡。</span>
                        <span class="ml20">></span>
                        <a href="{{route('user.bank')}}" class="ml20 juhuang"> 去绑定</a>
                    </p>
                @endif
            @endif

            <div class="user-head-wrapper">
                {!! Form::open(['url'=>route('account.upInfo',['type'=>'photo']),'role'=>'form','enctype'=>'multipart/form-data','name'=>"user-img-form"]) !!}
                    <div v-cloak v-show="!isClickUpload" class="user-no-upload">
                        <p><b>我的头像</b></p>
                        <p class="ml20">V</p>
                        <p><a @click="showUpload" href="javascript:;" class="juhuang">我来作主</a></p>
                    </div>
                    <div v-cloak v-show="isClickUpload" class="upload-wrapper">
                        <div class="img-wrapper">
                            <img width="200" height="200" id="upload-img" src="/themes/images/user-upload-def.png" data-src="{{getUserPhotoDefault($user->img_url)}}" alt="">
                            <input @change="previewFile" type="file" name="photo" id="upload-file" class="hide">
                            <p v-cloak v-show="!isSelectFile" class="upload-point" @click="upload">点击上传头像</p>
                        </div>
                        <p class="text-center mt10" v-cloak v-show="isSelectFile && !isUpload" >
                            <a @click="uploadFile" href="javascript:;" class="btn btn-s-md btn-danger btn-auto">保存</a>
                            <a @click="canceUploadFile" href="javascript:;" class="btn btn-s-md btn-danger btn-auto sure ml20">取消</a>
                        </p>
                        <p v-cloak v-show="!isSelectFile" class="fs12 mt5">仅支持JPG、GIF、PNG、JPEG、BMP格式，文件小于5M</p>
                        <a v-cloak v-show="isUpload" class="fs12 mt5 juhuang tdu" @click="uploadAgain" href="javascript:;">更换</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        <br><br><br><br><br><br><br><br>

        <div id="setNick" class="popupbox">
            <div class="popup-title">设置称呼</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="nick-title ml20">请选择您喜欢别人对您的称呼：</p>
                    <p class="fs14  tal">
                    <table class="tbl-nick">
                        <tr>
                            <td width="25%"><input v-model="nickNameTmp" type="radio" name="nick" value="先生"><span class="ml5">先生</span></td>
                            <td width="25%"><input v-model="nickNameTmp" type="radio" name="nick" value="女士"><span class="ml5">女士</span></td>
                            <td width="25%"><input v-model="nickNameTmp" type="radio" name="nick" value="老师"><span class="ml5">老师</span></td>
                            <td width="25%"><input v-model="nickNameTmp" type="radio" name="nick" value="小姐"><span class="ml5">小姐</span></td>
                        </tr>
                        <tr>
                            <td><input v-model="nickNameTmp" type="radio" name="nick" value="~ 总"><span class="ml5">~ 总</span></td>
                            <td><input v-model="nickNameTmp" type="radio" name="nick" value="老板"><span class="ml5">老板</span></td>
                            <td><input v-model="nickNameTmp" type="radio" name="nick" value="经理"><span class="ml5">经理</span></td>
                            <td><input v-model="nickNameTmp" type="radio" name="nick" value="师傅"><span class="ml5">师傅</span></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <p v-cloak v-show="!callChecked"  class="red">客官，非常抱歉，您没有选择称呼 </p>
                            </td>
                        </tr>
                    </table>
                    <div class="clear"></div>
                    <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doSetNick" class="btn btn-s-md btn-danger fs14 do w100 ">保存</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">取消</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div id="setAddress" class="popupbox">
            <div class="popup-title">设置地址</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <div class="set-address-wrapper">
                        <province-city type="all"  province-tmp="{{ $user->province }}" city-tmp="{{ $user->city }}" def-value="@if($user->address !==null){{ $user->province }}{{ $user->city }}@else--选择地区--@endif" :is-select-province="isSelectProvince" v-on:receive-params="listenSelect"></province-city>
                        <textarea v-model="address" @focus="setAddressStatus" class="textarea w300 mt10" name="address" placeholder="请输入详细地址"></textarea>
                        <div :class="{'error-div':isInputAddress,'red':true}">请输入详细地址</div>
                        <br><br><br>
                    </div>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click.stop="doSetAddress" class="btn btn-s-md btn-danger fs14 do w100 ">保存</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">取消</a>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

        <div id="authentication" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <div class="set-authentication-wrapper">
                        <p class="gray">客官，非常抱歉，您的银行账户无法进行绑定，</p>
                        <p class="gray">因为您的账户还未实名认证哦~</p>
                        <br>
                    </div>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="{{route('auth.addShowIdCart')}}" @click.stop="doSetAddress" class="btn btn-s-md btn-danger fs14 do btn-auto ">前往实名认证</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto ml20 inline-block ">取消此次绑定</a>
                    <div class="clear"></div>
                </div>
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
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-self", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //称呼和地址如果已经存在值取消下面两行的注释
            @if($user->call !==null && $user->call !='')
                u.initNick('{{ $user->call }}');
            @endif
            @if($user->address !==null)
                u.initAddress('{{ $user->province }}','{{ $user->city }}','{{ $user->address }}');
            @endif
            @if($user->img_url !==null)
                u.initUserImg('{{ $user->img_url }}')
            @endif

           u.initSetUrl('{{ route('account.upInfo',['type'=>'call']) }}','{{ route('account.upInfo',['type'=>'address']) }}');
        })
    </script>
@endsection