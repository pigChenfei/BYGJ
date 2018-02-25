{!! Form::open(['route' => ['carrierAgentLevels.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentLevels.edit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    @if($type == 1)
        <a class='btn btn-primary btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentLevels.commissionAgent', $id)) !!}">
            <i class="fa fa-dollar">佣金设置</i>
        </a>
        <a class='btn btn-primary btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentLevels.platformFee', $id)) !!}">
            <i class="fa fa-dollar">佣金方案</i>
        </a>
        @if($is_multi_agent == 1 && \WinwinAuth::currentWebCarrier()->is_multi_agent == 1)
            <a class='btn btn-primary btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentLevels.agentLevelRebate', $id)) !!}">
                <i class="fa fa-dollar">多级比例设置</i>
            </a>
        @endif
    @elseif($type == 2)
        <a class='btn btn-primary btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentLevels.rebateFinancialFlowAgent', $id)) !!}">
            <i class="fa fa-dollar">洗码设置</i>
        </a>
    @elseif($type == 3)
        <a class='btn btn-primary btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentLevels.costTakeAgent', $id)) !!}">
            <i class="fa fa-dollar">占成设置</i>
        </a>
    @endif
    
    {!! TableScript::createDeleteButtonScript(Route('carrierAgentLevels.destroy',$id)) !!}
</div>
{!! Form::close() !!}
