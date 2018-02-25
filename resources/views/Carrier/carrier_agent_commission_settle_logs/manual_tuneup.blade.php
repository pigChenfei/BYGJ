<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">手工调整</h4>
        </div>
        {!! Form::model($carrierAgentCommissionSettleLog, ['route' => ['carrierAgentSettleLogs.saveManualTuneup',$carrierAgentCommissionSettleLog->id],'class' => 'form-horizontal','method' => 'PATCH']) !!}
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="form-group col-sm-12">
                        {!! Form::label('manual_tuneup', '手工调整佣金金额(可以为负数):') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="正数为原佣金+当前值，负数为原佣金-当前值" ></i>
                        {!! Form::text('manual_tuneup', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-group col-sm-12">
                        {!! Form::label('manual_tuneup_rebate', '手工调整洗码金额(可以为负数):') !!}
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="正数为原洗码金额+当前值，负数为原洗码金额-当前值" ></i>
                        {!! Form::text('manual_tuneup_rebate', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierAgentSettleLogs.saveManualTuneup',$carrierAgentCommissionSettleLog->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>