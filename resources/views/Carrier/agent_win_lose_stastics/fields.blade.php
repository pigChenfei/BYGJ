<!-- Register Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('register_count', 'Register Count:') !!}
    {!! Form::number('register_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Login Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('login_count', 'Login Count:') !!}
    {!! Form::number('login_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Deposit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deposit_amount', 'Deposit Amount:') !!}
    {!! Form::number('deposit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- First Deposit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_deposit_amount', 'First Deposit Amount:') !!}
    {!! Form::number('first_deposit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Deposit Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deposit_count', 'Deposit Count:') !!}
    {!! Form::number('deposit_count', null, ['class' => 'form-control']) !!}
</div>

<!-- First Deposit Count Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_deposit_count', 'First Deposit Count:') !!}
    {!! Form::number('first_deposit_count', null, ['class' => 'form-control']) !!}
</div>

<!-- Withdraw Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('withdraw_amount', 'Withdraw Amount:') !!}
    {!! Form::number('withdraw_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Winlose Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('winlose_amount', 'Winlose Amount:') !!}
    {!! Form::number('winlose_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Bonus Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bonus_amount', 'Bonus Amount:') !!}
    {!! Form::number('bonus_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Rebate Financial Flow Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rebate_financial_flow_amount', 'Rebate Financial Flow Amount:') !!}
    {!! Form::number('rebate_financial_flow_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Deposit Benefit Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deposit_benefit_amount', 'Deposit Benefit Amount:') !!}
    {!! Form::number('deposit_benefit_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Income Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_income', 'Carrier Income:') !!}
    {!! Form::number('carrier_income', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierWinLoseStastics.index') !!}" class="btn btn-default">Cancel</a>
</div>
