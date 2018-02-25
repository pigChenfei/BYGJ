<!-- Smtp  Server Field -->
<div class="form-group col-sm-6">
    {!! Form::label('carrier_id', 'Carrier Id:') !!}
    {!! Form::text('carrier_id', null, ['class' => 'form-control']) !!}
</div>


<!-- Smtp  Server Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp_server', 'Smtp  Server:') !!}
    {!! Form::text('smtp_server', null, ['class' => 'form-control']) !!}
</div>

<!-- Smtp Service Port Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp_service_port', 'Smtp Service Port:') !!}
    {!! Form::number('smtp_service_port', null, ['class' => 'form-control']) !!}
</div>

<!-- Mail Sender Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mail_sender', 'Mail Sender:') !!}
    {!! Form::text('mail_sender', null, ['class' => 'form-control']) !!}
</div>

<!-- Smtp Username Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp_username', 'Smtp Username:') !!}
    {!! Form::text('smtp_username', null, ['class' => 'form-control']) !!}
</div>

<!-- Smtp Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp_password', 'Smtp Password:') !!}
    {!! Form::text('smtp_password', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('carrierPasswordRecoverySiteConfs.index') !!}" class="btn btn-default">Cancel</a>
</div>
