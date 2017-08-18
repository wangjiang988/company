<div class="head-wrapper">
    <div class="head-container">
        <div class="head-logo-wrapper psr">
            <a class="logo" href="/">华车网</a>
        </div>
        <ul class="head-login-wrapper">
         @if(session('user.member_id') && session('user.is_login'))
            <li class="loginout">
                <label>欢迎您登上 <a href="/dealer/" class="juhuang">{{getSellerName(session('user.member_id'))}}</a>号华车！</label>
                <a @click="loginOutConfirm" href="javascript:;" class="hc-status-loginout ml20">下车</a>
                <div v-cloak v-show="isShowLoginOut" class="login-out-comfirm">
                    <p class="tac">确认退出吗？</p>
                    <div class="confirm">
                        <a @click="sureLoginOut(1)" href="javascript:;">确认退出</a>
                        <a @click="canceLoginOut" href="javascript:;" class="cancel">取消</a>
                    </div>
                </div>
            </li>
            @endif
        </ul>
    </div>
    <div class="clear"></div>
</div>