@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
				<h2 class="title">银行账户管理</h2>

                        <div class="content-wapper">
                        @if($memberInfo['name_verify'] ==2)
                        <div class="mt20"></div>
                         {{-- <p class="fs14 tac">已添加过或者已实名认证过银行卡显示以下内容</p>--}}
                          
                          <div class="cardinfo">
                              <p class="fs14 ml260">开 户 行：（ {{$memberInfo['bank_city']}}） {{$memberInfo['bank_addr']}}</p>
                              <p class="fs14 ml260">账     号： {{$memberInfo['bank_account']}}</p>
                              <p class="fs14 ml260">户     名： {{$memberInfo['member_truename']}}</p>
                              <p class="tac ">
                                  <a href="javascript:;" ms-on-click="addAndEditCardForm" class="btn btn-s-md btn-danger w150 fs16 sure" >修改</a>
                              </p>
                          </div>
                        
                        @elseif($memberInfo['name_verify'] ==1)
                        <p class="mt10 tac">实名认证时 您已经提交银行账号，敬请等待华车平台审核，谢谢！</p>
                        <div class="cardinfo">
                              <p class="fs14 ml260">开 户 行：（ {{$memberInfo['bank_city']}}） {{$memberInfo['bank_addr']}}</p>
                              <p class="fs14 ml260">账     号： {{$memberInfo['bank_account']}}</p>
                              <p class="fs14 ml260">户     名： {{$memberInfo['member_truename']}}</p>
                              <p class="tac ">
                                  <a href="javascript:;" ms-on-click="addAndEditCardForm" class="btn btn-s-md btn-danger w150 fs16 sure" >修改</a>
                              </p>
                          </div>
                        @elseif($memberInfo['name_verify'] ==0)
                        	@if(empty($memberInfo['bank_account']))
                        		<p class="mt10 tac">您还没有维护银行账户！<a href="javascript:;" ms-on-click="addAndEditCardForm" class="juhuang">去添加>></a></p>
                        	@endif
                        
                          
                        @endif
                          <div class="mt20"></div>
                          <div class="ifl fs14"><label>温馨提示：</label></div>
                          <div class="ifl">
                              <p class="fs14">办理退款提现时，只支持退款到您本人的银行账户。此银行账户的户名必须与您认证的姓名一致。</p>
                              <p class="fs14">若您已经进行实名认证，银行账户默认为认证时的银行账户。</p>
                          </div>
                          <div class="clear"></div>
                          <!--//已添加-->
                          <?php
                          if(!empty($memberInfo['bank_account']) && $memberInfo['name_verify'] ==0){
                          	$hideCss = "";
                          }else{
                          	$hideCss = "hide";
                          }
                          
                          ?>
                          <form class="{{$hideCss}}" action="" method="post" name="bankCardForm" enctype="multipart/form-data">
                                
                               <div class="form-txt psr">
                                  <label>&nbsp;开 户 行：</label>
                                  <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                      <div ms-on-click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                          <span class="dropdown-label"><span>{{$memberInfo['bank_city']}} &nbsp;</span></span>
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
                                          <input type="hidden" name="province" value="{{$areaArr[0]}}"/>
                                          <input type="hidden" name="city"  value="{{$areaArr[1]}}"/>
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
                                              <img data-enable="0" ms-on-click="upload" src="<?php if(!empty($memberInfo['bank_photo1'])){echo '/upload/'.$memberInfo['bank_photo1'];}else{echo '/themes/images/user/cd-0.gif';}?>" onerror="this.src='/themes/images/user/cd-0.gif'" alt="" style="width:135px;height:75px;">
                                              <input type="file" name="bank_photo1" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                              <input type="hidden" name="hfFile" >
                                              <span class=" inputerror hide">请上传银行卡正面</span>
                                          </dd>
                                          <dd>
                                              <img data-enable="0" ms-on-click="upload" src="<?php if(!empty($memberInfo['bank_photo2'])){echo '/upload/'.$memberInfo['bank_photo2'];}else{echo '/themes/images/user/cd-1.gif';}?>" onerror="this.src='/themes/images/user/cd-1.gif'" alt="" style="width:135px;height:75px;">
                                              <input type="file" name="bank_photo2" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                              <input type="hidden" name="hfFile">
                                              <span class=" inputerror hide">请上传银行卡反面</span>
                                          </dd>
                                      </dl>
                                  </div>
                               </div>

                               <div class="form-txt psr">
                                  <label>户&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</label>
                                  <input type="text" class="shot" name="username" value="{{$memberInfo['member_truename']}}" <?php if($memberInfo['name_verify'] ==2){echo 'readonly';}?>>
                                  <span class="edit" style="left:150px;"></span>
                                  <p class="mt5"><span class="inputerror hide ml75">请填写户名</span></p>
                               </div> 
                               <p>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger w150 fs16" ms-on-click="SubmitCardInfo">提交</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger ml50 w150 fs16">返回</a>
                               </p>
                               <input type="hidden" name="act_form" value="sub">
                               <input type="hidden" name="_token" value="{{ csrf_token() }}">
                          </form>
                        </div>

                         <div class="viewimg">
                             <div class="psr view-wapper">
                                 <img src="" width="350">
                                 <a href="javascript:;" ms-on-click="closeImg" class="closeimg"></a>
                             </div>
                         </div>
@endsection         

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection