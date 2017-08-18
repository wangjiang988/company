@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <div class="custom-offer-step-wrapper">
                        <ul>
                          <li class="prev">车型价格</li>
                          <li class="cur">车况说明</li>
                          <li>选装精品</li>
                          <li>收费标准</li>
                          <li class="last">其他事项</li>
                          <div class="clear"></div>
                        </ul>
                    </div>


                    <form action="/dealer/baojia/edit/{{$baojia['bj_id']}}/2" name="baojia-submit-form" method="post">
                    <input type='hidden' name='_token' value="{{csrf_token()}}">
                        <div class="content-wapper ">
                           @if($baojia['bj_is_xianche']==1)
                            <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tar">
                                      <label>车身颜色：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="btn-group btn-jquery-event-use-id default-value btn-group-auto">
                                      	  <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                              <span class="dropdown-label"><span>选择车身颜色</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-company">
                                          	  <input type="hidden" name="body_color">
                                              @if(isset($carinfoArray['body_color']) && count($carinfoArray['body_color'])>0)
	                                              @foreach($carinfoArray['body_color'] as $k=>$v)
	                                              <li data-value="{{$k}}"><a><span>{{$v}}</span></a></li>
	                                              @endforeach
                                              @endif

                                          </ul>

                                      </div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar" >
                                      <label>内饰颜色：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="btn-group btn-jquery-event-use-id default-value btn-auto">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-long btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>请选择内饰颜色</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select dropdown-long">
                                              <input type="hidden" name="interior_color">
                                              @if(isset($carinfoArray['interior_color']) && count($carinfoArray['interior_color'])>0)
	                                              @foreach($carinfoArray['interior_color'] as $k=>$v)
	                                              <li data-value="{{$k}}"><a><span>{{$v}}</span></a></li>
	                                              @endforeach
                                              @endif
                                          </ul>
                                      </div>
                                   </td>
                               </tr>

                               <tr>
                                   <td class="tar" >
                                      <label>出厂年月：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="btn-group btn-jquery-event-use-id">
                                          <button data-toggle="dropdown" class="btn  btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$currentyear}}年</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu" id="year-dropdown">
                                              <input type="hidden" name="produce-year" value="{{$currentyear}}">

                                          </ul>
                                      </div>
                                      <div class="btn-group btn-jquery-event-use-id ml10">
                                          <button data-toggle="dropdown" class="btn  btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$currentmonth}}月</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-small" id="month-dropdown">
                                              <input type="hidden" name="produce-month" value="{{$currentmonth}}">

                                          </ul>
                                      </div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tar" >
                                      <label>行驶里程：</label>
                                   </td>
                                   <td class="tal">
                                      <input id="mileage" placeholder="" type="text" name="bj_licheng" class="form-control custom-control custom-control-min abs">
                                      <span class="ml15">公里（请填写整数，有小数点的一律进位填写，例如15.1填写为16）</span>
                                      <span class="error-div juhuang ml6" >请输入整数~</span>
                                   </td>
                               </tr>
                             </tbody>
                            </table>
                            <p><b>已装原厂选装精品：</b></p>
                            <table class="tbl tbl-blue wp100">
                              <tbody>
                                <tr class="title-tag">
                                  <th width="50" class="tac none select"><span>选择</span></th>
                                  <th width="150" class="tac"><span>名称</span></th>
                                  <th width="200" class="tac"><span>型号/说明</span></th>
                                  <th width="70" class="tac"><span>厂商编号</span></th>
                                  <th width="70" class="tac"><span>厂商指导价</span></th>
                                  <th width="150" class="tac"><span>数量</span></th>
                                  <th width="70" class="tac"><span>附加价值</span></th>
                                </tr>
                                @if(!count($bj_xzj))
                                <tr>
                                  <td colspan="6">
                                     <div class="m-t-10"></div>
                                     <p class="tac">暂无 <a href="javascript:;" class="juhuang tdu goods-add-link">添加</a></p>
                                     <div class="m-t-10"></div>
                                  </td>
                                </tr>
                                @endif
                                @if(count($bj_xzj))
                                @foreach($bj_xzj as $k=>$v)
                                <tr data-id="{{$k}}" class="handler-loading">
                                  <td class="tac"><span>{{$v['xzj_title']}}</span></td>
                                  <td class="tac"><span>{{$v['xzj_model']}}</span></td>
                                  <td class="tac"><span>{{$v['xzj_cs_serial']}}</span></td>
                                  <td class="tac"><span>￥{{number_format($v['xzj_guide_price'],2)}}</span></td>
                                  <td class="tac count"><span>{{$v['num']}}</span></td>
                                  <td class="tac"><span>￥{{number_format($v['xzj_guide_price'],2)}}</span></td>
                                </tr>
                                @endforeach
                                @endif
                                @foreach($xzj as $k=>$v)
                                <tr data-id="{{$k}}" class="loading none">
                                  <td class="tac select-checked">
                                      <input type="checkbox" name="xzj[{{$v['xzj_id']}}]" id="" <?php if(isset($bj_xzj[$v['xzj_id']])){echo 'checked';}?>>
                                  </td>
                                  <td class="tac"><span>{{$v['xzj_title']}}</span></td>
                                  <td class="tac"><span>{{$v['xzj_model']}}</span></td>
                                  <td class="tac"><span>{{$v['xzj_cs_serial']}}</span></td>
                                  <td class="tac"><span>￥{{number_format($v['xzj_guide_price'],2)}}</span></td>
                                  <td class="tac count">
                                    <div class="checkbox-wrapper inline counter-wrapper">
                                        <span class="prev ">-</span>
                                        <input data-price="{{$v['xzj_guide_price']}}" class="txt-count-input" type="text" name="xzj_num[{{$v['xzj_id']}}]" id="" value="1" max="{{$v['xzj_max_num']}}" <?php if(!isset($bj_xzj[$v['xzj_id']])){echo 'disabled';}?>>
                                        <span class="next noleftborder">+</span>
                                    </div>
                                  </td>
                                  <td class="tac"><span>￥{{number_format($v['xzj_guide_price'],2)}}</span></td>
                                </tr>
                                @endforeach



                              </tbody>
                            </table>
                            @if(count($bj_xzj))
                            <p class="tar"><a href="javascript:;" class="juhuang tdu goods-modify-link">修改</a></p>
                            @endif
                            <div class="m-t-10"></div>
                            <p class="tac red none empty-error">未添加任何选装精品~</p>
                            <p class="tac none goods-control">
                              <a href="javascript:;" class="btn btn-danger bt fs18 goods-save-link">保存</a>
                              <a href="javascript:;" class="btn btn-danger bt sure fs18 ml20  goods-back-link">返回</a>
                            </p>


                    @else
							<!-- 非现车展示 -->
                            <table class=" custom-info-tbl noborder">
                             <tbody>
                               <tr>
                                   <td class="tal" width="90">
                                      <label>车身颜色：</label>
                                   </td>
                                   <td class="tal" width="425">
                                      <div class="btn-group btn-jquery-event-use-id default-value">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                              <span class="dropdown-label"><span>请选择车身颜色</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-company">
                                              <input type="hidden" name="">
                                               @if(isset($carinfoArray['body_color']) && count($carinfoArray['body_color'])>0)
	                                              @foreach($carinfoArray['body_color'] as $k=>$v)
	                                              <li data-value="{{$k}}"><a><span>{{$v}}</span></a></li>
	                                              @endforeach
                                               @endif

                                          </ul>
                                          <input type="hidden" name="body_color">
                                      </div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tal" colspan="3" >
                                      <label>内饰颜色（可多选，多选将生成多条报价）：</label>
                                   </td>
                               </tr>
                               <tr>
                                   <td></td>
                                   <td class="tal nopadding" colspan="2" >
                                   @if(isset($carinfoArray['interior_color']) && count($carinfoArray['interior_color'])>0)
	                                    @foreach($carinfoArray['interior_color'] as $k=>$v)
	                                    	<label class="ml10"><input type="checkbox" class="fl" name="interior_color[]" id="" value="{{$k}}"><span class="fl ml5 noweight">{{$v}}</span></label>
	                                    @endforeach
                                   @endif

                                   </td>
                               </tr>

                               <tr>
                                   <td class="tar" >
                                      <label>交车周期：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="btn-group btn-jquery-event-use-id">
                                          <button data-toggle="dropdown" class="btn  btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>六个月</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu">
                                              <input type="hidden" name="bj_jc_period" value='6'>
                                              <li data-value="1"><a><span>一个月</span></a></li>
                                              <li data-value="3"><a><span>三个月</span></a></li>
                                              <li data-value="6"><a><span>六个月</span></a></li>
                                          </ul>
                                      </div>

                                   </td>
                               </tr>

                             </tbody>
                            </table>

                            <p class="tac none goods-control">
                              <a href="javascript:;" class="btn btn-danger bt fs18 goods-save-link">保存</a>
                              <a href="javascript:;" class="btn btn-danger bt sure fs18 ml20  goods-back-link">返回</a>
                            </p>
					@endif
                            <div class="m-t-10"></div>
                            <p><b>免费礼品或服务：</b><a href="javascript:;" class="juhuang tdu add-solution"><b>新增项目</b></a></p>
                            <table class="tbl tbl-blue wp100"  id="table-zengpin">
                              <tbody>
                                <tr>
                                  <th class="tac" width="260"><span>名称</span></th>
                                  <th class="tac" width="120"><span>数量</span></th>
                                  <th class="tac" width="120"><span>状态</span></th>
                                  <th class="tac" width="120"><span>操作</span></th>
                                </tr>
                              @if(count($baojiaZengpinList)>0)
                              @foreach($baojiaZengpinList as $v)
                                <tr class='tr-zengpin'>
                                  <td class="tac"><span>{{$v['zp_title']}}</span></td>
                                  <td class="tac"><span>{{$v['num']}}</span></td>
                                  <td class="tac"><span>{{$v['is_install']==1?"已安装":"/"}}</span></td>
                                  <td class="tac">
                                      <a href="javascript:;" class="juhuang tdu edit-solution" data-id="{{$v['id']}}" data-value="{{$v['zengpin_id']}}" data-install="{{$v['is_install']}}">修改</a>
                                      <a href="javascript:;" class="juhuang tdu ml10 del-solution" data-id="{{$v['id']}}" >删除</a>
                                  </td>
                                </tr>
                               @endforeach
                               @endif
                               <tr class="hidenTr {{count($baojiaZengpinList)>0?'hide':''}}"><td colspan="4"><p class="tac mt10">暂无</p></td></tr>
                              </tbody>
                            </table>

                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            <p class="tac">
                                <a href="/dealer/baojia/edit/{{$baojia['bj_id']}}/1" class="btn btn-danger sure fs18 ml20">返回上一步</a>
                                <a href="javascript:;" class="btn btn-danger fs18 ml20 baojia-submit-button" data-step='2' data-type='1'>下一步</a>
                                <a href="javascript:;" class="btn btn-danger sure fs18 ml20 baojia-submit-button" data-step='2' data-type='2'>保存并退出</a>
                           	  	<a href="javascript:;" class="btn btn-danger sure fs18 ml20 reset-form">重置</a>
                            </p>






                        </div>

                    </form>
                    <div class="m-t-200"></div>




                  <div id="SolutionAddWin" class="popupbox" >
                      <div class="popup-title">
                        <span class="">新增项目</span>
                      </div>
                      <div class="popup-wrapper ">
                          <form action="/dealer/baojia/ajaxsubmit/edit-zengpin"  method="post">
                    		  <input type='hidden' name='_token' value="{{csrf_token()}}">
                    		  <input type='hidden' name='id' value="0">
                    		  <input type='hidden' name='bj_id' value="{{$baojia['bj_id']}}">
                              <div class="popup-content ">
                                  <table class="noborder ml20">
                                    <tr>
                                      <td class="tar nopadding"><span><b>名称：</b></span></td>
                                      <td class="nopadding">
                                          <div class="btn-group  btn-jquery-event-use-id btn-auto">
                                              <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                                  <span class="dropdown-label"><span>--请选择免费礼品和服务--</span></span>
                                                  <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu dropdown-company">
                                                  <input type="hidden" name="zengpin_id">
                                                  @if(count($zengpinList)>0)
                                                  @foreach($zengpinList as $k=>$v)
                                                  <li data-value="{{$v['id']}}"><a><span>{{$v['title']}}</span></a></li>
                                                  @endforeach
                                                  @endif
                                              </ul>
                                          </div>
                                          <div class="clear error-div"><label class="red">请选择免费礼品和服务</label class="red"></div>
                                      </td>
                                      <td class="nopadding">
                                          @if($baojia['bj_is_xianche'] >0 )
                                        <label class="ml5">
                                            <input type="checkbox" name="is_install" value='1' id="" class="fl mt10">
                                            <span class="fl mt5 ml5 noweight">已安装</span>
                                            <span class="clear"></span>
                                        </label>
                                         @endif
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3 nopadding">
                                         <span>（如不在列表中，请先向华车平台提交审核，去<a href="javascript:;" class="juhuang tdu new">提交</a>）</span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="tar nopadding"><span><b>数量：</b></span></td>
                                      <td class="nopadding" colspan="2">
                                          <div class="checkbox-wrapper inline counter-wrapper">
                                              <span class="prev tac">-</span>
                                              <input class="" type="text" name="num" id="" value="1" max="9999">
                                              <span class="next tac">+</span>
                                          </div>
                                      </td>
                                    </tr>
                                  </table>
                                  <div class="m-t-10"></div>
                                  <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">保存</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure ml20">返回</a>
                                  <div class="clear mt10"></div>
                              </div>
                          </form>
                      </div>
                  </div>

                  <div id="SolutionEditWin" class="popupbox" >
                      <div class="popup-title">
                        <span class="">修改项目</span>
                      </div>
                      <div class="popup-wrapper ">
                          <form action="/dealer/baojia/ajaxsubmit/edit-zengpin" method="post">
                   			 <input type='hidden' name='_token' value="{{csrf_token()}}">
                   			 <input type='hidden' name='id' value="">
                   			 <input type='hidden' name='bj_id' value="{{$baojia['bj_id']}}">
                              <div class="popup-content ">
                                  <table class="noborder ml20">
                                    <tr>
                                      <td class="tar nopadding"><span><b>名称：</b></span></td>
                                      <td class="nopadding">
                                          <div class="btn-group btn-jquery-event-use-id btn-auto">
                                              <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                                  <span class="dropdown-label"><span>--请选择免费礼品和服务--</span></span>
                                                  <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu dropdown-company">
                                                  <input type="hidden" name="zengpin_id">
                                                  @if(count($zengpinList)>0)
                                                  @foreach($zengpinList as $k=>$v)
                                                  <li data-value="{{$v['id']}}"><a><span>{{$v['title']}}</span></a></li>
                                                  @endforeach
                                                  @endif
                                              </ul>
                                          </div>
                                          <div class="clear error-div"><label class="red">请选择免费礼品和服务</label class="red"></div>
                                      </td>
                                      <td class="nopadding">
                                          @if($baojia['bj_is_xianche'] >0 )
                                        <label>
                                            <input type="checkbox" name="is_install" value='1' id="" class="fl mt10">
                                            <span class="fl mt5 ml5 noweight">已安装</span>
                                            <span class="clear"></span>
                                        </label>
                                           @endif
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="3 nopadding">
                                         <span>（如不在列表中，请先向华车平台提交审核，去<a href="javascript:;" class="juhuang tdu new">提交</a>）</span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="tar nopadding"><span><b>数量：</b></span></td>
                                      <td class="nopadding" colspan="2">
                                          <div class="checkbox-wrapper inline counter-wrapper">
                                              <span class="prev tac">-</span>
                                              <input class="" type="text" name="num" id="" value="1" max="9999">
                                              <span class="next tac">+</span>
                                          </div>
                                      </td>
                                    </tr>
                                  </table>
                                  <div class="m-t-10"></div>
                                  <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">保存</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure ml20">返回</a>
                                  <div class="clear mt10"></div>
                              </div>
                          </form>
                      </div>
                  </div>

                  <div id="SolutionDelWin" class="popupbox">
                      <div class="popup-title">温馨提示</div>
                      <div class="popup-wrapper">
                          <form action="/dealer/baojia/ajaxsubmit/delete-zengpin"  method="post">
                   			 <input type='hidden' name='_token' value="{{csrf_token()}}">
                   			 <input type='hidden' name='id' value="">
                   			 <input type='hidden' name='bj_id' value="{{$baojia['bj_id']}}">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd  tac">确定要删除该免费产品或服务吗？</p>
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

                  <div id="SolutionNewWin" class="popupbox">
                      <div class="popup-title">新的免费礼品和服务提交</div>
                      <div class="popup-wrapper">
                          <form action="/dealer/baojia/ajaxsubmit/apply-new-zengpin"  method="post">
                   			 <input type='hidden' name='_token' value="{{csrf_token()}}">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 tac new-tip"> 新的免费礼品和服务名称：</p>
                                   <p class="tac">
                                     <input placeholder="请输入新的免费礼品和服务名称" type="text" name="title" class="form-control custom-control-2">
                                   </p>
                                   <div class="clear error-div tac"><label class="red">请输入新的免费礼品和服务名称</label class="red"></div>
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

                  <div id="tip-error" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">添加失败!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                    </div>

                    <div id="tip-succeed" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac succeed auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">添加成功!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                    </div>


                     <div id="tip-info-success" class="popupbox">
                         <div class="popup-title">温馨提示</div>
                         <div class="popup-wrapper">
                             <div class="popup-content">
                                  <div class="m-t-10"></div>
                                  <p class="fs14 pd tac succeed constraint">
                                     <span class="tip-tag"></span>
                                     <span class="tip-text">您已成功提交工单！工单内容经平台审核后方可使用。</span>
                                     <div class="clear"></div>
                                  </p>
                                  <p class="fs14 tac">查看审核进度方请前往：便捷工具->工单进度管理</p>
                                  <div class="m-t-10"></div>
                             </div>
                             <div class="popup-control">
                                 <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure">确认</a>
                                 <div class="clear"></div>
                             </div>
                         </div>
                     </div>


