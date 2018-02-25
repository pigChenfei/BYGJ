{!! Form::open(['route' => ['carrierAgentDomains.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentDomains.edit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierAgentDomains.destroy',$id)) !!}
</div>
{!! Form::close() !!}


