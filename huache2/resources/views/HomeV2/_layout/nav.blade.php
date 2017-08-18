<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="{{ url('/') }}">华车</a>
            </div>
            <ul class="nav navbar-nav">
                <li class=""><a href="#home">首页</a></li>
                <li class=""><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            @yield('nav_login')
        </div>
    </div>
</nav>