<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">解绑三方支付</h4>
        </div>
        {!! Form::model($paylist, ['route' => ['carrierPayChannels.unbundPayList', $cid], 'method' => 'patch']) !!}
        <div class="modal-body" id="modalContent">
            是否确定解除三方支付?
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierPayChannels.unbundPayList',$cid)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>