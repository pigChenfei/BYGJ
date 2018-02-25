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

<!-- Reward Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reward_type', 'Reward Type:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('reward_type', false) !!}
        {!! Form::checkbox('reward_type', '1', null) !!} 1
    </label>
</div>

<!-- Reward Related Player Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reward_related_player', 'Reward Related Player:') !!}
    {!! Form::number('reward_related_player', null, ['class' => 'form-control']) !!}
</div>

<!-- Reward Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reward_amount', 'Reward Amount:') !!}
    {!! Form::number('reward_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Related Player Deposit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('related_player_deposit_amount', 'Related Player Deposit Amount:') !!}
    {!! Form::number('related_player_deposit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Related Player Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('related_player_bet_amount', 'Related Player Bet Amount:') !!}
    {!! Form::number('related_player_bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Related Player Validate Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('related_player_validate_bet_amount', 'Related Player Validate Bet Amount:') !!}
    {!! Form::number('related_player_validate_bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerInviteRewardLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
