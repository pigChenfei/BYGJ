<!-- Act Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('act_id', 'Act Id:') !!}
    {!! Form::number('act_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierActivityAudits.index') !!}" class="btn btn-default">Cancel</a>
</div>
