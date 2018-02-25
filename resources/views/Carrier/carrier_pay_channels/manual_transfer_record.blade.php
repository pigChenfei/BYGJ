<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">手动添加银行转账记录</h4>
        </div>
        {!! Form::open(['route' => 'carrierPayChannels.store']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="form-group col-sm-6">
                    {!! Form::label('transfer_type', '转账类型') !!}
                    <select name="transfer_type" id="transfer_type" class="form-control disable_search_select2" style="width: 100%;">
                        @foreach(\App\Models\CarrierPayChannel::manualAdjustAmountTypeMeta() as $key => $value)
                            <option value="{!! $key !!}">{!! $value !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('fee', '手续费') !!}
                    <input type="number" name="fee" value="0" class="form-control">
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('transfer_out_bank', '转出银行') !!}
                    <?php $payChannels =  \App\Models\CarrierPayChannel::with('payChannel')->get() ?>
                    <select name="transfer_out_bank" id="transfer_type" class="form-control disable_search_select2" style="width: 100%;">
                        <option value="">--请选择--</option>
                    @foreach($payChannels as $payChannel)
                            <option value="{!! $payChannel->id !!}">{!! $payChannel->display_name.'('.$payChannel->payChannel->channel_name.')' !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('transfer_in_bank', '转入银行') !!}
                    <select name="transfer_in_bank" id="transfer_type" class="form-control disable_search_select2" style="width: 100%;">
                        <option value="">--请选择--</option>
                    @foreach($payChannels as $payChannel)
                            <option value="{!! $payChannel->id !!}">{!! $payChannel->display_name.'('.$payChannel->payChannel->channel_name.')' !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('amount', '金额') !!}
                    <input type="number" name="amount" value="0" class="form-control">
                </div>
                {{--<div class="form-group col-sm-6">--}}
                    {{--{!! Form::label('transfer_time', '交易时间') !!}--}}
                    {{--<input type="text" name="transfer_time" class="form-control datetimepicker" placeholder="默认为当前时间" style="border-radius: 0;">--}}
                {{--</div>--}}
                <div class="form-group col-sm-12">
                    {!! Form::label('remark', '备注') !!}
                    <textarea type="text" name="remark" class="form-control" style="resize: none" rows="10"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                {!! TableScript::addFormSubmitAndCancelButtonsScript(Route('carrierPayChannels.newManualTransferRecord')) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(function(){
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
        $('.datetimepicker').datetimepicker({
            format:'yyyy-mm-dd hh:ii:ss',
            language:'zh-CN'
        });
    })
</script>
