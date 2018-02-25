<!-- Agent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_id', 'Agent Id:') !!}
    {!! Form::number('agent_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Adjust Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adjust_type', 'Adjust Type:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('adjust_type', false) !!}
        {!! Form::checkbox('adjust_type', '1', null) !!} 1
    </label>
</div>

<!-- Operator Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operator', 'Operator:') !!}
    {!! Form::number('operator', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierAccountAdjusts.index') !!}" class="btn btn-default">Cancel</a>
</div>
