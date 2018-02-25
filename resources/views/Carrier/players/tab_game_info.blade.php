<table class="table table-bordered table-hover table-responsive">
    <tr>
        <th style="width: 400px">主账户余额</th>
        <td style="width: 300px">{!! $player->main_account_amount !!}</td>
        <td style="text-align: left">
            <button class="btn btn-xs btn-primary" onclick="
                    var _me = this;
                    $.fn.winwinAjax.buttonActionSendAjax(
                            this,
                            '{!! route('players.queryAndSynchronizePlayerAllGameAccountsToDB',$player->player_id) !!}',
                            {},
                            function(){
                                var tabPanel = $(_me).parents('.tab-pane');
                                var tabId    = tabPanel.attr('id');
                                tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                            },
                            function() {

                            },
                            'GET'
                    )
                    "><i class="fa fa-search"></i>一键查询游戏余额</button>
            <button class="btn btn-xs btn-danger" onclick="
                    var _me = this;
                    $.fn.winwinAjax.buttonActionSendAjax(
                    this,
                    '{!! route('players.withDrawAllPlayerGameAccounts',$player->player_id) !!}',
                    {},
                    function(){
                        var tabPanel = $(_me).parents('.tab-pane');
                        var tabId    = tabPanel.attr('id');
                        tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                    },
                    function() {

                    },
                    'GET'
                    )
                    "><i class="fa fa-search"></i>一键收回平台余额</button>
        </td>
    </tr>
</table>

<table style="margin-top: 40px" class="table table-bordered table-hover table-responsive">
    <tr>
        <th style="width: 400px">游戏平台</th>
        <th style="width: 300px;">余额</th>
        <th class="text-center">操作</th>
    </tr>
    @foreach($gameAccounts as $gameAccount)
        <tr>
            <td>{!! $gameAccount->mainGamePlat->main_game_plat_name !!}</td>
            <td>{!! $gameAccount->amount !!}</td>
            <td class="text-left">
                <form action="">
                    <div class='btn-group'>
                        @if($gameAccount->mainGamePlat->main_game_plat_code == \App\Vendor\GameGateway\PT\PTGameGateway::getMainGamePlatCode())
                        <button onclick="var _me = this;
                                $.fn.winwinAjax.buttonActionSendAjax(
                                _me,
                                '{!! route('players.showPTGamePasswordChangeModal',$player->player_id) !!}',
                                {},
                                function(resp){
                                    var modal = $('#tpPasswordChangeModal');
                                    modal.html(resp);
                                    modal.modal('show');
                                },
                                function() {

                                },
                                'GET',
                                {dataType:'html'}
                                )" class='btn btn-primary btn-xs'>
                            <i class="fa fa-key"></i>同步PT密码
                        </button>
                        @endif
                        <button onclick="
                                var _me = this;
                                $.fn.winwinAjax.buttonActionSendAjax(
                                this,
                                '{!! route('players.depositPlayerGameAccount',[$player->player_id,$gameAccount->main_game_plat_id]) !!}',
                                {},
                                function(){
                                    var tabPanel = $(_me).parents('.tab-pane');
                                    var tabId    = tabPanel.attr('id');
                                    tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                                },
                                function() {

                                },
                                'GET'
                                )" class='btn btn-success btn-xs'>
                            <i class="fa fa-sign-in"></i>转入
                        </button>
                        <button onclick="
                                var _me = this;
                                $.fn.winwinAjax.buttonActionSendAjax(
                                this,
                                '{!! route('players.withDrawPlayerGameAccount',[$player->player_id,$gameAccount->main_game_plat_id]) !!}',
                                {},
                                function(){
                                    var tabPanel = $(_me).parents('.tab-pane');
                                    var tabId    = tabPanel.attr('id');
                                    tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                                },
                                function() {

                                },
                                'GET'
                                )" class='btn btn-warning btn-xs'>
                            <i class="fa fa-sign-out"></i>转出
                        </button>
                        <button onclick="
                                var _me = this;
                                $.fn.winwinAjax.buttonActionSendAjax(
                                this,
                                '{!! route('players.switchPlayerGameAccountTransferLockStatus',[$player->player_id,$gameAccount->main_game_plat_id]) !!}',
                                {},
                                function(){
                                    var tabPanel = $(_me).parents('.tab-pane');
                                    var tabId    = tabPanel.attr('id');
                                    tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                                },
                                function() {

                                },
                                'GET'
                                )" class='btn btn-danger btn-xs'>
                            <i class="fa fa-lock"></i>转账{!! $gameAccount->is_locked ? '打开' : '锁定'  !!}
                        </button>
                        <button onclick="
                                var _me = this;
                                $.fn.winwinAjax.buttonActionSendAjax(
                                this,
                                '{!! route('players.switchPlayerGameCloseStatus',[$player->player_id,$gameAccount->main_game_plat_id]) !!}',
                                {},
                                function(){
                                    var tabPanel = $(_me).parents('.tab-pane');
                                    var tabId    = tabPanel.attr('id');
                                    tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                                },
                                function() {

                                },
                                'GET'
                                )" class='btn btn-info btn-xs'>
                            <i class="fa fa-wrench"></i>{!! $gameAccount->is_need_repair ? '开启' : '维护' !!}游戏
                        </button>
                            @if($gameAccount->mainGamePlat->main_game_plat_code != App\Models\Def\MainGamePlat::MG)
                            <button onclick="
                                    var _me = this;
                                    $.fn.winwinAjax.buttonActionSendAjax(
                                    this,
                                    '{!! route('players.forcedToLogOut',[$player->player_id,$gameAccount->main_game_plat_id]) !!}',
                                    {},
                                    function(data){
                                    var tabPanel = $(_me).parents('.tab-pane');
                                    var tabId    = tabPanel.attr('id');
                                    if (data.success) {
                                        alert('强制退出登录成功') ;
                                    } else {
                                    alert('强制退出登录失败') ;
                                    }
                                    tabPanel.load($.fn.vueModalContentComponent.tabIdRouteMap[tabId],null, function (resp) {});
                                    },
                                    function() {

                                    },
                                    'GET'
                                    )" class='btn btn-warning btn-xs'>
                                <i class="fa fa-sign-out"></i>强制退出游戏
                            </button>
                            @endif

                    </div>
                </form>
            </td>
        </tr>
    @endforeach
</table>

