<!-- Player Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_id', 'Player Id:') !!}
    {!! Form::number('player_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Plat Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_plat_id', 'Game Plat Id:') !!}
    {!! Form::number('game_plat_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_id', 'Game Id:') !!}
    {!! Form::text('game_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Flow Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_flow_code', 'Game Flow Code:') !!}
    {!! Form::text('game_flow_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Game Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_status', 'Game Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('game_status', false) !!}
        {!! Form::checkbox('game_status', '1', null) !!} 1
    </label>
</div>

<!-- Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_amount', 'Bet Amount:') !!}
    {!! Form::number('bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Company Win Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_win_amount', 'Company Win Amount:') !!}
    {!! Form::number('company_win_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Available Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('available_bet_amount', 'Available Bet Amount:') !!}
    {!! Form::number('available_bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Company Payout Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_payout_amount', 'Company Payout Amount:') !!}
    {!! Form::number('company_payout_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Bet Flow Available Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_flow_available', 'Bet Flow Available:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('bet_flow_available', false) !!}
        {!! Form::checkbox('bet_flow_available', '1', null) !!} 1
    </label>
</div>

<!-- Progressive Bet Field -->
<div class="form-group col-sm-6">
    {!! Form::label('progressive_bet', 'Progressive Bet:') !!}
    {!! Form::number('progressive_bet', null, ['class' => 'form-control']) !!}
</div>

<!-- Progressive Win Field -->
<div class="form-group col-sm-6">
    {!! Form::label('progressive_win', 'Progressive Win:') !!}
    {!! Form::number('progressive_win', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerBetFlowLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
