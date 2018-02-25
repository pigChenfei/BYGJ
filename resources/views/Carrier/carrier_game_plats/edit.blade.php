<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑游戏平台</h4>
        </div>
        {!! Form::model($carrierGamePlat, ['route' => ['carrierGamePlats.update', $carrierGamePlat->id], 'method' => 'patch']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">

            @include('Carrier.carrier_game_plats.fields')

            <!-- Submit Field -->
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierGamePlats.update',$carrierGamePlat->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>