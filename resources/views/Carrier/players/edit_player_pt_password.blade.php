<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">修改PT登录密码</h4>
        </div>
        {!! Form::model($player, ['route' => ['players.updatePlayerPTGamePassword', $player->player_id], 'method' => 'patch','id' => 'edit_player_password_form']) !!}
         <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="">请输入密码:</label>
                        <input required minlength="6" name="password" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('players.updatePlayerPTGamePassword',$player->player_id)) !!}
            </div>
        {!! Form::close() !!}
    </div>
</div>