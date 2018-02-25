<nav class="bar bar-tab">
    <a class="tab-item @if(Route::currentRouteName() == '/') active @endif" href="{{url('/')}}">
        <span class="icon icon-ww icon-homepage"></span>
        <span class="tab-label">首页</span>
    </a>
    <a class="tab-item @if(Route::currentRouteName() == 'players.purse-security') active @endif" href="@if(!\WinwinAuth::memberUser()) {{ url('/homes.mobileLogin') }} @else {{ url('/players.purse-security') }} @endif">
        <span class="icon icon-ww icon-purse"></span>
        <span class="tab-label">钱包</span>
    </a>
    <a class="tab-item @if(Route::currentRouteName() == 'homes.special-offer') active @endif" href="{{url('homes.special-offer')}}">
        <span class="icon icon-ww icon-discount"></span>
        <span class="tab-label">优惠</span>
    </a>
    <a class="tab-item @if(Route::currentRouteName() == 'players.account-security') active @endif" href="@if(!\WinwinAuth::memberUser()) {{ url('/homes.mobileLogin') }} @else {{ url('/players.account-security') }} @endif">
        <span class="icon icon-ww icon-personal_center"></span>
        <span class="tab-label">我的</span>
    </a>
</nav>