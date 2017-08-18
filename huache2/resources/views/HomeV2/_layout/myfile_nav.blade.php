<ul class="tab-card">
    <li @if($tab =='id_cart')class="cur"@endif>
        <a href="{{route('auth.showIdCart')}}">实名认证</a>
    </li>
    <li @if($tab =='bank')class="cur"@endif>
        <a href="{{route('user.bank')}}">银行账户认证</a>
    </li>
    <li @if($tab =='special')class="cur"@endif>
        <a href="{{route('special.list')}}">上牌特殊文件</a>
    </li>
</ul>