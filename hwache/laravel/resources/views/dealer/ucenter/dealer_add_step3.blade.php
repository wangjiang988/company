@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

 
                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step">
                             <li><span>基本资料</span></li>
                             <li><span>服务专员</span></li>
                             <li class="prev"><span>保险条件</span></li>
                             <li class="cur cur-step"><span>上牌条件</span></li>
                             <li><span>临牌条件</span></li>
                             <li><span>免费提供</span></li>
                             <li><span>杂费标准</span></li>
                             <li><span>刷卡标准</span></li>
                             <li><span>补贴情况</span></li>
                             <li class="last"><span>竞争分析</span></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">上牌条件</h2>
                       <div class="m-t-10"></div>
                       @if($daili['dl_step'] == 3)
                       <form action="/dealer/ajaxsubmitdealer/next-step/{{$dealer_id}}" name="next-form">
                         <div class="tbl-list-tool-panle valite-xx">
                              <p><b>上牌（车辆注册登记）：</b></p>
                              <label class="radio-label"><input type="radio" name="dl_shangpai" id="" value='1' checked><span>本地客户上牌手续必须由经销商代办</span>
                              </label>
                              <label class="radio-label"><input type="radio" name="dl_shangpai" id=""><span>本地客户自由办理</span></label>
                              <div class="clear"></div>
                              <span class=" fl"><b>代办上牌服务费金额</b>  ￥</span> 
                                    <div class="edit-wp psr fl ml5">
                                        <input maxlength="8" class="positive-integer" type="text" name="dl_shangpai_fee">
                                        <span class="edit"></span>
                                       
                                    </div>
                              <span class="error-info error-div fl ml10 juhuang">*请输入数字格式，且须为100的整数倍，例如100</span>
                              <span class="fl ml10 gray-info fs14">请输入100的整数倍的数字</span>
                              <div class="clear"></div>
                          </div>
                          <div class="tbl-list-tool-panle valite-yy">
                              <p class=""><b>客户本人上牌违约赔偿</b></p>
                              <label class="radio-label ml26"><input type="radio" name="dl_shangpai_object" id="" value='1' ><span>有此要求</span><span class="ml10"><b>客户本人上牌违约赔偿金额：</b>￥</span>
                                  <div class="edit-wp psr ml5">
                                      <input maxlength="8" class="positive-integer noweight" type="text" name="dl_shangpai_object_fee">
                                      <span class="edit"></span>
                                       <span class="error-info error-div juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                       <span class="error-info show ml10 gray-info fs14">请输入100的整数倍的数字</span>
                                  </div>
                              </label>
                              <label class="radio-label ml26"><input type="radio" name="dl_shangpai_object" id="" value='0' checked><span>无此要求</span></label>
                         </div>                       
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                         <p class="tac">
                            <a href="javascript:;"  class="btn btn-danger fs18 registrationEvent isnext">下一步</a>
                            <a href="/dealer/editdealer/check/{{$id}}/step2" class="juhuang tdu ml5"><span>返回上一步</span></a>
                         </p>
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='_token' value="{{csrf_token()}}">
                       </form>
                       @else
    
                         <form action="/dealer/ajaxsubmitdealer/save-step/{{$dealer_id}}" method="post" name="next-form">
                            <div class="tbl-list-tool-panle valite-xx">
                              <p><b>上牌（车辆注册登记）：</b></p>
                              <label class="radio-label"><input type="radio" name="dl_shangpai" id="" value='1' checked 
                              @if($daili['dl_shangpai'] == 1) checked="checked"; @endif
                              ><span>本地客户上牌手续必须由经销商代办</span>
                              </label>
                              <label class="radio-label"><input type="radio" name="dl_shangpai" id="" @if($daili['dl_shangpai'] == 0) checked="checked"; @endif ><span>本地客户自由办理</span></label>
                              <div class="clear"></div>
                              <span class=" fl"><b>代办上牌服务费金额</b>  ￥</span> 
                                    <div class="edit-wp psr  fl ml5">
                                        <input maxlength="8" class="positive-integer" type="text" name="dl_shangpai_fee" value="{{$daili['dl_shangpai_fee']}}">

                                        <span class="edit"></span>
                                       
                                    </div>
                              <span class="error-info error-div fl ml10 juhuang">*请输入数字格式，且须为100的整数倍，例如100</span>
                              <span class=" fl ml10 gray-info fs14">请输入100的整数倍的数字</span>
                              <div class="clear"></div>
                          </div>
                          <div class="tbl-list-tool-panle valite-yy">
                              <p class=""><b>客户本人上牌违约赔偿</b></p>
                              <label class="radio-label ml26"><input type="radio" name="dl_shangpai_object" id="" value='1' @if($daili['dl_shangpai_object'] == 1) checked="checked"; @endif ><span>有此要求</span><span class="ml10"><b>客户本人上牌违约赔偿金额：</b>￥</span>
                                  <div class="edit-wp psr ml5">
                                      <input maxlength="8" class="positive-integer noweight" type="text" name="dl_shangpai_object_fee" 
                                      value="{{$daili['dl_shangpai_object']==1?$daili['dl_shangpai_object_fee']:''}}">
                                      <span class="edit"></span>
                                       <span class="error-info error-div juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                       <span class="error-info show ml10 gray-info fs14">请输入100的整数倍的数字</span>
                                  </div>
                              </label>
                              <label class="radio-label ml26"><input type="radio" name="dl_shangpai_object" id="" value='0' @if($daili['dl_shangpai_object'] == 0) checked="checked"; @endif><span>无此要求</span></label>
                         </div>


                       
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                         <p class="tac">
                            <a href="javascript:;"  class="btn btn-danger fs18 registrationEvent isnext">下一步</a>
                            <a href="/dealer/editdealer/check/{{$id}}/step2" class="juhuang tdu ml5"><span>返回上一步</span></a>
                         </p>
                         <input type='hidden' name='step' value="3">
                         <input type="hidden" name="type" value="check">
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='_token' value="{{csrf_token()}}">
                       </form>
                       @endif

 

                    </div>
           </div>
                <div class="clear"></div>
            </div>                   

@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt", "module/custom/custom.dealer.step.common.fix.jquery"],function(a,b,c){
           
        });
	</script>
	
@endsection