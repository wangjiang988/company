@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
					<h2 class="title">个人信息</h2>
                   {{-- <form action="" method="post" name="infoform">--}}
                        <div class="content-wapper">
                            <p><label>我的会员号： </label>{{$memberInfo['member_name']}}</p>
                            @if($memberInfo['name_verify'] != 2)
                            <p class="form-txt">
                                <span class="xing">*</span>
                                <label>我的姓名：</label>
                                <input type="text" name="txtName" ms-on-blur="checkMyName"  value="{{$memberInfo['member_truename']}}"/>
                                <a href="/user/memberSafe/check_real_member" class="link tdu">认证</a>
                                <span class="inputerror hide">*我的姓名不能为空，请重新输入</span>
                                <input type="hidden" name="nameIsValite" value="0">
                            </p>
                            <p class="inputerror pl85 hide" id="uservalite">*您的姓名还未进行认证，请先进行实名认证</p>
                            @else
                             <p class="form-txt">
                                <span class="xing">*</span>
                                <label>我的姓名：</label>
                                <span class="txt">{{$memberInfo['member_truename']}}</span> 
                                <span>已认证，不可修改</span> 
                            </p>
                            
                            @endif
                            
                            
                            <p class="form-txt">
                                <span class="xing">*</span>
                                <label>我的手机：</label>
                                <span class="txt">{{$memberInfo['member_mobile']}}</span>
                                <a href="/user/memberSafe/phone_change" class="link tdu">修改</a>
                                <span>已验证</span>
                            </p>
                            
                            @if($memberInfo['email_verify'] != 2)
                            <p class="form-txt">
                                <span class="xing">*</span>
                                <label>常用邮箱：</label>
                                <input type="text" name="txtEmail" ms-on-blur="checkMyEmail" value="{{$memberInfo['member_email']}}"/>
                                <a href="/user/memberSafe/email_check" class="link tdu">验证</a>
                                <span class="inputerror hide">*邮箱格式不正确，请重新输入</span>
                                <input type="hidden" name="emailIsValite" value="0">
                            </p>
                            <p class="inputerror pl85 hide" id="emailvalite">*您的邮箱还未进行验证，请先验证邮箱的可用性</p>
                            @else
                            <p class="form-txt">
                                <span class="xing">*</span>
                                <label>常用邮箱：</label>
                                <span class="txt"> {{$memberInfo['member_email']}}</span>
                                <!-- // <a href="/user/memberSafe/email_check" class="link tdu">修改</a>-->
                                <span>已验证</span>
                            </p>
                            @endif
                            <!--//已经编辑后显示的方式-->
                           

                            
                            <!--//已经编辑后显示的方式 end-->
							{{--
                            <p class="tac">
                                <a ms-on-click="checkInfoForm" href="javascript:;" class="btn btn-danger fs16 w150">提交</a>
                                <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                            </p>
                        </div>
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="hidden" name="act" value='submit_info'>
                    </form>
                    --}}
                    <h2 class="title">补充信息</h2>
                    
                    <div class="content-wapper">
                    <?php
                    if(!empty($memberInfo['member_title']) && !empty($memberInfo['member_areainfo']) && !empty($memberInfo['member_address'])){
                    	$cssForm1 = "display:none";
                    	$cssForm2 = "";
                    }else{
                    	$cssForm1 = "";
                    	$cssForm2 = "display:none";
                    }
                    ?>
						<form action="" method="post" enctype="multipart/form-data" name='otherInfoForm' style='{{$cssForm1}}'>
                        <div class="psr">
                            <label class="prefix">对我的称呼：</label>
                            <label class="input"><input type="radio" name="title"  value="女士" <?php if($memberInfo['member_title']=='女士'){echo 'checked';}?>>女士</label>
                            <label class="input"><input type="radio" name="title" value="先生" <?php if($memberInfo['member_title']=='先生'){echo 'checked';}?>>先生</label>
                            <label class="input"><input type="radio" name="title" value="小姐" <?php if($memberInfo['member_title']=='小姐'){echo 'checked';}?>>小姐</label>
                            <label class="input"><input type="radio" name="title" value="other" <?php if($memberInfo['member_title']!='' && $memberInfo['member_title']!='女士' &&$memberInfo['member_title']!='先生'&&$memberInfo['member_title']!='小姐'){echo 'checked';}?>>其他</label>
                            <div class="edit-wp">
                                <input type="text" name="other_title" value='<?php if($memberInfo['member_title']!='' && $memberInfo['member_title']!='女士' &&$memberInfo['member_title']!='先生'&&$memberInfo['member_title']!='小姐'){echo $memberInfo['member_title'];}?>'>
                                <span class="edit"></span>
                            </div>
                        </div>
                        <div class="form-txt psr mt10"> 
                            <label class="prefix">常用地址：</label>
                            <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                <div ms-on-click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                    <span class="dropdown-label"><span id='areainfo'>{{$memberInfo['member_areainfo']}} &nbsp;</span></span>
                                    <span class="caret"></span>
                                </div>
                                <?php 
                                    		if(!empty($memberInfo['member_areainfo'])){
                                    			$areaArr = explode('-',$memberInfo['member_areainfo']);
                                    			if(!isset($areaArr[1])){
                                    				$areaArr[1] = '';
                                    			}
                                    		}else{
                                    			$areaArr = array(0=>'',1=>'');
                                    		}
                                    	?>
                                <div class="dropdown-menu dropdown-select area-tab-div">
                                    <input type="hidden" name="province" value="{{$areaArr[0]}}" />
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
                            <div class="clear"></div>
                            <div class="user-pic tac psa">
                                <img src="<?php if(!empty($memberInfo['member_avatar'])){echo '/upload/'.$memberInfo['member_avatar'];}else{echo '/themes/images/user/upload-face.gif';}?>" width="65"  ms-on-click="upload" alt="">
                                <input type="file" name="file" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                <input type="hidden" name="" id="hfFile">
                                <input type="hidden" name="orgin_avatar" value="{{$memberInfo['member_avatar']}}">
                                <p>仅支持JPG、GIF、PNG、JPEG、<br>BMP格式，文件小于5M</p>
                            </div>
                        </div>
                        <p class="form-txt mt5"> 
                            <label class="prefix"></label>
                            <textarea name="address">{{$memberInfo['member_address']}}</textarea>
                        </p>
                        <p class="tac">
                            <a  ms-on-click="check_other_info_member" href="javascript:;" class="btn btn-danger fs16 w150">提交</a>
                            <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                        </p>
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="hidden" name="act" value='submit_other_info'>
						</form>
                        <p class="form-txt" style='{{$cssForm2}}' id='mytitle'>
                            <label class="prefix">对我的称呼：</label>
                            <span >{{$memberInfo['member_title']}}</span>
                        </p>
                        <div class="form-txt psr" style='{{$cssForm2}}' id='myaddress'>
                            <label class="prefix">常用地址：</label>
                            <span >({{$memberInfo['member_areainfo']}}){{$memberInfo['member_address']}}</span>
                            <div class="user-pic tac psa">
                                <img src="<?php if(!empty($memberInfo['member_avatar'])){echo '/upload/'.$memberInfo['member_avatar'];}else{echo '/themes/images/user/upload-face.gif';}?>" width="65"  alt="">
                            </div>
                        </div>
                        <p class="tac" style='{{$cssForm2}}' id='sub_but'>
                            <a href="javascript:;" class="btn btn-danger fs16 w150" ms-on-click="edit_member_other_info">修改</a>
                        </p>

                    </div>
                    <div class="clear"></div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection