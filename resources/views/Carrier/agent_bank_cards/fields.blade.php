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

<!-- Card Account Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_account', 'Card Account:') !!}
    {!! Form::text('card_account', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_type', 'Card Type:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('card_type', false) !!}
        {!! Form::checkbox('card_type', '1', null) !!} 1
    </label>
</div>

<!-- Card Owner Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_owner_name', 'Card Owner Name:') !!}
    {!! Form::text('card_owner_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Birth Place Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_birth_place', 'Card Birth Place:') !!}
    {!! Form::text('card_birth_place', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    <label class="checkbox-inline">
        {!! Form::hidden('status', false) !!}
        {!! Form::checkbox('status', '1', null) !!} 1
    </label>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('agentBankCards.index') !!}" class="btn btn-default">Cancel</a>
</div>
