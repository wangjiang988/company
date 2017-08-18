@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <div class="custom-offer-step-wrapper">
                        <ul>
                          <li class="first">车型价格</li>
                          <li>车况说明</li>
                          <li>选装精品</li>
                          <li>首年保险</li>
                          <li>收费标准</li>
                          <li class="last">其他事项</li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="/dealer/baojia/edit/{{$baojia['bj_id']}}/1" method="post" name="baojia-submit-form">
                    	<input type='hidden' name='_token' value="{{csrf_token()}}">
                        <div class="content-wapper ">
                           
                           <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tal" width="135"><span class="blue weight">一、选择经销商：</span></td>
                                   <td class="tac">
                                      <div class="btn-group btn-group-auto btn-jquery-event-dealer">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company bt-jquery-dealer-list">
                                              <span class="dropdown-label"><span>{{$dealerName}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          
                                      </div>
                                   </td>
                                   <td><span>归属地区：</span></td>
                                   <td>
                                    <span class="" name="dealer-area-str">{{$dealerAddress}}&nbsp;</span>
                                   </td>
                               </tr>
                             </tbody>
                           </table> 

                           <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tal" width="135"><span class="blue weight">二、选择车型：</span></td>
                                   <td class="tal"></td>
                                   <td><span></span></td>
                                   <td></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">品牌：</span></td>
                                   <td class="tal">
                                   	  <span id="brand-str-default">{{$brandArr[0]}}&nbsp;</span>
                                       
                                   </td>
                                   <td class="tar"><span>车系：</span></td>
                                   <td class="tal">
                                      <div class="btn-group btn-jquery-event-brand-chexi">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label"><span id="chexi-label-str">{{$brandArr[1]}}</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            
                                        </div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">车型规格：</span></td>
                                   <td class="tal" colspan="3">
                                       <div class="btn-group btn-jquery-event-brand-chexing">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-long btn-default dropdown-toggle">
                                                <span class="dropdown-label"><span id="chexing-label-str">{{$brandArr[2]}}</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            
                                        </div>
                                   </td>
                                   
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">整车型号：</span></td>
                                   <td class="tal">
                                       <span name="vehicle_model-str" class="">{{$car_info['vehicle_model']}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar" width="135"><span class="">座位数：</span></td>
                                   <td class="tal">
                                   		<span name="seat-num-str" class="">{{$car_info['seat_num']}}&nbsp;</span>
                                   </td>
                                   <td class="tar"><span>基本配置：</span></td>
                                   <td class="tal">
                                      <a href="{{$car_info['official_url']}}" target="_blank" class="juhuang tdu">查看</a>
                                   </td>
                               </tr>
                             </tbody>
                           </table> 

                           <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tal" width="135"><span class="blue weight">三、是否现车：</span></td>
                                   <td class="tal" colspan="3">
                                   		<label><input type="radio" name="bj_is_xianche" id="" value='1' <?php if($baojia['bj_is_xianche']==1){echo 'checked';}?>><span class="noweight ml5">现车</span></label>
                                       <span class="ml20">经销商内部车辆编号（选填）</span>
                                       <input maxlength="20" placeholder="填写不超过20位数字或字母" type="text" name="bj_dealer_internal_id" class="form-control custom-control-2  autocomplete"  value="{{$baojia['bj_dealer_internal_id']}}" autocomplete="true">
                                       <div class="error-div text-right"><label>输入有误，请重新输入不超过20位的数字或字母~</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" width="135"></td>
                                   <td class="tal notopbottompadding" colspan="3">
                                       <label class=""><input type="radio" name="bj_is_xianche" id="" value='0' <?php if($baojia['bj_is_xianche']==0){echo 'checked';}?>><span class="noweight ml5">非现车</span></label>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" valign="top" width="135"><span class="blue weight">四、销售区域：</span></td>
                                   <td class="tal" colspan="3">
                                       <div class="area-readonly-wrapper psr">
                                           <textarea class="textarea area-textarea" >{{$myAreaStr}}</textarea>
                                           <i class="location-area"></i>
                                       </div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" valign="top" width="135"><span class="blue weight">五、排放标准：</span></td>
                                   <td class="tal" colspan="3" id="paifang-td">
                                   @foreach($paifangList as $k=>$v)
                                       <label class="@if($k!=0)ml20 @endif"><input type="radio" name="paifang" value="{{$k}}" <?php if($baojiaPaifang==$k){echo 'checked';}?>><span class="noweight ml5">{{$v}}</span></label>
                                   @endforeach
                                   
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" valign="top" width="135"><span class="blue weight">六、价格设置：</span></td>
                                   <td class="tal" colspan="3">
                                   </td>
                               </tr>
                             </tbody>
                           </table> 

                           <table class=" custom-info-tbl noborder wp100 tbl-price">
                             <tbody>
                               <tr>
                                   <td class="tar" width="115">厂商指导价：</td>
                                   <td class="tal">￥ <span class="format-money" id="zhidaojia-price-str">{{$car_info['zhidaojia']}}</span></td>
                                   <td>人民币 <span></span></td>
                                   <input type="hidden" name="zhidaojia" value="{{$car_info['zhidaojia']}}">
                               </tr>
                               <tr>
                                   <td class="tar" width="115">车辆开票价格： </td>
                                   <td class="tal">
                                   		 <span>￥</span><input maxlength="10" placeholder="" type="text" value="{{$baojia['bj_lckp_price']}}" name="bj_lckp_price" class="form-control custom-control ml5 format-money  invoice-price " data-bind="{{$car_info['zhidaojia']}}">
                                  		 <div class="error-div text-center psa" style="width: 216px;"><label>温馨提示：高于厂商指导价</label></div>
                                       <div class="error-div text-center psa" style="width: 251px"><label>温馨提示：低于厂商指导价的70%</label></div>
                                   </td>
                                   <td>人民币 <span></span></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="115">我的服务费： </td>
                                   <td class="tal">
                                   <span>￥</span><input maxlength="10" placeholder="0~{{round($baojia['bj_lckp_price']*0.03)}}" type="text" value="{{$baojia['bj_agent_service_price']}}" name="bj_agent_service_price" class="form-control custom-control ml5 format-money service-fee " data-limit={{$baojia['bj_lckp_price']*0.03>50000?50000:$baojia['bj_lckp_price']*0.03}}>
                                   <div class="error-div text-center psa" style="width: 321px"><label>温馨提示：不在平台规定范围内，请重新输入</label></div>
                                   </td>
                                   <td>人民币 <span></span></td>
                               </tr>
                               <tr>
                                   <td class="tar" width="115">客户买车定金： </td>
                                   <td class="tal">
                                       <span>￥</span><input maxlength="10" placeholder="" type="text" value="{{$baojia['bj_doposit_price']}}" name="bj_doposit_price" class="form-control custom-control ml5 format-money doposit-fee">
                                       <div class="error-div text-center psa" style="width: 321px"><label>温馨提示：不在平台规定范围内，请重新输入</label></div>
                                   </td>
                                   <td>人民币 <span></span></td>
                               </tr>

                             </tbody>
                           </table> 
                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                           <p class="tac">
                           	  <a href="javascript:;" class="btn btn-danger fs16 baojia-submit-button" data-step='1' data-type='1'>保存并进入下一步</a>
                           	  <a href="javascript:;" class="btn btn-danger sure fs18 ml20 baojia-submit-button" data-step='1' data-type='2'>保存并退出</a>
                           	  <a href="javascript:;" class="btn btn-danger sure fs18 ml20 reset-form">重置</a> 
                            </p>
                            <div class="error-div text-center" id="error-div"><label></label></div>
                        </div>
                        
                        <div class="m-t-200"></div>
                  <div id="SelectTimeWin" class="popupbox" >
                      <div class="popup-title popup-title-white psr">
                        <span class="fl fs16"><b>选择城市</b></span>
                        <span class="ml20 fl">|</span>
                        <div class="ml20 fl  inline">
                          <label class="">
                            <input class="fl mt13 check-all" type="checkbox" name="" id="">
                            <span class="fl ml5 ">全国</span>
                            <div class="clear inline"></div>
                          </label>
                        </div>
                        <dl class="letter-nav fl">
                            <dd>
                              <a href="#NAV-A"><span class="cur">A</span></a>
                              <a href="#NAV-B"><span>B</span></a>
                              <a href="#NAV-C"><span>C</span></a>
                              <a href="#NAV-F"><span>F</span></a>
                              <a href="#NAV-G"><span>G</span></a>
                              <a href="#NAV-H"><span>H</span></a>
                              <a href="#NAV-J"><span>J</span></a>
                              <a href="#NAV-L"><span>L</span></a>
                              <a href="#NAV-Q"><span>Q</span></a>
                              <a href="#NAV-S"><span>S</span></a>
                              <a href="#NAV-T"><span>T</span></a>
                              <a href="#NAV-X"><span>X</span></a>
                              <a href="#NAV-Y"><span>Y</span></a>
                              <a href="#NAV-Z"><span>Z</span></a>
                            </dd>
                          </dl>
                        <i></i>
                        <div class="clear inline"></div>
                      </div>
                      <div class="popup-wrapper notopbottompadding">
                          <div class="popup-content nopadding">
                               <div class="clear"></div>
                               <div id="province-city-select">
                                 @foreach($provinceByFirstLetter as $k=>$provinces)
                                 <dl class="province-city-select-wrapper">
                                 	<?php $i=0;?>
                                 	@foreach($provinces as $k1=>$province)
                                   <dt>
                                   	@if($i==0)
                                     <span class="fl juhuang province" id="NAV-{{$k}}">{{$k}}</span>
                                    @else
                                    <span class="fl juhuang">&nbsp;&nbsp;</span>
                                    @endif
                                     <label class="fl ml10">
                                        <input class="fl  check-all-province" type="checkbox" name="province[{{$province['area_id']}}]" id="">
                                        <span class="fl ml5 c72 province">{{$province['name']}}</span>
                                        <div class="clear inline"></div>
                                     </label>
                                     <span class="clear"></span>
                                   </dt>
                                   <dd>
                                     <ul class="notype nopadding persent area-city-list">
                                       @foreach($area[$province['area_id']] as $k2 =>$a)
                                       <li class="city" data-id="{{$a['area_id']}}" data-value="{{$a['name']}}">
	                                       <input type="checkbox" name="sale_area[{{$a['area_id']}}]" id="" class="fl" value="{{$province['area_id']}}" <?php if(in_array($a['area_id'],$sale_area)){echo 'checked';}?>>
	                                       <span class="fl ml5">{{$a['name']}}</span>
                                       </li>
                                       @endforeach
                                     </ul>
                                   </dd>
                                   <div class="clear"></div>
                                   <?php $i++;?>
                                   @endforeach
                                 </dl>
								@endforeach
                                
                                 



                               </div>
                          </div>
                          <div class="popup-control">
                              <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">保存</a>
                              <div class="clear mt10"></div>
                          </div>
                      </div>
                  </div>
                  
                 </form>
                 
                  <input type="hidden" name="gps_city" value="{{$gps_city}}">
 
 		
                   
                </div>
                
@endsection
@section('js')
<script type="text/template" id="city-list-wrapper-tpl">
       <li class="clear city-list">
          <ul class="notype nopadding persent back-gray ml-20">
             {0}
             <div class="clear"></div> 
           </ul>
       </li>
    </script>
    <script type="text/template" id="city-list-item-tpl">
       <li data-id="{1}"><input type="checkbox" name="" id="" class="fl"><span class="fl ml5">{0}</span></li>
    </script>
    

	 <script type="text/javascript">
	 var _bjStatus = 'edit';
        seajs.use(["module/custom/custom_admin",
                   "module/custom/custom.admin.common.jquery",
                   "module/custom/custom.admin.car.prices.jquery", 
                   "module/common/common",
                   "bt"],
                   function(a,b,c){
        			b.initAutocomplete("/dealer/baojia/ajax-get-data/get-internal-id?brand_id={{$baojia['brand_id']}}&dealer_id={{$baojia['dealer_id']}}")
            
        			});
	</script>
@endsection