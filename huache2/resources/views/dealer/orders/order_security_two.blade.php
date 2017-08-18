@extends('_layout.orders.base_order')
@section('title', '等待提交选项-用户管理-华车网')
@section('content')
    <div class="container content m-t-86 psr">
       <div class="cus-step">
           <div class="line stp-2"></div>
           <ul>
               <li class="first"><span class="hide">1</span><i class="cur-step">1</i></li>
               <li class="second"><span>2</span><i class="cur-step cur-step-2">2</i></li>
               <li class="third"><span>3</span><i>3</i></li>
               <li class="fourth"><span>4</span><i>4</i></li>
               <li class="last"><span>5</span><i>5</i></li>
           </ul>
       </div>
       <div class="step">
           <div class="min-step">
                <div class="m-content m-content-2">
                    <small>等待到账</small>
                    <i></i>
                    <small class="juhuang">预约准备</small>
                </div>
            </div>
       </div>

        <div class="wapper has-min-step">
            <div class="box">
                <div class="box-inner  box-inner-def">
                   @include('dealer.orders._layout.content')
                    <p>温馨提示：订单完整内容，参见订单总详情。</p>
                    <form action="{{route('dealer.nonfactory')}}" name="wait-sub-form" method="post">
                    {{csrf_field()}}
                     <input type="hidden" name="id" value="{{$order->id}}">
                        <div class="m-t-10"></div>
                        @if($order->orderAttr->new_file_comment)
                        <h2 class="title">
                            <span class="red">*</span><span class="blue ml5 weight">待处理</span>
                        </h2>
                        <p class="ul-prev">办理特别文件（{{$order->orderAttr->new_file_comment}}），是否需要马上获取客户联系方式？</p>
                        <p class="ml20">
                            <label for="yes"><input type="radio" name="or_contact" id="yes" value="1"><span class="ml5">是的，马上要联系客户</span></label>
                            <label for="no" class="ml50"><input type="radio" name="or_contact" id="no" checked="" value="0"><span class="ml5">不用</span></label>
                        </p>
                        @endif
                        <p class="ul-prev">
                           <span>选择向客户推荐的非原厂选装精品</span>
                           <a href="javascript:;" @click="loadGoods" class="btn btn-danger btn-import ml50" style="margin-top: -2px;">导入常用设置</a>
                        </p>
                        <table class="tbl tac">
                          <tr>
                            <td width="70" class="noborder">
                               <label for="checkAll"><input v-model='checked' @click='checkedAll' type="checkbox" name="checkAll" id="checkAll" class="wauto"  /><span class="ml5">全选</span></label>
                            </td>
                            <td><b>品牌</b></td>
                            <td><b>名称</b></td>
                            <td><b>型号/说明</b></td>
                            <td><b>含安装费折后单价</b></td>
                            <td><b>单车可装件数</b></td>
                            <td><b>可供件数</b></td>
                          </tr>
                          <tr v-for="good in goodsList" v-cloak>
                            <td class="noborder">
                               <input v-model='checkboxModel' :data-id="good.xzj_daili_id" :value="good.xzj_daili_id" type="checkbox" :name="'xzj_list['+good.xzj_daili_id+']'" class="wauto">
                            </td>
                            <td>@{{good.xzj_brand}}</td>
                            <td>@{{good.xzj_title}}</td>
                            <td>@{{good.xzj_model}}<br>@{{good.info}}</td>
                            <td>@{{formatMoney(good.xzj_guide_price,2,"￥")}}</td>
                            <td>@{{good.xzj_max_num}}</td>
                            <td>@{{good.xzj_has_num}}</td>
                          </tr>
                          <tr v-show="isLoading && goodsList.length > 0">
                            <td colspan="7" class="noborder">
                              <div class="loading"></div>
                            </td>
                          </tr>

                        </table>
                        <p class="center" id="btn-control-wapper">
                            <a href="javascript:;" @click="send" class="btn btn-danger fs16 mt50">确定提交</a>
                        </p>
                    </form>
            </div>

        </div>


         <div id="export" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac">
                       <br>
                       <span class="tip-text fs14 text-left inline-block">确定导入常用管理中的该车型最新的<br>非原厂选装精品吗？</span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doLoadGoods({{$order->daili_dealer_id}},{{$order->brand_id}})" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>
                </div>
            </div>
          </div>

          <div id="send" class="popupbox">
            <div class="popup-title">温馨提示</div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac">
                       <br>
                       <span class="tip-text fs14 text-left inline-block">确定提交所选内容吗？</span>
                       <div class="clear"></div>
                       <br>
                    </p>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="doSend" class="btn btn-s-md btn-danger fs14 do w100 ">确认</a>
                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20 inline-block ">返回</a>
                    <div class="clear"></div>
                </div>
            </div>
          </div>

    </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/custom/js/module/custom/custom-order-prepare", "module/common/common"],function(v,u,c){
            @if(! is_null($order->orderDate))
            u.init('{{date('Y-m-d H:i:s',time())}}',@if($order->orderDate->status)'{{date('Y-m-d H:i:s',strtotime("-7 days",strtotime($order->orderDate->jiaoche_at)))}}'@else'{{date('Y-m-d H:i:s',time())}}'@endif,function(){
              //设置回调
            }),
            u.getGoodsList({{$order->daili_dealer_id}},{{$order->brand_id}},function(){

            })
            @endif
        })
    </script>
@endsection
