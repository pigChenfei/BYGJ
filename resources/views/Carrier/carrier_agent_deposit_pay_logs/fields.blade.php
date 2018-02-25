<!-- Pay Order Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_order_number', 'Pay Order Number:') !!}
    {!! Form::text('pay_order_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::number('carrier_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Pay Order Channel Trade Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_order_channel_trade_number', 'Pay Order Channel Trade Number:') !!}
    {!! Form::text('pay_order_channel_trade_number', null, ['class' => 'form-control']) !!}
</div>

<!-- Agent Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('agent_id', 'Agent Id:') !!}
    {!! Form::number('agent_id', null, ['class' => 'form-control']) !!}
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

<!-- Fee Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fee_amount', 'Fee Amount:') !!}
    {!! Form::number('fee_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Pay Channel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pay_channel', 'Pay Channel:') !!}
    {!! Form::number('pay_channel', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Review User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('review_user_id', 'Review User Id:') !!}
    {!! Form::number('review_user_id', null, ['class' => 'form-control']) !!}
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
    <a href="{!! route('carrierAgentDepositPayLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
