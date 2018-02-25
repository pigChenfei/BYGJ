<li class="treeview {{ Route::is('players.*','carrierPlayerLevels*','carrierInviteFriendConf.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-user"></i> <span>会员管理</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('players.index'))

            <li class="{{ Route::is('players.*') ? 'active' : '' }}">
                <a href="{!! route('players.index') !!}"><i class="fa fa-group"></i>所有会员列表</a>
            </li>
        @endif
        {{--<li class="">--}}
        {{--<a href=""><i class="fa fa-clock-o"></i><span>会员管理日志</span></a>--}}
        {{--</li>--}}
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierPlayerLevels.index'))

            <li class="{{ Route::is('carrierPlayerLevels*') ? 'active' : '' }}">
                <a href="{!! route('carrierPlayerLevels.index') !!}"><i class="fa fa-bars"></i><span>会员等级设置</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierInviteFriendConf.edit'))

            <li class="{{ Route::is('carrierInviteFriendConf*') ? 'active' : '' }}">
                <a href="{!! route('carrierInviteFriendConf.edit') !!}"><i
                            class="fa  fa-heart-o"></i><span>邀请好友设置</span></a>
            </li>
        @endif
    </ul>
</li>

<!--<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>风控管理</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>风控取款审核</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>风控审核记录</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>套利会员管理</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>风控管理设置</span></a>
        </li>
    </ul>
</li>-->

<li class="treeview  {{ Route::is('playerAccountLogs.*','playerDepositPayLogs.*','playerAccountAdjustLogs.*','playerWithdrawFlowLimitLogs.*','playerWithdrawLogs.*','playerDepositPayReviewLogs.*','playerTransferUnknownProcess.*') ? 'active' : '' }}"">
<a href="#">
    <i class="fa fa-money"></i> <span>会员资金</span>
    <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
</a>
<ul class="treeview-menu">
    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerDepositPayReviewLogs.index'))

        <li class="{{ Route::is('playerDepositPayReviewLogs.*') ? 'active' : '' }}">
            <a href="{!! route('playerDepositPayReviewLogs.index') !!}"><i class="fa fa-cc-visa"></i><span>会员存款审核</span></a>
        </li>
    @endif
    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerDepositPayLogs.index'))

        <li class="{{ Route::is('playerDepositPayLogs.*') ? 'active' : '' }}">
            <a href="{!! route('playerDepositPayLogs.index') !!}"><i class="fa fa-search"></i><span>会员存款记录</span></a>
        </li>
    @endif
    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerAccountLogs.index'))

        <li class="{{ Route::is('playerAccountLogs.*') ? 'active' : '' }}">
            <a href="{!! route('playerAccountLogs.index') !!}"><i class="fa fa-line-chart"></i>会员资金流水</a>
        </li>
    @endif
    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerAccountAdjustLogs.index'))

        <li class="{{ Route::is('playerAccountAdjustLogs.*') ? 'active' : '' }}">
            <a href="{!! route('playerAccountAdjustLogs.index') !!}"><i class="fa fa-adjust"></i><span>会员资金调整</span></a>
        </li>
    @endif
    {{--<li class="{{ Route::is('playerWithdrawWaitingReviewLogs.*') ? 'active' : '' }}">--}}
    {{--<a href="{!! route('playerWithdrawWaitingReviewLogs.index') !!}"><i class="fa fa-edit"></i><span>会员取款记录</span></a>--}}
    {{--</li>--}}
    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerWithdrawLogs.index'))
		<li class="{{ Route::is('playerWithdrawLogs.verify') ? 'active' : '' }}">
            <a href="{!! route('playerWithdrawLogs.verify') !!}"><i class="fa fa-list-alt"></i><span>会员取款审核</span></a>
        </li>
        <li class="{{ Route::is('playerWithdrawLogs.index') ? 'active' : '' }}">
            <a href="{!! route('playerWithdrawLogs.index') !!}"><i class="fa fa-list-alt"></i><span>会员取款记录</span></a>
        </li>
    @endif

    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerWithdrawFlowLimitLogs.index'))

        <li class="{{ Route::is('playerWithdrawFlowLimitLogs.*') ? 'active' : '' }}">
            <a href="{!! route('playerWithdrawFlowLimitLogs.index') !!}"><i class="fa fa-bar-chart"></i><span>流水限制汇总</span></a>
        </li>
    @endif

    @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerTransferUnknownProcess.index'))
        <li class="{{ Route::is('playerTransferUnknownProcess.*') ? 'active' : '' }}">
        <a href="{!! route('playerTransferUnknownProcess.index') !!}"><i class="fa fa-list-alt"></i><span>转账未知处理</span></a>
        </li>
    @endif
