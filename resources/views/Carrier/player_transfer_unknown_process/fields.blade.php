<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_id', 'Player Id:') !!}
    {!! Form::number('player_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Account Log Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_account_log', 'Player Account Log:') !!}
    {!! Form::number('player_account_log', null, ['class' => 'form-control']) !!}
</div>

<!-- Limit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('limit_amount', 'Limit Amount:') !!}
    {!! Form::number('limit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Complete Limit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('complete_limit_amount', 'Complete Limit Amount:') !!}
    {!! Form::number('complete_limit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Finished Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_finished', 'Is Finished:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_finished', false) !!}
        {!! Form::checkbox('is_finished', '1', null) !!} 1
    </label>
</div>

<!-- Operator Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operator_id', 'Operator Id:') !!}
    {!! Form::number('operator_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Related Activity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('related_activity', 'Related Activity:') !!}
    {!! Form::number('related_activity', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerWithdrawFlowLimitLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
