<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑游戏</h4>
        </div>
        {!! Form::model($carrierGame, ['route' => ['carrierGames.update', $carrierGame->id], 'method' => 'patch']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">

            @include('Carrier.carrier_games.fields')

            <!-- Submit Field -->
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierGames.update',$carrierGame->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>