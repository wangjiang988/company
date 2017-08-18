<div class="container m-t-86 psr">
        <div class="step pos-rlt">
             <p class="order-head-status">
                 <a href="{{route('buy.show',['id'=>$order->id])}}" class="blue fs18">打造个性座驾</a>
                 <span class="ml5 blue fs18">></span>
                 <a href="{{route('parts.list',['id'=>$order->id])}}" class="ml5 blue fs18">已订购的精品</a>
                 <span class="ml5 blue fs18">></span>
                 <span class="ml5 blue fs18">协商减少订购</span>
             </p>

        </div>
    </div>

    <div class="container pos-rlt content">
        <p class="mt20 ml50 red"><span>由于售方已为您备货，减少数量请求须征得售方同意方能达成。如售方无法满足亦请您谅解～</span></p>
         <div class="xs-hd wp90">
            <ul class="ml30">
                <li class="cur">1.发起协商</li>
                <li>2.协商中</li>
                <li>3.完成协商</li>
                <div class="clear"></div>
            </ul>
        </div>

        <div class="clear"></div>
        <div class="wapper has-min-step">
            <div class="clear m-t-10"></div>
            <form id="main" action="{{route('parts.edit',['id'=>$order->id])}}" method="post">
            {{csrf_field()}}
                <table  class="tbl tbl-blue tbl-xzj">
                    <tr>
                        <th width="130"><b>品牌</b></th>
                        <th width="150"><b>名称</b></th>
                        <th width="350"><b>型号/说明</b></th>
                        <th width="120"><b>已订件数</b></th>
                        <th width="130"><b>希望件数减少为</b></th>
                    </tr>
                    @foreach($datas->sortByDesc('xzj_type') as $key=>$data)
                    <tr>
                @if($data->xzj_type)
                    <td><p class="tac">原厂</p></td>
                @else
                    <td><p class="tac">{{$data->xzj_brand}}</p></td>
                @endif
                        <td><p class="tac">{{$data->xzj_title}}</p></td>
                        <td><p class="tal">{{$data->xzj_model}}</p></td>
                        <td><p class="tac">{{$data->same_sun}}</p></td>
                        @if($order->orderXzjEdit->where('xzj_id',$data->xzj_id)->where('is_install',2)->isEmpty())
                        <input type="hidden" value="{{$data->xzj_id}}" name="xzj[{{$data->xzj_id}}][id]">
                        <td>
                            <div class="mauto wp80 center psr ">
                                <div class="x-w xuan nomargin ">
                                    <span class="red landscape mt-10">供应</span>
                                    <a @click="prev" class="prev">-</a>
                                    <input readonly  type="text" value="{{$data->same_sun}}" class="input" def-value="{{$data->same_sun}}" name="xzj[{{$data->xzj_id}}][num]">
                                    <a @click="next($event,{{$data->same_sun}})" class="next">+</a>
                                    <span class="red landscape mt-10 ml5">紧张</span>
                                    <div v-if="false" class="air-bubbles trans"><div class="air-txt noselect">请恕无法重复发起售方未同意请求</div></div>
                                </div>

                            </div>
                        </td>
                        @else
                        <td>
                            <div class="mauto wp80 center psr ">
                                <div class="x-w xuan nomargin ">
                                    <span class="red landscape mt-10">供应</span>
                                    <a @click="prev" class="prev"></a>
                                    <input readonly  type="text" value="{{$data->same_sun}}" class="input" def-value="{{$data->same_sun}}">
                                    <a @click="next($event,{{$data->same_sun}})" class="next"></a>
                                    <span class="red landscape mt-10 ml5">紧张</span>
                                    <div v-if="true" class="air-bubbles trans"><div class="air-txt noselect">请恕无法重复发起售方未同意请求</div></div>
                                </div>

                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </table>
            </form>
            <div class="clear"></div>
            <p class="tac red hide mt50" :class="{show:error}">未发现减少请求，无需协商请返回～</p>
            <div class=" tac psr center" :class="{mt50:!error}">
                <a href="javascript:;" @click="send" class="btn btn-danger fs16 btn-auto btn-white ">发起协商请求</a>
                <a href="{{route('buy.show',['id'=>$order->id])}}" class="btn btn-s-md btn-danger fs18 sure ml50">返回</a>
            </div>




            <div id="sendWin" class="popupbox">
                <div class="popup-title">核对减少请求</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <p class="fs14 tac">
                           <br>
                           <div class="tip-text fs14 text-left inline-block">
                               <div class="tac">
                                    <p class="inline-block"><span class="icon-large icon-error-large fl"></span><span class="fl ml10 mt10">您是否希望减少下列已订购产品？</span></p>
                               </div>
                               <div class="xzj-list-wrapper xzj-list-wrapper-small">
                                   <table class="tbl tbl-blue tac">
                                       <tr>
                                           <th width="120"><b>品牌</b></th>
                                           <th width="180"><b>名称</b></th>
                                           <th width="122"><b>已订件数</b></th>
                                           <th width="140"><b>希望件数减少为</b></th>
                                       </tr>
                                       <tr v-for="order in orderList">
                                           <td>
                                               <p>@{{order.brand}}</p>
                                           </td>
                                           <td>
                                               <p>@{{order.name}}</p>
                                           </td>
                                           <td>
                                               <p>@{{order.oldcount}}</p>
                                           </td>
                                           <td>
                                               <p>@{{order.count}}</p>
                                           </td>
                                       </tr>
                                   </table>
                               </div>
                               <div class="clear"></div>
                               <div class="xzj-code-send-wrapper mt20">
                                   <span class="fl">手 机 号： {{changeMobile(Auth::user()->phone)}}</span>
                                   <span class="ml20 fl">验 证 码：</span>
                                   <phone-code class="inline-block fl nopadding hasnomargin" phone="{{Auth::user()->phone}}" @valite-code="getCode" sendtype="78635075" sendurl="/parts/getcode"></phone-code>
                               </div>
                               <div class="clear"></div>
                               <p class="tac mt10">
                                   <span v-show="codeStatus == 0" class="red">请输入验证码</span>
                                   <span v-show="codeStatus == 2" class="red">验证码有误，请重新输入~</span>
                                   <span v-show="codeStatus == 3" class="red">验证码已失效，请重新获取~</span>
                               </p>
                           </div>
                           <div class="clear"></div>

                           <br>
                        </p>
                        <div class="m-t-10"></div>
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs18 do  w100">确认</a>
                        <a href="javascript:;" @click="chance" class="btn btn-s-md btn-danger fs18 sure w100 ml50 inline-block ">取消</a>
                        <div class="clear"></div>
                        <div class="m-t-10"></div>
                    </div>
                </div>
            </div>


            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>