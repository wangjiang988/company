  <nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
      <div id="navbar" class="collapse navbar-collapse">
        <div class="navbar-header pos-rlt">
          <a class="navbar-brand logo" href="{{ route('/') }}">华车</a>
        </div>
        <ul class="nav navbar-nav"></ul>
        <ul class="nav navbar-nav control">
          @if(session('user.is_login'))
            <li class=""><a href="{{ route('user.ucenter') }}">{{ session('user.member_name') }}</a><i></i></li>
            <li><a href="{{ route('user.logout') }}">退出登录</a></li>
          @else
            <li class=""><a ms-click="login" href="javascript:;">快速登陆</a><i></i></li>
            <li><a href="{{ route('user.reg.fill_mobile') }}">快捷注册</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
