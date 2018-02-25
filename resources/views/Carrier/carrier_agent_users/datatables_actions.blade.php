{!! Form::open(['route' => ['carrierAgentUsers.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs agentUser_edit' onclick="$.fn.overlayToggle();
        var _me = this;
        $.fn.winwinAjax.buttonActionSendAjax(_me,'{!! route('carrierAgentUsers.showAgentUserInfoEditModal',$id) !!}',{},function(content){
            $.fn.overlayToggle();
            $('#userInfoEditModal').html(content);
            $('#userInfoEditModal').modal('show');
        },function(){

        },'GET',{dataType:'html'})">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    <a class="btn btn-primary btn-xs" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentUsers.editPassword',$id)) !!}">
        <i class="fa fa-tv">{!! Lang::get('common.edit_password') !!}</i>
    </a>
    <a class="btn btn-default btn-xs" onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierAgentUsers.editTemplate',$id)) !!}">
        <i class="glyphicon glyphicon-edit">模板管理</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierAgentUsers.destroy',$id)) !!}
</div>
{!! Form::close() !!}

