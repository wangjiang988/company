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
            <div class="hd hd-card">
                <ul>
                    <li class="cur"><span>1</span><label class="juhuang">提交资料</label></li>
                    <li><span>2</span><label>核实信息</label></li>
                    <li><span>3</span><label>完成认证</label></li>
                    <div class="clear"></div>
                </ul>
            </div>
            {!! Form::open(['url'=>route('special.save'),'name'=>'user-special-file','role'=>'form','enctype'=>'multipart/form-data']) !!}
            <table class="noborder-tbl wp100">
                <tr>
                    <td colspan="2">
                        <p class="juhuang ml67 fs14 mt20">客官，您当地如办理上牌需要某些特殊文件，不在所列的经销商向您移交文件中，请告诉华车哦~</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="ml67">
                            <span class="red  mt5">*</span>
                            <span class="ml5  mt5 weight"> 您心仪座驾的生产国别：</span>
                            <input type="radio" name="country_car" checked="" value="0"><span class="ml5">国产</span>
                            <input type="radio" name="country_car" class="ml20" value="1"><span class="ml5">进口</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="middle">
                        <span class="ml5 fr mt5 weight">文件名称：</span>
                        <span class="red fr mt5">*</span>
                        <div class="clear"></div>
                    </td>
                    <td width="600" class="bank-area">
                        <div class="form-group psr pdi-control fs14  m-t-10">
                            <input @focus="initName" @blur="checkName" maxlength="20" v-model="name" type="text" name="file_name" placeholder="" :class="{'form-control':true,'w125':true,'error-bg':isName}">
                            <span class="edit pz-edit"></span>
                            <span :class="{red:true,hide:!isSame,ml5:true}">特殊文件已被提交过，请重新输入</span>
                            <span class="red hide ml5" :class="{display:isName}">请输入文件名称</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="middle">
                        <span class="ml5 fr mt5 weight">上牌地区：</span>
                        <span class="red fr mt5">*</span>

                        <div class="clear"></div>
                    </td>
                    <td width="600">
                        <div class="file-dropdown-wrapper">
                            <province-city class="inline-block" v-on:receive-params="getArea" def-value="--请选择--" is-select-province="false"></province-city>
                            <span class="red hide ml5" :class="{display:isEmptyPC}">请选择上牌地区</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td width="150" align="right" valign="middle">
                        <div class="mt10">
                            <div class="m-t-10"></div>
                            <span class="ml5 fr mt-10 weight"> 车辆用途：</span>
                            <span class="red fr mt-10">*</span>
                            <div class="clear"></div>
                        </div>
                    </td>
                    <td width="600">
                        <div class="mt5 file-dropdown-wrapper" v-cloak>
                            <div class="btn-group btn-jquery-event mt10 pdi-drop pdi-drop-warp">
                                    <div @click="dropDown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                    <span class="dropdown-label" ><span>@{{dropValue}}</span></span>
                                    <span class="caret"></span>
                                </div>
                                <ul :class="{'dropdown-menu':true,'dropdown-select':true,show:drop,'w315':true}">
                                    <input type="hidden" name="use_car" :value="dropValue">
                                    <li class="block" @click="setValue(info)" v-for="info in vehicleUseList"><a><span>@{{info.txt}}</span></a></li>
                                </ul>
                            </div>
                        </div>
                     </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="ml67 fs14 mt10"><span class="red">*</span><span class="ml5 weight">上牌（注册登记）车主身份类别：</span></p>
                        <div class="ml67" v-cloak v-show="dropCate != 'company'">
                            <p><input v-model="isParentSelect" type="radio" name="licence_user_type" id="shangpai" value="2" @click="setDefIdentity"><label class="fn" for="shangpai">上牌地本市户籍居民</label></p>
                            <p><input v-model="isParentSelect" type="radio" name="licence_user_type" id="qita" value="1"><label class="fn" for="qita">其他</label></p>
                            <p class="pl20"><input @click="selectParant(0)" type="radio" name="child_type" value="1" v-model="childVal"><label class="fn" for="">@{{child[0].txt}}</label></p>
                            <div class="pl20">
                                <input @click="selectParant(1)" type="radio" name="child_type" value="2" v-model="childVal" class="fl" />
                                <!--国内其他限牌城市户籍居民-->
                                <label class="fn fl" for="">@{{child[1].txt}}</label>
                                <ul class="files-city">
                                    <!--国内其他限牌城市户籍居民 北京上海广州天津杭州贵阳深圳苏州-->
                                    <li v-for="(item,index) in child[1].list" @click="selectHouseholdRegistration(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i>@{{item.txt}}</li>
                                    <li class="red noborder" v-show="childVal == 2 && householdRegistration == '' && isSub">请选择户籍所在城市</li>
                                    <input type="hidden" name="id_gn" id="huji" :value="householdRegistration" v-model="householdRegistration">
                                    <div class="clear"></div>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <p class="pl20">
                                <!--中国军人-->
                                <input @click="selectParant(3)" type="radio" name="child_type" value="3" v-model="childVal"><label class="fn" for="">@{{child[2].txt}}</label>
                            </p>
                            <div class="pl20">
                                <!--非中国大陆人士-->
                                <input @click="selectParant(4)" type="radio" name="child_type" value="4" v-model="childVal" class="fl" />
                                <label class="fn fl" for="">@{{child[3].txt}}</label>
                                <ul class="files-city">
                                    <li v-for="(item,index) in child[3].list" @click="selectForeign(index,item.id,item.txt)" :class="{'cur-select':item.isSelect}"><i></i>@{{item.txt}}</li>
                                    <li class="red noborder" v-show="childVal == 4  && foreign == '' && isSub ">请选择您的身份</li>
                                    <input type="hidden" name="id_gj" id="waiji" v-model="foreign">
                                    <div class="clear"></div>
                                </ul>
                                <div class="clear"></div>
                            </div>

                        </div>
                        <div class="ml67" v-cloak v-show="dropCate == 'company'">
                            <p class="pl20" v-for="cate in companyCate">
                                <input @click="selectParant(2)" type="radio" name="child_type" :value="cate.val" v-model="childVal">
                                <label class="fn">@{{cate.txt}}</label>
                            </p>
                        </div>
                        <div class="red ml67" v-show="isCate">
                            <p class="ml20">请选择上牌（注册登记）车主身份类别</p>
                        </div>

                        <div class="ml67">
                            <p class="mt10"><b>如有文件式样请拍照上传：</b><span class="p-gray">（仅支持JPG、GIF、PNG、JPEG、BMP格式，单张图片小于5M）</span></p>
                            <p class="upload-muti-wrapper">
                                <span :class="{'display-i':!file.del}" v-for="(file,index) in fileList" class="m-item-wrapper hide">
                                    <input @change="loadFileName($event,index)" type="file" :name="'file_url_'+index" class="hide">
                                    <span class="blue tdu">@{{file.filename}}</span><br>
                                    <a v-show="file.filename" @click="delFile($event,index)" href="javascript:;" class="">删除</a>
                                    <a :style="{top:index == 0 ? '68px' : '78px'}" v-show="file.display" @click="upload($event,index)" href="javascript:;" class="btn btn-danger fs14 btn-orange sure btn-white btn-upload">上传</a>
                                </span>
                            </p>
                            <div class="clear "></div>
                            <div class="m-t-10"></div>
                            <div class="tac mt20">
                                <div class="m-t-10"></div><div class="m-t-10"></div><div class="m-t-10"></div>
                                <a href="javascript:;" @click="send" class="btn btn-s-md btn-danger">提交</a>
                                <a href="javascript:history.go(-1);" data-grounp="" class="btn btn-s-md btn-danger sure ml50">返回</a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-special-files-submit"],function(v,u,c){
            u.initUrl("{{ route('special.list') }}");
            $(".box-border").css({
                'border-right':0,
                'border-bottom':0,
            })
            $(".slide").css({
                'border-bottom':"1px solid #ddd",
            })
        })
    </script>
@endsection