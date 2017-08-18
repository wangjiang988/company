@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <!--//账户信息-->
                    <h4 class="title"><span>账户信息</span></h4>
                    <form action="/dealer/member_info/modify_base" name="user-info-edit" enctype="multipart/form-data" method="post">
                        <table class="tbl-2 custom-info-tbl custom-info-edit-tbl">
                             <tr>
                                 <td width="135" align="right">性 别：</td>
                                 <td width="660" class="nobottomborder" style="text-align: left;">
                                     <label class="sexlable"><input type="radio" name="rdb-sex" id="" value='1' <?php if($member['seller_sex']==1){echo 'checked';}?>>男</label>
                                     <label class="sexlable"><input type="radio" name="rdb-sex" id="" value='2' <?php if($member['seller_sex']==2){echo 'checked';}?>>女</label>
                                     <div class="error-div"><label>请选择性别</label></div>
                                     <div class="pic-wrapper">
                                         <div class="pic">
                                             <img ms-on-click="upload" onerror="this.src='/themes/images/custom/custom-info-head.gif'"  width="84" height="89" class="block" src="/upload/{{$member['seller_photo']}}" alt="">
                                             <input type="file" name="file" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                             <input type="hidden" name="hfFile" >
                                             <p class="info-txt">上传头像</p>
                                         </div>
                                         <div class="txt-info">
                                             <p>大小不超过5M，支持</p>
                                             <p>jpg/png/bmp格式</p>
                                         </div>
                                     </div>
                                 </td>
                             </tr>
                             <tr>
                                 <td width="135" align="right">固定电话：</td>
                                 <td width="660" class="notopborder nobottomborder">
                                     <input placeholder="请输入带区号的固话号码" type="text" name="seller_phone" placeholder="" class="form-control custom-control pull-left email-input" value="{{$member['seller_phone']}}">
                                     <div class="error-div"><label>请填写固定电话</label></div>
                                 </td>
                             </tr>
                             <tr>
                                 <td width="135" align="right" valign="top">常用地址：</td>
                                 <td width="660" class="notopborder" style="text-align: left;">
                                    <div class="form-txt psr ">
                                         <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                              <div ms-on-click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                                  <span class="dropdown-label"><span>{{$areaProvinceStr.$areaCityStr}}</span></span>
                                                  <span class="caret"></span>
                                              </div>
                                              <div class="dropdown-menu dropdown-select area-tab-div" style="top:26px;">
                                                  <input type="hidden" name="province" value="{{$areaProvinceStr}}"/>
                                                  <input type="hidden" name="city" value="{{$areaCityStr}}"/>
                                                  <input type="hidden" name="province_id"  value="{{$member['seller_province_id']}}"/>
                                                  <input type="hidden" name="city_id" value="{{$member['seller_city_id']}}"/>
                                                  <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                                  <dl class="dl">
                                                  @foreach($province as $k=>$v)
                                                      <dd ms-on-click="selectProvince({{$k}})" >{{$v['name']}}</dd>
                                                   @endforeach
                                                    <div class="clear"></div>
                                                  </dl>
                                                  <dl class="dl" style="display: none;">
                                                    <dd ms-repeat-city="citylist" ms-on-click="selectCity('city_id')" ms-data-id=city.city_id><!--city.name--></dd>
                                                    <div class="clear"></div>
                                                  </dl>
                                              </div>
                                              <div class="clear "><br>
                                                  <textarea name="seller_address" placeholder="请输入余下地址信息" id="" cols="30" rows="10">{{$member['seller_address']}}</textarea>       
                                              </div>
                                              <div class="error-div"><label>请选择常用地址省份、城市</label></div>
                                              <div class="error-div"><label>请填写常用地址详细地址</label></div>
                                         </div>
                                         <div class="clear"></div>
                                    </div>
                                 </td>
                             </tr>
                             <tr>
                                 <td width="135" align="right">邮 编：</td>
                                 <td width="660" class="notopborder nobottomborder">
                                     <input placeholder="请输入邮编" type="text" name="seller_postcode" placeholder="" class="form-control custom-control pull-left email-input" value="{{$member['seller_postcode']}}">
                                     <div class="error-div"><label>请填写邮编</label></div>
                                 </td>
                             </tr>
                             <tr>
                                 <td width="135" align="right">微 信：</td>
                                 <td width="660" class="notopborder nobottomborder">
                                     <input placeholder="请输入微信" type="text" name="seller_weixin" placeholder="" class="form-control custom-control pull-left email-input" value="{{$member['seller_weixin']}}">
                                 </td>
                             </tr>
                             <tr>
                                 <td width="135" align="right" valign="top">其他联系方式：</td>
                                 <td width="660" style="text-align: left;" class="notopborder nobottomborder">
                                     <div class="clear">
                                         <a href="javascript:;" ms-on-click="addlinkmethod" class="">添加</a>
                                         <table class="tbl custom-info-tbl custom-info-tbl-user">
                                             <tr>
                                                 <th>名称</th>
                                                 <th>号码</th>
                                                 <th class="last"> 
                                                    操作
                                                 </th>
                                             </tr>
                                             @if(count($other_contact)>0)
                                             @foreach($other_contact as $v)
                                             <tr>
                                                 <td>{{$v->name}}</td>
                                                 <td>{{$v->phone}}</td>
                                                 <td class="tac">
                                                     <a href="javascript:;" ms-on-click="dellink({{$v->id}})" class="ml10 weight">删除</a>
                                                 </td>
                                             </tr>
                                             @endforeach
                                             @endif
                                           
                                        </table>
                                     </div>
                                 </td>
                             </tr>
                            
                        </table>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </form>

                    <br><br>
                    <p class="tac">
                        <input ms-on-click="edituserinfosub" type="button" value="提交" class="btn btn-s-md btn-danger ml20 ">
                        <a href="javascript:;" class="btn btn-s-md btn-danger  sure ml20 ">返回</a>
                    </p>
                    
  
                    


                    <div class="content-wapper">
                         <div id="account-add" class="popupbox">
                            <div class="popup-title">添加其他联系方式</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <form action="/dealer/member_info/add-other-contact" name="addlink" method="post">
                                        <table>
                                            
                                            <tr>
                                                <td align="right">名  称：</td>
                                                <td>
                                                    <input type="text" name="link-name" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写名称</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">号  码：</td>
                                                <td>
                                                    <input type="text" name="link-phone" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写号码</label></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    </form>

                                </div>
                                <div class="popup-control">
                                    <a ms-on-click="doaddlink" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>   
                        </div>
 
                        <div id="account-del" class="popupbox">
                            <div class="popup-title">温馨提示</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <p class="fs14 pd ti">       
                                        确定要删除该联系方式吗？
                                    </p>
                                </div>
                                <div class="popup-control">
                                    <a ms-on-click="dodellink" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>   
                        </div>

                    </div>
                    </div>
					</div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"]);
	</script>
@endsection