{{--首页导航栏--}}
<ul class="list-unstyled">
    <li>
        <a href="{{ route('/') }}" @if(Route::currentRouteName() == '/') class="active" @endif >首页</a>
    </li>
    <li class="has-nav">
        <a @if(Route::currentRouteName() == 'homes.live-entertainment') class="active" @endif href="{!! route('homes.live-entertainment') !!}">真人娱乐</a>
        <div class="nav-sm" hidden style="left:-151px">
            <a target="_blank" href="{!! route('players.loginBBinHall',array('live')) !!}" class="tx_login_game">BBIN真人</a>
            <a target="_blank" href="{!! route('players.gameLauncher',array('SB','Sunbet_Lobby')) !!}" class="tx_login_game">申博真人</a>
            <a target="_blank" href="{!! route('players.loginPTGame','bal') !!}" class="tx_login_game">PT真人</a>
            <a target="_blank" href="{!! route('players.launchItem',array('1172','1001')) !!}" class="tx_login_game">MG真人</a>
            {{--<a target="_blank" href="{!! route('players.gameLauncher',array('GD','Gold_Deluxe_Lobby')) !!}" class="tx_login_game">GD真人</a>--}}
        </div>
    </li>
    <li class="has-nav">
        <a href="{!! route('homes.slot-machine') !!}" @if(Route::currentRouteName() == 'homes.slot-machine') class="active" @endif>电子游艺</a>
        <div class="nav-sm" hidden style="left:-101px">
            @foreach(\WTemplate::gamePlat(['game_plat_name', 'like', '%电子游戏%']) as $v)
            <a href="{!! route('homes.slot-machine', ['main_game_plat' => $v->main_game_plat_id]) !!}" class="tx_login_game">{{ str_replace('电子游戏','',$v->game_plat_name) }}</a>
            @endforeach
        </div>
    </li>
    <li class="has-nav">
        <a href="{!! route('homes.ag-fish') !!}" @if(Route::currentRouteName() == 'homes.ag-fish') class="active" @endif>捕鱼游戏</a>
        <div class="nav-sm" hidden style="left:-70px">
            <a target="_blank" href="{!! url('players.joinElectronicGame/114') !!}" class="tx_login_game">捕鱼大师</a>
            <a target="_blank" href="{!! url('players.joinElectronicGame/113') !!}" class="tx_login_game">捕鱼达人</a>
        </div>
    </li>
    <li class="has-nav">
        <a href="{!! route('homes.sports-games') !!}"  @if(Route::currentRouteName() == 'homes.sports-games') class="active" @endif>体育投注</a>
        <div class="nav-sm" hidden style="left:-60px">
            <a target="_blank" href="{!! route('players.loginOneWorkHall') !!}" class="tx_login_game">沙巴体育</a>
            <a target="_blank" href="{!! url('players.loginBBinHall/ball') !!}" class="tx_login_game">BBIN体育</a>
        </div>
    </li>
    <li class="has-nav">
        <a href="{!! route('homes.lottery-betting') !!}" @if(Route::currentRouteName() == 'homes.lottery-betting') class="active" @endif>彩票投注</a>
        <div class="nav-sm" hidden style="left:-20px">
            <a target="_blank" href="{!! url('players.loginVRHall') !!}" class="tx_login_game">VR彩票</a>
            <a target="_blank" href="{!! url('players.loginBBinHall/Ltlottery') !!}" class="tx_login_game">BBIN彩票</a>
        </div>
    </li>
    {{--<li><a href="{!! url('players.loginBBinHall/Ltlottery') !!}" class="tx_login_game">彩票投注</a></li>--}}
    <li><a href="{!! route('homes.special-offer') !!}" @if(Route::currentRouteName() == 'homes.special-offer') class="active" @endif>优惠活动</a></li>
</ul>
