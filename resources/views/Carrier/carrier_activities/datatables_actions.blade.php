{!! Form::open(['route' => ['carrierActivities.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' href="{!! route('carrierActivities.edit',$id) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierActivities.destroy',$id)) !!}
</div>
{!! Form::close() !!}


