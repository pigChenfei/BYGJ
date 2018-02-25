<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">拒绝活动申请</h4>
        </div>
        {!! Form::model($carrierActivityAudit, ['route' => ['carrierActivityAudits.update',$carrierActivityAudit->id],'class' => 'form-horizontal','id' => 'playerAccountAdjustCreateForm','method' => 'PATCH']) !!}
        <div class="modal-body" id="playerAccountModalContent">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="pay_channel" class="col-sm-3 control-label">备注</label>
                    <input type="hidden" name="passed" value="0">
                    <div class="col-sm-9">
                        <textarea name="remark" class="form-control" style="resize:none" id="" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('carrierActivityAudits.update',$carrierActivityAudit->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>