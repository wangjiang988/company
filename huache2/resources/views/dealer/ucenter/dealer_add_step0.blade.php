@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
 
                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step">
                             <li class="cur"><span>基本资料</span></li>
                             <li><span>服务专员</span></li>
                             <li><span>上牌条件</span></li>
                             <li><span>临牌条件</span></li>
                             <li><span>免费提供</span></li>
                             <li><span>杂费标准</span></li>
                             <li><span>刷卡标准</span></li>
                             <li class="last"><span>竞争分析</span></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                      <div id="add-dealers-form">
                          <form  action="/dealer/editdealer/add/0/step0" name="add-dealers-form" method='post'>
                          @if(empty($dealer))
                              <table class="custom-form-tbl ml-30" v-cloak>
                                  <tr>
                                      <td align="right" width="150">
                                          <label><span>*</span>销售品牌：</label>
                                      </td>
                                      <td width="200">
                                          <div class="psr">
                                              <div @click.stop.prevent="selectEvent(0)"class="btn-group btn-auto" id="sell-brand">
                                                  <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                      <span class="dropdown-label"><span v-cloak>${switchs[0].value == '' ? '--请选择--' : switchs[0].value}</span></span>
                                                      <span class="caret"></span>
                                                  </button>
                                                  <ul class="dropdown-menu dropdown-select " :class="switchs[0].selectEventSwitch ? 'show' : ''">
                                                      <input type="hidden" name="hfbrand" :value="switchs[0].id" />
                                                      @foreach($brand as $k=>$v)
                                                      	<li @click.stop.prevent-1="selectElmEvent(0,'{{$v['gc_name']}}')" @click.stop.prevent-2="getArea({{$v['gc_id']}},0,true,'请选择归属地区')" @click.stop.prevent-3="clearSelect(true)" ><a><span>{{$v['gc_name']}}</span></a></li>
                                                      @endforeach
                                                      <input type="hidden" name="brand_id" :value="switchs[0].id"/>
                                                  </ul>
                                              </div> 
                                              <div class="psa error-div" :class="formValite.isBrandDisplay ? 'show' : ''"><label>请选择销售品牌</label></div>
                                              <div class="mt10"></div>
                                          </div>
                                       </td>
                                       <td>
                                          <label><span>*</span>归属地区：</label>
                                          <div class="form-txt psr inlineblock">
                                             <div  class="btn-group m-r pdi-drop pdi-drop-warp" id="area-group">
                                                  <div @click.stop.prevent="selectAreaEvent(1,true)" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                                      <span class="dropdown-label" data-def="请选择归属地区"><span v-cloak v-html="switchs[1].value == '' ? '请选择归属地区' : switchs[1].value"></span></span>
                                                      <span class="caret"></span>
                                                  </div>
                                                  <div class="dropdown-menu dropdown-select area-tab-div" :class="switchs[1].selectEventSwitch ? 'show' : ''">
                                                      <input type="hidden" name="province" :value="provinceTxt" />
                                                      <input type="hidden" name="city" :value="cityTxt" />
                                                      <input type="hidden" name="dealer_city_id" :value="cityId" />
                                                      <p class="area-tab"><span :class="provinceDisplay ? 'cur-tab' : ''">省份</span><span :class="cityDisplay ? 'cur-tab' : ''">城市</span>
                                                      </p>
                                                      <dl class="dl" :class="provinceDisplay ? 'show' : 'hide'">
                                                      		@foreach($area as $k=>$v)
                                                      			<dd @click.stop.prevent-1="getCity(1,{{$k}},'{{$v['name']}}',true)">{{$v['name']}}</dd>
                                                      		@endforeach
                                                        <div class="clear"></div>
                                                      </dl>
                                                      <dl class="dl" :class="cityDisplay ? 'show' : 'hide'">
                                                        <dd v-for="city in citylist" @click.stop.prevent-1="selectCity(1,city.city_id,city.name,true)" @click.stop.prevent-2="clearSelect(false)">${city.name}</dd>
                                                        <div class="clear"></div>
                                                      </dl>
                                                  </div>

                                             </div>
                                             <div class="error-div psa" :class="formValite.isProvinceDisplay ? 'show' : ''"><label>请选择省份</label></div>
                                             <div class="error-div psa" :class="formValite.isCityDisplay ? 'show' : ''"><label>请选择城市</label></div>
                                             <div class="clear"></div>
                                             <div class="mt10"></div>
                                          </div>
                                       </td>
                                  </tr>
                                  <tr>
                                      <td align="right">
                                          <label><span>*</span>经销商：</label>
                                      </td>
                                      <td colspan="2">
                                           <div @click.stop.prevent="selectEvent(2)" class="btn-group btn-auto" id="dealers-group">
                                                <button data-toggle="dropdown" class="btn btn-select btn-select-long btn-default dropdown-toggle">
                                                    <span class="dropdown-label" data-def="请选择经销商"><span v-cloak v-html="switchs[2].value == '' ? '请选择经销商' : switchs[2].value"></span></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-select dropdown-long" :class="switchs[2].selectEventSwitch ? 'show' : ''">
                                                    <input type="hidden" name="hfdealers" :value="sellerInfo.name" />
                                                    <li v-for="dealer in dealerlist"
                                                        @click.stop.prevent-1="selectElmEvent(2,dealer.d_name)"
                                                        @click.stop.prevent-2="selectDealerEvent(2,dealer.d_yy_place,dealer.d_jc_place,dealer.d_id,dealer.d_name)" 
                                                    >
                                                    	<a><span>${dealer.d_name}</span></a>
                                                    </li>
                                                </ul>
                                                <span class="nofand" @click.stop.prevent="noFandDelears">未找到，点此</span>
                                            </div> 
                                            <div class="error-div" :class="formValite.isSellerDisplay ? 'show' : ''"><label>请选择经销商</label></div>
                                       </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>营业地点：</label>
                                      </td>
                                      <td colspan="2">
                                          <span id="yy_place">${sellerInfo.d_yy_place}</span>
                                          <input :value="sellerInfo.d_yy_place" type="hidden" name="yy_place" placeholder="" class="form-control">
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>交车地点：</label>
                                      </td>
                                      <td colspan="2">
                                          <span id="jc_place">${sellerInfo.d_jc_place}</span>
                                          <input :value="sellerInfo.d_jc_place" type="hidden" name="jc_place" placeholder="" class="form-control">
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label><span>*</span>经销商简称：</label>
                                      </td>
                                      <td>
                                          <input v-model="formInput.shot_name" placeholder="可输入不超过6个汉字" maxlength="6" type="text" name="txt-dealers-shot-name"  class="form-control custom-control">
                                          <div class="error-div" :class="formValite.isShotDisplay ? 'show' : ''"><label>请输入经销商简称</label></div>
    									                    <div class="error-div" :class="formValite.isShotPassDisplay ? 'show' : ''"><label>请输入不超过6个汉字</label></div>
                                      </td>
                                      <td>
                                          <label>经销商编号：</label>
                                          <span id="d_id">${sellerInfo.d_id}</span>
                                          <input :value="sellerInfo.d_id" type="hidden" name="d_id" placeholder="" class="form-control">
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>类别：</label>
                                      </td>
                                      <td colspan="2">                                   
                                          <span>授权4s</span>                 
                                          <input type='hidden' name='dl_type' value='1'>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>统一社会信用代码：</label>
                                      </td>
                                      <td colspan="2">
                                          <input v-model="formInput.code" placeholder="选填 统一社会信用代码由18位数字+字母组成" type="text" name="txtcode" placeholder="" maxlength="18" class="form-control custom-control custom-control-long" style="width: 460px!important;">
                                          <div class="error-div" :class="formValite.isSnDisplay ? 'show' : ''"><label>输入不正确，请重新输入 "统一社会信用代码由18位数字+字母组成"</label></div>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>开户行：</label>
                                      </td>
                                      <td colspan="2">
                                          <input v-model="formInput.bank" maxlength="50" placeholder="选填" type="text" name="bank_addr" placeholder="" class="form-control custom-control" style="width: 460px!important;">
                                      </td>
                                       
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>账号：</label>
                                      </td>
                                      <td colspan="2"> 
                                          <input v-model="formInput.account" maxlength="30" placeholder="选填" type="text" name="bank_account" placeholder="" class="form-control custom-control" style="width: 460px!important;">
                                          <div class="error-div" :class="formValite.isAccountDisplay ? 'show' : ''"><label>*请输入纯数字账号~</label></div>
                                      </td>
                                   </tr>

                                   <tr>

                                      <td colspan="3" align="center">
                                          <div class="m-t-10"></div>
                                          <div class="m-t-10"></div>
                                          <label class="add-info-tip"><span>*</span>为必填项</label>
                                          <div class="clear"></div>
                                          <a @click="subAddDealersForm" href="javascript:;" class="btn btn-s-md btn-danger fs18">下一步</a>
                                      </td>
                                   </tr>
                              </table>
                              <input type='hidden' name='_token' value="{{csrf_token()}}">

                          @else
                              <table class="custom-form-tbl ml-30">
                                  <tr>
                                      <td align="right" width="150">
                                          <label><span>*</span>销售品牌：</label>
                                      </td>
                                      <td width="200">
                                          {{$car_brand['gc_name']}}
                                      
                                       </td>
                                       <td>
                                           <label><span>*</span>归属地区：</label>
                                           <span>{{$dealer['d_areainfo']}}</span>
                                       </td>
                                  </tr>
                                  <tr>
                                      <td align="right">
                                          <label><span>*</span>经销商：</label>
                                      </td>
                                      <td colspan="2">
                                           <span>{{$dealer['d_name']}}</span>
                                       </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>营业地点：</label>
                                      </td>
                                      <td colspan="2">
                                          {{$dealer['d_yy_place']}}
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>交车地点：</label>
                                      </td>
                                      <td colspan="2">
                                          {{$dealer['d_jc_place']}}
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label><span>*</span>经销商简称：</label>
                                      </td>
                                      <td>
                                          {{$daili['d_shortname']}} 
                                      </td>
                                      <td>
                                          <label>经销商编号：</label>
                                          <span>{{$daili['d_id']}}</span>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>类别：</label>
                                      </td>
                                      <td colspan="2">                                   
                                         <span>授权4s</span>  
                                         <input type='hidden' name='dl_type' value='1'>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>统一社会信用代码：</label>
                                      </td>
                                        <td colspan="2">
                                          <input v-model="formInput.code" placeholder="选填 统一社会信用代码由18位数字+字母组成" type="text" name="txtcode" placeholder="" maxlength="18" class="form-control custom-control custom-control-long" value="{{$daili['dl_code']}}" style="width: 460px!important;">
                                          <div class="error-div" :class="formValite.isSnDisplay ? 'show' : ''"><label>输入不正确，请重新输入 "统一社会信用代码由18位数字+字母组成"</label></div>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>开户行：</label>
                                      </td>
                                      <td colspan="2">
                                          <input v-model="formInput.bank" maxlength="50" placeholder="选填" type="text" name="bank" value="{{$daili['dl_bank_addr']}}" class="form-control custom-control" style="width: 460px!important;">
                                      </td>
                                   </tr>
                                   <tr>
                                      <td align="right">
                                          <label>账号：</label>
                                      </td>
                                      <td colspan="2"> 
                                          <input placeholder="选填" type="text" name="account" maxlength="30" v-model="formInput.account" value="{{$daili['dl_bank_account']}}" class="form-control custom-control" style="width: 460px!important;">
                                          <div class="error-div" :class="formValite.isAccountDisplay ? 'show' : ''"><label>*请输入纯数字账号~</label></div>
                                      </td>
                                   </tr>
                                      <td colspan="3" align="center" valign="middle">
                                          <div class="row">
                      									  	  <div class="col-xs-6 col-sm-6 tar">
                      									  	  	  <a href="javascript:;" class="juhuang tdu inlineblock mt20 delseller" @click="delSeller" data-daili-dealer-id='{{$daili["id"]}}' data-dealer-id='{{$daili["d_id"]}}'>删除经销商</a>
                      									  	  </div>
                      									  	  <div class="col-xs-6 col-sm-6 tal">
                                                <a @click="subEditDealersForm({{$id}},'check')" href="javascript:;" class="btn btn-s-md btn-danger fs18 SubEditDealersForm">下一步</a>
                                                <input type='hidden' name='id' value="{{$id}}">
                                                <input type="hidden" name="type" value="check">
                                              </div>
                      									  	  <div class="clear"></div>
                      									  </div> 
                                      </td>
                                   </tr>
                              </table>
                              <div id="delSeller" class="popupbox">
                                  <div class="popup-title">温馨提示</div>
                                  <div class="popup-wrapper">
                                      <div class="popup-content">
                                          <div class="m-t-10"></div>
                                          <p class="fs14 pd tac succeed error constraint">
                                             <span class="tip-tag" style="background-position: 0px 0px;"></span>
                                             <span class="tip-text">确定要删除此经销商相关的所有信息吗？</span>
                                             <div class="clear"></div>
                                          </p>
                                          <div class="m-t-10"></div>
                                      </div> 
                                      <div class="popup-control">
                                          <a @click="doDelSeller({{$daili['id']}},{{$daili['d_id']}})" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                          <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                          <div class="clear"></div>
                                      </div>
                                  </div>
                              </div>
                          
                          @endif
                          </form>
                          <div id="noFandDelearsWin" class="popupbox">
                            <div class="popup-title">未找到经销商</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <p class="fs14">       
                                        <table class="custom-form-tbl">
                                            <tr>
                                                <td width="70" valign="top" class="nopadding"><b>温馨提示：</b></td>
                                                <td class="nopadding">请核对授权经销商归属地区是否正确，如仍有问题，请提交给华车平台审核，通过后可进行添加。</td>
                                            </tr>
                                        </table>
                                    </p>
                                    <form action="/dealer/ajaxsubmitdealer/add-dealer" name="no-dealers-form" method ="post">
                                      <table class="custom-form-tbl ml-15">
                                          <tr>
                                              <td align="right" width="">
                                                  <label>销售品牌：</label>
                                              </td>
                                              <td>
                                               <input placeholder="必填" type="text" name="txtbrand" class="form-control custom-control custom-txtbrand" v-model="noFindForm.brand">
                                               <div class="error-div" :class="noFindForm.isSmallBrand ? 'show' : 'hide'"><label>*请输入销售品牌~</label></div>
                                          </td>
                                          </tr>
                                          <tr>
                                              <td align="right" width="150"><label>归属地区：</label></td>
                                              <td> 
                                                   
                                                   <div class="form-txt psr inlineblock">
                                                     <div  class="btn-group m-r pdi-drop pdi-drop-warp" id="area-group">
                                                          <div @click.stop.prevent="selectAreaEvent(3,false)" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                                              <span class="dropdown-label" data-def="请选择归属地区"><span v-cloak v-html="switchs[3].value == '' ? '请选择归属地区' : switchs[3].value"></span></span>
                                                              <span class="caret"></span>
                                                          </div>
                                                          <div class="dropdown-menu dropdown-select area-tab-div" :class="switchs[3].selectEventSwitch ? 'show' : ''">
                                                              <input type="hidden" name="province" :value="provinceTxt2" />
                                                              <input type="hidden" name="city" :value="cityTxt2" />
                                                              <input type="hidden" name="area" v-model="noFindForm.area" />

                                                              <p class="area-tab"><span :class="provinceDisplay2 ? 'cur-tab' : ''">省份</span><span :class="cityDisplay2 ? 'cur-tab' : ''">城市</span>
                                                              </p>
                                                              <dl class="dl" :class="provinceDisplay2 ? 'show' : 'hide'">
                                                                  @foreach($area as $k=>$v)
                                                                    <dd @click.stop.prevent="getCity(3,{{$k}},'{{$v['name']}}',false)">{{$v['name']}}</dd>
                                                                  @endforeach
                                                                <div class="clear"></div>
                                                              </dl>
                                                              <dl class="dl" :class="cityDisplay2 ? 'show' : 'hide'">
                                                                <dd v-for="city in citylist" @click="selectCity(3,city.city_id,city.name,false)">${city.name}</dd>
                                                                <div class="clear"></div>
                                                              </dl>
                                                          </div>

                                                     </div>
                                                     <div class="error-div psa" :class="formValite.isProvinceDisplay2 ? 'show' : ''"><label>请选择省份</label></div>
                                                     <div class="error-div psa" :class="formValite.isCityDisplay2 ? 'show' : ''"><label>请选择城市</label></div>
                                                     <div class="clear"></div>
                                                     <div class="mt10"></div>
                                                  </div>
                                               </td>
                                          </tr>
                                          <tr>
                                              <td align="right" width="">
                                                  <label>经销商：</label>
                                              </td>
                                              <td>
                                                  <textarea v-model="noFindForm.textarea" name="dealer_name" id="" cols="30" rows="10" class="textarea"></textarea>
                                                   <div class="error-div" :class="noFindForm.isSellerEmpty ? 'show' : ''"><label>*请填写经销商</label></div>
                                              </td>
                                          </tr>
                                      </table>
                                      <input type='hidden' name='_token' value="{{csrf_token()}}">
                                      <input type="reset" name="reset" style="display: none;" />
                                    </form>
                                </div>
                                <div class="popup-control">
                                    <a @click="tellme" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">告诉华车</a>
                                    <a @click="tellMeBack" href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                          </div>
                      
                      </div>


                    
					
					          
                    
                    <div id="tip-has" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">经销商已存在!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                    </div>

                    <div id="tip-error" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">添加失败!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                    </div>

                    <div id="tip-succeed" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac succeed auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">添加成功!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                    </div>

                    
            </div>
                   
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </div>

    <div class="box" ms-include-src="footernew"></div>	
@endsection

@section('js')
<script type="text/javascript">

   seajs.use(["module/vue.custom/dealer/vue.common","module/vue.custom/dealer/step1","bt"],function(a,b,c){
      @if(!empty($dealer))
      b.init("{{$daili['dl_code']}}","{{$daili['dl_bank_addr']}}","{{$daili['dl_bank_account']}}")
      @endif
   })
		/*seajs.use(["module/custom/custom_admin", 
		   		"module/common/common", 
		   		"module/custom/custom.admin.jquery", 
		   		"bt",
		   		"module/custom/custom.dealer.step1.fix.jquery",
		   		"module/custom/custom.admin.fix.jquery"],function(){
			
		});*/
</script>
@endsection