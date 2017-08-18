@extends('_layout.orders.base_order')
@section('title', '等待补充预约信息-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 psr">
       <div class="cus-step">
           <div class="line stp-3"></div>
           <ul>
               <li class="first"><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><i class="cur-step cur-step-3">3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>

       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-3">
                    <small>发出通知</small>
                    <i></i>
                    <small class="juhuang">确认反馈</small>
                    <i></i>
                    <small>预约完毕</small>
                </div>
            </div>
       </div>


        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                    @include('dealer.orders._layout.content')
                    <p>温馨提示：订单完整内容，参见订单总详情。</p>


                  <div class="m-t-10"></div>
                    <h2 class="title">
                        <span class="red">*</span><span class="blue ml5 weight">待补充</span>
                    </h2>

                    <div class="time-wrapper fs14 box-inner-def box-inner-fix ">
                          <div class="ul-prev">
                              <span class="fl mt2">选择需要客户提车时携带的所有可能文件资料： </span>
                              <a href="javascript:;" @click="confirmReload" class="ml20 juhuang import-set fl">导入常用设置</a>
                              <div class="clear"></div>
                          </div>
                          <form action="{{route('dealer.delivery.store',['id'=>$order->id])}}" method="POST" id="main">
                          {{csrf_field()}}
                            <table class="tbl text-center tbl-blue fs14 wp80 mauto">
                                <tr>
                                    <th width="200"><b>文件资料使用场合</b></th>
                                    <th width="390"><b>文件资料名称</b></th>
                                    <th width="60"><b>数量</b></th>
                                    <th width="100"><b>文件格式</b></th>
                                    <th width="130"><b>选择</b></th>
                                </tr>
                               @if($order->orderAppoint->car_purpose !== 2)
                                <tr>
                                    @if($agent_files->where('cate_id',2)->isEmpty())
                                    <td class="nopadding">投保车辆首年商业保险<br></td>
                                    <td>无</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @elseif($agent_files->where('cate_id',2)->count() == 1)
                                    <td class="nopadding">投保车辆首年商业保险<br></td>
                                    <?php $file = $agent_files->where('cate_id',2)->first();?>
                                    <td>{{$file->title}}</td>
                                    <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                                    <td>{{$file->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="{{$file->id}}"><span class="ml5">需要</span></label>
                                    <label><input style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="0"><span class="ml5">不需要</span></label>
                                    </td>
                                    @else
                                    <td class="nopadding" rowspan="{{$agent_files->where('cate_id',2)->count()}}">投保车辆首年商业保险<br></td>
                                    @foreach($agent_files->where('cate_id',2) as $files)
                                    <td>{{$files->title}}</td>
                                    <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                                    <td>{{$files->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" id="" value="{{$files->id}}"><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" id="" value="0"><span class="ml5">不需要</span></label>
                                    </td>
                                    </tr><tr>
                                    @endforeach
                                    @endif
                                </tr>
                             @if(!($order->orderAppoint->car_purpose ==0 && $order->orderAppoint->identity_type>0))
                                <tr>
                                    @if($agent_files->where('cate_id',3)->isEmpty())
                                    <td class="nopadding">代办上牌手续<br>（含缴纳车辆购置税）</td>
                                    <td>无</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @elseif($agent_files->where('cate_id',3)->count() == 1)
                                    <td class="nopadding">代办上牌手续<br>（含缴纳车辆购置税）</td>
                                    <?php $file = $agent_files->where('cate_id',3)->first();?>
                                    <td>{{$file->title}}</td>
                                    <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                                    <td>{{$file->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="{{$file->id}}"><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="0"><span class="ml5">不需要</span></label>
                                    </td>
                                    @else
                                    <td class="nopadding" rowspan="{{$agent_files->where('cate_id',3)->count()}}">代办上牌手续<br>（含缴纳车辆购置税）</td>
                                    @foreach($agent_files->where('cate_id',3) as $files)
                                    <td>{{$files->title}}</td>
                                    <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                                    <td>{{$files->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="{{$files->id}}" id=""><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="0" id=""><span class="ml5">不需要</span></label>
                                    </td>
                                    </tr><tr>
                                    @endforeach
                                    @endif
                                </tr>
                             @endif
                                  <tr>
                                    @if($agent_files->where('cate_id',4)->isEmpty())
                                    <td class="nopadding">代办车辆临时牌照手续<br></td>
                                    <td>无</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @elseif($agent_files->where('cate_id',4)->count() == 1)
                                    <td class="nopadding">代办车辆临时牌照手续<br></td>
                                    <?php $file = $agent_files->where('cate_id',4)->first();?>
                                    <td>{{$file->title}}</td>
                                    <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                                    <td>{{$file->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="{{$file->id}}"><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="0"><span class="ml5">不需要</span></label>
                                    </td>
                                    @else
                                    <td class="nopadding" rowspan="{{$agent_files->where('cate_id',4)->count()}}">代办车辆临时牌照手续<br></td>
                                    @foreach($agent_files->where('cate_id',4) as $files)
                                    <td>{{$files->title}}</td>
                                    <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                                    <td>{{$files->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="{{$files->id}}" id=""><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="0" id=""><span class="ml5">不需要</span></label>
                                    </td>
                                    </tr><tr>
                                    @endforeach
                                    @endif
                                </tr>
                          @endif

                         <?php $status = ($order->orderAppoint->owner_name == $order->orderAppoint->extract_name);?>
                          @if($order->orderAppoint->car_purpose || !$status)
                              <tr>
                                    @if($agent_files->where('cate_id',1)->isEmpty())
                                    <td class="nopadding">提车人身份验证<br>（提车人非车主本人）</td>
                                    <td>无</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @elseif($agent_files->where('cate_id',1)->count() == 1)
                                    <td class="nopadding">提车人身份验证<br>（提车人非车主本人）</td>
                                    <?php $file = $agent_files->where('cate_id',1)->first();?>
                                    <td>{{$file->title}}</td>
                                    <td>@if($file->isself)√@else{{$file->num}}@endif</td>
                                    <td>{{$file->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="{{$file->id}}"><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="0"><span class="ml5">不需要</span></label>
                                    </td>
                                    @else
                                    <td class="nopadding" rowspan="{{$agent_files->where('cate_id',1)->count()}}">提车人身份验证<br>（提车人非车主本人）</td>
                                    @foreach($agent_files->where('cate_id',1) as $files)
                                    <td>{{$files->title}}</td>
                                    <td>@if($files->isself)√@else{{$files->num}}@endif</td>
                                    <td>{{$files->file_url}}</td>
                                    <td>
                                      <label><input checked="" style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="{{$files->id}}" id=""><span class="ml5">需要</span></label>
                                        <label><input style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="0" id=""><span class="ml5">不需要</span></label>
                                    </td>
                                    </tr><tr>
                                    @endforeach
                                    @endif
                                </tr>
                              @endif


                                 <tr>
                                    @if($agent_files->where('cate_id',5)->isEmpty())
                                    <td class="nopadding bb">
                                    <p>非卡主本人刷卡</p>
                                      <p>
                                         <label><input @click="setDisabled(0)" checked="" style="width: auto" type="radio" name="files[status]" id=""><span class="ml5">可接受</span></label>
                                         <label><input @click="setDisabled(1)" style="width: auto" type="radio" name="files[status]" id=""><span class="ml5">不可接受</span></label>
                                      </p>
                                      <div class="clear"></div>
                                    </td>
                                    <td>无</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @elseif($agent_files->where('cate_id',5)->count() == 1)
                                    <td class="nopadding bb">
                                    <p>非卡主本人刷卡</p>
                                      <p>
                                         <label><input @click="setDisabled(0)" checked="" style="width: auto" type="radio" name="files[status]" id="" value="1"><span class="ml5">可接受</span></label>
                                         <label><input @click="setDisabled(1)" style="width: auto" type="radio" name="files[status]" id="" value="0"><span class="ml5">不可接受</span></label>
                                      </p>
                                      <div class="clear"></div>
                                    </td>
                                    <?php $file = $agent_files->where('cate_id',5)->first();?>
                                    <td :class="{'p-gray':isDisabled}">{{$file->title}}</td>
                                    <td :class="{'p-gray':isDisabled}">@if($file->isself)√@else{{$file->num}}@endif</td>
                                    <td :class="{'p-gray':isDisabled}">{{$file->file_url}}</td>
                                    <td>
                                      <label><input :disabled="isDisabled" checked="" style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id="" value="{{$file->id}}"><span class="ml5">需要</span></label>
                                        <label><input :disabled="isDisabled" style="width: auto" type="radio" name="files[{{$file->cate_id}}][{{$file->id}}]" id=""><span class="ml5" value="0">不需要</span></label>
                                    </td>
                                    @else
                                    <td class="nopadding bb" rowspan="{{$agent_files->where('cate_id',5)->count()}}">
                                    <p>非卡主本人刷卡</p>
                                      <p>
                                         <label><input @click="setDisabled(0)" checked="" style="width: auto" type="radio" name="files[status]" id="" value="1"><span class="ml5">可接受</span></label>
                                         <label><input @click="setDisabled(1)" style="width: auto" type="radio" name="files[status]" id="" value="0"><span class="ml5">不可接受</span></label>
                                      </p>
                                      <div class="clear"></div>
                                    </td>
                                    @foreach($agent_files->where('cate_id',5) as $files)
                                    <td :class="{'p-gray':isDisabled}">{{$files->title}}</td>
                                    <td :class="{'p-gray':isDisabled}">@if($files->isself)√@else{{$files->num}}@endif</td>
                                    <td :class="{'p-gray':isDisabled}">{{$files->file_url}}</td>
                                    <td>
                                      <label><input :disabled="isDisabled" checked="" style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="{{$files->id}}" id=""><span class="ml5">需要</span></label>
                                        <label><input :disabled="isDisabled" style="width: auto" type="radio" name="files[{{$files->cate_id}}][{{$files->id}}]" value="0" id=""><span class="ml5">不需要</span></label>
                                    </td>
                                    </tr><tr>
                                    @endforeach
                                    @endif
                                </tr>
                            </table>
                            <p class="ul-prev">选择本次的服务专员：</p>
                            <div class="mauto tac">
                              <table class="tbl tbl-blue wp50">
                                  <tr>
                                    <th><b>姓名</b></th>
                                    <th><b>手机</b></th>
                                    <th><b>备用电话</b></th>
                                  </tr>
                                  <tr v-cloak>
                                    <td>
                                        <input type="hidden" class="hide" name="waiter_id" v-model="user.id" >
                                        <select v-model="user">
                                            <option :value="u" v-for="u in userList" >@{{u.name}}</option>
                                        </select>
                                    </td>
                                    <td>@{{user.phone}}</td>
                                    <td>@{{user.tel}}</td>
                                  </tr>
                              </table>
                            </div>
                            <div class="clear m-t-10"></div>
                            <div class="m-t-10"></div>
                            <p class="tac red hide" :class="{show:isEmpty}">您还没有添加服务专员信息呢，请到您的后台中添加哦~</p>
                            <p class="tac red hide" :class="{show:optionalError}">选装精品减少事宜请先确认哦~</p>
                            <p class="center">
                                <a href="javascript:;" @click="send" class="btn btn-s-md btn-danger">提交</a>
                            </p>
                          </form>


                          <div id="tipWin" class="popupbox">
                            <div class="popup-title"><span>提交确认</span></div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                      <div class="m-t-10"></div>
                                      <p class="fs14 pd tac" >
                                         <span class="tip-text mt10">确定提交服务专员信息和客户文件资料吗？</span>
                                         <div class="clear"></div>
                                         <br>
                                      <div class="m-t-10"></div>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="doSend" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                                      <div class="clear"></div>

                                      <div class="m-t-10"></div>
                                  </div>
                              </div>
                          </div>

                          <div id="reloadWin" class="popupbox">
                            <div class="popup-title"><span>温馨提示</span></div>
                              <div class="popup-wrapper">
                                  <div class="popup-content">
                                      <div class="m-t-10"></div>
                                      <p class="fs14 pd tac" >
                                         <span class="tip-text mt10">确定导入常用管理中的客户文件吗？</span>
                                         <div class="clear"></div>
                                         <br>
                                      <div class="m-t-10"></div>
                                  </div>
                                  <div class="popup-control">
                                      <a @click="reload" href="javascript:;" class="btn btn-s-md btn-danger fs14 do w100">确 定</a>
                                      <a href="javascript:;" class="btn btn-s-md btn-danger fs14  w100 sure ml50">取 消</a>
                                      <div class="clear"></div>

                                      <div class="m-t-10"></div>
                                  </div>
                              </div>
                          </div>





                  </div>

        </div>

    </div>
    </div>
@endsection
@section('js')
    <script src="/webhtml/common/js/vendor/My97DatePicker/WdatePicker.js"></script>

    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-waiting-supplementary-reservation", "module/common/common"],function(v,u,c){
          @if ($order->orderWaiter->isEmpty())
              u.initUserList([])
          @else
              @foreach($order->orderWaiter as $waiter)
              <?php $waiters[] = ['id'=>$waiter->id,'name'=>$waiter->name,'phone'=>$waiter->mobile,'tel'=>$waiter->tel];?>
              @endforeach
              u.initUserList( {!! json_encode($waiters) !!})
          @endif
          @if($order->orderXzjEdit->where('is_install',0)->count())
          u.initOptionalStatus(0)
          @else
          u.initOptionalStatus(1)
          @endif
        })
    </script>
@endsection
