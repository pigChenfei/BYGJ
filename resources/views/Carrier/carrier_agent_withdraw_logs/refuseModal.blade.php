<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">拒绝取款申请</h4>
        </div>
        {!! Form::model($agentWithdrawLog, ['route' => ['carrierAgentWithdrawLogs.refuseWithdrawApply', $agentWithdrawLog->id], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            <div class="form-group col-sm-12">
                {!! Form::label('remark', '备注') !!}
                {!! Form::textarea('remark', null, ['class' => 'form-control','style' => 'resize: none;']) !!}
            </div>
            <input type="hidden" name="status" value="{!! \App\Models\Log\CarrierAgentWithdrawLog::STATUS_REFUSED !!}">
            <div class="clearfix">
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierAgentWithdrawLogs.refuseWithdrawApply',$agentWithdrawLog->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>