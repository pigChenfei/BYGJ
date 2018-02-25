<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Unreview Deposit Record Limit Field -->
<div class="form-group col-sm-6">
    {!! Form::label('unreview_deposit_record_limit', 'Unreview Deposit Record Limit:') !!}
    {!! Form::number('unreview_deposit_record_limit', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierDepositConfs.index') !!}" class="btn btn-default">Cancel</a>
</div>
