@extends('_layout.base_dealercenter')
<?php $title="等待生效报价";?>
@section('css')

@endsection

@section('nav')

@endsection

@section('content')



                    <form action="/dealer/baojialist/effective" method="get" id="Filter-query">
                        <div class="content-wapper noleftrightpadding">

                           <table class=" custom-info-tbl noborder mt10">
                             <tbody>
                               <tr>
                                   <td class="tar nopadding" valign="top"><span class="blue weight">筛选：</span></td>
                                   <td class="tal nopadding">
                                      <div class="btn-group inline-block btn-jquery-event btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label">
                                                @if(!empty($bj_brand))
                                                <span>{{$bj_brand}}</span>
                                                @else
                                                <span>品牌不限</span>
                                                @endif

                                                </span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="brand">
                                                @if(isset($brands))
                                                @foreach ($brands as $brand)
                                                <li class="find-cars" data-id="{{$brand->gc_id}}" data-type="effective"><a><span>{{$brand->gc_name}}</span></a></li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="btn-group  inline-block ml10 btn-jquery-event btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label">
                                                @if(!empty($cars))
                                                <span>{{$cars}}</span>
                                                @else
                                                <span>车系不限</span>
                                                @endif
                                                </span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select place">
                                                <input type="hidden" name="cars">
                                            </ul>
                                        </div>
                                        <input type="hidden" name="bj_type" value="effective" class="types">
                                        <div class="btn-group  inline-block ml10 btn-jquery-event btn-price btn-auto">
                                            <button data-toggle="dropdown" class="btn btn-select  btn-default dropdown-toggle btn-380">
                                                <span class="dropdown-label">
                                                @if(!empty($standard))
                                                <span>{{$standard}}</span>
                                                @else
                                                <span>车型规格不限</span>
                                                @endif
                                                </span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select standard">
                                                <input type="hidden" name="standard" >
                                                <input type="hidden" name="pid">
                                            </ul>
                                        </div>


                                      <div class="clear"></div>
                                      <div class="btn-group btn-jquery-event mt10 fl btn-auto">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle  btn-380">
                                              <span class="dropdown-label">
                                               @if(!empty($bj_dealer))
                                                <span>{{$bj_dealer}}</span>
                                                @else
                                                <span>--经销商不限--</span>
                                                @endif</span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-380">
                                              <input type="hidden" name="dealer">
                                              @if(isset($dealers))
                                              @foreach($dealers as $dealer)
                                              <li><a><span>{{strstr($dealer->dealer_name,'(',true)}}</span></a></li>
                                              @endforeach
                                              @endif
                                          </ul>
                                      </div>
                                      @if(empty($price['zhidaojia']))
                                      <span class="ml10 inline-block mt10 fl guide-price">
                                        @elseif (!empty($price['zhidaojia']))
                                        <span class="ml10 inline-block mt10 fl guide-price guidelines">
                                        厂商指导价:￥{{$price['zhidaojia']}}
                                        @endif
                                        </span>
                                      <div class="clear"></div>
                                      <div class="no-offer fl mt10">
                                         <span class="fl mt5">设定生效时间：</span>
                                         <div class="btn-group m-r time-sl fl ml10">
                                            <div class="form-group psr pdi-control">
                                              <input readonly="" type="text" placeholder="{{date('Y-m-d',time())}}" id="realInp" name="start_time" class="form-control select-time-control start" />
                                              <i class="rili"></i>
                                            </div>
                                         </div>
                                         <span class="fl mt5 ml10">~</span>
                                         <div class="btn-group m-r time-sl fl ml10">
                                            <div class="form-group psr pdi-control">
                                              <input readonly="" type="text" name="end_time" placeholder="请选择时间" class="form-control select-time-control start" id="realInp2">
                                              <i class="rili"></i>
                                            </div>
                                         </div>
                                         <div class="clear"></div>
                                      </div>
                                      <a href="javascript:;" class="btn btn-danger fs14 mt10 cfff tdn bt fl ml10 submit-search">查找</a>
                                      <a href="javascript:;" class="btn btn-danger fs14 sure bt tdn mt10 fl ml10 reset">重置</a>
                                      <div class="clear"></div>

                                   </td>

                               </tr>
                             </tbody>
                           </table>
                          @include('dealer.ucenter.baojia_effective_table')
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
                                   <p class="fs14 pd" id="msg" style="text-align:center"></p>
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

                      <div id="StopWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd" id="msg" style="text-align:center"></p>
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
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.offer-new-unfinished.jquery", "module/common/common", "vendor/DatePicker/WdatePicker.js","vendor/DatePicker/EditwdatePicker.js","bt"],function(a,b,c){

        });
    </script>
@endsection


