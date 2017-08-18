@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                        <div class="custom-set-flow-step-wrapper">
                         <ul class="custom-set-flow-step custom-normal-flow-step">
                             <li class="cur"><span>基本资料</span></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step1"><span>服务专员</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step2"><span>保险条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step3"><span>上牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step4"><span>临牌条件</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step5"><span>免费提供</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step6"><span>杂费标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step7"><span>刷卡标准</span></a></li>
                             <li><a href="/dealer/editdealer/edit/{{$id}}/step8"><span>补贴情况</span></a></li>
                             <li class="last"><a href="/dealer/editdealer/edit/{{$id}}/step9"><span>竞争分析</span></a></li>
                             <div class="clear"></div>
                             <div class="clear"></div>
                         </ul>
                     </div>
                    <div class="content-wapper ">
                       <h2 class="title weighttitle">基本资料</h2>
                       <div class="m-t-10"></div>
                       <table class="tbl custom-info-tbl">
                             <tr>
                                 <td width="380"><b>销售品牌：</b>
                                 <span>{{$car_brand['gc_name']}}</span>
                                 </td>
                                 <td width="378"><b>归属地区：</b>
                                 <span>{{$dealer['d_areainfo']}}</span></td>
                             </tr>
                             <tr>
                                 <td width="380"><b>经销商：</b><span>{{$dealer['d_name']}}</span></td>
                                 <td width="378"><b>经销商编号：</b><span>{{$daili['d_id']}}</span></td>
                             </tr>
                             <tr>
                                 <td width="380"><b>营业地点：</b><span>{{$dealer['d_yy_place']}}</span></td>
                                 <td width="378"><b>交车地点：</b><span>{{$dealer['d_jc_place']}}</span></td>
                             </tr>
                             <tr>
                                 <td width="380"><b>经销商简称：</b><span>{{$daili['d_shortname']}}</span></td>
                                 <td width="378"><b>类别：</b><span>授权4S</span></td>
                             </tr>
                             <tr>
                                 <td width="380"><b>开户行：</b><span>{{$daili['dl_bank_addr']}}</span></td>
                                 <td width="378"><b>统一社会信用代码：</b><span>{{$daili['dl_code']}}</span></td>
                             </tr>
                             <tr>
                                 <td width="380"><b>账号：</b><span>{{$daili['dl_bank_account']}}</span></td>
                                 <td width="378"><b></b><span></span></td>
                             </tr>
                                    
                        </table>
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
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"]);
	</script>
@endsection