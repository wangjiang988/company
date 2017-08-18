@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <div class="common-models-list-control-panel">

                       <form action="/dealer/carmodel/{{$dealer_id}}" method="get" id="forms">
                       <b>车系选择：</b>
                       <div class="btn-group btn-group-auto">
                            <button data-toggle="dropdown" class="btn btn-select btn-select-long btn-default dropdown-toggle">
                                <span class="dropdown-label"><span>
                                @if (isset($goods_name))
                                    {{ $goods_name }}
                                @else 
                                    全部
                                    @endif
                                </span></span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-select dropdown-long btn-search-car">
                                <input type="hidden" name="search" />
                                <input type="hidden" name="id"/>
                                <li class="active"><a><span>全部</span></a></li>
                                 @foreach($series as $ser)
                                <li><a><span data-id={{$ser->staple_id}}>{{$ser->gc_name}}</span></a></li>                                
                                @endforeach

                            </ul>
                        </div>
                        <a href="{{route('dealer.editcarmodel',['type'=>'add','delear_id'=>$dealer_id])}}" class="btn btn-s-md btn-danger fs18 nomargin ">新增常用车型</a>
                        </form>
                        <div class="error-div"><label>请选择经销商</label></div>

                    </div>
                    <div class="m-t-10"></div>
                    <table class="tbl custom-info-tbl tbl-goods">
                       <tbody>
                         <tr>
                             <th class="tac" width="191">车系</th>
                             <th class="tac" width="271">车型规格</th>
                             <th class="tac" width="130">厂商指导价</th>
                             <th class="tac" width="108">车源锁定</th> 
                             <th class="last" width="175">操作</th>
                         </tr>
                         @if (isset($carmodel))                         
                         @if (array_key_exists('0', $carmodel))
                          @foreach ($carmodel as $car)
                         <tr class="def-temp">
                             <td class="tac"><span>{{$car['gc_name']}}</span></td>
                             <td class="tac">
                                <div class="remark-box">
                                  <div class="remark-wrapper">
                                      {{$car['staple_name']}}
                                  </div>
                                  <div class="showdiv">{{$car['staple_name']}}</div>
                                </div>
                             </td>
                             <td class="tac"><span>￥{{unserialize($car['value'])}}</span></td> 
                             @if($car['locking_count'])
                             <td class="tac"><a href="{{route('dealer.locking_car_staple',['delear_id'=>$car['dealer_id'],'staple_id'=>$car['staple_id'], 'brand_id'=>$car['gc_id_3']])}}" class="juhuang tdu">{{$car['locking_count']}}</a></td>
                             @else
                             <td class="tac"><a href="javascript:void(0);" class="juhuang tdu">{{$car['locking_count']}}</a></td>
                             @endif
                             <td class="tac">
                                   <a data-id="10086" href="{{route('dealer.editcarmodel',['type'=>'check','delear_id'=>$car['dealer_id'],'staple_id'=>$car['staple_id']])}}" class="juhuang tdu">查看</a>
                                   <a data-id="10086" href="{{route('dealer.editcarmodel',['type'=>'edit','delear_id'=>$car['dealer_id'],'staple_id'=>$car['staple_id']])}}" class="juhuang tdu">修改</a>
                                   <a data-id="{{$car['staple_id']}}" href="javascript:;" class="juhuang tdu del-common-models" model-isdel="0">删除</a>
                             </td>
                         </tr>
                         @endforeach
                         @else
                         <tr class="def-temp">
                             <td class="tac"><span>{{$carmodel['gc_name']}}</span></td>
                             <td class="tac">
                                <div class="remark-box">
                                  <div class="remark-wrapper">
                                      {{$car['staple_name']}}
                                  </div>
                                  <div class="showdiv">{{$car['staple_name']}}</div>
                                </div>
                             </td>
                             <td class="tac"><span>￥{{unserialize($carmodel['value'])}}</span></td> 
                              @if($car['locking_count'])
                             <td class="tac"><a href="{{route('dealer.locking_car_staple',['delear_id'=>$car['dealer_id'],'staple_id'=>$car['staple_id'],  'brand_id'=>$car['gc_id_3']])}}" class="juhuang tdu">{{$car['locking_count']}}</a></td>
                             @else
                             <td class="tac"><a href="javascript:void(0);" class="juhuang tdu">{{$car['locking_count']}}</a></td>
                             @endif
                             <td class="tac">
                                   <a data-id="10086" href="{{route('dealer.editcarmodel',['type'=>'check','delear_id'=>$dealer_id,'staple_id'=>$carmodel['staple_id']])}}" class="juhuang tdu">查看</a>
                                   <a data-id="10086" href="{{route('dealer.editcarmodel',['type'=>'edit','delear_id'=>$dealer_id,'staple_id'=>$carmodel['staple_id']])}}" class="juhuang tdu">修改</a>
                                   <a data-id="{{$carmodel['staple_id']}}" href="javascript:;" class="juhuang tdu del-common-models" model-isdel="0">删除</a>
                             </td>
                         </tr>
                        @endif
                        <tr class="del-row none">
                          <td class="tac" colspan="5">
                              暂未添加常用车型的信息~
                          </td>                            
                        </tr>
                        @else
                        <tr>
                          <td class="tac" colspan="5">
                              暂未添加常用车型的信息~
                          </td>                            
                        </tr>
                        @endif
                       </tbody>
                     </table>
                      


      
                    <div id="delCommonModels" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                 <div class="m-t-10"></div>
                                 <p class="fs14 pd  tac">确定要删除该常用车型吗？</p>
                                 <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

                    <div id="delNoCommonModels" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                 <div class="m-t-10"></div>
                                 <p class="fs14 pd  tac">此常用车型存在有效的报价，不能删除哦~</p>
                                 <div class="m-t-10"></div>
                            </div>
                            <div class="popup-control">
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">确认</a>
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
                                       <span class="tip-text">操作成功!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
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




                   
                </div>
                <div class="clear"></div>
            </div>
        </div>


    <div class="box" ms-include-src="footernew"></div>
@endsection

@section('js')
<script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){
           
        });
  </script>
    
@endsection

