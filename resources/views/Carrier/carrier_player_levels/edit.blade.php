<div class="modal-dialog modal-lg" style="min-width: 1280px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑会员等级</h4>
        </div>
        {!! Form::model($carrierPlayerLevel, ['route' => ['carrierPlayerLevels.update', $carrierPlayerLevel->id], 'method' => 'patch']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">
                @include('Carrier.carrier_player_levels.fields')
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierPlayerLevels.update',$carrierPlayerLevel->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>