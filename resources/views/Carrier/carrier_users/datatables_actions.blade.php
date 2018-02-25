{!! Form::open(['route' => ['carrierUsers.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierUsers.edit', $id)) !!}">
        <i class="fa fa-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    <a class="btn btn-primary btn-xs" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierUsers.editPassword',$id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit_password') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierUsers.destroy',$id)) !!}
</div>
{!! Form::close() !!}
