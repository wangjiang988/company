@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
 
                     <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step custom-normal-flow-step">
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step0"><span>基本资料</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step1"><span>服务专员</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step2"><span>保险条件</span></a></li>
                             <li class="cur"><a hrf="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">上牌条件</h2>
                       <div class="m-t-10"></div>
                         <div class="tbl-list-tool-panle">
					        <p><span class=" weight">上牌（车辆注册登记）：</span></p>
                              <label class="radio-label"><input type="radio" name="shangpai" id=""  disabled <?php if($daili['dl_shangpai']==1){echo 'checked';} ?>/><span>本地客户上牌手续必须由经销商代办</span>

                              </label>
                              <label class="radio-label"><input type="radio" name="shangpai" id=""  disabled <?php if($daili['dl_shangpai']==0){echo 'checked';} ?>/><span>本地客户自由办理</span></label>
                              <div class="clear"></div>
                              <span class=" fl"><b>代办上牌服务费金额：</b>￥</span> 
                              <span class="fl">{{$daili['dl_shangpai_fee']}}</span>
                              <div class="clear"></div>
                          </div>
                          <div class="tbl-list-tool-panle valite-1">
                              <p><b>客户本人上牌违约赔偿</b></p>
							  <label class="radio-label ml26">
                                <input type="radio" name="benrenshangpai" id="" disabled <?php if($daili['dl_shangpai_object']==1){echo 'checked';} ?>/>
                                <span>有此要求</span>
                                <span class="ml10"><b>客户本人上牌违约赔偿金额：</b></span>
                                <span class="nomargin nopadding">{{$daili['dl_shangpai_object']==1?'￥'.$daili['dl_shangpai_object_fee']:''}}</span>
								</label>
                            <label class="radio-label ml26"><input type="radio" name="benrenshangpai" disabled id="" <?php if($daili['dl_shangpai_object']==0){echo 'checked';} ?>><span>无此要求</span></label>          
                         </div>                       
                         <div class="m-t-10"></div>
                         <div class="m-t-10"></div>
                          <p class="tac">
                          <a href="javascript:;" class="btn btn-danger oksure fs18">等待审核</a>
                       </p>
                          <div class="m-t-10"></div>
                        <p class="tac"><b class="juhuang">温馨提示：</b>经销商基本信息审核中，审核通过后您可进行下一步常规车型等设置</p>
                    </div>
                      </div>
                <div class="clear"></div>
            </div>  
                   
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){
           
        });
	</script>
	
@endsection