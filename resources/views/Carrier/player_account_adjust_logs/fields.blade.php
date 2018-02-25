<!-- Player Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_id', 'Player Id:') !!}
    {!! Form::number('player_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Adjust Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adjust_type', 'Adjust Type:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('adjust_type', false) !!}
        {!! Form::checkbox('adjust_type', '1', null) !!} 1
    </label>
</div>

<!-- Operater Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operater', 'Operater:') !!}
    {!! Form::number('operater', null, ['class' => 'form-control']) !!}
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
    <a href="{!! route('playerAccountAdjustLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