</ul>
</li>

<li class="treeview {{ Route::is('carrierActivityTypes.*','carrierActivities.*','carrierActivityAudits*','carrierActivityFund.*','carrierActivityAuditHistory.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-gamepad"></i> <span>优惠活动</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierActivities.index'))
            <li class="{{ Route::is('carrierActivities*') ? 'active' : '' }}">
                <a href="{!! route('carrierActivities.index') !!}"><i class="fa fa-list"></i>优惠活动管理</a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierActivityAudits.index'))
            <li class="{{ Route::is('carrierActivityAudits*') ? 'active' : '' }}">
                <a href="{!! route('carrierActivityAudits.index') !!}"><i
                            class="fa fa-search-plus"></i><span>活动审核管理</span></a>
            </li>
        @endif
            <li class="{{ Route::is('carrierActivityFund*') ? 'active' : '' }}">
                <a href="{!! route('carrierActivityFund.index') !!}"><i class="fa fa-database"></i>活动资金统计</a>
            </li>
            <li class="{!! Route::is('carrierActivityAuditHistory.*') ? 'active' : '' !!}">
                <a href="{!! route('carrierActivityAuditHistory.index') !!}"><i class="fa fa-clock-o"></i>活动审核历史</a>
            </li>
    </ul>
</li>

<li class="treeview {{ Route::is('playerBetFlowLogs.*','playerRebateFinancialFlows.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-life-saver"></i> <span>投注洗码</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerBetFlowLogs.index'))

            <li class="{{ Route::is('playerBetFlowLogs.*') ? 'active' : '' }}">
                <a href="{!! route('playerBetFlowLogs.index') !!}"><i class="fa fa-list-ol"></i><span>会员投注明细</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('playerRebateFinancialFlows.index'))

            <li class="{{ Route::is('playerRebateFinancialFlows.*') ? 'active' : '' }}">
                <a href="{!! route('playerRebateFinancialFlows.index') !!}"><i
                            class="fa fa-gift"></i><span>发放投注洗码</span></a>
            </li>
        @endif

        {{--<li class="">--}}
        {{--<a href=""><i class="fa fa-clock-o"></i><span>会员洗码历史</span></a>--}}
        {{--</li>--}}
        {{--<li class="">--}}
        {{--<a href=""><i class="fa fa-clock-o"></i><span>洗码参数设置</span></a>--}}
        {{--</li>--}}
    </ul>
</li>

<!--<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>电销管理</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>今日注册会员</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>会员活跃情况</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>电销管理日志</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>电销管理设置</span></a>
        </li>
    </ul>
</li>-->

<!--<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>积分管理</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>积分变动日志</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>积分游戏设置</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>兑换商品管理</span></a>
        </li>
        <li class="">
            <a href=""><i class="fa fa-clock-o"></i><span>积分参数设置</span></a>
        </li>
    </ul>
</li>-->

<li class="treeview {{ Route::is('carrierAgentLevels*','carrierAgentUsers.*','carrierAgentDomains.*','carrierAgentAudits.*','carrierAgentAuditHistorys.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-user-plus"></i> <span>代理管理</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentAudits.index'))

            <li class="{{ Route::is('carrierAgentAudits.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentAudits.index') !!}"><i class="fa fa-registered"></i><span>代理注册审核</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentUsers.index'))

            <li class="{{ Route::is('carrierAgentUsers.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentUsers.index') !!}"><i class="fa  fa-users"></i><span>代理用户列表</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentAuditHistorys.index'))

            <li class="{{ Route::is('carrierAgentAuditHistorys.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentAuditHistorys.index') !!}"><i
                            class="fa fa-reorder"></i><span>代理审核历史</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentDomains.index'))

            <li class="{{ Route::is('carrierAgentDomains.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentDomains.index') !!}"><i
                            class="fa fa-sitemap"></i><span>代理域名设置</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentLevels.index'))

            <li class="{{ Route::is('carrierAgentLevels*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentLevels.index') !!}"><i class="fa fa-male"></i><span>代理类型设置</span></a>
            </li>
        @endif
    </ul>
