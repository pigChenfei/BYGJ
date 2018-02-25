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

<!-- Game Plat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('game_plat', 'Game Plat:') !!}
    {!! Form::number('game_plat', null, ['class' => 'form-control']) !!}
</div>

<!-- Bet Times Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_times', 'Bet Times:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('bet_times', false) !!}
        {!! Form::checkbox('bet_times', '1', null) !!} 1
    </label>
</div>

<!-- Rebate Financial Flow Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rebate_financial_flow_amount', 'Rebate Financial Flow Amount:') !!}
    {!! Form::number('rebate_financial_flow_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Bet Flow Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bet_flow_amount', 'Bet Flow Amount:') !!}
    {!! Form::number('bet_flow_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Company Pay Out Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_pay_out_amount', 'Company Pay Out Amount:') !!}
    {!! Form::number('company_pay_out_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Is Already Settled Field -->
<div class="form-group col-sm-6">
    {!! Form::label('is_already_settled', 'Is Already Settled:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('is_already_settled', false) !!}
        {!! Form::checkbox('is_already_settled', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerRebateFinancialFlows.index') !!}" class="btn btn-default">Cancel</a>
</div>
