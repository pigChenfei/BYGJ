<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_id', 'Agent Id:') !!}
    {!! Form::number('agent_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Available Member Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('available_member_number', 'Available Member Number:') !!}
    {!! Form::text('available_member_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Plat Win Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_plat_win_amount', 'Game Plat Win Amount:') !!}
    {!! Form::number('game_plat_win_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Available Player Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('available_player_bet_amount', 'Available Player Bet Amount:') !!}
    {!! Form::number('available_player_bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Cost Share Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cost_share', 'Cost Share:') !!}
    {!! Form::number('cost_share', null, ['class' => 'form-control']) !!}
</div>

<!-- Cumulative Last Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cumulative_last_month', 'Cumulative Last Month:') !!}
    {!! Form::number('cumulative_last_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Manual Tuneup Field -->
<div class="form-group col-sm-6">
    {!! Form::label('manual_tuneup', 'Manual Tuneup:') !!}
    {!! Form::number('manual_tuneup', null, ['class' => 'form-control']) !!}
</div>

<!-- This Period Commission Field -->
<div class="form-group col-sm-6">
    {!! Form::label('this_period_commission', 'This Period Commission:') !!}
    {!! Form::number('this_period_commission', null, ['class' => 'form-control']) !!}
</div>

<!-- Actual Payment Field -->
<div class="form-group col-sm-6">
    {!! Form::label('actual_payment', 'Actual Payment:') !!}
    {!! Form::number('actual_payment', null, ['class' => 'form-control']) !!}
</div>

<!-- Transfer Next Month Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transfer_next_month', 'Transfer Next Month:') !!}
    {!! Form::number('transfer_next_month', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Created User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('created_user_id', 'Created User Id:') !!}
    {!! Form::number('created_user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierAgentCommissionSettleLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
