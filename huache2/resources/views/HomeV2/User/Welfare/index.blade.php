@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content">
        <h4 class="blue-title"><span class="fs14">我的代金券<span class="fs12 p-gray ml10">未激活的代金券赶快通过激活码来激活使用吧~</span></span><div class="mt5"></div></h4>
        <div class="content-wapper ">
            <form action="/user/getVouchersList/" method="post" name="vouchers-list-form">
                <input type="hidden" name="token" value="{{ csrf_token()}}">
                <a href="javascript:;" class="btn btn-s-md btn-danger btn-auto fl">代金券激活</a>
                <a href="#" class="fr juhuang mt-10">华车代金券规则说明</a>
                <div class="clear"></div>
                <div class="vouchers-option-wrapepr">
                    <drop-down @receive-params="getVouchers" def-value="可用代金卷" class-name="btn-auto btn-dropdown-small" :list="vouchersStatusList"></drop-down>
                    <drop-down v-if="vouchersStatus == 1" class="ml20" @receive-params="getVolumeType" def-value="全部卷类型" class-name="btn-auto btn-dropdown-small" :list="volumeTypeList"></drop-down>
                    <a href="javascript:;" v-if="vouchersStatus == 1" data- ="" class="fl ml50 mt5">新到账</a>
                    <a href="javascript:;" v-if="vouchersStatus == 1" class="fl ml50 mt5">即将过期</a>
                    <div class="clear"></div>
                </div>
                <div class="vouchers-list-wrapper" v-cloak>
                    <div class="vouchers-item-wrapper" :class="{ 'vouchers-disabled':vouchersStatus!=1, 'vouchers-disabled-overdue':vouchersStatus==3 ,'vouchers-disabled-used':vouchersStatus==2}" v-for="voucher in vouchersList">
                        <div class="vouchers-item">
                            <div class="vouchers-head">
                                <div class="circular"></div>
                                <h1>￥@{{voucher.money}}</h1>
                                <p class="fs16">@{{voucher.typeName}}</p>
                                <p class="fs16">@{{voucher.life_start_time}}-@{{voucher.life_end_time}}</p>
                            </div>
                            <div class="vouchers-foot">
                                <div class="vouchers-foot-content">
                                    <p>品类：<span class="p-gray">@{{voucher.category}}</span></p>
                                    <p><b>编号</b>：<span class="p-gray">@{{voucher.voucher_sn}}</span></p>
                                    <p v-if="vouchersStatus == 1"><span class="red">使用须知：</span><span>该券用于支付买车担保金余款不可用于支付诚意金！</span></p>
                                    <p v-if="vouchersStatus == 2">订单号：<span class="p-gray">@{{voucher.ordersn}}</span></p>
                                    <p v-if="vouchersStatus == 2">时间：<span class="p-gray">@{{voucher.usetime}}</span></p>
                                    <p class="tac">
                                        <a href="javascript:;" class="btn btn-danger btn-auto btn-round mt10">立即使用</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="vouchers-list-end">
                    <a href="#" class="juhuang list-link">华车代金券规则说明</a>
                </div>
                <div class="text-center">
                    <pagination @receive-params="getPageIndex" :currents="pageinfo.current" :show-item="pageinfo.showItem" :all-page="pageinfo.allPage"></pagination>
                </div>
            </form>

            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>

        </div>

    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-vouchers", "/webhtml/user/js/module/common/common"],function(v,u,c){
            //如果代金券状态固定
            //可以在user-vouchers.js中直接设置vouchersStatusList
            //删除下面u.initVouchersStatusList方法
            u.initVouchersStatusList([
                {id:1,name:"\u53ef\u7528\u4ee3\u91d1\u5377"},
                {id:2,name:"\u5df2\u7528\u4ee3\u91d1\u5377"},
                {id:3,name:"\u8fc7\u671f\u4ee3\u91d1\u5377"}
            ])
            //如果卷类型固定
            //可以在user-vouchers.js中直接设置volumeTypeList
            //删除下面u.initVolumeTypeList方法
            u.initVolumeTypeList([
                {id:1,name:"\u901a\u7528\u5377"},
                {id:2,name:"\u54c1\u7c7b\u5377"}
            ])

        })
    </script>
@endsection