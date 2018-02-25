<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">修改密码</h4>
        </div>
        {!! Form::model($carrierAgentUser, ['route' => ['carrierAgentUsers.savePassword', $carrierAgentUser->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="form-group col-sm-12">
                    {!! Form::label('password', '新密码:') !!}
                    <input type="password" class="form-control" id="password" name="password" placeholder="请输入新密码">
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('confirm_password', '确认密码:') !!}
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="请再次输入新密码">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentUsers.savePassword',$carrierAgentUser->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>