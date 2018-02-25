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

<!-- Bet Player Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_player_count', 'Bet Player Count:') !!}
    {!! Form::number('bet_player_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Bet Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_count', 'Bet Count:') !!}
    {!! Form::number('bet_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_amount', 'Bet Amount:') !!}
    {!! Form::number('bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Win Lose Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('win_lose_amount', 'Win Lose Amount:') !!}
    {!! Form::number('win_lose_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Rebate Financial Flow Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rebate_financial_flow_amount', 'Rebate Financial Flow Amount:') !!}
    {!! Form::number('rebate_financial_flow_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Average Bet Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('average_bet_amount', 'Average Bet Amount:') !!}
    {!! Form::number('average_bet_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Average Bet Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('average_bet_count', 'Average Bet Count:') !!}
    {!! Form::number('average_bet_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('gameWinLoseStastics.index') !!}" class="btn btn-default">Cancel</a>
</div>
