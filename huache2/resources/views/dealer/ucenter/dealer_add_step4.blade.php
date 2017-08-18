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
                             <li class="prev"><span>上牌条件</span></li>
                             <li class="cur cur-step"><span>临牌条件</span></li>
                             <li><span>免费提供</span></li>
                             <li><span>杂费标准</span></li>
                             <li><span>刷卡标准</span></li>
                             <li class="last"><span>竞争分析</span></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">临牌条件</h2>
                       <div class="m-t-10"></div>
                        @if($daili['dl_step'] == 4)
                         <form action="/dealer/ajaxsubmitdealer/next-step/{{$dealer_id}}" name="next-form" method="post">
                         <!-- <div class="tbl-list-tool-panle">
                            <p><b>上临时牌照（临时移动牌照）</b></p>
                              <label class="radio-label"><input type="radio" name="dl_linpai" id="" value='1' checked><span>车辆临时移动牌照手续必须由经销商代办（适用条件：a.经销商代办上牌，且客户选择上临牌 b.客户本人上牌）</span>
                              </label>
                              <label class="radio-label"><input type="radio" name="dl_linpai" id=""><span>本地客户自由办理</span></label>
                              <div class="clear"></div>
                              <span class=""><b>代办车辆临时牌照（每次）服务费金额：  ￥</b></span> 
                                    <div class="edit-wp psr">
                                        <input maxlength="8" type="text" name="dl_linpai_fee">
                                        <span class="edit"></span>
                                         <div class="clear"></div>
                                    </div>
                             <div class="error-info mt20 ml100 none juhuang">*请输入数字格式，且须为100的整数倍，例如100</div>
                              <span class="ml10 gray-info">请输入100的整数倍的数字</span>
                              <div class="clear"></div>
                          </div>  -->
                         <div class="ml20">
                            <p class="fs14"><b>上临时牌照（临时移动牌照）:  </b>客户自由办理</p>
                            <div class="m-t-10"></div>
                            <p class="fs14">
                                <b>代办车辆临时牌照（每次）服务费金额：</b>
                                <span class="ml10">￥</span>
                                <input name="dl_linpai_fee" maxlength="8" type="text" class="ml10 form-control w150 inline-block positive-integer">
                                <span class="error-info error-div juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                <span class="error-info error-div ml10 gray-info fs14">请输入100的整数倍的数字</span>
                            </p>
                         </div>     
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                         <p class="tac">
                            <a href="javascript:;" class="btn btn-danger fs18 registrationEvent registrationCondition-adv isnext">下一步</a>
                            <a href="/dealer/editdealer/check/{{$id}}/step3"  class="juhuang tdu ml5"><span>返回上一步</span></a>
                         </p>
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='_token' value="{{csrf_token()}}">
                       </form>
                         
                       @else

                       <form action="/dealer/ajaxsubmitdealer/save-step/{{$dealer_id}}" name="next-form" method="post">
                         <!-- <div class="tbl-list-tool-panle">
                                                 <p><b>上临时牌照（临时移动牌照）</b></p>
                                                 <label class="radio-label"><input type="radio" name="dl_linpai" id="" value='1' @if($dl_linpai == 1) checked @endif)><span>车辆临时移动牌照手续必须由经销商代办（适用条件：a.经销商代办上牌，且客户选择上临牌 b.客户本人上牌）</span>
                                                 </label>
                                                 <label class="radio-label"><input type="radio" name="dl_linpai" id="" @if($dl_linpai == 0) checked @endif) ><span>本地客户自由办理</span></label>
                                                 <div class="clear"></div>
                                                 <span class=""><b>代办车辆临时牌照（每次）服务费金额：  ￥</b></span> 
                                                       <div class="edit-wp psr">
                                                           <input class="positive-integer" type="text" name="dl_linpai_fee" value="{{$dl_linpai_fee}}">
                                                           <span class="edit"></span>
                                                            <div class="clear"></div>
                                                       </div>
                                                <div class="error-info mt20 ml100 none juhuang">*请输入数字格式，且须为100的整数倍，例如100</div>
                                                 <span class="ml10 gray-info">请输入100的整数倍的数字</span>
                                                 <div class="clear"></div>
                                             </div>     -->  

                         <div class="ml20">
                            <p class="fs14"><b>上临时牌照（临时移动牌照）:  </b>客户自由办理</p>
                            <div class="m-t-10"></div>
                            <p class="fs14">
                                <b>代办车辆临时牌照（每次）服务费金额：</b>
                                <span class="ml10">￥</span>
                                <input name="dl_linpai_fee" value="{{$dl_linpai_fee}}" maxlength="8" type="text" class="ml10 form-control w150 inline-block positive-integer">
                                <span class="error-info error-div juhuang fs14">*请输入数字格式，且须为100的整数倍，例如100</span>
                                <span class="error-info error-div ml10 gray-info fs14">请输入100的整数倍的数字</span>
                            </p>
                         </div>     
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                         <p class="tac">
                            <a href="javascript:;" class="btn btn-danger fs18 btn btn-danger fs18 registrationEvent registrationCondition-adv isnext">下一步</a>
                            <a href="/dealer/editdealer/check/{{$id}}/step3"  class="juhuang tdu ml5"><span>返回上一步</span></a>
                         <input type="hidden" name="type" value="check">
                         <input type='hidden' name='step' value="4">
                         <input type='hidden' name='id' value="{{$id}}">
                         <input type='hidden' name='_token' value="{{csrf_token()}}">
                       </form>
                       @endif
 

                    </div>

@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom-step4", "module/common/common", "bt"],function(a,b,c){
           
        });
	</script>
	
@endsection