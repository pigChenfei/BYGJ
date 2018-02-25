<!-- Player Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_id', 'Player Id:') !!}
    {!! Form::number('player_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::number('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Finally Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('finally_amount', 'Finally Amount:') !!}
    {!! Form::number('finally_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Benefit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('benefit_amount', 'Benefit Amount:') !!}
    {!! Form::number('benefit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Bonus Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bonus_amount', 'Bonus Amount:') !!}
    {!! Form::number('bonus_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Withdraw Flow Limit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('withdraw_flow_limit_amount', 'Withdraw Flow Limit Amount:') !!}
    {!! Form::number('withdraw_flow_limit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Pay Channel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_channel', 'Pay Channel:') !!}
    {!! Form::number('pay_channel', null, ['class' => 'form-control']) !!}
</div>

<!-- Operate Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operate_time', 'Operate Time:') !!}
    {!! Form::date('operate_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Credential Field -->
<div class="form-group col-sm-6">
    {!! Form::label('credential', 'Credential:') !!}
    {!! Form::text('credential', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerDepositPayLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
