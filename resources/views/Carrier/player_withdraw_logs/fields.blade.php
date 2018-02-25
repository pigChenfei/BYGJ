<!-- Order Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('order_number', 'Order Number:') !!}
    {!! Form::text('order_number', null, ['class' => 'form-control']) !!}
</div>

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

<!-- Apply Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apply_amount', 'Apply Amount:') !!}
    {!! Form::number('apply_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Finnally Withdraw Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('finnally_withdraw_amount', 'Finnally Withdraw Amount:') !!}
    {!! Form::number('finnally_withdraw_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Carrier Pay Channel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_pay_channel', 'Carrier Pay Channel:') !!}
    {!! Form::number('carrier_pay_channel', null, ['class' => 'form-control']) !!}
</div>

<!-- Player Bank Card Field -->
<div class="form-group col-sm-6">
    {!! Form::label('player_bank_card', 'Player Bank Card:') !!}
    {!! Form::number('player_bank_card', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Reviewed At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reviewed_at', 'Reviewed At:') !!}
    {!! Form::date('reviewed_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Withdraw Succeed At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('withdraw_succeed_at', 'Withdraw Succeed At:') !!}
    {!! Form::date('withdraw_succeed_at', null, ['class' => 'form-control']) !!}
</div>

<!-- Operator Field -->
<div class="form-group col-sm-6">
    {!! Form::label('operator', 'Operator:') !!}
    {!! Form::number('operator', null, ['class' => 'form-control']) !!}
</div>

<!-- Remark Field -->
<div class="form-group col-sm-6">
    {!! Form::label('remark', 'Remark:') !!}
    {!! Form::text('remark', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('playerWithdrawLogs.index') !!}" class="btn btn-default">Cancel</a>
</div>
