<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">复审</h4>
        </div>
        {!! Form::model($carrierAgentCommissionSettleLog, ['route' => ['carrierAgentSettleLogs.saveReviewTrial',$carrierAgentCommissionSettleLog->id],'class' => 'form-horizontal','method' => 'PATCH']) !!}
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="form-group col-sm-12">
                        <label>实际发放佣金金额(最多:{!! $carrierAgentCommissionSettleLog->this_period_commission !!})</label>
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="实际发放的佣金金额，剩余的将会结转值下月" ></i>
                        {!! Form::text('actual_payment', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group col-sm-12">
                        <label>实际发放洗码金额(最多:{!! $carrierAgentCommissionSettleLog->rebate_amount !!})</label>
                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="实际发放的洗码金额，剩余的将会结转值下月" ></i>
                        {!! Form::text('actual_payment_rebate', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="form-group col-sm-12">
                        <label>备注</label>
                        {!! Form::textarea('remark', null, ['class' => 'form-control','rows' => 5, 'style' => 'resize:none;']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierAgentSettleLogs.saveReviewTrial',$carrierAgentCommissionSettleLog->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>