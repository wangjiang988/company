<div class="head-wrapper">
    <div class="head-container">
        <div class="head-logo-wrapper">
            <a class="logo" href="/">华车网</a>
        </div>

        <ul class="head-login-wrapper">
            <li class="loginout">
                @if(Auth::check())
                <label>欢迎您，
                    <a href="{{ route('user.home') }}" class="juhuang">
                        {{ getNikeName(Auth::user()->id,Auth::user()->phone) }}
                    </a>
                    <a href="{{ route('user.home') }}" class="hc-status inline-block">我的华车</a>
                </label>
                <a @click="loginOutConfirm" href="javascript:;" class="hc-status-loginout">下车<em>(退出)</em>
                </a>
                @endif
                <div v-cloak v-show="isShowLoginOut" class="login-out-comfirm">
                    <p>退出登陆状态将重新回到<br>华车首页，是否继续？</p>
                    <div class="confirm">
                        <a @click="sureLoginOut(0)" href="javascript:;">确认退出</a>
                        <a @click="canceLoginOut" href="javascript:;" class="cancel">取消</a>
                    </div>
                </div>
            </li>
        </ul>
        <div class="clear"></div>
    </div>
</div>