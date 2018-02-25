<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierThirdPartPays.edit', $id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierThirdPartPays.destroy',$id)) !!}
</div>
{!! Form::close() !!}
