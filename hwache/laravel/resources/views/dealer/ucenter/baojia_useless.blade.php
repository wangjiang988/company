@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                     
                     
                    <form action="" >
                        <input type='hidden' name='_token' value="{{csrf_token()}}">
                        <div class="content-wapper noleftrightpadding">
                           
                           <table class=" custom-info-tbl noborder mt10">
                             <tbody> 
                               <tr>
                                   <td class="tar nopadding" valign="top" style="width:42px"><span class="blue weight">筛选：</span></td>
                                   <td class="tal nopadding">
                                      <div class="btn-group btn-group-auto btn-jquery-event-dealer fl">
                                          <button data-toggle="dropdown" class="btn btn-select  btn-default dropdown-toggle btn-380 bt-jquery-dealer-list">
                                              <span class="dropdown-label"><span>经销商不限</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-380 dropdown-company">
                                              <input type="hidden" name="dealer_name" value=''>
                                              @foreach($dealerList as $dealer)
                                                  <li
                                                          data-dealer-id="{{$dealer['d_id']}}"
                                                          data-dealer-area="{{$dealer['place']}}"
                                                          data-dealer-area-id="{{$dealer['d_shi']}}"
                                                          data-car-brand="{{$dealer['car_brand']}}"
                                                          data-car-brand-id="{{$dealer['dl_brand_id']}}"
                                                          data-daili-dealer-id="{{$dealer['daili_dealer_id']}}"
                                                  ><a><span>{{$dealer['d_name']}}({{$dealer['gc_name']}})</span></a></li>
                                              @endforeach
                                          </ul>
                                      </div>
                                       <div class="error-div text-left"><label>*请选择经销商</label></div>
                                       <input type="hidden" name="dealer_id" value=''>
                                       <input type="hidden" name="daili_dealer_id" value=''>
                                      <div class="clear"></div>
                                      <div class="btn-group inline-block btn-jquery-event mt10 btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label"><span id="brand-str-default">品牌不限</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <input type="hidden" name="hfbrand">
                                            <input type="hidden" name="car-brand-id">
                                      </div>
                                        <div class="btn-group  inline-block ml10 btn-jquery-event mt10 btn-jquery-event-brand-chexi btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label"><span id="chexi-label-str">请选择车系</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="hfbrand-chexi">

                                            </ul>
                                        </div>
                                        
                                        <div class="btn-group  inline-block ml10 btn-jquery-event mt10 btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select  btn-default dropdown-toggle btn-380 btn-jquery-event-brand-chexing">
                                                <span class="dropdown-label"><span id="chexing-label-str">请选择车型规格</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select  dropdown-380">
                                                <input type="hidden" name="hfbrand-chexing">
                                            </ul>
                                        </div>

                                        <div class="btn-group  inline-block btn-jquery-event mt10 fl btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-default dropdown-toggle btn-jquery-event-car-color">
                                                <span class="dropdown-label"><span id="body-color">车身颜色不限</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="car_body_color">
                                            </ul>
                                        </div>

                                        <div class="btn-group inline-block btn-jquery-event mt10 fl ml10 btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-default dropdown-toggle">
                                                <span class="dropdown-label"><span>失效时间不限</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="time_circle">
                                                <li class="active"><a><span>失效时间不限</span></a></li>
                                                <li><a><span data-time-circle="一周内">一周内</span></a></li>
                                                <li><a><span data-time-circle="一个月内">一个月内</span></a></li>
                                                <li><a><span data-time-circle="三个月内">三个月内</span></a></li>
                                                <li><a><span data-time-circle="三个月外">三个月外</span></a></li>
                                            </ul>
                                        </div>

                                       <div class="btn-group inline-block btn-jquery-event mt10 ml10 fl btn-auto">
                                           <button data-toggle="dropdown" class="btn btn-select btn-default dropdown-toggle">
                                               <span class="dropdown-label"><span>失效原因不限</span></span>
                                               <span class="caret"></span>
                                           </button>
                                           <ul class="dropdown-menu dropdown-select">
                                               <input type="hidden" name="timeout_reason">
                                               <li class="active"><a><span>失效原因不限</span></a></li>
                                               <li><a><span>已被订购</span></a></li>
                                               <li><a><span>主动终止</span></a></li>
                                               <li><a><span>过时失效</span></a></li>
                                           </ul>
                                       </div>

                                       <a href="javascript:;" class="btn btn-danger fs16 sure bt mt10 fl ml10 submit">搜索</a>
                                       <a href="javascript:;" class="btn btn-danger fs16 sure bt mt10 fl ml10 reset">重置</a>
                                       <div class="clear"></div>

                                   </td>
                                  
                               </tr>
                             </tbody>
                           </table>

                            @include('dealer.ucenter.baojia_useless_table')

                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                            
                        </div>
                        
                    </form>
                    <div class="m-t-200"></div>

                    <div id="DelWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd tac msg">确定要直接将报价{0}下架吗？</p>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </form>
                      </div>
                    </div>

                    <div id="NoWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd tac ">暂无可恢复生效报价~</p>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </form>
                      </div>
                    </div>
                   
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </div>

    <div class="box" ms-include-src="footernew"></div>

@endsection                
 
@section('js')

    <script type="text/javascript">
        seajs.use(["module/custom/custom_admin",
            "module/custom/custom.admin.common.jquery",
            "module/custom/custom.admin.car.prices.jquery",
            "module/custom/custom.admin.offer-new-useless.jquery",
            "module/common/common",
            "bt"],function(a,b,c){

        });
    </script>
@endsection


