<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">新建银行卡</h4>
        </div>
        {!! Form::open(['route' => 'carrierBankCards.store']) !!}
        <div class="modal-body" id="modalContent">

            <div class="row">
                @include('Carrier.carrier_bank_cards.fields')
            </div>
        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                {!! TableScript::addFormSubmitAndCancelButtonsScript(Route('carrierBankCards.store')) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