</li>

<li class="treeview {{ Route::is('agentAccountAdjustLogs*','carrierAgentDepositPayLogs*','carrierAgentSettleLogs.*','carrierAgentWithdrawLogs.*','carrierAgentWithdrawLogsVerify.*','carrierAgentSettleHistoryLogs.*','carrierAgentDepositVerify.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-dollar"></i> <span>代理资金</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">

        {{--<li class="{{ Route::is('carrierAgentDepositVerify.*') ? 'active' : '' }}">--}}
            {{--<a href="{!! route('carrierAgentDepositVerify.index') !!}"><i class="fa fa-money"></i><span>代理存款审核</span></a>--}}
        {{--</li>--}}
        {{--@if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentDepositPayLogs.index'))--}}

            {{--<li class="{{ Route::is('carrierAgentDepositPayLogs.*') ? 'active' : '' }}">--}}
                {{--<a href="{!! route('carrierAgentDepositPayLogs.index') !!}"><i--}}
                            {{--class="fa  fa-align-left"></i><span>代理存款记录</span></a>--}}
            {{--</li>--}}
        {{--@endif--}}
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('agentAccountAdjustLogs.index'))
            <li class="{{ Route::is('agentAccountAdjustLogs.*') ? 'active' : '' }}">
                <a href="{!! route('agentAccountAdjustLogs.index') !!}"><i class="fa fa-indent"></i><span>代理资金调整</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentSettleLogs.index'))
            <li class="{{ Route::is('carrierAgentSettleLogs.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentSettleLogs.index') !!}"><i
                            class="fa fa-download"></i><span>代理佣金结算</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentSettleHistoryLogs.index'))
            <li class="{{ Route::is('carrierAgentSettleHistoryLogs.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentSettleHistoryLogs.index') !!}"><i
                            class="fa fa-th-list"></i><span>代理结算历史</span></a>
            </li>
        @endif
    <!--        <li class="">
                    <a href=""><i class="fa fa-clock-o"></i><span>代理取款审核</span></a>
                </li>-->
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierAgentWithdrawLogs.index'))
			<li class="{{ Route::is('carrierAgentWithdrawLogsVerify.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentWithdrawLogsVerify.index') !!}"><i
                            class="fa fa-list-alt"></i><span>代理取款审核</span></a>
            </li>
            <li class="{{ Route::is('carrierAgentWithdrawLogs.*') ? 'active' : '' }}">
                <a href="{!! route('carrierAgentWithdrawLogs.index') !!}"><i
                            class="fa fa-list-alt"></i><span>代理取款记录</span></a>
            </li>
        @endif
    </ul>
</li>

<li class="treeview {{ Route::is('carrierPayChannels.*','carrierPayBankCards.*','carrierThirdPartPays.*','carrierQuotaConsumptionLogs.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-money"></i> <span>系统资金</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierQuotaConsumptionLogs.index'))
            <li class="{{ Route::is('carrierQuotaConsumptionLogs.*') ? 'active' : '' }}">
                <a href="{!! route('carrierQuotaConsumptionLogs.index') !!}"><i
                            class="fa fa-building-o"></i><span>系统资金明细</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierPayChannels.index'))
            <li class="{{ Route::is('carrierPayChannels*') ? 'active' : '' }}">
                <a href="{!! route('carrierPayChannels.index') !!}"><i class="fa fa-tasks"></i><span>支付渠道设置</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierThirdPartPays.index'))
            <li class="{{ Route::is('carrierThirdPartPays*') ? 'active' : '' }}">
                <a href="{!! route('carrierThirdPartPays.index') !!}"><i class="fa  fa-exchange"></i><span>支付接口设置</span></a>
            </li>
        @endif
        {{--<li class="{{ Route::is('carrierPayBankCards*') ? 'active' : '' }}">--}}
        {{--<a><i class="fa fa-credit-card"></i><span>存款对账管理</span></a>--}}
        {{--</li>--}}
    </ul>
</li>
<li class="treeview {{ Route::is('carrierGamePlats*','carrierDashLoginConfs*','carrierGames.*','carrierUsers*','carrierServiceTeams.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa  fa-desktop"></i> <span>系统设置</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierDashLoginConfs.index'))
            <li class="{{ Route::is('carrierDashLoginConfs*') ? 'active' : '' }}">
                <a href="{!! route('carrierDashLoginConfs.index') !!}"><i class="fa fa-gear"></i><span>系统参数设置</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierGames.index'))
            <li class="{{ Route::is('carrierGames.*') ? 'active' : '' }}">
                <a href="{!! route('carrierGames.index') !!}"><i class="fa fa-cubes"></i><span>游戏平台设置</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierServiceTeams.index'))
            <li class="{{ Route::is('carrierServiceTeams*') ? 'active' : '' }}">
                <a href="{!! route('carrierServiceTeams.index') !!}"><i class="fa fa-group"></i><span>管理部门设置</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierUsers.index'))
            <li class="{{ Route::is('carrierUsers*') ? 'active' : '' }}">
                <a href="{!! route('carrierUsers.index') !!}"><i class="fa fa-key"></i><span>管理账号设置</span></a>
            </li>
        @endif
    </ul>
</li>

<li class="treeview {{ Route::is('carrierPlayerNews*','carrierAgentNews*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-commenting-o"></i> <span>系统消息</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('carrierPlayerNews*') ? 'active' : '' }}">
            <a href="{!! route('carrierPlayerNews.index') !!}"><i class="fa fa-commenting-o"></i><span>会员消息</span></a>
        </li>
        <li class="{{ Route::is('carrierAgentNews*') ? 'active' : '' }}">
            <a href="{!! route('carrierAgentNews.index') !!}"><i class="fa fa-comments-o"></i><span>代理消息</span></a>
        </li>
    </ul>
