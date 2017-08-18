@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

                    <!--//账户信息-->
                    <h3 class="title">账户信息</h3>


                    <div class="content-wapper border">
                        <p><b>本人新的银行账户信息</b></p>
                        <form action="/dealer/member_info/modify_bank" class="ml50" method="post" name="bankCardForm" enctype="multipart/form-data" >
                         	<input type="hidden" name="_token" value="{{csrf_token()}}">
                               <div class="form-txt psr ">
                                  <label>&nbsp;开 户 行：</label>
                                  <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                      <div ms-on-click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                          <span class="dropdown-label"><span>{{str_replace(' ','',$member['seller_bank_city_str'])}}</span></span>
                                          <span class="caret"></span>
                                      </div>
                                      <div class="dropdown-menu dropdown-select area-tab-div">
                                          <input type="hidden" name="province" value="{{$bankAreaArray['province']}}"/>
                                          <input type="hidden" name="city" value="{{$bankAreaArray['city']}}"/>
                                          <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                          <dl class="dl">
                                              @foreach($province as $k=>$v)
                                                  <dd ms-on-click="selectProvince({{$k}})" >{{$v['name']}}</dd>
                                              @endforeach
                                            <div class="clear"></div>
                                          </dl>
                                          <dl class="dl" style="display: none;">
                                            <dd ms-repeat-city="citylist" ms-on-click="selectCity('city_id')" ><!--city.name--></dd>
                                            <div class="clear"></div>
                                          </dl>
                                      </div>
                                  </div>
                                  <div class="edit-wp edit-long">
                                      <input type="text" name="address" value="{{$member['seller_bank_addr']}}">
                                      <span class="edit"></span>
                                  </div>
                                  <div class="clear"></div>
                                  <p class="mt5"><span class=" inputerror hide ml75">请选择开户行省份、城市</span></p>
                                  <p class="mt5"><span class=" inputerror hide ml380">请填写开户行详细地址</span></p>
                               </div>

                               <div class="form-txt psr mt10">
                                  <label>账&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：</label>
                                  <input maxlength="19"  autocomplete="off" name="userCard" ms-on-keyup="showCardComplate" ms-on-focus="showCardComplate" ms-on-blur="hideComplate('card')"  type="text" name="txtName" class="complatetxt" value="{{$member['seller_bank_account']}}">
                                  <div class="inputcomplate psa hide" style="left:72px;z-index: 33">

                                  </div>
                                  <span class="inputerror  hide mt5 ml75">请正确输入银行卡号</span>
                                  <p class="mt5"><span class=" inputerror hide ml75">请填写银行卡号</span></p>
                               </div>
                               <div class="form-txt psr">
                                  <label>户&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</label>
                                  <span>徐琴提</span>
                               </div>


                               <div class="form-txt mt10">
                                  <label>上传银行卡：</label>
                                  <span class="fs12">（每张图片大小不超过5M，支持jpg、png、bmp格式）</span>
                                  <div class="card-wapper">
                                      <dl>
                                          <dd>
                                              <img data-enable="0" ms-on-click="upload" src="/upload/{{$member['seller_bank_photo1']}}" onerror="this.src='/themes/images/user/cd-0.gif'" alt="" style="width:130px;height:75px;">
                                              <input type="file" name="bank_photo1" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                              <input type="hidden" name="hfFile" >
                                              <span class=" inputerror hide">请上传银行卡正面</span>
                                          </dd>
                                          <dd>
                                              <img data-enable="0" ms-on-click="upload" src="/upload/{{$member['seller_bank_photo2']}}" onerror="this.src='/themes/images/user/cd-1.gif'" alt="" style="width:130px;height:75px;">
                                              <input type="file" name="bank_photo2" ms-on-change="changeAndPreview(this)" id="hfUpload" class="hide" value="">
                                              <input type="hidden" name="hfFile">
                                              <span class=" inputerror hide">请上传银行卡反面</span>
                                          </dd>
                                      </dl>
                                  </div>
                               </div>


                               <p>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger w150 fs16" ms-on-click="SubmitCardInfo">提交</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger ml50 w150 fs16">返回</a>
                               </p>
                               <br />
                               <div class="fs16">
                                    <span class="fl">温馨提示：</span>
                                    <span class="fl w600">办理提现时，只支持退款到您本人的银行账户。此银行账户的户名必须与您认证的姓名一致。</span>
                                    <div class="clear"></div>

                               </div>
                          </form>
                    </div>

                </div>

@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"]);
	</script>
@endsection