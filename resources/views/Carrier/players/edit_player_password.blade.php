<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">修改{!! $player->user_name !!}的密码</h4>
        </div>
        {!! Form::model($player, ['route' => ['players.updatePlayerLoginPassword', $player->player_id], 'method' => 'patch','id' => 'edit_player_password_form']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">
                <!-- Level Name Field -->
                <div class="form-group col-sm-12">
                    <label for="password">新密码</label>
                    <input title="新密码" name="password" type="password" class="form-control" required>
                </div>

                <!-- Sort Rule Field -->
                <div class="form-group col-sm-12">
                    <label for="password">再次输入密码</label>
                    <input title="再次输入密码" name="password_repeat" type="password" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('players.updatePlayerLoginPassword',$player->player_id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>