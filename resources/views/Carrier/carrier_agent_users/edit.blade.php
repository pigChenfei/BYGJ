<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑代理</h4>
        </div>
        {!! Form::model($carrierAgentUser, ['route' => ['carrierAgentUsers.update', $carrierAgentUser->id], 'method' => 'patch']) !!}

        <div class="modal-body" id="modalContent">

            <div class="row">

                @include('Carrier.carrier_agent_users.fields')

                        <!-- Submit Field -->
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentUsers.update',$carrierAgentUser->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>