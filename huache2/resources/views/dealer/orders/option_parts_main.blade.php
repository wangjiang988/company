@extends('_layout.orders.base_order')
@section('title', '等待确认客户减少订购精品-用户管理-华车网')
@section('content')
<div class="container content m-t-86 pos-rlt ">
        <div class="wapper has-min-step box-border">
            <div class="box-inner  box-inner-def">
                <h2 class="title psr">
                    <span class="title-gray-inline-bg mt-10 inline-block">选装精品订购减少确认</span>
                    <span class="jishi countdown inline-block juhuang weight psa title-count-down">
                        <span></span>
                        <span>0</span>
                        <span class="fuhao">天</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">小时</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao">分</span>
                        <span>0</span>
                        <span>0</span>
                        <span class="fuhao juhuang">秒</span>
                    </span>
                </h2>
                <div class="clear"></div>
                <ul class="pdi-order-ul border ">
                   <p class="fs14 ml10">
                       <span>订单号：{{$order->order_sn}}</span>
                       <?php $gc_name = explode('&gt;', $order->gc_name);?>
                       <span class="ml50">{{$gc_name[0]}}</span>
                       <span class="ml5">&gt;</span>
                       <span class="ml5">{{$gc_name[1]}}</span>
                       <span class="ml5">&gt;</span>
                       <span class="ml5">{{$gc_name[2]}}</span>
                       <span class="ml30">({{$order->orderAttr->cart_color}})</span>
                   </p>
                </ul>
                <div class="m-t-10"></div>
                <form action="{{route('dealer.access.store',['id'=>$order->id])}}" method="Post" id="form">
                {{csrf_field()}}
                @if(!$datas->where('xzj_type',1)->isEmpty())
                    <p class="ul-prev mt10"><b>原厂选装精品</b></p>
                    <table class="tbl tbl-blue tac">
                        <tr>
                            <th width="157"><span class="fs14 weight">名称</span></th>
                            <th width="270"><span class="fs14 weight">型号/说明</span></th>
                            <th width="96"><span class="fs14 weight">厂商编号</span></th>
                            <th width="96"><span class="fs14 weight">含安装费<br>折后总单价</span></th>
                            <th width="72"><span class="fs14 weight">可供件数</span></th>
                            <th width="72"><span class="fs14 weight">已订件数</span></th>
                            <th width="70"><span class="fs14 weight">希望减<br>少为</span></th>
                            <th width="155"><span class="fs14 weight red">操作</span></th>
                        </tr>
                        @foreach($datas as $data)
                        @if($data->xzj_type == 1 && $data->edit_num != $data->same_num)
                        <tr>
                            <td>
                                <p class="fs14">{{$data->xzj_title}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_model}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_cs_serial}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_fee+$data->xzj_guide_price}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_has_num}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->same_num}}</p>
                            </td>
                            <td>
                                <p class="fs14 juhuang">{{$data->edit_num}}</p>
                            </td>
                            <td>
                            <input type="hidden" name="xzj[{{$data->xzj_id}}][old_num]" value="{{$data->same_num}}">
                            <input type="hidden" name="xzj[{{$data->xzj_id}}][xzj_id]" value="{{$data->xzj_id}}">
                                <label><input style="width: auto;" class="inline-block nomargin nopadding wauto" checked="" type="radio" name="xzj[{{$data->xzj_id}}][type]" id=""><span class="ml5">同意</span></label>
                                <label class="ml20"><input style="width: auto;" class="inline-block nomargin nopadding wauto" type="radio" name="xzj[{{$data->xzj_id}}][type]" id="" value="off"><span class="ml5">不同意</span></label>
                            </td>
                        </tr>
                        @endif
                       @endforeach
                    </table>
                    @endif
                    <div class="m-t-10"></div>
                    @if(!$datas->where('xzj_type',0)->isEmpty())
                    <p class="ul-prev mt10"><b>非原厂选装精品</b></p>
                    <table class="tbl tbl-blue tac">
                        <tr>
                            <th width="115"><span class="fs14 weight">品牌</span></th>
                            <th width="115"><span class="fs14 weight">名称</span></th>
                            <th width="200"><span class="fs14 weight">型号/说明</span></th>
                            <th width="96"><span class="fs14 weight">厂商编号</span></th>
                            <th width="96"><span class="fs14 weight">含安装费<br>折后总单价</span></th>
                            <th width="72"><span class="fs14 weight">可供件数</span></th>
                            <th width="72"><span class="fs14 weight">已订件数</span></th>
                            <th width="70"><span class="fs14 weight">希望减<br>少为</span></th>
                            <th width="155"><span class="fs14 weight red">操作</span></th>
                        </tr>
                        @foreach($datas as $data)
                        @if($data->xzj_type == 0 && $data->edit_num != $data->same_num)
                        <tr>
                            <td>
                                <p class="fs14">{{$data->xzj_brand}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_title}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_model}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_cs_serial}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_fee+$data->xzj_guide_price}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->xzj_has_num}}</p>
                            </td>
                            <td>
                                <p class="fs14">{{$data->same_num}}</p>
                            </td>
                            <td>
                                <p class="fs14 juhuang">{{$data->edit_num}}</p>
                            </td>
                            <td>
                             <input type="hidden" name="xzj[{{$data->xzj_id}}][old_num]" value="{{$data->same_num}}">
                             <input type="hidden" name="xzj[{{$data->xzj_id}}][xzj_id]" value="{{$data->xzj_id}}">
                                <label><input style="width: auto;" class="inline-block nomargin nopadding wauto" checked="" type="radio" name="xzj[{{$data->xzj_id}}][type]" id=""><span class="ml5">同意</span></label>
                                <label class="ml20"><input style="width: auto;" class="inline-block nomargin nopadding wauto" type="radio" name="xzj[{{$data->xzj_id}}][type]" id=""><span class="ml5">不同意</span></label>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </table>
                    @endif
                </form>

                <div class="m-t-10"></div>
                <div class="m-t-10"></div>

                <table>
                    <tr>
                        <td width="80" valign="top"><span>温馨提示：</span></td>
                        <td>
                            <p class="fs14">1.请在2017年4月2日17:00:00前回复。超时未主动提交，将默认同意自动提交。</p>
                            <p class="fs14">2.如有不同意项，请与客户沟通说明后再作提交。（T:{{$order->orderUsers->phone}}）</p>
                        </td>
                    </tr>
                </table>
                @if($order->orderXzjEdit->where('is_install',0)->count())
                <p class="center mt50">
                    <a @click="send" href="javascript:;" class="btn btn-s-md btn-danger fs16">提交</a>
                    <a href="javascript:history.go(-1)" class="btn btn-s-md btn-danger fs16 ml50 sure">关闭</a>
                </p>
                @endif

                <div id="sendWin" class="popupbox">
                    <div class="popup-title">温馨提示</div>
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <div class="m-t-10"></div>
                            <p class="fs14 pd tac">
                               <br>
                               <span class="tip-text fs14 tac inline-block">
                                    确定提交上述确认内容吗？<br>
                                    （同意减少的数量将自动恢复加入可供件数哦）

                               </span>
                               <div class="clear"></div>
                               <br>
                            </p>
                            <div class="m-t-10"></div>
                        </div>
                        <div class="popup-control">
                            <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs14 do w100 ">确定提交</a>
                            <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">取消</a>
                            <div class="clear"></div>
                            <div class="m-t-10"></div>
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
        seajs.use(["/js/vendor/vue.min","module/order/custom-order-goods-confirmed", "module/common/common"],function(v,u,c){
            u.init('{{date('Y-m-d H:i:s',time())}}','{{$order->xzjp_updated_at}}')
        })
    </script>
@endsection