</li>

<li class="treeview {{ Route::is('carrierWinLoseStastics.*','gameWinLoseStastics.*','agentWinLoseStastics*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-area-chart"></i> <span>报表中心</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('carrierWinLoseStastics*') ? 'active' : '' }}">
            <a href="{!! route('carrierWinLoseStastics.index') !!}"><i class="fa fa-pie-chart"></i><span>公司输赢报表</span></a>
        </li>
        <li class="{{ Route::is('agentWinLoseStastics*') ? 'active' : '' }}">
            <a href="{!! route('agentWinLoseStastics.index') !!}">
            	<i class="fa fa-bar-chart"></i><span>代理输赢报表</span>
            </a>
        </li>
        <li class="{{ Route::is('gameWinLoseStastics*') ? 'active' : '' }}">
            <a href="{!! route('gameWinLoseStastics.index') !!}"><i class="fa fa-line-chart"></i><span>游戏输赢报表</span></a>
        </li>
    </ul>
</li>

<li class="treeview {{ Route::is('carrierImages.*','carrierWebSiteConfs.*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa  fa-hdd-o"></i> <span>前台设置</span>
        <span class="pull-right-container">
            <i class="pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierWebSiteConfs.index'))
            <li class="{{ Route::is('carrierWebSiteConfs*') ? 'active' : '' }}">
                <a href="{!! route('carrierWebSiteConfs.index') !!}"><i
                            class="fa fa-sitemap"></i><span>网站信息管理</span></a>
            </li>
        @endif
        @if(\App\Helpers\Privileges\PrivilegeHelper::instance()->carrierUserHasPrivilege('carrierImages.index'))
            <li class="{{ Route::is('carrierImages*') ? 'active' : '' }}">
                <a href="{!! route('carrierImages.index') !!}"><i
                            class="fa fa-file-archive-o"></i><span>广告图片管理</span></a>
            </li>
        @endif
        {{--<li class="">--}}
            {{--<a href=""><i class="fa fa-th-large"></i><span>网站主题管理</span></a>--}}
        {{--</li>--}}
    </ul>
</li>

