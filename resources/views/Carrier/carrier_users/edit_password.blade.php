<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">修改密码</h4>
        </div>
        {!! Form::model($carrierUser, ['route' => ['carrierUsers.savePassword', $carrierUser->id], 'method' => 'patch']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">

                <div class="form-group col-sm-12">
                    {!! Form::label('password', '新密码:').Form::required_pin() !!}
                    <input type="password" class="form-control" id="password" name="password" placeholder="请输入新密码">
                </div>

                <div class="form-group col-sm-12">
                    {!! Form::label('confirm_password', '确认密码:').Form::required_pin() !!}
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="两次密码必须一致">
                </div>
                        <!-- Submit Field -->
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierUsers.savePassword',$carrierUser->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>