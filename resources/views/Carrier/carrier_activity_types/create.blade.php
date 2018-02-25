<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">新建活动类型</h4>
        </div>
        {!! Form::open(['route' => 'carrierActivityTypes.store']) !!}
        <div class="modal-body" id="modalContent">

            <div class="row">
                @include('Carrier.carrier_activity_types.fields')
            </div>
        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                {!! TableScript::addFormSubmitAndCancelButtonsScript(Route('carrierActivityTypes.store')) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
