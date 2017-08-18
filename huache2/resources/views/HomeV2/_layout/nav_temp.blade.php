<ul class="nav navbar-nav control">
    @if (Auth::check())
        <li class=""><a href="{{ route('user.home') }}">
                {{ getNikeName(Auth::user()->id,Auth::user()->phone)}}
            </a><i></i></li>
        <li><a href="{{ route('user.logout') }}">退出登录</a></li>
    @else
        <li class=""><a ms-click="login" href="javascript:;">快速登陆</a><i></i></li>
        <li><a href="{{ route('user.getReg') }}">快捷注册</a></li>
    @endif
</ul>