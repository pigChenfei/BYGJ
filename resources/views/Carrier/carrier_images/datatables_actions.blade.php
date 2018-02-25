<div class='btn-group'>
    {{--<a href="{{ route('carrierImages.show', $id) }}" class='btn btn-default btn-xs'>--}}
        {{--<i class="glyphicon glyphicon-eye-open">{!! Lang::get('common.show') !!}</i>--}}
    {{--</a>--}}
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierImages.edit', $id)) !!}">
        <i class="fa fa-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierImages.destroy',$id)) !!}
</div>
{!! Form::close() !!}
