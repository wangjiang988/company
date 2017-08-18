@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                     
                     
                    <form action="/dealer/baojialist/suspensive" id="Filter-query">
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
                                      <a href="javascript:;" class="btn btn-danger fs14 ml50 fr wauto fff ntdu mt0" id="shelves-all">一键恢复所有已主动暂停报价</a>
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
                                                <li class="find-cars" data-id="{{$brand->gc_id}}" data-type="suspensive"><a><span>{{$brand->gc_name}}</span></a></li>
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
                                        <input type="hidden" name="bj_type" value="suspensive" class="types">
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
                                                <span class="dropdown-label"><span>暂时下架原因不限</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-select">
                                                <input type="hidden" name="state">
                                                <li data-id='-1'><a><span>暂时下架原因不限</span></a></li>
                                                <li data-id='0'><a><span>非销售时间</span></a></li>
                                                <li data-id='1'><a><span>主动暂时下架</span></a></li>
                                                <li data-id='2'><a><span>资金池可提现余额不足</span></a></li>
                                            </ul>
                                        </div>     
                                     @if(empty($price['zhidaojia']))
                                      <span class="ml10 inline-block mt10 fl guide-price">
                                        @elseif (!empty($price['zhidaojia']))
                                        <span class="ml10 inline-block mt10 fl guide-price guidelines">
                                        厂商指导价:￥{{$price['zhidaojia']}}
                                        @endif
                                      </span>
                                      <a href="javascript:;" class="btn btn-danger fs16 sure bt mt10 fl ml10 submit-search">搜索</a>
                                      <a href="javascript:;" class="btn btn-danger fs16 sure bt mt10 fl ml10 reset">重置</a>
                                      <div class="clear"></div>

                                   </td>
                                  
                               </tr>
                             </tbody>
                           </table> 
                       @include('dealer.ucenter.baojia_suspensive_table')

                           <div class="m-t-10"></div>
                           <div class="m-t-10"></div>
                            
                        </div>
                        
                    </form>
                    <div class="m-t-200"></div>

                    <div id="RenewWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd msg" id="msg" style="text-align:center">确定将该报价{0}恢复吗？</p>
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

                    <div id="NoWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd" id="msg" style="text-align:center">暂无可恢复生效报价~</p>
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

                    <div id="restoreSmipWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd msg" style="text-align:center">确定立即恢复报价{0}生效吗？</p>
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

                    <div id="ShelvesAllWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd tac">确定要恢复当前所有已主动暂停报价吗？</p>
                                   <p class="fs14 tac">（非销售时间及资金池可提现余额不足的报价仍为暂时下架状态）</p>
                                   <div class="m-t-10"></div>
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

                    <div id="RestoreAllWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd tac">您已恢复下列报价生效：</p>
                                   <p class="fs14 tac msg">{0}</p>
                                   <p class="fs14 tac"><span class="seconds juhuang">5</span> 秒后自动刷新页面</p>
                                   <div class="m-t-10"></div>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">刷新</a>
                                  <div class="clear"></div>
                              </div>
                          </form>
                      </div>
                    </div> 

                    <div id="restoreNoWorkTimeWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd tac msg">因不在平台销售时间（9:00-17:00）内，报价{0}仍处于暂时下架状态，下一个销售时间开始后将自动生效</p>
                                   <p class="fs14 tac"><span class="seconds juhuang">5</span> 秒后自动刷新页面</p>
                                   <div class="m-t-10"></div>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">刷新</a>
                                  <div class="clear"></div>
                              </div>
                          </form>
                      </div>
                    </div> 

                    <div id="restoreNoMoneyWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="SolutionDel">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd tac msg">因您的资金池可提现余额不足，报价{0}仍处于暂时下架状态，请尽快增加您账户的余额。</p>
                                   <p class="fs14 tac"><span class="seconds juhuang">5</span> 秒后自动刷新页面</p>
                                   <div class="m-t-10"></div>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">刷新</a>
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
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.offer-new-unfinished.jquery", "module/common/common", "vendor/DatePicker/WdatePicker","bt"],function(a,b,c){
         
        });
    </script>
@endsection


