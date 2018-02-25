<?php
    $agent = \App\Models\CarrierAgentUser::where(['id'=>\WinwinAuth::agentUser()->id])->first();
    $agentLevel = \App\Models\CarrierAgentLevel::where(['id'=>$agent['agent_level_id']])->first();
?>

<li class="treeview {{ Route::is('agentAccountCenters*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-user"></i> <span>账户中心</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Route::is('agentAccountCenters*') ? 'active' : '' }}">
            <a href="{!! route('agentAccountCenters.index') !!}"><i class="fa fa-list"></i>账户信息</a>
        </li>
    </ul>
</li>
@if($agentLevel->is_multi_agent == 1)
    <li class="treeview {{ Route::is('agentSubs*') ? 'active' : '' }}">
        <a href="{!! route('agentSubs.index') !!}">
            <i class="fa fa-user"></i> <span>下级代理</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
    </li>
@endif

<li class="treeview {{ Route::is('agentPromotePics*') ? 'active' : '' }}">
    <a href="{!! route('agentPromotePics.index') !!}">
        <i class="fa fa-user"></i> <span>推广图片</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
</li>



@if($agentLevel->type == \App\Models\CarrierAgentLevel::COST_TAKE_AGENT)
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i> <span>报表中心</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu">
            <li class="">
                <a href=""><i class="fa fa-list"></i>占成报表</a>
            </li>
            <li class="">
                <a href=""><i class="fa fa-list"></i>子代理报表</a>
            </li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i> <span>财务中心</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu">
            <li class="">
                <a href=""><i class="fa fa-list"></i>快速存款</a>
            </li>
        </ul>

        <ul class="treeview-menu">
            <li class="">
                <a href=""><i class="fa fa-list"></i>快速取款</a>
            </li>
        </ul>
    </li>

@elseif($agentLevel->type == \App\Models\CarrierAgentLevel::COMMISSION_AGETN)
    <li class="treeview {{ Route::is('agentPlayers*','agentPlayerDepositLogs*','agentWithdrawLogs*','agentSettleReports*') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-user"></i> <span>报表中心</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu">
            <li class="{{ Route::is('agentPlayers*') ? 'active' : '' }}">
                <a href="{!! route('agentPlayers.index') !!}"><i class="fa fa-list"></i>会员报表</a>
            </li>
            <li class="{{Route::is('agentPerformances*') ? 'active' : ''}}">
                <a href="{!! route('agentPerformances.index') !!}"><i class="fa fa-list"></i>业绩报表</a>
            </li>
            <li class="{{Route::is('agentSettleReports*') ? 'active' : ''}}">
                <a href="{!! route('agentSettleReports.index') !!}"><i class="fa fa-list"></i>佣金报表</a>
            </li>
            @if($agentLevel->is_multi_agent == 1)
            <li class="">
                <a href=""><i class="fa fa-list"></i>子代理报表</a>
            </li>
            @endif
            <li class="{{ Route::is('agentWithdrawLogs*') ? 'active' : '' }}">
                <a href="{!! route('agentWithdrawLogs.index') !!}"><i class="fa fa-list"></i>取款记录</a>
            </li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i> <span>财务中心</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu">
            <li class="">
                <a href="{!! route('agentWithdraws.index') !!}"><i class="fa fa-list"></i>快速取款</a>
            </li>
        </ul>
    </li>
@elseif($agentLevel->type == \App\Models\CarrierAgentLevel::REBATE_FINANCIAL_FLOW_AGENT)
    <li class="treeview {{ Route::is('agentPlayers*','agentScatterReports*','subordinateAgentScatterReports*','agentWithdrawLogs*') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-user"></i> <span>报表中心</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu">
            <li class="{{ Route::is('agentPlayers*') ? 'active' : '' }}">
                <a href="{!! route('agentPlayers.index') !!}"><i class="fa fa-list"></i>会员报表</a>
            </li>
            <li class="{{Route::is('agentScatterReports*') ? 'active' : ''}}">
                <a href="{!! route('agentScatterReports.index') !!}"><i class="fa fa-list"></i>洗码报表</a>
            </li>
            <li class="{{Route::is('subordinateAgentScatterReports*') ? 'active' : ''}}">
                <a href="{!! route('subordinateAgentScatterReports.index') !!}"><i class="fa fa-list"></i>子代理报表</a>
            </li>
            <li class="{{ Route::is('agentWithdrawLogs*') ? 'active' : '' }}">
                <a href="{!! route('agentWithdrawLogs.index') !!}"><i class="fa fa-list"></i>取款记录</a>
            </li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i> <span>财务中心</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>

        <ul class="treeview-menu">
            <li class="">
                <a href=""><i class="fa fa-list"></i>快速取款</a>
            </li>
        </ul>
    </li>
@endif

<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>消息中心</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href=""><i class="fa fa-list"></i>我的消息</a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>更改密码</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
</li>

<li class="treeview {{ Route::is('agentOpenAccounts*') ? 'active' : '' }}">
    <a href="{!! route('agentOpenAccounts.index') !!}">
        <i class="fa fa-user"></i> <span>开户中心</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
</li>

<li class="treeview">
    <a href="#">
        <i class="fa fa-user"></i> <span>退出登录</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
</li>

