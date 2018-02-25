<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">创建代理结算单</h4>
        </div>
        {!! Form::open(['route' => 'carrierAgentSettleLogs.store']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row" style="align-content: center;">
                <div class="form-group col-sm-12">
                     <?php $settleDic = \App\Models\Log\CarrierAgentSettleLog::settlePeriodsMeta() ?>
                    <select name="type" class="form-control disable_search_select2">
                        @foreach($settleDic as $key => $value)
                                <option value="{!! $key !!}">{!! $value !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                {!! TableScript::addFormSubmitAndCancelButtonsScript(Route('carrierAgentSettleLogs.store')) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    $(function () {
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
    })
</script>    
