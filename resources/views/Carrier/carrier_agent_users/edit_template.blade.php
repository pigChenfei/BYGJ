<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">修改代理加盟中心模板</h4>
        </div>
        {!! Form::model($carrierAgentUser, ['route' => ['carrierAgentUsers.saveTemplate', $carrierAgentUser->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="form-group col-sm-12">
                    {!! Form::label('template_agent_admin', '模板选择:') !!}
                    <select name="template_agent_admin"  class="selectpicker show-tick form-control">
                        @foreach($templates as $t)
                            <option value="{!! $t->templates->value !!}" @if($carrierAgentUser->template_agent_admin == $t->templates->value) selected @endif>{!! $t->templates->alias !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentUsers.saveTemplate',$carrierAgentUser->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>