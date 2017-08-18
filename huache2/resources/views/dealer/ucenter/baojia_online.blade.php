@extends('_layout.base_dealercenter')
<?php $title="正在报价";?>
@section('css')

@endsection

@section('nav')

@endsection

@section('content')


                    <form action="/dealer/baojialist/online" id="Filter-query">
                        <div class="content-wapper noleftrightpadding">

                           <table class=" custom-info-tbl noborder mt10">
                             <tbody>
                               <tr>
                                   <td class="tar nopadding" valign="top"><span class="blue weight">筛选：</span></td>
                                   <td class="tal nopadding">
                                      <div class="btn-group btn-jquery-event fl btn-group-auto">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle  btn-380">
                                              <span class="dropdown-label">
                                                @if(!empty($bj_dealer))
                                                <span>{{$bj_dealer}}</span>
                                                @else
                                                <span>--经销商不限--</span>
                                                @endif
                                              </span>
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
                                      <a href="javascript:;" class="btn btn-danger fs14 ml50 fr wauto fff ntdu mt0" id="Cease-all">一键暂停当前所有有效报价</a>
                                      <div class="clear"></div>
                                      <div class="btn-group inline-block btn-jquery-event mt10 btn-auto">
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
                                                <li class="find-cars" data-id="{{$brand->gc_id}}" data-type="online"><a><span>{{$brand->gc_name}}</span></a></li>
                                                @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="btn-group  inline-block ml10 btn-jquery-event mt10 btn-auto">
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
                                         <input type="hidden" name="bj_type" value="online" class="types">
                                        <div class="btn-group  inline-block ml10 btn-jquery-event mt10 btn-price btn-group-auto">
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
                                            <ul class="dropdown-menu dropdown-select standard dropdown-380">
                                                <input type="hidden" name="standard">
                                                <input type="hidden" name="pid">
                                            </ul>
                                        </div>
                                        <div class="clear"></div>

                                        <div class="btn-group  inline-block btn-other-event mt10 fl">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label">
                                                @if(isset($xianche))
                                                @if($xianche == 1)
                                                <span>现车</span>
                                                @elseif($xianche == 0)
                                                <span>非现车</span>
                                                @endif
                                                @else
                                                <span>现车非现车不限</span>
                                                @endif
                                                </span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="xianche">
                                                <li class="active"><a><span>现车非现车不限</span></a></li>
                                                <li data-id='1'><a><span>现车</span></a></li>
                                                <li data-id='0'><a><span>非现车</span></a></li>
                                            </ul>
                                        </div>
                                        <div class="btn-group  inline-block ml10 btn-other-event mt10 fl">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle">
                                                <span class="dropdown-label"><span>结束设置时间不限</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="state">
                                                <li data-id='0'><a><span>有结束设置时间</span></a></li>
                                                <li data-id='1'><a><span>无结束设置时间</span></a></li>
                                            </ul>
                                        </div>

                                      @if(empty($price['zhidaojia']))
                                      <span class="ml10 inline-block mt10 fl guide-price">
                                        @elseif (!empty($price['zhidaojia']))
                                        <span class="ml10 inline-block mt10 fl guide-price guidelines">
                                        厂商指导价:￥{{$price['zhidaojia']}}
                                        @endif
                                      </span>
                                      <a href="javascript:;" class="btn btn-danger fs14 mt10 cfff tdn bt fl ml10 submit-search">查找</a>
                                      <a href="javascript:;" class="btn btn-danger fs14 sure bt tdn mt10 fl ml10 reset">重置</a>
                                      <div class="clear"></div>

                                   </td>

                               </tr>
                             </tbody>
                           </table>
                          @include('dealer.ucenter.baojia_online_table')
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
                                   <p class="fs14 pd msg" id="msg" style="text-align:center">确定将该报价{0}终止吗？</p>
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
                                   <p class="fs14 pd msg" id="msg" style="text-align:center">确定将报价{0}暂时下架吗？</p>
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

                    <div id="CeaseAllWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd" style="text-align:center">确定将当前所有有效报价暂时下架吗？</p>
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
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.offer-new-unfinished.jquery", "module/common/common", "bt"],function(a,b,c){

        });
    </script>
@endsection


