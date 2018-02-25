<!-- Type Id Field -->
<div class="form-group">
    {!! Form::label('type_id', 'Type Id:') !!}
    <p>{!! $bankType->type_id !!}</p>
</div>

<!-- Bank Name Field -->
<div class="form-group">
    {!! Form::label('bank_name', 'Bank Name:') !!}
    <p>{!! $bankType->bank_name !!}</p>
</div>

<!-- Bank Type Field -->
<div class="form-group">
    {!! Form::label('bank_type', 'Bank Type:') !!}
    <p>{!! $bankType->bank_type !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $bankType->updated_at !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $bankType->created_at !!}</p>
</div>

