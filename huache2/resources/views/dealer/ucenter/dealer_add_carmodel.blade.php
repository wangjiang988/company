@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')

 
                        <div class="content-wapper ">
                           <h2 class="title weighttitle">新增常用车型</h2>
                           <div class="m-t-10"></div>
                           <form action="/dealer/addcarmodel/{{$dealer_id}}/" method="post" id="forms">
                           <table class="custom-info-tbl ml-10" width="100%" cellpadding="0" cellspacing="0">
                               <tr>
                                   <td class="right" width="100">
                                       <span>品 牌：</span>
                                   </td>
                                   <td align="left">
                                  <span>{{$car_brand['gc_name']}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                       <span>车 系：</span>
                                   </td>
                                   <td align="left">
                                        <div class="btn-group new-offerings-drop-valite add-carmodl btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-models" >
                                                <span class="dropdown-label"><span>--请选择--</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-models dropdown-cars-demio">
                                            @if(count($goods_class)>0)
                                            @foreach ($goods_class as $goods)
                                                <li data-id="{{$goods['gc_id']}}"><a><span>{{$goods['gc_name']}}</span></a></li>
                                            @endforeach
                                            @endif
                                            </ul>
                                            <input type="hidden" name="" value="">                                           
                                        </div>
                                        <div class="error-div"><label>请选择车系</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                       <span>车型规格：</span>
                                   </td>
                                   <td align="left">
                                       <input type="hidden" name="dealer_id" value="{{$dealer_id}}">
                                       <div class="btn-group new-offerings-drop-valite add-carmodl btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-models" >
                                                <span class="dropdown-label"><span>--请选择--</span></span>
                                                <span class="caret"></span>
                                                <input type="hidden" name="gc_id_3" value="">
                                            </button>
                                            <ul class="dropdown-menu dropdown-models dropdown-cars-standard">                                         
                                            </ul>
                                            
                                        </div>
                                        <div class="error-div"><label>车型规格</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                       <span>整车型号：</span>
                                   </td>
                                   <td align="left">
                                       <span class="vehicle"></span>
                                       <div class="error-div"><label>整车型号</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right">
                                       <span>厂商指导价：</span>
                                   </td>
                                   <td align="left">
                                   <span class="disabled-span disabled-span-normal guide-price tal"></span>
                                   <input value="" readonly="" placeholder="" type="text" name="" disabled="disabled" class="form-control custom-control guide-price hide">
                                   <span class="check_hint hide red">该车型已经存在,请重新选择</span>
                             
                            </td>
                               </tr>
                           <input type="hidden" name="_token" value="{{csrf_token()}}">
                           </table>
                          </form>
                             <p class="tac mt10">
                              <a href="javascript:;" class="btn btn-danger fs16 btn-new-jixu btn-continue">
                                <span class="ml-20" >继续</span>
                                <i></i>
                              </a>
                           </p>
                        </div>
                        <div id="tip-error" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">操作失败!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                        </div>
                          
@endsection

@section('js')
<script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){
        });
  </script>
  
@endsection


