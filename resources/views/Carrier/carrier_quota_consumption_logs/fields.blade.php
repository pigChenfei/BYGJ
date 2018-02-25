<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Pay Channel Remain Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_channel_remain_amount', 'Pay Channel Remain Amount:') !!}
    {!! Form::number('pay_channel_remain_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Related Pay Channel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('related_pay_channel', 'Related Pay Channel:') !!}
    {!! Form::number('related_pay_channel', null, ['class' => 'form-control']) !!}
</div>

<!-- Consumption Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('consumption_source', 'Consumption Source:') !!}
    {!! Form::text('consumption_source', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierQuotaConsumptionLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
