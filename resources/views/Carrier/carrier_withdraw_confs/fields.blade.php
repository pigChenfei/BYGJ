<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Day Withdraw Max Sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_day_withdraw_max_sum', 'Player Day Withdraw Max Sum:') !!}
    {!! Form::number('player_day_withdraw_max_sum', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Once Withdraw Max Sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_once_withdraw_max_sum', 'Player Once Withdraw Max Sum:') !!}
    {!! Form::number('player_once_withdraw_max_sum', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Once Withdraw Min Sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_once_withdraw_min_sum', 'Player Once Withdraw Min Sum:') !!}
    {!! Form::number('player_once_withdraw_min_sum', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Day Withdraw Max Sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_day_withdraw_max_sum', 'Agent Day Withdraw Max Sum:') !!}
    {!! Form::number('agent_day_withdraw_max_sum', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Once Withdraw Max Sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_once_withdraw_max_sum', 'Agent Once Withdraw Max Sum:') !!}
    {!! Form::number('agent_once_withdraw_max_sum', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Once Withdraw Min Sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_once_withdraw_min_sum', 'Agent Once Withdraw Min Sum:') !!}
    {!! Form::number('agent_once_withdraw_min_sum', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierWithdrawConfs.index') !!}" class="btn btn-default">Cancel</a>
</div>
