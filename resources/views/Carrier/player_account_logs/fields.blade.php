<!-- Player Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_id', 'Player Id:') !!}
    {!! Form::number('player_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Plat Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_plat_id', 'Game Plat Id:') !!}
    {!! Form::number('game_plat_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Fund Source Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fund_source', 'Fund Source:') !!}
    {!! Form::text('fund_source', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Operator Reviewer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operator_reviewer_id', 'Operator Reviewer Id:') !!}
    {!! Form::number('operator_reviewer_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerAccountLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
