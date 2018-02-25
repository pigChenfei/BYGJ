{{--首页导航栏--}}
<ul id="nav" class="nav-nav">
    <li @if(Route::currentRouteName() == '/') class="nav-boorder-bottom" @endif><a href="{!! route('/') !!}">网站首页<b></b></a>
    </li>
    <li @if(Route::currentRouteName() == 'homes.live-entertainment') class="nav-boorder-bottom" @endif><a href="{!! route('homes.live-entertainment') !!}" >真人娱乐<b></b></a>
        <ul>
            <li><a target="_blank" href="{!! route('players.loginPTGame','bal') !!}">PT真人</a></li>
            <li><a target="_blank" href="{!! route('players.loginBBinHall',array('live')) !!}">BBIN真人</a></li>
            <li><a target="_blank" href="{!! route('players.gameLauncher',array('SB','Sunbet_Lobby')) !!}">SUNBET真人</a></li>
            <li><a target="_blank" href="{!! route('players.gameLauncher',array('GD','Gold_Deluxe_Lobby')) !!}">GD真人</a></li>
            <li><a target="_blank" href="{!! route('players.launchItem',array('1172','1001')) !!}">MG真人</a></li>
        </ul>
    </li>
    <li @if(Route::currentRouteName() == 'homes.slot-machine') class="nav-boorder-bottom" @endif><a href="{!! route('homes.slot-machine') !!}" >老虎机<b></b></a>
    	<ul style="left: 285px;display: none;">
            <li><a href="{!! route('players.loginPTGame','arc') !!}">PT电游</a></li>
            <li><a href="{!! route('players.launchItem',array('1232','1002')) !!}">MG电游</a></li>
            <li><a href="{!! route('players.gameLauncher',array('TGP','Dragons_Luck')) !!}">TGP电游</a></li>
        </ul>
    </li>
    <li @if(Route::currentRouteName() == 'homes.ag-fish') class="nav-boorder-bottom" @endif><a href="{!! route('homes.ag-fish') !!}">AG捕鱼<b></b></a>
    </li>
    <li @if(Route::currentRouteName() == 'homes.sports-games') class="nav-boorder-bottom" @endif><a href="{!! route('homes.sports-games') !!}">体育投注<b></b></a>
    </li>
    <li @if(Route::currentRouteName() == 'homes.lottery-betting') class="nav-boorder-bottom" @endif><a href="{!! route('homes.lottery-betting') !!}">彩票投注<b></b></a>
    </li>
    <li @if(Route::currentRouteName() == 'homes.mobile') class="nav-boorder-bottom" @endif><a href="{!! route('homes.mobile') !!}">手机版<b></b></a>
    </li>
    <li @if(Route::currentRouteName() == 'homes.special-offer') class="nav-boorder-bottom" @endif><a href="{!! route('homes.special-offer') !!}">优惠活动</a>
    </li>
</ul>