</div>

@endsection



@section('js')
<script type="text/template" id="good-tpl">
        <tr class="def" data-ref-id="{id}">
          <td class="tac none">
              <input type="checkbox" name="" id="">
          </td>
          <td class="tac"><span>{0}</span></td>
          <td class="tac"><span>{1}</span></td>
          <td class="tac"><span>{2}</span></td>
          <td class="tac"><span>{3}</span></td>
          <td class="tac count"><span>{4}</span></td>
          <td class="tac"><span>{5}</span></td>
        </tr>
</script>
	 <script type="text/javascript">
	   function selectYearWithMonth (obj,month){
         var _monthObj = $(obj).parents(".btn-jquery-event-use-id").next()
         var _month    = []
         for (var i = 1; i <= month; i++) {
             _month.push(i)
         }
         var _monthHtml = ""
         $.each(_month,function(idx){
            _monthHtml+='<li  data-value="'+this+'"><a><span>'+this+'月</span></a></li>'
         })
         $("#month-dropdown").find("li").remove().end().append(_monthHtml)
         _monthHtml = null
         $("#month-dropdown").find("li").eq(0).click()
     }

     function selectAllMonth(){
         $("#month-dropdown").find("li").remove().end().append('<li data-value="1"><a><span>1月</span></a></li><li data-value="2"><a><span>2月</span></a></li><li data-value="3"><a><span>3月</span></a></li><li data-value="4"><a><span>4月</span></a></li><li data-value="5"><a><span>5月</span></a></li><li data-value="6"><a><span>6月</span></a></li><li data-value="7"><a><span>7月</span></a></li><li data-value="8"><a><span>8月</span></a></li><li data-value="9"><a><span>9月</span></a></li><li data-value="10"><a><span>10月</span></a></li><li data-value="11"><a><span>11月</span></a></li><li data-value="12"><a><span>12月</span></a></li>')
     }

        seajs.use(["module/custom/custom_admin",
                   "module/custom/custom.admin.common.jquery",
                   "module/custom/custom.admin.shape.jquery",
                   "module/common/common",
                   "bt"],function(a,b,c,d,e){
            //_year 和 _month 是服务器当前时间的年和月
            //_first位起始年份
            var _year         = {{date("Y")}}
            var _month        = {{date("m")}}
            var _first        = 2010
            var _year_arr     = []
            for (var i = _first; i <= _year; i++) {
                _year_arr.push(i)
            }
            var _yearhtml = ""
            $.each(_year_arr,function(idx){
               if (idx == _year_arr.length - 1) {
                   _yearhtml+='<li onclick="selectYearWithMonth(this,'+_month+')" data-value="'+_year+'"><a><span>'+this+'年</span></a></li>'
               }else{
                   _yearhtml+='<li onclick="selectAllMonth()" data-value="'+this+'"><a><span>'+this+'年</span></a></li>'
               }
            })
            $("#year-dropdown").append(_yearhtml)
            _yearhtml = null
            var _monthHtml = ""
            for(i=1;i<=_month;i++){
            	_monthHtml+='<li data-value="'+i+'"><a><span>'+i+'月</span></a></li>'
            }
            $("#month-dropdown").append(_monthHtml)
            _monthHtml = null
            //

        });
	</script>
@endsection