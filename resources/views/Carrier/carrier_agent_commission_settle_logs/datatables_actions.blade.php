{!! Form::open(['route' => ['carrierAgentDomains.destroy', $id], 'method' => 'delete']) !!}
@if($status == 1)
    <div class='btn-group'>
        <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentSettleLogs.theTrial', $id)) !!}">
            <i class="glyphicon glyphicon-edit">初审</i>
        </a>
    </div>
@elseif($status == 2)
    <div class='btn-group'>
        <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentSettleLogs.reviewTrial', $id)) !!}">
            <i class="glyphicon glyphicon-edit">复审</i>
        </a>
    </div>
@endif

{!! Form::close() !!}


