{!! Form::open(['route' => ['carrierServiceTeams.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierServiceTeams.edit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    <a class="btn btn-primary btn-xs" onclick="{!! TableScript::addOrEditModalShowEventScript(route('CarrierServiceTeams.permissionSetShow',$id)) !!}">
        <i class="fa fa-group">部门权限设置</i>
    </a>
    @if($is_administrator == false)
        {!! TableScript::createDeleteButtonScript(Route('carrierServiceTeams.destroy',$id)) !!}
    @endif
</div>
{!! Form::close() !!}
