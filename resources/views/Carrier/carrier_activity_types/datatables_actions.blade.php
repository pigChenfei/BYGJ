{!! Form::open(['route' => ['carrierActivityTypes.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierActivityTypes.edit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierActivityTypes.destroy',$id)) !!}
</div>
{!! Form::close() !!}
