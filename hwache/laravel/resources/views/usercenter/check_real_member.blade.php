@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
				<div class="content-wapper">
                        @if($memberInfo['name_verify'] == 2)
                         <div class="wapper has-min-step">
                              <div class="mt60"></div>
                              <h2 class="tac pay-s ">您已完成实名认证！</h2>
                              <div class="ml200">
                                  <p class="p pl30 mt10">姓      名： {{$memberInfo['member_truename']}}</p>
                                  <p class="p pl30 mt10">身份证号：  {{$memberInfo['card_num']}}</p>
                                  <p class=" mt10">
                                      <a href="javascript:;" ms-on-click="viewImg('/upload/<?php if(!empty($memberInfo['card_photo1'])){echo $memberInfo['card_photo1'];}?>')" class="juhuang tud">查看身份证正面图片</a>
                                      <a href="javascript:;" ms-on-click="viewImg('/upload/<?php if(!empty($memberInfo['card_photo2'])){echo $memberInfo['card_photo2'];}?>')" class="juhuang tud ml20">查看身份证反面图片</a>
                                  </p>
                                  <p class="p pl30 mt10">开 户 行： （ {{$memberInfo['bank_city']}}） {{$memberInfo['bank_addr']}}</p>
                                  <p class="p pl30 mt10">银行卡号：  {{$memberInfo['bank_account']}}</p>
                                  <p class=" mt10">
                                      <a href="javascript:;" ms-on-click="viewImg('/upload/<?php if(!empty($memberInfo['bank_photo1'])){echo $memberInfo['bank_photo1'];}?>')" class="juhuang tud">查看银行卡正面图片</a>
                                      <a href="javascript:;" ms-on-click="viewImg('/upload/<?php if(!empty($memberInfo['bank_photo2'])){echo $memberInfo['bank_photo2'];}?>')" class="juhuang tud ml20">查看银行卡反面图片</a>
                                  </p> 
                              </div>
                              <p class="tac">
                                 {{-- <a href="javascript:;" class="btn btn-s-md btn-danger ml20 w150  fs16">确认</a>--}}
                              </p>
                              
                               
                         </div>
                         @else



                        <form action="./check_real_member" method="post" name="valiteForm" enctype="multipart/form-data">
                             <!--//未认证显示-->
                             <div class="form-txt">
                                <label>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</label>
                                <input type="text" class="shot" name="username" value="{{$memberInfo['member_truename']}}">
                                <a href="#" class="link ">一经认证，将无法修改</a>
                                <p class="mt5"><span class="inputerror  hide  ml75">*我的姓名不能为空，请重新输入</span></p>
                                
                             </div>

                             <div class="form-txt psr">
                                <label>身份证号：</label>
                                <input maxlength="18" type="text" autocomplete="off" name="userNumcard" ms-on-keyup="showNumComplate" ms-on-focus="showNumComplate" ms-on-blur="hideComplate('numcard')" class="complatetxt" value="{{$memberInfo['card_num']}}">
                                <div class="inputcomplate psa hide">
                                    
                                </div>
                                <p class="mt5"><span class=" inputerror hide ml75">您输入的身份证号不正确，请重新输入</span></p>
                             </div>

                             <div class="form-txt mt10">
                                <label>上传身份证：</label>
                                <span class="fs12">（每张图片大小小于5M）</span>
                                <div class="card-wapper">
                                    <dl>
                                        <dd>
                                            <img data-enable="0" ms-on-click="upload" src="<?php if(!empty($memberInfo['card_photo1'])){echo '/upload/'.$memberInfo['card_photo1'];}else{echo '/themes/images/user/card-0.gif';}?>" onerror="this.scr='/themes/images/user/card-0.gif'" alt="" style="width:135px;height:75px;">
                                            <input type="file" name="card_photo1" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                            <input type="hidden" name="hfFile" >
                                            <span class=" inputerror hide">请上传身份证正面</span>
                                        </dd>
                                        <dd>
                                            <img data-enable="0" ms-on-click="upload" src="<?php if(!empty($memberInfo['card_photo2'])){echo '/upload/'.$memberInfo['card_photo2'];}else{echo '/themes/images/user/card-1.gif';}?>" onerror="this.scr='/themes/images/user/card-1.gif'" alt="" style="width:135px;height:75px;">
                                            <input type="file" name="card_photo2" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                            <input type="hidden" name="hfFile">
                                            <span class=" inputerror hide">请上传身份证反面</span>
                                        </dd>
                                    </dl>
                                </div>
                             </div>

                             <div class="form-txt psr">
                                <label>开 户 行：</label>
                                <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                    <div ms-on-click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                        <span class="dropdown-label"><span id='areainfo'>{{$memberInfo['bank_city'] or '请选择开户行所在省市'}}</span></span>
                                        <span class="caret"></span>
                                    </div>
                                    <div class="dropdown-menu dropdown-select area-tab-div">
                                    	<?php 
                                    		if(!empty($memberInfo['bank_city'])){
                                    			$areaArr = explode('-',$memberInfo['bank_city']);
                                    		}else{
                                    			$areaArr = array(0=>'',1=>'');
                                    		}
                                    	?>
                                        <input type="hidden" name="province"  value="{{$areaArr[0]}}"/>
                                        <input type="hidden" name="city" value="{{$areaArr[1]}}"/>
                                        
                                        <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                        <dl class="dl">
                                    @foreach($topArea as $k=>$v)
                                        <dd ms-on-click="selectProvince({{$v['area_id']}})">{{$v['area_name']}}</dd>
                                    @endforeach
                                          <div class="clear"></div>
                                        </dl>
                                        <dl class="dl" style="display: none;">
                                          <dd ms-repeat-city="citylist" ms-on-click="selectCity"><!--city.name--></dd>
                                          <div class="clear"></div>
                                        </dl>
                                    </div>
                                </div>
                                <div class="edit-wp edit-long">
                                    <input type="text" name="address" value="{{$memberInfo['bank_addr']}}">
                                    <span class="edit"></span>
                                </div>
                                <div class="clear"></div> 
                                <p class="mt5"><span class=" inputerror hide ml75">请选择开户行省份、城市</span></p>
                                <p class="mt5"><span class=" inputerror hide ml380">请填写开户行详细地址</span></p>
                             </div>

                             <div class="form-txt psr mt10">
                                <label>银行卡号：</label>
                                <input maxlength="19"  autocomplete="off" name="bank_account" ms-on-keyup="showCardComplate" ms-on-focus="showCardComplate" ms-on-blur="hideComplate('card')"  type="text" name="txtName" class="complatetxt" value="{{$memberInfo['bank_account']}}">
                                <div class="inputcomplate psa hide">
                                    
                                </div>
                                <span class="inputerror  hide mt5 ml75">请正确输入银行卡号</span>
                                <p class="mt5"><span class=" inputerror hide ml75">请填写银行卡号</span></p>
                             </div>


                             <div class="form-txt mt10">
                                <label>上传银行卡：</label>
                                <span class="fs12">（每张图片大小小于5M）</span>
                                <div class="card-wapper">
                                    <dl>
                                        <dd>
                                            <img data-enable="0" ms-on-click="upload" src="<?php if(!empty($memberInfo['bank_photo1'])){echo '/upload/'.$memberInfo['bank_photo1'];}else{echo '/themes/images/user/cd-0.gif';}?>" onerror="this.scr='/themes/images/user/card-1.gif'" alt="" style="width:135px;height:75px;">
                                            <input type="file" name="bank_photo1" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                            <input type="hidden" name="hfFile" >
                                            <span class=" inputerror hide">请上传银行卡正面</span>
                                        </dd>
                                        <dd>
                                            <img data-enable="0" ms-on-click="upload" src="<?php if(!empty($memberInfo['bank_photo2'])){echo '/upload/'.$memberInfo['bank_photo2'];}else{echo '/themes/images/user/cd-1.gif';}?>" onerror="this.scr='/themes/images/user/card-1.gif'" alt="" style="width:135px;height:75px;">
                                            <input type="file" name="bank_photo2" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                            <input type="hidden" name="hfFile">
                                            <span class=" inputerror hide">请上传银行卡反面</span>
                                        </dd>
                                    </dl>
                                </div>
                             </div>
                             
                             <p>
                             @if($memberInfo['name_verify'] == 1)
                                <a href="javascript:;" class="btn btn-s-md btn-danger w150 fs16" >已提交</a>
                             @else
                             	<a href="javascript:;" class="btn btn-s-md btn-danger w150 fs16" ms-on-click="SubmitValite">提交</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger ml50 w150 fs16">返回</a>
                             @endif
                             </p>
                             <input type="hidden" name="act_form" value="sub">
                             <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         </form>
						@endif
                         <div class="viewimg">
                             <div class="psr view-wapper">
                                 <img src="" width="350">
                                 <a href="javascript:;" ms-on-click="closeImg" class="closeimg"></a>
                             </div>
                         </div>

 


                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection